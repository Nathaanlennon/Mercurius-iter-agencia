<?php
include "header.php";

$info_util = $_SESSION;
if (!isset($info_util['id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

// Traitement du formulaire
$queue_dir = "../queue";
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] === 'delete_voyage' && isset($_POST['voyage'])) {
        $voyageToDelete = $_POST['voyage'];

        // Vérifier que le voyage existe et n'est pas payé
        if (isset($_SESSION['voyages'][$voyageToDelete]) && !$_SESSION['voyages'][$voyageToDelete]['payé']) {
            $suppression = [
                'id' => $_SESSION['id'],
                'action' => 'delete_voyage',
                'voyage' => $voyageToDelete
            ];

            $queue_file = $queue_dir . "/" . uniqid("delete_", true) . ".json";
            file_put_contents($queue_file, json_encode($suppression, JSON_PRETTY_PRINT));

            unset($_SESSION['voyages'][$voyageToDelete]);
        }

        exit;
    }

    $nv_info = [
        'id' => $_SESSION['id'],
        'nom' => $_POST['nom'] ?? $_SESSION['nom'],
        'email' => $_POST['email'] ?? $_SESSION['email']
    ];

    if (!filter_var($nv_info['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide.";
        exit;
    }

    $_SESSION['email'] = $nv_info['email'];
    $_SESSION['nom'] = $nv_info['nom'];

    $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
    file_put_contents($queue_file, json_encode($nv_info, JSON_PRETTY_PRINT));
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/profile.css">
    <script>
        globalthis.nomInitial = "<?php echo htmlspecialchars($_SESSION['nom']); ?>";
        globalthis.emailInitial = "<?php echo htmlspecialchars($_SESSION['email']); ?>";
    </script>
    <script src="../js/profile.js"></script>
</head>

<body class="profil">

<h1 class="title">Profil</h1>
<form method="POST" id="profile-form">
    <table>
        <tr>
            <th>Nom</th>
            <td>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_SESSION['nom']); ?>" readonly />
            </td>
            <td>
                <div id="nom-modify-button">
                    <button type="button" onclick="enableNomEditing()">Modifier</button>
                </div>
                <div id="nom-edit-buttons" style="display: none;">
                    <button type="submit">Confirmer</button>
                    <button type="button" onclick="cancelNomEditing()">Annuler</button>
                </div>
            </td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly />
            </td>
            <td>
                <div id="email-modify-button">
                    <button type="button" onclick="enableEmailEditing()">Modifier</button>
                </div>
                <div id="email-edit-buttons" style="display: none;">
                    <button type="submit">Confirmer</button>
                    <button type="button" onclick="cancelEmailEditing()">Annuler</button>
                </div>
            </td>
        </tr>
    </table>
</form>

<?php if (isset($_SESSION['voyages']) && !empty($_SESSION['voyages'])): ?>
    <h2 class="title">Mes Voyages</h2>
    <div id="voyages-container">
    <table id="voyages-table">
        <tr>
            <th>Nom du voyage</th>
            <th>Action</th>
            <th>Etat de payement</th>
        </tr>
        <?php foreach ($_SESSION['voyages'] as $key => $voyage): ?>
            <tr>
                <td><?php echo htmlspecialchars($key); ?></td>
                <td>
                    <button onclick="window.location.href='trip_recap.php?<?php echo htmlspecialchars($voyage["config"]); ?>'">Voir détail</button>
                    <?php if (!$voyage['payé']): ?>
                        <form method="POST" class="delete-voyage-form" style="display:inline;">
                            <input type="hidden" name="action" value="delete_voyage">
                            <input type="hidden" name="voyage" value="<?php echo htmlspecialchars($key); ?>">
                            <button type="submit">Annuler</button>
                        </form>
                    <?php endif; ?>
                </td>
                <td><?php echo $voyage['payé'] ? "payé" : "non payé"; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
<?php else: ?>
    <p>Aucun voyage sélectionné.</p>
<?php endif; ?>

</body>
</html>

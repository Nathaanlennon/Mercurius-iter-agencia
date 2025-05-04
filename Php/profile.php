<?php
include "header.php"; // Inclut l'en-tête de la page, souvent pour la navigation, le style, etc.

$info_util = $_SESSION; // Récupère les informations de l'utilisateur actuel à partir de la session
// Vérifie si l'utilisateur est connecté, sinon il est redirigé vers la page de connexion
if (!isset($info_util['id'])) {
    header("Location: connexion.php");
    exit;
}

// Si la session est vide, redirige vers la page de connexion
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

// Traitement du formulaire d'édition de profil ou de suppression de voyage
$queue_dir = "../queue"; // Dossier où les actions seront mises en file d'attente
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true); // Crée le répertoire de file d'attente s'il n'existe pas
}

// Vérifie si le formulaire a été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si l'action est 'delete_voyage' et que le voyage est spécifié
    if ($_POST['action'] === 'delete_voyage' && isset($_POST['voyage'])) {
        $voyageToDelete = $_POST['voyage']; // Récupère l'identifiant du voyage à supprimer

        // Vérifie que le voyage existe et n'a pas été payé
        if (isset($_SESSION['voyages'][$voyageToDelete]) && !$_SESSION['voyages'][$voyageToDelete]['payé']) {
            // Prépare l'action de suppression à mettre en file d'attente
            $suppression = [
                'id' => $_SESSION['id'],
                'action' => 'delete_voyage',
                'voyage' => $voyageToDelete
            ];

            // Crée un fichier dans la file d'attente pour traiter cette action
            $queue_file = $queue_dir . "/" . uniqid("delete_", true) . ".json";
            file_put_contents($queue_file, json_encode($suppression, JSON_PRETTY_PRINT));

            // Supprime le voyage de la session
            unset($_SESSION['voyages'][$voyageToDelete]);
        }

        // Redirige vers la page de profil après suppression
        header("Location: profile.php");
        exit;
    }

    // Mise à jour des informations du profil de l'utilisateur
    $nv_info = [
        'id' => $_SESSION['id'],
        'nom' => $_POST['nom'] ?? $_SESSION['nom'], // Récupère le nom du formulaire ou garde celui de la session
        'email' => $_POST['email'] ?? $_SESSION['email'] // Récupère l'email du formulaire ou garde celui de la session
    ];

    // Vérification que l'email est valide
    if (!filter_var($nv_info['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide.";
        exit;
    }

    // Mise à jour de l'email et du nom dans la session
    $_SESSION['email'] = $nv_info['email'];
    $_SESSION['nom'] = $nv_info['nom'];

    // Sauvegarde des modifications dans la file d'attente
    $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
    file_put_contents($queue_file, json_encode($nv_info, JSON_PRETTY_PRINT));

    // Redirige vers la page de profil après modification
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="../Css/style.css"> <!-- Lien vers la feuille de style principale -->
    <link rel="stylesheet" href="../Css/profile.css"> <!-- Lien vers la feuille de style spécifique au profil -->
    <script>
        // Variables JavaScript pour initialiser le nom et l'email de l'utilisateur
        const nomInitial = "<?php echo htmlspecialchars($_SESSION['nom']); ?>";
        const emailInitial = "<?php echo htmlspecialchars($_SESSION['email']); ?>";
    </script>
    <script src="../js/profile.js"></script> <!-- Script pour la gestion de l'édition de profil -->
</head>

<body class="profil"> <!-- Classe pour le corps de la page profil -->

<h1 class="title">Profil</h1>
<form method="POST"> <!-- Formulaire pour la mise à jour du profil -->
    <table>
        <tr>
            <th>Nom</th>
            <td>
                <!-- Champ pour afficher le nom de l'utilisateur -->
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_SESSION['nom']); ?>" readonly />
            </td>
            <td>
                <!-- Bouton pour activer l'édition du nom -->
                <div id="nom-modify-button">
                    <button type="button" onclick="enableNomEditing()">Modifier</button>
                </div>
                <!-- Boutons pour confirmer ou annuler la modification du nom -->
                <div id="nom-edit-buttons" style="display: none;">
                    <button type="submit">Confirmer</button>
                    <button type="button" onclick="cancelNomEditing()">Annuler</button>
                </div>
            </td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td>
                <!-- Champ pour afficher l'email de l'utilisateur -->
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly />
            </td>
            <td>
                <!-- Bouton pour activer l'édition de l'email -->
                <div id="email-modify-button">
                    <button type="button" onclick="enableEmailEditing()">Modifier</button>
                </div>
                <!-- Boutons pour confirmer ou annuler la modification de l'email -->
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
    <table>
        <tr>
            <th>Nom du voyage</th>
            <th>Action</th>
            <th>Etat de paiement</th>
        </tr>
        <?php foreach ($_SESSION['voyages'] as $key => $voyage): ?>
            <tr>
                <td><?php echo htmlspecialchars($key); ?></td>
                <td>
                    <!-- Bouton pour voir les détails du voyage -->
                    <button onclick="window.location.href='trip_recap.php?<?php echo htmlspecialchars($voyage["config"]); ?>'">Voir détail</button>
                    <?php if (!$voyage['payé']): ?>
                        <!-- Formulaire pour annuler le voyage si non payé -->
                        <form method="POST" style="display:inline;">
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
<?php else: ?>
    <p>Aucun voyage sélectionné.</p>
<?php endif; ?>

</body>
</html>

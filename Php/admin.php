<!DOCTYPE html>
<?php

include "header.php";

$queue_dir = "queue";
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}
$fichier = "utilisateurs.json";
$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$info_util = $_SESSION;

if (!isset($info_util['role']) || $info_util['role'] !== "admin") {
    header("Location: index.php");
    exit;
}

$utilisateurs_par_page = 10;
$total_utilisateurs = count($utilisateurs);


if (isset($_GET['id_specific'])) {
    $id_specific = $_GET['id_specific'];
    $utilisateurs = array_filter($utilisateurs, fn($user) => strpos($user['id'], $id_specific) !== false);
}

$total_utilisateurs = count($utilisateurs);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);


$debut = ($page - 1) * $utilisateurs_par_page;
$utilisateurs_page = array_slice($utilisateurs, $debut, $utilisateurs_par_page);

$total_pages = ceil($total_utilisateurs / $utilisateurs_par_page);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['maj_role'])) {
        $util_id = $_POST['util_id'];
        $nv_role = $_POST['role'];

        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        $nv_info = [
            'id' => $util_id,
            'role' => $nv_role,
        ];
        file_put_contents($queue_file, json_encode($nv_info, JSON_PRETTY_PRINT));
        header("Location: admin.php");
        exit;
    }
}

?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administrateur</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/admin.css">
</head>
<body>
<div class="content">

    <form method="GET">
        <label>Id spécifique : <input type="text" name="id_specific" maxlength="9" value="<?= isset($id_specific) ? htmlspecialchars($id_specific) : '' ?>"></label>
        <button type="submit">Envoyer</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Etat</th>
        </tr>
        <?php
        foreach ($utilisateurs_page as $util):
            ?>
            <tr>
                <td><?= htmlspecialchars($util['id']) ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="util_id" value="<?= htmlspecialchars($util['id']) ?>">
                        <select name="role">
                            <option value="Normal" <?= $util['role'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
                            <option value="VIP" <?= $util['role'] == 'VIP' ? 'selected' : '' ?>>VIP</option>
                            <option value="Banni" <?= $util['role'] == 'Banni' ? 'selected' : '' ?>>Banni</option>
                        </select>
                        <button type="submit" name="maj_role">Modifier</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&id_specific=<?= isset($id_specific) ? htmlspecialchars($id_specific) : '' ?>">Précédent</a>
        <?php endif; ?>

        <span>Page <?= $page ?> sur <?= $total_pages ?></span>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>&id_specific=<?= isset($id_specific) ? htmlspecialchars($id_specific) : '' ?>">Suivant</a>
        <?php endif; ?>
    </div>

</div>
</body>
</html>

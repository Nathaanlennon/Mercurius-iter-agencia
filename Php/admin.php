<!DOCTYPE html>
<?php

include "header.php";

$queue_dir = "../queue";
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);//crée le fichier de queue s'il n'existe pas
}
$fichier = "../json/utilisateurs.json";//charge le fichier json

$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : []; //si pas possible, on obtient un tableau vide

$info_util = $_SESSION;

if (!isset($info_util['role']) || $info_util['role'] !== "admin") {
    header("Location: index.php"); //redirection vers index si l'utilisateur n'est pas connecté ou admin
    exit;
}
$utilisateurs = array_filter($utilisateurs, fn($user) => $user['role'] !== 'admin'); //on cache les admins

$utilisateurs_par_page = 10;
$total_utilisateurs = count($utilisateurs);

if (isset($_GET['id_specific'])) {
    $id_specific = $_GET['id_specific'];
    $utilisateurs = array_filter($utilisateurs, fn($user) => strpos($user['id'], $id_specific) !== false);//on filtre les utilisateurs par id
}

$total_utilisateurs = count($utilisateurs);//nombre d'utilisateur

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);


$debut = ($page - 1) * $utilisateurs_par_page;
$utilisateurs_page = array_slice($utilisateurs, $debut, $utilisateurs_par_page);

$total_pages = ceil($total_utilisateurs / $utilisateurs_par_page);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {//envoi requête de changement de rôle sous forme json dans queue/
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
        <label>Id spécifique : <input type="text" name="id_specific" id="recherche" maxlength="9" value="<?= isset($id_specific) ? htmlspecialchars($id_specific) : '' ?>"></label>
        <button type="submit">Envoyer</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Etat</th>
        </tr>
        <?php
        foreach ($utilisateurs_page as $util):
            ?>
            <tr>
                <td><?= htmlspecialchars($util['id']) ?></td>
                <td><?= htmlspecialchars($util['email']) ?></td>
                <td>
                    <form method="post" class="change-form">
                        <input type="hidden" name="util_id" value="<?= htmlspecialchars($util['id']) ?>">
                        <label>
                            <select name="role">
                                <option value="Normal" <?= $util['role'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
                                <option value="VIP" <?= $util['role'] == 'VIP' ? 'selected' : '' ?>>VIP</option>
                                <option value="Banni" <?= $util['role'] == 'Banni' ? 'selected' : '' ?>>Banni</option>
                            </select>
                        </label>
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
<script src="../JavaScript/admin.js"></script>



</body>
</html>

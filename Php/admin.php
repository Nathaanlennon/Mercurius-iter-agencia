<!DOCTYPE html>
<?php

include "header.php"; // Inclusion de l'en-tête (vérification de session, menu, etc.)

$queue_dir = "../queue"; // Répertoire où seront stockés les fichiers de modification
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true); // Création du dossier s'il n'existe pas
}

$fichier = "../json/utilisateurs.json"; // Chemin vers le fichier JSON contenant les utilisateurs

// Chargement des utilisateurs depuis le fichier JSON ou tableau vide si inexistant
$utilisateurs = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

$info_util = $_SESSION; // Récupération des données de session (utilisateur connecté)

// Vérifie si l'utilisateur est bien un administrateur, sinon redirige vers la page d'accueil
if (!isset($info_util['role']) || $info_util['role'] !== "admin") {
    header("Location: index.php");
    exit;
}

// Exclut les administrateurs de la liste à afficher
$utilisateurs = array_filter($utilisateurs, fn($user) => $user['role'] !== 'admin');

$utilisateurs_par_page = 10; // Nombre d'utilisateurs à afficher par page
$total_utilisateurs = count($utilisateurs); // Total des utilisateurs après filtre

// Filtrage si un identifiant spécifique est fourni en GET
if (isset($_GET['id_specific'])) {
    $id_specific = $_GET['id_specific'];
    $utilisateurs = array_filter($utilisateurs, fn($user) => strpos($user['id'], $id_specific) !== false);
}

$total_utilisateurs = count($utilisateurs); // Recalcule après filtrage

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Page actuelle
$page = max(1, $page); // Empêche les pages < 1

$debut = ($page - 1) * $utilisateurs_par_page; // Index de départ pour le découpage
$utilisateurs_page = array_slice($utilisateurs, $debut, $utilisateurs_par_page); // Découpage de la liste

$total_pages = ceil($total_utilisateurs / $utilisateurs_par_page); // Calcul du nombre total de pages

// Traitement du formulaire POST (modification du rôle utilisateur)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['maj_role'])) {
        $util_id = $_POST['util_id'];
        $nv_role = $_POST['role'];

        // Préparation du fichier de mise à jour dans le répertoire queue
        $queue_file = $queue_dir . "/" . uniqid("user_", true) . ".json";
        $nv_info = [
            'id' => $util_id,
            'role' => $nv_role,
        ];
        // Écriture des données dans un fichier JSON pour traitement différé
        file_put_contents($queue_file, json_encode($nv_info, JSON_PRETTY_PRINT));
        header("Location: admin.php"); // Redirection pour éviter le renvoi de formulaire
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

    <!-- Formulaire pour rechercher un utilisateur par son ID -->
    <form method="GET">
        <label>Id spécifique : <input type="text" name="id_specific" maxlength="9" value="<?= isset($id_specific) ? htmlspecialchars($id_specific) : '' ?>"></label>
        <button type="submit">Envoyer</button>
    </form>

    <!-- Tableau des utilisateurs -->
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
                    <!-- Formulaire pour changer le rôle de l'utilisateur -->
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

    <!-- Pagination -->
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
<script src="../js/admin.js"></script>

</body>
</html>

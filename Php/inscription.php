<?php
include "header.php"
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'inscription</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/auth.css">
</head>

<body>
<div class="content">
    <form>
        <table>
            <tr>
                <th colspan="2">Formulaire d'inscription</th>
            <tr>
                <td>Nom :</td>
                <td><label for="nom"></label><input type="text" id="nom" name="nom"/></td>
            </tr>
            <tr>
                <td>Prenom :</td>
                <td><label for="first-name"></label><input type="text" id="first-name" name="first-name"/></td>
            </tr>
            <tr>
                <td>E-mail :</td>
                <td><label for="email"></label><input type="text" id="email" name="email"/></td>
            </tr>
            <tr>
                <td>confirmation de l'e-mail :</td>
                <td><label for="valid-email"></label><input type="text" id="valid-email" name="valid-email"/></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><label for="password"></label><input type="text" id="password" name="password"/></td>
            </tr>
            <tr>
                <td> Confirmation du mot de passe :</td>
                <td><label for="valid-password"></label><input type="text" id="valid-password" name="valid-password"/></td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Inscription</button>
                    <br></td>
            </tr>
        </table>
    </form>

</div>
</body>

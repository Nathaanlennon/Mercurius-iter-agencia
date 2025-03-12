<?php
include "header.php"
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../Css/style.css">
    <link rel="stylesheet" href="../Css/auth.css">
</head>


<body>

<div class="content">
    <form>
        <table>
            <tr>
                <th colspan="2">Formulaire de connexion</th>
            <tr>
                <td>E-mail :</td>
                <td><label for="email"></label><input type="text" id="email" name="email"/></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><label for="password"></label><input type="text" id="password" name="password"/></td>
            </tr>
            <tr>
                <td colspan="2" id="button">
                    <button type="submit">Connexion</button>
                    <br></td>
            </tr>
        </table>
    </form>
</div>
</body>

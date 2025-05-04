#!/usr/bin/php
<?php
// Répertoire contenant les fichiers de la file d'attente
$queue_dir = "../queue";
// Fichier principal des utilisateurs
$fichier_utilisateurs = "../json/utilisateurs.json";

// Fonction de fusion de deux tableaux associatifs
function array_fusion($array1, $array2)
{
    // Parcours des éléments du deuxième tableau
    foreach ($array2 as $key => $value) {
        // Si la clé existe dans le premier tableau
        if (array_key_exists($key, $array1)) {
            // Si les deux valeurs sont des tableaux, on fusionne récursivement
            if (is_array($array1[$key]) && is_array($value)) {
                $array1[$key] = array_fusion($array1[$key], $value);
            } else {
                // Sinon, on remplace la valeur dans le premier tableau par celle du second
                $array1[$key] = $value;
            }
        } else {
            // Si la clé n'existe pas, on l'ajoute au premier tableau
            $array1[$key] = $value;
        }
    }
    return $array1; // Retourne le tableau fusionné
}

// Si le répertoire de la queue n'existe pas, on arrête le script
if (!is_dir($queue_dir)) {
    exit();
}

// Chargement du fichier des utilisateurs, sinon un utilisateur par défaut est créé
$utilisateurs = file_exists($fichier_utilisateurs) ? json_decode(file_get_contents($fichier_utilisateurs), true) : [['id' => 0, 'nom' => 'admin', 'email' => 'admin', 'password' => password_hash('000', PASSWORD_DEFAULT), 'role'=>'admin']];

// Recherche tous les fichiers JSON dans le répertoire de la queue
$files = glob($queue_dir . "/*.json");

// Boucle infinie pour traiter les fichiers dans la queue
while (true) {
    // Recherche tous les fichiers JSON dans la queue
    $files = glob($queue_dir . "/*.json");

    // Si aucun fichier n'est trouvé, on attend 5 secondes avant de réessayer
    if (empty($files)) {
        echo "Aucune requête en attente. Pause...\n";
        sleep(5);
        continue;
    }

    // Parcours de tous les fichiers dans la queue
    foreach ($files as $file) {
        // Décodage du fichier JSON
        $data = json_decode(file_get_contents($file), true);

        // Si les données du fichier ne sont pas valides, on le supprime
        if (!$data) {
            unlink($file);
            continue;
        }

        // Si l'ID n'est pas égal à 0, c'est un utilisateur existant à mettre à jour
        if ($data["id"] != 0) {
            // On parcourt les utilisateurs pour trouver celui avec le même ID
            foreach ($utilisateurs as &$util) {
                // Si on trouve l'utilisateur, on le met à jour avec les nouvelles données
                if ($util['id'] == $data['id']) {
                    $util = array_fusion($util, $data);
                    echo "Modification de l'utilisateur ID: " . $data['id'] . "\n";
                    break; // On arrête de chercher une fois l'utilisateur trouvé et modifié
                }
            }
        } else {
            // Si l'ID est égal à 0, c'est un nouvel utilisateur à ajouter
            end($utilisateurs);
            // On attribue un nouvel ID en fonction du dernier utilisateur existant
            $data["id"] = key($utilisateurs) + 1;
            // On ajoute l'utilisateur dans le tableau
            $utilisateurs[] = $data;
            echo "Nouvel utilisateur ajouté, ID: " . $data['id'] . "\n";
        }

        // On supprime le fichier après l'avoir traité
        unlink($file);
    }

    // On réécrit le fichier principal des utilisateurs avec les modifications
    file_put_contents($fichier_utilisateurs, json_encode($utilisateurs, JSON_PRETTY_PRINT));

    // Message de fin de traitement de la queue
    echo "Traitement de la queue terminé.";

    // On attend 5 secondes avant de commencer à traiter à nouveau
    sleep(5);
}
?>

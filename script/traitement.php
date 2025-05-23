#!/usr/bin/php
<?php
$queue_dir = "../queue";
if (!file_exists($queue_dir)) {
    mkdir($queue_dir, 0777, true);
}
$fichier_utilisateurs = "../json/utilisateurs.json";

function array_fusion($array1, $array2)
{
    foreach ($array2 as $key => $value) {
        if (array_key_exists($key, $array1)) {
            if (is_array($array1[$key]) && is_array($value)) {
                $array1[$key] = array_fusion($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        } else {

            $array1[$key] = $value;
        }
    }
    return $array1;
}
if (!is_dir($queue_dir)) {
    exit();
}

$utilisateurs = file_exists($fichier_utilisateurs) ? json_decode(file_get_contents($fichier_utilisateurs), true) : [['id' => 0, 'nom' => 'admin', 'email' => 'admin', 'password' => password_hash('000', PASSWORD_DEFAULT), 'role'=>'admin']];
$files = glob($queue_dir . "/*.json");

while (true) {
    $files = glob($queue_dir . "/*.json");
    if (empty($files)) {
        echo "Aucune requête en attente. Pause...\n";
        sleep(5);
        continue;
    }

    foreach ($files as $file) {

        $data = json_decode(file_get_contents($file), true);

        if (!$data) {
            unlink($file);
            continue;
        }
        if ($data["id"] != 0 ) {
            foreach ($utilisateurs as &$util) {
                if ($util['id'] == $data['id']) {

                    if (isset($data['action']) && $data['action'] === 'delete_voyage' && isset($data['voyage'])) {
                        $voyage = $data['voyage'];
                        if (isset($util['voyages'][$voyage])) {
                            unset($util['voyages'][$voyage]);
                            echo "Voyage '$voyage' supprimé pour l'utilisateur ID: " . $data['id'] . "\n";
                        } else {
                            echo "Voyage '$voyage' introuvable pour l'utilisateur ID: " . $data['id'] . "\n";
                        }
                    } else {
                        $util = array_fusion($util, $data);
                        echo "Modification de l'utilisateur ID: " . $data['id'] . "\n";
                    }
                    break;
                }
            }

        } elseif(isset($data['nom'])) {
            end($utilisateurs);
            $data["id"] = key($utilisateurs) + 1;
            $utilisateurs[] = $data;
            echo "Nouvel utilisateur ajouté, ID: " . $data['id'] . "\n";

        }
        unlink($file);
    }
    file_put_contents($fichier_utilisateurs, json_encode($utilisateurs, JSON_PRETTY_PRINT));
    echo "Traitement de la queue terminé.";
    sleep(5);
}
?>
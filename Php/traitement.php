<?php
$queue_dir = "queue";
$fichier_utilisateurs = "utilisateurs.json";

if (!is_dir($queue_dir)) {
    exit();
}

$utilisateurs = file_exists($fichier_utilisateurs) ? json_decode(file_get_contents($fichier_utilisateurs), true) : [ ['id'=>0,'nom'=>'admin','email'=>'admin', 'password'=>password_hash('000', PASSWORD_DEFAULT)]];
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
        if ($data["id"] != 0) {
            echo "modif";
            foreach ($utilisateurs as &$util) {
                if ($util['id'] == $data['id']) {
                    echo $data['id'];
                    echo $data['nom'];
                    $util = array_merge($util, $data);
                    echo "Modification de l'utilisateur ID: " . $data['id'] . "\n";
                    break; //
                }
            }

        } else {
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
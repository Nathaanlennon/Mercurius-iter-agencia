# Mercurius-iter-agencia

lancer avant tout sur linux la commande dans le terminal pour lancer le script du traitement en arrière plan : nohup php traitement.php > /dev/null 2>&1 &

pour arretez le script entrer : pkill -f traitement.php

Pour acceder aux utilisateur normaux lors de la connexion : le mot de passe est l'initiale du nom et celle du prenom ( exemple : manon lefort -> mot de passe : lm ) : 

email : admin@gmail.com
mdp : 000

email: mp@gmail.com
mdp : mp

email : dp@gmail.com
mdp : dp

email : ap@gmail.com
mdp : ap

email : admin2@gmail.com
mdp : 000

email : op@gmail.com
mdp : op

pour acceder aux administrateurs, le mot de passe est : 000

ATTENTION : si vous accedez a traitement.php via le lien, vous entrerez dans une boucle infini faisant ainsi buguer le site. Appuyer sur la croix de chargement pour annuler

### Les bases de données :
Elles sont en .json

Voyages : les voyages sont composés de l'id du voyage, le nom, le prix minimum pour une personne, la durée, une liste de toutes les étapes et une liste des mots clés.

La base de donnee utilisateurs.json contient les nom prenom email mot de passe et etat de chaque utilisateur. l'etat est par defaut a normal, sauf si l'admin le change, l'etat banni l'interdit de se connecter

### L'identifiant des transactions : 

Les transactions ont un identifiant de 15 caractères hexa ; le premier correspond au type de transaction (normale ou panier) ; le deuxieme le nombre de chiffres de l'id ; les m suivants l'id de l'utilisateur ; ensuite le nombre de chffres de l'id du voyage ; les n suivants l'id du voyage ; le reste est completé aléatoirement

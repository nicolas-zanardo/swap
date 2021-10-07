# SWAP
___

view demo https://swap.nicolas-zanardo.com/

Pour se connecter au compte admin sur le web
login : niko32@gmail.fr
password : Aa123456789*

Tout les utilisateurs on le même mot de passe mais sont initialisé a un ROLE_USER


Avant de lancer l'application il est important de creeer le vichier de variable PDO dans le dossier env

```php
   #./env/Variable.php
   # le dossier est accompagné d'un fichier exemple
   
   <?php

/**
 * Vous devez créer une classe variable pour PDO
 */
class Variable
{
    protected $DNS = "mysql:host=localhost;dbname=SWAP";
    protected $DB_USER = "admin";
    protected $DB_PWD = "admin";
} 
```

### -> *Important* 

- Lorsqu'un utilisateur s'inscrit, l'application créer un dossier avec le numéro de son id_membre pour mettre les photos de ses annonces

    ex : ./public/images/annonces/ID_MEMBRE/ID-ANNONCE-N°PHOTO-NAMEPHOTO.ext

         ./public/images/annonces/114/98-1-voiture.jpg

- Dans le dossier DOC il y a les fichiers SQL et MCD pour la base de donnée
- Les utilisateurs s'enregitre par default en ROLE_USER avec le status 0


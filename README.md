PollMe
======

Projet réalisé dans le cadre du cours de développement web de 3ème année de
licence informatique à l'université d'Aix-Marseille.

Le [sujet](http://pageperso.lif.univ-mrs.fr/~bertrand.estellon/index.php?n2=35)
du projet est disponible sur le site de Mr. Estellon, qui enseigne la matière.


Installation
============


## Vendors

Certains composants tiers ont été utilisés :

  * [pimple](http://pimple.sensiolabs.org/)
  * [l'event dispatcher de Symfony](https://github.com/symfony/EventDispatcher)
  * [le parseur Yaml de Symfony](https://github.com/symfony/yaml)
  * [Twig](http://twig.sensiolabs.org/)
  * [Kunststube Router](https://github.com/K-Phoen/Kunststube-Router) (patché pour l'occasion)

Les commandes suivantes permettent de les installer :

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```


## Base de données

Par défaut, les identifiants sont les suivants :

  * hôte : localhost
  * base de données : poll_me
  * utilisateur : poll_me
  * mot de passe : poll_me

Ces valeurs sont définies dans le fichier `app/config/config.yml`.

La configuration peut aussi être altérée en définissant (dans le virtualhost par
exemple) les variables suivantes :

  * hôte : $_SERVER['dbHost']
  * base de données : $_SERVER['dbBd']
  * utilisateur : $_SERVER['dbLogin']
  * mot de passe : $_SERVER['dbPass']

Les tables seront créées automatiquement lors de la première visite.


## VirtualHost

Voici un exemple de configuration d'un virtualhost permettant de faire
fonctionner le site :

```apache
<VirtualHost *:80>
  ServerName poll.me

  DocumentRoot /home/vagrant/www/poll.me/web
  <Directory /home/vagrant/www/poll.me/web>
    AllowOverride All
    Order allow,deny
    allow from all
  </Directory>
</VirtualHost>
```

Un .htaccess gère la réécriture d'URL et le changement du DirectoryIndex.


## Vagrant box

La branche _vagrant_ contient la configuration de la box Vagrant utilisée pour
le développement du projet. Peu de modifications devraient être nécessaires pour
la réutiliser (modifier les occurences de "kevin", cloner le code du projet dans
~/www/poll.me/)


Tests
=====


Les tests peuvent être lancés via la commande suivante :

```bash
./vendor/bin/phpunit
```

Pour que cela fonctionne, les vendors doivent avoir été installés en mode
"développeur" :

```bash
$ php composer.phar install --dev
```


Architecture générale
=====================


L'architecture générale est la même que [Symfony](http://www.symfony.com).

La partie "framework" du code est constituée d'un Kernel HTTP et d'un Kernel applicatif.
Le kernel HTTP a pour unique objectif de transformer une requête en réponse.
Le kernel applicatif, lui, décrit le coeur de l'application. On y retrouve des informations sur la gestion de la
configuration, du cache, de l'injection de dépendance, etc.

Le cycle de vie (simplifié) d'une requête serait le suivant:

 1. le serveur reçoit la requête et construit un objet la représentant ;
 2. cette requête est transmise au kernel applicatif ;
 3. ce dernier démarre : il initialise le conteneur d'injection de dépendance, les évènements et le kernel HTTP ;
 4. la requête est transmise au kernel HTTP ;
 5. l'objectif étant de transformer cette requête en réponse, il recherche un contrôleur pouvant réaliser cette tâche;
 6. le kernel HTTP retourne la réponse générée par le contrôleur.

Bien sûr, on ignore ici la gestion du routing, du templating, de la session, de la base de données, etc.

La session, le templating et l'accès à la base de données sont des services accessibles via le conteneur
d'injection de dépendances (Pimple).

La gestion du routing et la recherche du contrôleur à utiliser sont implémentés à l'aide d'événements lancés à la
fois par le kernel applicatif et HTTP. On initialise par exemple le routing lors du boot du kernel applicatif et
on recherche le contrôleur une fois la requête passée au kernel HTTP.

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

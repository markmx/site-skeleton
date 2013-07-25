Symfony Site Skeleton
=====================

A project offering an almost completely pre-configured working Symfony2 Web site, allowing you to focus
immediately on developing your actual application.

Includes:

* Symfony2
* FOSUserBundle
* BcBootstrapBundle (Twitter Bootstrap/Jquery)
* KnpMenuBundle

View the Site Skeleton in action on my [free hosting account](http://site-skeleton.eu1.frbit.net) (thanks to [fortrabbit](http://fortrabbit.com/) for providing it).


Pre-requisites
--------------
LAMP stack with a suitably privileged MySQL user account and [Composer](http://getcomposer.org/) in your shell path.


Installation
------------

1. Clone this repository setting your project directory name and cd into it.

        Example:
        $ cd /var/www (or wherever Apache's document root is on your system)
        $ git clone git@github.com:markmx/site-skeleton.git foobar && cd foobar

2. Name the skeleton with your own PHP namespace and bundle name for the project.

        Example:
        $ bash bin/name-skeleton.sh Acme Foobar

3. Copy the parameters sample file and set details to your requirements.

        $ cp app/config/parameters.sample.yml app/config/parameters.yml

4. Install required bundles.

        $ composer install

5. Set file permissions depending on your OS distribution (see [Installing and Configuring Symfony](http://symfony.com/doc/current/book/installation.html)).

        Example:
        $ sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs
        $ sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

6. Create your database.

        $ php app/console doctrine:database:create
        $ php app/console doctrine:schema:update --force

7. Remove Git and start afresh with your own Git repository.

        $ rm -rf .git && git init && git add . && git commit -m 'Initial commit'

8. View your running skeleton application and begin developing it.

        http://localhost/foobar/web/app_dev.php

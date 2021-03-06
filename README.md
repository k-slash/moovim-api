moovim-api
==========

MoovIm a new way to discover movies

Welcome to the back side of MoovIm website based on Symfony v2.5.

Go to your project directory.

## WARNING
To install apache, php and mysql correctly, follow this link:
http://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-10-yosemite/

Add to your virtualhost this code:
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} ^(.*)
    RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
Work in Virtualhost tag not in Directory tag
For exemple, edit the file /etc/apache2/extra/httpd-vhosts.conf:
<VirtualHost *:80>
    DocumentRoot "/Users/victorkbidi/Sites/"
    ServerName localhost
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} ^(.*)
    RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
    ErrorLog "/private/var/log/apache2/k-slash.local-error_log"
</VirtualHost>


## Quick start

### Composer

1. Install Composer `curl -sS https://getcomposer.org/installer | php`
2. Download the vendors `php composer.phar install`

### NodeJS

1. [http://nodejs.org/download/](Download NodeJS installer)
2. Install NodeJS

### Gulp

Gulp is a streaming build system. It helps you to do repetitive tasks such as: minify JS files, compile LESS to CSS files...

1. Install gulp: `sudo npm install -g gulp`
2. Type: `gulp &` in the project directory before starting to develop

### Authentication Google OAuth 2

1. Create a new google project at: https://console.developers.google.com/project
2. Go to APIs & auth -> Credentials, click Create new Client ID and enter the url of your website
3. It will create a client id and a client secret
4. Copy/paste this values to src/MoovIm/UserBundle/Resources/config/services.yml


### Database

Create a new SQL database called `moovim` collated in `utf8_unicode_ci`.

### Import the database's structure

`php app/console doctrine:schema:update --force`

### Load data set

First drop the schema: `php app/console doctrine:schema:drop --force`.
Second re-create the schema: `php app/console doctrine:schema:create`.
And then load the data: `php app/console doctrine:fixtures:load`.

### Troubles with cache and log directories

Remove old directories:

```
#!shell
rm -rf app/cache/*
```
```
#!shell
rm -rf app/logs/*
```

Fix rights:

```
#!shell
HTTPDUSER="ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1"
```
```
#!shell
sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```
```
#!shell
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```

## Coding standards

Respect the standard issue [PSR-2](http://www.php-fig.org/psr/psr-2/ "PSR-2"). Check with [PHP-CS-Fixer](https://github.com/fabpot/PHP-CS-Fixer "PHP-CS-Fixer").
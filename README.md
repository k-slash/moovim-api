moovim-api
==========

MoovIm a new way to discover movies

Welcome to the back side of MoovIm website based on Symfony v2.5.

Go to your project directory.

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

### Upload directory

Create a directory called `content`.

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
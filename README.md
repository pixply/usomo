# Docker startup project for Wordpress Development with Composer

## Installation
### 1. Clone project
`git clone git@github.com:pixply/wordpress-docker-composer.git`

### 2. Run docker compose
`docker compose up -d`

### 3. Run composer install
`composer install`

### 4. Run postinstall.sh
`chmod +x postinstall.sh && ./postinstall.sh`
This will create a config file for wordpress, install the database, setup migration etc.


## 5. Add custom Plugins and Themes via local composer file
Add plugins to the composer.local.json and run:

`composer update --lock`

## Start project
- Add any SQL to install on the system to `install.sql`
- Destroy with `docker-compose down -v`

#!/bin/bash

# This script will install the basic wordpress database

# Helpers and variables
yesnoinput=( "Y" "y" "")

# Import .env file
source .env

echo "This script will setup database, sync and wordpress settings."

echo -n "Create basic wordpress configuration file? [Y/n]:"
read createwpconfig

if [[ " ${yesnoinput[*]} " == *" ${createwpconfig} "* ]]
then
  cp ./config/wordpress/sample-config.php ./public/local-config.php
  sed -i.bak "s/dbpass/${MYSQL_PASSWORD}/" ./public/local-config.php && rm ./public/local-config.php.bak
  sed -i.bak "s/dbuser/${MYSQL_USER}/" ./public/local-config.php && rm ./public/local-config.php.bak
  sed -i.bak "s/dbname/${MYSQL_NAME}/" ./public/local-config.php && rm ./public/local-config.php.bak
else
  echo "Skipping wordpress configuration file. You need to create it manually."
fi


echo -n "Install basic Wordpress database? [Y/n]: "
read installdatabase

if [[ " ${yesnoinput[*]} " == *" ${installdatabase} "* ]]
then
  echo "Installing Wordpress DB..."
  docker-compose run --rm wpcli wp core install --url=${LOCAL_URL} --path="/var/www/html/wp" --title="${SITE_TITLE}" --admin_user=${WORDPRESS_ADMIN_USER} --admin_password=${WORDPRESS_ADMIN_PASSWORD} --admin_email=${WORDPRESS_ADMIN_EMAIL} --skip-themes --skip-plugins
else
  echo "Skipping wordpress installation."
fi


echo -n "Activate WP Migrate DB Pro plugin for sync with prod? [Y/n]: "
read activatewpmigrate

if [[ " ${yesnoinput[*]} " == *" ${activatewpmigrate} "* ]]
then
  echo "Activating base, cli and media files plugin..."
  docker-compose run --rm wpcli wp plugin activate wp-migrate-db-pro
  docker-compose run --rm wpcli wp plugin activate wp-migrate-db-pro-cli
  docker-compose run --rm wpcli wp plugin activate wp-migrate-db-pro-media-files
  echo "Adding the WP Migrate DB Pro license key"
  docker-compose run --rm wpcli wp migratedb setting update license ab5625f5-2c83-4a37-a8f5-53f492fd6d93
else
  echo "Skipping WP Migrate DB Pro activation."
fi


echo -n "Set hosturl and siteurl for nested wp installation? [Y/n]: "
read sethostsite

if [[ " ${yesnoinput[*]} " == *" ${sethostsite} "* ]]
then
  echo "Setting hosturl and siteurl in wp_options..."
  docker-compose run --rm wpcli wp option update siteurl ${LOCAL_URL}/wp
  docker-compose run --rm wpcli wp option update home ${LOCAL_URL}
else
  echo "Skipping hosturl and siteurl."
fi
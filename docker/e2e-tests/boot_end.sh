#!/bin/sh

sudo -u www-data composer require it-bens/shopware-code-based-plugin-configuration
sudo -u www-data php bin/console plugin:refresh
sudo -u www-data php bin/console plugin:install --activate --clearCache TestPlugin
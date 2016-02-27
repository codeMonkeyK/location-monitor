1. Install LAMP stack and composer - I am running on VM Ubuntu 14.04

2. Install Yii2 in apache host directory via composer - for me /var/www/html
	
	composer global require "fxp/composer-asset-plugin:~1.1.1"
	composer require yiisoft/yii2

	* NOTE - I had to add SWAP space for this to install correctly:
		sudo swapon -s
	* NOTE - I named my application location-monitor

3. Update apache configuration to allow override for 'pretty URLs' - mine is  /etc/apache2/sites-available/000-default.conf
	<VirtualHost *:80>
		...
		ServerAdmin webmaster@localhost
		DocumentRoot /var/www/html
		<Directory /var/www/html>
			Options Indexes FollowSymLinks MultiViews
			AllowOverride All
			Order allow,deny
			allow from all
		</Directory> 
		...
	</VirtualHost>

4. Populate your local mysql database by copy/pasting contents of Populate_DB.txt in the mysql shell after authenticating.
	Database: location_db
	Tables: locations
		logs

5. Configure Yii2 db connections to access the database. In location-monitor/config/db.php

	return [
    		'class' => 'yii\db\Connection',
    		'dsn' => 'mysql:host=localhost;dbname=location_db',
    		'username' => 'root',
    		'password' => '',
    		'charset' => 'utf8',
	];

	* NOTE - Update your username and password for your local mysql authentication

6. To check the database was set up correctly, verify the output.
	Locations Data: http://localhost/location-monitor/web/locations
	Logs Data: http://localhost/location-monitor/web/logs

7. Install Google Chart widget
	composer require scotthuangzl/yii2-google-chart "dev-master"

8. Install GeoIp package
	composer require lysenkobv/yii2-geoip "~1.0"

9. Homepage: http://localhost/location-monitor/web/logs/lookup

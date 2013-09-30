update AbstractModel.php and module/Myapp/src/Myapp/Model/AbstractModel.php
with your database credentials.

add the following to your httpd.conf where applicant.localhost is defined in 
your /etc/hosts file.  DocumentRoot and Directory will need to be adjusted 
to wherethe applicant_project directory is located.

{{<VirtualHost applicant.localhost:80>
    ServerName applicant.localhost
    DocumentRoot /var/www/html/applicant_project/public
    SetEnv APPLICATION_ENV "development"
    <Directory /var/www/html/applicant_project/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
}}

if you receive the error:
[Mon Sep 30 00:55:37 2013] [error] [client 127.0.0.1] PHP Fatal error:  Uncaught exception 'RuntimeException' with message 'Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.' in /var/www/html/applicant_project/init_autoloader.php:48\nStack trace:\n#0 /var/www/html/applicant_project/public/index.php(14): require()\n#1 {main}\n  thrown in /var/www/html/applicant_project/init_autoloader.php on line 48

this command might help:
cd /var/www/html/applicant_project
php composer.phar install


to run the application 
http://applicant.localhost/myapp/view?id=2

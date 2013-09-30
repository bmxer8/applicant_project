update AbstracttModel.php and module/Myapp/src/Myapp/Model/AbstractModel.php
with your database credentials.

add the following to your httpd.conf where applicant.localhost is defined in 
your /etc/hosts file.  DocumentRoot and Directory will need to be adjusted 
to wherethe applicant_project directory is located.

<VirtualHost applicant.localhost:80>
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


if you receive the error:


cd /var/www/html/applicant_project
php composer.phar install


to run the application 
http://applicant.localhost/myapp/view?id=2
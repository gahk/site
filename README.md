# Site
## How to run the site in your local environment

**Run local copy of database:**  
1. Go to one.com > Login > Control Panel > Advanced settings > PHP and databse settings  
2. Technical Information > PhpMyAdmin > gahk_dk  
3. Export as SQL to download the database in a sql script  
4. Download MySQL and MySQL Workbench (or equivalent)  
5. Create MySql database 'gahk_dk' with user: 'gahk_dk' password: 'keldogfrederik'  


**Run website:**  
``git pull https://github.com/gahk/site.git``  
``cd site``  
edit file *application/config/database.php*  
change: `$db['default']['hostname'] = 'localhost';`  
to: `$db['default']['hostname'] = 'localhost:3306';` (or whatever port you use)  
serve website:  
``php -S localhost:8080``  


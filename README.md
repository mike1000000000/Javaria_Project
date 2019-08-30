# The Javaria Project

The Javaria Project aims to be an open-source tool published under the ‘Creative Commons Attribution-ShareAlike 4.0 International Public License‘ (https://creativecommons.org/licenses/by-sa/4.0/) to view charts for users and their teams.

Works in a LAMP environment. 

Installation is easy - simply clone the files into your Apache server www root directory and copy config.php_example to config.php and input the following variables:

    $CFG->DB_SERVER = 'www.example.com';
    $CFG->DB_USERNAME = 'username';
    $CFG->DB_PASSWORD = 'password';
    $CFG->DB_DATABASE = 'database-name';
    $CFG->DB_PORT = 'database-port';

    $CFG->path = '/path/to/www/root';
  
    $CFG->logfilepath = '/path/to/log/directory/';
    $CFG->logfileprefix = 'logfile.name';


Reload server (typically 'service apache2 reload') and from the CLI and www-root run:

    php admin/install.php
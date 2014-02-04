### Requirements ###

    Server:
    - PHP 5.2 or higher (recent stable build recommended)
    - MySQL 4 or higher
    
    It is recommended to run Collabtive on LAMP (Linux, Apache, MySQL, PHP) servers.
    Windows Servers may work, but are not supported as well.
    
    Client:
    - Firefox 3.6, Internet Explorer 7/8/9, Opera 9/10, Safari, Chrome
    - JavaScript enabled
    - Cookies enabled


### Installation instructions ###

    1. Unpack the Collabtive archive on your computer.
    2. Upload everything, including the empty folders ./files and ./templates_c, to your server.
	   (Alternatively you can create ./templates_c and ./files manually before installation.)
	3. Make the following folders & files writable (chmod 777):
	   - ./config/standard/config.php
	   - ./files
	   - ./templates_c
	4. Create a new MySQL database.
	5. Point your browser to install.php and follow the instructions given.
	6. If the installation was successful, delete install.php and update.php.
	7. Disable the writing permissions for ./config/standard/config.php (chmod 755).


### Update instructions ###

    1. Unpack the Collabtive Archive
    2. Retrieve your config.php from your server
    3. Put your config.php in the folder /config/standard/, replacing the blank one.
    4. Upload everything to your server, replacing any old Collabtive files
    5. Point your browser to update.php.
	6. If the update was successful, delete install.php and update.php.


### License conditions ###

    Collabtive is free software under the terms and conditions of the
    GNU General Public License (GPL) (Version 3).
    Please see license.txt for full licensing terms and conditions.


### Credits ###

    - Collabtive is (c) Philipp Kiszka
    - Project coordination and support by Eva Kiszka
    - Artwork by Marcus Fröhner
    - Icons partially taken from the Oxygen iconset
    - Many locales translated by various contributors
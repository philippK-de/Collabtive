### Voraussetzungen ###

    Server:
	- PHP 5.5, PHP 7 oder neuer
	- MySQL 5 oder neuer, SQLite 3

	Wir empfehlen die Verwendung eines LAMP-Servers (Linux, Apache, MySQL, PHP).
	Windows-Server (IIS) können ebenfalls funktionieren, werden aber nicht im gleichen Ausmaß unterstützt.

    Client:
    - Firefox, Internet Explorer 10/11, MS Edge, Safari, Chrome
	- JavaScript aktiviert
	- Cookies aktiviert


### Installationsanweisungen ###

    1. Entpacke das Collabtive-Archiv auf deinem Computer.
    2. Lade alle Dateien und Ordner auf deinen Server hoch, auch die leeren Ordner ./files und ./templates_c.
       (Alternativ können die Ordner vor der Installation manuell auf dem Server erstellt werden.)
    3. Mache die folgenden Dateien und Ordner beschreibbar (chmod 777):
    	- ./config/standard/config.php
		- ./files
		- ./templates_c
	4. Erzeuge eine neue MySQL-Datenbank  (Kollation: utf8_general_ci).
	5. Öffne install.php im Browser und folge den Anweisungen.
	6. Wenn die Installation erfolgreich war, lösche install.php und update.php.
	7. Setze die Schreibrechte für ./config/standard/config.php zurück (chmod 755).


### Update ###
Vor dem Update sollte ein Backup der Datenbank und des /files/ Ordners erstellt werden.

    1. Entpacke das Collabtive-Archiv auf deinem Computer.
    2. Lade die bestehende Datei config.php aus dem Ordner ./config/standard/ vom Server runter.
    3. Kopiere die heruntergeladene Datei in den Ordner ./config/standard/ des entpackten Archivs.
    4. Lade alle Dateien und Ordner des entpackten Archivs auf den Server hoch.
    5. Öffne update.php im Browser.
    6. Wenn das Update erfolgreich war, lösche install.php und update.php.


### Lizenz ###

    Collabtive ist freie Software unter den Bedingungen der GNU General Public license (GPL) Version 3.


### Credits ###

    - Collabtive ist (c) Philipp Kiszka
    - Projektkoordination: Eva Kiszka
    - Artwork: Marcus Fröhner
    - Icons zum Teil aus dem Iconset Oxygen
    - Viele Übersetzungen von Community-Mitgliedern

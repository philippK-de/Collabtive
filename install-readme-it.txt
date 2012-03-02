### Requisiti ###
Server:
- PHP 5.1 o superiore (PHP 5.2 o superiore raccomandato)
- MySQL 4 o superiore
Si raccomanda di far girare Collabtive su un server LAMP (Linux, Apache, MySQL, PHP).
Server Windows potrebbero funzionare, ma non sono supportati.

Client:
- Firefox 3, Internet Explorer 7/8, Opera, Safari
- Javascript abilitato


### Istruzioni di installazione ###
	 1. Estrarre l'archivio;
	 2. Caricare tutto, incluse le cartelle vuote /files e /templates_c, nel server;
	   (Alternativamente dovrete creare le cartelle /templates_c e /files manualmente
		prima dell'installazione.)
    3. Rendere scrivibili le seguenti cartelle e files:
	   - /templates_c
		- /files
		- /config/standard/config.php
	 4. Creare un nuovo database MySQL per Collabtive;
	 5. Visitare la pagina install.php e seguire le istruzioni fornite;
	 6. Se l'installazione va a buon fine, eliminare i files install.php e update.php.

### Istruzione per l'Update  ###
	 1. Estrarre l'archivio;
	 2. Prelevare il file config.php dal vostro server;
	 3. Mettere il vostro config.php nella cartella /config/standard/, rimpiazzando quello vuoto;
	 4. Caricare tutto sul server, rimpiazzando tutti i vecchi files di Collabtive;
	 5. Visitare la pagina update.php;
	 6. Se l'update va a buon fine, elimiare install.php e update.php.

### Condizioni di Licenza ###
Collabtice è free software secondo i termini e le condizioni della
GNU General Public License (GPL) (Versione 3).
Pregasi visionare licese.txt per tutti i termini e le condizioni
della licenza.

### Crediti ###
- Collabtive è (c) Philipp Kiszka
- Coordinatore di progetto e supporto: Eva Kranz
- Grafica: Marcus Fröhner
- Le icone sono prese in parte dall'iconset di Oxygen.
- Le versioni localizzate sono mantenute da diversi contribuenti:
   - Francese: mantenuto da Jean-Christophe Breboin (www.fairytree.fr)
	- Italiano: mantenuto da Edoardo Stefani
	- Bulgaro: mantenuto da Jordan Hlebarov
	- Cinese: mantenuto da Hu Yanggang
	- Polacco: mantenuto da Hubert Miazek, Jakub Dyda e Maciej Smolinski
	- Serbo: mantenuto da Vladimir Mincev
	- Turco: mantenuto da Mustafa Sarac

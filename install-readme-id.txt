### Kebutuhan Minimum Instalasi ###
Server:
- PHP 5.2 keatas (Direkomendasikan build stabil terakhir)
- MySQL 4 keatas

Direkomendasikan untuk menjalankan Collabtive pada server LAMP(Linux, Apache, MySQL, PHP).
Aplikasi ini mungkin dapat dijalankan pada server Windows. Tetapi belum didukung untuk saat ini.

Peramban:
- Firefox 3.6, Internet Explorer 7/8/9, Opera 9/10, Safari, Chrome
- Javascript diaktifkan

### Petunjuk Instalasi ###
    1. Ekstrak arsip.
	 2. Unggah seluruh file, termasuk folder kosong /files dan /templates_c kedalam server anda.
	 	 (Anda dapat membuat sendiri folder /files dan templates_c pada server anda jika diinginkan.)
	 3. Berikan hak akses tulis (writable) pada folder dan file berikut:
		- /templates_c
		- /files
		- /config/standard/config.php
	4. Buat database MySQL baru untuk digunakan dalam Collabtive  (collation: utf8_general_ci).
	5. Arahkan peramban browser anda ke install.php dan ikuti petunjuk instalasinya.
	6. Bila instalasi berhasil dilakukan, silahkan hapus file install.php dan update.php


### Petunjuk update ###
	 1. Ekstrak arsip Collabtive.
	 2. Backup dan simpan file config.php anda dari server.
	 3. Taruh file config.php pada folder /config.standard/, untuk menimpa file config.php kosong.
	 4. Unggah seluruh file update baru kedalam server dan menimpa file-file Collabtive yang lama.
	 5. Arahkan peramban anda ke update.php.
	 6. Bila proses update telah berhasil dilakukan, silahkan hapus file install.php dan update.php


### Ketentuan Lisensi ###
Collabtive merupakan software bebas yang berada dalam syarat dan ketentuan yang berlaku 
pada GNU General Public License (GPL) (Version 3).
Silahkan baca file license.txt untuk syarat dan kondisi yang berlaku.


### Kredit ###
- Hak cipta Collabtive ada pada Philipp Kiszka
- Dukungan dan Koordinasi Project oleh Eva Kiszka
- Artwork oleh Marcus Fröhner
- Sebagian dari citra icon diambil dari Oxygen Iconset.
- Berbagai macam bahasa dikelola oleh banyak kontributor:
   - Bahasa Perancis dikelola oleh Jean-Christophe Breboin (www.fairytree.fr)
	- Bahasa Bulgaria dikelola oleh Jordan Hlebarov
	- Bahasa Cina dikelola oleh Hu Yanggang
	- Bahasa Polandia dikelola oleh Hubert Miazek, Jakub Dyda and Maciej Smolinski
	- Bahasa Serbia dikelola oleh Vladimir Mincev
	- Bahasa Turki dikelola oleh Mustafa Sarac
	- Bahasa Indonesia dikelola oleh Doni Marshall

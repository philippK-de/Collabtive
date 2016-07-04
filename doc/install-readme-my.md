### Keperluan Minima sebelum Pemasangan ###
Server:
- PHP 5.2 keatas (Disyorkan build stabil yang terakhir)
- MySQL 4 keatas

Disarankan untuk menlaksanakan Collabtive pada server LAMP(Linux, Apache, MySQL, PHP).
Aplikasi ini mungkin dapat dilaksana pada server Windows tetapi belum sepenuhnya didukung pada waktu ini.

Keperluan Pelayar Internet:
- Firefox 3.6, Internet Explorer 7/8/9, Opera 9/10, Safari, Chrome
- Javascript diaktifkan

### Arahan Pemasangan ###
  1. Ekstrak file arkib.
  2. Muat naik seluruh fail, termasuk direktori kosong `/files` dan `/templates_c` kedalam server anda. (Anda dibenarkan membuat sendiri direktori `/files` dan `/templates_c` pada server.)
  3. Benarkan hak akses tulis pada folder dan fail berikut:
  	- `/templates_c`
  	- `/files`
  	- `/config/standard/config.php`
  4. Buat database MySQL baru untuk bagi kegunaan Collabtive  (collation: utf8_general_ci).
  5. Arahkan pelayar internet anda ke install.php dan ikut arahan yang tertera.
  6. Apabila pemasangan sudah siap, sila hapuskan fail install.php dan juga update.php

### Arahan Kemaskini ###
	 1. Ekstrak fail arkib Collabtive.
	 2. Backup dan simpan fail `config.php` yang diperoleh server.
	 3. Letakkan fail `config.php` pada folder `/config/standard/`, untuk menggantikan fail `config.php` yang kosong.
	 4. Muatnaik seluruh fail-fail *update* terbaru kedalam server dan gantikannya dengan fail-fail Collabtive yang lama.
	 5. Arahkan pelayar anda ke `update.php`.
	 6. Bila proses update telah siap, sila hapuskan fail `install.php` dan `update.php`

### Ketentuan Lesen ###
Collabtive merupakan perisian bebas yang berada dalam syarat dan ketentuan yang berlaku pada GNU General Public License (GPL) (Version 3).
Sila semak fail `license.txt` untuk syarat dan terma.

### Kredit ###
- Hak cipta Collabtive adalah milik sepenuhnya Philipp Kiszka
- Sokongan dan Koordinasi Project oleh Eva Kiszka
- Karya Seni oleh Marcus Fröhner
- Sebahagian citra ikon diambil dari Oxygen Iconset.
- Pelbagai bahasa yang dikendali oleh penggiat dari seantero dunia:
  - Bahasa Perancis diusahakan oleh Jean-Christophe Breboin (www.fairytree.fr)
  - Bahasa Bulgaria diusahakan oleh Jordan Hlebarov
  - Bahasa Cina diusahakan oleh Hu Yanggang
  - Bahasa Polandia diusahakan oleh Hubert Miazek, Jakub Dyda dan Maciej Smolinski
  - Bahasa Serbia diusahakan oleh Vladimir Mincev
  - Bahasa Turki diusahakan oleh Mustafa Sarac
  - Bahasa Indonesia diusahakan oleh Doni Marshall
  - Bahasa Melayu digiatkan oleh [Abdul Muhaimin](https://github.com/infacq)

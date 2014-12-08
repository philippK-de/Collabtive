### Rendszerkövetelmények ###
Szerver:
- PHP 5.2 vagy újabb (Legújabb stabil verzió ajánlott)
- MySQL 4 vagy újabb

Ajánlott a Collabtive telepítése LAMP (Linux, Apache, MySQL, PHP) szervereken.
Windows szervereken is futtatható, de egyenlőre nem támogatott.

Kliens:
- Firefox 3.6, Internet Explorer 7/8/9, Opera 9/10, Safari, Chrome
- Javascript engedélyezés


### Telepítési útmutató ###
    1. Csomagolja ki az állományt.
    2. Töltsön fel mindent, beleértve az üres /files és /templates_c könyvtárakat a szerverére.
	   (Előfordulhat, hogy kézzel kell létrehoznia a /templates_c és /files könyvtárakat telepítés előtt.)
	3. Adjon írási jogot a következő könyvtárakra és fájlokra:
		- /templates_c
		- /files
		- /config/standard/config.php
	4. Hozzon létre egy új MySQL adatbázist a Collabtive-nek  (collation: utf8_general_ci).
	5. Hívja be a böngészőjébe az install.php-t és kövesse a megjelenő utasításokat.
	6. A telepítést követően törölje az install.php és az update.php fájlokat.


### Frissítési útmutató ###
    1. Csomagolja ki a Collabtive állományt.
    2. Töltse le a saját config.php fájlját a szerveréről.
    3. Másolja be a letöltött config.php fájlt a /config/standard/ könyvtárba lecserélve a benne levő üreset.
    4. Töltsön fel mindent szerverére, lecserélve az összes ott levő régi fájlt.
    5. Hívja be a böngészőjébe az update.php-t.
	6. Amennyiben a frissítés sikeresen lefutott, törölje az install.php és update.php fájlokat.


### Licensz feltételek ###
Collabtive egy ingyenes szoftver a GNU General Public License (GPL) (Version 3) alatt.
Kérjük, olvassa el a teljes license.txt-t a részletes feltételekről.


### Közreműködők ###
- Collabtive (c): Philipp Kiszka
- Projekt koordinátor és támogatás: Eva Kiszka
- Dizájn: Marcus Fröhner
- Ikonok egy része az Oxygen iconset-ből származik.
- A különböző nyelvi fájlokban közreműködtek:
	- Bolgár nyelv: Jordan Hlebarov
    - Francia nyelv: Jean-Christophe Breboin (www.fairytree.fr)
	- Kínai nyelv: Hu Yanggang
	- Lengyel nyelv: Hubert Miazek, Jakub Dyda and Maciej Smolinski
	- Magyar nyelv: Németh Balázs
	- Szerb nyelv: Vladimir Mincev
	- Török nyelv: Mustafa Sarac

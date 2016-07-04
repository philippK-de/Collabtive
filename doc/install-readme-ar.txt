### متطلبات التشغيل ###
الملقم
- PHP 5.1 أو أحدث (يفضل أحدث إصدار مستقر)
- MySQL 4 أو أعلى

العميل
- أحدث متصفح يدعم JS/DOM ( اُختبر علي Firefox 2/3, Internet Explorer 7, Opera 9, Konqueror, Safari)
- إتاحة الكوكيز


### تعليمات التنصيب ###
    1. فك ضغط ملف Collabtive المضغوط.
    2.  قم برفع كافة الملفات والمجلدات, شامل مجلدي /files و /templates_c إلى ملقمك
	   (يمكنك إنشاء المجلدين /templates_c و /files يدوياً قبل التنصيب.)
	3. امنح الملفات والمجلدات التالية صلاحيات القراءة والكتابة:
		- /templates_c
		- /files
		- /config/standard/config.php
	4. قم بإنشاء قاعدة ييانات MySQL جديدة لـ Collabtive.  (collation: utf8_general_ci)
	5. وجه المتصفح إلى العنوان yourdomain.com/path/to/Collabtive/install.php واتبع التعليمات في معالج التنصيب.
	6. بعد انتهاء التنصيب، قم بحذف install.php و update.php


### تعليمات التحديث ###
    1. فك ضغط ملف Collabtive المضغوط.
    2. قم بتحميل ملف config.php من المسار path/to/Collabtive/config/standard/config.php
    3. انسخ الملف وألصقه مكان ملف config.php الفارغ في المجلد الذي قمت بفك ضغطه.
    4. قم برفع الملفات والمجلدات إلى الملقم مستبدلاً الملفات القديمة.
    5. وجه متصفحك إلى path/to/Collabtive/update.php
	6. بعد انتهاء التنصيب، قم بحذف install.php و update.php.


### شروط الرخصة ###
Collabtive هو برنامج مجاني مرخص برخصة GPL الإصدار الثالث.
الرجاء قراءة license.txt للإطلاع على كافة شروط الرخصة.


### حقوق وعرفان ###
- Collabtive هو ملك لـ (c) Philipp Kiszka
- تنسيق المشروع المشروع والدعم بواسطة Eva Kiszka
- الصور والرسوميات بواسطة Marcus Fröhner
- الأيقونات مأخوذة جزئياً من مجموعة أيقونات Oxygen.
- يشرف على ترجمة التطبيق:
    - الفرنسية يشرف/يقوم بها Jean-Christophe Breboin (www.fairytree.fr) و Nilo (www.nilostudio.fr)
	- المجرية يشرف/يقوم بها Ferenc Forgács
	- الإيطالية يشرف/يقوم بها Lephio (www.lephio.org) and Edoardo Stefani
	- اليابانية يشرف/يقوم بها  Yamamoto Shoot
	- الإسبانية يشرف/يقوم بها Efrain Barcena
	- البلغارية يشرف/يقوم بها Jordan Hlebarov
	- الصينية يشرف/يقوم بها Hu Yanggang
	- التشيكية يشرف/يقوم بها Jan Hanzelka
	- البولندية يشرف/يقوم بها Hubert Miazek, Jakub Dyda و Maciej Smolinski
	- الروسية يشرف/يقوم بها Danil Pridvorov
	- الصربية يشرف/يقوم بها Vladimir Mincev
	- التركية يشرف/يقوم بها Mustafa Sarac
	- العربية يشرف/يقوم بها Cubex Solutions - www.cubexco.com
		+ راجع الترجمة العربية iTranslate Unlimited - www.i-translate.info
		+ الترجمة العربية مبنية على جهود عبدالله علي (www.mesklinux.org) السابقة

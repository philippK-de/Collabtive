### 安装软件要求 ###

    服务器端:
    - PHP 5.2 或更高版本 (推荐使用最新稳定版本)
    - MySQL 4 或更高版本
    
    建议将Collabtive运行在LAMP(Linux, Apache, MySQL, PHP)服务器上。
    Collabtive可以在Windows系统上运行，但不会得到技术支持。
    
    客户端:
    - Firefox 3.6, Internet Explorer 7/8/9, Opera 9/10, Safari, Chrome
    - 启用JavaScript
    - 启用Cookies


### 安装指南 ###

    1. 解压Collabtive压缩包到计算机。
    2. 上传所有文件至服务器，包括./files 和./templates_c这两个空文件夹。
	   (你也可以选择在安装结束后手动创建这些文件夹)
	3. 确保一下文件和文件夹有写的权限。(chmod 777):
	   - ./config/standard/config.php
	   - ./files
	   - ./templates_c
	4. 新建一个新的MySQL数据库a。 (collation: utf8_general_ci)
	5. 在你的浏览器中打开install.php，然后根据提示安装。
	6. 安装成功后，请删除install.php和update.php。
	7. 剥夺./config/standard/config.php写的权限(chmod 755)。


### 升级指南 ###

    1. 解压Collabtive压缩包到计算机。
    2. 从你的服务器端获得config.php文件。
    3. 将config.php放至/config/standard/文件夹，从而覆盖原有空白的config.php文件.
    4. 上传所有文件至服务器，覆盖所有旧文件。
    5. 在你的浏览器中打开update.php。
	6. 升级成功后，请删除install.php文件。


### 授权条款 ###

    Collabtive是在GNU 通用公共授权(GPL) (第三版)条款下发布的免费软件。
    请查阅license.txt中的完整条款与条件。


### 开发人员 ###

    - Collabtive版权归Philipp Kiszka所有
    - 项目协调和产品支持由Eva Kiszka负责
    - 美工Marcus Fröhner完成
    - 部分图标来自the Oxygen iconset
    - 大多本地化文件有多位译者译制
        此简体中文版本译者larsenlouis, https://github.com/larsenlouis

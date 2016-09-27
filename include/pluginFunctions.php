<?php
// Autoload requires classes on new class()
function cl_plugins_autoload($class_name)
{
    $path = CL_ROOT . "/plugins/plugin." . $class_name . ".php";
    if (file_exists($path)) {
        return require_once($path);
    }
    return false;
}

spl_autoload_register('cl_plugins_autoload');

/*
 * Interface describing methods each plugin must implement
 */
interface collabtivePlugin
{
    //method that installs the plugin into the plugin system. only installed plugins can be activated.
    public function install();
    //bind the plugin to  smarty
    public function activate();
    //deactivate the plugin
    public function deactivate();
    //replace the <!--hook--> by a {smarty tag}, associated with a function
    public static function activateMainHook($source, Smarty_Internal_Template $localTemplateObj);
    //default function to call for the {smarty tag} registered for the plugin
    public static function getMainTemplate($params, Smarty_Internal_Template $localTemplateObj);

}

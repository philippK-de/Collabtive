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

interface collabtivePlugin
{
    public function install();
    public function bindPlugin();

    public static function filter($source, Smarty_Internal_Template $localTemplateObj);

    public static function getTemplate($params, Smarty_Internal_Template $localTemplateObj);

}

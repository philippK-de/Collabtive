<?php

class test implements collabtivePlugin
{
    private static $pluginHooks = [
        "main" => "testplugin",
        "second" => "testpluginTwo"
    ];

    private static $pluginTemplates = [
        "main" => "test.tpl"
    ];

    private $filterFunctions = ["test::activateMainHook", "test::activateSecondHook"];

    public function install()
    {
        // TODO: Implement install() method.
    }

    function activate()
    {
        global $pluginManager;
        $templateTags = [
            ["tag" => test::$pluginHooks["main"]],
            ["tag" => test::$pluginHooks["second"]]
        ];
        $pluginManager->registerPlugin($templateTags, "test");
        $pluginManager->registerHook($this->filterFunctions);
    }
    public function deactivate(){

    }

    /*
     * Implement the main plugin hook
     *
     * This function is registered as a smarty pre-filter
     * It is passed the unprocessed smarty template source, as well as an object representing the current template
     * @implements collabtivePlugin
     * @param str $source String representing the unmodified Smarty template, as read from the .tpl files
     * @param obj $localTemplateObj Object representing the template
     */
    static function activateMainHook($source, Smarty_Internal_Template $localTemplateObj)
    {
        return preg_replace("/<!--" . test::$pluginHooks["main"] . "-->/i", "{" . test::$pluginHooks["main"] . "}", $source);
    }

    static function activateSecondHook($source, Smarty_Internal_Template $localTemplateObj)
    {
        return preg_replace("/<!--" . test::$pluginHooks["second"] . "-->/i", "{" . test::$pluginHooks["second"] . "}", $source);
    }

    /*
     * Implement the main template. Each plugin has to have a main template
     * This returns the string that is inserted for the {hook} created with activateHeaderHook.
     * The return value is inserted into the $localTemplateObj at compile time
     *
     * This function is registered as a smarty plugin.
     * It is passed parameters, as well as an object representing the current template
     * @implements collabtivePlugin
     * @param str $params Parameters bound to the plugin
     * @param obj $localTemplateObj Object representing the template
     */
    static function getMainTemplate($params, Smarty_Internal_Template $templateObj)
    {
        global $template;

        $taskObj = new project();
        $allTask = $taskObj->getMyProjects($_SESSION["userid"]);

        $template->assign("irgendwas", $allTask);

        return $template->fetch(CL_ROOT . "/plugins/templates/" . test::$pluginTemplates["main"]);
    }
}


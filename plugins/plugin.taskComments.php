<?php

class taskComments implements collabtivePlugin
{
    private static $pluginHooks = [
        "main" => "taskCommentsplugin",
        "second" => "taskCommentspluginTwo"
    ];

    private static $pluginTemplates = [
        "main" => "taskComments.tpl"
    ];

    private $filterFunctions = ["taskComments::activateMainHook", "taskComments::activateSecondHook"];

    public function install()
    {
        // TODO: Implement install() method.
    }

    function activate()
    {
        global $pluginManager;
        $templateTags = [
            ["tag" => taskComments::$pluginHooks["main"]],
            ["tag" => taskComments::$pluginHooks["second"]]
        ];
        $pluginManager->registerPlugin($templateTags, "taskComments");
        $pluginManager->registerHook($this->filterFunctions);
    }
    public function deactivate(){

    }

     public function getCommentsByTask($id){
         return "taskComments " . $id;
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
        return preg_replace("/<!--" . taskComments::$pluginHooks["main"] . "-->/i", "{" . taskComments::$pluginHooks["main"] . "}", $source);
    }

    static function activateSecondHook($source, Smarty_Internal_Template $localTemplateObj)
    {
        return preg_replace("/<!--" . taskComments::$pluginHooks["second"] . "-->/i", "{" . taskComments::$pluginHooks["second"] . "}", $source);
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

        return $template->fetch(CL_ROOT . "/plugins/templates/" . taskComments::$pluginTemplates["main"]);
    }
}


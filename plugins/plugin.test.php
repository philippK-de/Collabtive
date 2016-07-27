<?php

class test implements collabtivePlugin
{
    const templateFile = "test.tpl";
    const templateTag = "testplugin";

    function __construct()
    {

    }

    function bindPlugin()
    {
        global $plugins;
        $plugins->registerPlugin($this::templateTag, "test");
    }

    static function filter($source, Smarty_Internal_Template $localTemplateObj)
    {
        return preg_replace("/<!--" . test::templateTag . "-->/i", "{" . test::templateTag . "}", $source);
    }

    static function getTemplate($params, Smarty_Internal_Template $templateObj)
    {

        global $template;

        $taskObj = new project();
        $allTask = $taskObj->getMyProjects($_SESSION["userid"]);

        $template->assign("irgendwas", $allTask);

        return $template->fetch(CL_ROOT . "/plugins/templates/" . test::templateFile);
    }
}


<?php

class test
{
    const templateFile = "test.tpl";
    const templateTag = "testplugin";

    function __construct()
    {
        $pluginsManager = new plugins();
        $pluginsManager->registerPlugin($this::templateTag, "test::getIrgendwelcheListen");
    }

    static function getIrgendwelcheListen($params, Smarty_Internal_Template $templateObj)
    {
        global $template;

        $taskObj = new project();
        $allTask = $taskObj->getMyProjects($_SESSION["userid"]);

        $template->assign("irgendwas", $allTask);

        return $template->fetch(CL_ROOT . "/plugins/templates/" . test::templateFile);
    }


}


<?php

class test implements collabtivePlugin
{
    const templateFile = "test.tpl";
    const templateTag = "testplugin";
    const templateTagTwo = "testpluginTwo";
    private $filterFunctions = ["test::filter", "test::filterTwo"];

    public function install()
    {
        // TODO: Implement install() method.
    }

    function bindPlugin()
    {
        global $pluginManager;
        $templateTags = [
            ["tag" => $this::templateTag],
            ["tag" => $this::templateTagTwo]
        ];
        $pluginManager->registerPlugin($templateTags, "test");
        $pluginManager->registerHook($this->filterFunctions);
    }

    static function filter($source, Smarty_Internal_Template $localTemplateObj)
    {
        return preg_replace("/<!--" . test::templateTag . "-->/i", "{" . test::templateTag . "}", $source);
    }

    static function filterTwo($source, Smarty_Internal_Template $localTemplateObj)
    {
        return preg_replace("/<!--" . test::templateTagTwo . "-->/i", "{" . test::templateTagTwo . "}", $source);
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


<?php

class pluginManager
{


    private $installedPlugins;

    function __construct()
    {
        $installedPluginsFile = file_get_contents(CL_ROOT . "/config/standard/installedPlugins.json");
        $this->installedPlugins = json_decode($installedPluginsFile);
    }

    function loadPlugins()
    {
        foreach ($this->installedPlugins as &$plugin) {
            $this->loadPlugin($plugin);
        }
        return;
    }

    function loadPlugin($thePlugin)
    {
        $plugin = new $thePlugin();
        $plugin->bindPlugin();
        return true;
    }

    function registerPlugin(array $templateTags, $pluginClassName)
    {
        global $template;

        foreach ($templateTags as $templateTag) {
            if(!isset($templateTag["method"]) || empty($templateTag["method"]))
            {
                $templateTag["method"] = "::getTemplate";
            }
            $template->registerPlugin("function", $templateTag["tag"], $pluginClassName . $templateTag["method"]);
        }
        return true;
    }

    function registerHook(array $filterFunctionNames)
    {
        global $template;
        foreach ($filterFunctionNames as $filterFunctionName) {
            $template->registerFilter("pre", $filterFunctionName);
        }

        return true;
    }
}
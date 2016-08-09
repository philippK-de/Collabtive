<?php

class pluginManager
{


    private $installedPlugins;

    /*
     * Constructor
     * Reads the installedPlugins.json
     */
    function __construct()
    {
        $installedPluginsFile = file_get_contents(CL_ROOT . "/config/standard/installedPlugins.json");
        $this->installedPlugins = json_decode($installedPluginsFile);
    }

    /*
     * loop over the list of installed plugins and load them
     */
    function loadPlugins()
    {
        foreach ($this->installedPlugins as $plugin) {
            $this->loadPlugin($plugin);
        }
        return;
    }

    /*
     * Load a plugin by calling
     */
    function loadPlugin($thePlugin)
    {
        //call the constructor
        $plugin = new $thePlugin();
        //call bindPlugin()
        $plugin->bindPlugin();
        return true;
    }

    /*
     * Register a plugin with smarty
     * This registers a smarty template tag and connect it to a static method in the plugin class
     */
    function registerPlugin(array $templateTags, $pluginClassName)
    {
        global $template;

        //loop over tags
        foreach ($templateTags as $templateTag) {
            //if a tag has no method field, assume ::getTemplate method
            if (!isset($templateTag["method"]) || empty($templateTag["method"])) {
                $templateTag["method"] = "::getTemplate";
            }
            //register with smarty
            $template->registerPlugin("function", $templateTag["tag"], $pluginClassName . $templateTag["method"]);
        }
        return true;
    }

    /*
     * Register smarty filters to replace <!--comments--> with full smarty tags
     */
    function registerHook(array $filterFunctionNames)
    {
        global $template;
        foreach ($filterFunctionNames as $filterFunctionName) {
            $template->registerFilter("pre", $filterFunctionName);
        }

        return true;
    }
}
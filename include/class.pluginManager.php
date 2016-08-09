<?php

class pluginManager
{

    private $installedPluginsPath;
    private $installedPlugins;

    /*
     * Constructor
     * Reads the installedPlugins.json
     */
    function __construct()
    {
        $this->installedPluginsPath = CL_ROOT . "/config/standard/installedPlugins.json";
        $installedPluginsFile = file_get_contents($this->installedPluginsPath);
        $this->installedPlugins = json_decode($installedPluginsFile);
    }

    function getPlugins(){
        return $this->installedPlugins;
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

    function activatePlugin($pluginName)
    {
        if (!in_array($pluginName, $this->installedPlugins)) {
            array_push($this->installedPlugins, $pluginName);
            $this->writePluginConfig($this->installedPlugins);
        }
        return true;
    }

    function deactivatePlugin($pluginName)
    {
        $existingIndex = array_search($pluginName, $this->installedPlugins);

        if($existingIndex)
        {
            array_splice($this->installedPlugins,$existingIndex,1);
            $this->writePluginConfig($this->installedPlugins);
        }
        return true;
    }

    private function writePluginConfig(array $fileConfig)
    {
        $fileHandle = fopen($this->installedPluginsPath, "w");
        fwrite($fileHandle, json_encode($fileConfig));
        fclose($fileHandle);
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
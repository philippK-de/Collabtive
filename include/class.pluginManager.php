<?php

class pluginManager
{

    private $installedPluginsPath;
    //array describing the installed plugins and their status
    private $installedPlugins;

    /*
     * Constructor
     * Reads the installedPlugins.json
     */
    function __construct()
    {
        //set path to installedPlugins.json containing a JSON representation of the plugin configuration
        $this->installedPluginsPath = CL_ROOT . "/config/standard/installedPlugins.json";

        //read configuration file
        $installedPluginsFile = file_get_contents($this->installedPluginsPath);
        //decode json
        //each entry in this array is an array containing 2 fields: the plugin name and its activation state
        $this->installedPlugins = json_decode($installedPluginsFile);
    }

    /*
     * List all available plugins
     */
    function getPlugins()
    {
        return $this->installedPlugins;
    }

    /*
     * loop over the list of installed plugins and load them
     */
    function loadPlugins()
    {
        foreach ($this->installedPlugins as $plugin) {
            if ($plugin[1]) {
                $this->loadPlugin($plugin[0]);
            }
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
     * Enable a plugin
     * @param string $pluginName UniqueName of the plugin to be enabled
     */
    function enablePlugin($pluginName)
    {
        /*
         * Installed plugins is an array where each entry contains an array with 2 fields
         * 0 = name of the plugin
         * 1 = activation state (true / false)
         */
        foreach ($this->installedPlugins as &$installedPlugin) {
            //if the first field matches the name of the plugin to be enabled
            if ($installedPlugin[0] == $pluginName) {
                //set its activation state to true
                $installedPlugin[1] = true;
                //refresh config file
                $this->writePluginConfig($this->installedPlugins);
            }
        }
        return true;
    }

    /*
     * Enable a plugin
     * @param string $pluginName UniqueName of the plugin to be disabled
     */
    function disablePlugin($pluginName)
    {
        global $template;
        /*
         * Installed plugins is an array where each entry contains an array with 2 fields
         * 0 = name of the plugin
         * 1 = activation state (true / false)
         */
        foreach ($this->installedPlugins as &$installedPlugin) {
            //if the first field matches the name of the plugin to be disabled
            if ($installedPlugin[0] == $pluginName) {
                //set its activation state to false
                $installedPlugin[1] = false;
                //refresh config file
                $this->writePluginConfig($this->installedPlugins);
            }
        }
        return true;
    }

    /*
     * Helper function to manipulate the installedPlugins.json file
     * @param array $pluginConfig Array representing the configuration file to be written
     */
    private function writePluginConfig(array $pluginConfig)
    {
        //clear the template cache
        clearTemplateCache();
        //open file for reading and truncate to 0 length
        $fileHandle = fopen($this->installedPluginsPath, "w");
        //write JSON representation of the configuration array
        fwrite($fileHandle, json_encode($pluginConfig));
        //close file
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
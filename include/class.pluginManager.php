<?php

/*
 * Class to list, install, enable, disable, etc plugins
 * @private string $installedPluginsPath path to the JSON file holding the installed plugins
 * @private array $installedPlugins arrays representing the installed plugins and their activation state
 */

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
            //plugin[1] is a bool indicating the activation state of the plugin
            if ($plugin[1]) {
                //plugin[0] is the name of the plugin
                $this->loadPlugin($plugin[0]);
            }
        }
        return;
    }

    /*
     * Load a plugin by calling  $activate();
     */
    function loadPlugin($thePlugin)
    {
        //call the constructor
        $plugin = new $thePlugin();
        //call bindPlugin()
        $plugin->activate();
        return true;
    }

    /*
     * Install a plugin
     * @param String $pluginName Unique Name of the plugin to be installed
     */
    function installPlugin($pluginName)
    {
        //add a new entry to the installed Plugins array, with an activation state of true
        array_push($this->installedPlugins, [$pluginName, true]);
        //refresh the config file
        $this->writePluginConfig($this->installedPlugins);

        return true;
    }

    /*
    * Set the state of a plugin
    * @param string $pluginName UniqueName of the plugin to be enabled
    * @param bool $pluginState true = enable, false = disable
    */
    function setPluginState($pluginName, $pluginState)
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
                $installedPlugin[1] = $pluginState;
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
        //clear the template cache so templates get re-rerendered on next page load
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
     * This registers a smarty {template tag} and connect it to a static method in the plugin class
     */
    function registerPlugin(array $templateTags, $pluginClassName)
    {
        global $template;

        //loop over tags
        foreach ($templateTags as $templateTag) {
            //if a tag has no method field, assume ::getTemplate method
            if (!isset($templateTag["method"]) || empty($templateTag["method"])) {
                $templateTag["method"] = "::getMainTemplate";
            }
            //register with smarty
            $template->registerPlugin("function", $templateTag["tag"], $pluginClassName . $templateTag["method"]);
        }
        return true;
    }

    /*
     * Register smarty filters to replace <!--hooks--> with full {smarty tags}, associated to functions
     */
    function registerHook(array $filterFunctionNames)
    {
        global $template;
        //loop over filter functions and register a prefilter for each hook
        foreach ($filterFunctionNames as $filterFunctionName) {
            $template->registerFilter("pre", $filterFunctionName);
        }

        return true;
    }
}
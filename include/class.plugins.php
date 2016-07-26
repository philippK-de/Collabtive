<?php
class plugins {


    private $installedPlugins;

    function __construct(){
     /*   $this->installedPlugins = [];

        //get the contents of the plugins folder
        $pluginFiles = scandir(CL_ROOT . "/plugins/");
        //loop through the contents
        foreach ($pluginFiles as $pluginFile) {
            if (!is_dir($pluginFile)) {
                //magic number
                //files are named plugin.name.php
                //plugin. = 7 chars
                //remove first 7 chars
                $pluginName = substr($pluginFile, 7);
                //magic number
                //files are named plugin.name.php
                //.php = 4 chars
                //remove last 4 chars
                $pluginName = substr($pluginName, 0, strlen($pluginName) - 4);

                //push the plugin name to the list of installed plugins
                array_push($this->installedPlugins, $pluginName);
            }
        }
       */
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
        return new $thePlugin();
    }

    function registerPlugin($templateTag, $templateCallable, $templateFilter)
    {
        global $template;

        $template->registerPlugin("function", $templateTag, $templateCallable);

      /*  $template->registerFilter("pre", function ($source, Smarty_Internal_Template $templateObj) use ($templateTag) {
            return preg_replace("/<!--" . $templateTag . "-->/i", "{{" . $templateTag . "}}", $source);
        }); */
      $template->registerFilter("pre", $templateFilter);
    }
}
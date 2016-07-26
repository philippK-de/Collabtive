<?php
class plugins {


    private $installedPlugins;

    function __construct(){
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
<?php
class plugins {


    private $installedPlugins;

    function __construct(){
        $installedPluginsFile = file_get_contents(CL_ROOT . "/config/standard/installedPlugins.json");
        $this->installedPlugins = json_decode($installedPluginsFile);
    }
    function loadPlugins()
    {
        foreach ($this->installedPlugins as $plugin) {
            $pluginObj = new $plugin();
        }
        return;
    }

    function getTemplateFile()
    {

    }

}
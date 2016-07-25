<?php


class test{
    private $templateFile = "test.tpl";
    private $templateTag = "testplugin";

   function __construct(){
       global $template;
       $template->registerFilter("pre",  function($source, Smarty_Internal_Template $templateObj){

           $filePath = CL_ROOT . "/plugins/templates/" . $this->templateFile;
           $templateFile = file_get_contents($filePath);

           return preg_replace("/<!--" . $this->templateTag . "-->/i", $templateFile, $source);
       });


   }

    function getIrgendwelcheListen(Smarty_Internal_Template $templateObj)
    {
        $taskObj = new task();
        $allTask = $taskObj->getMyTasks($_SESSION["userid"]);

        $templateObj->assign("irgendwas",$allTask);
    }
    function bindValues(array $values, Smarty_Internal_Template $templateObj)
    {
        foreach($values as $value)
        {
            $templateObj->assign($value["name"],$value["value"]);
        }
    }
}


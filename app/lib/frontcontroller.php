<?php
namespace PHPMVC\LIB;

class frontController{
 
   const Not_Found_Action = 'notFoundAction';
   const Not_Found_Controller = 'PHPMVC\Controllers\NotFoundController';
   private  $_controller='index';
   private $_action='default';
   private $_params=array();
   private $_template;

   public function __construct(Template $template){
      $this->_template = $template;
      $this->_parseUrl();
   }
   private function _parseUrl(){

      $url =  explode('/',trim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),'/'),3);
      
      if(isset($url[0])&&$url[0]!=''){
         $this->_controller = $url[0];
      }
      if(isset($url[1])&&$url[1]!=''){
         $this->_action= $url[1];
     }
     if(isset($url[2])&&$url[0]!=''){
         $this->_params = explode('/',$url[2]);
     }
   }
   public function dispatch(){
      $controllerClassName = 'PHPMVC\Controllers'.DS. ucfirst($this->_controller) .'Controller';
      $actionName = $this->_action.'Action';
      if(!class_exists($controllerClassName)){
         $controllerClassName = self::Not_Found_Controller;
      }
      $controller = new $controllerClassName();
      if(!method_exists($controller,$actionName)){
            $this->_action = $actionName = self::Not_Found_Action ;
      }
      $controller->setContorller($this->_controller);
      $controller->setAction($this->_action);
      $controller->setParams($this->_params);
      $controller->setTemplate($this->_template);
      $controller->$actionName();
      // var_dump($controller);
   }
}
?>
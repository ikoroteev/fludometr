<?php
namespace Model;

class Model{

    public function __get($name) {
        if(property_exists('this',$name) AND method_exists('this','get'.ucfirst($name)) ) {
            call_user_func(array($this,'get'.ucfirst($name)));
        }
    }
}
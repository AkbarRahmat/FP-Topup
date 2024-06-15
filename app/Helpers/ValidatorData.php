<?php

if(!function_exists('validatorEmail')){
    
    function validatorEmail($text){
        if (!$text) {return null;}
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $text)) {
            return null;
        }

        return $text;
    }
}

if(!function_exists('validatorPhone')){
    
    function validatorPhone($text){
        if (!$text) {return null;}
        
        if (substr($text, 0, 2) === "08") {
            $text = "628" . substr($text, 2);
        }

        if (!preg_match('/^\d{12,15}$/', $text)) {
            return null;
        }

        return $text;
    }
}

if(!function_exists('isUUID')){
    
    function isUUID($text){
        if (!$text) {return null;}
        
        return preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $text);
    }
}
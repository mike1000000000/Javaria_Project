<?php
/**
 * Javaria Project
 * Copyright © 2019
 * Michel Noel
 * Datalight Analytics
 * http://www.datalightanalytics.com/
 *
 * Creative Commons Attribution-ShareAlike 4.0 International Public License
 * By exercising the Licensed Rights (defined below), You accept and agree to be bound by the terms and conditions of
 * this Creative Commons Attribution-ShareAlike 4.0 International Public License ("Public License"). To the extent this
 * Public License may be interpreted as a contract, You are granted the Licensed Rights in consideration of Your
 * acceptance of these terms and conditions, and the Licensor grants You such rights in consideration of benefits the
 * Licensor receives from making the Licensed Material available under these terms and conditions.
 *
 * File: lang.php
 * Last Modified: 7/27/19, 8:27 PM
 */

// Multilingual Support
    
    getlang();
    
    function mlang_str($lang_str, $ret = false){

      global $langArr;

      if (empty($langArr) ) {
          getlang();
      }

      if(isset($langArr[$lang_str]) )  {
        $value = $langArr[$lang_str];
      }
      else {
        $value = "{".$lang_str."}";
      }

      if(!$ret){
          echo $value;
          return '';
      }
      else {
          return $value;
      }
    }


    
    function getlang(){
                
        global $langArr;
        if(isSet($_GET['lang']))
        { 
            $lang = $_GET['lang'];
            $_SESSION['lang'] = $lang;
        }
        else if(isSet($_SESSION['lang']))
        {
            $lang = $_SESSION['lang'];
        }
        else
        {
            $lang = 'en';
        }
        
        switch ($lang) {
                case 'en':
                    $lang_file = 'lang.en.php';
                    break;

                case 'fr':
                    $lang_file = 'lang.fr.php';
                    break;
        }
        
       include_once 'languages/'.$lang_file;
    }
    
    
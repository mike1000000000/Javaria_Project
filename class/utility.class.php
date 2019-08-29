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
 * File: utility.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class utility
{
    static function cleanArray(array $inputarray, array $filterarray){
        return self::cleanupArray($inputarray,$filterarray);
    }

    private static function cleanupArray(array $result, array $filterarray){
        self::removeNumericIndexes($filterarray);
        $result = self::getOnlyArrayKeys($result,$filterarray);
        $vals = self::replaceKeyNames($result, $filterarray);
        return $vals;
    }

    private static function replaceKeyNames(array $inputArray, array $nameArray){
        foreach ($nameArray as $key => $value) {
            if(array_key_exists($key,$inputArray) && $key != $value ) {
                self::changeKey($inputArray,$key,$value);
            }
        }
        return $inputArray;
    }

    // Replaces Key name in array - does not preserve order
    static function changeKey(array &$array, $oldkeyname, $newkeyname){
        $array[$newkeyname] = $array[$oldkeyname];
        unset($array[$oldkeyname]);
    }

    private static function removeNumericIndexes(array &$filterArray){
        foreach ($filterArray as $key => $value) {
            if(is_numeric($key)) {
                self::changeKey($filterArray,$key,$value);
            }
        }
    }

    // Returns only keys from the $filterArray
    private static function getOnlyArrayKeys(array $inputArray, array $filterArray){
        return array_filter($inputArray, function($val) use ($filterArray) {
            return ( array_key_exists($val, $filterArray) || in_array($val,$filterArray) ) ;
        }, ARRAY_FILTER_USE_KEY );
    }

    static function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    // Input array contains userinput
    // Filterarray contains only valid data settings
    // Required breaks if a required value is missing
    static function cleanArrayP(array $inputarray, array $filterarray, $required){
        $output = array() ;

        foreach ($filterarray as $item){
            if(array_key_exists($item[1],$inputarray) || array_key_exists($item[2],$inputarray)){
                $temp = array('type' =>$item[0],'name'=> (!empty($item[2]) ? $item[2] : $item[1]), 'value'=>$inputarray[ $item[1]] ?? $inputarray[$item[2]]);

                if( ($item[3] !== '' &&  preg_match($item[3], $temp['value'])) || $item[3] === '' ) {
                    $output[] = $temp;
                }else if($item[4] && $required) {
                    return (bool) false;
                }
            }else if($item[4] && $required) {
                return (bool) false;
            }
        }
        return $output;
    }
}
<?php
/**
 * The Javaria Project
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
 * File: colour.class.php
 * Last Modified: 8/24/19, 12:55 PM
 */

class colour
{
    /**
     * colour constructor.
     */
    public function __construct()
    {

    }


    /**
     * @param $colourid
     * @return mixed
     */
    function getsinglecolour($colourid){

        $colarray = array('idcolours','colour_name','colour_code','border_colour_code','border_colour_width');
        $wherearray[] = array('type'=>'i','name'=>'idcolours','value'=>$colourid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('colours', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function createcolour($colourinfo){

        $colourinfo = $this->cleanColourArrayP($colourinfo,true);
        if(!$colourinfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('colours', $colourinfo);
        $value = sqlhandler::pushsql($pre_sql,$colourinfo);

        return $value;
    }


    function updatecolour($colourid,$colourinfo){

        $colourinfo = $this->cleanColourArrayP($colourinfo);
        if(!$colourinfo)return (bool) false;

        $wherearray[] = array('type'=>'i','name'=>'idcolours','value'=>$colourid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('colours', $colourinfo, $wherearray);
        $value = sqlhandler::pushsql($sql_line,$colourinfo,$wherearray);

        return $value;
    }


    function deletecolour($colourid){
        $wherearray[] = array('type'=>'i','name'=>'idcolours','value'=>$colourid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('colours', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null, $wherearray);
        return $value;
    }


    function getFullList(){
        // Get all colours
        $colarray = array('idcolours','colour_name','colour_code','border_colour_code','border_colour_width');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('colours', $colarray);
        $value = sqlhandler::getsql($pre_sql);

        return $value;
    }


    private function cleanColourArrayP($colourArray,$required = false){
        // Clean colour array
        // Prepared Type, Alias/Name, Actual Name if different, Rules, Required
        $filterarray = array(
            array('s','newcname','colour_name','/^([a-zA-Z0-9 ._]{1,16})$/',1),
            array('s','newcvalue','colour_code','',1),
            array('s','newbcvalue','border_colour_code','',0),
            array('i','bwidth','border_colour_width','',0)

        );
        return utility::cleanArrayP($colourArray,$filterarray,$required);
    }
}
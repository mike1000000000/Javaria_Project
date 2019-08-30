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
 * File: htmlblock.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class htmlblock
{
    public function __construct()
    {

    }

    function getsinglehtml($htmlblockid){
        $colarray  = array('name','htmlcode');
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$htmlblockid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('htmlblocks', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function createhtmlblock($htmlblockinfo){
        $htmlblockinfo = $this->cleanhtmlarray($htmlblockinfo);
        if(!$htmlblockinfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('htmlblocks', $htmlblockinfo);
        $value = sqlhandler::pushsql($pre_sql,$htmlblockinfo);

        return $value;
    }

    function updatehtmlblock($htmlblockid,$htmlblockinfo){
        $htmlblockinfo = $this->cleanhtmlarray($htmlblockinfo);
        if(!$htmlblockinfo)return (bool) false;

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$htmlblockid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('htmlblocks', $htmlblockinfo, $wherearray);
        $value = sqlhandler::pushsql($sql_line,$htmlblockinfo,$wherearray);

        return $value;
    }

    function deletehtmlblock($htmlblockid){
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$htmlblockid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('htmlblocks', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null, $wherearray);
        return $value;
    }

    private function cleanHtmlArray($userArray,$required = false){
        // Clean user array
        // Prepared Type, Alias/Name, Actual Name if different, Rules, Required
        $filterarray = array(
            array('s','htmlname','name','/^([a-zA-Z0-9 ._]{1,26})$/',1),
            array('s','code','htmlcode','',0)
        );
        return utility::cleanArrayP($userArray,$filterarray, $required);
    }
}
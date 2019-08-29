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
 * File: dataconnection.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class dataconnection
{

    public function __construct()
    {

    }

    function availableDataconnection($dataconnectionid)
    {
        if(!is_int(intval($dataconnectionid))){return false;}

        // Does the dataconnection exist?
        $colarray = array('id');
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dataconnectionid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('data_connectors', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);
        if(!$value){return false;}

        if (has_permission('bypass_dataconnectionscreening') ){return true;}

        $dataconnection = $this->getfiltereddataconnectionlist();
        $dataconnectionArray = array_column($dataconnection,'id');

        $value = in_array($dataconnectionid,$dataconnectionArray);
        return $value;
    }

    function getsingledataconnection($dataconnectionid,$credential = false){

        $colarray = array('id','name','server','port','instance','db','username','pword','servertype');

        if(is_numeric($dataconnectionid)) {
            $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dataconnectionid);
        } else {
            $wherearray[] = array('type'=>'s','name'=>'name','value'=>$dataconnectionid);
        }

        $pre_sql = sqlhandler::parseReturnSQLfromarray('data_connectors', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        if(!$credential){
            utility::changekey($value, 'pword','password');
            $value['password'] = str_repeat('•', strlen($value['password']) );
        }

        return $value;
    }

    // Get only the accessible groups from the hierarchy
    function getfiltereddataconnectionlist(){

        if (has_permission('bypass_dataconnectionscreening') ){
            $colarray = array('id', 'name');
            $pre_sql = sqlhandler::parseReturnSQLfromarray('data_connectors', $colarray);
            $value = sqlhandler::getsql($pre_sql);
        }
        else {

            $loginid = $_SESSION['login_id'];

            $colarray = array('dataconnectionid');
            $wherearray[] = array('type'=>'i','name'=>'userid','value'=>$loginid);

            $pre_sql = sqlhandler::parseReturnSQLfromarray('dataconnection_auths', $colarray,$wherearray);
            $accessibledc = sqlhandler::getsql($pre_sql,$wherearray);

            $accessibledcArray = array_column($accessibledc,'dataconnectionid');

            $cleanArray = array_unique($accessibledcArray);
            asort($cleanArray);

            if(empty($cleanArray)) return '';

            // Return all dataconnection info that user can see
            $colarray = array('id', 'name');

            $whereIn[] =  array('type'=>'s','name'=>'id','value'=>implode(',', $cleanArray));
            $pre_sql = sqlhandler::parseReturnSQLIn('data_connectors', $colarray, $whereIn);
            $value = sqlhandler::getsql($pre_sql, $whereIn);
        }
        return $value;
    }

    function createDataconnection($dataconnectioninfo){

        $dataconnectioninfo = $this->cleanDataconnectionArray($dataconnectioninfo,true);
        if(!$dataconnectioninfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('data_connectors', $dataconnectioninfo);
        $value = sqlhandler::pushsql($pre_sql,$dataconnectioninfo);

        return $value;
    }

    function updatedataconnection($dataconnectionid,$dataconnectioninfo){

        $dataconnectioninfo = $this->cleanDataconnectionArray($dataconnectioninfo,false);
        if(!$dataconnectioninfo)return (bool) false;

        // Remove password if not changed
        $key = array_search('pword', array_column($dataconnectioninfo, 'name'));
        if(str_replace("•", "", $dataconnectioninfo[$key]['value']) === "" ) { unset($dataconnectioninfo[$key]); }

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dataconnectionid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('data_connectors', $dataconnectioninfo, $wherearray);
        $value = sqlhandler::pushsql($sql_line,$dataconnectioninfo,$wherearray);

        return $value;
    }

    function deletedataconnection($dataconnectionid){
        // Set connection to deleted
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dataconnectionid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('data_connectors', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }

    private function cleanDataconnectionArray($vals, $required){

        $filterarray = array(
            array('s','name','','/^([a-zA-Z0-9 ._]{1,26})$/',1),
            array('s','server','','',0),
            array('i','port','','',0),
            array('s','instance','','',0),
            array('s','database','db','',0),
            array('s','username','','',0),
            array('s','password','pword','',0),
            array('s','servertype','','',0),
            array('s','auth_ids','','',0)
        );
        return utility::cleanArrayP($vals,$filterarray,$required);
    }
}
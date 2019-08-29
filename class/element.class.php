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
 * File: element.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class element
{

    public function __construct()
    {

    }

    function getsingleelement($elementid){

        $colarray  = array('element_type','element_id','options');

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$elementid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('elements', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function createElement($elementinfo){

        $optiondefaults['showtitle'] = 'true';
        $optiondefaults['titlecolor'] = '000000';
        $optiondefaults['width'] = '300';
        $optiondefaults['height'] = '300';
        $optiondefaults['border'] = '2';
        $optiondefaults['bcolor'] = 'E4FFBF';
        $optiondefaults['bgcolor'] = 'FFFFFF';
        $optiondefaults['barc'] = '0';

        $elementinfo = $this->cleanElementArrayP($elementinfo,true);
        if(!$elementinfo)return (bool) false;

        $elementinfo[] = array('type'=>'s','name'=>'options','value'=>json_encode($optiondefaults));

        $pre_sql = sqlhandler::parseInsertSQLfromarray('elements', $elementinfo);
        $value = sqlhandler::pushsql($pre_sql,$elementinfo);

        return $value;
    }

    function updateElement($elementid,$elementoptions){

        $options = $this->cleanElementOptions($elementoptions);
        if(!is_numeric($elementid)) return false;

        // Pull current options and compare and replace as need be
        $currentoptions = json_decode($this->getsingleelement($elementid)['options'],true);

        foreach($options as $key => $value){
            $currentoptions[$key] = $value;
        }

        $elementinfo[] = array('type'=>'s','name'=>'options','value'=>json_encode($currentoptions));
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$elementid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('elements', $elementinfo,$wherearray);
        $value = sqlhandler::pushsql($sql_line,$elementinfo,$wherearray);

        return $value;
    }

    function getdashboardelements($dashboardid){

        $colarray = array('element_id');
        $wherearray[] = array('type'=>'i','name'=>'dashboard_id','value'=>$dashboardid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboard_elements', $colarray, $wherearray);
        $elements = sqlhandler::getsql($pre_sql,$wherearray);
        if(empty($elements)) return false;

        $elements = array_column($elements,'element_id');

        $colarray  = array('id','element_type','element_id','options');
        $whereIn[] = array('type'=>'s','name'=>'id','value'=>implode(',', $elements));

        $pre_sql = sqlhandler::parseReturnSQLIn('elements', $colarray, $whereIn);
        $value = sqlhandler::getsql($pre_sql,$whereIn);

        return $value;
    }

    function updateDashboardElement($elementid,$dashboardid){

        $dashboardelement[] = array('type'=>'i','name'=>'element_id','value'=>$elementid);
        $dashboardelement[] = array('type'=>'i','name'=>'dashboard_id','value'=>$dashboardid);

        $pre_sql = sqlhandler::parseInsertSQLfromarray('dashboard_elements', $dashboardelement);
        $value = sqlhandler::pushsql($pre_sql,$dashboardelement);

        return $value;
    }

    function deleteElement($elementid){
        // Figure out what type of element this is and delete the corresponding type
        $elementinfo = $this->getsingleelement($elementid);

        switch($elementinfo['element_type']){
            case 'html':
                if(!has_permission('addedithtml')){  return json_encode(array('error'=>'1')); }
                $htmlblock = new htmlblock();
                $htmlblock->deletehtmlblock($elementinfo['element_id']);

                break;

            case 'chartjs' :
                if(!has_permission('addeditchart')){  return json_encode(array('error'=>'1')); }
                $chartjsblock = new chartjs();
                $chartjsblock->deletechartjsblock($elementinfo['element_id']);

                break;
        }
        // Delete elements instance from the dashboard elements
        $wherearray[] = array('type'=>'i','name'=>'element_id','value'=>$elementid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('dashboard_elements', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);

        // Delete the element
        $wherearray2[] = array('type'=>'i','name'=>'id','value'=>$elementid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('elements', $wherearray2);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray2);

        return $value;
    }

    private function cleanElementArrayP($elementArray,$required = false){

        // Prepared Type, Alias/Name, Actual Name if different, Rules, Required
        $filterarray = array(
            array('s','element_type','','',1),
            array('i','element_id','','',1),
            array('i','refresh_rate','','',0),
            array('i','created_by','','',0),
            array('s','options','','',0)
        );
        return utility::cleanArrayP($elementArray,$filterarray, $required);
    }

    private function cleanElementOptions($elementArray){
        // Clean dc array
        $filterarray = array(
            'showtitle',
            'titlecolor',
            'width',
            'height',
            'top'=>'divposx',
            'left'=>'divposy',
            'border',
            'bordercolor',
            'borderarc'=>'barc',
            'bgcolor',
            'zindex'=>'z_index'
        );
        return utility::cleanArray($elementArray,$filterarray);
    }
}
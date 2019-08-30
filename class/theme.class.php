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
 * File: theme.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class theme
{
    public function __construct()
    {

    }

    function getsingletheme($themeid){
        $colarray = array('id','theme_name');
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$themeid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('themes', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function getFullList(){
        // Get all themes
        $colarray = array('id','theme_name');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('themes', $colarray);
        $value = sqlhandler::getsql($pre_sql);

        return $value;
    }

    function createtheme($themeinfo){
        $themeinfo = $this->cleanthemearray($themeinfo,true);
        if(!$themeinfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('themes', $themeinfo);
        $value = sqlhandler::pushsql($pre_sql,$themeinfo);

        return $value;
    }

    function updatetheme($themeid,$themeinfo){
        $themeinfo = $this->cleanthemearray($themeinfo,false);
        if(!$themeinfo)return (bool) false;

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$themeid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('themes', $themeinfo, $wherearray);
        $value = sqlhandler::pushsql($sql_line,$themeinfo,$wherearray);

        return $value;
    }

    function deletetheme($themeid){
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$themeid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('themes', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);

        $this->deleteAllColours($themeid);

        return $value;
    }

    function deleteAllColours($themeid){
        $wherearray[] = array('type'=>'i','name'=>'theme_id','value'=>$themeid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('colours_theme_colours', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }

    function addColour($themeid, $colourid)
    {
        $themecolours[] = array('type'=>'i','name'=>'theme_id','value'=>$themeid);
        $themecolours[] = array('type'=>'i','name'=>'colour_ids','value'=>$colourid);

        $pre_sql = sqlhandler::parseInsertSQLfromarray('colours_theme_colours', $themecolours);
        $value = sqlhandler::pushsql($pre_sql,$themecolours);

        return $value;
    }

    function getThemeColours($themeid)
    {
        $colarray = array('colour_ids');
        $wherearray[] = array('type'=>'i','name'=>'theme_id','value'=>$themeid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('colours_theme_colours', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);

        if( !isset($value[0]['colour_ids'])  || (isset($value[0]['colour_ids']) && $value[0]['colour_ids'] == 0 )) return '';

        $colourlist = array();

        $colourinfo = new colour();

        foreach ($value as $colourid) {
            $colourlist[] = $colourinfo->getsinglecolour($colourid['colour_ids']);
        }

        $value = $colourlist;

        return $value;
    }

    private function cleanthemearray($themeArray, $required = false){
        // Prepared Type, Alias/Name, Actual Name if different, Rules, Required
        $filterarray = array(
            array('s','name','theme_name','',1)
        );
        return utility::cleanArrayP($themeArray,$filterarray, $required);
    }
}
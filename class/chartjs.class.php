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
 * File: chartjs.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class ChartSettings
{
    // Order of these variables is important for JSON encode
    public $type;
    public $data;
    public $options;

    function __construct() {   }
}


class chartjs
{
    public function __construct()  {   }

    function getsinglechartjs($chartjsid){
        $colarray = array('name','charttype','datasets','x_axis_name','xgrid','xshow','xtick','xstack','xlabel','xline','y_axis_name','ygrid','yshow','ytick','ystack','yline','ylabel','legend');

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$chartjsid);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('chartjs', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];
        return $value;
    }


    function createchartjsblock($chartjsinfo){
        $chartjsinfo = $this->cleanChartjsArrayP($chartjsinfo,true);
        if(!$chartjsinfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('chartjs', $chartjsinfo);
        $value = sqlhandler::pushsql($pre_sql,$chartjsinfo);
        return $value;
    }


    function updatechartjsblock($chartjsid,$chartjsinfo){
        $chartjsinfo = $this->cleanchartjsarrayP($chartjsinfo,false);
        if(!$chartjsinfo)return (bool) false;

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$chartjsid);
        $sql_line = sqlhandler::parseUpdateSQLfromarray('chartjs', $chartjsinfo, $wherearray);
        $value = sqlhandler::pushsql($sql_line,$chartjsinfo,$wherearray);
        return $value;
    }


    function deletechartjsblock($chartjsid){
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$chartjsid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('chartjs', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }


    function buildChart($elementid,$chartinfo = null){

        if($elementid){
            $elementinfo = new element();
            $chartjsblockid = $elementinfo->getsingleelement($elementid)['element_id'];
            $chartinfo = $this->getsinglechartjs($chartjsblockid);
        }

        $chartinfo =   $this->cleanchartjsarrayP( (array) $chartinfo, false);
        $chartinfo = (object) array_column($chartinfo,'value','name');

        $chartinfo->datasets = json_decode( $chartinfo->datasets );

        $defChartType = !empty($chartinfo->charttype) ? $chartinfo->charttype : 'pie';

        $defData = [];
        $defLabel = [];

        $fullarray = new stdClass();
        $datalab = -1;

        $dset = count($chartinfo->datasets);

        foreach( $chartinfo->datasets as $dataset  ) {
            $datalab++;
            $temparray = [];

            if(!empty($dataset->labelvalue) && !empty($dataset->sumvalue)) {

                $colarray['labels'] = $dataset->labelvalue;
                $colarray['values'] = $dataset->sumvalue;

                $wherearray = json_decode($dataset->filters);

                $pass_sql = sqlhandler_ext::parseReturnExtSQLfromarray($dataset->tablevalue,$colarray,$dataset->aggregation,$wherearray);

                $this->getDatafromOptions($dataset->dataconnection, $pass_sql,$temparray);
            }

            foreach($temparray as $key => $value){
                $datasetArray = $value['labels'];
                $fullarray->$datasetArray[$datalab] =  $value['values'];
            }
        }


        foreach($fullarray as $key => $value) {
            $defLabel[] = $key;
        }

        $defColors = [];
        $defBorderColors = [];
        $defBorderWidths = [];

        for ($x = 0; $x < $dset; $x++ ){
            $tempDefData = [];

            foreach($fullarray as $key => $value) {

                if(isset($fullarray->$key[$x])){
                    $tempDefData[] = $fullarray->$key[$x];
                }
                else{
                    $tempDefData[] = 0;
                }
            }

            $defData[] = $tempDefData;
            if (empty($chartinfo->datasets[$x]->theme)) {
                $defColors[] = ['#FF0000', '#00FF00', '#0000FF'];
                $defBorderColors = ['#FF0000', '#00FF00', '#0000FF'];
                $defBorderWidths = [1,1,1];

            } else {
                $tempdefColors = $this->getColors($chartinfo->datasets[$x]->theme, count($defLabel));
                $defColors[] = $tempdefColors['colors'];
                $defBorderColors[] = $tempdefColors['borders'];
                $defBorderWidths[] = $tempdefColors['widths'];
            }

        };

        $defLabels = $defLabel;

        $defXLabel = empty($chartinfo->x_axis_name) ? "X Name" : $chartinfo->x_axis_name;
        $defYLabel = empty($chartinfo->y_axis_name) ? "Y Name" : $chartinfo->y_axis_name;

        $new_obj = new ChartSettings();
        $new_obj->type =  $defChartType ;
        $new_obj->data['labels'] = $defLabels;

        $valarray = $defData;
        $colarray = $defColors;

        for ($x = 0; $x < $dset; $x++ ) {

            $dataset_array = array();

            $dataset_array['label'] = $chartinfo->datasets[$x]->datasetname;
            $dataset_array['fill'] = false;
            $dataset_array['data'] = $valarray[$x];
            $dataset_array['backgroundColor'] = $colarray[$x];

            if($defChartType == 'line') {
                $dataset_array['borderColor'] = $defBorderColors[$x][0];
                $dataset_array['borderWidth'] = $defBorderWidths[$x][0];
                $dataset_array['fill'] = false;
            }else{
                $dataset_array['borderColor'] = $defBorderColors[$x];
                $dataset_array['borderWidth'] = $defBorderWidths[$x];
            }

            $new_obj->data['datasets'][$x] =  $dataset_array;
        };


        $new_obj->options['responsive'] = false;
        $new_obj->options['maintainAspectRatio'] = false;

        $new_obj->options['legend']['display'] = isset($chartinfo->legend) ? $chartinfo->legend ? true : false : false;


        $new_obj->options['scales']['xAxes'] = [[
            "display"=> !empty($chartinfo->xshow) ? $chartinfo->xshow  ? true : false : false,
            "ticks" => array("display"=> !empty($chartinfo->xtick) ? $chartinfo->xtick  ? true : false : false ,"autoSkip"=>false,"autoSkipPadding"=>1 ),
            "gridLines" => array( "display" => !empty($chartinfo->xgrid) ? $chartinfo->xgrid  ? true : false : false,
                "drawBorder"=> !empty($chartinfo->xline) ? $chartinfo->xline  ? true : false : false
            ),
            "scaleLabel" => array("display" => !empty($chartinfo->xlabel) ? $chartinfo->xlabel ? true : false : false,"labelString"=> $defXLabel ),
            "stacked" => !empty($chartinfo->xstack) ? $chartinfo->xstack ? true : false : false
        ]];

        $new_obj->options['scales']['yAxes'] = [[
            "display"=> !empty($chartinfo->yshow) ? $chartinfo->yshow  ? true : false : false,
            "ticks" => array("display"=> !empty($chartinfo->ytick) ? $chartinfo->ytick  ? true : false : false ,"autoSkip"=>false,"autoSkipPadding"=>1 ),
            "gridLines" => array( "display" => !empty($chartinfo->ygrid) ? $chartinfo->ygrid ? true : false : false,
                "drawBorder"=> !empty($chartinfo->yline) ? $chartinfo->yline ? true : false : false
            ),
            "scaleLabel" => array("display" => !empty($chartinfo->ylabel) ? $chartinfo->ylabel ? true : false : false,"labelString"=> $defYLabel ),
            "stacked" =>  !empty($chartinfo->ystack) ? $chartinfo->ystack ? true : false : false
        ]];

        $value = [$chartinfo->name, json_encode($new_obj)];
        return $value;
    }


    private function getDatafromOptions($dc,$pass_sql, &$keys){
        $dataconnection = new dataconnection();
        $conn = $dataconnection->getsingledataconnection($dc, true);

        $keys = sqlhandler_ext::getsql_ext($conn, $pass_sql);
        return;
    }


    private function getColors($theme,$no){
        $retcolarray = array();

        if($no == 0) return '';

            $temptheme = new theme();
            $temptheme = $temptheme->getThemeColours($theme);

            $colarray = array_column($temptheme,"colour_code");
            $bcolarray = array_column($temptheme,"border_colour_code");
            $bwidtharray = array_column($temptheme,"border_colour_width");

            $arr_c_count = count($colarray);

            if($arr_c_count == 0) return array('colors'=>'000000', 'borders'=>'0','widths'=>'0');

            for($i = 1; $i <= $no; $i++){
                $z = $i % $arr_c_count;

                $retcolarray[] = (strpos($colarray[$z], 'rgba') !== false) ?  $colarray[$z] :  '#' . $colarray[$z];
                $borderarray[] = (strpos($bcolarray[$z], 'rgba') !== false) ?  $bcolarray[$z] :  '#' . $bcolarray[$z];
                $widtharray[] = $bwidtharray[$z];
            }
        $retcol['colors'] = $retcolarray;
        $retcol['borders'] = $borderarray;
        $retcol['widths'] = $widtharray;

        return $retcol;
    }


    private function cleanChartjsArrayP($vals,$required){

        $filterarray = array(
            array('s','name','','/^([a-zA-Z0-9 ._]{1,26})$/',1),
            array('s','charttype','','',1),
            array('s','datasetsJSON','datasets','',1),
            array('s','xaxis','x_axis_name','',0),
            array('i','gridXaxis','xgrid','',0),
            array('i','showXaxis','xshow','',0),
            array('i','tickXaxis','xtick','',0),
            array('i','stackedXaxis','xstack','',0),
            array('i','lineXaxis','xline','',0),
            array('i','labelXaxis','xlabel','',0),
            array('s','yaxis','y_axis_name','',0),
            array('i','gridYaxis','ygrid','',0),
            array('i','showYaxis','yshow','',0),
            array('i','tickYaxis','ytick','',0),
            array('i','stackedYaxis','ystack','',0),
            array('i','lineYaxis','yline','',0),
            array('i','labelYaxis','ylabel','',0),
            array('i','legend','','',0)
        );
        return utility::cleanArrayP($vals,$filterarray,$required);
    }
}
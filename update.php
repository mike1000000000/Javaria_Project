<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 3/3/19
 * Time: 2:37 PM
 */

include_once('loadclass.php');

$chart_no = $_GET["cn"];
$chart_no = $_POST["cn"];

$newchart = new chartjs();
$json =  $newchart->buildChart($chart_no) ;

header('Content-type:application/json;charset=utf-8');
echo   json_encode( $json );
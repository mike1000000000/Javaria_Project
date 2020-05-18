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
 * File: sqlhandler_ext.class.php
 * Last Modified: 8/19/19, 8:27 PM
 */

class sqlhandler_ext
{
    public function __construct()
    {

    }

    private static function connect_ext($connJSON)
    {
        switch ($connJSON['servertype']) {

            case 'mysql':
                $db = mysqli_connect($connJSON['server'], $connJSON['username'], $connJSON['pword'], $connJSON['db'], $connJSON['port']);
                break;

            case 'mssql':
                $serverName = $connJSON['server'] . "\\" . $connJSON['instance'];

                $connectionInfo = array("Database" => $connJSON['db'], "UID" => $connJSON['username'], "PWD" => $connJSON['pword']);
                $db = sqlsrv_connect($serverName, $connectionInfo);
        }
        return $db;
    }

    static function getsql_ext($connJSON, $sql)
    {
//    TODO --- ADD sql parsing
        $json = array();

        $conn = self::connect_ext($connJSON);

        switch ($connJSON['servertype']) {

            case 'mysql':
                $result = $conn->query($sql);
                $json = $result->fetch_all(MYSQLI_ASSOC);

                break;

            case 'mssql':
                $sql = str_replace('`labels`', '"labels"', $sql);
                $sql = str_replace('`values`', '"values"', $sql);
                $sql = str_replace('`', '', $sql);

                $getResults = sqlsrv_query($conn, $sql);
                while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
                    $json[] = $row;
                }
        }
        $conn = null;
        return $json;
    }

    static function parseReturnExtSQLfromarray($tablename, $colArray, $aggrtype, $whereArray = array())
    {
        $colstatement = '';

        $colstatement .= "`" . $tablename . "`.`" . $colArray['labels'] . "` as `labels`,";
        $colstatement .= "ROUND(" . (!is_null($aggrtype) && $aggrtype != 'NONE' ? $aggrtype . "(" : '');
        $colstatement .= "`" . $tablename . "`.`" . $colArray['values'] . "`" . (!is_null($aggrtype) && $aggrtype != 'NONE' ? ")" : '') . ",2) as `values`";

        $wherestatement = '';

        foreach ($whereArray as $key => $value) {
            if ($wherestatement !== '') {
                $wherestatement .= ' AND ';
            }
            $equatorIdx = array_search($value->filterequator,  ["EQUAL","LESSTHAN", "GREATERTHAN", "LESSTHANEQUAL", "GREATERTHANEQUAL", "NOTEQUAL", "EMPTY"]);
            $equatorvalue = ["=","<", ">", "<=", ">=", "!=", "IS NULL"][$equatorIdx];

            $wherestatement .= "`" . $tablename . "`.`" . $value->tablefield . "` " . $equatorvalue;
            $wherestatement .= $equatorvalue != "IS NULL" ? "'" . $value->filtervalue . "'" : "";
        }

        $sql_line = "SELECT " . $colstatement . " FROM `" . $tablename . "` ";
        $sql_line .= ($wherestatement != '' ? ' WHERE ' . $wherestatement : '');
        $sql_line .= (!is_null($aggrtype) && $aggrtype != 'NONE' ? ' GROUP BY ' . "`" . $tablename . "`.`" . $colArray['labels'] . '`;' : '');

        return $sql_line;
    }
}
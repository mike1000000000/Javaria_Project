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
 * File: sqltrans.class.php
 * Last Modified: 8/19/19, 8:56 PM
 */

class sqltrans
{
    public static $sqlArr = array();

    public function __construct()
    {
        self::$sqlArr['mysql']['GET_TABLE_SCHEMA'] = "select table_name as tables from information_schema.tables where table_schema = '%s' order by table_name;";
        self::$sqlArr['mssql']['GET_TABLE_SCHEMA'] = "select concat(table_schema, '.', table_name) as tables from information_schema.tables where table_catalog = '%s' order by table_schema,table_name;";
        self::$sqlArr['mysql']['GET_TABLE_COLUMNS'] = "select column_name as column_name, data_type as data_type from information_schema.columns where table_schema = '%s' and table_name = '%s' order by column_name;";
        self::$sqlArr['mssql']['GET_TABLE_COLUMNS'] = "select COLUMN_NAME as column_name, DATA_TYPE as data_type from information_schema.columns where table_catalog = '%s' and concat(table_schema, '.', table_name) = '%s' order by COLUMN_NAME;";
    }
}
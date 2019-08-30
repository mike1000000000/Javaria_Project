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
 * File: sqlhandler.class.php
 * Last Modified: 8/19/19, 9:41 PM
 */

class sqlhandler
{

    public function __construct()
    {

    }


    public static function connect()
    {
        global $CFG;
        $db = mysqli_connect($CFG->DB_SERVER, $CFG->DB_USERNAME, $CFG->DB_PASSWORD, $CFG->DB_DATABASE, $CFG->DB_PORT);
        /* check connection */
        if (mysqli_connect_errno()) {
            echo "Connect failed: %s\n" . mysqli_connect_error();
            exit();
        }
        return $db;
    }

    public static function getsql($sql, $whereArray = array(), $resulttype = MYSQLI_ASSOC)
    {

        self::parsetablenames($sql);

        $conn = self::connect();
        $stmt = $conn->prepare($sql);

        $arguements = array();
        if (!empty($whereArray)) {
            $typelist = "";

            foreach ($whereArray as $key => $value) {
                $typelist .= $value['type'];
                $arguements[] = $value['value'];
            }
            $stmt->bind_param($typelist, ...$arguements);
        }
        /// Logging
        self::log_sql($sql, $arguements);

        $stmt->execute();
        $result = $stmt->get_result();

        $json = $result->fetch_all($resulttype);
        $conn = null;

        return $json;
    }

    static function pushsql($sql, $values, $whereArray = array())
    {

        self::parsetablenames($sql);

        $typelist = "";
        $arguements = array();

        foreach ($values as $key => $value) {
            $typelist .= $value['type'];
            $arguements[] = (string)$value['value'];
        }

        foreach ($whereArray as $key => $value) {
            $typelist .= $value['type'];
            $arguements[] = $value['value'];
        }
        /// Logging
        self::log_sql($sql, $arguements);

        $obj = new stdClass();

        try {
            $conn = self::connect();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($typelist, ...$arguements);

            $stmt->execute();

            $obj->result = "New record created successfully";
            $obj->insert_id = $stmt->insert_id;
        } catch (PDOException $e) {
            $obj->result = $sql . "<br>" . $e->getMessage();
            $obj->insert_id = -1;
        }
        $conn = null;
        return $obj;
    }


    static function putsql($sql)
    {
        self::parsetablenames($sql);

        $success = false;
        $conn = self::connect();

        if ($conn->query($sql) === TRUE) {
            $success = true;
        }

        $conn = null;
        return $success;
    }


    private static function parsetablenames(&$sql)
    {
        global $CFG;
        include($CFG->path . '/admin/tables.php');

        foreach ($tables as $table => $keyval) {
            $sql = str_replace("{" . $table . "}", (string)"`" . $CFG->prefix . $table . "`", $sql);
        }

        if (!isset($views)) return;

        foreach ($views as $table => $keyval) {
            $sql = str_replace("{" . $table . "}", (string)$CFG->prefix . $table, $sql);
        }
    }


    static function parseDeleteSQLfromarray($tablename, $whereArray)
    {

        $wherestatement = '';

        foreach ($whereArray as $key => $value) {
            if ($wherestatement !== '') {
                $wherestatement .= ' AND ';
            }
            $wherestatement .= "{" . $tablename . "}.`" . $value['name'] . "` = ? ";
        }

        $sql_line = "DELETE FROM {" . $tablename . "}  where " . $wherestatement;
        return $sql_line;
    }


    static function parseReplaceSQLfromarray($tablename, $valueArray)
    {

        $col_sql = '';
        $val_sql = '';

        foreach ($valueArray as $key => $value) {
            if ($col_sql !== '') {
                $col_sql .= ',';
            }
            if ($val_sql !== '') {
                $val_sql .= ',';
            }
            $col_sql .= "{" . $tablename . "}.`" . $value['name'] . "`";
            $val_sql .= '?';
        }

        $sql_line = "REPLACE INTO {" . $tablename . "} (" . $col_sql . ") VALUES ( " . $val_sql . ");";
        return $sql_line;
    }


    private static function has_string_keys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    static function parseReturnSQLfromarray($tablename, $colArray, $whereArray = array())
    {

        $colstatement = '';
        foreach ($colArray as $key) {
            if ($colstatement !== '') {
                $colstatement .= ',';
            }
            $colstatement .= "{" . $tablename . "}." . "`" . $key . "`";
        }

        $wherestatement = '';

        $useIndexKey = (bool)true;
        if (self::has_string_keys($whereArray)) {
            $useIndexKey = false;
        }

        foreach ($whereArray as $key => $value) {
            if ($wherestatement !== '') {
                $wherestatement .= ' AND ';
            }
            $wherestatement .= "{" . $tablename . "}." . "`" . ($useIndexKey ? $value['name'] : $key) . "`" . " = ? ";
        }

        $sql_line = "SELECT " . $colstatement . " FROM {" . $tablename . "} " . ($wherestatement != '' ? ' WHERE ' . $wherestatement : '') . ";";
        return $sql_line;
    }


    static function parseInsertSQLfromarray($tablename, $superArray)
    {

        $col_sql = '';
        $val_sql = '';

        foreach ($superArray as $key => $value) {
            if ($col_sql !== '') {
                $col_sql .= ',';
            }
            if ($val_sql !== '') {
                $val_sql .= ',';
            }
            $col_sql .= "{" . $tablename . "}." . "`" . $value['name'] . "`";
            $val_sql .= '?';
        }

        $sql_line = "INSERT INTO {" . $tablename . "} (" . $col_sql . ") VALUES ( " . $val_sql . ");";
        return $sql_line;
    }


    static function parseUpdateSQLfromarray($tablename, $superArray, $whereArray)
    {

        $col_sql = '';

        foreach ($superArray as $key => $value) {
            if ($col_sql !== '') {
                $col_sql .= ',';
            }
            $col_sql .= "{" . $tablename . "}." . "`" . $value['name'] . "` = ?";
        }

        $wherestatement = '';

        foreach ($whereArray as $key => $value) {
            if ($wherestatement !== '') {
                $wherestatement .= ' AND ';
            }
            $wherestatement .= "{" . $tablename . "}." . "`" . $value['name'] . "`" . " = ? ";
        }

        $sql_line = "UPDATE {" . $tablename . "} SET " . $col_sql . ($wherestatement != '' ? ' WHERE ' . $wherestatement : '') . ";";
        return $sql_line;
    }


    static function parseReturnSQLIn($tablename, $colArray, $whereIn = array(), $whereArray = array())
    {
        $colstatement = '';
        foreach ($colArray as $key) {
            if ($colstatement !== '') {
                $colstatement .= ',';
            }
            $colstatement .= "{" . $tablename . "}.`" . $key . "`";
        }

        $wherestatement = '';

        foreach ($whereIn as $key => $value) {
            if ($wherestatement !== '') {
                $wherestatement .= ' AND ';
            }
            $wherestatement .= " FIND_IN_SET( {" . $tablename . "}.`" . $value['name'] . "`, ? ) ";
        }

        foreach ($whereArray as $key => $value) {
            if ($wherestatement !== '') {
                $wherestatement .= ' AND ';
            }
            $wherestatement .= "{" . $tablename . "}.`" . $value['name'] . "` = ? ";
        }

        $sql_line = "SELECT " . $colstatement . " FROM {" . $tablename . "} " . ($wherestatement != '' ? ' WHERE ' . $wherestatement : '') . ";";
        return $sql_line;
    }

    private static function log_sql($sql, $arguements = array())
    {
        global $CFG;
        if (isset($CFG->logging) && $CFG->logging >= 2) {
            $sqldebug = $sql;
            foreach ($arguements as $value) {
                $sqldebug = preg_replace("#\?#", "'" . $value . "'", $sqldebug, 1);
            }
            $message = 'SQL: ' . $sqldebug;
            (new log())->write_log($message);
        }
    }
}
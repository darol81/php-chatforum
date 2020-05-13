<?php
require_once("tbs_mysql_class.php");

function mydb_connect()
{
    $database = new clsTbsSQL; 
    $database->Connect('localhost','<sql_user_name>','<sql_password>','<db_name>');
    return $database; 
}

function mydb_close($database)
{
    $database -> Close();
}
?>
<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check(1)== false) 
{
    header("Location: main_screen.php");
    return;
}
$user_name = $_POST["user_name"];
$password = $_POST["password"];

$mysql_conn = mydb_connect();
$data_array = $mysql_conn -> GetRow("SELECT id FROM users WHERE user_name=@1@", $user_name);
/* This ID already exists, user name exists, cannot create. */
if($data_array["id"] > 0)
{
    $_SESSION["info_text"] = "Käyttäjä ". $user_name ." on jo olemassa. Uutta tunnusta ei voitu luoda.";
    mydb_close($mysql_conn);
    header("Location: admin_create_login.php");
    return;
}
$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
$password = hash('sha512', $password.$salt);
$mysql_conn -> Execute("INSERT INTO users (user_name, real_name, email, password, salt, admin) VALUES ". 
            "(@1@, @1@, '', @2@, @3@, 0)", $user_name, $password, $salt);
mydb_close($mysql_conn);
$_SESSION["info_text"] = "Käyttäjätunnus luotu.";
header("Location: admin_create_login.php");
?>

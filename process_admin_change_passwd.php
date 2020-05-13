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
if(!$data_array)
{
    $_SESSION["info_text"] = "Käyttäjää ". $user_name ." ei ole olemassa. Ei voitu vaihtaa salasanaa.";
    mydb_close($mysql_conn);
    header("Location: admin_change_passwd.php");
    return;
}
$user_id = $data_array["id"];
$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
$password = hash('sha512', $password.$salt);

$mysql_conn -> Execute("UPDATE users SET password=@1@, salt=@2@ WHERE id=%3%", $password, $salt, 
               $user_id);            
mydb_close($mysql_conn);
$_SESSION["info_text"] = "Käyttäjän salasana vaihdettu.";
header("Location: admin_change_passwd.php");
?>

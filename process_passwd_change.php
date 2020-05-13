<?php
require_once("tbs_class.php");
require_once("functions/misc_functions.php");
sec_session_start();
if(login_check()== false) 
{
    header("Location: ./index.php");
    return;
}

$old_pass = $_POST["old_password"];
$new_pass = $_POST["new_password"];
$confirmed = $_POST["confirm_password"];
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

/* First checking that the old password is a match. */
if(handle_login_process($user_name, $old_pass))
{
    /* Then checking that new password and confirmed password is a match. */
    if($new_pass == $confirmed)
    {
        /* Getting these values to form new login string */
        $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $password = hash('sha512', $new_pass.$random_salt);
        
        /* Updating new password to the database. */
        $mysql_conn = mydb_connect();                
        $mysql_conn -> Execute("UPDATE users SET password=@1@, salt=@2@ WHERE id=%3%", $password, $random_salt, $user_id);
        mydb_close($mysql_conn);
        /* Updating the current login string. */
        $_SESSION['login_string'] = hash('sha512', $password.$ip_address.$user_browser);
        $_SESSION["info_text"] = "Salasana vaihdettu.";
    }
    else
    {
        $_SESSION["info_text"] = "Salasana ja sen vahvistus ei täsmää. Salasanaa ei vaihdettu.";
    }
}
else
{
    $_SESSION["info_text"] = "Annettu salasana oli väärin. Salasanaa ei vaihdettu.";
}                         
header("Location: passwd_change.php");
?>

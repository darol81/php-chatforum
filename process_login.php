<?php
include "functions/misc_functions.php";
sec_session_start(); // Our custom secure way of starting a php session. 
 
if(isset($_POST['user_name'], $_POST['passwd'])) 
{ 
    $user_name = $_POST['user_name'];
    $password = $_POST['passwd']; // The hashed password.
    if(handle_login_process($user_name, $password) == true) 
    {
        // working login
        header("Location: ./main_screen.php"); // works
    } 
    else 
    {
        // failed login
        header("Location: ./index.php?error=1");   // works
    }
}  
else
{
    header("Location: index.php");
}
?>

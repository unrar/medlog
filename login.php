<?php
require_once ("./include/usercontrol.php");

$u = new UserControl();

// Check if we actually want to log out
if (!empty($_GET["logout"]) && $_GET["logout"] == "true") {
  $u->LogOut();
  header("Location: index.php");
  die();
}

if(empty($_POST['username']))
{
    $u->HandleError("UserName is empty!");
    return false;
}

if(empty($_POST['password']))
{
    $u->HandleError("Password is empty!");
    return false;
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if(!$u->CheckLoginInDB($username,$password))
{
    return false;
}

session_start();

$_SESSION[$u->GetLoginSessionVar()] = $username;

header("Location: index.php");
die();
?>

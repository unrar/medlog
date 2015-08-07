<?php
/*
 * MedLog's Internal API (mAPI).
 * This is not a RESTful API, but a rather simplistic API.
 * This API is managed by MJM, and their major version numbers (1.x.x) should be equal.
 * @method: POST
 * @endpoint: $_POST["op"]
 * @return: JSON encoded response.
 * 
 * This is the API "router", and user authentification must be performed here before
 * creating the mAPI obejct.
 */

require_once("../include/api.php");
require_once("../include/usercontrol.php");

/* User auth */

$u = new UserControl();

// Notice that if the op code is "reg", we bypass authentification
if ((!$u->CheckLogin()) && ($_POST["op"] != "reg")) {
  // Send a JSON response
  header("HTTP/1.1 403 Forbidden");    
  echo json_encode(array("msg" => "Not logged in!", "status" => 403));
  die();
}


/* Now we're sure we're logged in, let's start a mAPI instance */
$sus = ($_POST["op"] == "reg") ? "something" : $_SESSION[$u->GetLoginSessionVar()];
$mia = new mAPI("POST", $_POST, $sus);

// Route to the right mAPI endpoint
$op = $_POST["op"];

if ($op == "add")
{
  $mia->addEntry();
} 
else if ($op == "rem")
{
  $mia->remEntry();
}
else if ($op == "fetch")
{
  $mia->fetchEntry();
}
else if ($op == "edit")
{
  $mia->editEntry();
}
else if ($op == "reg")
{
  $mia->registerUser();
}
else if ($op == "debug")
{
  $mia->debug();
}

?>

<?php
require_once("./include/usercontrol.php");
require_once("./include/entries.php");
/* This is a server-side PHP file! No HTML will be displayed. */

// Check if logged in, for security reasons.
$u = new UserControl();
if (!$u->CheckLogin())
{
  // Not logged-in, redirect to index
  header("Location: index.php");
  die();
}
$sess_user = $_SESSION[$u->GetLoginSessionVar()];
$e = new EntriesController($sess_user);

// First, check what we want to do
$op = $_POST["op"];

if ($op == "add")
{
  /* Adding an entry to the database!
     Parse our POST data first. */
  $drug = $_POST["drug"];
  $dose = $_POST["dose"];
  $dose_unit = $_POST["dose_unit"];
  $datetime = $_POST["datetime"];
  $comment = $_POST["comment"];
  $e->AddEntry($sess_user, $drug, $dose, $dose_unit, $datetime, $comment);
  header("Location: index.php?message=Entry added correctly!");
  die();
} else if ($op == "rem")
{
  /* Check if it's our entry */
  $user = $e->GetEntryUser($_POST["id"]);
  if ($user != $sess_user && $user != false ) {
    $e->HandleError("You don't own the entry you specified! It belongs to $user's MedLog.");
    exit;
  } else if ($user == false) {
    $e->HandleError("The specified entry doesn't exist!");
    exit;
  }
  if($e->RemEntry($_POST["id"])) {
    header("Location: index.php?message=Entry removed correctly!");
    die();
  }
}
else if ($op == "edit")
{
  $drug = $_POST["drug"];
  $dose = $_POST["dose"];
  $dose_unit = $_POST["dose_unit"];
  $datetime = $_POST["datetime"];
  $comment = $_POST["comment"];

  if($e->EditEntry($_POST["id"], $sess_user, $drug, $dose, $dose_unit, $datetime, $comment))
  {
    header("Location: index.php?message=Entry edited correctly!");
    die();
  }


}
?>

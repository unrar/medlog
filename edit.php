<?php
/* This file displays an "add-entry" form filled with the already existing details. */
require_once("./include/usercontrol.php");
require_once("./include/entries.php");
require_once("./include/static.php");
// Check if logged in, for security reasons.
$u = new UserControl();
$dry = new DRYHelper();
if (!$u->CheckLogin())
{
  // Not logged-in, redirect to index
  header("Location: index.php");
  die();
}
$sess_user = $_SESSION[$u->GetLoginSessionVar()];
$id = $_POST["id"];
$e = new EntriesController($sess_user);

// Check if we own the entry / it exists.
$user = $e->GetEntryUser($id);
if ($user != $sess_user && $user != false ) {
  $e->HandleError("You don't own the entry you specified! It belongs to $user's MedLog.");
  exit;
} else if ($user == false) {
  $e->HandleError("The specified entry doesn't exist!");
  exit;
}

// Now, get the details of this entry.
$query = "SELECT * FROM $e->tablename WHERE id=$id;";
$res = $e->connection->query($query);
$row = $res->fetch_assoc();
$du = $row["dose_unit"];
$dry->build_header("Edit entry");
 ?>

  <h2>Edit entry</h2>
  <div id="content">
  <form id="edit-entry" action="result.php" method="POST">
    <fieldset>
      <label for="drug">Drug name: </label>
      <input type="text" name="drug" value="<?php echo $row["drug"]; ?>"/>
      <br />

      <label for="dose">Dose: </label>
      <input type="text" size="5" name="dose" value="<?php echo $row["dose"]; ?>" />
      <input type="radio" name="dose_unit" value="mg" <?php if ($du == "mg") echo "checked";?>> mg
      <input type="radio" name="dose_unit" value="g" <?php if ($du == "g") echo "checked";?>> g
      <input type="radio" name="dose_unit" value="oz." <?php if ($du == "oz") echo "checked";?>> oz
      <br />

      <label for="datetime">Date and time: </label>
      <input type="datetime-local" name="datetime" value="<?php echo strftime('%Y-%m-%dT%H:%M:%S', strtotime($row["date"]));?>" />
      <br />

      <label for="comment">Comments: </label>
      <input type="text" name="comment" value="<?php echo $row["comment"]; ?>"/>
      <br />

      <input type="hidden" name="op" value="edit" />
      <input type="hidden" name="id" value="<?php echo $id;?>" />
      <input type="submit" value="Submit" />
    </fieldset>
  </form>
</div>
</body>
</html>

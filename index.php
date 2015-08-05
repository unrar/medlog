<?php

/* Main page of MedLog. Displays the brief site details,
   login form and last entries of the user */
require_once("./include/usercontrol.php");
require_once("./include/entries.php");
require_once("./include/static.php");
$u = new UserControl();
$dry = new DRYHelper();

// Check login before sending headers
$checklogin = $u->CheckLogin();
$dry->build_header();
if (!empty($_GET["message"])) {
  ?>
  <div id="message"><?php echo $_GET["message"]; ?></div>
  <?php
}
 ?>
<h1>MedLog</h1>
<div id="site-info">
  <p>MedLog provides an easy-to-use medication/drug log for our users. Log every drug you take!
    MedLog also analyses your logged data to create powerful statistics regarding your drug use.</p>
</div>
<div id="content">
  <?php
  /* Check if the user has already logged in */
  if (!$checklogin) {

    /* Log-in form */
  ?>
  <div id="login-area">
    <p>Please, log in to use our web logging service.</p>
    <form id="login" action="login.php" method="POST">
      <fieldset>
        <input type='hidden' name='submitted' id='submitted' value='1'/>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" />
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" />
        <input type="submit" name="Submit" value="Submit" />
      </fieldset>
    </form>
    <p>If you don't own an account yet, <a href="register.php?do=show_form">register now!</a></p>
  </div>
  <?php
  }
  else {
    $sess_user = $_SESSION[$u->GetLoginSessionVar()];
    /* Content to be displayed if the user is logged in */
  ?>
  <!-- START LOGGED IN HTML -->

  <p id="welcome-user">Welcome <?php echo $sess_user; ?>, you've been successfully logged in!
    Your last 5 entries are shown below.</p>
    <?php
    /* Get all the FIRST FIVE entries by this user */
    $ent = new EntriesController($sess_user);
    $res = $ent->FindEntries();
    if ($res != false) {
    $dry->build_table_header();

      //$rows = []; Holds the 5 last rows of the user
      $count = 0;
      while (($row = $res->fetch_assoc()) && ($count < 5))
      {
        //array_push($rows, $row);
        $dry->build_table_row($row);
        $count++;
      }
      echo "</table>";
      // Reverse the rows from newer to older
      // $rows = array_reverse($rows);
      // Iterate over those rows
      // $dry->build_table_rows($rows);
    }
    ?>
    <!-- End dynamic PHP content -->
  <!-- Manage your MedLog -->
  <?php $dry->build_links(); ?>
  <!-- END LOGGED IN HTML -->
  <?php
  }
  ?>
</div>
<?php $dry->build_footer(); ?>

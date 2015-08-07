<?php
require_once("./include/usercontrol.php");
require_once("./include/static.php");
$u = new UserControl();
$dry = new DRYHelper();
$dry->build_header("Registration");
 ?>
 <h1>Registration</h1>
  <div id="content">
  <!-- File-specific script -->
  <script>
  // Main function
    $(document).ready(function() {
      // Validate the form
      mjm.setForm("#reg-form");
      mjm.watchForm();
    });
  </script>

  <!-- /File-specific script -->
    <form id="reg-form">
      <fieldset>
        <label for="username">Username: </label>
        <input type="text" id="username" name="username" required min-length="3"/>
        <br />

        <label for="password">Password: </label>
        <input type="password" id="password" name="password" required min-length="3"/>
        <br />

        <label for="email">E-mail: </label>
        <input type="text" name="email" id="email" required email="true"/>
        <br />

        <label for="realname">Real name: </label>
        <input type="text" name="realname" id="realname" required min-length="3"/>
        <br />

        <input type="hidden" name="op" value="reg" />
        <input type="submit" id="submit-btn" value="Submit"/>
      </fieldset>
    </form>
    <div id="incoming"></div>
  </div>

<?php
  $dry->build_links();
  $dry->build_footer();
?>


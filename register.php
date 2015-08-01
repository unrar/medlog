<?php
require_once("./include/usercontrol.php");
require_once("./include/static.php");
$u = new UserControl();
$dry = new DRYHelper();
$dry->build_header("Registration");
 ?>
 <h1>Registration</h1>
  <div id="content">
<?php
if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["realname"]) && empty($_GET["do"]))
{
  $reg_ok = $u->registerUser($_POST["username"], $_POST["password"], $_POST["email"], $_POST["realname"]);
  if ($reg_ok) {
    echo "<p class=\"reg-success\">Registration completed correctly! <a href=\"index.php\">Go back to main page</a>.</p>";
  }
}
else if ($_GET["do"] == "show_form")
{
  ?>
    <form id="reg-form" action="register.php" method="POST">
      <fieldset>
        <label for="username">Username: </label>
        <input type="text" name="username" />
        <br />

        <label for="password">Password: </label>
        <input type="password" name="password" />
        <br />

        <label for="email">E-mail: </label>
        <input type="text" name="email" />
        <br />

        <label for="realname">Real name: </label>
        <input type="text" name="realname" />
        <br />

        <input type="submit" value="Submit" />
      </fieldset>
    </form>
  <?php
}
 ?>
    </div>
  </body>
</html>

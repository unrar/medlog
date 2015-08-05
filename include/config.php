<?php
/* MedLog configuration file. Added in v0.3 to ease custom installation. 
   **EDIT THIS!***/
class Settings
{
  // MySQL configuration
  var $config;
  function Settings()
  {
   $this->config["database"] = "medlog";     // SQL Database name
   $this->config["sqlhost"] = "localhost";   // SQL Host name
   $this->config["sqluser"] = "root";        // SQL User name
   $this->config["sqlpassword"] = "";// SQL Password 

  // Site configuration
   $this->config["sitename"] = "MedLog";     // Site name
   $this->config["siteurl"] = "http://medlog.io"; // Site URL 
   $this->config["randkey"] = "0iQx5oBk66oVZep";  // Random key for user identification (don't change it unless you're sure about it)
}
}
?>

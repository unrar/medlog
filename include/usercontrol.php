<?php
// Import config
/* Class that control users: registration, logins, cookies... */
require_once("config.php");
class UserControl {

    /* Properties */
    var $connection;
    var $tablename;
    var $rand_key;
    var $s;

    /* Initialize */
    function __construct() {
        $this->s = new Settings();

        $this->tablename = "users";
        //$this->rand_key = "0iQx5oBk66oVZep";
        $this->rand_key = $this->s->config["randkey"];
    }


    /* Function to handle an error */
    function HandleError($err_desc)
    {
        echo "<p class=\"error\">An error has occurred: " . $err_desc . "</p>\n";
    }

    /* Function to log-in in the database */

    function DBLogin()
    {

        $conn = new mysqli($this->s->config["sqlhost"], $this->s->config["sqluser"],
                           $this->s->config["sqlpassword"], $this->s->config["database"]);
        if ($conn->connect_error)
        {
            return false;
        } else
        {
            $this->connection = $conn;
            return true;
        }

    }

    function CheckLoginInDB($username,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $username = $this->SanitizeForSQL($username);
        $pwdmd5 = md5($password);
        $qry = "Select name, email from " . $this->tablename .
            " where username='". $username . "' and password='" . $pwdmd5 . "';";

        $result = $this->connection->query($qry);

        if(!$result || $result->num_rows <= 0)
        {
            $this->HandleError("Error logging in. ".
                "The username or password does not match");
            return false;
        }
        return true;
    }

    /* Log in an user */
    function Login($username, $password)
    {

        $username = trim($username);
        $password = trim($password);

        if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }

        session_start();

        $_SESSION[$this->GetLoginSessionVar()] = $username;

        return true;
    }


    /* Helper functions */
    function CheckLogin()
    {
        if(!isset($_SESSION)){
            session_start();
        }

         $sessionvar = $this->GetLoginSessionVar();

         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
              $ret_str = $this->connection->real_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    function LogOut()
    {
      session_start();
      $sessionvar = $this->GetLoginSessionVar();
      $_SESSION[$sessionvar]=NULL;
      unset($_SESSION[$sessionvar]);
    }
    function user_exists($username)
    {
      $this->DBLogin();
      $query = "SELECT * FROM $this->tablename WHERE username='$username';";
      $res = $this->connection->query($query);
      if(!$res || $res->num_rows <= 0)
      {
        return false;
      } else {
        return true;
      }
    }
    function registerUser($username, $password, $email, $realname)
    {
      if ($this->user_exists($username)) {
        return false;
      }
      $this->DBLogin();
      $pwdmd5 = md5($password);
      $query = "INSERT INTO `users`(`username`, `password`, `name`, `email`) VALUES ('$username','$pwdmd5','$realname','$email');";
      $res = $this->connection->query($query);
      if ($res)
      {
        return true;
      }
      else {
        return false;
      }
    }

 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);
        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }
        return $str;
    }
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }
}


?>

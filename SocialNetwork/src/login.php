<!DOCTYPE html>
<html>
<head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 51%;
  padding: 12px 20px;
  margin: 5px 0px 23px 300px;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.errors
{
  margin: 25px 0px 13px 400px;
  color: red;
}

button {
  background-color: #000080;
  color: white;
  padding: 14px 20px;
  cursor: pointer;
  margin: 25px 0px 13px 295px;
  width: 52%;
}

button:hover {
  opacity: 0.8;
}

.logo {
  text-align: center;
  margin: 25px 0 13px 0;
}

img.logo1 {
  width: 50%;
  border: 40%;
}

.UsernameC {
  padding: 14px;
}

span.psw {
  float: right;
  padding-top: 14px;
}

@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
}
</style>
</head>
<body>
<form method="post">
  <div class="logo">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/The_social_network.svg/1196px-The_social_network.svg.png" alt="logo" class="logo1">
  </div>
  <div class="errors">
  </div>
  <div class="UsernameC">
    <label for="uname" style="margin: 25px 0px 13px 300px;"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required><br/>

    <label for="psw" style="margin: 25px 0px 13px 300px;"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required><br/>
        
    <button type="submit" action="login.php" name="login_user">Login</button>
  </div>
</form>

<?php
  session_start();
  $username = $password = ""; // Define and initialize variables with empty values

  if(isset($_POST['login_user']))
  {
    $username = trim($_POST["uname"]);
    $password = trim($_POST["psw"]);
    $password = md5($password);

    try 
    {
        
        $db = mysqli_connect('localhost','root','','network') or die("could not connect to the database");
        $query = "SELECT * FROM `users` WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db,$query);
       
        if(mysqli_num_rows($results))  
        {
              $_SESSION["loggedin"] = true;
              $_SESSION["success"] = "Logged in successfully";
              $_SESSION["username"] = $username; 
              header("location: network.php");
        }
        else
        {
              echo '<div class="errors">';
              echo "Invalid username/password,please try again with right credentials";
              echo '</div>';
        }

    } 
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

  }
 ?>
</body>
</html>
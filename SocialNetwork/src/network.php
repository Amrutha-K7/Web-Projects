<html>
<head>
  <title>Home Page</title>
  <style>
    .user_details
    {
        position: relative;
        padding-left: 20px;
        padding-top: 30px;
        padding-bottom: 20px;
        padding-right: 20px; 
        line-height: 0.8px; 
        background: rgb(28, 28, 65);
        color:cornsilk;
        width: 1230px;
        height: 70px;
    }
    .friends
    {
      position: relative;
      padding-left: 20px;
      line-height: 0.5px;
      padding-top: 10px;
      background: grey;
      color:cornsilk;

    }
    .nonfriends
    {
      position: relative;
      padding-left: 20px;
      line-height: 0.5px;
      padding-top: 10px;
      background: grey;
      color:aquamarine;
    }

  </style>
</head>
<body>

<?php

session_start();

if(!isset($_SESSION['username'])) //if the user is not loggedin, redirect him to login page
{
    echo "<p>"."You must be logged in to view the content"."</p>"."<br/>";
    echo "<button type='submit' action='login.php' method='GET'>"."<a href='login.php'>Login</a></button>"."<br/>"."<br/>";
    exit;
}
if(isset($_GET['logout'])) //if the user clicks on logout, redirect him to login page and clear the variables
{
  session_destroy();
  unset($_SESSION['username']);
  $_SESSION["loggedin"] = false;
  header("location: login.php");
}

  $usrname = $_SESSION['username'];
  $db = mysqli_connect('localhost','root','','network') or die("could not connect to the database");
  $query = "SELECT * FROM `users` WHERE username='$usrname'";
  $results = mysqli_query($db,$query);
  $usr = mysqli_fetch_assoc($results);
  if($usr)
  {
    //Admin user details
    echo '<div class="user_details">';
    echo "<h2>"."Welcome ".$usr['username']."...!!"."</h2>";
    echo "<h3>".$usr['fullname']."</h3>";
    echo "<h3>".$usr['email']."</h3>";
    echo "</div>";

   //friends list
    $query1 = "SELECT * FROM `friends` WHERE user='$usrname'";
    $friends = mysqli_query($db,$query1);

    echo '<div class="friends">';
    echo "<p style='font-weight: bold; font-size:18px;'>"."Friends of ".$usrname." :"."</p>"."<br/>"."<br/>";
    echo "</div>";

    while($row = mysqli_fetch_assoc($friends))
    {
      $friend = $row['friend'];
      $query2 = "SELECT * FROM `users` WHERE username='$friend'";
      $results1 = mysqli_query($db,$query2);
      $usr1 = mysqli_fetch_assoc($results1);

      echo '<div class="friends">';
      echo $friend."<br/>";
      echo "<p>".$usr1['fullname']."</p>";
      echo "<p>".$usr1['email']."</p>";
      echo "<button type='submit' action='network.php' method='GET'>"."<a href='network.php?removefriend=$friend'>Remove</a>"."</button>"."<br/>"."<br/>";
      echo "</div>";
    }

    //non friends list
    $query4 = "SELECT * FROM `users` WHERE username!='$usrname' AND username not in (SELECT friend FROM `friends` WHERE user='$usrname')";
    $nonfriends = mysqli_query($db,$query4);

    echo '<div class="nonfriends" >';
    echo "<p style='font-weight: bold; font-size:18px;'>"."Non Friends of ".$usrname." :"."</p>"."<br/>"."<br/>";
    echo "</div>";

    while($rownon = mysqli_fetch_assoc($nonfriends))
    {
      $nonfriend = $rownon['username'];
      $nonfullname = $rownon['fullname'];
      $nonemail = $rownon['email'];
      
      echo '<div class="nonfriends">';
      echo $nonfriend."<br/>";
      echo "<p>".$nonfullname."</p>";
      echo "<p>".$nonemail."</p>";
      echo "<button type='submit' action='network.php' method='GET'>"."<a href='network.php?addfriend=$nonfriend'>Add</a>"."</button>"."<br/>"."<br/>";
      echo "</div>";
    }

  }

  //removing a friend
  if(isset($_GET['removefriend']))
  {
    $rmfriend = $_GET['removefriend'];
    $query3 = "DELETE FROM `friends` WHERE user='$usrname' AND friend='$rmfriend'";
    $results3 = mysqli_query($db,$query3);
    header("location: network.php");
  }
   //adding a friend
   if(isset($_GET['addfriend']))
   {
     $addfriend = $_GET['addfriend'];
     $query4 = "INSERT INTO `friends`(`user`, `friend`) VALUES ('$usrname', '$addfriend') ";
     $results4 = mysqli_query($db,$query4);
     header("location: network.php");
   }

?>
<div class="user_details">
<button type="submit" action="network.php" name="logout" method="GET" style="font-size:18px;"><a href="network.php?logout='1'">Logout</a></button>
</div>
<div class="friends"></div>
<div class="nonfriends"></div>
</body>
</html>
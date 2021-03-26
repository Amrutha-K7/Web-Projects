
<!DOCTYPE html>
<html>
<head>
<style>

    .label1
    {
        font-size: 16px;
        color:cornsilk;
        font-weight: bold;
        padding-left: 20px;
        margin-top: 50px;
    }
    .browsefile
    {
        font-size: 16px;
        color:cornsilk;
        font-weight: bold;
        padding-left: 4px;
        margin-top: 30px;
    }
    
    .imageup
    {
        font-size: 16px;
    }

    .form1
    {
        margin-bottom: 20px;
        padding-bottom: 40px;
        padding-top: 20px;
    }
    .first
    {
        padding-bottom: 10px;
        padding-top: 10px;

    }
    .second
    {
        padding-bottom: 10px;
        padding-top: 10px;
        float: left;
        position: absolute;
        left:500px; 
        top:180px;
    }

    
</style>
</head>
<body style = "background:white">

<div class=form1 style = "background: #1F2833;">
  <form action="album.php" method="POST" enctype="multipart/form-data">
  <label class = "label1">Select image:</label>
  <input type="file" name="fileToUpload" id="fileToUpload" class="browsefile">
  <input type="submit" value="Upload Image" name="Upload_Image" class ="imageup">
  </form>
</div>

<div class="first" >
</div>
<div class="second">
</div>

<?php

// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','On');
require_once 'demo-lib.php';
demo_init();
set_time_limit( 0 );
require_once 'DropboxClient.php';

$dropbox = new DropboxClient( array(
	'app_key' => "rxuvh456im0koqs",      // Put your Dropbox API key here
	'app_secret' => "nvppk8tfay4w50f",   // Put your Dropbox API secret here
	'app_full_access' => false,
) );


$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";

$bearer_token = demo_token_load( "bearer" );
if ( $bearer_token ) {
	$dropbox->SetBearerToken( $bearer_token );
} elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
{
	// get & store bearer token
	$bearer_token = $dropbox->GetBearerToken( null, $return_url );
	demo_store_token( $bearer_token, "bearer" );
} elseif ( ! $dropbox->IsAuthorized() ) {
	// redirect user to Dropbox auth page
	$auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
	die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}

if(isset($_POST['Upload_Image']))
{
    $image =  basename($_FILES["fileToUpload"]["name"]); 
    $dropbox->UploadFile("$image");
}

if(isset($_GET['removeImage']))
{
  $rmImage = $_GET['removeImage'];
  $dropbox->Delete("/$rmImage");
}

$files = $dropbox->GetFiles( "", false );
foreach(array_keys( $files ) as $file) 
{
    echo '<div class="first">';
    echo "<ul><li style='list-style-type:square;' >";
    echo "<a href='album.php?retrieve=$file' style='font-size:18px; font-weight: bold; text-decoration:none; color:black; line-height:1.6; margin-left:20px;'>$file</a>    ";
    echo "<button type='submit' action='album.php' method='GET' style='text-decoration:none; background: #1F2833; color: cornsilk; font-size:16px;' >"."<a href='album.php?removeImage=$file' style='text-decoration:none; background: #1F2833; color: cornsilk;'>Delete</a>"."</button>";
    echo "</li></ul>";
    echo '</div>';
}

if(isset($_GET["retrieve"]))
{
    $retrieveimage = $_GET["retrieve"];
    $imageDisplay= $dropbox->GetLink( $retrieveimage );

    $imageDisplay = explode("?", $imageDisplay);
    $rawlink = $imageDisplay[0]."?raw=1";

    echo "<div class='second'>";
    echo "<img src=\"$rawlink\" id='movie_poster' width='600' height='340'  style='border: 1px solid black;'/>";
    echo '</div>';
}



?>

</body>
</html>
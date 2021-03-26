<html>
<head>
<title>Display Movie Information</title>
<style type="text/css">



</style>
</head>
<body>
<div style="width:cover; height: 100px; background-color:rgb(28, 28, 65);">
<form action="movies.php" method="get">
    <label style="font-size:18px; margin-left:20px; font-weight: bold; color:cornsilk;">Movie title: <input type="text" name="forminput" style="font-size:16px; margin-top:20px;"/></label>
    <input type="submit" value="Display Info"  style="font-size:18px;"/>
</form>
</div>
<div id="leftbox"  class="first">
</div>
<div id="rightbox"  class="second">
</div>
<div id="img"  class="image">
</div>

<br>

<?php

$api_key = "992836ae12ece3726858c45b3bc96e41"; // put your TMDb API key here:
$method = "/3/search/movie";
$inputtitle="";
$jsonresponse="";
$movie_id="";
$jsonresponse1="";
$diplay_title="";
$poster="";
$movie_overview="";
$movie_genres="";
$jsonresponse2="";
$cast_names="";
define("NEXTLINE", "<br/>");

if(isset($_GET["forminput"]))
{
  $inputtitle = $_GET["forminput"];
  $url = "https://api.themoviedb.org$method?api_key=$api_key&language=en-US&query=$inputtitle&page=1&include_adult=false&format=json";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json')); //setting header
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  $jsonresponse = json_decode($response,true);

  foreach($jsonresponse["results"] as $res) 
  {
    $date= $res['release_date']; //fetching release date
    $date ? $movie_title_temp = $res["title"] ."- ".substr($date,0,4) : $movie_title_temp=$res["title"]; //movie title with release date

    $movie_title_temp = "<li>".$movie_title_temp."</li>";
    $movie_id = $res["id"];

    echo '<div class="first" style="font-size:16px; line-height:1.65; margin-left:20px;" >';
    echo "<a href='movies.php?set_id=$movie_id' style='font-size:16px; line-height:1.65; list-style-type: square; text-decoration:none;'>$movie_title_temp</a>";
    echo '</div>';

  }
curl_close($ch);
}

if(isset($_GET["set_id"]))
{

$id= $_GET["set_id"];
$url1 = "https://api.themoviedb.org/3/movie/$id?api_key=$api_key&language=en-US&format=json";
$ch1 = curl_init($url1);
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Accept: application/json')); //setting header
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
$response1 = curl_exec($ch1);
$jsonresponse1 = json_decode($response1,true);
if(isset($jsonresponse1))
{
  $diplay_title= $jsonresponse1["title"];   //fetching movie title
  $poster = $jsonresponse1["poster_path"];  //fetching poster path
  $poster ? $movie_poster = "http://image.tmdb.org/t/p/w185".$poster : $movie_poster="http://www.movienewz.com/img/films/poster-holder.jpg";
  $movie_overview = $jsonresponse1["overview"];  //fetching movie overview
 
  foreach($jsonresponse1["genres"] as $gen)
  {
    $movie_genres .= $gen["name"].", "; 
  }
  $movie_genres = substr($movie_genres,0,-2); //genre names
  
  echo '<div class="image" style="margin-left:10px; margin-right:10px;float:left; position: relative;">';
  echo NEXTLINE;
  echo "<img src=$movie_poster  id='movie_poster' alt=' ' width='300' height='500' />";
  echo '</div>';

  echo '<div class="second"  style="font-size:18px; line-height:1.65; margin-left:20px;">';
  echo NEXTLINE;
  echo "Movie Title: ".$diplay_title.NEXTLINE;
  echo NEXTLINE;
  echo "Movie Genres: ".$movie_genres.NEXTLINE;
  echo NEXTLINE;
  echo "Movie Summary: ".NEXTLINE.$movie_overview;
  echo NEXTLINE;
  echo NEXTLINE;
  echo '</div>';
  

}
curl_close($ch1);


$url2 = "https://api.themoviedb.org/3/movie/$id/credits?api_key=$api_key&format=json";
$ch2 = curl_init($url2);
curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
$response2 = curl_exec($ch2);
$jsonresponse2 = json_decode($response2,true);
if($jsonresponse2["cast"])
{
  for ($i = 0; $i < 5; $i++) 
  {
    if(isset($jsonresponse2["cast"][$i]["name"]))
    {
      $cast_names .=$jsonresponse2["cast"][$i]["name"].", ";
    }
    
  }
  $cast_names = substr($cast_names,0,-2); // top 5 cast names

}
echo '<div class="second" style="font-size:18px; line-height:1.65; margin-left:10px;" >';
echo "Movie Cast: ".$cast_names;
echo '</div>';
curl_close($ch2);
}

?>
</body>
</html>
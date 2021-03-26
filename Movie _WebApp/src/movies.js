
   var movie_titles;
   var movie_id;
   var movie_overview;
   var movie_poster;
   var movie_genres;
   var json;
   var json_response;
   let movie_title_id;
   var cast_names;
   var cast_response;

function initialize () {

   movie_overview = "";
   movie_poster = "";
   movie_genres = "";
   movie_titles = "";
   movie_id = "";
   json_response = "";
   movie_title_id = new Map();
   cast_names="";
   cast_response="";
   document.getElementById('movie_poster').src="";
   document.getElementById('title').innerHTML="";
   document.getElementById('genre').innerHTML="";
   document.getElementById('overview').innerHTML="";
   document.getElementById("cast").innerHTML="";

}

function sendRequest () {
   initialize(); //handling a negative case where when user searches for the next movie, we should clear the right window
   var xhr = new XMLHttpRequest();
   var query = encodeURI(document.getElementById("form-input").value);
   xhr.open("GET", "proxy.php?method=/3/search/movie&query=" + query);
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) 
       {
          json = JSON.parse(this.responseText); //fetching the json response

          for (i in json.results) {

            var date= json.results[i].release_date; //fetching release date
            var movie_title_temp="";
            
            //handling a negative case where release date is empty string("") example: insidious movie
            (date!=null && date!="")? movie_title_temp=json.results[i].title +"- "+date.substring(0,4): movie_title_temp=json.results[i].title; //appending the release date to the movie title
            movie_titles += "<li>"+movie_title_temp+"</li>"; //taking all the movie titles into an unordered list
            movie_id = json.results[i].id;
         
            movie_title_id.set(movie_title_temp,movie_id); // putting movie title and movie id into the map

          }

         document.getElementById("leftbox").innerHTML = "<ul class=\"movie_list\">"+ movie_titles +"</ul>"; //appending all the movie titles to the left window(div)

       }
   };
   xhr.send(null);

}

  function fetchDetails() {

   movie_genres = "";//clearing this so that every time user clicks on next movie, genres of previous movie should not be appended to the current movie
   cast_names="";

   var xhra = new XMLHttpRequest();
   var movie_clicked = window.getSelection().focusNode.data; //fetching movie title of the movie which has been selected/clicked among the list of movies
   var query_temp = encodeURI(movie_title_id.get(movie_clicked)); //fetching movie id of the selected movie from the map object & encoding it
   var query_id = String(query_temp);

   console.log("selected movie..."+movie_clicked);
   console.log("selected movie id..."+query_id);

   xhra.open("GET", "proxy.php?method=/3/movie/"+query_id);
   xhra.setRequestHeader("Accept","application/json");
   xhra.onreadystatechange = function () {

            if (this.readyState == 4) {
               json_response = JSON.parse(this.responseText);

               var poster = json_response.poster_path;//fetching poster path
               
               //handling a negative case where poster_path is null example: titanic movie. if it is not null then prefixing it with given string else displaying poster not found image
               (poster!=null && poster!="")? movie_poster = "http://image.tmdb.org/t/p/w185"+poster : movie_poster="http://www.movienewz.com/img/films/poster-holder.jpg";
               
               console.log("movie poster path.... "+movie_poster);
               movie_overview = json_response.overview;  //fetching movie overview

               for(i in json_response.genres)
               {
                  movie_genres +=json_response.genres[i].name+", "; // comma separated genre names with extra comma at the end
               }
               movie_genres = movie_genres.slice(0,-2) //Removing the extra comma at the end of all the genres
               
               //Appending movie poster, title, genre and overview for the particular movie to the right div window
               document.getElementById('movie_poster').src = movie_poster;
               document.getElementById('title').innerHTML = "Movie Title: "+json_response.title+"<br/>"+"<br/>";
               document.getElementById('genre').innerHTML = "Movie Genre: "+movie_genres+"<br/>"+"<br/>";
               document.getElementById('overview').innerHTML = "Movie Summary: "+"<br/>"+json_response.overview+"<br/>"+"<br/>";

            }
         };
         xhra.send(null);

         
         var xhrc = new XMLHttpRequest();
         xhrc.open("GET", "proxy.php?method=/3/movie/"+query_id+"/credits");
         xhrc.setRequestHeader("Accept","application/json");
         xhrc.onreadystatechange = function () {
            if (this.readyState == 4) {

                 cast_response = JSON.parse(this.responseText);

                 for(i=0; i<5;i++) //taking top 5 cast names
                 {
                    cast_names +=cast_response.cast[i].name+", "; // comma separated cast names with extra comma at the end
                 }
                 cast_names = cast_names.slice(0,-2) //Removing the extra comma at the end of top five cast names
                 document.getElementById("cast").innerHTML =  "Movie Cast: "+cast_names;

            }

         }
         xhrc.send(null);

      
  }
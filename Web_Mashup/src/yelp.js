
var business_details;
var latitude;
var longitude;
let map;
var xhr;
var json;
var script;
var initial_position;
var count;
var markers=[];
var bounds;

function initialize () 
{
    business_name="";
    latitude="";
    longitude="";
    initial_position="";
    business_details="";
    xhr="";
    json="";
    count=0;
    document.getElementById("leftbox").innerHTML = "";
}

script = document.createElement('script');
script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCExUFws_-FpxeqH518H9K9xaMDyscCnGI&callback=initMap';
script.defer = true;

function initMap()                                             // Attaching callback function to the `window` object
{
    initial_position = { lat: 32.75, lng: -97.13 };            // Given initial position co-ordinates
    map = new google.maps.Map( document.getElementById("map"), { center: initial_position, zoom:16}); // The map, positioned at given initial position

};
document.head.appendChild(script);  // Appending the 'script' element to 'head' of html

function setMapOnAll(map) // Sets the map on all markers in the array.
{
   for (let i = 0; i < markers.length; i++) 
   {
     markers[i].setMap(map);
   }
}
function clearMarkers() { // Removes the markers from the map
   setMapOnAll(null);
 }


function sendRequest () 
{
   initialize();       //clearning all the variables and left window before next search
   clearMarkers();     //clearing all previous markers before next search

   xhr = new XMLHttpRequest();
   var query = encodeURI(document.getElementById("search").value); //User input business name/search term
   var queryBounds = map.getBounds(); //get the map bounds : NE (LatLng) and SW (LatLng)
   var ne = queryBounds.getNorthEast();
   var sw = queryBounds.getSouthWest();
   var radius = getRadius(ne.lat(),ne.lng(),sw.lat(),sw.lng()); //computing radius using Haversine formula

   xhr.open("GET", "proxy.php?term="+query+"&latitude="+queryBounds.getCenter().lat()+"&longitude="+queryBounds.getCenter().lng()+"&radius="+radius+"&limit=10"); //yelp business fusion api for business search
   xhr.setRequestHeader("Accept","application/json");

   xhr.onreadystatechange = function ()
   {
       if (this.readyState == 4) {
          json = JSON.parse(this.responseText);
          bounds = new google.maps.LatLngBounds(); //creating bounds object

          business_details = json.businesses.map(businesses =>{   //looping through the top 10 businesses to find the name,rating and yelp redirect url and the business image
            count++
            longitude = businesses.coordinates.longitude; //fetching longitude and latitude parameters from the yelp JSON to position the markers on the map
            latitude  = businesses.coordinates.latitude;
            var position = { lat: latitude, lng: longitude };
            var marker = new google.maps.Marker({position: position, map: map, icon:"http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+count+"|D33B1C|000000"}); //Adding customized markers with numbers on it
            //bounds.extend(position); //setting the coordinates of the business to the bounds object
            markers.push(marker);    //adding each marker on the map into an array

            var infoWindow = new google.maps.InfoWindow({content: businesses.name}); // adding info window to show the name of the business when we mouseover on the marker
            marker.addListener('mouseover', function(){ infoWindow.open(map, marker)}); //adding event listener to listen to the mouseover event on any busniess marker
            marker.addListener('mouseout', function(){ infoWindow.close(map, marker)}); //adding event listener to listen to the mouseout event on any busniess marker, so that infowindow is displayed only when mouse is hovered
            
            return `<li>
                     <div class="Businesses">
                     <img src="${businesses.image_url}"  id="food"/>
                     <h4><a href="${businesses.url}">${businesses.name}</a></h4>
                     <p>Rating: ${businesses.rating}</p>
                     </div>
                     </li>`;
          }).join("");

          //map.fitBounds(bounds); // this adjusts the map in such a way that all the markers of the business should be visible, im not doing this becuase we need to search within visble map area
          document.getElementById("leftbox").innerHTML = "<ol class=\"business_list\">"+business_details+"</ol>"; //appending top 10 the business details to the left window(div)
       
      }
   };
   xhr.send(null);
}

function getRadius(nlat,nlng,slat,slng) {

   var R = 6371; // Radius of the earth in km
   var dLat = degreeTOradians(slat-nlat);  // calls the function to convert degree to radians
   var dLon = degreeTOradians(slng-nlng); 
   var a = 
     Math.sin(dLat/2) * Math.sin(dLat/2) +
     Math.cos(degreeTOradians(nlat)) * Math.cos(degreeTOradians(slat)) * 
     Math.sin(dLon/2) * Math.sin(dLon/2); 

   var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
   var d = R * c; // Distance in km

   var radius_temp = parseInt((d*1000)/3); // diving by 3 to make sure my radius value approximation searches within the visible map
   console.log("Radius value for the specified map area: "+radius_temp);

   if(radius_temp > 40000) //max radius value supported by yelp fusion api is 40000 meters (about 25 miles).
    radius_temp=40000; //rounding off the radius to 40000m if its more than 40k

   return radius_temp;
 }
 
 function degreeTOradians(degree) 
 {
   return degree * (Math.PI/180);
 }
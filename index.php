
<!DOCTYPE html>
<html>
  <head>
    <title>seeUaround</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
   



    <script>


      function initMap() {
        
        //to make map
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 28.6139, lng: 77.2090},
          zoom: 8,
        });
       
        var input =(document.getElementById('pac-input'));
        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        var infowindow = new google.maps.InfoWindow();
        
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });
        //to make map
        

         var directionsService = new google.maps.DirectionsService;
         var directionsDisplay = new google.maps.DirectionsRenderer;
         directionsDisplay.setMap(map);




        $('#table1').append('<tr><td><b><u>NearBy Places</u></b></td></tr>');       
          

        //autocomplete
        autocomplete.addListener('place_changed', function() {
              
              infowindow.close();
              marker.setVisible(false);
              var place = autocomplete.getPlace()
               
               //autocomplete places  
              var geocodingAPI = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location="+place.geometry.location.lat()+","+place.geometry.location.lng()+"&radius=1000&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";


                  $('#table1 tr').empty();
                  $('#table1').append('<tr><td><b><u>NearBy Places</u></b></td></tr>');
                

                //json loop
                $.getJSON(geocodingAPI, function (json) {
                    var nearplace;
                     for (nearplace in json.results)
                      {
                        var data = json.results[nearplace];
                        //console.log(data);
                        map.setCenter(data.geometry.location);

                        map.setZoom(16);
                        var myIcon = {
                        url: data.icon, //url
                        scaledSize: new google.maps.Size(20, 20) };
                        
                        var marker = new google.maps.Marker({
                        position: data.geometry.location,
                        icon:myIcon,
                        map: map,
                        id:data.place_id,
                        anchorPoint: new google.maps.Point(0, -29)
                        });
                       
                        


                       $('#table1 tr:last').after('<tr ><td> '+data.name+' </td></tr>');

                       
                        ////forsidebar& distance

                         

                        (function (marker, data) {
                        google.maps.event.addListener(marker, "click", function (e) {
                        //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                          
                          $('#myModal').modal('show');
                          $('.modal-title').text(data.name);
                          

                      
                        /////////////////////////////////////////

                         var url1="https://maps.googleapis.com/maps/api/place/details/json?placeid="+data.place_id+"&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";

                         $.getJSON(url1, function (json2) {

                          if (json2.result.photos!=undefined) 
                          {
                            var photox="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference="+json2.result.photos[0].photo_reference+"&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";
                            $('#addr').text(json2.result.formatted_address);
                            $('#myModal img').attr('src', photox); 
                          }
                          else
                          {
                            $('#myModal img').attr('src', "no-image"); 
                          }

                             
                          
                          if(json2.result.formatted_address!=undefined){
                              $('#addr').text(json2.result.formatted_address);
                          }
                          else
                          {
                            $('#addr').text(" Sorry!Address not Defined");
                          }


                          if(json2.result.types!=undefined){
                              $('#type').text(json2.result.types[0]);
                          }
                          else
                          {
                            $('#type').text(" Sorry!Type not Defined");
                          }

                          if(json2.result.rating!=undefined){
                              $('#rating').text(json2.result.rating);
                          }
                          else
                          {
                            $('#rating').text("----");
                          }


                          if(json2.result.formatted_phone_number!=undefined){
                              $('#phn').text(json2.result.formatted_phone_number);
                          }
                          else
                          {
                            $('#phn').text(" Sorry! Contact not mentioned");
                          }


                          if(json2.result.website!=undefined){
                              $('#website').text(json2.result.website);
                          }
                          else
                          {
                            $('#website').text(" Sorrys! Website not mentioned");
                          }
                        });
                        ////////////////////////////////////////
              
                        });
                        
                        })(marker, data);

                      }

                });
                //json loop


             

              if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
              }
               //autocomplete places  


              // If the place has a geometry, then present it on a map.
              if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
              }
               else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
              }
              marker.setIcon(/** @type {google.maps.Icon} */({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
              }));
             // marker.setPosition(place.geometry.location);
              marker.setVisible(true);
              var address = '';
              if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
              }

              infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
              infowindow.open(map, marker);
      });
        //autocomplete




        //geolocation
            var previous_location;
            if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function (location) {
            //location.coords.latitude     
            // var geocodingAPI = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=28.6139,77.2090&radius=500&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";
            var geocodingAPI = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location="+location.coords.latitude+","+location.coords.longitude+"&radius=500&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";

            


             //json loop
                $.getJSON(geocodingAPI, function (json) {
                    var nearplace;
                     for (nearplace in json.results)
                      {
                        var data = json.results[nearplace];
                        //console.log(data);
                        map.setCenter(data.geometry.location);
                        map.setZoom(16);
                        var myIcon = {
                        url: data.icon, //url
                        scaledSize: new google.maps.Size(20, 20) };
                        
                        var marker = new google.maps.Marker({
                        position: data.geometry.location,
                        icon:myIcon,
                        map: map,
                        id:data.place_id,
                        anchorPoint: new google.maps.Point(0, -29)
                        });


                        


                        $('#table1 tr:last').after('<tr ><td> '+data.name+' </td></tr>');

                       
                        ////forsidebar& distance
                         



                        

                        (function (marker, data) {
                        google.maps.event.addListener(marker, "click", function (e) {
                        //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                          
                          $('#myModal').modal('show');
                          $('.modal-title').text(data.name);
                          

                      
                        /////////////////////////////////////////


                        

                         var url1="https://maps.googleapis.com/maps/api/place/details/json?placeid="+data.place_id+"&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";

                         $.getJSON(url1, function (json2) {

                          if (json2.result.photos!=undefined) 
                          {
                            var photox="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference="+json2.result.photos[0].photo_reference+"&key=AIzaSyDF6Ws2ReGw-4e_1Tno9_jV3JjgPeh08zo";
                            $('#addr').text(json2.result.formatted_address);
                            $('#myModal img').attr('src', photox); 
                          }
                          else
                          {
                            $('#myModal img').attr('src', "no-image"); 
                          }

                             
                          
                          if(json2.result.formatted_address!=undefined){
                              $('#addr').text(json2.result.formatted_address);
                          }
                          else
                          {
                            $('#addr').text(" Sorry!Address not Defined");
                          }


                          if(json2.result.types!=undefined){
                              $('#type').text(json2.result.types[0]);
                          }
                          else
                          {
                            $('#type').text(" Sorry!Type not Defined");
                          }

                          if(json2.result.rating!=undefined){
                              $('#rating').text(json2.result.rating);
                          }
                          else
                          {
                            $('#rating').text("----");
                          }


                          if(json2.result.formatted_phone_number!=undefined){
                              $('#phn').text(json2.result.formatted_phone_number);
                          }
                          else
                          {
                            $('#phn').text(" Sorry! Contact not mentioned");
                          }


                          if(json2.result.website!=undefined){
                              $('#website').text(json2.result.website);
                          }
                          else
                          {
                            $('#website').text(" Sorrys! Website not mentioned");
                          }
                        });
                        ////////////////////////////////////////
              
                        });
                        
                        })(marker, data);

                      }

                });
                //json loop


        });
      }
      //geolocation
//map
}

                      
    </script>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDML2NAuWndTUnYF9hj4GB-jVRbIfwX_O0&libraries=places&callback=initMap"
        async defer>
          
    </script>

      </head>
  <body>

    <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
       
    <div id="sidebar" >
      <div id="titles" align="center">
        <h3>seeUaround</h3>
      </div>
      <div >
        <table class="table table-hover " id="table1" align="center" >

  </table>

      </div>

    </div>   
    
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <div >
               <img src="" style="width:100%; max-height: 300px; " >
            </div>
           <hr>
            <div>
              
              <table class="table table-hover" align="center" style="text-align:center;">
    
                <tbody>
                  <tr>
                    <td><span><b>Type: </b></span><span id="type" ></span></td>
                    
                  </tr>
                  <tr>
                    <td><span><b>Rating: </b></span><span id="rating"></span></td>
                    
                  </tr>
                  <tr>
                    <td><span><b>Address: </b></span><span id="addr"></span></td>
                    
                  </tr>
                   <tr>
                    <td><span><b>Phone Number: </b></span><span id="phn"></span></td>
                    
                  </tr>
                   <tr>
                    <td><span><b>Website: </b></span><span id="website"></span></td>
                    
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="map"></div>

    
  </body>
</html>
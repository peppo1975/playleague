<?

if(isset($latitudine) && isset($longitudine)):

if(isset($info) && !empty($info)) {

	$php_markers = '<ul style="list-style-type: none; padding-left: 0px;">';
	$php_markers .= '<li><b>'.$info['Descrizione'].'</b></li>';
	if(isset($info['Indirizzo']) && $info['Indirizzo'] != '') $php_markers .= '<li>'.$info['Indirizzo'].'</li>';
	if(isset($info['Citta']) && $info['Citta'] != '') 		  $php_markers .= '<li>'.$info['Citta'].' '.(($info['Provincia'] == '')? $info['Provincia']:'('.$info['Provincia'].')').'</li>';
	if(isset($info['Telefono']) && $info['Telefono'] != '')   $php_markers .= '<li>tel. '.$info['Telefono'].'</li>';
	if(isset($info['Email']) && $info['Email'] != '')         $php_markers .= '<li>email <a href="mailto:'.$info['Email'].'">'.$info['Email'].'</a></li>';
	$php_markers .= '</ul>';
	
	$strip_markers = strip_tags($php_markers);
	
	if($strip_markers == '') $php_markers = '';
	
}

?>
<script type="text/javascript">
  var map;
  var panorama;
  var astorPlace = new google.maps.LatLng(-25.746418, 28.188041);
  
   var rendererOptions = {
    draggable: true,
    markerOptions: {
		visible: false
	},
	polylineOptions: {
		strokeColor: '#C9181C'
	}
  };
  
  var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
  var directionsService = new google.maps.DirectionsService();
  
     var infowindow;
(function () {

  google.maps.Map.prototype.markers = new Array();
    
  google.maps.Map.prototype.addMarker = function(marker) {
    this.markers[this.markers.length] = marker;
  };
    
  google.maps.Map.prototype.getMarkers = function() {
    return this.markers
  };
    

})();

  var myMarkes = null;
  
  function initialize() {

    var a = new Array();
    var t =  new Object();
    t.lat =  '<?=$latitudine;?>';
    t.lng =  '<?=$longitudine;?>';
	var markers_php = '<?=$php_markers;?>';
	if(markers_php != '') {
    t.displayText = 
    
    '<div class="marker"><?=$php_markers;?></div>';
	}
	
    //t.show = 0;
      
		t.show = 1;
	    a[0] = t;
    
    // ,
  
    var mapOptions = {
	  center: new google.maps.LatLng(a[0].lat, a[0].lng),
      zoom: 13,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: true
    };
    map = new google.maps.Map(document.getElementById('map'),
        mapOptions);
        
    directionsDisplay.setMap(map);
    panorama = map.getStreetView();
	

   
    for (var i = 0; i < a.length; i++) {
        var latlng = new google.maps.LatLng(a[i].lat, a[i].lng);
        
        var thisMarker = createMarker('',latlng,a[i].show,i);
        
        map.addMarker(thisMarker);
     
		a[i].markerObject = thisMarker;
     
		//if (a[i].show == 1) $(".view-address").html(a[i].displayText);
     
     }

    myMarkers = a;
   
  }
  
  function createMarker(name, latlng,show,markerIndex) {
	var iconname = '/img/pin-porto.png';
	if (show == 1) iconname = '/img/pin-porto.png';
    var image = new google.maps.MarkerImage(iconname,
// This marker is 20 pixels wide by 32 pixels tall.
      new google.maps.Size(26, 58),
      new google.maps.Point(0,0),
      new google.maps.Point(26, 58));


    var marker = new google.maps.Marker({
	position: latlng, 
	map: map,
	icon: image,
	});

    if (show == 1) {
      
    //  if (infowindow) infowindow.close();
      infowindow = new google.maps.InfoWindow({content: '<?=$php_markers;?>',                        size: new google.maps.Size(200,140)
});
      
	  infowindow.open(map.getStreetView().getVisible() ?
                      map.getStreetView() : map, marker);
	  
	}

    google.maps.event.addListener(marker, "click", function(change) {


	  infowindow.open(map.getStreetView().getVisible() ?
                      map.getStreetView() : map, marker);
	  

    });

    return marker;
	
  }
  
  function calcRoute(from) {
  
	for (var i = 0; i < myMarkers.length;i++) {
		
		if (myMarkers[i].show == 1) {
		
				var latlng = new google.maps.LatLng(myMarkers[i].lat, myMarkers[i].lng);

				var request = {
				  origin: latlng,
				  destination: from,
				  travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
				directionsService.route(request, function(response, status) {
				  if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(response);
				  }
				});
			
		}
		
	}
	
  }
  
  $(function() {
  
	$("#timmy_close").bind('click', function(){
		
		location.reload();
		
	});  
  
	$("#form-route input.text").focus(function() {
	
		if ($(this).attr('data-used') == undefined) {
			
			$(this).attr('data-used',$(this).val());
			$(this).val('');
			
		}
		
	});
	
	$("#form-route input.text").blur(function() {
		
		if ($.trim($(this).val()) == "") $(this).val($(this).attr('data-used'));
		
	});
	
	$("#form-route form").submit(function(e) {
	
		if ($("#form-route input.text").attr('data-used') == undefined || $.trim($("#form-route input.text").val()) == '') return false;
		
		e.preventDefault();
		
		var from = $(this).find('input.text').val();
		
		calcRoute(from);
		
		return false;
		
	});
	
	$("#form-route input.text").keyup(function(e){
	
		if(e.keyCode == 13) {
		
			$("#form-route form").submit();
		
		}
	
	});
	  
  });
  
 $(function(){
 
	$('.view-address').hide();
 
 }); 

 $(document).ready(function() { 
 
	 initialize();
	 t = setTimeout(function(){$('.view-address').fadeIn('fast')},'2000');;
	 
 });
  
 </script>	
							<div class="view-address" style="display: none;"></div>
							<div class="cont-mappa" id="map" style="width: 100%; height: 420px;"></div>
							<br />
							<blockquote class="with-borders fields-filter-container">
							<div class="cont-partenza">
								<div class="cont-partenza-input" id="form-route">
									<form id="form-route-google">
										<label>Inserisci il tuo indirizzo di partenza e traccia il percorso per raggiungere l'impianto</label>
										<input type="text" class="form-control text" name="da" value="Da" onclick="this.value = ''" onfocusout="if(this.value == '') this.value = 'da'" />
									</form>
	 							</div>
	 							<div class="clear"></div>
							</div>
							</blockquote>
	 						<div class="clear"></div>

<? 

endif; 

?>
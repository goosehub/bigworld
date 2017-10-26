<!-- Google Map Element -->
<div id="map"></div>

<!-- Start Script -->
<script>
// Create overlay that will be removed when map tiles are loaded
loading = function() {
  var over = '<div id="overlay"><p>Loading...</p></div>';
  $(over).appendTo('body');
};
loading();

// Marker options
// https://sites.google.com/site/gmapsdevelopment/
var classic_marker_img = 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png';
var green_marker_img = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
var blue_marker_img = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
var red_marker_img = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
var purple_marker_img = 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png';
var yellow_marker_img = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';


// Constants
var default_marker_img = classic_marker_img;
var current_marker_img = blue_marker_img;
// Google Map Created Callback
var map;
var messages_load_interval_id;
var current_marker = false;
function initMap() {
  // Create map init
  map = new google.maps.Map(document.getElementById('map'), {

    // Map center is slightly north centric
    center: {
      lat: 20,
      lng: 0
    },

    // This level of zoom shows whole world but no repetition
    zoom: 2,

    // Prevent zooming out so much users can see north and south edge
    minZoom: 1,

    // Map type
    // mapTypeId: google.maps.MapTypeId.TERRAIN
    mapTypeId: google.maps.MapTypeId.HYBRID
    // mapTypeId: google.maps.MapTypeId.SATELLITE
  });

  // Remove overlay when map tiles are loaded
  google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
    $('#overlay').fadeOut();
  });

  // Add markers to map
  // This loop might thousands large
  // Keep this performant and minimize bytes transfered
  var markers = [];
  <?php foreach ($rooms as $room) { ?>

  // Create marker
  var location = {};
  location.lat = <?php echo $room['lat']; ?>;
  location.lng = <?php echo $room['lng']; ?>;
  marker = new google.maps.Marker({
    position: location,
    map: map,
    title: '<?php echo $room['name'] ?>',
    room_id: <?php echo $room['id'] ?>,
  });

  // Open room on click
  marker.addListener('click', marker_clicked);

  // Add to marker array
  markers[<?php echo $room['id'] ?>] = marker;
  <?php } ?>

  // If hash exists, zoom on room
  if (window.location.hash) {
    // Get room id
    var room_id = window.location.hash.replace('#', '');

    // Switch marker icons
    if (current_marker) {
      current_marker.setIcon(default_marker_img);
    }
    markers[this.room_id].setIcon(current_marker_img);
    current_marker = markers[this.room_id];

    // Zoom
    // map.setZoom(17);
    // map.panTo(markers[room_id].position);
  }

  // Trigger event on click
  google.maps.event.addListener(map, 'click', function(event) {
    open_create_room_block(event.latLng);
  });

  function marker_clicked(event) {
    // var lat = event.latLng.lat();
    // var lng = event.latLng.lng();

    // Hide any center blocks
    $('.center_block').hide();

    // Switch marker icons
    if (current_marker) {
      current_marker.setIcon(default_marker_img);
    }
    markers[this.room_id].setIcon(current_marker_img);
    current_marker = markers[this.room_id];

    // Load room
    load_room(this.room_id);
  }

  // Place marker
  function open_create_room_block(location) {
    $('.center_block').hide();
    $('#create_room_block').show();
    $('#input_room_name').val('');
    $('#input_room_name').focus();

    // Create room on enter key
    $('#input_room_name').off().on('keyup', function (e) {
      if (e.keyCode == 13 && $('#input_room_name').val()) {
        create_room(location);
      }
    });

    // Create room on button click
    $('#create_room_submit').off().click(function(){
      if ($('#input_room_name').val()) {
        create_room(location);
      }
    });
  }

  function create_room(location) {
    var url = 'room/create';
    var data = {};
    data.lat = location.lat();
    data.lng = location.lng();
    data.room_name = $('#input_room_name').val();
    ajax_post(url, data, function(result){
      if (result.error) {
        alert(result.error_message);
        return false;
      }
      $('.center_block').hide();
      // Create marker
      var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: result.name,
        room_id: result.id,
      });

      // Open room on click
      marker.addListener('click', marker_clicked);

      // Add to marker array
      markers[result.id] = marker;

      // Switch marker icons
      if (current_marker) {
        current_marker.setIcon(default_marker_img);
      }
      markers[result.id].setIcon(current_marker_img);
      current_marker = markers[result.id];

      // Load room
      load_room(result.id);
    });
  }

}
</script>

<!-- Google Maps Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_lT8RkN6KffGEfJ3xBcBgn2VZga-a05I&callback=initMap" async defer>
</script>
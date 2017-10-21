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

// Google Map Created Callback
var map;
var messages_load_interval_id;
function initMap() {
  // Create map init
  map = new google.maps.Map(document.getElementById('map'), {

    // Map center is slightly north centric
    center: {
      lat: 20,
      lng: 0
    },

    // This level of zoom shows whole world but no repetition
    zoom: 3,

    // Prevent zooming out so much users can see north and south edge
    minZoom: 2,

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
  // console.log(markers);

  // Trigger event on click
  google.maps.event.addListener(map, 'click', function(event) {
    open_create_room_block(event.latLng);
  });

  function marker_clicked(event) {
    // var lat = event.latLng.lat();
    // var lng = event.latLng.lng();
    $('.center_block').hide();
    $('#room_parent').fadeIn();

    // Initial Load Messages
    clearInterval(messages_load_interval_id);
    messages_load(this.room_id, true);
    var load_interval = 3000;
    messages_load_interval_id = setInterval(function() {
      messages_load(this.room_id, false);
    }, load_interval);
  }

  // Place marker
  function open_create_room_block(location) {
    $('.center_block').hide();
    $('#create_room_block').show();
    $('#input_room_name').val('');
    $('#input_room_name').focus();
    $('#create_room_submit').click(function(){
      create_room(location);
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
        map: map
      });
    });
  }

}
</script>

<!-- Google Maps Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_lT8RkN6KffGEfJ3xBcBgn2VZga-a05I&callback=initMap" async defer>
</script>
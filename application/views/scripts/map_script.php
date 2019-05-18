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

// Map room polling
var map_room_polling_seconds = <?php echo MAP_ROOM_POLLING_SECONDS; ?>;
var current_last_activity_slug = '<?php echo $current_last_activity_filter['slug']; ?>';

// Constants for markers
var default_marker_img = blue_marker_img;
var current_marker_img = red_marker_img;
var favorite_marker_img = green_marker_img;
// Google Map Created Callback
var map;
var messages_load_interval_id;
var current_marker = false;
var markers = [];
var map_default_zoom = <?php echo MAP_DEFAULT_ZOOM; ?>;
var map_default_lat = <?php echo MAP_DEFAULT_LAT; ?>;
var map_default_lng = <?php echo MAP_DEFAULT_LNG; ?>;
function initMap() {
    // var defaultMapType = google.maps.MapTypeId.TERRAIN
    var defaultMapType = google.maps.MapTypeId.HYBRID;
    // var defaultMapType = google.maps.MapTypeId.SATELLITE

    // Create map init
    map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: map_default_lat,
            lng: map_default_lng
        },
        zoom: map_default_zoom,
        minZoom: 1,
        mapTypeId: defaultMapType
    });

    // Remove overlay when map tiles are loaded
    google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
        $('#overlay').fadeOut();
    });

    // Add markers to map
    // This loop might be thousands large
    // Keep this performant and minimize bytes transfered
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
        icon: default_marker_img
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

        // Zoom
        // map.setZoom(17);
        // map.panTo(markers[room_id].position);
    }

    // Trigger event on click
    google.maps.event.addListener(map, 'click', function(event) {
        if (!user) {
            $('#register_block').fadeIn();
            return false;
        }
        open_create_room_block(event.latLng);
    });

    // Map zoom in and out
    $('#zoom_in_button').click(function(){
        $('#zoom_in_button').hide();
        $('#zoom_out_button').show();

        // Recreate map init
        map = new google.maps.Map(document.getElementById('map'), {
            // Room location
            center: {
                lat: current_marker.getPosition().lat(),
                lng: current_marker.getPosition().lng()
            },
            // Zoom in just enough for slanted view
            zoom: 18,
            minZoom: 1,
            mapTypeId: defaultMapType
        });
        // Reload markers
        load_map_rooms();
    });

    // Map zoom in and out
    $('#zoom_out_button').click(function(){
        $('#zoom_out_button').hide();
        $('#zoom_in_button').show();

        // Recreate map init
        map = new google.maps.Map(document.getElementById('map'), {
            // Room location
            center: {
                lat: map_default_lat,
                lng: map_default_lng
            },
            // Zoom in just enough for slanted view
            zoom: map_default_zoom,
            minZoom: 1,
            mapTypeId: defaultMapType
        });
        // Reload markers
        load_map_rooms();
    });

    // Load map rooms on interval
    setInterval(function(){
        load_map_rooms();
    }, map_room_polling_seconds * 1000);

    function load_map_rooms() {
        var url = 'main/load_map_rooms?world_id=' + world_id + '?last_activity=' + current_last_activity_slug;
        ajax_get(url, function(result){
            // Remove existing markers
            Object.keys(markers).forEach(function(key) {
                markers[key].setMap(null);
            });

            // current_marker_room_id = current_marker.room_id;

            $.each(result, function(i, room) {
                var location = {};
                location.lat = parseFloat(room.lat);
                location.lng = parseFloat(room.lng);
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: room.name,
                    room_id: parseInt(room.id),
                    icon: default_marker_img
                });
                if (room.id == current_marker.room_id) {
                    marker.setIcon(current_marker_img);
                    current_marker = marker;
                }

                // Open room on click
                marker.addListener('click', marker_clicked);

                // Add to marker array
                markers[parseInt(room.id)] = marker;
            });
        });
    }

    function marker_clicked(event) {
        // var lat = event.latLng.lat();
        // var lng = event.latLng.lng();

        // Hide any center blocks
        $('.center_block').hide();
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
        data.world_id = world_id;
        data.room_name = $('#input_room_name').val();
        data.world_key = $('#input_world_key').val();
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
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
}
</script>

<!-- Google Maps Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_lT8RkN6KffGEfJ3xBcBgn2VZga-a05I&callback=initMap" async defer>
</script>
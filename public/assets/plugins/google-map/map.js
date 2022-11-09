var map = null;
var marker;

function showlocation() {
    if ("geolocation" in navigator) {
        /* geolocation is available */
        // One-shot position request.
        navigator.geolocation.getCurrentPosition(callback, error);
    } else {
        /* geolocation IS NOT available */
        console.warn("geolocation IS NOT available");
    }
}

function error(err) {
    console.warn('ERROR(' + err.code + '): ' + err.message);
};

function callback(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;
    document.getElementById('default_latitude').value = lat;
    document.getElementById('default_longitude').value = lon;
    var latLong = new google.maps.LatLng(lat, lon);
    map.setZoom(16);
    map.setCenter(latLong);
}
google.maps.event.addDomListener(window, 'load', initMap);



function initMap() {

    var initialLat = document.getElementById('default_latitude').value;
    var initialLong = document.getElementById('default_longitude').value;

    var mapOptions = {
        center: new google.maps.LatLng(initialLat, initialLong),
        zoom: 20,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });


    google.maps.event.addListener(map, 'center_changed', function() {
        document.getElementById('default_latitude').value = map.getCenter().lat();
        document.getElementById('default_longitude').value = map.getCenter().lng();
    });

    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        places.forEach(function(place) {
            map.setCenter(place.geometry.location);
            console.log(place.name);
            //place.name
        });
        // // Clear out the old markers.
        // markers.forEach(function(marker) {
        //     marker.setMap(null);
        // });
        // markers = [];
        //
        // // For each place, get the icon, name and location.
        // var bounds = new google.maps.LatLngBounds();
        // places.forEach(function(place) {
        //     if (!place.geometry) {
        //         console.log("Returned place contains no geometry");
        //         return;
        //     }
        //     var icon = {
        //         url: place.icon,
        //         size: new google.maps.Size(71, 71),
        //         origin: new google.maps.Point(0, 0),
        //         anchor: new google.maps.Point(17, 34),
        //         scaledSize: new google.maps.Size(25, 25)
        //     };
        //
        //     // Create a marker for each place.
        //     markers.push(new google.maps.Marker({
        //         map: map,
        //         icon: icon,
        //         title: place.name,
        //         position: place.geometry.location
        //     }));
        //
        //     if (place.geometry.viewport) {
        //         // Only geocodes have viewport.
        //         bounds.union(place.geometry.viewport);
        //     } else {
        //         bounds.extend(place.geometry.location);
        //     }
        // });
        // map.fitBounds(bounds);
    });

    $('<div/>').addClass('centerMarker').appendTo(map.getDiv())
    //do something onclick
        .click(function() {
            var that = $(this);
            if (!that.data('win')) {
                that.data('win', new google.maps.InfoWindow({
                    content: 'this is the center'
                }));
                that.data('win').bindTo('position', map, 'center');
            }
            that.data('win').open(map);
        });
}
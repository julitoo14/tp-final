
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.6037, lng: -58.3816},
        zoom: 12
    });

    var geocoder = new google.maps.Geocoder;

    map.addListener('click', function(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        geocodeLatLng(geocoder, lat, lng);
    });

    function geocodeLatLng(geocoder, lat, lng) {
        var latlng = {lat: lat, lng: lng};
        geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    var components = results[0].address_components;
                    var country = '';
                    var city = '';

                    for (var i = 0; i < components.length; i++) {

                        if (components[i].types.includes('country')) {
                            country = components[i].long_name;
                        }

                        if (components[i].types.includes('sublocality')) {
                            city = components[i].long_name;
                        }

                        if (components[i].types.includes('locality') && !city) {
                            city = components[i].long_name;
                        }

                    }

                    document.getElementById('country').value = country;
                    document.getElementById('city').value = city;
                } else {
                    window.alert('No results found');
                }
            } else {
                window.alert('Geocoder failed due to: ' + status);
            }
        });
    }
}

<!DOCTYPE html>
<html>
<head>
    <title>Geolocation</title>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4NOvmOrbvQsORoBew2RO9drpvwrXVJh8&v=weekly" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        getLocation(); 

        function getLocation() {
            const websocket = new WebSocket('ws://localhost:6001/app/c0e40e9eb9ca9ef1fea0');

            websocket.addEventListener('open', function (event) {
                const options = {
                    enableHighAccuracy: true, 
                    timeout: 3000, 
                    maximumAge: 0 // Force the device to get the current location
                };
                navigator.geolocation.watchPosition(
                    (position) => {
                        userId = {{ auth()->user()->id }};
                        console.log( position.coords.latitude, position.coords.longitude, position.coords.accuracy ,userId);

                        const dataToSend = {
                            "event": "client-message",
                            "data": {
                                "lat": position.coords.latitude,
                                "lng": position.coords.longitude,
                                "userId": userId
                            },
                            "channel": "private-location"
                        };

                        websocket.send(JSON.stringify(dataToSend));

                    }, 
                    (error) => { 
                        console.error('Geolocation error:', error);
                    },
                    options 
                );
            });
            websocket.addEventListener('error', function (event) {
                console.error('WebSocket error:', event);
            });
        }
    });
</script>


</body>
</html>

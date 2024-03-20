<!DOCTYPE html>
<html>
<head>
    <title>Geolocation</title>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4NOvmOrbvQsORoBew2RO9drpvwrXVJh8&v=weekly" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        getLocation(); 

        function getLocation() {
            let ws = new WebSocket('ws://localhost:6001/app/c0e40e9eb9ca9ef1fea0');
            ws.onopen = function(){
                ws.onmessage = (incomeMsg) => {
                    const message = JSON.parse(incomeMsg.data);
                    switch (message.event) {
                        case "pusher:connection_established":
                            const socketData = JSON.parse(message.data);
                            const channel_name = "private-location";
                            const socket_id = socketData.socket_id;
                            const secret = "{{ config( 'app.pusher_app_secret' ) }}";

                            let wordArrayData = CryptoJS.enc.Utf8.parse( socket_id + ":" + channel_name );
                            let auth = CryptoJS.HmacSHA256(wordArrayData, secret);

                            auth = ":" + CryptoJS.enc.Hex.stringify(auth);

                            let msg = {
                                "event":"pusher:subscribe",
                                "data":{
                                    "auth": auth,
                                    "channel": channel_name
                                }
                            };


                            ws.send(JSON.stringify(msg));

                            break;
                        case "client-message":
                            console.log("Client Message: " + JSON.stringify( message.data ) );
                            break;
                    }
                };
            }     
        }
    });
</script>


</body>
</html>

<?php
include('db.php');

// Provjera da li je korisnik ulogovan
$likedEvents = array();
if (isset($_SESSION['userID'])) {
    // Dobijanje ID-a korisnika iz sesije
    $userId = $_SESSION['userID'];

    // Dohvatanje lajkovanih događaja za trenutnog korisnika iz baze
    $query = "SELECT EventID FROM followedevents WHERE UserID = $userId";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $likedEvents[] = $row['EventID'];
        }
    }
}

$query = "SELECT e.eventID, e.eventName, e.date, v.venueName, v.location, e.price, e.imgPath FROM event e 
          JOIN venue v ON e.venueID = v.venueID";

$result = mysqli_query($conn, $query);

?>

<main>
    <div class="w-100 position-relative bg-black text-white bg-cover d-flex align-items-center">
        <div class="cover" data-aos="fade-up">
            <div class="cover-slide">
                <?php
                if ($result) {
                    while ($event = mysqli_fetch_assoc($result)) {
                        $eventId = $event['eventID'];
                        $eventName = $event['eventName'];
                        $date = $event['date'];
                        $venueName = $event['venueName'];
                        $location = $event['location'];
                        $price = $event['price'];
                        $imgPath = $event['imgPath'];


                        // Provjera da li je trenutni događaj lajkovan
                        $heartClass = (in_array($eventId, $likedEvents)) ? 'bi-heart-fill' : 'bi-heart';
                        /*ZVUK sam makao
                            <i class="bi bi-play-circle" id="audioIcon-' . $eventId . '" onclick="toggleAudioWave(' . $eventId . ')"></i>
                            <div class="loader playing" id="audioLoader-' . $eventId . '">
                            <svg id="wave-' . $eventId . '" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 38.05">
                                <title>Audio Wave</title>
                                <path id="Line_1" data-name="Line 1" d="M0.91,15L0.78,15A1,1,0,0,0,0,16v6a1,1,0,1,0,2,0s0,0,0,0V16a1,1,0,0,0-1-1H0.91Z" />
                            </svg>
                            </div>
                        */

                        if (isset($_SESSION['userID'])) {
                            // Ako je prijavljen, preusmjeri ga na oneEvent.php
                            echo '<div class="cover-item" style="cursor:pointer;">
                                    <div class="cover-bg" >
                                        <img src="' . $imgPath . '" style="width: 230px; height: 230px;" onclick="redirectToEvent(' . $eventId . ')">
                                        <p class="ime">' . $eventName . '</p>
                                        <p class="opis">' . date('D, M j', strtotime($date)) . ' </br> ' . $venueName . ', ' . $location . '<div style="color: rgb(195,0,56); font-size: medium; position: relative; top:-15px; left:0px;">' . $price . ' €</div></p>
                                        <i class="bi ' . $heartClass . '" id="heartIcon-' . $eventId . '" onclick="toggleHeartIcon(' . $eventId . ')"></i>
                                    </div>
                                </div>';
                        } else {
                            // Ako nije prijavljen, preusmjeri ga na login.php
                            echo '<div class="cover-item" style="cursor:pointer;">
                                    <div class="cover-bg" >
                                        <img src="' . $imgPath . '" style="width: 230px; height: 230px;" onclick="redirectToLogin()">
                                        <p class="ime">' . $eventName . '</p>
                                        <p class="opis">' . date('D, M j', strtotime($date)) . ' </br> ' . $venueName . ', ' . $location . '<div style="color: rgb(195,0,56); font-size: medium; position: relative; top:-15px; left:0px;">' . $price . ' €</div></p>
                                        <i class="bi ' . $heartClass . '" id="heartIcon-' . $eventId . '" onclick="toggleHeartIcon(' . $eventId . ')"></i>
                                    </div>
                                </div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</main>

<script>
    function redirectToEvent(eventId) {
        window.location.href = 'oneEvent.php?eventId=' + eventId;
    }

    function redirectToLogin() {
        window.location.href = 'login.php';
    }

    // // Funkcija za inicijalizaciju Spotify SDK
    // function initializeSpotifySDK(eventId) {
    //     window.onSpotifyWebPlaybackSDKReady = () => {
    //         const token = 'BQBeZxEuRkVrDflNNCH6FCJJ02rFypJPGRggDyXfEMuwJ8mQNuFqO58Etxzmr7RGfFIE5fR6Et9Ko1VMKaBipvOtO-17Tc-0LwOB2nNxZ7ZEmVfmgYIeJbW4zoilU18DxUs2S95-c904DXi1O_m8RY6nTqGqSrrlERbTqsvQFIkFW09XyDq4O5PVKKUhlEgXonQLAuhFdI-bYVvCHg2kadiDa_dT';

    //         const player = new Spotify.Player({
    //             name: 'Eventus',
    //             getOAuthToken: cb => {
    //                 cb(token);
    //             }
    //         });

    //         // Povezivanje sa Spotify player-om
    //         player.connect().then(success => {
    //             if (success) {
    //                 console.log('The Web Playback SDK successfully connected to Spotify!');
    //                 playSpotifyTrack(player, eventId);
    //             }
    //         });

    //         // Slušanje događaja kada se promeni stanje reprodukcije
    //         player.addListener('player_state_changed', state => {
    //             console.log('Current track:', state.track_window.current_track);
    //         });
    //     };
    // }

    // // Funkcija za puštanje određene pesme sa Spotify-a
    // function playSpotifyTrack(player, eventId) {
    //     // Trebate dobiti URI pesme (Spotify identifikator pesme) iz Spotify API-ja
    //     const trackURI = 'spotify:track:2LtCEKs68u3RpNh4wybCF8';

    //     // Puštanje pesme
    //     player
    //         .play({
    //             uris: [trackURI]
    //         })
    //         .then(() => {
    //             console.log('Playing track with URI:', trackURI);
    //         })
    //         .catch(error => {
    //             console.error('Error playing track:', error);
    //         });
    // }

    // // Funkcija za puštanje muzike kada se klikne na bi-play-circle ikonu
    // function toggleAudioWave(eventId) {
    //     initializeSpotifySDK(eventId);
    // }


    function toggleHeartIcon(eventId) {
        var heartIcon = document.getElementById('heartIcon-' + eventId);

        // Provjera da li je korisnik ulogovan
        var isUserLoggedIn = <?php echo isset($_SESSION['userID']) ? 'true' : 'false'; ?>;

        if (!isUserLoggedIn) {
            // Ako korisnik nije ulogovan, preusmjeri ga na login.php
            window.location.href = 'login.php';
            return; // Prekini izvršavanje funkcije
        }

        if (heartIcon.classList.contains('bi-heart')) {
            // Korisnik je lajkovao event
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Promjena ikone srca nakon uspješnog lajkovanja
                    heartIcon.classList.remove('bi-heart');
                    heartIcon.classList.add('bi-heart-fill');
                    console.log('Liked Event ID: ' + eventId);
                }
            };
            xhttp.open("GET", "php/add_like.php?event_id=" + eventId, true);
            xhttp.send();
        } else {
            // Korisnik poništava lajk eventa
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Promjena ikone srca nakon uspješnog poništavanja lajka
                    heartIcon.classList.remove('bi-heart-fill');
                    heartIcon.classList.add('bi-heart');
                    console.log('Unliked Event ID: ' + eventId);
                }
            };
            xhttp.open("GET", "php/add_like.php?event_id=" + eventId, true);
            xhttp.send();
        }
    }
</script>
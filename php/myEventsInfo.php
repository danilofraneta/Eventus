<?php
include('db.php');

// Provjera da li je korisnik prijavljen
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    // Upit za dohvaćanje rezervacija korisnika
    $getUserReservationsQuery = "SELECT * FROM reservation WHERE userID = $userID";
    $userReservationsResult = mysqli_query($conn, $getUserReservationsQuery);

    // Provjera da li korisnik ima rezervacije
    if ($userReservationsResult && mysqli_num_rows($userReservationsResult) > 0) {
?>
        <link rel="stylesheet" href="css/confirm.css">
        <div class="loader"></div>
        <div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
            <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
            <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
                <div class="row">
                    <div class="col"><br>
                        <h5>YOUR TICKETS:</h5>
                        <ul>
                            <?php
                            // Ispis rezervacija korisnika
                            while ($row = mysqli_fetch_assoc($userReservationsResult)) {
                                // Dohvatanje informacija o rezervaciji
                                $eventId = $row['eventID'];
                                $price = $row['price'];
                                $resNumber = $row['resNumber'];

                                // Upit za dohvatanje informacija o događaju
                                $getEventInfoQuery = "SELECT e.eventName, e.date, v.venueName, v.location, e.imgPath FROM event e JOIN venue v ON e.venueID = v.venueID WHERE e.eventID = $eventId";
                                $eventInfoResult = mysqli_query($conn, $getEventInfoQuery);
                                if ($eventInfoResult && mysqli_num_rows($eventInfoResult) > 0) {
                                    $eventInfo = mysqli_fetch_assoc($eventInfoResult);
                                    $eventName = $eventInfo['eventName'];
                                    $date = $eventInfo['date'];
                                    $venueName = $eventInfo['venueName'];
                                    $location = $eventInfo['location'];
                                    $imgPath = $eventInfo['imgPath'];

                                    // Prikaz informacija o rezervaciji
                                    echo '<div class="row jedan" onclick="redirectToEvent(' . $eventId . ')">
                                            <div class="col col-lg-2">
                                                <img src="' . $imgPath . '" class="img-fluid" style="width: 130px; height: 130px;">
                                            </div>
                                            <div class="col-md-auto"><br>
                                                <p>' . $eventName . '<br>
                                                <a style="color: #FBF719">' . date('D, M j', strtotime($date)) . '</a><br>
                                                ' . $venueName . ', ' . $location . '<br>
                                                </p>
                                            </div>
                                            <div class="col col-lg-2"><br>
                                                <p style="padding-top:10px;"><b>' . $price . ' €</b><br>
                                                <a style="color:#a9a9a9">' . $resNumber . '</a><br>
                                                </p>
                                            </div>
                                            <div class="col col-lg-2"><br>
                                                <button class="iks" onclick="cancelReservation(' . $row['resID'] . ')">
                                                    <span class="replies">Cancel ticket</span>
                                                    <span class="comment"><i class="bi bi-x-circle"></i></span>
                                                </button>
                                            </div>
                                        </div>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener("load", () => {
                const loader = document.querySelector(".loader");

                loader.classList.add("loader--hidden");

                loader.addEventListener("transitionend", () => {
                    document.body.removeChild(loader);
                });
            });

            function cancelReservation(reservationID) {
                if (confirm("Are you sure you want to cancel reservation?")) {
                    // AJAX poziv za brisanje rezervacije iz baze
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Osvežavanje stranice nakon brisanja rezervacije
                            window.location.reload();
                        }
                    };
                    xhttp.open("GET", "php/cancel_reservation.php?reservationID=" + reservationID, true);
                    xhttp.send();
                }
            }
        </script>
<?php
    } else {
        // Ako korisnik nema rezervacija, prikaži odgovarajuću poruku
        echo '  <link rel="stylesheet" href="css/confirm.css">
                <div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
                    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
                    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
                        <div class="row">
                            <div class="col"><br>
                                <h5>YOUR TICKETS:</h5>
                                <p class="kraj">You haven\'t bought any tickets, <a href="eventsUser.php" style="color: #888;">explore events now!</a></p>
                                
                            </div>
                        </div>
                    </div>
                </div>';
    }
} else {
    // Ako korisnik nije prijavljen, prikaži odgovarajuću poruku ili preusmjeri na stranicu za prijavu
    echo "You are not logged in.";
}
?>
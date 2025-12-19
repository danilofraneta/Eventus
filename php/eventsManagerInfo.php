<?php
include('db.php');

$query = "SELECT DISTINCT location FROM venue";
$result = mysqli_query($conn, $query);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="scss/liked.scss">
<link rel="stylesheet" href="css/search.css">
<div class="container-fluid px-vw-5 position-relative" data-aos="fade">
    <div class="position-relative py-vh-5 bg-cover bg-center rounded-5">
        <div class="container bg-black px-vw-5 py-vh-3 rounded-5 shadow">

            <div class="row d-flex align-items-center">
                <div class="row d-flex align-items-center">
                    <?php
                    // Prikaz svih događaja bez filtriranja
                    $query = "SELECT e.eventID, e.eventName, e.date, v.venueName, v.location, e.price, e.imgPath FROM event e JOIN venue v ON e.venueID = v.venueID";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $imgPath = $row['imgPath'];
                            $eventId = $row['eventID'];
                            $eventName = $row['eventName'];
                            $date = $row['date'];
                            $venueName = $row['venueName'];
                            $location = $row['location'];
                            $price = $row['price'];
                            echo '<div class="events">
                                    <div class=slikaSrce>
                                    <a onclick="redirectToLogin()">
                                        <img src="' . $imgPath . '" class="img-fluid" style="width: 245px;height: 245px;">
                                    </a>
                                    </div>
                                    <div class="opisEventa">
                                        <p>' . $eventName . '<br>
                                            <a style="color: #FBF719">' . date('D, M j', strtotime($date)) . '</a><br>
                                            <a>' . $venueName . ', ' . $location . '</a><br><a style="color:rgba(255, 255, 255, 0.5);">' . $price . ' €</a>
                                        </p>
                                    </div>
                                    <label class="like">
                                        <input type="checkbox" name="liked_events[]" value="' . $eventId . '"/>
                                        <div class="hearth"></div>
                                    </label>
                                </div>';
                        }
                    } else {
                        echo '<p>No events found.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function redirectToLogin(eventId) {
        window.location.href = 'login.php';
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Uhvati sve checkbox elemente
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');

        // Iteriraj kroz svaki checkbox
        checkboxes.forEach(function(checkbox) {
            // Dodaj event listener koji će reagovati kada se promijeni stanje checkbox-a
            checkbox.addEventListener('change', function() {
                // Dobijanje event ID-a iz vrijednosti checkbox-a
                var eventId = this.value;

                // AJAX zahtjev za dodavanje/uklanjanje lajka
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'php/add_like.php?event_id=' + eventId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            // Reaguj na odgovor servera ako je uspješan
                            var responseText = xhr.responseText.trim();
                            if (responseText !== "") {
                                // Prikaži poruku samo ako odgovor nije prazan
                                alert(responseText);
                            }
                        } else {
                            // Ako odgovor nije uspješan, prikaži odgovarajuću poruku
                            alert('Error: ' + xhr.statusText);
                        }
                    }
                };
                xhr.send();
            });
        });
    });


    // Funkcija za filtriranje događaja prema tipu
    function filterEvents(eventType) {
        // Provjeravamo da li je korisnik prijavljen
        <?php if (isset($_SESSION['userID'])) : ?>
            // Ako je korisnik prijavljen, koristi stranicu eventsUser.php
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'eventsUser.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'film';
            input.value = eventType;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        <?php else : ?>
            // Ako korisnik nije prijavljen, koristi stranicu event.php
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'events.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'film';
            input.value = eventType;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        <?php endif; ?>
    }



    function toggleSlider() {
        var sliderContainer = document.getElementById("sliderContainer");
        if (sliderContainer.style.display === "none") {
            sliderContainer.style.display = "block";
        } else {
            sliderContainer.style.display = "none";
        }

        // Ažurirajte vrednost u labeli "opcije"
        var slider = document.getElementById("myRange");
        var output = document.getElementById("sliderValue");
        var priceLabel = document.getElementById("priceLabel");
        output.innerHTML = slider.value;
        priceLabel.innerHTML = "Up to " + slider.value + "<i class='bi bi-currency-euro'></i>";
    }

    var slider = document.getElementById("myRange");
    var output = document.getElementById("sliderValue");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    }




    function redirectToEventsPage() {
        // Dobijanje vrednosti lokacije, datuma i cene
        var location = document.getElementById('location').value;
        var date = document.getElementById('datum').value;
        var price = document.getElementById('myRange').value;
        var userId = document.getElementById('userId').value;

        console.log(location);
        console.log(date);
        console.log(price);

        // Kreiranje URL-a sa filtriranim parametrima
        var url = 'FEBC.php?location=' + location + '&date=' + date + '&price=' + price + '&userId=' + userId;

        // Preusmeravanje na novu stranicu
        window.location.href = url;
    }
</script>

<style>
    .locations-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        /* Providna pozadina */
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .locations {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
    }

    .location-btn {
        margin: 5px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Stilizacija opcija u select polju */
    .opcije option {
        background-color: #f8f9fa;
        /* Boja pozadine */
        color: #212529;
        /* Boja teksta */
        padding: 10px;
        /* Razmak između opcija */
        border-bottom: 1px solid #dee2e6;
        /* Linija između opcija */
    }

    /* Hover efekat opcija */
    .opcije option:hover {
        background-color: #e9ecef;
        /* Boja pozadine prilikom hovera */
    }

    .custom-select {
        position: relative;
        display: inline-block;
    }

    .custom-select i {
        position: absolute;
        right: 10px;
        /* Prilagodite položaj ikone po potrebi */
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        /* Omogućava klik na select polje ispod ikone */
    }
</style>
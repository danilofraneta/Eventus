<?php
include('db.php');

// Provjeravamo da li je proslijeđen ID događaja
if (isset($_GET['eventId'])) {
    // Dobijamo ID događaja iz URL-a
    $eventId = $_GET['eventId'];

    // SQL upit za dohvatanje podataka o događaju
    $getEventDataQuery = "SELECT e.eventName, e.date, e.price, e.eventType, e.eventAbout, e.imgPath, a.artistName, a.artistAbout, v.venueName, v.location FROM Event e LEFT JOIN Artist a ON e.artistID = a.artistID LEFT JOIN Venue v ON e.venueID = v.venueID WHERE e.eventID = $eventId";
    $eventDataResult = mysqli_query($conn, $getEventDataQuery);

    // Provjeravamo da li smo uspješno dobili podatke o događaju
    if ($eventDataResult && mysqli_num_rows($eventDataResult) > 0) {
        $row = mysqli_fetch_assoc($eventDataResult);
        $eventName = $row['eventName'];
        $date = $row['date'];
        $price = $row['price'];
        $eventType = $row['eventType'];
        $eventImage = $row['imgPath'];
        $artistName = $row['artistName'];
        $artistAbout = $row['artistAbout'];
        // $artistImg = $row['imgPath'];
        $venueName = $row['venueName'];
        $location = $row['location'];
        $eventAbout = $row['eventAbout'];
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<link rel="stylesheet" href="css/confirm.css">

<div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="col-12 pad">
            <button class="back" onclick="redirectToOneEvent(<?php echo $eventId; ?>)"><i class="bi bi-arrow-left"></i>&nbsp;Back</button>
            <span class="korak1"><b style="color:#FBF719;">Tickets &nbsp; <i class="bi bi-arrow-right"></i></b>&nbsp; Payment <i class="bi bi-arrow-right"></i>&nbsp; Finish</span>
        </div>
        <div class="col-12 ukratko">
            <div class="row">
                <div class="col l">
                    <img src="<?php echo $eventImage; ?>" id="previewImage" alt="Selected image" class="slikaIza">
                    <img src="<?php echo $eventImage; ?>" id="previewImage" alt="Selected image" class="slika">
                </div>
                <div class="col d">
                    <?php
                    echo '  
                                <span class="eventIme">' . $eventName . '</span><br>
                                <span class="datum" >' . date('D, M j', strtotime($date)) . '</span><br>
                                <span class="lokacija">' . $venueName . '</span>
                            '
                    ?>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="sveKarte">
                <b>Seats</b>
                <?php
                $eventId = $_GET['eventId'];
                echo '  <p class="cijena">' . $price . ' €</p>
                        <div class="quantity-wrapper">
                            <button onclick="this.parentNode.querySelector(\'input[type=number]\').stepDown()">
                                &minus;
                            </button>
                            <input type="number" id="quantity" name="quantity" min="1" max="10" value="1" readonly>
                            <button onclick="this.parentNode.querySelector(\'input[type=number]\').stepUp()">
                                &#43;
                            </button>
                        </div>

                        <button class="confirmDugme" onclick="redirectToPayment()">Submit</button>
                        '
                ?>
            </div>
        </div>
    </div>

</div>

<script>
    function redirectToOneEvent(eventId) {
        window.location.href = 'oneEvent.php?eventId=' + eventId;
    }

    function redirectToPayment() {
        // Dobijanje vrijednosti brojača
        var quantity = document.getElementById('quantity').value;

        // Redirekcija na payment.php sa podacima o eventID-u i vrijednosti brojača
        window.location.href = 'payment.php?eventId=<?php echo $eventId; ?>&quantity=' + quantity;
    }
</script>
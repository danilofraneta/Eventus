<?php
include('db.php');

// Provjeravamo da li je proslijeđen ID događaja
if (isset($_GET['eventId'])) {
    // Dobijamo ID događaja iz URL-a
    $eventId = $_GET['eventId'];

    // SQL upit za dohvaćanje podataka o događaju
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
        // Ako nije uspjelo dobiti podatke o događaju, preusmjeravamo korisnika nazad na početnu stranicu
        header("Location: index.php");
        exit();
    }
} else {
    // Ako nije proslijeđen ID događaja, preusmjeravamo korisnika nazad na početnu stranicu
    header("Location: index.php");
    exit();
}
?>
<link rel="stylesheet" href="css/one.css">

<div class="w-100 overflow-hidden position-relative bg-black text-white" data-aos="fade">
    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="row d-flex align-items-center justify-content-center py-vh-5">
            <div class="col-6 ">
                <form action="confirmTicket.php" method="GET">
                    <img src="<?php echo $eventImage; ?>" id="previewImage" alt="Selected image" class="slikaIza">
                    <img src="<?php echo $eventImage; ?>" id="previewImage" alt="Selected image" class="slika"><br>
                    <button type="submit" class="kupiDugme">Buy ticket</button>
                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                </form>
            </div>
            <div class="col-6 ">
                <?php
                echo '<div class="sveInfo">
                        <p class="eventIme">' . $eventName . '</p>
                        <p class="datum" >' . date('D, M j', strtotime($date)) . '</p>
                        <p style="margin-bottom: 0px;">' . $location . '</p>
                        <p class="cijena">' . $price . ' €</p>
                        <button class="dugmad" onclick="filterVenue(' . $venueName . ')"><i class="bi bi-pin-map"></i>  &nbsp;' . $venueName . '</button>
                        <button class="dugmad" id="dugmeEvent" name="dugmeEvent" onclick="filterEvents(' . $eventType . ')"><i class="bi bi-tag"></i> &nbsp' . $eventType . '</button>
                        <p class="about">About event</p>
                        <p class="oEventu">' . $eventAbout . '</p>
                     <div>'
                ?>
            </div>
        </div>
    </div>
    <!-- </canvas> -->
</div>
<script>
    function filterEvents(eventType) {
        // Preusmjeri korisnika na eventsUser.php sa odgovarajućim eventId
        window.location.href = 'eventsUser.php?eventType=' + eventType;
    }
</script>
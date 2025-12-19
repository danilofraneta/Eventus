<?php
include('db.php');

// Provjeravamo da li je proslijeđen ID događaja
if (isset($_GET['eventId'])) {
    // Dobijamo ID događaja iz URL-a
    $eventId = $_GET['eventId'];

    // Provjeravamo da li je poslan zahtjev za ažuriranje
    if (isset($_POST['update'])) {
        // Dohvatamo sve podatke iz forme
        $artistName = $_POST['artistName'];
        $aboutArtist = $_POST['aboutArtist'];
        $eventType = $_POST['eventType'];
        $eventName = $_POST['eventName'];
        $eventAbout = $_POST['eventAbout'];
        $date = $_POST['date'];
        $price = $_POST['price'];
        $venueName = $_POST['venueName'];
        $location = $_POST['location'];

        // Pripremljeni SQL upit za ažuriranje podataka o događaju
        $updateEventQuery = "UPDATE Event SET eventName=?, date=?, price=?, eventType=?, eventAbout=? WHERE eventID=?";
        $stmt = mysqli_prepare($conn, $updateEventQuery);
        mysqli_stmt_bind_param($stmt, "ssdssi", $eventName, $date, $price, $eventType, $eventAbout, $eventId);
        $result = mysqli_stmt_execute($stmt);

        // Provjera uspješnosti ažuriranja
        if ($result) {
            // Ažuriranje ostalih informacija o događaju (umjetnik, mjesto)
            $updateArtistQuery = "UPDATE Artist SET artistName=?, artistAbout=? WHERE artistID=(SELECT artistID FROM Event WHERE eventID=?)";
            $stmt = mysqli_prepare($conn, $updateArtistQuery);
            mysqli_stmt_bind_param($stmt, "ssi", $artistName, $aboutArtist, $eventId);
            $resultArtist = mysqli_stmt_execute($stmt);

            $updateVenueQuery = "UPDATE Venue SET venueName=?, location=? WHERE venueID=(SELECT venueID FROM Event WHERE eventID=?)";
            $stmt = mysqli_prepare($conn, $updateVenueQuery);
            mysqli_stmt_bind_param($stmt, "ssi", $venueName, $location, $eventId);
            $resultVenue = mysqli_stmt_execute($stmt);

            // Provjera uspješnosti ažuriranja
            if ($resultArtist && $resultVenue) {
                $_SESSION['success_message'] = "Event successfully updated.";
                // Preusmjeravanje korisnika na početnu stranicu
                header("Location: ../managerProfile.php");
                exit();
            } else {
                echo "Error updating event.";
            }
        } else {
            echo "Error updating event.";
        }
    } else {
        // Ako nije poslan zahtjev za ažuriranjem, preuzimamo podatke o događaju
        $getEventDataQuery = "SELECT e.eventName, e.date, e.price, e.eventType, e.eventAbout, e.imgPath, a.imgPath AS artistImg, a.artistName, a.artistAbout, v.venueName, v.location FROM Event e LEFT JOIN Artist a ON e.artistID = a.artistID LEFT JOIN Venue v ON e.venueID = v.venueID WHERE e.eventID = ?";
        $stmt = mysqli_prepare($conn, $getEventDataQuery);
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        $eventDataResult = mysqli_stmt_get_result($stmt);

        // Provjeravamo da li smo uspješno dobili podatke o događaju
        if ($eventDataResult && mysqli_num_rows($eventDataResult) > 0) {
            $row = mysqli_fetch_assoc($eventDataResult);
            $eventName = $row['eventName'];
            $date = $row['date'];
            $price = $row['price'];
            $eventType = $row['eventType'];
            $imgPath = $row['imgPath'];
            $eventAbout = $row['eventAbout'];
            $artistName = $row['artistName'];
            $artistAbout = $row['artistAbout'];
            $artistImg = $row['artistImg'];
            $venueName = $row['venueName'];
            $location = $row['location'];
        } else {
            header("Location: index.php");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}

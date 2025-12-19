<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {
    $userID = $_POST['userID'];

    // Prvo brišemo rezervaciju povezane sa korisnikom
    $deleteReservationsQuery = "DELETE FROM reservation WHERE userID = $userID";
    if (!mysqli_query($conn, $deleteReservationsQuery)) {
        http_response_code(500);
        echo "Greška prilikom brisanja rezervacija: " . mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }

    // Onda brišemo sačuvane događaje
    $deleteFollowedEventsQuery = "DELETE FROM followedevents WHERE userID = $userID";
    if (!mysqli_query($conn, $deleteFollowedEventsQuery)) {
        http_response_code(500);
        echo "Greška prilikom brisanja sacuvanih dogadjaja: " . mysqli_error($conn);
        mysqli_close($conn);
        exit;
    }

    // Zatim brišemo korisnika
    $deleteUserQuery = "DELETE FROM user WHERE userID = $userID";
    if (mysqli_query($conn, $deleteUserQuery)) {
        http_response_code(200);
        echo "Korisnik je uspješno izbrisan.";
    } else {
        http_response_code(500);
        echo "Greška prilikom brisanja korisnika: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    http_response_code(400);
    echo "Nije proslijeđen ispravan zahtjev.";
}

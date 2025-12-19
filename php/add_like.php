<?php
include('db.php');

// Provjera da li je prosleđen event_id u zahtjevu
if (isset($_GET['event_id'])) {
    // Dobijanje event_id iz GET parametra
    $eventId = $_GET['event_id'];

    // Provera da li je korisnik ulogovan
    session_start();
    if (isset($_SESSION['userID'])) {
        // Dobijanje ID-a korisnika iz sesije
        $userId = $_SESSION['userID'];

        // Provera da li korisnik već prati ovaj događaj
        $checkQuery = "SELECT * FROM followedevents WHERE UserID = $userId AND EventID = $eventId";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            // Ako korisnik već prati ovaj događaj, obriši ga iz tabele
            $deleteQuery = "DELETE FROM followedevents WHERE UserID = $userId AND EventID = $eventId";
            mysqli_query($conn, $deleteQuery);
        } else {
            // Ako korisnik ne prati ovaj događaj, dodaj ga u tabelu
            $insertQuery = "INSERT INTO followedevents (UserID, EventID) VALUES ($userId, $eventId)";
            mysqli_query($conn, $insertQuery);
        }

        // Prije izlaza iz skripte, spremamo lajkovani event u session
        if (!isset($_SESSION['liked_events'])) {
            $_SESSION['liked_events'] = array();
        }
        $_SESSION['liked_events'][] = $eventId;
    }
}

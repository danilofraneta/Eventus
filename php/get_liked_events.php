<?php
include('db.php');

// da li je korisnik prijavljen
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $likedEvents = array();

    // Dobijamo sve evente koje je korisnik lajkovao iz baze
    $query = "SELECT eventID FROM likes WHERE user_id = $userId";
    $likedResult = mysqli_query($conn, $query);

    if ($likedResult) {
        while ($row = mysqli_fetch_assoc($likedResult)) {
            $likedEvents[] = $row['eventID'];
        }
    }

    // Vracam lajkovane događaje u JSON formatu
    echo json_encode($likedEvents);
} else {
    // Ako korisnik nije prijavljen, vrati prazan niz
    echo json_encode(array());
}

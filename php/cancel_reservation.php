<?php
include('db.php');

// Provjera da li je proslijeđen reservationID
if (isset($_GET['reservationID'])) {
    $reservationID = $_GET['reservationID'];

    // Upit za brisanje rezervacije iz baze
    $deleteReservationQuery = "DELETE FROM reservation WHERE resID = $reservationID";
    $deleteReservationResult = mysqli_query($conn, $deleteReservationQuery);

    if ($deleteReservationResult) {
        // Uspješno brisanje rezervacije
        echo "Reservation successfully canceled.";
    } else {
        // Greška prilikom brisanja rezervacije
        echo "Error canceling reservation.";
    }
} else {
    // Ako reservationID nije prosleđen
    echo "Reservation ID not provided.";
}

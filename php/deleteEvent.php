<?php
include('db.php');

// Provjeravamo da li je proslijeđen ID događaja
if (isset($_GET['eventId'])) {
    // Dobijamo ID događaja iz URL-a
    $eventId = $_GET['eventId'];

    $getEventDataQuery = "SELECT e.venueID, e.artistID, a.imgPath FROM Event e LEFT JOIN Artist a ON e.artistID = a.artistID WHERE e.eventID = $eventId";
    $eventDataResult = mysqli_query($conn, $getEventDataQuery);

    if ($eventDataResult && mysqli_num_rows($eventDataResult) > 0) {
        $row = mysqli_fetch_assoc($eventDataResult);
        $venueID = $row['venueID'];
        $artistID = $row['artistID'];

        // Prvo obrišite rezervacije povezane sa događajem
        $deleteReservationsQuery = "DELETE FROM reservation WHERE eventID = $eventId";
        if (mysqli_query($conn, $deleteReservationsQuery)) {
            $deleteAddedEventsQuery = "DELETE FROM addedevents WHERE EventID = $eventId";

            // Izvršavanje upita za brisanje iz tabele addedevents
            if (mysqli_query($conn, $deleteAddedEventsQuery)) {
                $deleteEventQuery = "DELETE FROM Event WHERE eventID = $eventId";

                // Izvršavanje upita za brisanje iz tabele Event
                if (mysqli_query($conn, $deleteEventQuery)) {
                    // Sada možemo obrisati povezane zapise iz tabele Artist
                    if (!empty($artistID)) {
                        $deleteArtistQuery = "DELETE FROM Artist WHERE artistID = $artistID";
                        mysqli_query($conn, $deleteArtistQuery);

                        // Takođe, brišemo i sliku umjetnika iz direktorija
                        if (!empty($row['imgPath']) && file_exists($row['imgPath'])) {
                            unlink($row['imgPath']);
                        }
                    }
                    // Isto tako brišemo povezane zapise iz tabele Venue
                    $deleteVenueQuery = "DELETE FROM Venue WHERE venueID = $venueID";
                    mysqli_query($conn, $deleteVenueQuery);

                    header("Location: ../managerProfile.php");
                    exit();
                } else {
                    echo "Error deleting event: " . mysqli_error($conn);
                }
            } else {
                echo "Error deleting added events: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting reservations: " . mysqli_error($conn);
        }
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

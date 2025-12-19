<?php
include('db.php');

if (isset($_GET['location']) && isset($_GET['date']) && isset($_GET['price']) && isset($_GET['userId'])) {
    $location = $_GET['location'];
    $date = $_GET['date'];
    $price = $_GET['price'];
    $userId = $_GET['userId'];

    // Formiranje SQL upita na osnovu dobijenih podataka
    $query = "SELECT e.eventID, e.eventName, e.date, v.venueName, v.location, e.price, e.imgPath 
          FROM event e 
          JOIN venue v ON e.venueID = v.venueID 
          WHERE 1=1"; // Početak WHERE klauze

    if (!empty($location)) {
        // Dodavanje uslova za lokaciju
        $query .= " AND v.location = '$location'";
    }

    if (!empty($date)) {
        // Dodavanje uslova za datum
        $query .= " AND DATE(e.date) = '$date'";
    }

    if (!empty($price)) {
        // Dodavanje uslova za cijenu
        $query .= " AND e.price <= '$price'";
    }


    // Izvršavanje upita
    $result = mysqli_query($conn, $query);

    // Prikaz rezultata
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $imgPath = $row['imgPath'];
            $eventId = $row['eventID'];
            $eventName = $row['eventName'];
            $date = $row['date'];
            $venueName = $row['venueName'];
            $location = $row['location'];
            $price = $row['price'];

            // Dohvatamo lajkovane događaje za trenutnog korisnika iz baze
            $likedEventsQuery = "SELECT EventID FROM followedevents WHERE UserID = $userId";
            $likedEventsResult = mysqli_query($conn, $likedEventsQuery);
            $likedEvents = [];
            if ($likedEventsResult && mysqli_num_rows($likedEventsResult) > 0) {
                while ($row = mysqli_fetch_assoc($likedEventsResult)) {
                    $likedEvents[] = $row['EventID'];
                }
            }

            // Provjeravamo da li je događaj već lajkovan
            $isLiked = in_array($eventId, $likedEvents);
            echo '<div class="events">
                    <div class=slikaSrce>
                        <img src="' . $imgPath . '" class="img-fluid" style="width: 245px;height: 245px;">
                        
                    </div>
                    <div class="opisEventa">
                        <p>' . $eventName . '<br>
                            <a style="color: #FBF719">' . date('D, M j', strtotime($date)) . '</a><br>
                            <a>' . $venueName . ', ' . $location . '</a><br><a style="color:rgba(255, 255, 255, 0.5);">' . $price . ' €</a>
                        </p>
                    </div>
                    
                    <label class="like">
                        <input type="checkbox" name="liked_events[]" value="' . $eventId . '" ' . ($isLiked ? 'checked' : '') . ' />
                        <div class="hearth"></div>
                    </label>
                </div>';
        }
    } else {
        echo '<p>No events found with specified information</p>';
    }
} else {
    echo '<p>Parametri nisu prebaceni</p>';
}

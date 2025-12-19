<?php
include('db.php');

$query = "SELECT DISTINCT eventType FROM event";
$result = mysqli_query($conn, $query);

// Provjera da li je pritisnut dugme za upload
if (isset($_POST['upload'])) {
    // Podaci o umjetniku
    $artistName = $_POST['artistName'];
    $aboutArtist = $_POST['aboutArtist'];

    // Podaci o prostoru
    $venueName = $_POST['venueName'];
    $location = $_POST['location'];

    // Podaci o događaju
    $eventName = $_POST['eventName'];
    $date = $_POST['date'];
    $price = $_POST['price'];
    $eventType = $_POST['eventType'];
    $eventAbout = $_POST['eventAbout'];

    // Upload slike umjetnika
    $artistImagePath = 'images/artists/' . $_FILES['profilePhoto']['name'];
    move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $artistImagePath);

    // Pripremljeni SQL upit za unos umjetnika u bazu
    $artistQuery = "INSERT INTO Artist (artistName, artistAbout, imgPath) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $artistQuery);
    mysqli_stmt_bind_param($stmt, "sss", $artistName, $aboutArtist, $artistImagePath);
    mysqli_stmt_execute($stmt);

    // Dobijanje ID-ja umetnika koji je upravo dodat
    $artistID = mysqli_insert_id($conn);

    // SQL upit za unos prostora u bazu
    $venueQuery = "INSERT INTO Venue (venueName, location) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $venueQuery);
    mysqli_stmt_bind_param($stmt, "ss", $venueName, $location);
    mysqli_stmt_execute($stmt);

    // Dobijanje ID-ja prostora koji je upravo dodat
    $venueID = mysqli_insert_id($conn);

    // Upload slike događaja
    $eventImagePath = 'images/events/' . $_FILES['image']['name']; // Dodajemo "events" u putanju za događaj
    move_uploaded_file($_FILES['image']['tmp_name'], $eventImagePath);

    // Pripremljeni SQL upit za unos događaja u bazu
    $eventQuery = "INSERT INTO Event (artistID, venueID, eventName, date, price, imgPath, eventType, eventAbout) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $eventQuery);
    mysqli_stmt_bind_param($stmt, "iissssss", $artistID, $venueID, $eventName, $date, $price, $eventImagePath, $eventType, $eventAbout);
    mysqli_stmt_execute($stmt);

    // Dobijanje ID-ja događaja koji je upravo dodat
    $eventID = mysqli_insert_id($conn);

    $userID = $_SESSION['userID'];

    // SQL upit za unos dodatog događaja u bazu
    $addedEventQuery = "INSERT INTO addedEvents (EventID, UserID) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $addedEventQuery);
    mysqli_stmt_bind_param($stmt, "ii", $eventID, $userID);
    mysqli_stmt_execute($stmt);

    // Poruka o uspešnom unosu
    echo "<script>alert('Successfully uploaded event!');</script>";
}

?>





<link rel="stylesheet" href="css/addEvents.css">
<div class="container-fluid px-vw-5 position-relative pozadina" data-aos="fade">
    <div class="position-relative py-vh-5 bg-cover bg-center rounded-5 ">
        <div class="container  px-vw-5 py-vh-3 rounded-5 shadow ">
            <span class="h3 text-secondary fw ">Let's add events here:</span><br>
            <span class="h7 text-secondary fw-lighter">Fill in the form and add events that will show up to users! The inputs that are marked with " * " are optional</span>
            <h2 class="fw-lighter"></h2>
            <form action="" method="post" enctype="multipart/form-data">

                <div class="container text-center">
                    <div class="row">
                        <div class="col poz">
                            <h3 class="h6 te">Artist profile photo: *</h3>
                            <div class="profilePhoto rounded-circle bg-secondary text-white">
                                <input type="file" name="profilePhoto" id="profilePhotoInput" style="display: none;" accept="image/*">
                                <label for="profilePhotoInput" style="cursor: pointer;">
                                    <img src="#" id="previewImage" alt="Selected image" style="max-width: 100%; max-height: 100%;">
                                    <!-- Ovo je prazna slika koja će se koristiti za prikaz odabrane slike -->
                                    Click to choose photo
                                </label>
                            </div>


                            <div>
                                <h3 class="h6 te">Artist name:</h3>
                                <input type="text" name="artistName" class="g form-control  border-dark" required>
                            </div>
                            <div>
                                <h3 class="h6 te">About artist: *</h3>
                                <textarea name="aboutArtist" class=" g ta form-control  border-dark"></textarea>
                            </div>



                        </div>
                        <div class="col poz">
                            <div>
                                <h3 class="h6 te">Event type:</h3>
                                <select class="g form-control border-dark" id="eventType" name="eventType" required>
                                    <option disabled selected>Event type</option>
                                    <?php
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $type = $row['eventType'];
                                            echo '<option>' . $type . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                <h3 class="h6 te">Event name:</h3>
                                <input type="text" name="eventName" class="form-control g border-dark" required>
                            </div>

                            <div>
                                <h3 class="h6 te">Date:</h3>
                                <input class=" form-control g border-dark" style="color:white;" type="date" id="date" name="date" required>
                            </div>
                            <div>
                                <h3 class="h6 te">Price:</h3>
                                <input type="number" name="price" class="form-control g border-dark" required>
                            </div>
                            <div>
                                <h3 class="h6 te">Image:</h3>
                                <input type="file" name="image" accept="image/*" required>
                            </div>

                        </div>
                        <div class="col poz">
                            <div>
                                <h3 class="h6 te">Venue name:</h3>
                                <input type="text" name="venueName" class="form-control g border-dark" required>
                            </div>
                            <div>
                                <h3 class="h6 te">Location:</h3>
                                <input type="text" name="location" class="form-control g border-dark" required>
                            </div>
                            <div>
                                <h3 class="h6 te">About event: </h3>
                                <textarea name="eventAbout" class=" g ta form-control  border-dark"></textarea>
                            </div>

                            <button name="upload" id="upload" class="btn up"> Upload </button>


                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript kod za prikaz odabrane slike
    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById("previewImage");
            imgElement.src = reader.result;
            imgElement.style.display = "block"; // Prikazujemo sliku kada je odabrana
            imgElement.style.marginTop = "45px";
        };
        reader.readAsDataURL(input.files[0]);
    }

    // Event listener koji će se pozvati kada korisnik odabere sliku
    document.getElementById("profilePhotoInput").addEventListener("change", previewImage);
</script>
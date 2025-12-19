<?php
include('db.php');

// Provjeravamo da li je proslijeđen ID događaja
if (isset($_GET['eventId'])) {
    // Dobijamo ID događaja iz URL-a
    $eventId = $_GET['eventId'];

    // SQL upit za dohvatanje podataka o događaju
    $getEventDataQuery = "SELECT e.eventName, e.date, e.price, e.eventType, e.imgPath, e.eventAbout, a.imgPath, a.artistName, a.artistAbout, v.venueName, v.location FROM Event e LEFT JOIN Artist a ON e.artistID = a.artistID LEFT JOIN Venue v ON e.venueID = v.venueID WHERE e.eventID = $eventId";
    $eventDataResult = mysqli_query($conn, $getEventDataQuery);

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
        $artistImg = $row['imgPath'];
        $venueName = $row['venueName'];
        $location = $row['location'];
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="css/addEvents.css">
<div class="container-fluid px-vw-5 position-relative pozadina" data-aos="fade">
    <div class="position-relative py-vh-5 bg-cover bg-center rounded-5 ">
        <div class="container  px-vw-5 py-vh-3 rounded-5 shadow ">
            <span class="h3 text-secondary fw ">Edit an event:</span><br>
            <span class="h7 text-secondary fw-lighter">Only alter inputs you are interested in updating</span>
            <h2 class="fw-lighter"></h2>
            <form action="php/editEventFinal.php?eventId=<?php echo $eventId; ?>" method="post" enctype="multipart/form-data">

                <div class="container text-center">
                    <div class="row">
                        <div class="col poz">
                            <h3 class="h6 te">Artist profile photo:</h3>
                            <div class="profilePhotoEdit rounded-circle bg-secondary text-white">
                                <input type="file" name="profilePhoto" id="profilePhotoInput" style="display: none;" accept="image/*">
                                <div for="profilePhotoInput" style="cursor: pointer;"> <!-- BIO JE LABEL ALI SAM PTOMJENIO ZA SADA!!! -->
                                    <img src="<?php echo $artistImg; ?>" id="previewImage" alt="Selected image" style="max-width: 100%; max-height: 100%;  padding-top:45px;">
                                    <!-- Ovo je prazna slika koja će se koristiti za prikaz odabrane slike -->
                                    Click to choose photo
                                </div>
                            </div>


                            <div>
                                <h3 class="h6 te">Artist name:</h3>
                                <input type="text" name="artistName" class="g form-control  border-dark" value="<?php echo $artistName; ?>" required>
                            </div>
                            <div>
                                <h3 class="h6 te">About artist:</h3>
                                <textarea name="aboutArtist" class="g ta form-control  border-dark"><?php echo $artistAbout; ?></textarea>
                            </div>



                        </div>
                        <div class="col poz">
                            <div>
                                <h3 class="h6 te">Event type:</h3>
                                <select class="g form-control border-dark" id="eventType" name="eventType" required>
                                    <option disabled selected>Event type</option>
                                    <?php
                                    $query = "SELECT DISTINCT eventType FROM event";
                                    $result = mysqli_query($conn, $query);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $type = $row['eventType'];
                                            $selected = ($type == $eventType) ? 'selected' : '';
                                            echo '<option ' . $selected . '>' . $type . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                <h3 class="h6 te">Event name:</h3>
                                <input type="text" name="eventName" class="form-control g border-dark" value="<?php echo $eventName; ?>" required>
                            </div>

                            <div>
                                <h3 class="h6 te">Date:</h3>
                                <input class="form-control g border-dark" type="date" id="date" name="date" value="<?php echo $date; ?>" required>
                            </div>
                            <div>
                                <h3 class="h6 te">Price:</h3>
                                <input type="number" name="price" class="form-control g border-dark" value="<?php echo $price; ?>" required>
                            </div>

                        </div>
                        <div class="col poz">
                            <div>
                                <h3 class="h6 te">Venue name:</h3>
                                <input type="text" name="venueName" class="form-control g border-dark" value="<?php echo $venueName; ?>" required>
                            </div>
                            <div>
                                <h3 class="h6 te">Location:</h3>
                                <input type="text" name="location" class="form-control g border-dark" value="<?php echo $location; ?>" required>
                            </div>
                            <div>
                                <h3 class="h6 te">About event: </h3>
                                <textarea name="eventAbout" class=" g ta form-control  border-dark"><?php echo $eventAbout; ?></textarea>
                            </div>


                            <button name="update" id="update" class="btn up"> Update </button>


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
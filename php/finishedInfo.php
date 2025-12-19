<?php
include('db.php');

//unos podataka u rezervaciju
if (isset($_GET['totalPrice']) && isset($_SESSION['userID']) && isset($_GET['eventId']) && isset($_GET['quantity'])) {
    $totalPrice = $_GET['totalPrice'];
    $userID = $_SESSION['userID'];
    $eventID = $_GET['eventId'];
    $status = 'active'; // fiksno postavljen za sada
    $resNumber = rand();
    $quantity = $_GET['quantity'];
    $reservationDate = date('Y-m-d');

    // Priprema upita za unos podataka u bazu
    $insertReservationQuery = "INSERT INTO reservation (userID, eventID, price, resNumber, status, date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertReservationQuery);
    $stmt->bind_param("iidiss", $userID, $eventID, $totalPrice, $resNumber, $status, $reservationDate);


    // Izvršavanje upita
    if ($stmt->execute()) {
        // echo "Reservation successfully added to the database.";
    } else {
        echo "Error: " . $conn->error;
    }

    // Zatvaranje prepared statementa
    $stmt->close();
} else {
    echo 'Necessary data is missing.';
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    echo 'No email found';
}

// Provjeravamo da li je proslijeđen ID događaja
if (isset($_GET['eventId'])) {
    // Dobijamo ID događaja iz URL-a
    $eventId = $_GET['eventId'];

    // SQL upit za dohvatanje podataka o događaju
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
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$totalPrice = $quantity * $price;






//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings                   
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'eventusits@gmail.com';                     //SMTP username
    $mail->Password   = 'mljp nfee drpg vjkm';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('eventusits@gmail.com', 'Eventus');
    $mail->addAddress($email);     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment($eventImage, 'ticket.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Congratulations!';
    $mail->Body    = file_get_contents('mejl.html'); // Load HTML template
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
<link rel="stylesheet" href="css/confirm.css">
<div class="loader"></div>
<div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="col-12 pad">
            <span class="korak1">Tickets &nbsp; <i class="bi bi-arrow-right"></i>&nbsp; Payment <i class="bi bi-arrow-right"></i>&nbsp;<b style="color:#FBF719;"> Finish</b></span>
        </div>

        <div class="col-12">
            <div class="congrats">
                <h1>Congratulations!</h1><br>
                You have sucessfully bought tickets! An email confirmation will be sent to <b><?php echo $email ?></b>. Thank you for using Eventus!<br><br>

            </div>
        </div>

        <div class="col-12">
            <div class="row kupljeno">
                <div class="col"><br><br>
                    <div class="row">
                        <div class="col l">
                            <img src="<?php echo $eventImage; ?>" id="previewImage" alt="Selected image" class="slikaIza">
                            <img src="<?php echo $eventImage; ?>" id="previewImage" alt="Selected image" class="slika">
                        </div>
                        <div class="col d">
                            <?php
                            echo '  
                                <span class="eventIme">' . $eventName . '</span><br>
                                <span class="datum" >' . date('D, M j', strtotime($date)) . '</span><br>
                                <span class="lokacija">' . $venueName . '</span>
                            '
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <p class="order">Order summary</p>
                    <hr class="linija">
                    <p class="sum"><b><?php echo $quantity ?></b> x General Admission</p>
                    <p class="order">Paid amount</p>
                    <hr class="linija">
                    <p class="total"><b><?php echo $totalPrice ?> €</b></p>
                </div>
            </div>

        </div>

    </div>


    <script>
        window.addEventListener("load", () => {
            const loader = document.querySelector(".loader");

            loader.classList.add("loader--hidden");

            loader.addEventListener("transitionend", () => {
                document.body.removeChild(loader);
            });
        });
    </script>
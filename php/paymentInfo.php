<?php
include('db.php');

if (isset($_GET['quantity'])) {
    // Dohvatanje vrijednosti quantity
    $quantity = $_GET['quantity'];
} else {
    // Ako quantity nije poslan, prikazati odgovarajuću poruku
    echo 'Quantity not knwon.';
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
        // Ako nije uspjelo dobiti podatke o događaju, preusmjeravamo korisnika nazad na početnu stranicu
        header("Location: index.php");
        exit();
    }
} else {
    // Ako nije proslijeđen ID događaja, preusmjeravamo korisnika nazad na početnu stranicu
    header("Location: index.php");
    exit();
}

$totalPrice = $quantity * $price;


?>
<link rel="stylesheet" href="css/confirm.css">
<div class="loader"></div>
<div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="col-12 pad">
            <button class="back" onclick="redirectToConfirm(<?php echo $eventId; ?>)"><i class="bi bi-arrow-left"></i>&nbsp;Back</button>
            <span class="korak1">Tickets &nbsp; <i class="bi bi-arrow-right"></i>&nbsp;<b style="color:#FBF719;"> Payment <i class="bi bi-arrow-right"></i></b>&nbsp; Finish</span>
        </div>
        <div class="row">
            <div class="col-6 gore">
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
                <p class="order">Order summary</p>
                <hr class="linija">
                <p class="sum"><b><?php echo $quantity ?></b> x General Admission</p>
                <p class="order">Total</p>
                <hr class="linija">
                <p class="total"><b><?php echo $totalPrice ?> €</b></p>

            </div>
            <div class="col-6 dolje">
                <div class="kartica">
                    <img src="images/cards.webp" class="kartice"><br>
                    <b>Payment</b><br>
                    All transactions are secure and encrypted.
                    <?php
                    $eventId = $_GET['eventId'];
                    ?>
                    <div class="cardInfo">
                        <div class="input-container">
                            <input type="text" name="cardNumber" placeholder="Card number" class="cardNumber">
                            <i class="bi bi-credit-card black"></i>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="text" name="cardNumber" placeholder="Exp. date (MM/YY)" class="drugiRed">
                            </div>
                            <div class="col">
                                <input type="text" name="cardNumber" placeholder="CCV" class="drugiRed2">
                            </div>
                        </div>
                        <input type="text" name="cardNumber" placeholder="Name on card" class="cardNumber">


                    </div>
                    <button class="buyDugme" onclick="redirectToFinished()">Buy ticket</button>


                </div>
            </div>
        </div>

    </div>

</div>

<script>
    function redirectToFinished() {
        window.location.href = 'finished.php?eventId=<?php echo $eventId; ?>&totalPrice=<?php echo $totalPrice; ?>&quantity=<?php echo $quantity; ?>';
    }


    function redirectToConfirm(eventId) {
        window.location.href = 'confirmTicket.php?eventId=' + eventId;
    }


    window.addEventListener("load", () => {
        const loader = document.querySelector(".loader");

        loader.classList.add("loader--hidden");

        loader.addEventListener("transitionend", () => {
            document.body.removeChild(loader);
        });
    });

    //     window.addEventListener("load", () => {
    //         const loader = document.querySelector(".loader");

    //         loader.classList.add("loader--hidden");

    //         loader.addEventListener("transitionend", () => {
    //             document.body.removeChild(loader);
    //         });
    //     });

    //     function validateCard() {
    //         const cardNumber = document.getElementById('cardNumber').value;
    //         if (!isValidCardNumber(cardNumber)) {
    //             document.getElementById('cardError').style.display = 'block';
    //         } else {
    //             document.getElementById('cardError').style.display = 'none';
    //             redirectToFinished();
    //         }
    //     }

    //     function isValidCardNumber(number) {
    //         number = number.replace(/\D/g, '');
    //         let sum = 0;
    //         let shouldDouble = false;

    //         for (let i = number.length - 1; i >= 0; i--) {
    //             let digit = parseInt(number.charAt(i));

    //             if (shouldDouble) {
    //                 digit *= 2;
    //                 if (digit > 9) {
    //                     digit -= 9;
    //                 }
    //             }

    //             sum += digit;
    //             shouldDouble = !shouldDouble;
    //         }

    //         return (sum % 10) === 0;
    //     }
    // 
</script>
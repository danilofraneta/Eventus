<?php
include('db.php');
// Provjerite da li je korisnik kliknuo na dugme za odjavu
if (isset($_POST['logout'])) {
    // Uništi sesiju
    session_destroy();
    // Preusmjerite korisnika na početnu stranicu koristeći JavaScript
    echo '<script>window.location.href = "index.php";</script>';
    exit(); 
}
?>
<div class="container-fluid px-vw-5 position-relative" data-aos="fade">
    <div class="position-relative py-vh-5 bg-cover bg-center rounded-5">
        <div class="container bg-black px-vw-5 py-vh-3 rounded-5 shadow">
            <div class="row d-flex align-items-center">

                <div class="accountBG col-6 " class=" d-flex align-items-center bg-dark shadow rounded-5 p-0" data-aos="zoom-in-up">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <div id="profileInitials" class="profile-initials rounded-circle bg-secondary text-white">
                                <?php
                                // Provjera da li postoji sesija sa imenom i prezimenom
                                if (isset($_SESSION['name']) && isset($_SESSION['surname'])) {
                                    $name = $_SESSION['name'];
                                    $surname = $_SESSION['surname'];

                                    // Formiranje inicijala
                                    $initials = strtoupper(substr($name, 0, 1) . substr($surname, 0, 1));

                                    // Ispis inicijala
                                    echo $initials;
                                } else {
                                ?>
                                    <p>Profile: </p>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-12 col-lg-10 col-xl-8  my-5" class="accountInfo">
                            <p class="lead" style="font-weight: bold;">
                                <?php
                                if (isset($_SESSION['name']) && isset($_SESSION['surname'])) {
                                    $name = $_SESSION['name'];
                                    $surname = $_SESSION['surname'];

                                    echo $name . " " . $surname;
                                } ?>
                            </p>
                            <p class="text novi">Username:
                                <a style="color: #8A8A8E">
                                    <?php
                                    if (isset($_SESSION['username'])) {
                                        $username = $_SESSION['username'];

                                        echo $username;
                                    } ?></a>
                            </p>
                            <p class="text novi border-bottom pb-4 border-secondary">Email:
                                <a style="color: #8A8A8E">
                                    <?php
                                    if (isset($_SESSION['email'])) {
                                        $email = $_SESSION['email'];

                                        echo $email;
                                    } ?></a>
                            </p>
                            <!-- <p class="text-secondary text-center maliBG">Account info</p> MOZDA VRATIM -->
                            <div class="col-12 text-center">
                                <form id="logoutForm" method="post">
                                    <button type="submit" name="logout" class="btn dugme" onclick="confirmLogout()">Sign out</button>
                                </form>
                            </div>
                            <script>
                                function confirmLogout() {
                                    var confirmLogout = confirm("Are you sure you want to logout?");
                                    if (confirmLogout) {
                                        // Dugme za odjavu je kliknuto, podnesite formu
                                        document.getElementById("logoutForm").submit();
                                        window.location.href = "index.php"; // Redirect to index.php
                                    };
                                }
                            </script>
                        </div>
                    </div>
                </div>

                <div class="col-5 offset-2 row d-flex " style="width: 500px;">
                    <p data-aos="zoom-in-left">Uploaded events</p>
                    <div class="col-12 modifikacija">
                        <div class="savedEvents">
                            <?php
                            if (isset($_SESSION['userID'])) {
                                // Dobijanje ID-a korisnika iz sesije
                                $userId = $_SESSION['userID'];

                                // Dohvatanje dodatih događaja za trenutnog korisnika iz baze
                                $query = "SELECT e.eventID, e.eventName, e.date, v.venueName, v.location, e.price, e.imgPath FROM event e JOIN venue v on e.venueID = v.venueID JOIN addedEvents f ON e.eventID = f.EventID WHERE f.UserID = $userId";
                                $result = mysqli_query($conn, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $imgPath = $row['imgPath'];
                                        $eventId = $row['eventID'];
                                        $eventName = $row['eventName'];
                                        $date = $row['date'];
                                        $venueName = $row['venueName'];
                                        $location = $row['location'];
                                        $price = $row['price'];

                                        // Prikaz dodatog događaja
                                        echo '<div class="row red">
                                                <div class="col-md-6">
                                                    <img src="' . $imgPath . '" class="img-fluid" style="width: 110px;">
                                                </div>
                                                <div class="col-md-6"><br>
                                                    <p style="width: 300px; margin-left: -90px;">' . $eventName . '<br>
                                                    <a style="color: #FBF719">' . date('D, M j', strtotime($date)) . '</a>
                                                    <button name="edit" class="dugme1" onclick="editEvent(' . $eventId . ')"><i class="bi bi-pencil"></i></button>
                                                    <button name="delete" class="dugme2" onclick="confirmDelete(' . $eventId . ')"><i class="bi bi-trash3"></i></button><br>
                                                    <a style="width: fit-content">' . $venueName . ', ' . $location . '</a><br>
                                                    </p>
                                                </div>
                                            </div>';
                                    }
                                } else {
                                    echo '<p class="noSaved fade-in" data-aos="zoom-in-left">You haven\'t added events yet, <a href="addEvents.php" style="color: #888;">add here!</a></p>';
                                }
                            } else {
                                echo '<p>Please log in to see saved events</p>';
                            }
                            ?>
                        </div>
                    </div>




                    <script>
                        function redirectToIndex() {
                            window.location.href = "index.php";
                        }

                        function confirmDelete(eventId) {
                            var confirmDelete = confirm("Are you sure you want to delete this event?");
                            if (confirmDelete) {
                                window.location.href = "php/deleteEvent.php?eventId=" + eventId;
                            }
                        }

                        function editEvent(eventId) {
                            window.location.href = "editEvent.php?eventId=" + eventId;
                        }
                    </script>

                </div>
            </div>

        </div>
    </div>



    <style>
        .accountBG {
            /*background-color: rgb(32, 32, 32); MOZDA VRATIM*/
            border-radius: 20px;
            width: 350px;
            padding: 0;
        }

        .novi {
            width: max-content;
            font-size: 17px;
            margin: 5px;
        }

        .profile-initials {
            width: 100px;
            height: 100px;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000000;
            background-image: linear-gradient(315deg, #000000 0%, #0b0322 85%, #492883 100%);

            margin-left: 70px;
            margin-top: 20px;
            margin-bottom: -30px;
        }

        .maliBG {
            font-size: 16px;
            text-align: center;
        }

        /* media upit za ekrane širine veće od 1200px */
        @media (min-width: 1200px) {
            .row.d-flex.align-items-center {
                flex-wrap: nowrap;
                /* onemogućava da se elementi prelome na novi red */
            }
        }

        .status {
            margin-left: -70px;
            margin-top: -280px;
            transition: transform 0.3s ease;
            background-color: rgb(32, 32, 32);
            padding: 15px;
            border-radius: 30px;
        }

        .status:hover {
            transform: scale(0.96);
            cursor: pointer;
        }

        .dugme {
            font-size: 17px;
            background-color: white;
            margin: 15px;
        }

        .saved {
            font-weight: bold;
            font-size: 17px;
            margin-left: -117%;
            margin-top: -135px;
        }

        .noSaved {
            font-size: 17px;
            color: #888;
            height: 340px;
            width: max-content;
        }




        .savedEvents {
            font-size: 15px;
        }

        .modifikacija {
            margin-left: -100%;
            position: relative;
            top: 35px;
            max-height: 375px;
            width: fit-content;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .modifikacija::-webkit-scrollbar {
            width: 10px;
            margin: 5px;
        }

        /* Stilizacija thumb-a - ručice za skrolovanje */
        .modifikacija::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 55px;
        }

        /* Stilizacija hover stanja thumb-a */
        .modifikacija::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .savedEvents img {
            height: 110px;
            border-radius: 5px;
            margin-top: 7px;
            margin-bottom: 7px;
        }

        .red:hover {
            background-color: #888;
            border-radius: 15px;
            transition: 0.3s ease;
            cursor: pointer;
        }

        .dugme1 {
            color: white;
            border: none;
            font-size: 20px;
            margin-left: 110px;
            background: none;
        }

        .dugme1:hover {
            color: #150015;
        }

        .dugme2 {
            color: white;
            border: none;
            font-size: 20px;
            background: none;
        }

        .dugme2:hover {
            color: red;
        }
    </style>
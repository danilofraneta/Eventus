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
<link rel="stylesheet" href="css/admin.css">
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
                            <p class="lead" style="font-weight: bold; text-align:center">
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
            </div>
        </div>
    </div>
</div>
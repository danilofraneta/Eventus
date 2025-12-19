<?php
session_start();
?>
<div class="w-100 overflow-hidden position-relative  text-white" data-aos="fade">
    <!-- <canvas id="canvas" width="1400" height="600"> -->
    <main class="wrapper">
        <div class="ciao-frames">
            <div class="frame" id="one"></div>
            <div class="frame" id="two"></div>
            <div class="frame" id="three"></div>
            <div class="frame" id="four"></div>
    </main>
    <div class="position-absolute w-100 h-100  opacity-75 top-0 start-0"></div>
    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="row d-flex align-items-center justify-content-center py-vh-5">

            <div class="col-6 ">
                <span class="h5 text-secondary fw-lighter">
                    <?php
                    // Provjera da li je korisnik prijavljen
                    if (isset($_SESSION['name'])) {
                        $name = $_SESSION['name'];
                        echo 'Welcome ' . $name . '';
                    }
                    ?>
                </span>
                <h1 class="display-huge mt-3 mb-3 lh-1">Let's work together</h1>
            </div>
            <div class="col-6 ">
                <p class="lead text-secondary">Help people find all the fun events you offer. Easy, fast and great way to expand your audience! </p>
            </div>
            <div class="col-12 text-center"><br><br>
                <a href="addEvents.php" class="btn btn-xl btn-light">Add events now
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- </canvas> -->
</div>
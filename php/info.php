<?php
include('db.php');
?>
<div class="w-100 overflow-hidden position-relative bg-black text-white" data-aos="fade">
    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
    <section class='wrapper'>
        <div class='hero'>
        </div>
        <div class='content'>
            <span class="h5 text-secondary fw-lighter">Welcome</span>
            <h1 class="display-huge mt-3 mb-3 lh-1">Find all of the events you<br> love and need</h1>
        </div>
    </section>
    <div class=" container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="row d-flex align-items-center justify-content-center py-vh-5">



            <div class="col-12 col-xl-10">
                <span class="h5 text-secondary fw-lighter" style="visibility: hidden;">Welcome</span>
                <h1 class="display-huge mt-3 mb-3 lh-1" style="visibility: hidden;">Find all of the events you love and need</h1><br><br>
            </div>
            <div class=" col-12 col-xl-8">
                <p class="lead text-secondary">Traveling abroad or in your home town? Have a look at 2,000+ events all around the world and near you!</p>
            </div>
            <div class="col-12 text-center">
                <?php
                if (isset($_SESSION["userID"])) {
                ?>
                    <a href="eventsUser.php" class="btn btn-xl btn-light">Let's start
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                        </svg>
                    </a>
                <?php } else {
                ?>
                    <a href="events.php" class="btn btn-xl btn-light">Let's start
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                        </svg>
                    </a>
                <?php }
                ?>
            </div>
        </div>
    </div>
</div>
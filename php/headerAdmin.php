<?php
session_start();
?>
<nav id="navScroll" class="navbar navbar-dark bg-black fixed-top px-vw-5" tabindex="0">
    <div class="container">
        <a class="navbar-brand pe-md-4 fs-4 col-12 col-md-auto text-center" href="indexAdmin.php">
            <img src="images/logo.png" alt="EventNow">
            <span class="ms-md-1 mt-1 fw-bolder me-md-5">Eventus </span>
        </a>

        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 list-group list-group-horizontal">
            <li class="nav-item">
                <a class="nav-link fs-5" href="indexAdmin.php" aria-label="Homepage">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-5" href="editAdmin.php" aria-label="A sample content page">
                    Edit
                </a>
            </li>
        </ul>

        <a href="<?php echo isset($_SESSION['name']) ? 'adminProfile.php' : 'login.php'; ?>" class="btn btn-outline-light">
            <?php
            // Provera da li je korisnik prijavljen
            if (isset($_SESSION['name'])) {
                $name = $_SESSION['name'];
                echo '<small>Hey ' . $name . '</small>';
            } else {
                // Ako korisnik nije prijavljen, prikaži Sign up dugme
                echo '<small>Sign in</small>';
            }
            ?>
        </a>
    </div>
</nav>

<style>
    /* Dodavanje prilagođenih stilova za scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
        /* Širina scrollbar-a */
    }

    ::-webkit-scrollbar-track {
        background-color: #222;
        /* Boja pozadine scrollbar-a */
    }

    ::-webkit-scrollbar-thumb {
        background-color: #555;
        /* Boja thumb-a */
        border-radius: 5px;
        /* Zaobljenje rubova thumb-a */
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: #888;
        /* Boja thumb-a na hover */
    }
</style>
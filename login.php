<?php
session_start();
include('php/db.php');
?>

<head>
    <title>Log in</title>
    <link rel="stylesheet" href="css/theme.min.css">
    <link rel="icon" href="images/logo.png">
</head>


<body class="d-flex h-100 w-100 bg-black text-white" data-bs-spy="scroll" data-bs-target="#navScroll">
    <div class="h-100 container-fluid">
        <div class="h-100 row d-flex align-items-stretch">
            <div class="col-12 col-md-7 col-lg-6 col-xl-5 d-flex align-items-start flex-column px-vw-5 fade-in">

                <header class="mb-auto py-vh-2 col-12">
                    <a class="navbar-brand pe-md-4 fs-4 col-12 col-md-auto text-center" href="index.php">
                        <img src="images/logo.png" alt="Ēventus">
                        <span class="ms-md-1 mt-1 fw-bolder me-md-5">Eventus</span>
                    </a>
                </header>

                <main class="mb-auto col-12">
                    <h2 class="fs-3">Sign in</h2>
                    <br>
                    <form class="row" action="" method="post">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="email" class="form-label" style="font-size: 17px;">Email address</label>
                                <input type="email" name="email" class="form-control bg-gray-800 border-dark" id="email" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label" style="font-size: 17px;">Password</label>
                                <input type="password" name="password" class="form-control bg-gray-800 border-dark" id="password" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 17px;">Don't have an account? <a href="signup.php" class="form-text" style="font-size: 17px;">Sign up</a></label>
                            </div>
                            <button type="submit" name="login" id="login" class="btn btn-white mb-4" style="font-size: 17px;">Sign in</button>
                            <h1 class="naslov">Eventus</h1>
                        </div>
                    </form>

                    <?php

                    if (isset($_POST['login'])) {
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        //admina
                        $adminSelect = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password' AND userRole = 3");
                        $adminRow = mysqli_fetch_array($adminSelect);

                        if (is_array($adminRow)) {
                            $_SESSION["userID"] = $adminRow['userID'];
                            $_SESSION["email"] = $adminRow['email'];
                            $_SESSION["password"] = $adminRow['password'];
                            $_SESSION["userRole"] = 3;
                            $_SESSION['name'] = $adminRow['name'];
                            $_SESSION['surname'] = $adminRow['surname'];
                            $_SESSION['username'] = $adminRow["username"];

                            header("Location: indexAdmin.php");
                            exit;
                        }

                        //menadžer
                        $managerSelect = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password' AND userRole = 2");
                        $managerRow = mysqli_fetch_array($managerSelect);

                        if (is_array($managerRow)) {
                            $_SESSION["userID"] = $managerRow['userID'];
                            $_SESSION["email"] = $managerRow['email'];
                            $_SESSION["password"] = $managerRow['password'];
                            $_SESSION["userRole"] = 2;
                            $_SESSION['name'] = $managerRow['name'];
                            $_SESSION['surname'] = $managerRow['surname'];
                            $_SESSION['username'] = $managerRow["username"];

                            header("Location: indexManager.php");
                            exit;
                        }

                        //korisnik
                        $userSelect = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password' AND userRole = 1");
                        $userRow = mysqli_fetch_array($userSelect);

                        if (is_array($userRow)) {
                            $_SESSION["userID"] = $userRow['userID'];
                            $_SESSION["email"] = $userRow['email'];
                            $_SESSION["password"] = $userRow['password'];
                            $_SESSION["userRole"] = 1;
                            $_SESSION['name'] = $userRow['name'];
                            $_SESSION['surname'] = $userRow['surname'];
                            $_SESSION['username'] = $userRow["username"];

                            header("Location: indexUser.php");
                            exit;
                        }

                        //greška
                        echo '<script type="text/javascript">';
                        echo 'alert("Incorrect email or password. Please try again");';
                        echo '</script>';
                    }
                    ?>
                </main>
            </div>
            <div class="col-12 col-md-5 col-lg-6 col-xl-7 gradient"></div>
            <!-- <p class="ime">Eventus</p> -->
        </div>
    </div>
</body>




<style>
    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .gradient {
        --size: 20vw;
        --speed: 12s;
        --easing: cubic-bezier(0.8, 0.2, 0.2, 0.8);

        width: var(--size);
        height: var(--size);
        filter: blur(calc(var(--size) / 5));
        background-image: linear-gradient(hsl(158, 82, 57, 85%), hsl(252, 82, 57));
        animation: rotate var(--speed) var(--easing) alternate infinite;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
    }

    @media (min-width: 720px) {
        .gradient {
            --size: 40vw;
        }
    }

    body {
        background-color: #222;
        position: absolute;
        inset: 0;
        display: flex;
        place-content: center;
        align-items: center;
        overflow: hidden;
        margin: 0;
    }

    * {
        transition: all 0.25s ease-out;
    }

    .fade-in {
        animation: fadeIn ease 1s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .ime {
        margin-top: -1400px;
        margin-left: 1300px;
        font-weight: bold;
        transform: rotate(270deg);
        font-size: 250px;
        color: rgba(255, 255, 255, 0.1);

    }

    .naslov {
        font-size: 210px;
        transform: rotate(-90deg);
        position: absolute;
        margin-top: -350px;
        margin-left: 1000px;
        color: white;
        z-index: 2;
        font-weight: bolder;
        filter: opacity(3%);
    }
</style>

<script>
    function changeTextColor(input) {
        input.style.color = 'black';
    }

    function resetTextColor(input) {
        input.style.color = 'white';
    }
</script>
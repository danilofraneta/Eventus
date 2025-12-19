<?php
ob_start(); // Opoziva slanje izlaznih podataka MORAM JER NECE DVA HEADER-A DA SALJE

session_start();
include('php/db.php');
?>


<head>
    <title>Sign up</title>
    <link rel="stylesheet" href="css/theme.min.css">
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="scss/checkbox.scss">
</head>

<body class="d-flex h-100 w-100 bg-black text-white" data-bs-spy="scroll" data-bs-target="#navScroll">
    <div class="h-100 container-fluid">
        <div class="h-100 row d-flex align-items-stretch">
            <div class="col-12 col-md-8 col-lg-7 col-xl-6 d-flex align-items-start flex-column px-vw-5 fade-in">

                <header class="mb-auto py-vh-2 col-12">
                    <a class="navbar-brand pe-md-4 fs-4 col-12 col-md-auto text-center" href="index.php">
                        <img src="images/logo.png" alt="Ēventus">
                        <span class="ms-md-1 mt-1 fw-bolder me-md-5">Eventus</span>
                    </a>
                </header>

                <main class="mb-auto col-12">
                    <h2 class="fs-3">Sign up</h2>
                    <br>

                    <form class="row" action="" method="post">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstName" class="form-label" style="font-size: 17px;">First name</label>
                                <input type="text" name="firstName" class="form-control bg-gray-800 border-dark" id="firstName" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label" style="font-size: 17px;">Last name</label>
                                <input type="text" name="lastName" class="form-control bg-gray-800 border-dark" id="lastName" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)">
                                <label class="form-text">Make sure it matches the name on your goverment ID</label>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label" style="font-size: 17px;">Username</label>
                                <input type="text" name="username" class="form-control bg-gray-800 border-dark" id="username" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)"><br>
                            </div>

                            <button type="submit" name="register" id="register" class="btn btn-white mb-3" style="font-size: 17px; margin-top:0px;">Register</button><br>
                            <label class="form-label" style="font-size: 17px; ">Already have an account? <a href="login.php" class="form-text" style="font-size: 17px;">Log in</a></label>

                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label" style="font-size: 17px;">Email</label>
                                <input type="email" name="email" class="form-control bg-gray-800 border-dark" id="email" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label" style="font-size: 17px;">Password</label>
                                <input type="password" name="password" class="form-control bg-gray-800 border-dark" id="password" required onfocus="changeTextColor(this)" onblur="resetTextColor(this)">
                            </div>
                            <div class="mb-3">
                                <!-- <label class="checkbox">
                                    <input type="checkbox" />
                                    _I agree to the terms and conditions
                                    
                                    <svg viewBox="0 0 21 18">
                                        <symbol id="tick-path" viewBox="0 0 21 18" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.22003 7.26C5.72003 7.76 7.57 9.7 8.67 11.45C12.2 6.05 15.65 3.5 19.19 1.69" fill="none" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                                        </symbol>
                                        <defs>
                                            <mask id="tick">
                                                <use class="tick mask" href="#tick-path" />
                                            </mask>
                                        </defs>
                                        <use class="tick" href="#tick-path" stroke="currentColor" />
                                        <path fill="white" mask="url(#tick)" d="M18 9C18 10.4464 17.9036 11.8929 17.7589 13.1464C17.5179 15.6054 15.6054 17.5179 13.1625 17.7589C11.8929 17.9036 10.4464 18 9 18C7.55357 18 6.10714 17.9036 4.85357 17.7589C2.39464 17.5179 0.498214 15.6054 0.241071 13.1464C0.0964286 11.8929 0 10.4464 0 9C0 7.55357 0.0964286 6.10714 0.241071 4.8375C0.498214 2.39464 2.39464 0.482143 4.85357 0.241071C6.10714 0.0964286 7.55357 0 9 0C10.4464 0 11.8929 0.0964286 13.1625 0.241071C15.6054 0.482143 17.5179 2.39464 17.7589 4.8375C17.9036 6.10714 18 7.55357 18 9Z" />
                                    </svg>
                                    <svg class="lines" viewBox="0 0 11 11">
                                        <path d="M5.88086 5.89441L9.53504 4.26746" />
                                        <path d="M5.5274 8.78838L9.45391 9.55161" />
                                        <path d="M3.49371 4.22065L5.55387 0.79198" />
                                    </svg>
                                </label> -->
                                <input type="checkbox" name="role" id="role"><label class="form-text" style="font-size: 17px;">&nbsp; I want to host an event (optional)</label>
                            </div>
                        </div>

                    </form>


                    <?php
                    if (isset($_POST['register'])) {
                        $firstName = $_POST['firstName'];
                        $lastName = $_POST['lastName'];
                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $userRole = isset($_POST['role']) ? 2 : 1;

                        // Provera da li korisničko ime ili email već postoje u bazi
                        $checkUser = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
                        $checkEmail = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");

                        if (mysqli_num_rows($checkUser) > 0) {
                            echo '<script>alert("Username already exists. Please choose another one.");</script>';
                        } elseif (mysqli_num_rows($checkEmail) > 0) {
                            echo '<script>alert("Email already exists. Please use another one.");</script>';
                        } else {
                            // Unos podataka u bazu
                            $insertQuery = "INSERT INTO user (name, surname, username, email, password, userRole) VALUES ('$firstName', '$lastName', '$username', '$email', '$password', '$userRole')";
                            if (mysqli_query($conn, $insertQuery)) {
                                $userSelect = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password' AND userRole = '$userRole'");
                                $userRow = mysqli_fetch_array($userSelect);

                                $_SESSION["userID"] = $userRow['userID'];
                                $_SESSION["email"] = $userRow['email'];
                                $_SESSION["password"] = $userRow['password'];
                                $_SESSION["userRole"] = $userRole;
                                $_SESSION['name'] = $userRow['name'];
                                $_SESSION['surname'] = $userRow['surname'];
                                $_SESSION['username'] = $userRow["username"];

                                // Preusmjeravanje korisnika na odgovarajuću stranicu u zavisnosti od uloge
                                if ($userRole == 1) {
                                    header("Location: indexUser.php");
                                } else {
                                    header("Location: indexManager.php");
                                }
                                exit;
                            } else {
                                echo '<script>alert("Error registering user. Please try again.");</script>';
                            }
                        }
                    }
                    ?>
                </main>
            </div>
            <div class="col-12 col-md-5 col-lg-6 col-xl-7 gradient"></div>
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
        /* Use viewport width units for responsive size */
        --speed: 20s;
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
            /* Adjust the size for larger screens */
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
</style>

<script>
    function changeTextColor(input) {
        input.style.color = 'black';
    }

    function resetTextColor(input) {
        input.style.color = 'white';
    }
</script>
<?php
ob_end_flush(); // Završava odgodu slanja izlaznih podataka i šalje ih klijentu
?>
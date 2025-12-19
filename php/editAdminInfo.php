<?php
include('db.php');

// Izvlačenje korisnika
$getUserDataQuery = "SELECT * FROM user WHERE userRole = 1";
$userDataResult = mysqli_query($conn, $getUserDataQuery);

// Izvlačenje menadžera
$getEventManagerDataQuery = "SELECT * FROM user WHERE userRole = 2";
$eventManagerDataResult = mysqli_query($conn, $getEventManagerDataQuery);

// Izvlačenje venue-a
$getVenueDataQuery = "SELECT * FROM venue";
$venueDataResult = mysqli_query($conn, $getVenueDataQuery);

?>

<link rel="stylesheet" href="css/admin.css">

<div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
    <div class="position-absolute w-100 h-100 bg-black opacity-75 top-0 start-0"></div>
    <div class="container py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Tabela za korisnike -->
        <h4 style="text-align: left;">Users:</h4>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($userDataResult && mysqli_num_rows($userDataResult) > 0) {
                    while ($row = mysqli_fetch_assoc($userDataResult)) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $row['userID']; ?></th>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['surname']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <select class="userRole combobox" data-user-id="<?php echo $row['userID']; ?>">
                                    <option value="1" <?php if ($row['userRole'] == 1) echo 'selected'; ?>>User</option>
                                    <option value="2" <?php if ($row['userRole'] == 2) echo 'selected'; ?>>Event Manager</option>
                                    <option value="2" <?php if ($row['userRole'] == 3) echo 'selected'; ?>>Admin</option>
                                </select>
                            </td>

                            <td><button class="brisi" data-user-id="<?php echo $row['userID']; ?>" data-user-name="<?php echo $row['name']; ?>"><i class="bi bi-trash3"></i></button></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found!</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Tabela za menadžere -->
        <h4 style="text-align: left;">Event Managers:</h4>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">Manager ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($eventManagerDataResult && mysqli_num_rows($eventManagerDataResult) > 0) {
                    while ($row = mysqli_fetch_assoc($eventManagerDataResult)) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $row['userID']; ?></th>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['surname']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <select class="userRole combobox" data-user-id="<?php echo $row['userID']; ?>">
                                    <option value="1" <?php if ($row['userRole'] == 1) echo 'selected'; ?>>User</option>
                                    <option value="2" <?php if ($row['userRole'] == 2) echo 'selected'; ?>>Event Manager</option>
                                    <option value="3" <?php if ($row['userRole'] == 3) echo 'selected'; ?>>Admin</option>
                                </select>
                            </td>
                            <td><button class="brisi" data-user-id="<?php echo $row['userID']; ?>" data-user-name="<?php echo $row['name']; ?>"><i class="bi bi-trash3"></i></button></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No event managers found!</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Tabela za venue -->
        <h4 style="text-align: left;">Venues:</h4>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">Venue ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($venueDataResult && mysqli_num_rows($venueDataResult) > 0) {
                    while ($row = mysqli_fetch_assoc($venueDataResult)) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $row['venueID']; ?></th>
                            <td><?php echo $row['venueName']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='3'>No venues found!</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>

<?php
// Zatvaranje veze s bazom podataka
mysqli_close($conn);
?>

<script>
    // Kada se dokument učita
    document.addEventListener("DOMContentLoaded", function() {
        // Pronađi sva dugmad za brisanje
        var deleteButtons = document.querySelectorAll('.brisi');

        // Iteriraj kroz svako dugme i dodaj event listener
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Dobavi ID korisnika koji treba izbrisati
                var userID = this.getAttribute('data-user-id');
                // Dobavi ime korisnika za potvrdu
                var name = this.getAttribute('data-user-name');

                // Prikaži potvrdu
                var confirmDelete = confirm("Are you sure you want to delete " + name + "?");

                // Ako je korisnik potvrdio brisanje
                if (confirmDelete) {
                    // Pošalji AJAX zahtjev na server
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'php/delete_user.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status == 200) {
                            // Osvježi stranicu nakon brisanja
                            location.reload();
                        } else {
                            console.error(xhr.responseText);
                        }
                    };
                    xhr.send('userID=' + userID);
                }
            });
        });
    });




    document.addEventListener("DOMContentLoaded", function() {
        var selectElements = document.querySelectorAll('.userRole');

        selectElements.forEach(function(select) {
            select.addEventListener('change', function() {
                var userID = this.getAttribute('data-user-id');
                var userRole = this.value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'php/update_user_role.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'error' && userID == 18) {
                            alert(response.message);
                        } else {
                            location.reload();
                        }
                    } else {
                        console.error(xhr.responseText);
                    }
                };
                xhr.send('userID=' + userID + '&userRole=' + userRole);
            });
        });
    });
</script>
<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID']) && isset($_POST['userRole'])) {
    $userID = $_POST['userID'];
    $userRole = $_POST['userRole'];

    // Provera za userID 18
    if ($userID == 18) {
        echo json_encode(array('status' => 'error', 'message' => 'The role for manager was not updated!'));
    } else {
        $updateUserRoleQuery = "UPDATE user SET userRole = $userRole WHERE userID = $userID";

        if (mysqli_query($conn, $updateUserRoleQuery)) {
            echo json_encode(array('status' => 'success', 'message' => 'Uloga korisnika je uspješno ažurirana.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Greška prilikom ažuriranja uloge korisnika: ' . mysqli_error($conn)));
        }
    }

    mysqli_close($conn);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Nije proslijeđen ispravan zahtjev.'));
}

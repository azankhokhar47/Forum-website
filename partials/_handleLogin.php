<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    session_start();

    $email = $_POST['loginEmail'];
    $pass = $_POST['loginPass'];

    $sql = "SELECT * FROM users WHERE user_email = '$email'";
    $result = mysqli_query($conn, $sql);
    $numRows = mysqli_num_rows($result);

    if ($numRows == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['user_pass'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['useremail'] = $email;
            $_SESSION['userid'] = $row['sno'];
            header("Location: ../index.php");
            exit;
        } else {
            // Wrong password
            header("Location: ../index.php?error=wrongpass");
            exit;
        }
    } else {
        // No such user
        header("Location: ../index.php?error=nouser");
        exit;
    }
}
?>

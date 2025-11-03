<?php
session_start();

echo '
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/forum">iDiscuss</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/forum">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            Top Categories
          </a>
          <ul class="dropdown-menu">';

          $sql = "SELECT categori_name, categori_id FROM `categories` LIMIT 3";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<li><a class="dropdown-item" href="threadlist.php?catid=' . $row['categori_id'] . '">' . $row['categori_name'] . '</a></li>';
          }

          echo '</ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
      </ul>
    </div>

    <div class="row mx-2">
';
?>

<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '
      <form class="d-flex" role="search" method="get" action="search.php">
        <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search" required>
        <button class="btn btn-success" type="submit">Search</button>
        <p class="mx-2 my-0 text-success">Welcome ' . ($_SESSION['useremail']) . '</p>
        <a href="partials/_logout.php" class="btn btn-outline-success ms-2">Logout</a>
      </form>';
} else {
    echo '
      <form class="d-flex" role="search" method="get" action="search.php">
        <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search" required>
        <button class="btn btn-success" type="submit">Search</button>
        <button type="button" class="btn btn-outline-success ms-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
        <button type="button" class="btn btn-outline-success mx-2" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</button>
      </form>';
}
?>

<?php
echo '
    </div>
  </div>
</nav>
';

include 'partials/LoginModel.php';
include 'partials/SignupModel.php';

if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true") {
    echo '
    <div class="alert alert-success alert-dismissible fade show my-0" role="alert">
      ✅ Congratulations! You have successfully signed up!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

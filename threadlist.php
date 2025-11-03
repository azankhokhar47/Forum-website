<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>iDiscuss - Coding forum</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
  <?php
  include 'partials/_dbconnect.php';
  include 'partials/_header.php';

  $id = $_GET['catid'];

  // --- Category info ---
  $sql = "SELECT * FROM `categories` WHERE categori_id=$id";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $catname = $row['categori_name'];
  $catdesc = $row['categori_discription'];
  ?>

  <?php
  // --- Post new thread ---
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $th_title = $_POST['tittle'];
      $th_desc = $_POST['desc'];
      $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) 
              VALUES ('$th_title', '$th_desc', '$id', '0', current_timestamp())";
      $result = mysqli_query($conn, $sql);

      if ($result) {
          echo '<div class="alert alert-success" role="alert">
                  ✅ Your question has been posted successfully!
                </div>';
      } else {
          echo '<div class="alert alert-danger" role="alert">
                  ❌ Failed to submit the question.
                </div>';
      }
  }
  ?>

  <!-- Category Description -->
  <div class="container my-4">
    <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">Welcome to the <?php echo $catname; ?> Forum</h4>
      <p><?php echo $catdesc; ?></p>
      <hr>
      <p class="mb-0">
        This is a peer-to-peer forum to share knowledge with each other. 
        No Spam, Advertising, or Self-promotion is allowed. Be respectful and stay on topic.
      </p>
      <a><button type="button" class="btn btn-success mb-2 mt-4">Learn more</button></a>
    </div>
  </div>

  <!-- Post Question Form -->
  <?php
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
      echo '
      <div class="container">
        <h1>Start a discussion</h1>
        <form action="' . $_SERVER["REQUEST_URI"] . '" method="post">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Problem Title</label>
            <input type="text" class="form-control" id="tittle" name="tittle" required>
            <div id="emailHelp" class="form-text">Keep your title short and clear.</div>
          </div>
          <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Elaborate your problem</label>
            <textarea class="form-control" id="desc" name="desc" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-success">Submit</button>
        </form>
      </div>';
  } else {
      echo '
      <div class="container">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          You are not logged in, please login to start a discussion.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>';
  }
  ?>

  <!-- Browse Questions -->
  <div class="container py-2 mb-5">
    <h1 class="p-3">Browse Questions</h1>

    <?php
    $id = $_GET['catid'];

    // --- Pagination setup ---
    $threads_per_page = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $threads_per_page;

    // Count total threads
    $count_sql = "SELECT COUNT(*) AS total FROM `threads` WHERE thread_cat_id=$id";
    $count_result = mysqli_query($conn, $count_sql);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_threads = $count_row['total'];
    $total_pages = ceil($total_threads / $threads_per_page);

    // Fetch threads for current page
    $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id ORDER BY thread_id DESC LIMIT $threads_per_page OFFSET $offset";
    $result = mysqli_query($conn, $sql);

    $noResult = true;

    while ($row = mysqli_fetch_assoc($result)) {
        $noResult = false;
        $thread_id = $row['thread_id'];
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];

        echo '<div class="d-flex my-3">
                <div class="flex-shrink-0">
                    <img src="images/user-default.img" width="50px" alt="User">
                </div>
                <div class="as-0">
                    <h5 class="ms-3">
                      <a class="text-dark" href="thread.php?threadid=' . $thread_id . '">' . htmlspecialchars($title) . '</a>
                    </h5>
                    <div class="flex-grow-1 ms-3">' . htmlspecialchars($desc) . '</div>
                </div>
              </div>';
    }

    if ($noResult) {
        echo '<div class="alert alert-success" role="alert">
                <h4 class="alert-heading">No questions for this category</h4>
                <p>Be the first to ask a question.</p>
                <hr>
              </div>';
    }

    // --- Pagination Buttons ---
    if ($total_pages > 1) {
        echo '<nav aria-label="Thread navigation">
                <ul class="pagination justify-content-center mt-4">';

        // Previous Button
        if ($page > 1) {
            echo '<li class="page-item">
                    <a class="page-link" href="threadlist.php?catid=' . $id . '&page=' . ($page - 1) . '">Previous</a>
                  </li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        // Page Numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<li class="page-item ' . $active . '">
                    <a class="page-link" href="threadlist.php?catid=' . $id . '&page=' . $i . '">' . $i . '</a>
                  </li>';
        }

        // Next Button
        if ($page < $total_pages) {
            echo '<li class="page-item">
                    <a class="page-link" href="threadlist.php?catid=' . $id . '&page=' . ($page + 1) . '">Next</a>
                  </li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        echo '  </ul>
              </nav>';
    }
    ?>
  </div>

  <?php include 'partials/_footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>
</html>
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

// ---------- Get thread details ----------
if (isset($_GET['threadid']) && is_numeric($_GET['threadid'])) {
    $id = $_GET['threadid'];
} else {
    die("❌ threadid is missing in URL.");
}

$sql = "SELECT * FROM `threads` WHERE thread_id = $id";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("❌ SQL Query Failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$title = $row['thread_title'];
$desc = $row['thread_desc'];
?>

<!-- ---------- Thread Info ---------- -->
<div class="container my-4">
  <div class="alert alert-success" role="alert">
    <h4 class="alert-heading"><?php echo $title; ?></h4>
    <p><?php echo $desc; ?></p>
    <hr>
    <p class="mb-0">
      This is a peer-to-peer forum for sharing knowledge with each other.
      No spam, self-promotion, or disrespectful language is allowed.
    </p>
    <a><button type="button" class="btn btn-success mb-2 mt-4">Learn more</button></a>
  </div>
</div>

<?php
// ---------- Handle comment submission ----------
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') {
    $comment = $_POST['comment'];
    $comment = str_replace("<", "&lt;", $comment);
    $comment = str_replace(">", "&gt;", $comment);
    $userId = $_SESSION["userid"];
    $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) 
            VALUES ('$comment', '$id', '$userId', current_timestamp())";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<div class="alert alert-success" role="alert">
                ✅ Your comment has been posted successfully!
              </div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
                ❌ Failed to submit the comment.
              </div>';
    }
}
?>

<!-- ---------- Comment Form ---------- -->
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '
    <div class="container">
      <h1>Start a discussion</h1>
      <form action="' . $_SERVER["REQUEST_URI"] . '" method="post">
        <div class="mb-3">
          <label for="comment" class="form-label">Type your comment</label>
          <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Post comment</button>
      </form>
    </div>';
} else {
    echo '
    <div class="container">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        You are not logged in, please login to be able to start a discussion.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>';
}
?>

<!-- ---------- Comments Section ---------- -->
<div class="container py-2 mb-5">
  <h1>Discussions</h1>

<?php
$thread_id = $_GET['threadid'];

// ---- Pagination setup ----
$comments_per_page = 10; // show 10 comments per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $comments_per_page;

// ---- Count total comments ----
$total_sql = "SELECT COUNT(*) AS total FROM `comments` WHERE thread_id = $thread_id";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_comments = $total_row['total'];
$total_pages = ceil($total_comments / $comments_per_page);

// ---- Get only the comments for the current page ----
$sql = "SELECT comments.*, users.user_email 
        FROM comments 
        LEFT JOIN users ON comments.comment_by = users.sno 
        WHERE comments.thread_id = $thread_id 
        ORDER BY comments.comment_id DESC 
        LIMIT $comments_per_page OFFSET $offset";

$result = mysqli_query($conn, $sql);
$noResult = true;

while ($row = mysqli_fetch_assoc($result)) {
    $noResult = false;
    $content = $row['comment_content'];
    $comment_time = $row['comment_time'];
    $user_email = $row['user_email'] ?? "Unknow User";
    echo '<div class="d-flex my-3">
            <div class="flex-shrink-0">
                <img src="images/user-default.img" width="50px" alt="User">
            </div>
            <div class="flex-grow-1 ms-3">
                <p class="fw-bold my-0">'.$user_email.' <small class="text-muted">' . $comment_time . '</small></p>
                <p>' . $content . '</p>
            </div>
          </div>';
}

// ---- No comments ----
if ($noResult) {
    echo '<div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">No comments yet</h4>
            <p>Be the first person to reply.</p>
            <hr>
          </div>';
}

// ---- Pagination buttons ----
if ($total_pages > 1) {
    echo '<nav aria-label="Comment navigation">
            <ul class="pagination justify-content-center mt-4">';

    // Previous button
    if ($page > 1) {
        echo '<li class="page-item">
                <a class="page-link" href="thread.php?threadid=' . $thread_id . '&page=' . ($page - 1) . '">Previous</a>
              </li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo '<li class="page-item ' . $active . '">
                <a class="page-link" href="thread.php?threadid=' . $thread_id . '&page=' . $i . '">' . $i . '</a>
              </li>';
    }

    // Next button
    if ($page < $total_pages) {
        echo '<li class="page-item">
                <a class="page-link" href="thread.php?threadid=' . $thread_id . '&page=' . ($page + 1) . '">Next</a>
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
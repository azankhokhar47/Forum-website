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
?>

<div class="container my-3">
  <h1 class="py-3">Search results for <em>"<?php echo $_GET['search'] ?>"</em></h1>

  <?php
  $noresult = true;
  $query = $_GET["search"];
  $sql = "SELECT * FROM threads WHERE MATCH(thread_title, thread_desc) AGAINST ('$query')";
  $result = mysqli_query($conn, $sql);

  while ($row = mysqli_fetch_assoc($result)) {
      $title = $row['thread_title'];
      $desc = $row['thread_desc'];
      $thread_id = $row['thread_id'];
      $url = "thread.php?threadid=" . $thread_id;

      $noresult = false;

      echo '<div class="result mb-3">
              <h3><a href="' . $url . '" class="text-dark">' . $title . '</a></h3>
              <p>' . $desc . '</p>
            </div>';
  }

  if ($noresult) {
      echo '<div class="alert alert-success" role="alert">
              <h4 class="alert-heading">No Results Found</h4>
              <p>Suggestions:</p>
              <hr>
              <ul>
                <li>Make sure that all words are spelled correctly.</li>
                <li>Try different keywords.</li>
                <li>Try more general keywords.</li>
                <li>Try fewer keywords.</li>
              </ul>
            </div>';
  }
  ?>

</div>

<?php include 'partials/_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"
        integrity="sha256-S1J4GVHHDMiirir9qsXWc8ZWw74PHHafpsHp5PXtjTs=" crossorigin="anonymous"></script>
</body>

</html>
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

    <!-- slider start here -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel"
    style="width:1350px; height:500px; margin:auto;">

    <!-- ✅ Carousel Indicators (now 6 dots) -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
            aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
            aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
            aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"
            aria-label="Slide 5"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5"
            aria-label="Slide 6"></button>
    </div>

    <!-- ✅ Carousel Images -->
    <div class="carousel-inner" style="height:500px;">
        <div class="carousel-item active">
            <img src="images/python3.img" class="d-block w-100" style="height:650px; object-fit:cover;"
                alt="Python Slide">
        </div>
        <div class="carousel-item">
            <img src="images/php3.img" class="d-block w-100" style="height:650px; object-fit:cover;" alt="PHP Slide">
        </div>
        <div class="carousel-item">
            <img src="images/php4.img" class="d-block w-100" style="height:650px; object-fit:cover;" alt="PHP Slide 2">
        </div>
        <div class="carousel-item">
            <img src="images/php9.img.png" class="d-block w-100" style="height:650px; object-fit:cover;" alt="PHP Slide 3">
        </div>
        <div class="carousel-item">
            <img src="images/php7.img" class="d-block w-100" style="height:650px; object-fit:cover;" alt="PHP Slide 4">
        </div>
        <div class="carousel-item">
            <img src="images/php11.img" class="d-block w-100" style="height:650px; object-fit:cover;" alt="PHP Slide 5">
        </div>
    </div>
</div>

<!-- ✅ Auto slide setup -->
<script>
  const carousel = document.querySelector('#carouselExampleIndicators');
  new bootstrap.Carousel(carousel, {
    interval: 3000, // 3 seconds
    ride: 'carousel',
    wrap: true
  });
</script>


    <script>
    // Optional: Ensure smooth automatic sliding (3s interval)
    const carousel = document.querySelector('#carouselExampleIndicators');
    new bootstrap.Carousel(carousel, {
        interval: 3000, // 3 seconds between slides
        ride: 'carousel',
        wrap: true // loops back to first slide automatically
    });
    </script>

    <!-- catagories container start here   -->

    <div class="container my-3 ">
        <h2 class="text-center my-3">iDiscuss - browse Categories</h2>
        <div class="row">

            <!-- fetch all the catagaries and use a loop to itegeate a catagaries -->
            <?php
            $sql = "SELECT * FROM `categories`";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                  
              $id = $row['categori_id'];
              $cat = $row['categori_name'];
              $desc = $row['categori_discription']; 

               echo '<div class="col-md-4 my-2">
            <div class="card" style="width: 18rem;">
                <img src="images/card-'.$id.'.img" class="card-img-top" alt="'. $cat .'">
                <div class="card-body">
                    <h5 class="card-title"><a href="threadlist.php?catid='. $id .' "> '. $cat .'  </a></h5>
                    <p class="card-text">'. $desc .'</p>
                    <a href="threadlist.php?catid='. $id .' " class="btn btn-primary">View Threads</a>
                </div>
            </div>
          </div>';
            }
            ?>
        </div>
    </div>
    <?php
  include 'partials/_footer.php';
  ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"
        integrity="sha256-S1J4GVHHDMiirir9qsXWc8ZWw74PHHafpsHp5PXtjTs=" crossorigin="anonymous"></script>
</body>

</html>
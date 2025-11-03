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

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['username'];
        $issue = $_POST['description'];

        // Prevent SQL Injection
        $name = mysqli_real_escape_string($conn, $name);
        $issue = mysqli_real_escape_string($conn, $issue);

        $sql = "INSERT INTO `contact` (`contact_name`, `contact_issue`) VALUES ('$name', '$issue')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    ✅ Your issue has been submitted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    ❌ Failed to submit your issue. Please try again later.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }
    ?>

    <div class="alert alert-primary alert-dismissible fade show text-center" role="alert" id="welcomeAlert">
        <strong>Contact us❤️</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
    setTimeout(function() {
        const alert = document.getElementById('welcomeAlert');
        if (alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 2000);
    </script>

    <div class="container">
        <div class="container my-5">
            <h2 class="text-center mb-4 text-primary fw-bold">Report an Issue</h2>

            <!-- ✅ Added method and action -->
            <form class="row g-3 col-md-8 mx-auto p-4 shadow-lg rounded bg-light" method="post" action="">
                <!-- Username -->
                <div class="col-md-6 text-center mx-auto">
                    <label for="username" class="form-label fw-semibold d-block text-center">Username</label>
                    <input type="text" class="form-control text-center mx-auto" id="username" name="username"
                        placeholder="Enter your username" style="max-width: 300px;" required>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label for="description" class="form-label fw-semibold">Describe Your Issue</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                        placeholder="Please explain your issue here..." required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success px-5">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'partials/_footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
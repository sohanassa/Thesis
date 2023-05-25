<?php include(dirname(dirname(__FILE__)) . '/process.php') ?>
<?php if (!isset($_SESSION["inst"])) :
?>
  ACCESS DENIED
<?php else : ?>
  <!doctype html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Online Shop.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Instructor page</title>
  </head>

  <body style="background-color: #818589;">

    <section id="Nav-Bar" style="background-color: #36454F;">
      <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
          <nav class="navbar navbar-light ">
            <div class="container-fluid">
            <a class="navbar-brand" href="../index.php" style="color:white">
                                Scheduling Tool
                            </a>
                        </div>
                    </nav>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="instructor.php">Instructor Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="my_timetable.php" style="color:white">My Schedule</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_course.php" style="color:white">Add Course</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_lab.php" style="color:white">Add Labratory</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="unavailable_days.php" style="color:white">Add Unavailability</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="view_my_courses.php" style="color:white">My Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="remove_course.php" style="color:white">Remove Course</a>
                            </li>
                            <li class="nav-item">
                             <a class="nav-link" href="../change_password.php" style="color:white">Change Password</a>
                            </li>
              <?php if (isset($_SESSION["username"])) :
              ?>
              <?php endif; ?>
            </ul>
            <?php if (isset($_SESSION["username"])) :
            ?>
              <ul class="navbar-nav navbar-right">
                <li>
                  <form action="instructor.php" method="post" id="logout_b">
                    <button type="submit" class="btn btn-outline-light" name="logout_b">Logout</button>
                  </form>
                </li>
              </ul>
            <?php else : ?>
            <?php endif; ?>
          </div>
        </div>
      </nav>
    </section>
    <br>

    <div style="width: 50%; height: 100px; margin: 0 auto;">
      <h2 style="text-align: center;">Instructor page</h2>
    </div>

    <div style="width: 50%; height: 100px; margin: 0 auto;">
    <h5>A simple guide</h5>
    <p>
       <br>
      <strong>Step 1: </strong>Add Unavailability <br><br>
      &emsp; &emsp; &emsp;Go to the <a href="unavailable_days.php" style="color:black">Add Unavailability</a> page to start the process. <br><br>
      &emsp; &emsp; &emsp;To update the unavailability, just go to the page and add the updated unavailability, the previous data will be deleted.<br><br>
      <strong>Step 2: </strong>Register a Course <br><br>
      &emsp; &emsp; &emsp;Go to the <a href="add_course.php" style="color:black">Add Course</a> page to start the process, note that any necessary Tutorials and Labratories will be added<br>
      &emsp; &emsp; &emsp;automatically. <br><br>
      <strong>Step 3: </strong>Choose a labratory to teach, if any <br><br>
      &emsp; &emsp; &emsp;Go to the <a href="add_lab.php" style="color:black">Add Labratory</a> page to start the process, note that the labratory needs to have been already registered by<br> 
      &emsp; &emsp; &emsp;the Instructor of the course. <br><br>
    </p>
    </div>
    <div style="height: 220px;"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <?php include('../footer.php'); ?>
</body>

  </html>
<?php endif; ?>
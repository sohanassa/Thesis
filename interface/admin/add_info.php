<?php include(dirname(dirname(__FILE__)) . '/process.php') ?>
<?php if (!isset($_SESSION["admin"])) :
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

    <title>Add Room</title>
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
                <a class="nav-link" href="admin.php" style="color:white">Admin Page</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="all_schedules.php" style="color:white">Schedules</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="course_combos.php" style="color:white">Combinations schedule</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="add_info.php">Add Initial Information</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="add_courses_conflict.php" style="color:white">Add Courses Conflicts</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="add_courses_parallel.php" style="color:white">Add Courses Parallel</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="view_courses.php" style="color:white">View Added Courses</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="add_admin.php" style="color:white">Register Admin</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="register_inst.php" style="color:white">Register Instructor</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="remove_inst.php" style="color:white">Remove Instructor</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../change_password.php" style="color:white">Change Password</a>
              </li>
              <?php if (isset($_SESSION["username"])) :
              ?>
              <?php else : ?>
              <?php endif; ?>
            </ul>
            <?php if (isset($_SESSION["username"])) :
            ?>
              <ul class="navbar-nav navbar-right">
                <li>
                  <form action="add_admin.php" method="post" id="logout_b">
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
    <div style="width: 30%; margin: 0 auto;">
      <h2 style="text-align: center;">Enter Initial Information</h2>
      <form method="post" action="add_info.php">
        <?php include(dirname(dirname(__FILE__)) . '/errors.php'); ?>
        <div class="form-group">
          <label for="num_of_lecture_rooms">The number of available lecture halls/rooms</label>
          <input class="form-control" type="text" id="num_of_lecture_rooms" placeholder="20" name="num_of_lecture_rooms" required>
        </div>
        <div class="form-group">
          <label for="num_of_lab_rooms">The number of available labratories</label>
          <input class="form-control" type="text" id="num_of_lab_rooms" placeholder="15" name="num_of_lab_rooms" required>
        </div>
        <div class="form-group">
          <label for="lec_capacity">The maximum capacity of the lecture halls</label>
          <input class="form-control" type="text" id="lec_capacity" placeholder="80" name="lec_capacity" required>
        </div>
        <div class="form-group">
          <label for="lab_capacity">The maximum capacity of the labratories</label>
          <input class="form-control" type="text" id="lab_capacity" placeholder="35" name="lab_capacity" required>
        </div>
        <div style="height: 10px;"></div>
        <div style="text-align: center; display:flex; justify-content: center;">
          <button type="submit" class="btn btn-outline-light" name="add_info">Submit</button>
        </div>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php include('../footer.php'); ?>
  </body>

  </html>
<?php endif; ?>
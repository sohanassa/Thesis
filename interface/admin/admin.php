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

    <title>Admin Page</title>
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
                <a class="nav-link" href="admin.php">Admin Page</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="all_schedules.php" style="color:white">Schedules</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="course_combos.php" style="color:white">Combinations schedule</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="add_info.php" style="color:white">Add Initial Information</a>
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
            </ul>
            <?php if (isset($_SESSION["username"])) :
            ?>
              <ul class="navbar-nav navbar-right">
                <li>
                  <form action="admin.php" method="post" id="logout_b" style="color: blanchedalmond;">
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

    <div style="width: 40%; height: 100px; margin: 0 auto;">
      <h2 style="text-align: center;">Select a solver version to create the Schedule</h2>
      <br>
     
      <form method="post" action="admin.php">
        <?php include(dirname(dirname(__FILE__)) . '/errors.php'); ?>
        <div style="width: 40%;  margin: 0 auto;">
          <ul class="list-group">
            <li class="list-group-item" style="background-color: #6a6e73;">
              <div class="form-check">
              <label class="form-check-label" for="algo1">Version 1</label>
                <input class="form-check-input" type="radio" name="version" id="algo1" value="1" checked>
                <br><br>
                <label class="form-check-label" for="algo2">Version 2</label>
                <input class="form-check-input" type="radio" name="version" id="algo2" value="1">
                <br><br>
                <label class="form-check-label" for="algo3">Version 3</label>
                <input class="form-check-input" type="radio" name="version" id="algo3" value="1">
              </div>
            </li>
          </ul>
        </div>
        <br>
      <br>
      <p><strong>Version 1:</strong> Generates a timetable that fulfills all constraints provided by instructors and administrators.</p>
      <p><strong>Version 2:</strong> Satisfies all given requirements but also ensures that iterations of a laboratory are taught on the <strong>same day</strong>.</p> 
      <p><strong>Version 3:</strong> Not only satisfies all given requirements but also ensures that iterations of a laboratory are taught on the <strong>same day in succession</strong>.</p> 
      

      <div style="text-align: center; display:flex; justify-content: center;">
          <button type="submit" class="btn btn-outline-light" name="run_algo">Create Schedule</button>
        </div>
        <br>
        <p style="font-size: 17px; text-align: center;">All solvers utilize the <a href="https://www.minizinc.org/">MiniZinc</a> tool. </p>
    </form>
     

    </div>
    <div style="height: 220px;"></div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <?php include('../footer.php'); ?>
</body>

  </html>
<?php endif; ?>
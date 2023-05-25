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

    <title>Add admin</title>
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
                <a class="nav-link" href="add_admin.php">Register Admin</a>
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

    <script>
      function checkData() {
        var email = document.getElementById("emailadress").value;
        if (email.length < 5) {
          alert("Email must be at least 5 characters long!");
          return false;
        }
        if (email.search(/@/) < 0) {
          alert("Email must have @ symbol!");
          return false;
        }
        return true;
      }
    </script>
    <br>
    <div style="width: 30%; margin: 0 auto;">
      <h2 style="text-align: center;">Register an Administrator</h2>
      <form method="post" action="add_admin.php">
        <?php include(dirname(dirname(__FILE__)) . '/errors.php'); ?>
        <div class="form-group">
          <label for="inst_username">Username</label>
          <input class="form-control" type="text" id="inst_username" placeholder="Enter username" name="add_admin_username" required>
        </div>
        <div class="form-group">
          <label for="password">OTP (One-time password)</label>
          <input class="form-control" type="text" id="password" placeholder="Enter otp" name="password" required>
        </div>
        <div style="height: 10px;"></div>
        <div style="text-align: center; display:flex; justify-content: center;">
          <button type="submit" class="btn btn-outline-light" name="reg_admin">Submit</button>
        </div>
      </form>
    </div>
    <div style="height: 100px;"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php include('../footer.php'); ?>
  </body>

  </html>
<?php endif; ?>
<?php include('process.php');
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Online Shop.">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <title>Create Password</title>
</head>

<body style="background-color: #818589;">

  <!-- Completed -->
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
            <?php if (isset($_SESSION["admin"])) :
            ?>
              <li class="nav-item">
                <a class="nav-link" href="admin/admin.php" style="color:white">Admin Page</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="admin/all_schedules.php" style="color:white">Schedules</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="admin/course_combos.php" style="color:white">Combinations schedule</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="admin/add_info.php" style="color:white">Add Initial Information</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin/add_courses_conflict.php" style="color:white">Add Courses Conflicts</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin/add_courses_parallel.php" style="color:white">Add Courses Parallel</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin/view_courses.php" style="color:white">View Added Courses</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin/add_admin.php" style="color:white">Register Admin</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin/register_inst.php" style="color:white">Register Instructor</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin/remove_inst.php" style="color:white">Remove Instructor</a>
              </li>
              
              <?php else : ?>
              
                   <li class="nav-item">
                                <a class="nav-link" href="instructor/instructor.php" style="color:white">Instructor Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="instructor/my_timetable.php" style="color:white">My Schedule</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="instructor/add_course.php" style="color:white">Add Course</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="instructor/add_lab.php" style="color:white">Add Labratory</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="instructor/unavailable_days.php" style="color:white">Add Unavailability</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="instructor/view_my_courses.php" style="color:white">My Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="instructor/remove_course.php" style="color:white">Remove Course</a>
                            </li>
            <?php endif; ?>
              <li class="nav-item">
                <a class="nav-link" href="change_password.php">Change password</a>
              </li>
            </ul>
            <?php if (isset($_SESSION["username"])) :
            ?>
              <ul class="navbar-nav navbar-right">
                <li>
                  <form action="index.php" method="post" id="logout">
                    <button type="submit" class="btn btn-outline-light" name="logout">Logout</button>
                  </form>
                </li>
              </ul>
            <?php else : ?>
            <?php endif; ?>
          </div>
        </div>
      </nav>
    </section>
  </section>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 <div style="width: 30%; margin: 0 auto;">
  <br>
  <h2 style="text-align: center;">Change Password</h2>
  <form method="post" action="change_password.php" id = "login-form" onsubmit="return validatePassword()">
    <?php include('errors.php'); ?>
    <div class="form-group">
      <label for="current_password">Current Password</label>
      <input id="current_password" class="form-control" type="password" placeholder="Enter current password" name="current_password" required>
    </div>
    <div class="form-group">
      <label for="password1">New Password</label>
      <input id="password1" class="form-control" type="password" placeholder="Enter new password" name="password1" required>
    </div>
    <div class="form-group">
      <label for="password2">New Password</label>
      <input id="password2" class="form-control" type="password" placeholder="Enter new password again" name="password2" required>
    </div>
    <div style="height: 10px;"></div>
    <div style="text-align: center; display:flex; justify-content: center;">
      <button type="submit" class="btn btn-outline-light" name="change_password">Submit</button>
    </div>
  </form>
</div>
<div style="height: 80px;"></div>
<?php include('footer.php'); ?>

</body>



</html>
<script>
  function validatePassword() {
    var password1 = document.getElementById("password1").value;
    var password2 = document.getElementById("password2").value;
    if (password1 != password2){
        alert("Enter the same passwords please!");
        return false;
    }
    var regex = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.{8,})/;
    if (!regex.test(password1)) {
      alert("Password must be at least 8 characters long, contain a capital letter and a symbol!");
      return false;
    }
    return true;
  }
</script>
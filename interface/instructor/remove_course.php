<?php include( dirname(dirname(__FILE__)) . '/process.php') ?>
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

    <title>Remove Course</title>
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
                                <a class="nav-link" href="instructor.php" style="color:white">Instructor Page</a>
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
                                <a class="nav-link" href="remove_course.php">Remove Course</a>
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
                  <form action="remove_course.php" method="post" id="logout_b">
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
        F
      }
    </script>
    <div style="width: 30%; margin: 0 auto;">
      <br>
      <h2 style="text-align: center;">Remove a Course</h2>
      <form method="post" action="remove_course.php">
        <?php include(dirname(dirname(__FILE__)) . '/errors.php'); ?>
        <div class="form-group">
        <?php
                $my_username = $_SESSION['username'];
                $result = mysqli_query($db, "SELECT * FROM courses WHERE instructor_username = '$my_username'");
                if ($result->num_rows > 0) {
                  echo '<label for="rem_course_code">Choose Course to Remove</label>';
                  echo "<select required class='form-select' id='rem_course_code' name='rem_course_code' type='text'>";
                  while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["course_code"] . "'>" . $row["course_code"] . "</option>";
                  }
                  echo "</select>";
                  echo '</div>
                  </div>
                  <div style="height: 10px;"></div>
                  <div style="text-align: center; display:flex; justify-content: center;">
                    <button type="submit" class="btn btn-outline-light" name="remove_course">Submit</button>
                  </div>
                  </form>
                  </div>';
                } else {
                  echo '<br><h4 style="text-align: center;">No Courses found!</h4>
                  </div>
                  </form>
                  </div>';
                }
            ?>
        <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <div style="height: 220px;"></div>
    <?php include('../footer.php'); ?>
  </body>

  </html>

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

    <title>Schedules</title>
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
              <a class="nav-link" href="all_schedules.php">Schedules</a>
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
    <h2 style="text-align: center;">View Instructor's shcedule</h2>
    
  <div style="height: 30px;"></div>
<div style="display: flex; justify-content: center;">
  <div style="text-align: center; width: 40%; margin-right: 20px;">
  <div style="width: 50%; margin: 0 auto; ">
  <h4>Select Instructor</h4>
  <br>
  <select class='form-select' id="instructor2" name="instructor">
    <option value=""></option>
    <?php
      $sql = "SELECT * FROM instructors";
      $result = mysqli_query($db, $sql);

      while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['instructor_username'] . '">' . $row['instructor_username'] . '</option>';
      }
    ?>
  </select>
</div>
  </div>
  <div style="text-align: center; width: 40%; margin-left: 20px;">
    <embed id="pdf2" src="../schedules/schedule_.pdf" type="application/pdf" width="100%" height="700px" />
  </div>
</div>


<script>
  const instructor2Select = document.getElementById('instructor2');

  const pdf2 = document.getElementById('pdf2');

  instructor2Select.addEventListener('change', function() {
    const instructorName = instructor2Select.value;
    pdf2.src = `../schedules/schedule_${instructorName}.pdf`;
  });
</script>

   
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <?php include('../footer.php'); ?>
</body>

  </html>
<?php endif; ?>
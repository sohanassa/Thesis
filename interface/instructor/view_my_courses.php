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

        <title>My Courses</title>
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
                                <a class="nav-link" href="view_my_courses.php">My Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="remove_course.php" style="color:white">Remove Course</a>
                            </li>
                            <li class="nav-item">
                             <a class="nav-link" href="../change_password.php" style="color:white">Change Password</a>
                            </li>
                        </ul>
                        <?php if (isset($_SESSION["username"])) :
                        ?>
                            <ul class="navbar-nav navbar-right">
                                <li>
                                    <form action="view_my_courses.php" method="post" id="logout_b">
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

        <div style="width: 50%; height: 100%; margin: 0 auto;">
            <h2 style="text-align: center;">My added courses and labratories</h2>
            <br><br>
            <?php
            $my_username = $_SESSION['username'];
            $result = mysqli_query($db, "SELECT * FROM courses WHERE instructor_username = '$my_username'");
            $courses = array();
            while ($course = mysqli_fetch_field($result)) {
                $courses[] = $course->name;
            }
            echo '<table class="table"> <thead class="thead-dark">
                  <tr>';
            echo '<th>Course code</th>';
            echo '<th>Course type</th>';
            echo '<th>Maximum number of students</th>';
            echo '<th>Repeats</th>';
            echo '</tr> </thead>';

            while ($row = mysqli_fetch_array($result)) {
                $i = 0;
                echo "<tr>";
                foreach ($courses as $item) {
                    if($i != 3){
                    if ($i == 1) {
                        if ($row[$item] == 1)
                            echo '<td>Lectures</td>';
                        else if ($row[$item] == 2)
                            echo '<td>Once a week Lab</td>';
                        else if ($row[$item] == 3)
                            echo '<td>Tutorial</td>';
                        else if ($row[$item] == 4)
                            echo '<td>Twice a week Lab</td>';
                        else if ($row[$item] == 5)
                            echo '<td>Lecture</td>';
                    } else
                        echo '<td>' . $row[$item] . '</td>';
                }
                $i++;
                }
                echo '</tr>';
            }
            echo "</table>";
            ?>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php include('../footer.php'); ?>
</body>
<?php endif; ?>
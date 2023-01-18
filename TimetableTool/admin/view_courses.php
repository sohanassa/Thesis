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

        <title>Home Page</title>
    </head>

    <body style="background-color: #818589;">

        <section id="Nav-Bar" style="background-color: #36454F;">
            <nav class="navbar navbar-expand-lg navbar-light ">
                <div class="container-fluid">
                    <nav class="navbar navbar-light ">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="../index.php">
                                CS Department Timetable Tool
                            </a>
                        </div>
                    </nav>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">Admin page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_admin.php">Add an administrator</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register_inst.php">Register an Instructor</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="remove_inst.php">Remove an Instructor</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_courses_conflict.php">Add courses conflicts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_room.php">Add a room</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="view_courses.php" style="color:blanchedalmond">View added courses</a>
                            </li>
                        </ul>
                        <?php if (isset($_SESSION["username"])) :
                        ?>
                            <ul class="navbar-nav navbar-right">
                                <li>
                                    <form action="admin.php" method="post" id="logout_b">
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
            <h2 style="text-align: center;">Added courses</h2>
            <br><br>
            <?php
            $result = mysqli_query($db, "SELECT * FROM courses");
            $courses = array();
            while ($course = mysqli_fetch_field($result)) {
                $courses[] = $course->name;
            }
            echo '<table class="table"> <thead class="thead-dark">
                  <tr>';
            echo '<th>Course code</th>';
            echo '<th>Course type</th>';
            echo '<th>Maximum number of students</th>';
            echo '<th>Added by</th>';
            echo '</tr> </thead>';

            while ($row = mysqli_fetch_array($result)) {
                $i = 0;
                echo "<tr>";
                foreach ($courses as $item) {
                    if ($i == 1) {
                        if ($row[$item] == 1)
                            echo '<td>Lectures only</td>';
                        else if ($row[$item] == 2)
                            echo '<td>Lectures and a tutorial</td>';
                        else if ($row[$item] == 3)
                            echo '<td>Lectures, a tutorial and 1 lab a week</td>';
                        else if ($row[$item] == 4)
                            echo '<td>Lectures, a tutorial and 2 labs a week</td>';
                    } else
                        echo '<td>' . $row[$item] . '</td>';
                    $i++;
                }
                echo '</tr>';
            }
            echo "</table>";
            ?>
        </div>

    </html>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>

    </html>
<?php endif; ?>
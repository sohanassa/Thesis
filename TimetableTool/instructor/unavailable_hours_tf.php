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
                                <a class="nav-link" href="instructor.php">Instructor page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="my_timetable.php">My timetable</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_course.php">Add a course</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="add_lab.php">Add a labratory</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="pref_days.php">Update course/lab preferred hours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="unavailable_hours_tf.php" style="color:blanchedalmond">Add unavailability</a>
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
        <div style="width: 40%; margin: 0 auto;">
            <h4 style="text-align: center;">Specify hours and days where teaching is not possible</h4>
            <br>
            <form method="post" action="unavailable_hours_tf.php">
                <?php include(dirname(dirname(__FILE__)) . '/errors.php'); ?>
                <h5 style="text-align: center;">On which of the following hours on Tuesday/Friday you can not teach:</h5>
                <div style="width: 40%; margin: 0 auto;">
                    <ul class="list-group">
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_7" type="checkbox" id="h_7" value="1">
                            <label class="form-check-label" for="h_7">07:00-08:30</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_8" type="checkbox" id="h_8" value="1">
                            <label class="form-check-label" for="h_8">08:00-09:30</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_1030" type="checkbox" id="h_1030" value="1">
                            <label class="form-check-label" for="h_1030">10:30-12:00</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_12" type="checkbox" id="h_12" value="1">
                            <label class="form-check-label" for="h_12">12:00-13:30</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_1330" type="checkbox" id="h_1330" value="1">
                            <label class="form-check-label" for="h_1330">13:30-15:00</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_15" type="checkbox" id="h_15" value="1">
                            <label class="form-check-label" for="h_15">15:00-16:30</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_1630" type="checkbox" id="h_1630" value="1">
                            <label class="form-check-label" for="h_1630">16:30-18:00</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_18" type="checkbox" id="h_18" value="1">
                            <label class="form-check-label" for="h_18">18:00-19:30</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_1930" type="checkbox" id="h_1930" value="1">
                            <label class="form-check-label" for="h_1930">19:30-21:00</label>
                        </li>
                        <li class="list-group-item" style="background-color: #6a6e73;">
                            <input class="form-check-input me-2" name="h_21" type="checkbox" id="h_21" value="1">
                            <label class="form-check-label" for="h_21">21:00-22:30</label>
                        </li>
                    </ul>
                </div>
                <div style="height: 10px;"></div>
                <div style="text-align: center; display:flex; justify-content: center;">
                    <button type="submit" class="btn btn-outline-light" name="add_unavailable_hours_tf">Submit</button>
                </div>
            </form>
        </div>
        <br><br><br><br><br>

    </html>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>

    </html>
<?php endif; ?>
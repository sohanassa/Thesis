<?php include('process.php') ?>
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
            <a class="navbar-brand" href="index.php" style="color:blanchedalmond">
              CS Department Timetable Tool
            </a>
          </div>
        </nav>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if (isset($_SESSION["username"])) :
            ?>
              <?php if (isset($_SESSION["admin"])) :
              ?>
                <li class="nav-item">
                  <a class="nav-link" href="admin/admin.php">Admin page</a>
                </li>
              <?php else : ?>
                <li class="nav-item">
                  <a class="nav-link" href="instructor/instructor.php">Instructor page</a>
                </li>
              <?php endif; ?>
            <?php else : ?>
              <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
              </li>
            <?php endif; ?>
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
  <br>

  <div style="width: 50%; height: 100px; margin: 0 auto;">
    <h4 style="text-align: center;">This is a tool created by Sohaib Nassar for automatically generating timetables for the courses of the Department of Computer Science in the University of Cyprus</h4>
  </div>

  <div style="width: 50%; height: 100px; margin: 0 auto; text-align: center;">
    <img style="width: 600px; height: auto;" src="https://www.cs.ucy.ac.cy/~mdd/img/ucy-cs-logo.jpg" alt="Italian Trulli">
  </div>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
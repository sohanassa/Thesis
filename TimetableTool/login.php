<?php include('process.php') ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Online Shop.">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <title>Login</title>
</head>

<body style="background-color: #818589;">

  <!-- Completed -->
  <section id="Nav-Bar" style="background-color: #36454F;">
    <nav class="navbar navbar-expand-lg navbar-light ">
      <div class="container-fluid">
        <nav class="navbar navbar-light ">
          <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
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
              <a class="nav-link" href="login.php" style="color:blanchedalmond">Login</a>
            </li>
            <?php if (isset($_SESSION["username"])) :
            ?>
            <?php else : ?>

            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

<div style="width: 30%; margin: 0 auto;">
  <br>
  <h2 style="text-align: center;">Login</h2>
  <form method="post" action="login.php">
    <?php include('errors.php'); ?>
    <div class="form-group">
      <label for="username">Username</label>
      <input id="username" class="form-control" type="text" placeholder="Enter username" name="username" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input id="password" class="form-control" type="password" placeholder="Enter password" name="password" required>
    </div>
    <div style="height: 10px;"></div>
    <div style="text-align: center; display:flex; justify-content: center;">
      <button type="submit" class="btn btn-outline-light" name="login_user">Submit</button>
    </div>
  </form>
</div>

</html>
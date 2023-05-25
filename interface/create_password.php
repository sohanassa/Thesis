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

  <section id="Nav-Bar" style="background-color: #36454F;">
    <nav class="navbar navbar-expand-lg navbar-light ">
      <div class="container-fluid">
        <nav class="navbar navbar-light ">
          <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
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
              <a class="nav-link" href="create_password.php" style="color:white">Create Password</a>
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
  <div style="width: 30%; margin: 0 auto;">
  <br>
  <h2 style="text-align: center;">Welcome, set your password please</h2>
  <form method="post" action="login.php" id = "login-form" onsubmit="return validatePassword()">
    <?php include('errors.php'); ?>
    <div class="form-group">
      <label for="password1">Password</label>
      <input id="password1" class="form-control" type="password" placeholder="Enter password" name="password1" required>
    </div>
    <div class="form-group">
      <label for="password2">Enter Password again</label>
      <input id="password2" class="form-control" type="password" placeholder="Enter password" name="password2" required>
    </div>
    <div style="height: 10px;"></div>
    <div style="text-align: center; display:flex; justify-content: center;">
      <button type="submit" class="btn btn-outline-light" name="create_password">Submit</button>
    </div>
  </form>
</div>
  
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
<?php

include('unauthenticatedmaster.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = test_input($_POST["uname"]);
  $password = test_input($_POST["psw"]);

  $validLogin = $db->checkLogin($name, $password);

  if ($validLogin)
  {
  	$_SESSION['userName'] = $name;
    header("Location: index.php"); 
  }
  else
  {
  	echo 'invalide login!';
    // todo: show error messages
  }
}


?>

<form form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div class="imgcontainer">
    <img src="img/planet.jpg" alt="Avatar" class="avatar" width="200" height="200">
  </div>

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <button type="submit">Login</button>
  </div>
</form>
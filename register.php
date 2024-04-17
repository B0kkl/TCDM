<?php include('server.php') ?>
<?php
$backgroundImage = 'michael.jpg';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 
  <style>
      body {
      background-image: url('<?php echo $backgroundImage; ?>'); 
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    h1 {
      text-align: center;
      color: rgb(255, 255, 255);
      font-size: 36px;
      margin-top: 20px;
    }

    form {
      width: 60%;
      background: rgba(38, 147, 248, 0.90);
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      border-radius: 10px;
      border: 1px rgba(38, 147, 248, 0.90) solid;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      box-sizing: border-box;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #D9D10F;
      border: none;
      color: white;
      cursor: pointer;
      border-radius: 5px;
      margin-top: 10px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      width: 100%;
      margin-bottom: 15px;
    }

    .input-group label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .input-group p {
      margin-top: 10px;
      font-size: 14px;
    }

    a {
      color: #D9D10F;
      text-decoration: none; /* Remove underline */
    }

    p {
      margin-top: 10px;
      font-size: 16px;
    }

    /* Style for back arrow */
    .back-arrow {
      position: absolute;
      top: 10px;
      left: 10px;
      font-size: 24px;
      cursor: pointer;
      color: black; 
    }
    </style>
</head>
<body>
<div class="back-arrow" onclick="goToHomePage()"><i class="fas fa-arrow-left"></i></div>
 
  <div class="header">
  	<h2>Register Traffic Officer</h2>
  </div>
	

  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Officer Name</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  </form>
  <script>
  function goToHomePage() {
    window.history.back();
  }
</script>
</body>
</html>
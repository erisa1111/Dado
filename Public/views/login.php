<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../components/logIncard/login.css">
</head>

<body>

    <div class="container" id="container">
        <?php if (!empty($_GET['error'])): ?>
  <div class="error">
    <?php if ($_GET['error'] === 'not_verified'): ?>
      Please verify your email before logging in.
    <?php else: /* invalid_credentials */ ?>
      Invalid username or password.
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (!empty($_GET['verified'])): ?>
  <div class="success">
    Your email was verified! You can now log in.
  </div>
<?php endif; ?>

        <div class="form-container sign-in-container">
        <form action="/login" method="POST">
    <h1>Log in</h1>
    
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    
    <button type="submit">Log in</button>

    
                   <div style="margin-top: 15px; text-align: center; font-size: 0.9em;">
       <p>Registered already? <a href="login.php" style="color: #333; text-decoration: none;"> LogIn</a></p>
    </div>
</form>
        </div>


        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 style="color: #e2687e;">Welcome Back!</h1>
                    <p style="color: #e2687e">Continue your free experience with Dado!</p>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script src="./components/logIncard/logIn.js"></script>
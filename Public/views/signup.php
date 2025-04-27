<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../assets/css/signup_p.css">
</head>
<body>

<div class="container">
    <form method="POST" action="/signup" class="scroll-form">
        <div class="form-section" id="personal-details">
            <h2>Sign Up</h2>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="surname" placeholder="Surname" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone_number" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <!-- <div class="custom-select"> -->
                <select name="role_id" required class="select">
                    <option value="2">Parent</option>
                    <option value="3">Nanny</option>
                </select>
            <!-- </div> -->
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit">Sign Up</button>
        </div>
    </form>
</div>

</body>
</html>

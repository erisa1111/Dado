<?php
$role = isset($_GET['role']) ? $_GET['role'] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/signup_n.css">
</head>

<body>


    <div class="container">
    <form action="/signup" method="POST" class="scroll-form">
            <div class="form-section" id="personal-details">
                <h1>Sign Up</h1>
                <h3>Personal details</h3>
                <input type="text" name="name" placeholder="Name" required />
            <input type="text" name="surname" placeholder="Surname" required />
            <input type="text" name="location" placeholder="Location" required />
            <select name="gender" required >
            <option value="" disabled selected hidden >Select Gender</option>
           <option value="F">Female</option>
           <option value="M">Male</option>
</select>
            <input type="number" name="expected_salary" placeholder="Avg Wage" required />
            <input type="number" name="experience" placeholder="Experience" required />
            <select name="schedule" required>
    <option value="" disabled selected hidden >Select Schedule</option>
    <option value="Full-Time">Full-Time</option>
    <option value="Part-Time">Part-Time</option>
</select>
            <input type="hidden" name="role" value="nanny">

            </div>

            <div class="form-section" id="account-details">
                <h3>Account details</h3>
                <input type="email" name="email" placeholder="Email" required />
                <input type="text" name="username" placeholder="Username" required />
                <input type="text" name="phone_number" placeholder="Phone number" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" >Sign Up</button>
            </div>
        </form>
    </div>
</body>

</html>

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
            <input type="text" name="gender" placeholder="Gender" required />
            <input type="number" name="expected_salary" placeholder="Avg Wage" required />
            <input type="number" name="experience" placeholder="Experience" required />
            <input type="text" name="schedule" placeholder="Schedule" required />
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

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
            <?php $usernameError = (isset($_GET['error']) && $_GET['error'] == 'username'); ?>
            <div class="form-section" id="personal-details">
                <h1>Sign Up</h1>
                <h3>Personal details</h3>
                <input type="text" name="name" placeholder="Name" required />
                <input type="text" name="surname" placeholder="Surname" required />
                <select name="location" required>
                    <option value="" style="color:black;">Select City</option>
                    <option value="Prishtina" style="color:black;">Prishtina</option>
                    <option value="Prizren" style="color:black;">Prizren</option>
                    <option value="Peja" style="color:black;">Peja</option>
                    <option value="Gjakova" style="color:black;">Gjakova</option>
                    <option value="Ferizaj" style="color:black;">Ferizaj</option>
                    <option value="Gjilan" style="color:black;">Gjilan</option>
                    <option value="Mitrovica" style="color:black;">Mitrovica</option>
                    <option value="Podujeva" style="color:black;">Podujeva</option>
                    <option value="Vushtrri" style="color:black;">Vushtrri</option>
                    <option value="Rahovec" style="color:black;">Rahovec</option>
                    <option value="Suhareka" style="color:black;">Suhareka</option>
                    <option value="Lipjan" style="color:black;">Lipjan</option>
                    <option value="Malisheva" style="color:black;">Malisheva</option>
                </select>
                <select name="gender" required style="color:black;">
                    <option value="" disabled selected hidden>Select Gender</option>
                    <option value="F" style="color:black;">Female</option>
                    <option value="M" style="color:black;">Male</option>
                </select>
                <input type="text" id="expected_salary" name="expected_salary" placeholder="Avg Wage (e.g., 1000.00)" required />
                <input type="text" name="experience" placeholder="Experience (in years)" required />
                <select name="schedule" required>
                    <option value="" disabled selected hidden>Select Schedule</option>
                    <option value="Full-Time">Full-Time</option>
                    <option value="Part-Time">Part-Time</option>
                </select>
                <input type="hidden" name="role" value="nanny">
            </div>

            <div class="form-section" id="account-details">
                <h3>Account details</h3>
                <input type="email" id="email" name="email" placeholder="Email" required />
                <span id="email-feedback" style="font-size: 0.9em; color: red;"></span>
                <input type="text" id="username" name="username" placeholder="Username" required style="<?= $usernameError ? 'border-color:#d2556d;' : '' ?>" />
                <?php if ($usernameError): ?>
                    <div class="error-message" style="color: #d2556d; font-size: 0.9em;">This username is already taken!</div>
                <?php endif; ?>
                <input type="text" name="phone_number" placeholder="Phone number" required />
               <input type="password" name="password" id="password" placeholder="Password" required />
                <span id="password-feedback" style="font-size: 0.9em; color: red;"></span>
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>

    <script>
        // Salary formatting and validation
        document.getElementById('expected_salary').addEventListener('input', function (e) {
            let value = e.target.value;

            // Allow only digits, decimal point, and commas
            value = value.replace(/[^\d\.,]/g, '');

            // Replace commas with dots for decimal points
            value = value.replace(',', '.');

            // Format the value to two decimal places
            let parts = value.split('.');
            if (parts[1] && parts[1].length > 2) {
                parts[1] = parts[1].substring(0, 2);
            }
            value = parts.join('.');

            // Update the input with the formatted value
            e.target.value = value;
        });

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function (e) {
            const salaryInput = document.getElementById('expected_salary');
            const salary = salaryInput.value.trim();

            // Validate salary format (positive decimal with up to two digits)
            const salaryPattern = /^\d+(\.\d{1,2})?$/;

            if (!salaryPattern.test(salary)) {
                alert('Please enter a valid salary (e.g., 1000.00)');
                e.preventDefault();
            }
        });

        const usernameInput = document.getElementById('username');
const feedback = document.getElementById('username-feedback');

usernameInput.addEventListener('input', () => {
  const username = usernameInput.value.trim();

  if (!username) {
    feedback.textContent = '';
    return;
  }

  fetch(`/api/check_username?username=${encodeURIComponent(username)}`)
    .then(res => {
      if (!res.ok) throw new Error('Network response not ok');
      return res.json();
    })
    .then(data => {
      if (data.taken) {
        feedback.textContent = 'This username is already taken.';
        usernameInput.style.borderColor = '#d2556d'; // red
      } else {
        feedback.textContent = '';
        usernameInput.style.borderColor = '';
      }
    })
    .catch(err => {
      console.error('Error checking username:', err);
      feedback.textContent = 'Unable to check username now.';
    });
});
        // Password validation
        document.getElementById('password').addEventListener('input', function () {
            const password = this.value;
            const feedback = document.getElementById('password-feedback');
            const submitBtn = document.getElementById('submit-btn');

            let passed = 0;

            const checks = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                symbol: /[^A-Za-z0-9]/.test(password),
            };

            for (const check in checks) {
                if (checks[check]) passed++;
            }

            if (passed === 5) {
                this.style.borderColor = 'green';
                feedback.textContent = 'Strong password.';
                feedback.style.color = 'green';
                submitBtn.disabled = false;
            } else if (passed >= 3) {
                this.style.borderColor = 'orange';
                feedback.textContent = 'Password is okay but could be stronger.';
                feedback.style.color = 'orange';
                submitBtn.disabled = true;
            } else {
                this.style.borderColor = '#d2556d';
                feedback.textContent = 'Password is too weak!';
                feedback.style.color = '#d2556d';
                submitBtn.disabled = true;
            }
        });

        // Email validation
        document.querySelector('form').addEventListener('submit', function (e) {
            const emailInput = document.querySelector('input[name="email"]');
            const email = emailInput.value.trim().toLowerCase();
            const allowedDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
            const emailFeedbackId = 'email-feedback';

            // Remove any old feedback message
            let oldFeedback = document.getElementById(emailFeedbackId);
            if (oldFeedback) oldFeedback.remove();

            const atIndex = email.indexOf('@');
            const dotComIndex = email.lastIndexOf('.com');
            const domain = email.substring(atIndex + 1);

            const isValid = (
                atIndex > 0 &&
                dotComIndex === email.length - 4 &&
                allowedDomains.includes(domain)
            );

            if (!isValid) {
                const feedback = document.createElement('div');
                feedback.id = emailFeedbackId;
                feedback.textContent = 'Please enter a valid email (e.g., name@gmail.com, yahoo.com, etc.)';
                feedback.style.color = '#d2556d';
                feedback.style.fontSize = '0.9em';
                emailInput.style.borderColor = '#d2556d';
                emailInput.insertAdjacentElement('afterend', feedback);
                e.preventDefault(); // prevent form from submitting
            } else {
                emailInput.style.borderColor = 'green';
            }
        });
    </script>
</body>

</html>

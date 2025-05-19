<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../components/logIncard/login.css">
    <style>
        /* Modal styles that won't interfere with your design */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 350px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #888;
        }
        
        .modal-close:hover {
            color: #333;
        }
        
        .modal-title {
            color: #e2687e;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .modal-button {
            background-color: #e2687e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            margin-top: 15px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .modal-button:hover {
            background-color: #d2556d;
        }
    </style>
</head>
<body>

    <!-- Verification Modal -->
    <div id="verificationModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="modal-title">Email Verification Required</h3>
            <p>Please check your email and verify your account before logging in.</p>
            <button class="modal-button" id="modalOkButton">OK</button>
        </div>
    </div>

    <div class="container" id="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php 
                switch ($_GET['error']) {
                    case 'invalid_credentials':
                        echo 'Invalid username or password.';
                        break;
                    default:
                        echo 'Login failed. Please try again.';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['verified'])): ?>
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
                    <p>Don't have an account? <a href="/signup" style="color: #333; text-decoration: none;">Sign Up</a></p>
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

    <script src="./components/logIncard/logIn.js"></script>
    <script>
        // Show modal if verification is needed
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            
            if (error === 'not_verified') {
                const modal = document.getElementById('verificationModal');
                modal.style.display = 'flex';
                
                // Close modal when clicking X or OK button
                document.querySelector('.modal-close').addEventListener('click', closeModal);
                document.getElementById('modalOkButton').addEventListener('click', closeModal);
                
                // Also close when clicking outside modal content
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }
        });
        
        function closeModal() {
            const modal = document.getElementById('verificationModal');
            modal.style.display = 'none';
            
            // Clean up URL
            const url = new URL(window.location.href);
            url.searchParams.delete('error');
            window.history.replaceState({}, '', url);
        }
    </script>
</body>
</html>
<?php
session_start(); // Start session to access $_SESSION data

use App\Models\User;
require_once __DIR__ . '/../../App/Models/User.php'; // Adjust path if needed

if (!isset($_SESSION['user_id'])) {
    echo "No user logged in!";
    exit();
}

$userModel = new User();
$userData = $userModel->getProfile($_SESSION['user_id']);

if (!$userData) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/components/nav_home/nav_home.css">
</head>
<body>
    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br><br><br><br><br><br>
    <div class="content">
        <div class="left">
            <div class="profile">
           
                <div class="photo">
                   
                    <img 
                        class="profile-image" 
                        src="/assets/img/default_profile.webp" 
                        alt="Profile Image">
                    <div class="info">
                        <h3 class="name"><?= htmlspecialchars($userData['name']) . ' ' . htmlspecialchars($userData['surname']) ?></h3>
                        <p class="status"><?= htmlspecialchars($userData['username']) ?></p>
                    </div>
                </div>
        
                <div class="bio">
                    
                    <div class="bio-box">
                        <p>Hello im a nanny and i have specialized in childcare</p>
                    </div>
                </div>
               
            </div>
          
            <div class="recent">
                <div class="image-container">
                  <img src="/assets/img/event_dado.webp" alt="Event for Parents and Nannies">
                  <div class="overlay-text">
                    <h2>Join Our Parent & Nanny Event</h2>
                    <button class="register-btn">Register</button>
                  </div>
                </div>
              </div>
              
        </div>
        <div id="center">
        </div>


        <div class="right">
            <div class="recommend">
              <h2>Add to your feed</h2>
              
              <div class="recommendation">
                <div class="logo">
                  <img src="https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=" alt="Nanny 1" />
                </div>
                <div class="rec">
                  <div class="info">
                    <h3>Nanny 1</h3>
                    <p>Experienced caregiver</p>
                  </div>
                  <button class="follow-btn">+ Follow</button>
                </div>
              </div>
              
              <div class="recommendation">
                <div class="logo">
                  <img src="https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=" alt="Parent 1" />
                </div>
                <div class="rec">
                  <div class="info">
                    <h3>Parent 1</h3>
                    <p>Looking for a caring nanny</p>
                  </div>
                  <button class="follow-btn">+ Follow</button>
                </div>
              </div>
              
              <div class="recommendation">
                <div class="logo">
                  <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s" alt="Nanny 2" />
                </div>
                <div class="rec">
                  <div class="info">
                    <h3>Nanny 2</h3>
                    <p>Passionate about child development activities.</p>
                  </div>
                  <button class="follow-btn">+ Follow</button>
                </div>
              </div>
          
              <a href="#" class="view-all">View all recommendations →</a>
            </div>
            
            <div class="about">
              <img src="/assets/img/find_dado.webp" alt="">
            </div>
            
            <div class="app">
              <h6>Try Dado on your Mobile →</h6>
              <a href="#">Dado</a>
            </div>
          </div>
          
          <script src="/components/nav_home/nav_home.js"></script>
          <script src="/components/postcard/postcard.js"></script>
   
</body>
</html>


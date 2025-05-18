<?php
session_start(); // Start session to access $_SESSION data

use App\Models\User;
require_once __DIR__ . '/../../App/Models/User.php'; // Adjust path if needed
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';
$connectionsModel = new \App\Models\Connections();
$connectionsController = new App\Controllers\ConnectionsController();

if (!isset($_SESSION['user_id'])) {
    echo "No user logged in!";
    exit();
}

$loggedInUserId = $_SESSION['user_id'];
$viewingUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : $loggedInUserId;
$isOwnProfile = ($loggedInUserId === $viewingUserId);

$userModel = new User();
$userData = $userModel->getProfile($viewingUserId);

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
    <link rel="stylesheet" href="/assets/css/profile.css">
    <link rel="stylesheet" href="../components/nav_home/nav_home.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br><br>
    <main>
        <div class="container">
            <div class="profile_wrapper">
                <div class="profile_background"> </div>
                
    
                <div class="profile_details">
                    <div class="profile_image">
                            <?php
                                $profilePic = !empty($userData['profile_picture']) 
                                    ? '/' . $userData['profile_picture'] 
                                    : '/assets/img/default_profile.webp';
                            ?>
                            <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture" id="profile_pic">
                        <p><i class="fa-solid fa-location-dot"></i><?= htmlspecialchars($userData['location']) ?></p>
                    </div>
                    <div class="profile_info">
                        <div class="rating-container">
                            <span class="review-rating">★★★★★</span>
                            <span class="rating-number">5.0</span>
                        </div>
    
                        <h5 id="username"> <?= htmlspecialchars($userData['username']) ?></h5>
                        <h3 id="name" contenteditable="false" aria-label="Your full name"><?= htmlspecialchars($userData['name']) . ' ' . htmlspecialchars($userData['surname']) ?></h3>
                        <div class="profile_icons">
                            <i class="fa-brands fa-twitter"></i>
                            <i class="fa-brands fa-linkedin-in"></i>
                            <i class="fa-brands fa-facebook-f"></i>
                        </div>

                        <div class="role_experience">
                            <div class="role">
                                <!-- <p id="role_" aria-label="Current role"></p> -->
                                <p id="role_type" contenteditable="false" aria-label="Role type"><i class="fa-regular fa-user"></i> <?= htmlspecialchars($userData['role_name']) ?></p>
                            </div>
                            <div class="experience">
                                <!-- <p id="experience_" aria-label="Experience">Phone</p> -->
                                <p id="experience_years" contenteditable="false" aria-label="Years of experience"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($userData['phone_number']) ?>
                                </p>
                            </div>
                            <div class="expected_salary">
                                <!-- <p id="salary" aria-label="Expected salary">Email</p> -->
                                <p id="salary_number" contenteditable="false" aria-label="Salary"><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($userData['email']) ?> </p>
                            </div>
                        </div>

                        <div class="profile-buttons">
                            <?php if ($isOwnProfile): ?>

                                <a href="edit_profile.php" class="follow-btn" style="margin: 0; text-decoration: none; display: inline-block;">Edit Profile</a>

                            <?php else: ?>
                                <button class="follow-btn" style="margin: 0;" data-recipient-id="<?= $viewingUserId ?>">Connect</button>
                            <?php endif; ?>
                            <button class="follow-btn">Share</button>
                        </div>

                      
    
                    </div>
                </div>


    
                <div class="profile_summary">
                    <h2 id="title">My Story</h2>
                    <div class="summary">
                        <ul>
                            <li><a href="#" data-section="story">My Story</a></li>
                            <?php if ($userData['role_name'] === 'Nanny'): ?>
                            <li><a href="#" data-section="skills">Skills</a></li>
                            <li><a href="#" data-section="experience">Experience</a></li>
                            <?php endif; ?>
                            <!-- <li><a href="#" data-section="reviews" onclick="showSection('reviews')">Reviews</a></li> -->
    
                        </ul>
                        <div id="content">
                            <!-- My Story Section -->
                            <div id="story" class="section">
                                <p><?= htmlspecialchars($userData['bio']) ?></p>
                            </div>
    
                        </div>
                    </div>
                </div>
                <!-- <div class="user_posts">
                    <h2>User Posts</h2>
                    <div id="posts-container">
                       
                    </div>
                </div>  -->
                <hr>
                <h2 id="postet">Posts:</h2>
                <hr>
                <hr>
                <br>
                <div class="posts_section">
                    <div class="left-rec">
                        <div class="recommend">
                            <h2>Add to your feed</h2>
    
                            <div class="recommendation">
                                <div class="logo">
                                    <img src="https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ="
                                        alt="Nanny 1" />
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
                                    <img src="https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY="
                                        alt="Parent 1" />
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
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s"
                                        alt="Nanny 2" />
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
                    </div>
    
                    <div class="post">
                        <div class="post-header">
                            <img id="profile-img"
                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s"
                                alt="User Profile">
                            <div class="details">
                                <h4 id="username">Filane Fisteku</h4>
                                <p id="location">Time · Location</p>
                            </div>
                            <button class="title"><i class="fa-solid fa-hands-holding-child"></i></button>
                        </div>
                        <div class="post-content" id="content">
                            <p>Hi! I’m [Your Name], an experienced and caring nanny with [X] years of experience in
                                childcare. I’m passionate about providing a safe, nurturing, and fun environment for
                                children. I enjoy engaging kids in creative activities, helping with homework, and ensuring
                                they feel loved and supported. I’m available for [full-time/part-time] work and can provide
                                references upon request. Looking forward to helping your family!</p>
    
                        </div>
                        <div class="post-images" id="images">
    
                        </div>
                        <div class="post-actions">
                            <button class="act"><i class="fa-regular fa-heart"></i></button>
                            <button class="act"><i class="fa-regular fa-comment"></i></button>
                        </div>
                        <div class="post-footer">
                            <div id="likes">0 likes</div>
                            <div id="comments">0 Comments</div>
                        </div>
                        <div class="comments-list" id="comments-list">
                            <div class="comment">
                                <img class="comment-profile-img" src="../img/dado_profile.webp" alt="User Profile">
                                <div class="comment-content">
                                    <span class="comment-username">Username</span>
                                    <p class="comment-text">This is a comment text.</p>
                                </div>
                                <button class="delete-comment"></button>
                            </div>
                        </div>
                        <div class="post-comment">
    
                            <input type="text" placeholder="Add a comment..." id="comment-input">
                            <button id="submit-comment"><i class="fa-regular fa-paper-plane"></i></button>
                        </div>
    
    
                    </div>
                </div>
            </div>
            <div class="right_profile">
                <div class="right">
                    <div class="recommend">
                        <h2>Add to your feed</h2>
    
                        <div class="recommendation">
                            <div class="logo">
                                <img src="https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ="
                                    alt="Nanny 1" />
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
                                <img src="https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY="
                                    alt="Parent 1" />
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
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s"
                                    alt="Nanny 2" />
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
                        <img src="../assets/img/find_dado.webp" alt="">
                    </div>
    
                    <div class="app">
                        <h6>Try Dado on your Mobile →</h6>
                        <a href="#">Dado</a>
                    </div>
                </div>
            </div>
        </div>

    </main>
     <!-- Add this hidden element to store the current user's ID -->
    <div id="current-user-id" data-user-id="<?= $loggedInUserId ?>" style="display: none;"></div>

    <script src="../components/nav_home/nav_home.js"></script>
    <script src="../assets/js/profile.js"></script>
      <script>
document.addEventListener("DOMContentLoaded", function() {
    const connectButtons = document.querySelectorAll('.follow-btn[data-recipient-id]');
    
    connectButtons.forEach(button => {
        const recipientId = button.getAttribute('data-recipient-id');
        const senderId = document.getElementById('current-user-id').getAttribute('data-user-id');  // Make sure this is in your HTML
        // Check initial connection status on page load
        checkConnectionStatus(senderId, recipientId, button);
        
        button.addEventListener('click', async function(e) {
            console.log('Connect button clicked');
            console.log('Sender ID:', senderId);
            console.log('Recipient ID:', recipientId);
            if (senderId === recipientId) {
                alert('You cannot connect with yourself.');
                return;
            }

            // 1. Check connection status before sending
            console.log('Checking connection status...');
            await checkConnectionStatus(senderId, recipientId, button);  // Refresh status before doing anything else
            console.log('Connection status checked');
            console.log('Button innerHTML:', button.innerHTML);

            // 2. Send connection request only if status is 'none' (Not already pending or connected)
            if (button.innerHTML !== 'Connect') return;  // Avoid sending if status is 'pending' or 'connected'

            try {
                console.log('Sending connection request...');
                const response = await fetch('/handle_connection.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        user_one_id: senderId,
                        user_two_id: recipientId
                    })
                });
                const data = await response.json();
                console.log(data); 
                console.log('Response received:', data);
                console.log('Response status:', response.status);

                if (data.success) {
                    alert('Connection request sent successfully!');
                    button.disabled = true;
                    button.innerHTML = 'Request Sent';
                } else if (data.status === "pending") {
                    button.innerHTML = 'Request Sent';
                    alert('Failed to send connection request: ' + data.message);
                } else if (data.status === "connected") {
                    button.innerHTML = 'Connected';
                    alert('You are already connected with this user.');
                }
            } catch (error) {
                console.error('Error sending connection request:', error);
                alert('An error occurred while sending the connection request.');
            }
        });
    });

    // Function to check connection status
    async function checkConnectionStatus(senderId, recipientId, button) {
        try {
            console.log('Loading initial connection status...');
            const statusResponse = await fetch(`/api/connection_status.php?sender_id=${senderId}&receiver_id=${recipientId}`);
            const statusData = await statusResponse.json();
            console.log("Initial status check:", statusData);

            if (statusData.status === 'connected') {
                button.innerHTML = 'Unfollow';
                button.disabled = false;

                // Remove the previous event listener (if any) to avoid multiple listeners
                button.removeEventListener('click', handleUnfollow);

                // Add the unfollow handler
                button.addEventListener('click', handleUnfollow);

            } else if (statusData.status === 'pending') {
                button.innerHTML = 'Request Sent';
                button.disabled = true;

            } else {
                button.innerHTML = 'Connect';
                button.disabled = false;

                // Remove the unfollow event listener when switching to "Connect"
                button.removeEventListener('click', handleUnfollow);
            }
        } catch (error) {
            console.error('Error loading initial connection status:', error);
        }
    }

    // Unfollow handler
    async function handleUnfollow(e) {
        e.stopImmediatePropagation();
        const confirmUnfollow = confirm("Are you sure you want to unfollow?");
        if (!confirmUnfollow) return;

        const senderId = document.getElementById('current-user-id').getAttribute('data-user-id');
        const recipientId = e.target.getAttribute('data-recipient-id');
        
        try {
            const response = await fetch('/handle_connection.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    action: 'remove',
                    user_one_id: senderId,
                    user_two_id: recipientId
                })
            });
            const result = await response.json();
            console.log("Unfollow result:", result);
            if (result.success) {
                e.target.innerHTML = 'Connect';  // Change the button back to "Connect"
                alert('You have unfollowed the user.');
                // Remove unfollow listener after success
                e.target.removeEventListener('click', handleUnfollow);
                checkConnectionStatus(senderId, recipientId, e.target);  // Refresh status
            } else {
                alert('Failed to unfollow: ' + result.message);
            }
        } catch (error) {
            console.error('Error unfollowing:', error);
            alert('An error occurred while trying to unfollow.');
        }
    }
});


    </script>
</body>

</html>
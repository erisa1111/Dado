<?php
session_start(); // Start session to access $_SESSION data

use App\Models\User;
require_once __DIR__ . '/../../App/Models/User.php'; // Adjust path if needed
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';
require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../App/Models/JobPost.php';

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

$postModel = new \App\Models\Post();
$jobPostModel = new \App\Models\JobPost();

$posts = $postModel->getPostsByUserId($viewingUserId);
$jobPosts = $jobPostModel->getJobPostsByUserId($viewingUserId);

$allPosts = [];

// Add regular posts
foreach ($posts as $post) {
  $allPosts[] = [
    'type' => 'post',
    'data' => $post,
    'sort_date' => strtotime($post['created_at'])
  ];
}

// Add job posts
foreach ($jobPosts as $jobPost) {
  $allPosts[] = [
    'type' => 'jobpost',
    'data' => $jobPost,
    'sort_date' => strtotime($jobPost['created_at'])
  ];
}

// Sort all posts by date descending
usort($allPosts, function ($a, $b) {
  return $b['sort_date'] - $a['sort_date'];
});


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
    <link rel="stylesheet" href="/components/nav_home/nav_home.css">
    
    <!-- <link rel="stylesheet" href="/assets/css/home.css"> -->
    <link rel="stylesheet" href="/assets/css/search_results.css">
    <link rel="stylesheet" href="/components/postcard/postcard.css">
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
                           <?php if ($isOwnProfile): ?>
    <div id="connections-count" data-user-id="<?php echo $loggedInUserId; ?>">
        Connections: <a href="/views/connections.php" id="connections-link"><span id="connections-number">0</span></a>
    </div>
<?php else: ?>
    <div id="connections-count" data-user-id="<?php echo $viewingUserId; ?>">
        Connections: <span id="connections-number">0</span>
    </div>
<?php endif; ?>

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
                <!-- <div class="posts_section"> -->
    
                    <div class="posts_section_scrollable">
                        <?php foreach ($allPosts as $item): ?>
                        <?php if ($item['type'] === 'post'): $post = $item['data']; ?>
                            <div class="post" id="post-<?php echo $post['id']; ?>">
                            <div class="post-header">
                                <?php
                                $profilePic = !empty($post['profile_picture']) 
                                ? '/' . ltrim($post['profile_picture'], '/\\') 
                                : '/assets/img/dado_profile.webp';
                                ?>
                                <img class="profile-img" src="<?php echo htmlspecialchars($profilePic); ?>" alt="User Profile">
                                <div class="details">
                                <h4 class="username"><?php echo htmlspecialchars($post['username']); ?></h4>
                                <p class="location">Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
                                </div>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                                <div class="post-menu-wrapper">
                                    <button type="button" class="post-menu-toggle">⋮</button>
                                    <div class="post-act">
                                    <button type="button" class="edit-post" data-post-id="<?php echo $post['id']; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="delete-post" data-post-id="<?php echo $post['id']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>
                            <div class="post-content">
                                <?php echo htmlspecialchars($post['body']); ?>
                            </div>
                            <?php if (!empty($post['image_url'])): ?>
                                <div class="post-images">
                                <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image">
                                </div>
                            <?php endif; ?>
                            <div class="post-actions">
                                <button class="act like-btn" data-post-id="<?php echo $post['id']; ?>">
                                <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="act comment-btn" data-post-id="<?php echo $post['id']; ?>">
                                <i class="fa-regular fa-comment"></i>
                                </button>
                            </div>
                            <div class="post-footer">
                                <div class="likes"><?php echo $post['like_count']; ?> likes</div>
                                <div class="comments"><?php echo $post['comment_count']; ?> comments</div>
                            </div>


                            <div class="comments-list" id="comments-list-<?php echo $post['id']; ?>" style="display:none">
                                <div class="no-comments">No comments yet</div>
                            </div>
                            <div class="post-comment">
                                <input type="text" id="comment-<?php echo $post['id']; ?>" name="comment" placeholder="Add a comment..."
                                class="comment-input" data-post-id="<?php echo $post['id']; ?>">
                                <button id="submit-comment" data-post-id="<?php echo $post['id']; ?>">
                                <i class="fa-regular fa-paper-plane"></i>
                                </button>
                                <div id="current-user-id" data-user-id="<?php echo $_SESSION['user_id'] ?? ''; ?>"></div>
                            </div>
                            </div>
                        <?php elseif ($item['type'] === 'jobpost'):
                            $jobpost = $item['data']; ?>

                            <div class="job-post" id="job-post-<?php echo $jobpost['id']; ?>">
                            <div class="job-post-header">
                                <?php
                                $profilePic = !empty($jobpost['profile_picture']) 
                                ? '/' . ltrim($jobpost['profile_picture'], '/\\') 
                                : '/assets/img/dado_profile.webp';
                                ?>
                                <img class="profile-img" src="<?php echo htmlspecialchars($profilePic); ?>" alt="User Profile">
                                <div class="details">
                                <h4 class="username"><?php echo htmlspecialchars($jobpost['username']); ?></h4>
                                <p class="location">Posted on <?php echo date('F j, Y', strtotime($jobpost['created_at'])); ?></p>
                                </div>

                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $jobpost['parent_id']): ?>
                                <div class="post-menu-wrapper">
                                    <button type="button" class="post-menu-toggle">⋮</button>
                                    <div class="post-act">
                                    <button type="button" class="edit-job-post" data-post-id="<?php echo $jobpost['id']; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="delete-job-post" data-post-id="<?php echo $jobpost['id']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="job-post-content">
                                <div class="job-header">
                                <h3 class="job-title"><?php echo htmlspecialchars($jobpost['title']); ?></h3>
                                <div class="job-meta">
                                    <div class="m">
                                    <span class="job-type"><?php echo htmlspecialchars($jobpost['schedule']); ?></span> |
                                    <span class="job-location"><?php echo htmlspecialchars($jobpost['location']); ?></span> |
                                    <span class="job-salary"><?php echo '$' . number_format($jobpost['salary'], 2); ?></span></div>
                                    <div class="kids"> <p id="kids_num"><?php echo (int) $jobpost['num_kids']; ?></p> <strong>Kids</strong></div>
                                </div>
                                </div>
                                <div class="job_desc_content">

                                <div class="job-description">
                                <p><?php echo nl2br(htmlspecialchars($jobpost['description'])); ?></p>
                                </div>

                                <div class="job-details-grid">
                                
                                <div class="time_date">

                                <div><i class="fa-regular fa-clock"></i>
                                    <?php echo isset($jobpost['start_hour']) ? htmlspecialchars($jobpost['start_hour']) : 'Not set'; ?>
                                    <span> - </span>
                                    <?php echo isset($jobpost['end_hour']) ? htmlspecialchars($jobpost['end_hour']) : 'Not set'; ?></div>

                                
                            
                                <div><i class="fa-regular fa-calendar"></i>
                                    <?php echo isset($jobpost['date_from']) ? htmlspecialchars($jobpost['date_from']) : 'Not set'; ?>
                                                        <span> - </span>

                                    <?php echo isset($jobpost['date_to']) ? htmlspecialchars($jobpost['date_to']) : 'Not set'; ?>
                                </div>
                                </div>
                                </div>
                                </div>

                                <?php if ($_SESSION['user_id'] != $jobpost['parent_id']) : ?>
                                <div class="job-post-actions">
                                    <form method="POST" action="" class="apply-form">
                                    <input type="hidden" name="job_id" value="<?php echo $jobpost['id']; ?>" />
                                    <button type="submit" class="apply-btn">Apply</button>
                                    </form>
                                </div>
                                <?php endif; ?>

                            </div>
                            <div class="job-post-actions2">
                                <button class="act job-like-btn" data-post-id="<?php echo $jobpost['id']; ?>">
                                <i class="fa-regular fa-heart"></i>
                                </button>
                                <button class="act job-comment-btn" data-post-id="<?php echo $jobpost['id']; ?>">
                                <i class="fa-regular fa-comment"></i>
                                </button>
                            </div>
                            <div class="job-post-footer">
                            <div class="job-likes"><?php echo isset($jobpost['job_like_count']) ? $jobpost['job_like_count'] : 0; ?> likes</div>
                            <div class="job-comments"><?php echo isset($jobpost['job_comment_count']) ? $jobpost['job_comment_count'] : 0; ?> comments</div>
                            </div>


                            <div class="job-comments-list" id="job-comments-list-<?php echo $jobpost['id']; ?>" style="display:none">
                                <div class="job-no-comments">No comments yet</div>
                            </div>
                            <div class="job-post-comment">
                                <input type="text" id="job-comment-<?php echo $jobpost['id']; ?>" name="comment" placeholder="Add a comment..."
                                class="job-comment-input" data-post-id="<?php echo $jobpost['id']; ?>">
                                <button id="job-submit-comment" data-post-id="<?php echo $jobpost['id']; ?>">
                                <i class="fa-regular fa-paper-plane"></i>
                                </button>

                                <div id="current-user-id-job" data-user-id="<?php echo $_SESSION['user_id'] ?? ''; ?>"></div>
                            </div>
                            </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        
                    </div>

                <!-- </div> -->
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
           <!-- Edit Post Modal -->
      <div id="editModal" class="modal2" style="display:none;">
        <div class="modal2-content">
          <span class="close" id="editClose">&times;</span>
          <h3>Edit Post</h3>
          <textarea id="editContent"></textarea>
          <input type="file" id="editImage">
          <button id="saveEdit">Save Changes</button>
        </div>
      </div>

      <!-- Delete Post Modal -->
      <div id="deleteModal" class="modal2" style="display:none;">
        <div class="modal2-content">
          <span class="close" id="deleteClose">&times;</span>
          <h3>Are you sure you want to delete this post?</h3>
          <button id="confirmDelete">Yes, Delete</button>
          <button id="cancelDelete">Cancel</button>
        </div>
      </div>
    </div>


    <script src="../components/nav_home/nav_home.js"></script>
    <script src="/assets/js/profile.js"></script>
  
    
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
    const connectionsCountDiv = document.getElementById('connections-count');
    if (connectionsCountDiv) {
        const profileUserId = connectionsCountDiv.getAttribute('data-user-id');
        if (profileUserId) {
            loadConnectionCount(profileUserId);
        }
    }

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
   async function loadConnectionCount(userId) {
    try {
        console.log(`Fetching connection count for user ID: ${userId}`);
        const response = await fetch(`/api/connection_count.php?user_id=${userId}`);
        console.log('Raw response object:', response);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        console.log('Parsed JSON data:', data);
        
        const countElem = document.getElementById('connections-number');
        if (countElem) {
            console.log('Setting connection count in DOM:', data.connection_count ?? 0);
            countElem.textContent = data.connection_count ?? 0;
        } else {
            console.warn('Element with id "connections-number" not found');
        }
    } catch (error) {
        console.error('Failed to load connection count:', error);
    }
}
});


    </script>
</body>

</html>
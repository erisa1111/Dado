<?php if (isset($userData)) : ?>
    <h1><?= htmlspecialchars($userData['name']) . ' ' . htmlspecialchars($userData['surname']) ?></h1>
    <p>Username: <?= htmlspecialchars($userData['username']) ?></p>
    <p>Email: <?= htmlspecialchars($userData['email']) ?></p>
    <p>Phone Number: <?= htmlspecialchars($userData['phone_number']) ?></p>
    <p>Location: <?= htmlspecialchars($userData['location']) ?></p>
    <p>Gender: <?= htmlspecialchars($userData['gender']) ?></p>
    <?php if (!empty($userData['profile_picture'])) : ?>
        <img src="path_to_images/<?= htmlspecialchars($userData['profile_picture']) ?>" alt="Profile Picture" width="150">
    <?php endif; ?>
<?php else : ?>
    <p>User not found.</p>
<?php endif; ?>



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
                    <p id="edit_profile">Edit</p>
                    <div class="profile_image">
                        <img src="../assets/img/main_img.png" alt="Profile Picture" id="profile_pic">
                        <div class="schedule"></i>Part time</div>
                        <p><i class="fa-solid fa-location-dot"></i>Prishtine, Kosove</p>
    
                    </div>
                    <div class="profile_info">
                        <div class="rating-container">
                            <span class="review-rating">★★★★★</span>
                            <span class="rating-number">5.0</span>
                        </div>
    
                        <h3 id="name" contenteditable="false" aria-label="Your full name">Filan Fisteku</h3>
                        <div class="profile_icons">
                            <i class="fa-brands fa-twitter"></i>
                            <i class="fa-brands fa-linkedin-in"></i>
                            <i class="fa-brands fa-facebook-f"></i>
                        </div>
                        <div class="role_experience">
                            <div class="role">
                                <p id="role_" aria-label="Current role">Role</p>
                                <p id="role_type" contenteditable="false" aria-label="Role type">Nanny</p>
                            </div>
                            <div class="experience">
                                <p id="experience_" aria-label="Experience">Experience</p>
                                <p id="experience_years" contenteditable="false" aria-label="Years of experience">10 years
                                </p>
                            </div>
                            <div class="expected_salary">
                                <p id="salary" aria-label="Expected salary">Expected salary</p>
                                <p id="salary_number" contenteditable="false" aria-label="Salary">800$ </p>
                            </div>
                        </div>
                      
    
                    </div>
                </div>
    
                <div class="profile_summary">
                    <h2 id="title">My Story</h2>
                    <div class="summary">
                        <ul>
                            <li><a href="#" data-section="story">My Story</a></li>
                            <li><a href="#" data-section="skills">Skills</a></li>
                            <li><a href="#" data-section="experience">Experience</a></li>
                            <li><a href="#" data-section="reviews" onclick="showSection('reviews')">Reviews</a></li>
    
                        </ul>
                        <div id="content">
                            <!-- My Story Section -->
                            <div id="story" class="section">
                                <p>Hi, my name is <span aria-label="Your Name">[Your Name]</span>, and caring for children
                                    has always been a part of my life...</p>
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
    <script src="../components/nav_home/nav_home.js"></script>
    <script src="../assets/js/profile.js"></script>
</body>

</html>
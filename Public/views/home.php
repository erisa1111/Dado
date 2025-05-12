<?php

require_once '/Users/macair/Desktop/dadodado/App/Models/Post.php';
require_once '/Users/macair/Desktop/dadodado/App/Controllers/PostsController.php';
require_once '/Users/macair/Desktop/dadodado/Config/Database.php';

// Initialize the controller
$postController = new App\Controllers\PostsController(); // No arguments for the constructor now
$posts = $postController->getPosts();




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/home.css">

  <link rel="stylesheet" href="/components/postcard/postcard.css">
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

          <img class="profile-image"
            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYXz402I39yGoxw90IrFr9w0vuQnuVSkgPCg&s"
            alt="Profile Image">
          <div class="info">
            <h3 class="name">Filan Fisteku</h3>
            <p class="status">Status</p>
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
      <div class="add_post">
        <p>Add post..</p>
        <div class="add_buttons">
          <button id="add"><i class="fa-regular fa-square-plus"></i></button>
       
   
          <button id="add_job"><i class="fa-solid fa-briefcase"></i></button>


        </div>

      </div>

      <div id="post-modal" class="modal" style="display: none;">

        <div class="modal-content">
          <button id="close-modal" class="close-modal"><i class="fa-solid fa-xmark"></i></button>
          <h2>Create Your Post</h2>
          <form id="post-form">
            <label for="post-content">Post Content</label>
            <textarea id="post-content" placeholder="Write something..."></textarea>
            <br>
            <label for="post-images" class="add_image"><i class="fa-solid fa-image"></i></label>
            <input type="file" id="post-images" accept="image/*" multiple />

            <div id="image-preview"></div>
            <button type="submit">Submit Post</button>
          </form>

        </div>
      </div>
      <div id="jobpost-modal" class="modal" style="display: none;">
  <div class="modal-content">
    <button id="close-jobpost-modal" class="close-modal"><i class="fa-solid fa-xmark"></i></button>
    <h2>Create Job Post</h2>
    <form id="jobpost-form">
      <label for="job-title">Job Title</label>
      <input type="text" id="job-title" placeholder="Enter job title" required />

      <label for="job-description">Description</label>
      <textarea id="job-description" placeholder="Describe the job..." required></textarea>

      <label for="job-location">Location</label>
      <input type="text" id="job-location" placeholder="Enter location" required />

      <div class="two-cols">
    <div class="form-group salary-wrapper">
        <label for="salary">Salary</label>
        <input type="number" id="salary" placeholder="Salary...">
        <span class="currency-symbol">€</span>
    </div>
    <div class="form-group">
        <label for="schedule">Schedule</label>
        <input type="text" id="schedule" placeholder="e.g. Full-time, Part-time">
    </div>
</div>


      <label for="job-num-kids">Number of Kids</label>
      <input type="number" id="job-num-kids" placeholder="Enter number of kids" min="0" />

      <button type="submit">Submit</button>
    </form>
  </div>
</div>


      <?php foreach ($posts as $post): ?>
        <div class="post" id="post-<?php echo $post['id']; ?>">
          <div class="post-header">
            <img class="profile-img"
              src="<?php echo htmlspecialchars($post['profile_picture'] ?? '/assets/img/dado_profile.webp'); ?>"
              alt="User Profile">
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
      <?php endforeach; ?>

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





    <div class="right">
      <div class="recommend">
        <h2>Add to your feed</h2>

        <div class="recommendation">
          <div class="logo">
            <img
              src="https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ="
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
            <img
              src="https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY="
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
        <img src="/assets/img/find_dado.webp" alt="">
      </div>

      <div class="app">
        <h6>Try Dado on your Mobile →</h6>
        <a href="#">Dado</a>
      </div>
    </div>

    <script src="/components/nav_home/nav_home.js"></script>
    <script src="/components/postcard/postcard.js"></script>
    <script src="/assets/js/home.js"></script>




</body>



</html>


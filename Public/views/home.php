<?php
session_start();


use App\Models\User;

require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../App/Controllers/PostsController.php';
require_once __DIR__ . '/../../App/Controllers/JobPostController.php';
require_once __DIR__ . '/../../Config/Database.php';



// Initialize the controller
$postController = new App\Controllers\PostsController(); // No arguments for the constructor now
$posts = $postController->getPosts();

$jobPostController = new App\Controllers\JobPostController(); // No arguments for the constructor now
$jobPosts = $jobPostController->getJobPosts();



require_once __DIR__ . '/../../App/Models/User.php'; // Adjust path if needed
if (!isset($_SESSION['user_id'])) {
  echo "No user logged in!";
  header('Location: login.php');
  exit();
}
$userModel = new User();
$userData = $userModel->getProfile($_SESSION['user_id']);
if (!$userData) {
  echo "User not found.";
  exit();
}
$allPosts = [];

// Add regular posts with type identifier
foreach ($posts as $post) {
  $allPosts[] = [
    'type' => 'post',
    'data' => $post,
    'sort_date' => strtotime($post['created_at'])
  ];
}

// Add job posts with type identifier
foreach ($jobPosts as $jobPost) {
  $allPosts[] = [
    'type' => 'jobpost',
    'data' => $jobPost,
    'sort_date' => strtotime($jobPost['created_at'])
  ];
}

// Sort all posts by date (newest first)
usort($allPosts, function ($a, $b) {
  return $b['sort_date'] - $a['sort_date'];
});




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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


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


          <?php if ($_SESSION['role_id'] == 0): ?>
            <button id="add_job"><i class="fa-solid fa-briefcase"></i></i></button>
          <?php endif; ?>


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
            <button type="submit">Submit</button>
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
            <div class="two-cols">
              <div class="form-group job-type-wrapper">
                <label>Job Type</label>
                <div class="job-type-buttons">
                  <input type="radio" id="part-time" name="job-type" value="Part-Time" checked>
                  <label for="part-time">Part-Time</label>

                  <input type="radio" id="full-time" name="job-type" value="Full-Time">
                  <label for="full-time">Full-Time</label>
                </div>
              </div>
              <div class="loc">
                <label for="job-location">Location</label>
                <select id="job-location" required>
                  <option value="">Select City</option>
                  <option value="Prishtinë">Prishtinë</option>
                  <option value="Gjilan">Gjilan</option>
                  <option value="Ferizaj">Ferizaj</option>
                  <option value="Mitrovicë">Mitrovicë</option>
                  <option value="Pejë">Pejë</option>
                  <option value="Prizren">Prizren</option>
                  <option value="Gjakovë">Gjakovë</option>
                  <option value="Vushtrri">Vushtrri</option>
                  <option value="Podujevë">Podujevë</option>
                  <option value="Kamenicë">Kamenicë</option>
                  <option value="Viti">Viti</option>
                  <option value="Malishevë">Malishevë</option>
                  <option value="Suharekë">Suharekë</option>
                  <option value="Rahovec">Rahovec</option>
                  <option value="Deçan">Deçan</option>
                  <option value="Istog">Istog</option>
                  <option value="Skenderaj">Skenderaj</option>
                  <option value="Dragash">Dragash</option>
                  <option value="Klinë">Klinë</option>
                  <option value="Kaçanik">Kaçanik</option>
                  <option value="Lipjan">Lipjan</option>
                  <option value="Obiliq">Obiliq</option>
                  <option value="Fushë Kosovë">Fushë Kosovë</option>
                  <option value="Shtime">Shtime</option>
                  <option value="Shtërpcë">Shtërpcë</option>
                  <option value="Leposaviq">Leposaviq</option>
                  <option value="Zubin Potok">Zubin Potok</option>
                  <option value="Zvečan">Zvečan</option>
                  <option value="Graçanicë">Graçanicë</option>
                  <option value="Ranillug">Ranillug</option>
                  <option value="Kllokot">Kllokot</option>
                  <option value="Novobërdë">Novobërdë</option>
                  <option value="Parteš">Parteš</option>
                  <option value="Mitrovicë e Jugut">Mitrovicë e Jugut</option>
                  <option value="Mitrovicë e Veriut">Mitrovicë e Veriut</option>
                </select>
              </div>
            </div>


            <label for="job-description">Description</label>
            <textarea id="job-description" placeholder="Describe the job..." required></textarea>



            <div class="two-cols">
              <div class="form-group salary-wrapper">
                <label for="salary">Salary </label>
                <div class="input-group">

                  <input type="text" id="salary" placeholder="e.g. 5000" inputmode="numeric" required
                    oninput="formatSalary(this)" onblur="finalizeSalary(this)">
                </div>
                <small class="helper-text">Format: 9,999.00 (2 decimal places)</small>
              </div>


              <div class="form-group">
                <label for="job-num-kids">Number of Kids</label>
                <input type="number" id="job-num-kids" placeholder="e.g. 2" min="0" required />
              </div>
            </div>



            <div class="two-cols">
              <div class="form-group">
                <label for="start-hour">Start Hour</label>
                <input type="text" id="start-hour" required readonly style="cursor:pointer;">
              </div>
              <div class="form-group">
                <label for="end-hour">End Hour</label>
                <input type="text" id="end-hour" required readonly style="cursor:pointer;">
              </div>
            </div>



            <div class="form-group">
              <label for="date-range">Select Date Range</label>
              <input type="text" id="date-range" placeholder="Select date range">
            </div>



            <button type="submit">Submit</button>
          </form>
        </div>
      </div>

      <div class="feed-container">
        <?php foreach ($allPosts as $item): ?>
          <?php if ($item['type'] === 'post'): $post = $item['data']; ?>
            <div class="post" id="post-<?php echo $post['id']; ?>">
              <div class="post-header">
                <img class="profile-img"
                  src="<?php echo file_exists($post['profile_picture']) ? htmlspecialchars($post['profile_picture']) : '/assets/img/dado_profile.webp'; ?>"
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
          <?php elseif ($item['type'] === 'jobpost'):
            $jobpost = $item['data']; ?>

            <div class="job-post" id="job-post-<?php echo $jobpost['id']; ?>">
              <div class="job-post-header">
                <img class="profile-img"
                  src="<?php echo file_exists($jobpost['profile_picture']) ? htmlspecialchars($jobpost['profile_picture']) : '/assets/img/dado_profile.webp'; ?>"
                  alt="User Profile">
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
    <form method="POST" action="apply.php" class="apply-form">
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
      flatpickr("#date-range", {
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function (selectedDates, dateStr, instance) {
          if (selectedDates.length === 2) {
            // Perfect, user selected a full range, do nothing
          } else if (selectedDates.length === 1) {
            // User started a new selection, clear any previously selected range
            instance.clear();
            instance.setDate(selectedDates[0]);
          }
        }
      });
      flatpickr("#start-hour", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 15,
        clickOpens: true,
      });

      flatpickr("#end-hour", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 15,
        clickOpens: true,
      });
      function formatSalary(input) {
        // Remove all formatting to get raw numbers
        let value = input.value.replace(/[^\d]/g, '');

        // If empty, return empty
        if (!value) return '';

        // Convert to number and format with thousand separators
        let num = parseInt(value, 10);
        input.value = num.toLocaleString('en-US');
      }

      function finalizeSalary(input) {
        // Add .00 if no decimals exist
        if (input.value && !input.value.includes('.')) {
          input.value += '.00';
        }
        // Ensure exactly 2 decimal places
        else if (input.value.includes('.')) {
          let parts = input.value.split('.');
          parts[1] = parts[1].padEnd(2, '0').substring(0, 2);
          input.value = parts.join('.');
        }
      }



    </script>
    </script>




</body>



</html>
<?php
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "User not logged in.";
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    require_once __DIR__ . '/../../App/Controllers/NotificationsController.php';
    $controller = new \App\Controllers\NotificationsController();
    $controller->fetch();
    exit;
}

require_once __DIR__ . '/../../App/Models/Notification.php';
require_once __DIR__ . '/../../App/Controllers/NotificationsController.php';

// Initialize the Notifications model
$notificationsModel = new \App\Models\Notifications();
$jobComments = $notificationsModel->getJobPostCommentsNotifications($user_id);
$jobLikes = $notificationsModel->getJobPostLikesNotifications($user_id);
$jobApplications = $notificationsModel->getJobPostApplicationsNotifications($user_id);
// Get all notifications for the logged in user
$allNotifications = $notificationsModel->getAllNotifications($user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notifications</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="/assets/css/notifications.css" />
  <link rel="stylesheet" href="../components/nav_home/nav_home.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
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

#modal-message {
    color: #e2687e;
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}
.btn1 {
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
    border: none;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}
.btn1-confirm {
     background-color: #e2687e;
    color: white;
}
.btn1-confirm:hover {
    background-color: #d2556d;
}

.btn1-cancel {
   background-color: #ccc;
    color: #333;
}
.btn1-cancel:hover {
    background-color: #aaa;
}
.notification-user-link {
    color: inherit;
    text-decoration: none;
}

.notification-user-link:hover {
    text-decoration: underline;
}

.notification-profile-link {
    display: inline-block;
    /* border-radius: 50%; */
    overflow: hidden;
}

/* .notification-profile-pic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.2s;
} */
 .notification-card.empty-notifications {
  padding: 20px;
  text-align: center;
  color: #666;
  font-style: italic;
  background-color: #fafafa;
  border-radius: 12px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  margin: 10px 0;
}

.notification-profile-link:hover .notification-profile-pic {
    transform: scale(1.05);
}
.empty-notifications-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.empty-notifications-container {
     background: #f8f9fa;;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    width: 100%;
    box-shadow: 0 2px 8px rgba(161, 157, 157, 0.05);
}

.empty-notifications .empty-icon {
    font-size: 40px;
    color: #e2687e;
    margin-bottom: 10px;
}

.empty-notifications h3 {
    color: #333;
    margin-bottom: 10px;
}

.empty-notifications p {
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}

.explore-btn {
    padding: 8px 16px;
    background-color: #e2687e;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}
.explore-btn i {
    margin-right: 6px;
}

</style>
</head>
<body>
  <header>
    <div id="nav-placeholder"></div>
  </header>

  <br><br><br><br><br><br><br><br>
  
  <div class="content">
    <div class="left">
      <?php include __DIR__ . '/../components/profile_card/profile_card.php'; ?>

      <div class="recent">
        <div class="image-container">
          <img src="../assets/img/event_dado.webp" alt="Event for Parents and Nannies" />
          <div class="overlay-text">
            <h2>Join Our Parent & Nanny Event</h2>
            <button class="register-btn">Register</button>
          </div>
        </div>
      </div>
    </div>

    <div id="center">
      <?php if (empty($allNotifications)): ?>
    <!-- 🟣 Place your empty notification card here -->
     <div class="empty-notifications-wrapper">
    <div class="empty-notifications-container">
        <div class="empty-notifications">
            <div class="empty-icon">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3>No notifications yet</h3>
            <p>You're all caught up! Check back later for updates.</p>
            <!-- <button class="explore-btn" onclick="window.location.href='search_results.php'">
                <i class="fas fa-users"></i> Explore users
            </button> -->
        </div>
    </div>
</div>
<?php else: ?>
    <!-- Render the notification cards here -->

     <div id="notifications-container"></div>
     <?php endif; ?>
    </div> 

    <div class="right">
      <?php
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $suggestedUsers = $userModel->getSuggestedUsers($userId);
            include '../components/recommend/recommendations.php';
        }
      ?>

      <div class="about">
        <img src="../assets/img/find_dado.webp" alt="" />
      </div>

      <div class="app">
        <h6>Try Dado on your Mobile →</h6>
        <a href="#">Dado</a>
      </div>
    </div>
  </div>
  <div id="confirmation-modal" class="modal" style="display:none;">
  <div class="modal-content">
    <p id="modal-message">Are you sure?</p>
    <div class="modal-buttons">
      <button id="confirm-button" class="btn1 btn1-confirm">Yes</button>
      <button id="cancel-button" class="btn1 btn1-cancel">No</button>
    </div>
  </div>
</div>

  <script src="../components/nav_home/nav_home.js"></script>
 <script>
  const fetchUrl = window.location.pathname + '?action=fetch';

fetch(fetchUrl, { credentials: 'include' })
  .then(res => res.json())
  .then(data => {

    const container = document.getElementById('notifications-container');
container.innerHTML = ''; // Clear previous

if (data.notifications.length === 0) {
  // Show empty message card
  const emptyCard = document.createElement('div');
  emptyCard.className = 'notification-card empty-notifications';
  emptyCard.textContent = "There aren't any notifications to show yet!";
  container.appendChild(emptyCard);
} else {
  data.notifications.forEach(notification => {
    // ... your existing notification card creation code
  });
}
    if (data.success) {
      const container = document.getElementById('notifications-container');
      container.innerHTML = ''; // Clear previous

      data.notifications.forEach(notification => {
        const card = document.createElement('div');
        card.className = 'notification-card';

        let iconHTML = '';
        let message = '';
        let profilePicture = '/assets/default-profile.png';
        let commentPreview = '';
        let buttonsHTML = '';
        let userId = '';

        function getProfilePicturePath(rawPath) {
          return rawPath && typeof rawPath === 'string'
            ? '/' + rawPath.replace(/^[\/\\]+/, '')
            : '/assets/img/dado_profile.webp';
        }

        switch (notification.type) {
          case 'comment':
            iconHTML = `<i class="fa-regular fa-comment notification-icon"></i>`;
            profilePicture = getProfilePicturePath(notification.commenter_profile_picture);
            userId = notification.commenter_id;
            message = `<a href="profile.php?user_id=${userId}" class="notification-user-link">
                        <strong>${notification.commenter_name} ${notification.commenter_surname}</strong>
                        <br>
                      </a> commented on your post.`;
            commentPreview = `<div class="notification-preview">
                                <p class="notification-preview-text">${notification.comment}</p>
                              </div>`;
            break;

          case 'like':
            iconHTML = `<i class="fa-regular fa-heart notification-icon"></i>`;
            profilePicture = getProfilePicturePath(notification.liker_profile_picture);
            userId = notification.liker_id;
            message = `<a href="profile.php?user_id=${userId}" class="notification-user-link">
                        <strong>${notification.liker_name} ${notification.liker_surname}</strong>
                        <br>
                      </a> liked your post.`;
            break;

          case 'job_comment':
            iconHTML = `<i class="fa-solid fa-briefcase notification-icon"></i>`;
            profilePicture = getProfilePicturePath(notification.commenter_profile_picture);
            userId = notification.commenter_id;
            message = `<a href="profile.php?user_id=${userId}" class="notification-user-link">
                        <strong>${notification.commenter_name} ${notification.commenter_surname}</strong>
                        <br>
                      </a> commented on your job post.`;
            commentPreview = `<div class="notification-preview">
                                <p class="notification-preview-text">${notification.comment}</p>
                              </div>`;
            break;

          case 'job_like':
            iconHTML = `<i class="fa-solid fa-briefcase notification-icon"></i>`;
            profilePicture = getProfilePicturePath(notification.liker_profile_picture);
            userId = notification.liker_id;
            message = `<a href="profile.php?user_id=${userId}" class="notification-user-link">
                        <strong>${notification.liker_name} ${notification.liker_surname}</strong>
                        <br>
                      </a> liked your job post.`;
            break;

          case 'job_application':
            iconHTML = `<i class="fa-solid fa-file-alt notification-icon"></i>`;
            profilePicture = getProfilePicturePath(notification.applicant_profile_picture);
            userId = notification.applicant_id;
            message = `<a href="profile.php?user_id=${userId}" class="notification-user-link">
                        <strong>${notification.applicant_name} ${notification.applicant_surname}</strong>
                        <br>
                      </a> applied for your job post.`;
            buttonsHTML = `
              <div class="notification-actions">
                <button class="btn accept-btn" data-application-id="${notification.application_id}">Accept</button>
                <button class="btn decline-btn" data-application-id="${notification.application_id}">Decline</button>
              </div>
            `;
            break;

 case 'application_acceptance':
            iconHTML = `<i class="fa-solid fa-check-circle notification-icon" style="color: green;"></i>`;
            profilePicture = getProfilePicturePath(notification.parent_profile_picture);
            userId = notification.parent_id;
            const jobTitle = notification.job_title || 'a job';
            const parentName = notification.parent_name || 'the parent';
            const parentSurname = notification.parent_surname || '';
            message = `<strong style="color:green;">Congrats!</strong> <strong>Your application</strong> for 
                        <strong>"${jobTitle}"</strong> has been <strong>accepted</strong> by 
                        <a href="profile.php?user_id=${userId}" class="notification-user-link">
                          <strong>${parentName} ${parentSurname}</strong>
                        </a>!`;

            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
              if (!e.target.closest('a')) {
                window.location.href = `profile.php?user_id=${userId}`;
              }
            });
            break;
          default:
            message = 'Unknown notification type.';
        }

        // Make the profile picture clickable too
        const profilePicLink = `<a href="profile.php?user_id=${userId}" class="notification-profile-link">
                              <img src="${profilePicture}" alt="Profile" class="notification-profile-pic" />
                            </a>`;
card.innerHTML = `
  <div class="notification-card-flex">
    <a href="profile.php?user_id=${userId}" class="notification-profile-link">
      <img src="${profilePicture}" alt="User profile" class="notification-profile-pic" onerror="this.onerror=null;this.src='/assets/img/default_profile.webp';" />
    </a>
    <div class="notification-body">
      <div class="notification-top">
        ${iconHTML}
        <p class="notification-message">${message}</p>
      </div>
      <span class="notification-time">${new Date(notification.created_at).toLocaleString()}</span>
      ${commentPreview}
    </div>
    ${buttonsHTML}   <!-- moved outside .notification-body -->
  </div>
`;


        container.appendChild(card);

        // Event listeners for buttons
        const acceptBtn = card.querySelector('.accept-btn');
        if (acceptBtn) {
          acceptBtn.addEventListener('click', () => {
            const applicationId = acceptBtn.getAttribute('data-application-id');
            if (!applicationId) return;
            
            showConfirmationModal("Are you sure you want to accept this application? This will be added into Your Jobs Contracts!", () => {
              fetch('/acceptApplication.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                credentials: 'include',
                body: new URLSearchParams({ application_id: applicationId })
              })
              .then(res => res.json())
              .then(data => {
                if (data.success) {
                 // alert('Application accepted!');
                  card.remove();
                } else {
                  alert('Failed to accept application: ' + data.message);
                }
              })
              .catch(err => {
                alert('Error accepting application');
                console.error(err);
              });
            });
          });
        }

        const declineBtn = card.querySelector('.decline-btn');
        if (declineBtn) {
          declineBtn.addEventListener('click', () => {
            const applicationId = declineBtn.getAttribute('data-application-id');
            if (!applicationId) return;

            showConfirmationModal("Are you sure you want to decline this application?", () => {
              fetch('/declineApplication.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                credentials: 'include',
                body: new URLSearchParams({ application_id: applicationId })
              })
              .then(res => res.json())
              .then(data => {
                if (data.success) {
                  alert('Application declined!');
                  card.remove();
                } else {
                  alert('Failed to decline application: ' + data.message);
                }
              })
              .catch(err => {
                alert('Error declining application');
                console.error(err);
              });
            });
          });
        }
      });
    } else {
      console.error("Failed to fetch notifications:", data.message);
    }
  })
  .catch(err => {
    console.error("Fetch error:", err);
  });

function showConfirmationModal(message, onConfirm) {
  const modal = document.getElementById('confirmation-modal');
  const messageEl = document.getElementById('modal-message');
  const confirmBtn = document.getElementById('confirm-button');
  const cancelBtn = document.getElementById('cancel-button');

  messageEl.textContent = message;
  modal.style.display = 'flex';

  const cleanup = () => {
    modal.style.display = 'none';
    confirmBtn.removeEventListener('click', handleConfirm);
    cancelBtn.removeEventListener('click', handleCancel);
  };

  const handleConfirm = () => {
    cleanup();
    onConfirm();
  };

  const handleCancel = () => {
    cleanup();
  };

  confirmBtn.addEventListener('click', handleConfirm);
  cancelBtn.addEventListener('click', handleCancel);
}
</script>
</body>
</html>
<!-- <script src="../components/notifications_card/notifications_card.js"></script> -->
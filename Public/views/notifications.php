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
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 12px;
  width: 300px;
  text-align: center;
}
.modal-buttons {
  margin-top: 15px;
  display: flex;
  justify-content: space-around;
}
.btn-confirm {
  background-color: #28a745;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.btn-cancel {
  background-color: #dc3545;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
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
     <div id="notifications-container"></div>
    </div> 

    <div class="right">
      <div class="recommend">
        <h2>Add to your feed</h2>

        <!-- Recommendations ... -->
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
      <button id="confirm-button" class="btn btn-confirm">Yes</button>
      <button id="cancel-button" class="btn btn-cancel">Cancel</button>
    </div>
  </div>
</div>

  <script src="../components/nav_home/nav_home.js"></script>
 <script>
  const fetchUrl = window.location.pathname + '?action=fetch';

fetch(fetchUrl, { credentials: 'include' })
  .then(res => res.json())
  .then(data => {
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
  let buttonsHTML = '';  // Declare here

  switch (notification.type) {
    case 'comment':
      iconHTML = `<i class="fa-regular fa-comment notification-icon"></i>`;
      profilePicture = notification.commenter_profile_picture;
      message = `<strong>${notification.commenter_name} ${notification.commenter_surname}</strong> commented on your post.`;
      commentPreview = `<div class="notification-preview">
                          <p class="notification-preview-text">${notification.comment}</p>
                        </div>`;
      break;

    case 'like':
      iconHTML = `<i class="fa-regular fa-heart notification-icon"></i>`;
      profilePicture = notification.liker_profile_picture;
      message = `<strong>${notification.liker_name} ${notification.liker_surname}</strong> liked your post.`;
      break;

    case 'job_comment':
      iconHTML = `<i class="fa-solid fa-briefcase notification-icon"></i>`;
      message = `<strong>${notification.commenter_name} ${notification.commenter_surname}</strong> commented on your job post.`;
      profilePicture = notification.commenter_profile_picture || '/assets/default-profile.png';
      commentPreview = `<div class="notification-preview">
                          <p class="notification-preview-text">${notification.comment}</p>
                        </div>`;
      break;

    case 'job_like':
      iconHTML = `<i class="fa-solid fa-briefcase notification-icon"></i>`;
      message = `<strong>${notification.liker_name} ${notification.liker_surname}</strong> liked your job post.`;
      profilePicture = notification.liker_profile_picture || '/assets/default-profile.png';
      break;

    case 'job_application':
      iconHTML = `<i class="fa-solid fa-file-alt notification-icon"></i>`;
      message = `<strong>${notification.applicant_name} ${notification.applicant_surname}</strong> applied for your job post.`;
      profilePicture = notification.applicant_profile_picture || '/assets/default-profile.png';
      buttonsHTML = `
  <div class="notification-actions">
    <button class="btn accept-btn" data-application-id="${notification.application_id}">Accept</button>
    <button class="btn decline-btn" data-application-id="${notification.application_id}">Decline</button>
  </div>
`;
      break;

    case 'application_acceptance':
  iconHTML = `<i class="fa-solid fa-check-circle notification-icon" style="color: green;"></i>`;
  const jobTitle = notification.job_title || 'a job'; // Fallback in case it's undefined
  message = `<strong style ="Color:green;">Congrats!</strong> <strong>Your application</strong> for <strong>"${jobTitle}"</strong> has been <strong>accepted</strong>!`;
  profilePicture = '/assets/default-profile.png';
  break;
    default:
      message = 'Unknown notification type.';
  }

  card.innerHTML = `
    <div class="notification-left">
      <img src="${profilePicture}" alt="Profile" class="notification-profile-pic" />
      ${iconHTML}
    </div>
    <div class="notification-message">
      <p>${message}</p>
      <span class="notification-time">${new Date(notification.created_at).toLocaleString()}</span>
      ${commentPreview || ''}
    </div>
    ${buttonsHTML}
  `;

  container.appendChild(card);const acceptBtn = card.querySelector('.accept-btn');
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
          alert('Application accepted!');
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
    
  showConfirmationModal("Are you sure you want to decline this application?", () => {
    declineBtn.addEventListener('click', () => {
      const applicationId = declineBtn.getAttribute('data-application-id');
      if (!applicationId) return;

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
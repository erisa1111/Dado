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
        <div class="empty-notifications">
          <p>No new notifications.</p>
        </div>
      <?php else: ?>
        <?php foreach ($allNotifications as $notification): ?>
          <?php
          // Make $notification available to the card component
          include __DIR__ . '/../components/notifications_card/notifications_card.php';
          ?>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Raw notifications JSON (updated by JS) -->
      <pre id="raw-notifications" style="display:none;"></pre>
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

  <script src="../components/nav_home/nav_home.js"></script>
  <script>
    // Build the URL of this page + ?action=fetch
    const fetchUrl = window.location.pathname + '?action=fetch';

    fetch(fetchUrl, { credentials: 'include' })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('raw-notifications').textContent = JSON.stringify(data.notifications, null, 2);
        } else {
          document.getElementById('raw-notifications').textContent = 'Error: ' + data.message;
        }
      })
      .catch(err => {
        document.getElementById('raw-notifications').textContent = 'Error fetching notifications: ' + err;
      });
  </script>
</body>
</html>
<!-- <script src="../components/notifications_card/notifications_card.js"></script> -->
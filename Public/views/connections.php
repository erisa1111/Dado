<?php
session_start();

// Session handling
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

error_log("User ID from session: " . $user_id);

if (!$user_id) {
    echo "User not logged in.";
    exit;
}
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';
require_once __DIR__ . '/../../Config/Database.php';
// Initialize the controller
$connectionsModel = new \App\Models\Connections();
$connectionsController = new App\Controllers\ConnectionsController();
//$connections = $connectionsController->getConnections(); 
$allConnections = $connectionsController->getConnections($user_id);
error_log("All connections: " . print_r($allConnections, true));

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/connections.css">
    <link rel="stylesheet" href="../components/nav_home/nav_home.css">
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
                  <img src="../assets/img/event_dado.webp" alt="Event for Parents and Nannies">
                  <div class="overlay-text">
                    <h2>Join Our Parent & Nanny Event</h2>
                    <button class="register-btn">Register</button>
                  </div>
                </div>
              </div>
              
        </div>
        <div id="qendra">
          
  <!-- <div class="connection-filters">
    <span class="filter-btn active" id="connected-btn">Connected</span>
    <span class="filter-btn" id="pending-btn">Pending Requests</span>
</div> -->
  <div id="center">
    <div id="current-user-id" data-user-id="<?= htmlspecialchars($_SESSION['user_id']) ?>"></div>
  <?php if (empty($allConnections)): ?>
      <div class="empty-connections-wrapper">
            <div class="empty-connections-container">
                <div class="empty-connections">
                    <div class="empty-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>You don't have any connections yet!</h3>
                    <p>Start growing your network by connecting with other users.</p>
                    <button class="find-connections-btn" onclick="window.location.href='search_results.php'">
                      <i class="fas fa-search"></i> Find connections
                    </button>

                </div>
            </div>
        </div>
<?php else: ?>
  <div id="connections-list">
    <?php foreach ($allConnections as $connection): 
        // Optional: implement logic to get user image from DB if available
        $profile_image = 'https://w7.pngwing.com/pngs/584/113/png-transparent-pink-user-icon.png'; // Placeholder

        // Extract and pass variables
        $sender_name = $connection['sender_name'] ?? 'Unknown';
        $sender_surname = $connection['sender_surname'] ?? '';
        $message = $connection['message'] ?? 'Sent you a connection request';
        $status = $connection['status'];
        $created_at = $connection['created_at'];

        include __DIR__ . '/../components/connections_card/connections_card.php';
    endforeach; ?>
</div>
    <?php endif; ?>
</div>
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
              <img src="../assets/img/find_dado.webp" alt="">
            </div>
            
            <div class="app">
              <h6>Try Dado on your Mobile →</h6>
              <a href="#">Dado</a>
            </div>
          </div>
 
</body>
</html>

<script src="../components/nav_home/nav_home.js"></script>
<script src="../components/connections_card/connections_card.js"></script>
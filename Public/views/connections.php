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
<div id="confirm-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" style="display:none;">
  <div class="modal-content">
    <span class="modal-close" id="modal-close">&times;</span>
    <h2 class="modal-title" id="modal-title">Confirm Action</h2>
    <p id="confirm-message">Are you sure?</p>
    <button id="confirm-yes" class="modal-button">Yes</button>
    <button id="confirm-no" class="modal-button" style="background-color: #ccc; color: #333; margin-left: 10px;">No</button>
  </div>
</div>
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
      $profile_image = $connection['profile_picture'] ?? 'https://w7.pngwing.com/pngs/584/113/png-transparent-pink-user-icon.png';
       
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
            <?php
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $suggestedUsers = $userModel->getSuggestedUsers($userId);
            include '../components/recommend/recommendations.php';
        }
      ?>
            
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
<script>
    const connections = <?php echo json_encode($allConnections, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    console.log("✅ Raw connections data:", connections);

    connections.forEach(conn => {
        // Log all possible user ID fields
        console.log('Connection IDs:', {
            sender_id: conn.sender_id,
            user_id: conn.user_id,
            other_ids: Object.keys(conn).filter(k => k.endsWith('_id') && !['sender_id', 'user_id'].includes(k)).reduce((acc, key) => { acc[key] = conn[key]; return acc; }, {})
        });

        // Choose which ID to display in log
        const id = conn.sender_id || conn.user_id || 'No user_id or sender_id found';

        console.log(`🖼️ Raw profile picture for user ${id}: ${conn.profile_picture}`);
    });
</script>
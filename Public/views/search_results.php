<?php
session_start();

use App\Models\User;

require_once __DIR__ . '/../../App/Models/User.php';
// Include Post.php and Job.php when you create them
// require_once __DIR__ . '/../../App/Models/Post.php';
// require_once __DIR__ . '/../../App/Models/Job.php';

if (!isset($_SESSION['user_id'])) {
    echo "No user logged in!";
    exit();
}

$loggedInUserId = $_SESSION['user_id'];

$searchCategory = $_GET['search_category'] ?? 'people';

// Shared filters
$usernameSearch = trim($_GET['username'] ?? '');
$location = $_GET['location'] ?? '';
$roleId = $_GET['role_id'] ?? '';
$minRating = $_GET['min_rating'] ?? '';

$postKeywords = trim($_GET['post_keywords'] ?? '');
$postDate = $_GET['post_date'] ?? '';

$jobKeywords = trim($_GET['job_keywords'] ?? '');
$jobLocation = $_GET['job_location'] ?? '';

$matchingResults = [];
$searchTitle = '';

if ($searchCategory === 'people') {
    $userModel = new User();
    $searchTitle = "People matching \"$usernameSearch\"";
    if ($usernameSearch === '') {
        $matchingResults = [];
    } else {
        $matchingResults = $userModel->searchUsersWithFilters($usernameSearch, $location, $roleId, $minRating);
    }
} elseif ($searchCategory === 'posts') {
    $searchTitle = "Post results for \"$postKeywords\"";
    // Example: $matchingResults = $postModel->searchPosts($postKeywords, $postDate);
    $matchingResults = []; // Replace with real logic later
} elseif ($searchCategory === 'jobs') {
    $searchTitle = "Job results for \"$jobKeywords\"";
    // Example: $matchingResults = $jobModel->searchJobs($jobKeywords, $jobLocation);
    $matchingResults = []; // Replace with real logic later
}

$cities = [ "Prishtina", "Gjilan", "Ferizaj", "Mitrovicë", "Pejë", "Prizren", "Gjakovë", "Vushtrri",
            "Podujevë", "Kamenicë", "Viti", "Malishevë", "Suharekë", "Rahovec", "Deçan", "Istog",
            "Skenderaj", "Dragash", "Klinë", "Kaçanik", "Lipjan", "Obiliq", "Fushë Kosovë", "Shtime",
            "Shtërpcë", "Leposaviq", "Zubin Potok", "Zvečan", "Graçanicë", "Ranillug", "Kllokot",
            "Novobërdë", "Parteš", "Mitrovicë e Jugut", "Mitrovicë e Veriut" ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/search_results.css">
</head>
<body>
    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br>

    <div class="container">

        <!-- FILTER FORM -->
        <div class="filter-container">
        <form method="GET" action="search_results.php" class="filter-form">
            <input type="hidden" name="search_category" id="search_category" value="<?= htmlspecialchars($searchCategory) ?>">

            <!-- PEOPLE -->
            <div class="filter-category">
                <button type="button" onclick="toggleSection('people-section')">People</button>
                <div id="people-section" class="filter-section" <?= $searchCategory === 'people' ? '' : 'style="display:none;"' ?>>
                    <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($usernameSearch) ?>">

                    <select name="location">
                        <option value="">All Locations</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= htmlspecialchars($city) ?>" <?= $location === $city ? 'selected' : '' ?>>
                                <?= htmlspecialchars($city) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="role_id">
                        <option value="">All Roles</option>
                        <option value="1" <?= $roleId === '1' ? 'selected' : '' ?>>Admin</option>
                        <option value="2" <?= $roleId === '2' ? 'selected' : '' ?>>Nanny</option>
                        <option value="0" <?= $roleId === '0' ? 'selected' : '' ?>>Parent</option>
                    </select>

                    <select name="min_rating">
                        <option value="">Any Rating</option>
                        <option value="5" <?= $minRating === '5' ? 'selected' : '' ?>>5 stars</option>
                        <option value="4" <?= $minRating === '4' ? 'selected' : '' ?>>4+ stars</option>
                        <option value="3" <?= $minRating === '3' ? 'selected' : '' ?>>3+ stars</option>
                    </select>
                </div>
            </div>

            <!-- POSTS -->
            <div class="filter-category">
                <button type="button" onclick="toggleSection('posts-section')">Posts</button>
                <div id="posts-section" class="filter-section" <?= $searchCategory === 'posts' ? '' : 'style="display:none;"' ?>>
                    <input type="text" name="post_keywords" placeholder="Post Keywords" value="<?= htmlspecialchars($postKeywords) ?>">
                    <input type="date" name="post_date" value="<?= htmlspecialchars($postDate) ?>">
                </div>
            </div>

            <!-- JOBS -->
            <div class="filter-category">
                <button type="button" onclick="toggleSection('jobs-section')">Jobs</button>
                <div id="jobs-section" class="filter-section" <?= $searchCategory === 'jobs' ? '' : 'style="display:none;"' ?>>
                    <input type="text" name="job_keywords" placeholder="Job Keywords" value="<?= htmlspecialchars($jobKeywords) ?>">

                    <select name="job_location">
                        <option value="">All Locations</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= htmlspecialchars($city) ?>" <?= $jobLocation === $city ? 'selected' : '' ?>>
                                <?= htmlspecialchars($city) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit">Search</button>
        </form>
        </div>

        <!-- RESULTS -->
        <div class="results-container">
            <h2><?= htmlspecialchars($searchTitle) ?></h2>

            <?php if (!empty($matchingResults)): ?>
                <ul class="result-list">
                    <?php foreach ($matchingResults as $result): ?>
                        <?php if ($searchCategory === 'people'): ?>
                            <li class="user-card">
                                <?php
                                    $profilePic = !empty($result['profile_picture']) 
                                        ? '/' . htmlspecialchars($result['profile_picture']) 
                                        : '/assets/img/default_profile.webp';
                                ?>
                                <div class="user-avatar">
                                    <img src="<?= $profilePic ?>" alt="Profile Picture">
                                </div>
                                <div class="user-info">
                                    <a href="profile.php?user_id=<?= $result['id'] ?>">
                                        <p class="username"><?= htmlspecialchars($result['username']) ?></p>
                                        <p class="full_name"><?= htmlspecialchars($result['name']) ?> <?= htmlspecialchars($result['surname']) ?></p>
                                    </a>
                                    <p class="role"><?= htmlspecialchars($result['role_name']) ?></p>
                                </div>
                            </li>
                        <?php elseif ($searchCategory === 'posts'): ?>
                            <li class="post-card">Post display placeholder</li>
                        <?php elseif ($searchCategory === 'jobs'): ?>
                            <li class="job-card">Job display placeholder</li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-results">No results found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function toggleSection(id) {
        const sections = ['people-section', 'posts-section', 'jobs-section'];
        const categoryMap = {
            'people-section': 'people',
            'posts-section': 'posts',
            'jobs-section': 'jobs'
        };
        sections.forEach(section => {
            document.getElementById(section).style.display = (section === id) ? 'block' : 'none';
        });
        document.getElementById('search_category').value = categoryMap[id];
    }
    </script>

    <script src="../components/nav_home/nav_home.js"></script>
</body>
</html>

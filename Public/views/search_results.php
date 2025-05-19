<?php
session_start();

use App\Models\User;
use App\Models\Post;
use App\Models\JobPost;

require_once __DIR__ . '/../../App/Models/User.php';
require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../App/Models/JobPost.php';

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
    if ($postKeywords !== '') {
        $postModel = new Post();
        $matchingResults = $postModel->searchPosts($postKeywords);
        if ($postDate !== '') {
            $matchingResults = array_filter($matchingResults, function ($post) use ($postDate) {
                return date('Y-m-d', strtotime($post['created_at'])) === $postDate;
            });
        }
    }
} elseif ($searchCategory === 'jobs') {
    $searchTitle = "Job results for \"$jobKeywords\"";
    $jobModel = new JobPost(); 
    if ($jobKeywords !== '') {
        $matchingResults = $jobModel->searchJobs($jobKeywords);

        if ($jobLocation !== '') {
            $matchingResults = array_filter($matchingResults, function ($job) use ($jobLocation) {
                return stripos($job['location'], $jobLocation) !== false;
            });
        }
    } else {
        $matchingResults = [];
    }
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
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/components/postcard/postcard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body style="display: block;">
    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br>

    <div class="container">

        <!-- FILTER FORM -->
        <div class="filter-container">
            <h2>Search Results</h2>
            <hr>
            <h3 style="margin-top: 1.5rem; color:#8f5a6b;">Filters</h3>
            <form method="GET" action="search_results.php" class="filter-form">
                <input type="hidden" name="search_category" id="search_category" value="<?= htmlspecialchars($searchCategory) ?>">

                <!-- PEOPLE -->
                <div class="filter-category">
                    <button type="button" onclick="toggleSection('people-section')"><i class="fa-solid fa-circle-user"></i> People</button>
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
                    <button type="button" onclick="toggleSection('posts-section')"><i class="fa-solid fa-rectangle-list"></i> Posts</button>
                    <div id="posts-section" class="filter-section" <?= $searchCategory === 'posts' ? '' : 'style="display:none;"' ?>>
                        <input type="text" name="post_keywords" placeholder="Post Keywords" value="<?= htmlspecialchars($postKeywords) ?>">
                        <input type="date" name="post_date" value="<?= htmlspecialchars($postDate) ?>">
                    </div>
                </div>

                <!-- JOBS -->
                <div class="filter-category">
                    <button type="button" onclick="toggleSection('jobs-section')"><i class="fa-solid fa-briefcase"></i> Jobs</button>
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
                            <li class="post-card">
                                <div class="post" id="post-<?php echo $result['id']; ?>">
                                    <div class="post-header">
                                        <?php
                                        $profilePic = !empty($result['profile_picture']) 
                                        ? '/' . ltrim($result['profile_picture'], '/\\') 
                                        : '/assets/img/dado_profile.webp';
                                        ?>
                                        <img class="profile-img" src="<?php echo htmlspecialchars($profilePic); ?>" alt="User Profile">
                                        <div class="details">
                                        <h4 class="username"><?php echo htmlspecialchars($result['username']); ?></h4>
                                        <p class="location">Posted on <?php echo date('F j, Y', strtotime($result['created_at'])); ?></p>
                                        </div>
                                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $result['user_id']): ?>
                                        <div class="post-menu-wrapper">
                                            <button type="button" class="post-menu-toggle">⋮</button>
                                            <div class="post-act">
                                            <button type="button" class="edit-post" data-post-id="<?php echo $result['id']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="delete-post" data-post-id="<?php echo $result['id']; ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="post-content">
                                        <?php echo htmlspecialchars($result['body']); ?>
                                    </div>
                                    <?php if (!empty($result['image_url'])): ?>
                                        <div class="post-images">
                                        <img src="<?php echo htmlspecialchars($result['image_url']); ?>" alt="Post Image">
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-actions">
                                        <button class="act like-btn" data-post-id="<?php echo $result['id']; ?>">
                                        <i class="fa-regular fa-heart"></i>
                                        </button>
                                        <button class="act comment-btn" data-post-id="<?php echo $result['id']; ?>">
                                        <i class="fa-regular fa-comment"></i>
                                        </button>
                                    </div>
                                    <!-- <div class="post-footer">
                                        <div class="likes"><?php echo $result['like_count']; ?> likes</div>
                                        <div class="comments"><?php echo $result['comment_count']; ?> comments</div>
                                    </div> -->


                                    <div class="comments-list" id="comments-list-<?php echo $result['id']; ?>" style="display:none">
                                        <div class="no-comments">No comments yet</div>
                                    </div>
                                    <div class="post-comment">
                                        <input type="text" id="comment-<?php echo $result['id']; ?>" name="comment" placeholder="Add a comment..."
                                        class="comment-input" data-post-id="<?php echo $result['id']; ?>">
                                        <button id="submit-comment" data-post-id="<?php echo $result['id']; ?>">
                                        <i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                        <div id="current-user-id" data-user-id="<?php echo $_SESSION['user_id'] ?? ''; ?>"></div>
                                    </div>
                                    </div>
                            </li>
                        <?php elseif ($searchCategory === 'jobs'): ?>
                            <?php $jobpost = $result; ?>
                            <li class="job-card">
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
            document.getElementById(section).style.display = (section === id) ? 'flex' : 'none';
        });
        document.getElementById('search_category').value = categoryMap[id];
    }
    </script>

    <script src="../components/nav_home/nav_home.js"></script>
    <script src="/components/postcard/postcard.js"></script>
    <script src="/assets/js/home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</body>
</html>
<?php
// test_post_model.php

require_once __DIR__ . '/Post.php';
use App\Models\Post;

// Instantiate the model
$model = new Post(null); // Pass null, the constructor creates the DB connection internally

// Simulate a user ID (make sure this exists in your database)
$userId = 46;

// Call the getAll method
$posts = $model->getAll($userId);

// Output results
if ($posts) {
    echo "Posts fetched successfully:\n\n";
    foreach ($posts as $i => $post) {
        echo "Post #" . ($i + 1) . "\n";
        echo "Title: " . ($post['title'] ?? 'No title') . "\n";
        echo "Author: " . $post['name'] . " " . $post['surname'] . "\n";
        echo "Like Count: " . $post['like_count'] . "\n";
        echo "Comment Count: " . $post['comment_count'] . "\n";
        echo "Is Liked by User: " . var_export($post['is_liked'], true) . "\n";
        echo "-----------------------------\n";
    }
} else {
    echo "No posts found or query failed.\n";
}

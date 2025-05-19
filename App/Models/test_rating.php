<?php
// test_rating_model.php

require_once __DIR__ . '/RatingModel.php';
use App\Models\RatingModel;

// Instantiate the model
$model = new RatingModel();

// Test data
$job_id = 9;
$reviewer_id = 46;
$rating = 4.5; // Example rating between 1 and 5
$comment = "This is a test rating.";

// Call insertRating
$result = $model->insertRating($job_id, $reviewer_id, $rating, $comment);

if ($result) {
    echo "Rating inserted successfully.\n";
} else {
    echo "Failed to insert rating.\n";
}

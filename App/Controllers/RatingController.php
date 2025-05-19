<?php
namespace App\Controllers;
require_once __DIR__ . '/../../App/Models/JobModel.php';
require_once __DIR__ . '/../../Config/Database.php';




require_once __DIR__ . '/../../App/Models/RatingModel.php';

use App\Models\RatingModel;
use App\Models\JobModel;

class RatingController
{
    private $ratingModel;


    public function __construct()
    {
        $this->ratingModel = new RatingModel();
  
      

    }
public function createRating($job_id, $reviewer_id, $rating, $comment)
{
    
    if (!is_numeric($job_id) || !is_numeric($reviewer_id)) {
        return ['success' => false, 'error' => 'Invalid job or reviewer ID', 'http_code' => 400];
    }

    // Allow rating to be optional if comment exists
    if ($rating !== '' && $rating !== null) {
        if (!is_numeric($rating)) {
            return ['success' => false, 'error' => 'Rating must be a number', 'http_code' => 400];
        }
        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'error' => 'Rating must be between 1 and 5', 'http_code' => 400];
        }
    } else {
        // If rating empty, maybe check if comment exists, else reject
        if (trim($comment) === '') {
            return ['success' => false, 'error' => 'Rating or comment required', 'http_code' => 400];
        }
        // You may want to set rating to NULL or 0 here if your DB/SP supports it
        $rating = null;
    }

    $comment = trim($comment);
    if ($this->ratingModel->hasUserRatedJob($job_id, $reviewer_id)) {
        return ['success' => false, 'error' => 'You have already rated this job.', 'http_code' => 400];
    }

    try {
        $success = $this->ratingModel->insertRating($job_id, $reviewer_id, $rating, $comment);
        if ($success) {
            return ['success' => true, 'message' => 'Rating created successfully', 'http_code' => 200];
        } else {
            return ['success' => false, 'error' => 'Failed to create rating', 'http_code' => 500];
        }
    } catch (\Exception $e) {
        return ['success' => false, 'error' => 'Server error: ' . $e->getMessage(), 'http_code' => 500];
    }
}

}

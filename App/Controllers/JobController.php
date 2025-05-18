<?php
namespace App\Controllers;

require_once __DIR__ . '/../../App/Models/JobModel.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Models\JobModel;

class JobController
{
    private $jobModel;
    public function __construct()
{
    $this->jobModel = new JobModel();
}


    public function getJobsByUser($userId)
{
    header('Content-Type: application/json');

    if (!$userId || !is_numeric($userId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid user ID']);
        return;
    }

    try {
        $this->jobModel->updateJobStatuses();
        $jobs = $this->jobModel->getJobsForUser($userId);
       

        if ($jobs) {
    echo json_encode([
        'success' => true,
        'jobs' => array_map(function($job) use ($userId) {
            if ($job['nanny_id'] == $userId) {
                $otherPersonName = $job['parent_name'] ?? 'Unknown Parent';
            } elseif ($job['parent_id'] == $userId) {
                $otherPersonName = $job['nanny_name'] ?? 'Unknown Nanny';
            } else {
                $otherPersonName = 'Unknown';
            }

            return [
                'id' => $job['id'],
                'other_person_name' => $otherPersonName,
                'start_date' => $job['start_date'] ?? 'Unknown Date',
                'end_date' => $job['end_date'] ?? 'Unknown Date',
                'job_type' => $job['job_type'] ?? 'Unknown Type',
                'status' => $job['status'] ?? 'Unknown Status',
            ];
        }, $jobs)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No jobs found']);
}

    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    }
}

}

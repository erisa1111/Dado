<?php

namespace App\Helpers;

class Validation
{
    public static function validateSignupData($data)
    {
        // Very basic checks - you can improve later
        $requiredFields = ['name', 'surname', 'location', 'gender', 'email', 'username', 'phone_number', 'password'];

    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            return ucfirst($field) . " is required.";
        }
    }
       /* foreach ($data as $field => $value) {
            if (empty($value)) {
                return "$field is required.";
            }
        }*/
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }

        if (strlen($data['password']) < 8) {
            return "Password must be at least 8 characters long.";
        }

        $allowedGenders = ['F', 'M'];
        if (!in_array(strtoupper($data['gender']), $allowedGenders)) {
            return "Gender must be either 'F' or 'M'.";
        }

        if (isset($data['role']) && $data['role'] === 'nanny') {
            if (empty($data['schedule'])) {
                return "Schedule is required for nannies.";
            }

            $allowedSchedules = ['Full-Time', 'Part-Time'];
            if (!in_array($data['schedule'], $allowedSchedules)) {
                return "Schedule must be either 'Full-Time' or 'Part-Time'.";
            }
        }
        return true;
    }
}
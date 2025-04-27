<?php

namespace App\Helpers;

class Validation
{
    public static function validateSignupData($data)
    {
        // Very basic checks - you can improve later
        foreach ($data as $field => $value) {
            if (empty($value)) {
                return "$field is required.";
            }
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return true;
    }
}
<?php
/**
 * Online Registration Form - Backend Processing
 * This file handles form submission, validation, and data processing
 */

// Set headers for JSON response
header('Content-Type: application/json');

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Initialize response array
$response = [
    'status' => 'error',
    'message' => '',
    'data' => []
];

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST requests are allowed.');
    }

    // Sanitize and validate input data
    $fullName = sanitizeInput($_POST['fullName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $dob = sanitizeInput($_POST['dob'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $country = sanitizeInput($_POST['country'] ?? '');
    $course = sanitizeInput($_POST['course'] ?? '');
    $comments = sanitizeInput($_POST['comments'] ?? '');

    // Handle interests (checkbox array)
    $interests = [];
    if (isset($_POST['interests']) && is_array($_POST['interests'])) {
        foreach ($_POST['interests'] as $interest) {
            $interests[] = sanitizeInput($interest);
        }
    }

    // Validation
    $errors = [];

    // Validate Full Name
    if (empty($fullName)) {
        $errors[] = 'Full Name is required';
    } elseif (strlen($fullName) < 2 || strlen($fullName) > 100) {
        $errors[] = 'Full Name must be between 2 and 100 characters';
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = 'Email Address is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    // Validate Phone
    if (empty($phone)) {
        $errors[] = 'Phone Number is required';
    } elseif (!preg_match('/^[0-9\-\+\(\)\s]+$/', $phone)) {
        $errors[] = 'Invalid phone number format';
    }

    // Validate Date of Birth
    if (empty($dob)) {
        $errors[] = 'Date of Birth is required';
    } else {
        $dobDate = DateTime::createFromFormat('Y-m-d', $dob);
        if (!$dobDate) {
            $errors[] = 'Invalid date format';
        } else {
            $today = new DateTime();
            $age = $today->diff($dobDate)->y;
            if ($age < 10 || $age > 100) {
                $errors[] = 'Age must be between 10 and 100 years';
            }
        }
    }

    // Validate Gender
    if (empty($gender)) {
        $errors[] = 'Gender is required';
    } elseif (!in_array($gender, ['Male', 'Female', 'Other'])) {
        $errors[] = 'Invalid gender selection';
    }

    // Validate Address
    if (empty($address)) {
        $errors[] = 'Address is required';
    } elseif (strlen($address) < 10) {
        $errors[] = 'Address must be at least 10 characters';
    }

    // Validate Country
    $validCountries = ['India', 'USA', 'UK', 'Canada', 'Australia', 'Germany', 'France', 'Other'];
    if (empty($country)) {
        $errors[] = 'Country is required';
    } elseif (!in_array($country, $validCountries)) {
        $errors[] = 'Invalid country selection';
    }

    // Validate Course
    $validCourses = [
        'Computer Science',
        'Information Technology',
        'Electronics',
        'Mechanical Engineering',
        'Civil Engineering',
        'Business Administration'
    ];
    if (empty($course)) {
        $errors[] = 'Course is required';
    } elseif (!in_array($course, $validCourses)) {
        $errors[] = 'Invalid course selection';
    }

    // Check if there are any validation errors
    if (!empty($errors)) {
        $response['message'] = implode(', ', $errors);
        echo json_encode($response);
        exit;
    }

    // Format interests for display
    $interestsString = !empty($interests) ? implode(', ', $interests) : 'None';

    // Format Date of Birth for display
    $dobFormatted = date('F d, Y', strtotime($dob));

    // Prepare data for response
    $registrationData = [
        'fullName' => $fullName,
        'email' => $email,
        'phone' => $phone,
        'dob' => $dobFormatted,
        'gender' => $gender,
        'address' => $address,
        'country' => $country,
        'course' => $course,
        'interests' => $interestsString,
        'comments' => !empty($comments) ? $comments : 'No additional comments',
        'submissionDate' => date('F d, Y h:i A')
    ];

    // Success response
    $response['status'] = 'success';
    $response['message'] = 'Registration submitted successfully!';
    $response['data'] = $registrationData;

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Return JSON response
echo json_encode($response);
exit;

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>

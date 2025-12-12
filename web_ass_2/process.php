<?php
// Enable error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set response header to JSON
header('Content-Type: application/json');

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Initialize response array
    $response = array();
    $errors = array();
    
    // Validate and sanitize inputs
    $fullName = isset($_POST['fullName']) ? sanitizeInput($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
    $dob = isset($_POST['dob']) ? sanitizeInput($_POST['dob']) : '';
    $gender = isset($_POST['gender']) ? sanitizeInput($_POST['gender']) : '';
    $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : '';
    $country = isset($_POST['country']) ? sanitizeInput($_POST['country']) : '';
    $course = isset($_POST['course']) ? sanitizeInput($_POST['course']) : '';
    $comments = isset($_POST['comments']) ? sanitizeInput($_POST['comments']) : '';
    
    // Handle interests (checkbox array)
    $interests = array();
    if (isset($_POST['interests']) && is_array($_POST['interests'])) {
        foreach ($_POST['interests'] as $interest) {
            $interests[] = sanitizeInput($interest);
        }
    }
    
    // Validation
    if (empty($fullName)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validateEmail($email)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($dob)) {
        $errors[] = "Date of birth is required";
    }
    
    if (empty($gender)) {
        $errors[] = "Gender is required";
    }
    
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    
    if (empty($country)) {
        $errors[] = "Country is required";
    }
    
    if (empty($course)) {
        $errors[] = "Course is required";
    }
    
    // Check if there are any errors
    if (!empty($errors)) {
        $response['status'] = 'error';
        $response['message'] = implode(', ', $errors);
        echo json_encode($response);
        exit;
    }
    
    // Format date of birth
    $formattedDob = date('F d, Y', strtotime($dob));
    
    // Prepare data for display
    $displayData = array(
        'fullName' => $fullName,
        'email' => $email,
        'phone' => $phone,
        'dob' => $formattedDob,
        'gender' => $gender,
        'address' => $address,
        'country' => $country,
        'course' => $course,
        'interests' => !empty($interests) ? implode(', ', $interests) : 'None',
        'comments' => !empty($comments) ? $comments : 'No additional comments'
    );
    
    // Optional: Save to database
    // Example database connection and insertion
    
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        $response['status'] = 'error';
        $response['message'] = 'Database connection failed';
        echo json_encode($response);
        exit;
    }
    
    // Prepare SQL statement
    $interestsString = implode(', ', $interests);
    $sql = "INSERT INTO registrations (full_name, email, phone, dob, gender, address, country, course, interests, comments, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $fullName, $email, $phone, $dob, $gender, $address, $country, $course, $interestsString, $comments);
    
    if ($stmt->execute()) {
        // Success
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to save registration';
        echo json_encode($response);
        exit;
    }
    
    $stmt->close();
    $conn->close();
    
    
    // Optional: Send email notification
    
    $to = $email;
    $subject = "Registration Confirmation";
    $message = "Dear " . $fullName . ",\n\n";
    $message .= "Thank you for registering. Your registration details:\n\n";
    $message .= "Name: " . $fullName . "\n";
    $message .= "Email: " . $email . "\n";
    $message .= "Course: " . $course . "\n\n";
    $message .= "Best regards,\nRegistration Team";
    $headers = "From: noreply@yourwebsite.com";
    
    mail($to, $subject, $message, $headers);
    
    
    // Success response
    $response['status'] = 'success';
    $response['message'] = 'Registration successful';
    $response['data'] = $displayData;
    
    echo json_encode($response);
    
} else {
    // If not POST request
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
}
?>

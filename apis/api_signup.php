<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit;
}

// Get JSON body input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input fields
$requiredFields = ['first_name', 'last_name', 'email', 'username', 'password'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "$field is required"]);
        exit;
    }
}

// Connect to MySQL
// Provide your own details here
$conn = mysqli_connect('hostname', 'username', 'password', 'database');
if (!$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}
mysqli_set_charset($conn, 'utf8');

// Check if username or email already exists
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ?");
mysqli_stmt_bind_param($stmt, 'ss', $input['username'], $input['email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
// Exit if username or email already exists
if (mysqli_fetch_assoc($result)) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Username or email already exists"]);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}
mysqli_stmt_close($stmt);

// Insert new user into database
$stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param(
    $stmt,
    'sssss',
    $input['first_name'],
    $input['last_name'],
    $input['email'],
    $input['username'],
    $input['password']
);
$success = mysqli_stmt_execute($stmt);

// Send back status
if ($success) {
    echo json_encode(["success" => true, "message" => "User registered successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error creating user"]);
}

// Cleanup
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

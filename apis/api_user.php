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

// Validate input presence
if (!isset($input['username']) || !isset($input['password'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Username and password required"]);
    exit;
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

// Grab user associated with username
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, 's', $input['username']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check username
if ($user = mysqli_fetch_assoc($result)) {
    // Check password associated with username
    if ($input['password'] === $user['password']) {
        // Auth success — don't return password
        echo json_encode([
            "success" => true,
            "username" => $user['username'],
        ]);
    } else {    // Password incorrect
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Incorrect username or password"]);
    }
} else {    // Username does not exist
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Incorrect username or password"]);
}

// Cleanup
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

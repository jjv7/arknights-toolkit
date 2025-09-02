<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Connect to MySQL
// Provide your own details here
$conn = mysqli_connect('hostname', 'username', 'password', 'database');
if (!$conn) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}
mysqli_set_charset($conn, 'utf8');

// === PUT: Toggle like/unlike ===
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['like']) && $_GET['like'] === 'true') {
    $input = json_decode(file_get_contents('php://input'), true);

    // This case should never happen, if authentication logic on frontend is correct
    if (!isset($input['id']) || !isset($input['user'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing post ID or user"]);
        mysqli_close($conn);
        exit;
    }

    $post_id = intval($input['id']);
    $user = $input['user'];

    // See if user has already liked post
    $check = mysqli_prepare($conn, "SELECT 1 FROM post_likes WHERE post_id = ? AND user = ?");
    mysqli_stmt_bind_param($check, "is", $post_id, $user);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    $liked = mysqli_stmt_num_rows($check) > 0;
    mysqli_stmt_close($check);

    if ($liked) {
        // Delete row associated with the user like for that post
        $delete = mysqli_prepare($conn, "DELETE FROM post_likes WHERE post_id = ? AND user = ?");
        mysqli_stmt_bind_param($delete, "is", $post_id, $user);
        mysqli_stmt_execute($delete);
        mysqli_stmt_close($delete);
        echo json_encode(["success" => true, "liked" => false]);    // Booleans are more for the frontend to recalculate the likes
    } else {
        // Insert username with associated post id for like tracking
        $insert = mysqli_prepare($conn, "INSERT INTO post_likes (post_id, user) VALUES (?, ?)");
        mysqli_stmt_bind_param($insert, "is", $post_id, $user);
        mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);
        echo json_encode(["success" => true, "liked" => true]);     // Booleans are more for the frontend to recalculate the likes
    }

    mysqli_close($conn);
    exit;
}

// === POST: Create a new post ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Check all required fields are present
    if (
        !isset($input['author']) ||
        !isset($input['title']) ||
        !isset($input['content']) ||
        !isset($input['category'])
    ) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        mysqli_close($conn);
        exit;
    }

    // Insert post into posts table
    $stmt = mysqli_prepare($conn, "
        INSERT INTO posts (author, title, content, category, timestamp)
        VALUES (?, ?, ?, ?, NOW())
    ");
    mysqli_stmt_bind_param($stmt, 'ssss', $input['author'], $input['title'], $input['content'], $input['category']);

    // Send back status
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to insert post"]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

// === PUT: Update a post ===
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Check all required fields are present
    if (
        !isset($input['id']) ||
        !isset($input['title']) ||
        !isset($input['content']) ||
        !isset($input['category'])
    ) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing required fields for update"]);
        mysqli_close($conn);
        exit;
    }

    // Update post associated with id
    $stmt = mysqli_prepare($conn, "
        UPDATE posts 
        SET title = ?, content = ?, category = ?
        WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmt, 'sssi', $input['title'], $input['content'], $input['category'], $input['id']);
    $success = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Send back status
    if ($success && $affected > 0) {
        echo json_encode(["success" => true, "message" => "Post updated successfully"]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Post not found or not updated"]);
    }

    exit;
}

// === DELETE: Delete a post ===
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if post id is present
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Missing post ID for deletion"]);
        mysqli_close($conn);
        exit;
    }

    // Delete post associated with id
    $id = intval($input['id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM posts WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Send back status
    if ($success && $affected > 0) {
        echo json_encode(["success" => true, "message" => "Post deleted successfully"]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Post not found or could not be deleted"]);
    }
    exit;
}

// === GET: Check if user liked a post ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
    isset($_GET['like_status']) && $_GET['like_status'] === 'true' &&
    isset($_GET['post_id']) && isset($_GET['user'])) {

    $post_id = intval($_GET['post_id']);
    $user = $_GET['user'];
    
    // Check if user liked post
    $stmt = mysqli_prepare($conn, "SELECT 1 FROM post_likes WHERE post_id = ? AND user = ?");
    mysqli_stmt_bind_param($stmt, "is", $post_id, $user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $liked = mysqli_stmt_num_rows($stmt) > 0;   // If rows returned, user has liked post

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo json_encode(["success" => true, "liked" => $liked]);   // Return boolean for frontend to handle
    exit;
}

// === GET: Fetch all posts or a single post by ID ===
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        // Get specific post if id is present
        $id = intval($_GET['id']);
        $stmt = mysqli_prepare($conn, "
            SELECT 
                p.id, p.author, p.title, p.content, p.category, p.timestamp,
                (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) AS like_count
            FROM posts p
            WHERE p.id = ?
        ");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $post = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Return status
        if (!$post) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Post not found"]);
        } else {
            echo json_encode(["success" => true, "post" => $post]);
        }
        exit;
    } else {
        // id is not present, grab all posts
        $query = "
            SELECT 
                p.id, p.author, p.title, p.content, p.category, p.timestamp,
                (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) AS like_count
            FROM posts p
            ORDER BY p.timestamp DESC
        ";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to fetch posts"]);
            mysqli_close($conn);
            exit;
        }

        // Make array of posts to return
        $posts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $posts[] = $row;
        }

        mysqli_free_result($result);
        mysqli_close($conn);

        // Return all posts
        echo json_encode($posts);
        exit;
    }
}

// === Method not allowed ===
http_response_code(405);
echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
mysqli_close($conn);
exit;
?>

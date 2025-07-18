<?php
$host = 'db'; 
$db   = 'xalencia_task_db';
$user = 'root';
$pass = 'root';

define('API_KEY', '9dj28Jsd!92@Xal#Key');



header('Content-Type: application/json');

$headers = getallheaders();
if (!isset($headers['Authorization']) || $headers['Authorization'] !== 'Bearer ' . API_KEY) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($request_uri, '/'));

//POST / USERS


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $request_uri === '/users') {
    $input = json_decode(file_get_contents('php://input'), true);

    $first = trim($input['first_name'] ?? '');
    $last  = trim($input['last_name'] ?? '');
    $email = trim($input['email'] ?? '');
    
    $errors = [];
    
    if ($first === '') {
        $errors[] = 'First name is required';
    } elseif (!ctype_upper(substr($first, 0, 1))) {
        $errors[] = 'First name must start with a capital letter';
    }
    
    if ($last === '') {
        $errors[] = 'Last name is required';
    } elseif (!ctype_upper(substr($last, 0, 1))) {
        $errors[] = 'Last name must start with a capital letter';
    }
    
    //This checks if email already exists
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();
    $emailExists = $checkStmt->fetchColumn();
    
    if ($emailExists > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Email already exists']);
        exit;
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['errors' => $errors]);
        exit;
    }


    try {
        $stmt = $pdo->prepare("CALL create_user(:first_name, :last_name, :email)");
        $stmt->bindParam(':first_name', $input['first_name']);
        $stmt->bindParam(':last_name', $input['last_name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(['message' => 'User created']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}


// GET/ USERS / pagination

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $request_uri === '/users') {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    try {
        $stmt = $pdo->prepare("CALL get_all_users(:limit, :offset)");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}


//GET/ USERS by id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('#^/users/\d+$#', $request_uri)) {
    $id = (int)$segments[1];

    try {
        $stmt = $pdo->prepare("CALL get_user_by_id(:id)");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

//PUT / USERS

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('#^/users/\d+$#', $request_uri)) {
    $id = (int)$segments[1];
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['first_name'], $input['last_name'], $input['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("CALL update_user(:id, :first_name, :last_name, :email)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':first_name', $input['first_name']);
        $stmt->bindParam(':last_name', $input['last_name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->execute();

        echo json_encode(['message' => 'User updated']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}


//DELETE/ USERS

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && preg_match('#^/users/\d+$#', $request_uri)) {
    $id = (int)$segments[1];

    try {
        $stmt = $pdo->prepare("CALL delete_user(:id)");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['message' => 'User deleted']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

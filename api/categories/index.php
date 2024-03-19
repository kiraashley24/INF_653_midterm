<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

    // Include database and category files
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Route based on method
    switch ($method) {
        case 'GET':
            // Check if an ID is provided
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if ($id !== null) {
                // Include read_single.php for fetching a single category
                include_once 'read_single.php';
            } else {
                // Include read.php for fetching all categories
                include_once 'read.php';
            }
            break;
        case 'POST':
            include_once 'create.php';
            break;
        case 'PUT':
            include_once 'update.php';
            break;
        case 'DELETE':
            include_once 'delete.php';
            break;
        default:
            // Method not allowed
            http_response_code(405);
            echo json_encode(array('message' => 'Method Not Allowed'));
            break;
    }

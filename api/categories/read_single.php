<?php
   // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Include database and category files
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Get ID
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get single category
    $category->read_single();

    // Check if category exists
    if ($category->id) {
        // Create array
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category
        );

        // Make JSON
        echo json_encode($category_arr);
    } else {
        // No category found
        echo json_encode(array('message' => 'category_id Not Found'));
    }

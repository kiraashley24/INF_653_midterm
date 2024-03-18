<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Include necessary files
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate category object
    $category = new Category($db);

    // Get categories
    $result = $category->read();
    $num = $result->rowCount();

    // Check if any categories
    if ($num > 0) {
        $categories_arr = array(); 

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                'id' => $id,
                'category' => $category
            );

            
            $categories_arr[] = $category_item;
        }

        echo json_encode($categories_arr);
    } else {
        // No Categories
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }

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

    // Include necessary files
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Route based on method
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Get single quote
                $quote->id = $_GET['id'];
                $quote->read_single();
                if ($quote->quote === null) {
                    break;
                } else {
                    $quote_item = array(
                        'id' => $quote->id,
                        'quote' => $quote->quote,
                        'author_name' => $quote->author_name,
                        'category_name' => $quote->category_name
                    );
                    echo json_encode($quote_item);
                }
            } elseif (isset($_GET['author_id']) || isset($_GET['category_id'])) {
                // Get quotes by author_id, category_id, or both
                $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
                $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
                $result = $quote->read($author_id, $category_id);
                $num = $result->rowCount();

                // Check if any quotes
                if ($num > 0) {
                    $quotes_arr = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $quote_item = array(
                            'id' => $id,
                            'quote' => $quote,
                            'author_name' => $author_name,
                            'category_name' => $category_name
                        );
                        array_push($quotes_arr, $quote_item);
                    }
                    echo json_encode($quotes_arr);
                } else {
                    // No Quotes
                    echo json_encode(
                        array('message' => 'No quotes found')
                    );
                }
            } else {
                // Get all quotes
                $result = $quote->read();
                $num = $result->rowCount();

                // Check if any quotes
                if ($num > 0) {
                    $quotes_arr = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $quote_item = array(
                            'id' => $id,
                            'quote' => $quote,
                            'author_name' => $author_name,
                            'category_name' => $category_name
                        );
                        array_push($quotes_arr, $quote_item);
                    }
                    echo json_encode($quotes_arr);
                } else {
                    // No Quotes
                    echo json_encode(
                        array('message' => 'No quotes found')
                    );
                }
            }
            break;
        
        case 'POST':
            // Your POST logic here
            break;
        case 'PUT':
            // Your PUT logic here
            break;
        case 'DELETE':
            // Your DELETE logic here
            break;
        default:
            // Method not allowed
            http_response_code(405);
            echo json_encode(array('message' => 'Method Not Allowed'));
            break;
    }
?>

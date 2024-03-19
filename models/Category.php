<?php
class Category {
    // DB stuff
    private $conn;
    private $table = 'categories';

    // Category Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT id, category
                    FROM ' . $this->table . '
                    ORDER BY id ASC';
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt;
    }    
    
    // Get Single Category
    public function read_single() {
        // Create query
        $query = 'SELECT id, category
                    FROM ' . $this->table . '
                    WHERE id = ?
                    LIMIT 1 OFFSET 0';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->id = $row['id'];
        $this->category = $row['category'];
    }

    // Create Category
    public function create() {
        $query = 'INSERT INTO ' .
            $this->table . '
            (category)
            VALUES
            (:category)';

        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function update() {
    // Check if category ID is provided
    if (!isset($this->id)) {
        echo json_encode(array('message' => 'Missing category ID'));
        return false;
    }

    // Check if category name is provided
    if (!isset($this->category)) {
        echo json_encode(array('message' => 'Missing category name'));
        return false;
    }

    // Check if category with the given ID exists
    $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
    $check_stmt = $this->conn->prepare($check_query);
    $check_stmt->bindParam(':id', $this->id);
    $check_stmt->execute();

    if ($check_stmt->rowCount() == 0) {
        // Category with the provided ID does not exist
        echo json_encode(array('message' => 'Category with id ' . $this->id . ' not found'));
        return false;
    }

    // Category exists, proceed with the update
    $query = 'UPDATE ' . $this->table . '
                SET category = :category
                WHERE id = :id';

    $stmt = $this->conn->prepare($query);
    $this->category = htmlspecialchars(strip_tags($this->category));
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(':category', $this->category);
    $stmt->bindParam(':id', $this->id);

    if ($stmt->execute()) {
        return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
    }

    // Delete Category
    public function delete() {
        // Check if category ID is provided
        if (!isset($this->id)) {
            echo json_encode(array('message' => 'Missing category ID'));
            return false;
        }
        // Check if category with the given ID exists
        $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':id', $this->id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() == 0) {
            // Category with the provided ID does not exist
            echo json_encode(array('message' => 'Category with id ' . $this->id . ' not found'));
            return false;
        }
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}

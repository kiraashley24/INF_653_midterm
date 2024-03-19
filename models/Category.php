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




    // Update Category
    public function update() {
        $query = 'UPDATE ' .
            $this->table . '
            SET
            category = :category
            WHERE
                id = :id';

        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam('category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Category
    public function delete() {
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

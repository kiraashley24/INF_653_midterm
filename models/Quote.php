<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id; 
    public $category_id; 
    public $author;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($author_id = null, $category_id = null) {
        $query = 'SELECT q.id, q.quote, 
                  a.id as author_id, a.author as author,
                  c.id as category_id, c.category as category
                  FROM quotes q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE 1 = 1'; 
        
        // Add conditions based on the provided author_id and category_id
        if ($author_id !== null) {
            $query .= ' AND q.author_id = :author_id';
        }
        if ($category_id !== null) {
            $query .= ' AND q.category_id = :category_id';
        }
    
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters if they are provided
        if ($author_id !== null) {
            $stmt->bindParam(':author_id', $author_id);
        }
        if ($category_id !== null) {
            $stmt->bindParam(':category_id', $category_id);
        }
    
        $stmt->execute();
    
        return $stmt;
    }

    public function read_single() {
        $query = 'SELECT q.id, q.quote, q.author_id, q.category_id,
                        a.author as author, c.category as category
                        FROM ' . $this->table . ' q
                        LEFT JOIN authors a ON q.author_id = a.id
                        LEFT JOIN categories c ON q.category_id = c.id
                        WHERE q.id = ?
                        LIMIT 1 OFFSET 0';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->id = $row['id'];
            $this->quote = $row['quote'];
            $this->author_id = $row['author_id'];
            $this->category_id = $row['category_id'];
            $this->author = $row['author'];
            $this->category = $row['category'];
        } else {
            echo json_encode(array('message' => 'No Quotes Found'));
        }
    }

    // Create Quote
    public function create() {
        // Check if author_id and category_id exist
        if (!$this->authorExists($this->author_id)) {
            echo json_encode(array('message' => 'author_id Not Found'));
            return false;
        }

        if (!$this->categoryExists($this->category_id)) {
            echo json_encode(array('message' => 'category_id Not Found'));
            return false;
        }

        // Check if quote is empty
        if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
            echo json_encode(array('message' => 'Missing Required Parameters'));
            return false;
        }

        // Proceed with inserting the quote
        $query = 'INSERT INTO ' . $this->table . ' 
        (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';

        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            $result = array(
                'id' => $this->conn->lastInsertId(),
                'quote' => $this->quote,
                'author_id' => $this->author_id,
                'category_id' => $this->category_id
            );
            return $result;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;

    }
    
    private function authorExists($author_id) {
        // Check if author exists in the database
        $query = 'SELECT id FROM authors WHERE id = :author_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_id', $author_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    private function categoryExists($category_id) {
        // Check if category exists in the database
        $query = 'SELECT id FROM categories WHERE id = :category_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    
    public function update() {
        // Check if author_id exists
        if (!$this->authorExists($this->author_id)) {
            echo json_encode(array('message' => 'author_id Not Found'));
            return false;
        }

        // Check if category_id exists
        if (!$this->categoryExists($this->category_id)) {
            echo json_encode(array('message' => 'category_id Not Found'));
            return false;
        }

        // Check if required fields are provided
        if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
            echo json_encode(array('message' => 'Missing Required Parameters'));
            return false;
        }

        $query = 'UPDATE ' . $this->table . '
                        SET quote = :quote, author_id = :author_id, category_id = :category_id
                        WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }


    public function delete() {
        // Check if quote ID is provided
        if (!isset($this->id)) {
            echo json_encode(array('message' => 'Missing quote ID'));
            return false;
        }
        // Check if quote with the given ID exists
        $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':id', $this->id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() == 0) {
            // quote with the provided ID does not exist
            //echo json_encode(array('message' => 'No Quotes Found'));
            return false;
        }
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            //$result = array('id' => $this->id);
            //return $result;
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}
?>
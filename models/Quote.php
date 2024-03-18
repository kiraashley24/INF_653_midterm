<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id; 
    public $category_id; 
    public $author_name;
    public $category_name;
    public $author;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($author_id = null, $category_id = null) {
        $query = 'SELECT q.id, q.quote, 
                  a.id as author_id, a.author as author_name,
                  c.id as category_id, c.category as category_name
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
                        a.author as author_name, c.category as category_name
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
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
        } else {
            echo json_encode(array('message' => 'No Quotes Found'));
        }
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . '
                    SET quote = :quote, author_id = :author_id, category_id = :category_id';

        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id)); 
        $this->category_id = htmlspecialchars(strip_tags($this->category_id)); 

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    public function update() {
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
?>

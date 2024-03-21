<?php
class Author {
    // DB stuff
    private $conn;
    private $table = 'authors';

    // Author properties
    public $id;
    public $author;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }
    // Get authors
    public function read() {
      // Create query
      $query = 'SELECT id, author
                FROM ' . $this->table . '
                ORDER BY id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Author
    public function read_single() {
      // Create query
      $query = 'SELECT id, author
                  FROM ' . $this->table . '
                  WHERE id = ?
                  LIMIT 1 OFFSET 0';
  
      // Prepare statement
      $stmt = $this->conn->prepare($query);
  
      // Bind ID
      $stmt->bindParam(1, $this->id);
  
      // Execute query
      $stmt->execute();
  
      // Fetch the row
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
      // Check if row is valid
      if ($row) {
          // Set properties
          $this->id = $row['id'];
          $this->author = $row['author'];
      } else {
          // Author not found
          $this->id = null;
          $this->author = null;
      }
  }
  

    // Create Author
    public function create() {
      $query = 'INSERT INTO ' .
          $this->table . '
          (author)
          VALUES
          (:author)';

      $stmt = $this->conn->prepare($query);
      $this->author = htmlspecialchars(strip_tags($this->author));
      $stmt->bindParam(':author', $this->author);

      if ($stmt->execute()) {
        $result = array(
            'id' => $this->conn->lastInsertId(),
            'author' => $this->author
        );
        return $result;
    }

      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update Author
    public function update() {
      // Check if author ID is provided
      if (!isset($this->id)) {
          echo json_encode(array('message' => 'Missing author ID'));
          return false;
      }
  
      // Check if author name is provided
      if (!isset($this->author)) {
          echo json_encode(array('message' => 'Missing Required Parameters'));
          return false;
      }
  
      // Check if author with the given ID exists
      $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
      $check_stmt = $this->conn->prepare($check_query);
      $check_stmt->bindParam(':id', $this->id);
      $check_stmt->execute();
  
      if ($check_stmt->rowCount() == 0) {
          // author with the provided ID does not exist
          echo json_encode(array('message' => 'Author with id ' . $this->id . ' not found'));
          return false;
      }
  
      // author exists, proceed with the update
      $query = 'UPDATE ' . $this->table . '
                  SET author = :author
                  WHERE id = :id';
  
      $stmt = $this->conn->prepare($query);
      $this->author = htmlspecialchars(strip_tags($this->author));
      $this->id = htmlspecialchars(strip_tags($this->id));
      $stmt->bindParam(':author', $this->author);
      $stmt->bindParam(':id', $this->id);
  
      if ($stmt->execute()) {
        $result = array(
            'id' => $this->id,
            'author' => $this->author
        );
        return $result;
    }
  
      printf("Error: %s.\n", $stmt->error);
  
      return false;
    }

    // Delete Author
    public function delete() {
      // Check if author ID is provided
      if (!isset($this->id)) {
          echo json_encode(array('message' => 'Missing author ID'));
          return false;
      }
      // Check if author with the given ID exists
      $check_query = 'SELECT id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
      $check_stmt = $this->conn->prepare($check_query);
      $check_stmt->bindParam(':id', $this->id);
      $check_stmt->execute();

      if ($check_stmt->rowCount() == 0) {
          // author with the provided ID does not exist
          echo json_encode(array('message' => 'No Quotes Found'));
          return false;
      }
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      $stmt = $this->conn->prepare($query);
      $this->id = htmlspecialchars(strip_tags($this->id));
      $stmt->bindParam(':id', $this->id);

      if ($stmt->execute()) {
        $result = array(
            'id' => $this->id,
            'author' => $this->author
        );
        return $result;
    }

      printf("Error: %s.\n", $stmt->error);

      return false;
  }
}

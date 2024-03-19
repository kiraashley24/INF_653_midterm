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
      $query = 'SELECT
        id,
        author
      FROM
        ' . $this->table . '
      ORDER BY
        id DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Author
    public function read_single() {
      // Create query
      $query = 'SELECT
          id,
          author
        FROM
          ' . $this->table . '
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
      $this->author = $row['author'];
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
          return true;
      }

      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update Author
    public function update() {
      // Create Query
      $query = 'UPDATE ' .
        $this->table . '
      SET
        author = :author
      WHERE
        id = :id';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->author = htmlspecialchars(strip_tags($this->author));
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt->bindParam(':author', $this->author);
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Delete Author
    public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind Data
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
}

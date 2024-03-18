<?php
    class Database {
        // DB Parameters
        private $host;
        private $port;
        private $db_name;
        private $username;
        private $password;
        private $conn;
    
        public function __construct() {
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->db_name = getenv('DBNAME');
            $this->host = getenv('HOST');
            $this->port = getenv('PORT');
        }
        
        //DB Connect
        public function connect() {
    
            // instead of $this->conn = null;
            if ($this->conn) {
                //connection already exists, return it
                return $this->conn;
            } else {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            }
            
    
            try {
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch(PDOException $e) {
                // echo for tutorial, but log the error for production
                echo 'Connection Error: ' . $e->getMessage();
            }
        }
    }
    
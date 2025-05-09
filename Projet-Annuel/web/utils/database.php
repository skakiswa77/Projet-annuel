<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'business_care';
    private $username = 'root';
    private $password = 'root';
    private $conn;
    private static $instance = null;
    
 
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Exécute une requête SQL et retourne les résultats
     * @param string $query Requête SQL
     * @param array $params Paramètres de la requête
     * @param bool $single Retourner un seul résultat ou tous
     * @return mixed Résultat(s) de la requête
     */
    public function query($query, $params = [], $single = false) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            return $single ? $stmt->fetch() : $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erreur d'exécution de la requête: " . $e->getMessage());
        }
    }
    
    /**
     * Exécute une requête SQL sans retourner de résultats
     * @param string $query Requête SQL
     * @param array $params Paramètres de la requête
     * @return int Nombre de lignes affectées
     */
    public function execute($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur d'exécution de la requête: " . $e->getMessage());
        }
    }
    
    /**
     * Insère des données dans une table
     * @param string $table Nom de la table
     * @param array $data Données à insérer (associatif)
     * @return int ID de la dernière insertion
     */
    public function insert($table, $data) {
       
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_values($data));
            
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("Erreur d'insertion: " . $e->getMessage());
        }
    }
    
    /**
     * Met à jour des données dans une table
     * @param string $table Nom de la table
     * @param array $data Données à mettre à jour (associatif)
     * @param string $where Condition WHERE
     * @param array $params Paramètres pour la condition WHERE
     * @return int Nombre de lignes affectées
     */
    public function update($table, $data, $where, $params = []) {
        
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $setClause = implode(', ', $set);
        
        $query = "UPDATE $table SET $setClause WHERE $where";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_merge(array_values($data), $params));
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur de mise à jour: " . $e->getMessage());
        }
    }
    
    /**
     * Supprime des données d'une table
     * @param string $table Nom de la table
     * @param string $where Condition WHERE
     * @param array $params Paramètres pour la condition WHERE
     * @return int Nombre de lignes affectées
     */
    public function delete($table, $where, $params = []) {
        $query = "DELETE FROM $table WHERE $where";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            die("Erreur de suppression: " . $e->getMessage());
        }
    }
    
   
    public function beginTransaction() {
        $this->conn->beginTransaction();
    }
    
    public function commit() {
        $this->conn->commit();
    }
    
    public function rollback() {
        $this->conn->rollBack();
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
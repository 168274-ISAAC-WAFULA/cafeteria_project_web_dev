<?php
require_once 'database.php';

class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Find user by username
    public function findUserByUsername($username){
        $this->db->query('SELECT * FROM users WHERE username = :username');
        // Bind value
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

    // Register user
    public function register($data){
        $this->db->query('INSERT INTO users (username, password, role) VALUES(:username, :password, :role)');
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($username, $password){
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)){
            return $row;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Get all users
    public function getUsers(){
        $this->db->query('SELECT * FROM users');
        $results = $this->db->resultSet();

        return $results;
    }

    // Update user
    public function updateUser($data){
        $this->db->query('UPDATE users SET username = :username, role = :role WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':role', $data['role']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Delete user
    public function deleteUser($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        // Bind value
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}

<?php
require_once 'database.php';

class Order {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Add order
    public function addOrder($data){
        $this->db->query('INSERT INTO orders (user_id, menu_item_id, status) VALUES(:user_id, :menu_item_id, :status)');
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':menu_item_id', $data['menu_item_id']);
        $this->db->bind(':status', $data['status']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get all orders
    public function getOrders(){
        $this->db->query('SELECT * FROM orders');
        $results = $this->db->resultSet();

        return $results;
    }

    // Get orders by user ID
    public function getOrdersByUserId($user_id){
        $this->db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY timestamp DESC');
        $this->db->bind(':user_id', $user_id);
        $results = $this->db->resultSet();

        return $results;
    }

    // Get order by ID
    public function getOrderById($id){
        $this->db->query('SELECT * FROM orders WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Update order
    public function updateOrder($data){
        $this->db->query('UPDATE orders SET user_id = :user_id, menu_item_id = :menu_item_id, status = :status WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':menu_item_id', $data['menu_item_id']);
        $this->db->bind(':status', $data['status']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Delete order
    public function deleteOrder($id){
        $this->db->query('DELETE FROM orders WHERE id = :id');
        // Bind value
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get last inserted ID
    public function getLastInsertedId() {
        return $this->db->lastInsertId();
    }
}

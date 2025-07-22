<?php
require_once 'database.php';

class OrderQueue {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Add order to queue
    public function addOrderToQueue($order_id){
        $this->db->query('INSERT INTO order_queue (order_id) VALUES(:order_id)');
        // Bind value
        $this->db->bind(':order_id', $order_id);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get all orders in queue
    public function getOrderQueue(){
        $this->db->query('SELECT 
                            oq.id as queue_id, 
                            o.id as order_id, 
                            u.username, 
                            mi.name as item_name, 
                            o.status, 
                            oq.timestamp 
                          FROM 
                            order_queue oq
                          JOIN 
                            orders o ON oq.order_id = o.id
                          JOIN 
                            users u ON o.user_id = u.id
                          JOIN 
                            menu_items mi ON o.menu_item_id = mi.id
                          ORDER BY 
                            oq.timestamp ASC');
        $results = $this->db->resultSet();

        return $results;
    }

    // Get order in queue by ID
    public function getOrderQueueById($id){
        $this->db->query('SELECT * FROM order_queue WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Delete order from queue
    public function deleteOrderFromQueue($id){
        $this->db->query('DELETE FROM order_queue WHERE id = :id');
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

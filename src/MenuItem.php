<?php
require_once 'database.php';

class MenuItem {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Add menu item
    public function addMenuItem($data){
        $this->db->query('INSERT INTO menu_items (name, description, price, image, time_of_day) VALUES(:name, :description, :price, :image, :time_of_day)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':time_of_day', $data['time_of_day']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get all menu items
    public function getMenuItems(){
        $this->db->query('SELECT * FROM menu_items');
        $results = $this->db->resultSet();

        return $results;
    }

    // Get menu item by ID
    public function getMenuItemById($id){
        $this->db->query('SELECT * FROM menu_items WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Update menu item
    public function updateMenuItem($data){
        $this->db->query('UPDATE menu_items SET name = :name, description = :description, price = :price, image = :image, time_of_day = :time_of_day WHERE id = :id');
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':time_of_day', $data['time_of_day']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Delete menu item
    public function deleteMenuItem($id){
        $this->db->query('DELETE FROM menu_items WHERE id = :id');
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

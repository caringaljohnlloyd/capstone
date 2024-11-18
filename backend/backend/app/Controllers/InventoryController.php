<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\InventoryModel;

class InventoryController extends ResourceController
{
      // Display all inventory items
    public function index()
    {
        $model = new InventoryModel();

        // Get all inventory items
        $inventory = $model->findAll();

        if ($inventory) {
            // Return a JSON response with inventory data
            return $this->respond($inventory, 200);
        } else {
            // If no inventory is found, return a 404 error
            return $this->failNotFound('No inventory items found');
        }
    }
    // Store a new inventory item
    public function store()
    {
        $json = $this->request->getJSON();
    
        // Validate the received data
        if (!isset($json->item_name) || !isset($json->quantity)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Missing required fields'
            ], 400); // Return error for missing fields
        }
    
        $data = [
            'item_name' => $json->item_name,
            'quantity'  => $json->quantity,
        ];
    
        try {
            $model = new InventoryModel();
    
            // Attempt to insert the new item into the database
            if (!$model->save($data)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Failed to add inventory item',
                    'errors' => $model->errors()
                ], 400); // Return error response if save fails
            }
    
            return $this->respond([
                'status' => 'success',
                'message' => 'Inventory item added successfully!'
            ], 200); // Success response
    
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            log_message('error', 'Failed to add inventory item: ' . $e->getMessage());
    
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to add inventory item: ' . $e->getMessage()
            ], 500); // Return error if an exception occurs
        }
    }
    
    // Update the inventory item
    public function update($inventory_id = null)
    {
        $request = $this->request;
        $model = new InventoryModel();
    
        // Find the existing data by ID
        $existingData = $model->find($inventory_id);
    
        // Return error if the inventory item is not found
        if (empty($existingData)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Inventory item not found'
            ], 404); // Return 404 if the record is not found
        }
    
        // Prepare new data, using existing values if no new values are provided
        $data = [
            'inventory_id' => $inventory_id,
            'item_name'    => $request->getVar('item_name') ?? $existingData['item_name'],
            'quantity'     => $request->getVar('quantity') ?? $existingData['quantity'],
        ];
    
        // Check if any data has actually changed
        if ($data === array_intersect_key($existingData, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'No changes detected, inventory item not updated'
            ], 200); // Return message if no changes were made
        }
    
        try {
            // Attempt to update the inventory item
            if ($model->update($inventory_id, $data)) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Inventory item updated successfully!'
                ], 200); // Success response
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Failed to update inventory item'
                ], 500); // Return error if update fails
            }
        } catch (\Exception $e) {
            // Log and return error message if an exception occurs
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update inventory item: ' . $e->getMessage()
            ], 500); // Return error response
        }
    }
    

    // Delete an inventory item
    public function delete($inventory_id = null)
    {
        $model = new InventoryModel();

        // Delete the inventory item
        if ($model->delete($inventory_id)) {
            return $this->respond(['status' => 'success', 'message' => 'Inventory item deleted successfully']);
        } else {
            return $this->respond('Inventory item not found');
        }
    }

}
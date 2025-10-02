<?php

namespace App\Models;

use App\Services\MongoDBService;

class Feedback
{
    protected $collection = 'feedbacks';
    
    protected $fillable = [
        '_id',
        'customer_id',
        'customer_name',
        'customer_email',
        'product_id',
        'product_name',
        'order_id',
        'rating',
        'feedback_text',
        'feedback_type',
        'status',
        'is_public',
        'admin_response',
        'admin_id',
        'created_at',
        'updated_at'
    ];

    protected $mongoService;
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->mongoService = new MongoDBService();
        $this->fill($attributes);
    }

    /**
     * Fill the model with attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        
        // Handle _id to id conversion
        if (isset($attributes['_id'])) {
            $this->attributes['_id'] = (string) $attributes['_id'];
        }
        
        return $this;
    }

    /**
     * Create a new feedback
     */
    public static function create(array $attributes = [])
    {
        $mongoService = new MongoDBService();
        $id = $mongoService->createFeedback($attributes);
        
        // Return a mock model instance with the ID
        $feedback = new static();
        $feedback->id = $id;
        $feedback->fill($attributes);
        
        return $feedback;
    }

    /**
     * Find feedback by ID
     */
    public static function find($id)
    {
        $mongoService = new MongoDBService();
        $data = $mongoService->getFeedbackById($id);
        
        if (!$data) {
            return null;
        }
        
        $feedback = new static();
        $feedback->id = $data['_id'];
        $feedback->fill($data);
        
        return $feedback;
    }

    /**
     * Update feedback
     */
    public function update(array $attributes = [])
    {
        $mongoService = new MongoDBService();
        $success = $mongoService->updateFeedback($this->id, $attributes);
        
        if ($success) {
            $this->fill($attributes);
        }
        
        return $success;
    }

    /**
     * Delete feedback
     */
    public function delete()
    {
        $mongoService = new MongoDBService();
        return $mongoService->deleteFeedback($this->id);
    }

    /**
     * Get all feedbacks with pagination
     */
    public static function paginate($perPage = 10, $page = 1)
    {
        $mongoService = new MongoDBService();
        $feedbacks = $mongoService->getFeedbacks($page, $perPage);
        
        // Convert arrays to Feedback objects
        $feedbackObjects = collect($feedbacks)->map(function($item) {
            return new static($item);
        });
        
        // Convert to a simple pagination-like structure
        return (object) [
            'data' => $feedbackObjects,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => count($feedbacks),
            'last_page' => ceil(count($feedbacks) / $perPage)
        ];
    }

    /**
     * Get feedbacks for a specific customer
     */
    public static function forCustomer($customerId, $perPage = 10, $page = 1)
    {
        $mongoService = new MongoDBService();
        $collection = $mongoService->getCollection('feedbacks');
        
        $skip = ($page - 1) * $perPage;
        $cursor = $collection->find(
            ['customer_id' => (string) $customerId],
            [
                'sort' => ['created_at' => -1],
                'skip' => $skip,
                'limit' => $perPage
            ]
        );

        $feedbacks = [];
        foreach ($cursor as $document) {
            $feedbacks[] = $mongoService->convertMongoDocumentToArray($document);
        }

        // Convert arrays to Feedback objects
        $feedbackObjects = collect($feedbacks)->map(function($item) {
            return new static($item);
        });

        return (object) [
            'data' => $feedbackObjects,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => count($feedbacks),
            'last_page' => ceil(count($feedbacks) / $perPage)
        ];
    }

    /**
     * Search feedbacks
     */
    public static function search($query, $perPage = 10, $page = 1)
    {
        $mongoService = new MongoDBService();
        $feedbacks = $mongoService->searchFeedbacks($query, $page, $perPage);
        
        // Convert arrays to Feedback objects
        $feedbackObjects = collect($feedbacks)->map(function($item) {
            return new static($item);
        });
        
        return (object) [
            'data' => $feedbackObjects,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => count($feedbacks),
            'last_page' => ceil(count($feedbacks) / $perPage)
        ];
    }

    /**
     * Get feedback statistics
     */
    public static function getStats()
    {
        $mongoService = new MongoDBService();
        return $mongoService->getFeedbackStats();
    }

    public function __get($key)
    {
        // Handle id property specifically
        if ($key === 'id' && isset($this->attributes['_id'])) {
            return (string) $this->attributes['_id'];
        }
        
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
        return null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get all attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get a specific attribute
     */
    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }
}

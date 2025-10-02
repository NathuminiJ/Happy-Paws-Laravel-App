<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;
use Exception;

class MongoDBService
{
    private $client;
    private $database;
    private $connectionString;

    public function __construct()
    {
        $this->connectionString = env('MONGODB_CONNECTION_STRING', 'mongodb+srv://username:password@cluster.mongodb.net/');
        $this->client = new Client($this->connectionString);
        $this->database = $this->client->selectDatabase('happy_paws_feedback');
    }

    /**
     * Get the MongoDB client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get the database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Get a collection
     */
    public function getCollection(string $collectionName): Collection
    {
        return $this->database->selectCollection($collectionName);
    }

    /**
     * Test the connection
     */
    public function testConnection(): bool
    {
        try {
            $this->database->command(['ping' => 1]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create a feedback document
     */
    public function createFeedback(array $data): string
    {
        $collection = $this->getCollection('feedbacks');
        $data['created_at'] = new \MongoDB\BSON\UTCDateTime();
        $data['updated_at'] = new \MongoDB\BSON\UTCDateTime();
        
        $result = $collection->insertOne($data);
        return (string) $result->getInsertedId();
    }

    /**
     * Get all feedbacks with pagination
     */
    public function getFeedbacks(int $page = 1, int $limit = 10): array
    {
        $collection = $this->getCollection('feedbacks');
        $skip = ($page - 1) * $limit;
        
        $cursor = $collection->find(
            [],
            [
                'sort' => ['created_at' => -1],
                'skip' => $skip,
                'limit' => $limit
            ]
        );

        $feedbacks = [];
        foreach ($cursor as $document) {
            $feedbacks[] = $this->convertMongoDocumentToArray($document);
        }

        return $feedbacks;
    }

    /**
     * Get feedback by ID
     */
    public function getFeedbackById(string $id): ?array
    {
        try {
            $collection = $this->getCollection('feedbacks');
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $document = $collection->findOne(['_id' => $objectId]);
            
            return $document ? $this->convertMongoDocumentToArray($document) : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Update feedback
     */
    public function updateFeedback(string $id, array $data): bool
    {
        try {
            $collection = $this->getCollection('feedbacks');
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $data['updated_at'] = new \MongoDB\BSON\UTCDateTime();
            
            $result = $collection->updateOne(
                ['_id' => $objectId],
                ['$set' => $data]
            );
            
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete feedback
     */
    public function deleteFeedback(string $id): bool
    {
        try {
            $collection = $this->getCollection('feedbacks');
            $objectId = new \MongoDB\BSON\ObjectId($id);
            
            $result = $collection->deleteOne(['_id' => $objectId]);
            return $result->getDeletedCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Search feedbacks
     */
    public function searchFeedbacks(string $query, int $page = 1, int $limit = 10): array
    {
        $collection = $this->getCollection('feedbacks');
        $skip = ($page - 1) * $limit;
        
        $filter = [
            '$or' => [
                ['customer_name' => ['$regex' => $query, '$options' => 'i']],
                ['feedback_text' => ['$regex' => $query, '$options' => 'i']],
                ['product_name' => ['$regex' => $query, '$options' => 'i']]
            ]
        ];

        $cursor = $collection->find(
            $filter,
            [
                'sort' => ['created_at' => -1],
                'skip' => $skip,
                'limit' => $limit
            ]
        );

        $feedbacks = [];
        foreach ($cursor as $document) {
            $feedbacks[] = $this->convertMongoDocumentToArray($document);
        }

        return $feedbacks;
    }

    /**
     * Get feedback statistics
     */
    public function getFeedbackStats(): array
    {
        $collection = $this->getCollection('feedbacks');
        
        $total = $collection->countDocuments();
        $positive = $collection->countDocuments(['rating' => ['$gte' => 4]]);
        $negative = $collection->countDocuments(['rating' => ['$lt' => 3]]);
        $neutral = $collection->countDocuments(['rating' => ['$gte' => 3, '$lt' => 4]]);
        
        return [
            'total' => $total,
            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,
            'average_rating' => $this->getAverageRating()
        ];
    }

    /**
     * Get average rating
     */
    private function getAverageRating(): float
    {
        $collection = $this->getCollection('feedbacks');
        
        $pipeline = [
            ['$group' => ['_id' => null, 'avgRating' => ['$avg' => '$rating']]]
        ];
        
        $result = $collection->aggregate($pipeline)->toArray();
        
        return $result ? round($result[0]['avgRating'], 2) : 0.0;
    }

    /**
     * Convert MongoDB document to array
     */
    public function convertMongoDocumentToArray($document): array
    {
        // Handle different MongoDB document types
        if (method_exists($document, 'toArray')) {
            $array = $document->toArray();
        } else {
            // Convert to array manually using iterator_to_array
            $array = iterator_to_array($document);
        }
        
        // Convert ObjectId to string
        if (isset($array['_id'])) {
            $array['_id'] = (string) $array['_id'];
        }
        
        // Convert UTCDateTime to string
        if (isset($array['created_at']) && $array['created_at'] instanceof \MongoDB\BSON\UTCDateTime) {
            $array['created_at'] = $array['created_at']->toDateTime()->format('Y-m-d H:i:s');
        }
        
        if (isset($array['updated_at']) && $array['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
            $array['updated_at'] = $array['updated_at']->toDateTime()->format('Y-m-d H:i:s');
        }
        
        return $array;
    }
}


<?php
class LocationSearch {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function searchWithinRadius($userLat, $userLng, $radius = 20, $filters = []) {
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                 cos(radians(longitude) - radians(?)) + 
                 sin(radians(?)) * sin(radians(latitude)))) AS distance
                FROM products p
                JOIN users u ON p.user_id = u.id
                JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1
                HAVING distance <= ?";
        
        $params = [$userLat, $userLng, $userLat, $radius];
        
        // Add filters
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition_type = ?";
            $params[] = $filters['condition'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY distance ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

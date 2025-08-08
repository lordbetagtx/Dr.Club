<?php
class User {
    private $db;
    private $table = 'users';
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function register($data) {
        $sql = "INSERT INTO {$this->table} 
                (email, password, first_name, last_name, mobile, whatsapp, 
                 facebook_profile, city, pincode, state, country, latitude, longitude) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $stmt->execute([
            $data['email'], $hashedPassword, $data['first_name'], $data['last_name'],
            $data['mobile'], $data['whatsapp'], $data['facebook_profile'],
            $data['city'], $data['pincode'], $data['state'], $data['country'],
            $data['latitude'], $data['longitude']
        ]);
    }
    
    public function login($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getUserProfile($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

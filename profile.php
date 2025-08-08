<?php
session_start();
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$userId = $_GET['id'] ?? $_SESSION['user_id'];

// Get user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's products
$sql = "SELECT * FROM products WHERE user_id = ? AND is_active = 1 ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute([$userId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get reviews
$sql = "SELECT r.*, u.first_name, u.last_name 
        FROM user_reviews r 
        JOIN users u ON r.reviewer_id = u.id 
        WHERE r.reviewed_user_id = ? 
        ORDER BY r.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute([$userId]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $user['first_name'] . ' ' . $user['last_name'] ?> - Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="<?= $user['profile_image'] ?: 'images/default-avatar.jpg' ?>" alt="Profile">
            </div>
            <div class="profile-info">
                <h1><?= $user['first_name'] . ' ' . $user['last_name'] ?></h1>
                <p class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    <?= $user['city'] . ', ' . $user['state'] ?>
                </p>
                <div class="contact-info">
                    <a href="mailto:<?= $user['email'] ?>" class="contact-btn email">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                    <?php if ($user['whatsapp']): ?>
                    <a href="https://wa.me/<?= $user['whatsapp'] ?>" class="contact-btn whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <?php endif; ?>
                    <?php if ($user['facebook_profile']): ?>
                    <a href="https://facebook.com/<?= $user['facebook_profile'] ?>" class="contact-btn facebook" target="_blank">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="profile-map">
                <div id="userLocationMap" style="height: 200px; width: 250px;"></div>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="section">
                <h2>All Listings (<?= count($products) ?>)</h2>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= json_decode($product['images'])[0] ?? 'images/no-image.jpg' ?>" alt="<?= $product['title'] ?>">
                        <div class="product-info">
                            <h3><?= $product['title'] ?></h3>
                            <p class="price">₹<?= $product['price'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="section">
                <h2>Reviews & Ratings</h2>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <strong><?= $review['first_name'] . ' ' . $review['last_name'] ?></strong>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'active' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p><?= $review['comment'] ?></p>
                        <small><?= date('M d, Y', strtotime($review['created_at'])) ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script>
        // Initialize Google Maps for user location
        function initUserLocationMap() {
            const userLat = <?= $user['latitude'] ?>;
            const userLng = <?= $user['longitude'] ?>;
            
            const map = new google.maps.Map(document.getElementById('userLocationMap'), {
                zoom: 15,
                center: { lat: userLat, lng: userLng }
            });
            
            new google.maps.Marker({
                position: { lat: userLat, lng: userLng },
                map: map,
                title: 'User Location'
            });
        }
        
        initUserLocationMap();
    </script>
</body>
</html>

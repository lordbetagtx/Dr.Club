<?php
require_once '../config/database.php';
require_once '../classes/LocationSearch.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();
$locationSearch = new LocationSearch($db);

$userLat = $_GET['lat'] ?? 0;
$userLng = $_GET['lng'] ?? 0;
$radius = $_GET['radius'] ?? 20;

$filters = [
    'category' => $_GET['category'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'condition' => $_GET['condition'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$products = $locationSearch->searchWithinRadius($userLat, $userLng, $radius, $filters);

echo json_encode([
    'success' => true,
    'products' => $products,
    'count' => count($products)
]);
?>


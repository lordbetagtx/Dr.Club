<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - Buy & Sell</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h2>Marketplace</h2>
            </div>
            <div class="nav-search">
                <input type="text" id="searchInput" placeholder="What are you looking for?">
                <select id="locationSelect">
                    <option value="">Select Location</option>
                    <!-- Populate with cities -->
                </select>
                <button id="searchBtn"><i class="fas fa-search"></i></button>
            </div>
            <div class="nav-links">
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <a href="post-ad.php" class="btn-primary">Post Free Ad</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="filters-section">
            <div class="filter-group">
                <label>Category:</label>
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <!-- Populate with categories -->
                </select>
            </div>
            <div class="filter-group">
                <label>Price Range:</label>
                <input type="number" id="minPrice" placeholder="Min Price">
                <input type="number" id="maxPrice" placeholder="Max Price">
            </div>
            <div class="filter-group">
                <label>Condition:</label>
                <select id="conditionFilter">
                    <option value="">Any Condition</option>
                    <option value="new">New</option>
                    <option value="like_new">Like New</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Distance:</label>
                <select id="radiusFilter">
                    <option value="5">Within 5km</option>
                    <option value="10">Within 10km</option>
                    <option value="20" selected>Within 20km</option>
                    <option value="50">Within 50km</option>
                </select>
            </div>
            <button id="applyFilters" class="btn-primary">Apply Filters</button>
        </div>

        <div class="products-grid" id="productsContainer">
            <!-- Products will be loaded here via AJAX -->
        </div>
        
        <div class="map-view" id="mapContainer" style="display:none;">
            <div id="map" style="height: 500px;"></div>
        </div>
        
        <div class="view-toggle">
            <button id="gridView" class="active">Grid View</button>
            <button id="mapView">Map View</button>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <script src="js/main.js"></script>
</body>
</html>
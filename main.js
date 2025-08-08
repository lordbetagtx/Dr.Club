$(document).ready(function() {
    let userLocation = {};
    
    // Get user's location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLocation.lat = position.coords.latitude;
            userLocation.lng = position.coords.longitude;
            loadProducts();
        });
    }
    
    // Load products based on filters
    function loadProducts() {
        const filters = {
            search: $('#searchInput').val(),
            category: $('#categoryFilter').val(),
            min_price: $('#minPrice').val(),
            max_price: $('#maxPrice').val(),
            condition: $('#conditionFilter').val(),
            radius: $('#radiusFilter').val(),
            lat: userLocation.lat,
            lng: userLocation.lng
        };
        
        $.ajax({
            url: 'api/search-products.php',
            method: 'GET',
            data: filters,
            success: function(response) {
                displayProducts(response.products);
            },
            error: function() {
                alert('Error loading products');
            }
        });
    }
    
    // Display products in grid
    function displayProducts(products) {
        let html = '';
        products.forEach(function(product) {
            html += `
                <div class="product-card" data-product-id="${product.id}">
                    <div class="product-image">
                        <img src="${product.images[0] || 'images/no-image.jpg'}" alt="${product.title}">
                        <span class="distance">${product.distance.toFixed(1)}km away</span>
                    </div>
                    <div class="product-info">
                        <h3>${product.title}</h3>
                        <p class="price">₹${product.price}</p>
                        <p class="location">${product.location_text}</p>
                        <div class="product-actions">
                            <button class="btn-chat" data-user-id="${product.user_id}">Chat</button>
                            <button class="btn-whatsapp" data-phone="${product.whatsapp}">WhatsApp</button>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#productsContainer').html(html);
    }
    
    // Initialize Google Maps
    function initMap() {
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: userLocation
        });
        
        // Add markers for products
        // Implementation for map markers
    }
    
    // Event handlers
    $('#applyFilters').click(function() {
        loadProducts();
    });
    
    $('#searchBtn').click(function() {
        loadProducts();
    });
    
    // View toggle
    $('#gridView').click(function() {
        $('#productsContainer').show();
        $('#mapContainer').hide();
        $(this).addClass('active');
        $('#mapView').removeClass('active');
    });
    
    $('#mapView').click(function() {
        $('#productsContainer').hide();
        $('#mapContainer').show();
        $(this).addClass('active');
        $('#gridView').removeClass('active');
        initMap();
    });
    
    // Chat button click
    $(document).on('click', '.btn-chat', function() {
        const userId = $(this).data('user-id');
        window.open(`chat.php?user=${userId}`, '_blank');
    });
    
    // WhatsApp button click
    $(document).on('click', '.btn-whatsapp', function() {
        const phone = $(this).data('phone');
        window.open(`https://wa.me/${phone}`, '_blank');
    });
});

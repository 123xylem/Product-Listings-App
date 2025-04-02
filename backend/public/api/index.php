<?php
// public/api/index.php\
require_once __DIR__ . '/../../src/bootstrap.php';
require_once __DIR__ . '/../../src/components/CorsHandler.php';
require_once __DIR__ . '/../../src/components/ApiResponse.php';

// Cors for all requests
CorsHandler::setHeaders();

// Exit earaly for preflights
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$router = new ApiRouter();

$router->addRoute('GET', '/', function () {
    return ApiResponse::success(
        [
            'name' => 'Listings API',
            'version' => '1.0.0',
            'endpoints' => [
                '/listings' => [
                    'GET' => 'Get all Listings',
                    'POST' => 'Create new listing'
                ],
                '/listings/{id}' => [
                    'GET' => 'Get listing by ID',
                    'PUT' => 'Edit Listing via ID',
                    'DELETE' => 'Delete listing by ID'
                ],
                // 'http://localhost/import_data.php?file={filename}' => 'Import data from an optionalspecific file (GET, replace {filename} with JSON file path)',
                // 'http://localhost/setup_database.php' => 'Setup database (GET)',
                // 'http://localhost/test_server.php' => 'Test server status (GET)',
            ]
        ],
        'API endpoints',
        200
    );
});

$router->addRoute('GET', '/listings', function () {
    $repository = new ListingRepository();
    $listings = $repository->getAlllistings();
    return ApiResponse::success(
        $listings,
        'Listings retrieved successfully',
        200
    );
});

// GET /listings/{id}
$router->addRoute('GET', '/listings/{id}', function ($params) {
    if (!isset($params['id'])) {
        return ApiResponse::error('listing ID is required', 400);
    }

    $listingId = $params['id'];
    if (!is_numeric($listingId)) {
        return ApiResponse::error('Invalid listing ID', 400);
    }

    $repository = new ListingRepository();
    $listing = $repository->getlisting($listingId);

    if (!$listing) {
        return ApiResponse::error('listing not found', 404);
    }

    return ApiResponse::success(
        $listing,
        'Listing retrieved successfully',
        200
    );
});

// POST /listings/{id}
$router->addRoute('POST', '/listings', function ($params) {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    // Validate required fields
    $requiredFields = ['title', 'description', 'price', 'category', 'date_posted', 'status'];
    foreach ($requiredFields as $field) {
        if (!isset($request[$field]) || empty($request[$field])) {
            return ApiResponse::error("Missing required field: $field");
        }
    }

    if (!is_numeric($request['price'])) {
        return ApiResponse::error('Price must be a number', 400);
    }

    $repository = new ListingRepository();
    $newListing = $repository->saveListing($request);

    if (!$newListing) {
        return ApiResponse::error('Failed to create listing', 500);
    }

    return ApiResponse::success(
        $newListing,
        'Listing created successfully',
        201
    );
});


$router->addRoute('PUT', '/listings/{id}', function ($params) {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    $listingId = $params['id'];
    if (!is_numeric($listingId)) {
        return ApiResponse::error('Invalid listing ID', 400);
    }
    if (isset($request['price']) && !is_numeric($request['price'])) {
        return ApiResponse::error('Price must be a number', 400);
    }

    $repository = new ListingRepository();
    $listing = $repository->updateListing($listingId, $request);

    if (!$listing) {
        return ApiResponse::error('listing not found', 404);
    }

    return ApiResponse::success(
        $listing,
        'Listing updated successfully',
        200
    );
});

$router->addRoute('DELETE', '/listings/{id}', function ($params) {
    $listingId = $params['id'];
    if (!is_numeric($listingId)) {
        return ApiResponse::error('Invalid listing ID', 400);
    }
    $repository = new ListingRepository();
    $result = $repository->deleteListing($listingId);
    if (!$result) {
        return ApiResponse::error('Listing not found or could not be deleted', 404);
    }

    return ApiResponse::success(
        $listingId,
        'Listing deleted successfully',
        200
    );
});


// Run the router
$router->run();

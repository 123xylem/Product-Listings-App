<?php
// public/import_data.php
require_once '../src/bootstrap.php';


// Import data
$importer = new DataImporter('../data/sample_data.json');
$importer->import();
$listingInfo = $importer->getListingsInfo();

// Instantiate to database Repository
$repository = new ListingRepository();

// Save Listing to db
foreach ($listingInfo as $listing) {
  $listingId = $repository->saveListing($listing);
}

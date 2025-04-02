<?php
// public/test_importer.php
require_once '../src/bootstrap.php';

try {
  $importer = new DataImporter('../data/sample_data.json');
  $data = $importer->import();

  echo "<h1>Listing Information</h1>";
  $listingInfo = $importer->getListingsInfo();
  echo "<pre>";
  print_r($listingInfo);
  echo "</pre>";
} catch (Exception $e) {
  echo "<h1>Error</h1>";
  echo "<p>{$e->getMessage()}</p>";
}

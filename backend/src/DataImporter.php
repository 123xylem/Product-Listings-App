<?php
// src/DataImporter.php

class DataImporter
{
  private $dataFile;
  private $listingData = [];

  public function __construct($dataFile)
  {
    $this->dataFile = $dataFile;
  }

  public function import()
  {

    $content = file_get_contents($this->dataFile);
    $data = json_decode($content, true);

    $this->listingData = $data;

    return $this->listingData;
  }

  public function getListingsInfo()
  {
    // Handle array of listings
    return array_map(function ($listing) {
      return [
        'title' => $listing['title'] ?? null,
        'description' => $listing['description'] ?? null,
        'price' => $listing['price'] ?? null,
        'category' => $listing['category'] ?? null,
        'status' => $listing['status'] ?? null
      ];
    }, $this->listingData);
  }
}

<?php
// src/ListingRepository.php


class listingRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function saveListing($listingData)
  {
    $sql = "INSERT INTO listings ( title, description, price, category, date_posted, status) 
            VALUES (:title, :description, :price, :category, :date_posted, :status);";
    $params = [
      ':title' => $listingData['title'],
      ':description' => $listingData['description'],
      ':date_posted' => $listingData['date_posted'],
      ':price' => $listingData['price'],
      ':category' => $listingData['category'],
      ':status' => $listingData['status'],
    ];
    $this->db->query($sql, $params);
    return $listingData;
  }

  public function getAllListings()
  {
    $sql = "SELECT * FROM listings";
    return $this->db->fetchAll($sql);
  }

  public function getListing($listingId)
  {
    $sql = "SELECT * FROM listings WHERE id = :id";
    $params = [':id' => $listingId];
    return $this->db->fetch($sql, $params);
  }

  public function updateListing($listingId, $listingData)
  {
    // First, get current listing data to use as defaults
    $currentListing = $this->getListing($listingId);
    if (!$currentListing) {
      return false;
    }

    // init params of listing 
    $params = [
      ':id' => $listingId,
      ':title' => $currentListing['title'],
      ':description' => $currentListing['description'],
      ':price' => $currentListing['price'],
      ':category' => $currentListing['category'],
      ':date_posted' => $currentListing['date_posted'],
      ':status' => $currentListing['status']
    ];

    // Override with new values if provided
    if (isset($listingData['title'])) $params[':title'] = $listingData['title'];
    if (isset($listingData['description'])) $params[':description'] = $listingData['description'];
    if (isset($listingData['price'])) $params[':price'] = $listingData['price'];
    if (isset($listingData['category'])) $params[':category'] = $listingData['category'];
    if (isset($listingData['date_posted'])) $params[':date_posted'] = $listingData['date_posted'];
    if (isset($listingData['status'])) $params[':status'] = $listingData['status'];

    $sql = "UPDATE listings 
            SET title = :title,
                description = :description, 
                price = :price,
                category = :category,
                date_posted = :date_posted,
                status = :status
            WHERE id = :id";

    $updated = $this->db->query($sql, $params);
    if (!$updated) {
      return false;
    }
    return $currentListing['title'];
  }

  public function deleteListing($listingId)
  {
    $listing = $this->getListing($listingId);
    if (!$listing) {
      return false;
    }

    $sql = "DELETE FROM listings WHERE id = :id";
    $params = [':id' => $listingId];

    try {
      $this->db->query($sql, $params);
      return $listing['title'];
    } catch (Exception $e) {
      return false;
    }
  }
}

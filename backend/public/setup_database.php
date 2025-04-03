<?php
// public/setup_database.php

require_once '../src/bootstrap.php';

try {
  $db = Database::getInstance();
  $pdo = $db->getPdo();

  // Create Listings table
  $pdo->exec("
        CREATE TABLE IF NOT EXISTS listings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            category VARCHAR(50) NOT NULL,
            date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(20) NOT NULL
        )
    ");



  echo "<h1>Database Setup Complete</h1>";
  echo "<p>The following tables were created:</p>";
  echo "<ul>";
  echo "<li>Listings</li>";
  echo "</ul>";
} catch (Exception $e) {
  echo "<h1>Error</h1>";
  echo "<p>{$e->getMessage()}</p>";
}

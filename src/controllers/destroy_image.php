<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include(BASE_DIR . "/src/models/offer.php");

    // Grab the offer
    $offer_id = $_GET['id'];
    $offer = findOffer($offer_id);
    if (!$offer) {
      throw new Exception("Cette proposition n'existe pas!", 404);
    }

    // Check if authenticated user is the owner
    if ($current_user['username'] != $offer['author_username']) {
      throw new Exception("Vous n'êtes pas l'auteur de cette proposition!", 403);
    }
    
    $image = $_GET['image'];
    
    // Does this image belong to the offer?
    if (!in_array($image, $offer['images'])) {
      throw new Exception("Cette image n'existe pas dans la proposition!", 404);
    }

    // Destroy the image
    if (!unlink(BASE_DIR . $image)) {
      throw new Exception("La suppression de cette image a échouée.", 500);
    }

    header("Location: " . BASE_URL ."?page=update_offer&id=" . $offer_id);
    exit;
  }

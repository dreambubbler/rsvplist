<?php

/**
 * @file
 * Contains the RSVP Enabler service.
 */

namespace Drupal\rsvplist;


use Drupal\Core\Database\Database;
use Drupal\jsonapi\JsonApiResource\Data;
use Drupal\node\Entity\Node;
use Exception;

class EnablerService {

  public function __construct() {

  }

  /**
   * Sets an individual node to be RSVP enabled.
   *
   * @param Node $node
   * @throws Exception
   */
  public function setEnabled(Node $node) {
//    try {
      \Drupal::messenger()->addMessage('in setEnabled in service.');
      if ($this->isEnabled($node)) {
        $insert = Database::getConnection()->insert('rsvplist_enabled');
        $insert->fields(['nid']);
        $insert->values([$node->id()]);
        $insert->execute();
      }
//    }
//    catch (Exception $e) {
//      \Drupal::messenger()->addError(t('Unable to save RSVP settings at this time due to database error. Please try again.'));
//    }
  }

  /**
   * Checks if an individual node is RSVP enabled.
   *
   * @param Node $node
   * @return bool
   *  whether or not the node is enabled for the RSVP functionality.
   */
  public function isEnabled(Node &$node) {
    if ($node->isNew()) {
      return FALSE;
    }
    $select = Database::getConnection()->select('rsvplist_enabled', 're');
    $select->fields(['nid']);
    $select->condition('nid', $node->id());
    $results = $select->execute();

    return !(empty($results->fetchCol()));
  }

  /**
   * Deletes enabled settings for an individual node.
   *
   * @param Node $node
   */
  public function delEnabled(Node $node) {
    $delete = Database::getConnection()->delete('rsvplist_enabled');
    $delete->condition('nid', $node->id());
    $delete->execute();
  }
}

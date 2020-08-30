<?php

/**
 * @file
 * Creates a block which displays the the RSVPForm contained in RSVPForm.php
 */

namespace Drupal\rsvplist\Plugin\Block;

use Drupal\Core\Block\BlockBase;

// Use code below for exercise 03_11
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
// end 03_11

/**
 * Provides the RSVP main block.
 *
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("The RSVP Block")
 * )
 */
class RSVPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Use this code for exercise 03_10_Adding a Block
    /*return [
      '#type' => 'markup',
      '#markup' => $this->t('My RSVP List Block'),
    ];*/
    // end 03_10_Adding a Block

    // Use this code for exercise 03_11 Setting Block Permissions
    return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
    // end 03_11

  }

  // Add this method for exercise 03_11

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->id(); //$nid = $node->nid->value;
    if (is_numeric($nid)) {
      return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
    }
    else {
      return AccessResult::forbidden();
    }
  }

}

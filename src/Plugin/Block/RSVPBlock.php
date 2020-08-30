<?php

/**
 * @file
 * Creates a block which displays the the RSVPForm contained in RSVPForm.php
 */

namespace Drupal\rsvplist\Plugin\Block;


use Drupal\Core\Block\BlockBase;


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
  public function build()
  {
    // Use this code for exercise 03_10_Adding a Block
    return [
      '#type' => 'markup',
      '#markup' => $this->t('My RSVP List Block'),
    ];
    // end 03_10 03_10_Adding a Block
  }
}

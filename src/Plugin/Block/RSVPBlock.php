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
 *   admin_label = @Translation("RSVP Block")
 * )
 */
class RSVPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('My RSVP List Block'),
    ];
  }
}

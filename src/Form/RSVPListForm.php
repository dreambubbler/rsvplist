<?php

/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPListForm
 */

use Drupal\Core\Form\FormBase;

namespace \Drupal\rsvplist\Form;


class RSVPListForm extends FormBase {

  /**
   * @return string|void
   */
  public function getFormId()
  {
    return 'rsvplist_email_form';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;

    // Dallas modified version.
    // This code is from: https://www.purencool.digital/development/how-to-i-access-an-entity-eg-node-field-in-drupal8
    if(\Drupal::routeMatch()->getParameter('node')){
      $nid = \Drupal::routeMatch()->getParameter('node');
      $node = \Drupal\node\Entity\Node::load($nid->id());
      return $node->get($fieldName)->getValue();
    }
    return NULL;
  }
}

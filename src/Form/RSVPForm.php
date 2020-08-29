<?php

/**
 * @file
 * A form to collect an email address.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class RSVPForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'rsvplist_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;

/*    // Dallas modified version for getting $nid below.
    // This code is from: https://www.purencool.digital/development/how-to-i-access-an-entity-eg-node-field-in-drupal8
    if(\Drupal::routeMatch()->getParameter('node')){
      $nid = \Drupal::routeMatch()->getParameter('node');
      $node = \Drupal\node\Entity\Node::load($nid->id());
      return $node->get($fieldName)->getValue();
    }
    return NULL;*/
    $form['email'] = [
      '#title' => t('Email address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t("We will send updates to the email address you provide."),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('RSVP'),
    ];

    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if (\Drupal::service('email.validator')->isValid($value)) {
      $form_state->setErrorByName('email', $this->t('The email address %mail is not valid',
                                                    ['%mail' => $value]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $this->messenger()->addMessage(t("The form is working. The user entered @entry.",
                                  ['@entry' => $email]));
  }
}

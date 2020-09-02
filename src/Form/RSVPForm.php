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
    $nid = $node->id();

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
      '#type' => 'textfield',  // Could use an 'email' field type here.
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
    if ( !(\Drupal::service('email.validator')->isValid($value)) ) {
      $form_state->setErrorByName('email', $this->t('The email address %mail is not valid',
                                                    ['%mail' => $value]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // Use this code for video 03_04_Building an RSVP Form
    //    $email = $form_state->getValue('email');
    //    $this->messenger()->addMessage(t("The form is working. The user entered @entry.",
    //                                  ['@entry' => $email]));

    // Use below code for videos 03_08 onward.

    // Get current user ID.
    $uid = \Drupal::currentUser()->id();

    // Demonstration for how to load a full user object of the current user. This variable is not needed for this code,
    // but is shown for demonstration purposes.
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

    // Establish the required variables to eventually insert into the database.
    $email = $form_state->getValue('email');
    $nid = $form_state->getValue('nid');
    $current_time = time();


    // Begin to build a query builder object $query.
    // https://www.drupal.org/docs/8/api/database-api/insert-queries
    $query = \Drupal::database()->insert('rsvplist');

    // Specify the fields that the query will insert into.
    $query->fields([
      'mail',
      'nid',
      'uid',
      'created',
    ]);

    // Set the values of the fields we selected. Note that they must be in the same order as we defined them
    // in the $query->fields([...]) above.
    $query->values([
      $email,
      $nid,
      $uid,
      $current_time,
    ]);

    // Execute the query! Drupal will handle the correct syntax of the query automatically!
    $query->execute();

    // Provide the form submitter a nice message.
    $this->t('Thank you for your RSVP, you are on the list for the event!');
  }
}

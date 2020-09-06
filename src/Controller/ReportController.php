<?php

/**
 * @file
 * Provide site administrators with a list of all the RSVP List signups so they know who is attending their events.
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use MongoDB\Driver\Exception\Exception;

// use Exception;


class ReportController extends ControllerBase {

  protected function load() {
    try {
      throw new \Exception('on purpose :P');
      // https://www.drupal.org/docs/8/api/database-api/dynamic-queries/introduction-to-dynamic-queries
      $database = \Drupal::database();
      $select = $database->select('rsvplist', 'r');

      // Join the user table, so we can get the entry creator's username.
      $select->join('users_field_data', 'u', 'r.uid = u.uid');

      // Join the node table, so we can get the event's name.
      $select->join('node_field_data', 'n', 'r.nid = n.nid');

      // Select these specific fields for the output.
      $select->addField('u', 'name', 'username');
      $select->addField('n', 'title');
      $select->addField('r', 'mail');

      // Note that fetchAll() and fetchAllAssoc() will by default fetch using whatever fetch mode was set
      // on the query (numeric array, associative array, or object).
      // That can be modified by passing in a new fetch mode constant. For fetchAll(), it is the first parameter.
      // https://www.drupal.org/docs/8/api/database-api/result-sets
      // https://www.php.net/manual/en/pdostatement.fetch.php
      $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

      // Return the associative array of RSVPList entries.
      return $entries;
    }
    catch (\Exception $e) {
      // Display a user-friendly error.
      \Drupal::messenger()->addStatus(t('Unable to access the database at this time. Please try again later.'));
      return NULL;
    }
  }

  public function report() {
    $content = [];

    // https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!theme.api.php/group/theme_render/9.0.x

    $content['message'] = [
      '#markup' => t('Below is a list of all Event RSVPs including username, email address
                             and the name of the event they will be attending.'),
    ];

    $headers = [
      t('Username'),
      t('Event'),
      t('Email'),
    ];

    $rows = [];
    // Load the entries using the above load() method.
    $entries = $this->load();

    // Go through each entry and add it to $rows. Ultimately each array will be rendered as a row in
    // an HTML table.
    foreach ($entries as $entry) {
      $rows[] = $entry;
    }

    // Create the render array for rendering an HTML table.
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    ];

    // Do not cache this page by setting the max-age to 0.
    $content['#cache']['max-age'] = 0;

    // Return the populated render array.
    return $content;
  }
}

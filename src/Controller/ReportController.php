<?php

/**
 * @file
 * Provide site administrators with a list of all the RSVP List signups so they know who is attending their events.
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;


class ReportController extends ControllerBase {

  protected function load() {
    // https://www.drupal.org/docs/8/api/database-api/dynamic-queries/introduction-to-dynamic-queries
    $select = Database::getConnection()->select('rsvplist', 'r');

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

    return $entries;
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

    foreach ($entries as $entry) {
      $rows[] = $entry;
    }

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    ];

    // Do not cache this page.
    $content['#cache']['max-age'] = 0;

    return $content;
  }
}

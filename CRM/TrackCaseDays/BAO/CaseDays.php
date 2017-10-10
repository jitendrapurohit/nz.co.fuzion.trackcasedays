<?php

class CRM_TrackCaseDays_BAO_CaseDays {

  /**
   * Calculate and update active days for the case.
   */
  public static function calculateOpenDays() {
    $values = self::getCasesAndCustomFields(array('Open', 'Closed'), 'Days_Open');
    extract($values);
    if (empty($cases['count'])) {
      return;
    }

    foreach ($cases['values'] as $caseVal) {
      //Count number of days a case is opened.
      $from = time();
      if (!empty($caseVal['end_date'])) {
        $from = strtotime($caseVal['end_date']);
      }
      $statusActivity = civicrm_api3('Activity', 'get', array(
        'sequential' => 1,
        'return' => array("activity_date_time"),
        'case_id' => $caseVal['id'],
        'activity_type_id' => "Change Case Status",
      ));
      $dateOpened = $caseVal['start_date'];
      if (!empty($statusActivity['values'])) {
        $dateOpened = $statusActivity['values'][0]['activity_date_time'];
      }
      $datediff = $from - strtotime($dateOpened);
      $daysOpen = floor($datediff / (60 * 60 * 24)) + 1;

      self::updateCustomValue($caseVal, $daysOpenTableName, $columnName, $daysOpen);
    }
  }

  /**
   * Calculate and update pending days for the case.
   */
  public static function calculatePendingDays() {
    $values = self::getCasesAndCustomFields(array("Pending"), 'Inactive_Days');
    extract($values);
    if (empty($cases['count'])) {
      return;
    }
    foreach ($cases['values'] as $caseVal) {
      //Count number of days a case is opened.
      $datediff = time() - strtotime($caseVal['start_date']);
      $daysOpen = floor($datediff / (60 * 60 * 24)) + 1;

      self::updateCustomValue($caseVal, $daysOpenTableName, $columnName, $daysOpen);
    }
  }

  /**
   * Update the value of the custom field.
   *
   * @param array $case
   * @param string $daysOpenTableName
   * @param string $columnName
   * @param int $daysOpen
   */
  public static function updateCustomValue($case, $daysOpenTableName, $columnName, $daysOpen) {
    $query = "SELECT id
      FROM {$daysOpenTableName['table_name']}
      WHERE entity_id = {$case['id']}";
    $dao = CRM_Core_DAO::executeQuery($query);

    //Insert/Update into custom table.
    if ($dao->fetch()) {
      $query = "UPDATE {$daysOpenTableName['table_name']}
        SET {$columnName['column_name']} = {$daysOpen}
        WHERE id={$dao->id}";
    }
    else {
      $query = "INSERT INTO {$daysOpenTableName['table_name']} (entity_id, {$columnName['column_name']}) VALUES
        ({$case['id']}, {$daysOpen})";
    }
    CRM_Core_DAO::executeQuery($query);
  }

  /**
   * Get case and custom field values.
   *
   * @param array $caseStatuses
   * @param string $cfName
   *
   * @return array
   */
  public static function getCasesAndCustomFields($caseStatuses, $cfName) {
    $cases = civicrm_api3('Case', 'get', array(
      'sequential' => 1,
      'return' => array("id", "start_date", "end_date"),
      'status_id' => array('IN' => $caseStatuses),
      'options' => array('limit' => 0),
    ));

    //Get table name of the custom group
    $daysOpenTableName = civicrm_api3('CustomGroup', 'getsingle', array(
      'return' => array("table_name"),
      'name' => "Track_Case_Days",
    ));

    //Get column name of the custom field.
    $columnName = civicrm_api3('CustomField', 'getsingle', array(
      'return' => array("column_name"),
      'custom_group_id' => "Track_Case_Days",
      'name' => $cfName,
    ));

    return array(
      'cases' => $cases,
      'daysOpenTableName' => $daysOpenTableName,
      'columnName' => $columnName,
    );
  }

}
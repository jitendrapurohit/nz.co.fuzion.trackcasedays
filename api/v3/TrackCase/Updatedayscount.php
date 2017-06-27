<?php
/**
 * TrackCase.Updatedayscount API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_track_case_Updatedayscount($params) {
  $cases = civicrm_api3('Case', 'get', array(
    'sequential' => 1,
    'return' => array("id", "start_date", "end_date"),
    'status_id' => array('IN' => array("Open", "Closed")),
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
    'name' => "Days_Open",
  ));

  foreach ($cases['values'] as $caseVal) {
    $query = "SELECT id
      FROM {$daysOpenTableName['table_name']}
      WHERE entity_id = {$caseVal['id']}";
    $dao = CRM_Core_DAO::executeQuery($query);

    //Count number of days a case is opened.
    $from = time();
    if (!empty($caseVal['end_date'])) {
      $from = strtotime($caseVal['end_date']);
    }
    $datediff = $from - strtotime($caseVal['start_date']);
    $daysOpen = floor($datediff / (60 * 60 * 24)) + 1;

    //Insert/Update into custom table.
    if ($dao->fetch()) {
      $query = "UPDATE {$daysOpenTableName['table_name']}
        SET {$columnName['column_name']} = {$daysOpen}
        WHERE id={$dao->id}";
    }
    else {
      $query = "INSERT INTO {$daysOpenTableName['table_name']} (entity_id, {$columnName['column_name']}) VALUES
        ({$caseVal['id']}, {$daysOpen})";
    }
    CRM_Core_DAO::executeQuery($query);
  }
}

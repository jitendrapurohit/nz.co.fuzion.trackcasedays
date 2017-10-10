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
  $result = civicrm_api3('OptionValue', 'get', array(
    'sequential' => 1,
    'return' => array("name"),
    'option_group_id' => "case_status",
  ));
  $caseStatus = CRM_Utils_Array::collect('name', $result['values']);
  //Update pending custom field if we have a pending status for case.
  if (in_array('Pending', $caseStatus)) {
    CRM_UpdateCaseDays_BAO_CaseDays::calculatePendingDays();
  }
  CRM_UpdateCaseDays_BAO_CaseDays::calculateOpenDays();
}

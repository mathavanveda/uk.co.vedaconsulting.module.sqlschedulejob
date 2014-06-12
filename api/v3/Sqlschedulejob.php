<?php

/**
 * Added by Mathavan@vedaconsulting.co.uk
 * New Custom API to execute the SQL stored in table civicrm_membership_renewal_sql_setting
 * @param type $params
 * @return string
 */
function civicrm_api3_sqlschedulejob_run() {
    $sqlColumnName     = CUSTOM_SQL_SCHEDULE_SETTING_SQL_COLUMNNAME;
    $aAllSettingsSqls = _get_all_schedule_settings_sql_from_table();
    foreach( $aAllSettingsSqls as $settingSQLs ){
        $aSqls[] = $settingSQLs[$sqlColumnName];
    }
    $sToExecute = implode('; ', $aSqls);
    if(!empty($sToExecute)){
        CRM_Core_DAO::executeQuery($sToExecute);
        CRM_Core_Session::setStatus("Success");
    }else{
        CRM_Core_Session::setStatus("No SQL Settings can be stored.. Please Redirect to <baseURL>/civicrm/sql_schedule_settings");
    }
    return ;
}

function _get_all_schedule_settings_sql_from_table(){
    #table name details
    $tableName         = CUSTOM_SQL_SCHEDULE_SETTING_TABLENAME;
    $weightColumnName  = CUSTOM_SQL_SCHEDULE_SETTING_WEIGHT_TABLENAME;
    $aRows             = array();
    $sql = "SELECT * FROM {$tableName} ORDER BY {$weightColumnName}";
    $dao = CRM_Core_DAO::executeQuery($sql);
    while($dao->fetch()){
        $aRows[] = $dao->toArray();
    }
    return $aRows;
}
<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 */

class VTEButtons_ActionAjax_Action extends arocrm_Action_Controller
{
    public function checkPermission(arocrm_Request $request)
    {
    }
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("enableModule");
        $this->exposeMethod("checkEnable");
        $this->exposeMethod("updateSequence");
        $this->exposeMethod("selectModule");
        $this->exposeMethod("UpdateStatus");
        $this->exposeMethod("DeleteRecord");
        $this->exposeMethod("doUpdateFields");
        $this->vteLicense();
    }
    public function vteLicense()
    {
        $vTELicense = new VTEButtons_VTELicense_Model("VTEButtons");
        if (!$vTELicense->validate()) {
            header("Location: index.php?module=VTEButtons&parent=Settings&view=Settings&mode=step2");
        }
    }
    public function process(arocrm_Request $request)
    {
        $mode = $request->get("mode");
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }
    public function enableModule(arocrm_Request $request)
    {
        global $adb;
        $value = $request->get("value");
        $adb->pquery("UPDATE `vte_buttons_settings` SET `active`=?", array($value));
        $response = new arocrm_Response();
        $response->setEmitType(arocrm_Response::$EMIT_JSON);
        $response->setResult(array("result" => "success"));
        $response->emit();
    }
    public function checkEnable(arocrm_Request $request)
    {
        global $adb;
        $rs = $adb->pquery("SELECT `enable` FROM `vte_buttons_settings`;", array());
        $enable = $adb->query_result($rs, 0, "active");
        $response = new arocrm_Response();
        $response->setEmitType(arocrm_Response::$EMIT_JSON);
        $response->setResult(array("enable" => $enable));
        $response->emit();
    }
    public function selectModule(arocrm_Request $request)
    {
        $moduleSelected = $request->get("moduleSelected");
        $module = $request->get("module");
        $moduleModel = arocrm_Module_Model::getInstance($moduleSelected);
        $recordStructureModel = arocrm_RecordStructure_Model::getInstanceForModule($moduleModel, arocrm_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
        $recordStructure = $recordStructureModel->getStructure();
        foreach ($recordStructure as $blocks) {
            foreach ($blocks as $fieldLabel => $fieldValue) {
                $fieldModels[$fieldLabel] = $fieldValue;
            }
        }
        $data = array();
        if ($moduleSelected) {
            $workflows = VTEButtons_Module_Model::getAllWorkflowsForModule($moduleSelected);
        }
        $data = array("fieldmodels" => $fieldModels, "workflows" => $workflows);
        $moduleModel = arocrm_Module_Model::getInstance($module);
        $response = new arocrm_Response();
        $response->setResult($data);
        $response->emit();
    }
    public function UpdateStatus(arocrm_Request $request)
    {
        global $adb;
        $record = $request->get("record");
        $status = $request->get("status");
        $status = $status == "off" ? "0" : "1";
        $sql = "UPDATE `vte_buttons_settings` SET active=? WHERE id=?";
        $adb->pquery($sql, array($status, $record));
        $response = new arocrm_Response();
        $response->setResult("success");
        $response->emit();
    }
    public function DeleteRecord(arocrm_Request $request)
    {
        global $adb;
        $record = $request->get("record");
        $sql = "DELETE FROM `vte_buttons_settings`  WHERE id=?";
        $adb->pquery($sql, array($record));
        $response = new arocrm_Response();
        $response->setResult("success");
        $response->emit();
    }
    public function doUpdateFields(arocrm_Request $request)
    {
        global $adb;
        $recordId = $request->get("record");
        $moduleName = $request->get("source_module");
        $moduleModel = arocrm_Module_Model::getInstance($moduleName);
        $recordModel = arocrm_Record_Model::getInstanceById($recordId, $moduleModel);
        $recordModel->set("id", $recordId);
        $recordModel->set("mode", "edit");
        $_REQUEST["ajxaction"] = "DETAILVIEW";
        $fieldModelList = $moduleModel->getFields();
        foreach ($fieldModelList as $fieldName => $fieldModel) {
            $uiType = $fieldModel->get("uitype");
            if ($uiType == 70) {
                $fieldValue = $recordModel->get($fieldName);
            } else {
                $fieldValue = $fieldModel->getUITypeModel()->getUserRequestValue($recordModel->get($fieldName));
            }
            if ($request->has($fieldName)) {
                $fieldValue = $request->get($fieldName, NULL);
            } else {
                if ($fieldName === $request->get("field")) {
                    $fieldValue = $request->get("value");
                }
            }
            $fieldDataType = $fieldModel->getFieldDataType();
            if ($fieldDataType == "time") {
                $fieldValue = arocrm_Time_UIType::getTimeValueWithSeconds($fieldValue);
            }
            if ($fieldValue !== NULL) {
                if (!is_array($fieldValue)) {
                    $fieldValue = trim($fieldValue);
                }
                $recordModel->set($fieldName, $fieldValue);
            }
            $recordModel->set($fieldName, $fieldValue);
            if ($fieldName === "contact_id" && isRecordExists($fieldValue)) {
                $contactRecord = arocrm_Record_Model::getInstanceById($fieldValue, "Contacts");
                $recordModel->set("relatedContact", $contactRecord);
            }
        }
		
        $recordModel->save();
		if($moduleName=='CustomerPayment'){
		VTEButtons_Module_Model::CustomerPaymentcancelAction($recordId);
		}
        $response = new arocrm_Response();
        $response->setResult("success");
        $response->emit();
    }
}

?>
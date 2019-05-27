<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 */

class VTEButtons_Module_Model extends arocrm_Module_Model
{
    public $user = NULL;
    public $db = NULL;
    public function __construct()
    {
        $this->user = Users_Record_Model::getCurrentUserModel();
        $this->db = PearDatabase::getInstance();
    }
	public function CustomerPaymentcancelAction($recordid){
		 global $adb;
		 $sql = $adb->pquery("SELECT arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.customerpaymentid = '".$recordid."'");
		 $numrow = $adb->num_rows($sql);
			for($i=0;$i<$numrow;$i++)
			{
				$invoiceid = $adb->query_result($sql, $i, "cf_3346");
				$paid = $adb->query_result($sql, $i, "cf_3356");
				$invsql = $adb->pquery("SELECT arocrm_invoice.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
				$balance = $adb->query_result($invsql, 0, "balance");
				$received = $adb->query_result($invsql, 0, "received");
				if($balance == '0.00')
				{
					$upinv = $adb->pquery("UPDATE arocrm_invoice SET invoicestatus = 'Approved' WHERE invoiceid = '".$invoiceid."'");
				}
				$curbalance = $balance + $paid;
				$curreceive = $received - $paid;
				$upinvdetail = $adb->pquery("UPDATE arocrm_invoice SET balance = '".$curbalance."', received = '".$curreceive."' WHERE invoiceid = '".$invoiceid."'");
			}
			$upcpstatus = $adb->pquery("UPDATE arocrm_customerpaymentcf SET cf_3376 = 'Cancelled' WHERE customerpaymentid = '".$recordid."'");
			$upadvstatus = $adb->pquery("UPDATE arocrm_customerpaymentcf SET cf_3376 = 'Cancelled' WHERE customerpaymentid IN (SELECT customerpaymentid FROM arocrm_customerpayment WHERE reference_id = '".$recordid."')");
			return 1;
	}
	
    public function getSettingLinks()
    {
        $settingsLinks[] = array("linktype" => "MODULESETTING", "linklabel" => "Settings", "linkurl" => "index.php?module=VTEButtons&parent=Settings&view=Settings", "linkicon" => "");
        $settingsLinks[] = array("linktype" => "MODULESETTING", "linklabel" => "Uninstall", "linkurl" => "index.php?module=VTEButtons&parent=Settings&view=Uninstall", "linkicon" => "");
        return $settingsLinks;
    }
    public function getCreateViewUrl($record = "")
    {
        return "index.php?module=VTEButtons&parent=Settings&view=Edit" . ($record != "" ? "&record=" . $record : "");
    }
    public function getCreatePreViewLink($record = "")
    {
        return "index.php?module=VTEButtons&parent=Settings&view=Preview" . ($record != "" ? "&record=" . $record : "");
    }
    public function getSettingURL()
    {
        return "index.php?module=VTEButtons&parent=Settings&view=Settings";
    }
    public function getrandomString()
    {
        return str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
    }
    public function getRelatedFieldName($module, $relModule)
    {
        global $adb;
        $sql = "SELECT fieldname FROM `arocrm_field` WHERE fieldid IN (SELECT fieldid from arocrm_fieldmodulerel WHERE module='" . $module . "' AND relmodule='" . $relModule . "')";
        $results = $adb->pquery($sql, array());
        if (0 < $adb->num_rows($results)) {
            $fieldname = $adb->query_result($results, 0, "fieldname");
        }
        return $fieldname;
    }
    public function getModuleFields($module)
    {
        $values = array();
        $moduleModel = arocrm_Module_Model::getInstance($module);
        $blockModelList = $moduleModel->getBlocks();
        foreach ($blockModelList as $blockLabel => $blockModel) {
            $fieldModelList = $blockModel->getFields();
            if (!empty($fieldModelList)) {
                foreach ($fieldModelList as $fieldName => $fieldModel) {
                    $values[$fieldName] = vtranslate($fieldModel->get("label"), $module);
                }
            }
        }
        return $values;
    }
    public function getlistViewEntries($where = "")
    {
        global $adb;
        $Entries = array();
        $sql = "SELECT * FROM `vte_buttons_settings` ";
        if ($where != "") {
            $sql .= " WHERE " . $where;
        }
        $sql .= " ORDER BY module ASC,sequence ASC";
        $results = $adb->pquery($sql, array());
        if (0 < $adb->num_rows($results)) {
            while ($row = $adb->fetchByAssoc($results)) {
                $moduleName = $row["module"];
                $moduleModel = arocrm_Module_Model::getInstance($moduleName);
                $fieldList = $moduleModel->getFields();
                $strfields = html_entity_decode($row["field_name"]);
                $strfields = str_replace("\"", "", $strfields);
                $arrFields = array();
                if (!empty($strfields)) {
                    $arrFields = explode(",", $strfields);
                }
                $Entries[] = array("module" => $moduleName, "header" => $row["header"], "icon" => $row["icon"], "color" => $row["color"], "active" => $row["active"], "field_name" => $arrFields, "id" => $row["id"], "sequence" => $row["sequence"], "conditions" => $row["conditions"], "row_conditions" => $this->getConditonDisplayValue($moduleName, $row["conditions"]), "conditions_count" => $row["conditions_count"], "fieldlist" => $fieldList);
            }
        }
        return $Entries;
    }
    public function getConditonDisplayValue($moduleName = "", $conditions = "")
    {
        $test = $conditions;
        $moduleModel = arocrm_Module_Model::getInstance($moduleName);
        $wfCond = json_decode(html_entity_decode($test), true);
        $conditionList = array();
        if (is_array($wfCond)) {
            for ($i = 0; $i < count($wfCond); $i++) {
                $key = $i + 1;
                foreach ($wfCond[$key]["columns"] as $k => $value) {
                    $arrColumnName = split(":", $wfCond[$key]["columns"][$k]["columnname"]);
                    $fieldName = $arrColumnName[2];
                    preg_match("/\\((\\w+) : \\(([_\\w]+)\\) (\\w+)\\)/", $fieldName, $matches);
                    if (count($matches) == 0) {
                        $fieldModel = arocrm_Field_Model::getInstance($fieldName, $moduleModel);
                        if ($fieldModel) {
                            $fieldLabel = vtranslate($fieldModel->get("label"), $moduleName);
                        } else {
                            $fieldLabel = $fieldName;
                        }
                    } else {
                        list($full, $referenceField, $referenceModule, $fieldName) = $matches;
                        $referenceModuleModel = arocrm_Module_Model::getInstance($referenceModule);
                        $fieldModel = arocrm_Field_Model::getInstance($fieldName, $referenceModuleModel);
                        $referenceFieldModel = arocrm_Field_Model::getInstance($referenceField, $moduleModel);
                        if ($fieldModel) {
                            $translatedReferenceModule = vtranslate($referenceModule, $referenceModule);
                            $referenceFieldLabel = vtranslate($referenceFieldModel->get("label"), $moduleName);
                            $fieldLabel = vtranslate($fieldModel->get("label"), $referenceModule);
                            $fieldLabel = "(" . $translatedReferenceModule . ") " . $referenceFieldLabel . " - " . $fieldLabel;
                        } else {
                            $fieldLabel = $fieldName;
                        }
                    }
                    $value = $wfCond[$key]["columns"][$k]["value"];
                    $operation = $wfCond[$key]["columns"][$k]["comparator"];
                    if ($wfCond[$key]["condition"] == "and") {
                        $conditionGroup = "All";
                    } else {
                        $conditionGroup = "Any";
                    }
                    if ($value == "true:boolean" || $fieldModel && $fieldModel->getFieldDataType() == "boolean" && $value == "1") {
                        $value = "LBL_ENABLED";
                    }
                    if ($value == "false:boolean" || $fieldModel && $fieldModel->getFieldDataType() == "boolean" && $value == "0") {
                        $value = "LBL_DISABLED";
                    }
                    if ($fieldLabel == "_VT_add_comment") {
                        $fieldLabel = "Comment";
                    }
                    $translateComparator = $this->translateComparator($operation, true);
                    $conditionList[$conditionGroup][] = $fieldLabel . " " . $translateComparator . " " . vtranslate($value, $moduleName);
                }
            }
        }
        return $conditionList;
    }
    /**
     * Translate comparator (condition) to long or short form.
     * @access private
     * @internal Used from arocrm_PackageExport also
     */
    public static function translateComparator($value, $tolongform = false)
    {
        $comparator = false;
        if ($tolongform) {
            $comparator = strtolower($value);
            if ($comparator == "e") {
                $comparator = "equals";
            } else {
                if ($comparator == "n") {
                    $comparator = "not equal to";
                } else {
                    if ($comparator == "s") {
                        $comparator = "starts with";
                    } else {
                        if ($comparator == "ew") {
                            $comparator = "ends with";
                        } else {
                            if ($comparator == "c") {
                                $comparator = "contains";
                            } else {
                                if ($comparator == "k") {
                                    $comparator = "does not contain";
                                } else {
                                    if ($comparator == "y") {
                                        $comparator = "is empty";
                                    } else {
                                        if ($comparator == "ny") {
                                            $comparator = "is not empty";
                                        } else {
                                            if ($comparator == "l") {
                                                $comparator = "less than";
                                            } else {
                                                if ($comparator == "g") {
                                                    $comparator = "greater than";
                                                } else {
                                                    if ($comparator == "m") {
                                                        $comparator = "less or equal";
                                                    } else {
                                                        if ($comparator == "h") {
                                                            $comparator = "greater or equal";
                                                        } else {
                                                            if ($comparator == "bw") {
                                                                $comparator = "between";
                                                            } else {
                                                                if ($comparator == "b") {
                                                                    $comparator = "before";
                                                                } else {
                                                                    if ($comparator == "a") {
                                                                        $comparator = "after";
                                                                    } else {
                                                                        if ($comparator == "lessthanhoursbefore") {
                                                                            $comparator = "Less than hours before";
                                                                        } else {
                                                                            if ($comparator == "lessthanhourslater") {
                                                                                $comparator = "Less than hours later";
                                                                            } else {
                                                                                if ($comparator == "morethanhoursbefore") {
                                                                                    $comparator = "More than hours before";
                                                                                } else {
                                                                                    if ($comparator == "morethanhourslater") {
                                                                                        $comparator = "More than Hours Later";
                                                                                    } else {
                                                                                        if ($comparator == "lessthandaysago") {
                                                                                            $comparator = "Less than days ago";
                                                                                        } else {
                                                                                            if ($comparator == "morethandaysago") {
                                                                                                $comparator = "More than days ago";
                                                                                            } else {
                                                                                                if ($comparator == "inlessthan") {
                                                                                                    $comparator = "In less than";
                                                                                                } else {
                                                                                                    if ($comparator == "inmorethan") {
                                                                                                        $comparator = "In More than";
                                                                                                    } else {
                                                                                                        if ($comparator == "daysago") {
                                                                                                            $comparator = "Days ago";
                                                                                                        } else {
                                                                                                            if ($comparator == "dayslater") {
                                                                                                                $comparator = "Days Later";
                                                                                                            } else {
                                                                                                                if ($comparator == "custom") {
                                                                                                                    $comparator = "Custom";
                                                                                                                } else {
                                                                                                                    if ($comparator == "prevfy") {
                                                                                                                        $comparator = "Previous FY";
                                                                                                                    } else {
                                                                                                                        if ($comparator == "thisfy") {
                                                                                                                            $comparator = "Current FY";
                                                                                                                        } else {
                                                                                                                            if ($comparator == "nextfy") {
                                                                                                                                $comparator = "Next FY";
                                                                                                                            } else {
                                                                                                                                if ($comparator == "prevfq") {
                                                                                                                                    $comparator = "Previous FQ";
                                                                                                                                } else {
                                                                                                                                    if ($comparator == "thisfq") {
                                                                                                                                        $comparator = "Current FQ";
                                                                                                                                    } else {
                                                                                                                                        if ($comparator == "nextfq") {
                                                                                                                                            $comparator = "Next FQ";
                                                                                                                                        } else {
                                                                                                                                            if ($comparator == "yesterday") {
                                                                                                                                                $comparator = "Yesterday";
                                                                                                                                            } else {
                                                                                                                                                if ($comparator == "today") {
                                                                                                                                                    $comparator = "Today";
                                                                                                                                                } else {
                                                                                                                                                    if ($comparator == "tomorrow") {
                                                                                                                                                        $comparator = "Tomorrow";
                                                                                                                                                    } else {
                                                                                                                                                        if ($comparator == "lastweek") {
                                                                                                                                                            $comparator = "Previous Week";
                                                                                                                                                        } else {
                                                                                                                                                            if ($comparator == "thisweek") {
                                                                                                                                                                $comparator = "Current Week";
                                                                                                                                                            } else {
                                                                                                                                                                if ($comparator == "nextweek") {
                                                                                                                                                                    $comparator = "Next Week";
                                                                                                                                                                } else {
                                                                                                                                                                    if ($comparator == "lastmonth") {
                                                                                                                                                                        $comparator = "Previous Month";
                                                                                                                                                                    } else {
                                                                                                                                                                        if ($comparator == "thismonth") {
                                                                                                                                                                            $comparator = "Current Month";
                                                                                                                                                                        } else {
                                                                                                                                                                            if ($comparator == "nextmonth") {
                                                                                                                                                                                $comparator = "Next Month";
                                                                                                                                                                            } else {
                                                                                                                                                                                if ($comparator == "last7days") {
                                                                                                                                                                                    $comparator = "Last 7 Days";
                                                                                                                                                                                } else {
                                                                                                                                                                                    if ($comparator == "last14days") {
                                                                                                                                                                                        $comparator = "Last 14 Days";
                                                                                                                                                                                    } else {
                                                                                                                                                                                        if ($comparator == "last30days") {
                                                                                                                                                                                            $comparator = "Last 30 Days";
                                                                                                                                                                                        } else {
                                                                                                                                                                                            if ($comparator == "last60days") {
                                                                                                                                                                                                $comparator = "Last 60 Days";
                                                                                                                                                                                            } else {
                                                                                                                                                                                                if ($comparator == "last90days") {
                                                                                                                                                                                                    $comparator = "Last 90 Days";
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    if ($comparator == "last120days") {
                                                                                                                                                                                                        $comparator = "Last 120 Days";
                                                                                                                                                                                                    } else {
                                                                                                                                                                                                        if ($comparator == "next30days") {
                                                                                                                                                                                                            $comparator = "Next 30 Days";
                                                                                                                                                                                                        } else {
                                                                                                                                                                                                            if ($comparator == "next60days") {
                                                                                                                                                                                                                $comparator = "Next 60 Days";
                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                if ($comparator == "next90days") {
                                                                                                                                                                                                                    $comparator = "Next 90 Days";
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                    if ($comparator == "next120days") {
                                                                                                                                                                                                                        $comparator = "Next 120 Days";
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                }
                                                                                                                                                                                                            }
                                                                                                                                                                                                        }
                                                                                                                                                                                                    }
                                                                                                                                                                                                }
                                                                                                                                                                                            }
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                        }
                                                                                                                                                                    }
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $comparator = strtoupper($value);
            if ($comparator == "EQUALS") {
                $comparator = "e";
            } else {
                if ($comparator == "NOT_EQUALS") {
                    $comparator = "n";
                } else {
                    if ($comparator == "STARTS_WITH") {
                        $comparator = "s";
                    } else {
                        if ($comparator == "ENDS_WITH") {
                            $comparator = "ew";
                        } else {
                            if ($comparator == "CONTAINS") {
                                $comparator = "c";
                            } else {
                                if ($comparator == "DOES_NOT_CONTAINS") {
                                    $comparator = "k";
                                } else {
                                    if ($comparator == "LESS_THAN") {
                                        $comparator = "l";
                                    } else {
                                        if ($comparator == "GREATER_THAN") {
                                            $comparator = "g";
                                        } else {
                                            if ($comparator == "LESS_OR_EQUAL") {
                                                $comparator = "m";
                                            } else {
                                                if ($comparator == "GREATER_OR_EQUAL") {
                                                    $comparator = "h";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $comparator;
    }
    public function getConditionalShowButtons($vteButtonsId, $moduleName)
    {
        global $adb;
        $list = array();
        $res = $adb->pquery("SELECT * FROM vte_buttons_settings WHERE id=? AND  module=?", array($vteButtonsId, $moduleName));
        if ($adb->num_rows($res)) {
            while ($row = $adb->fetchByAssoc($res)) {
                $list[] = $row;
            }
        }
        return $list;
    }
    public function getRecordsByCondition($condition, $recordid)
    {
        global $arocrm_current_version;
        $list = array();
        $advanceFilter = json_decode(html_entity_decode($condition["conditions"]), true);
        if (0 < count($advanceFilter[1]["columns"]) && count($advanceFilter[2]["columns"]) == 0) {
            unset($advanceFilter[1]["condition"]);
        }
        if (version_compare($arocrm_current_version, "7.0.0", ">=")) {
            $queryGenerator = new EnhancedQueryGenerator($condition["module"], $this->user);
        } else {
            $queryGenerator = new QueryGenerator($condition["module"], $this->user);
        }
        $queryGenerator->parseAdvFilterList($advanceFilter);
        $query = "SELECT arocrm_crmentity.crmid ";
        $query .= $queryGenerator->getFromClause();
        $query .= $queryGenerator->getWhereClause();
        $query .= " AND arocrm_crmentity.crmid = " . $recordid;
        $res = $this->db->pquery($query);
        if ($this->db->num_rows($res)) {
            while ($row = $this->db->fetchByAssoc($res)) {
                $list[] = $row["crmid"];
            }
        }
        return $list;
    }
    public function getFieldLabel($moduleName, $fieldName)
    {
        global $adb;
        $tabId = getTabid($moduleName);
        $results = $adb->pquery("SELECT fieldlabel FROM `arocrm_field` where tabid=? AND fieldname=?", array($tabId, $fieldName));
        if (0 < $adb->num_rows($results)) {
            return $adb->query_result($results, 0, "fieldlabel");
        }
        return "";
    }
}

?>
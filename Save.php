<?php
class arocrm_Save_Action extends arocrm_Action_Controller {

	public function checkPermission(arocrm_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$actionName = ($record) ? 'EditView' : 'CreateView';
		if(!Users_Privileges_Model::isPermitted($moduleName, $actionName, $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if(!Users_Privileges_Model::isPermitted($moduleName, 'Save', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if ($record) {
			$recordEntityName = getSalesEntityType($record);
			if ($recordEntityName !== $moduleName) {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
	}
	
	public function validateRequest(arocrm_Request $request) {
		return $request->validateWriteAccess();
	}

	public function process(arocrm_Request $request) {
		try {
			$recordModel = $this->saveRecord($request);
			if ($request->get('returntab_label')){
				$loadUrl = 'index.php?'.$request->getReturnURL();
			} else if($request->get('relationOperation')) {
				$parentModuleName = $request->get('sourceModule');
				$parentRecordId = $request->get('sourceRecord');
				$parentRecordModel = arocrm_Record_Model::getInstanceById($parentRecordId, $parentModuleName);
				//TODO : Url should load the related list instead of detail view of record
				$loadUrl = $parentRecordModel->getDetailViewUrl();
			} else if ($request->get('returnToList')) {
				$loadUrl = $recordModel->getModule()->getListViewUrl();
			} else if ($request->get('returnmodule') && $request->get('returnview')) {
				$loadUrl = 'index.php?'.$request->getReturnURL();
			} else {
				$loadUrl = $recordModel->getDetailViewUrl();
			}
			//append App name to callback url
			//Special handling for arocrm7.
			$appName = $request->get('appName');
			if(strlen($appName) > 0){
				$loadUrl = $loadUrl.$appName;
			}
			header("Location: $loadUrl");
		} catch (DuplicateException $e) {
			$requestData = $request->getAll();
			$moduleName = $request->getModule();
			unset($requestData['action']);
			unset($requestData['__vtrftk']);

			if ($request->isAjax()) {
				$response = new arocrm_Response();
				$response->setError($e->getMessage(), $e->getDuplicationMessage(), $e->getMessage());
				$response->emit();
			} else {
				$requestData['view'] = 'Edit';
				$requestData['duplicateRecords'] = $e->getDuplicateRecordIds();
				$moduleModel = arocrm_Module_Model::getInstance($moduleName);

				global $arocrm_current_version;
				$viewer = new arocrm_Viewer();

				$viewer->assign('REQUEST_DATA', $requestData);
				$viewer->assign('REQUEST_URL', $moduleModel->getCreateRecordUrl().'&record='.$request->get('record'));
				$viewer->view('RedirectToEditView.tpl', 'arocrm');
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Function to save record
	 * @param <arocrm_Request> $request - values of the record
	 * @return <RecordModel> - record Model of saved record
	 */
	public function saveRecord($request) {
		$this->db = PearDatabase::getInstance();
		$recordModel = $this->getRecordModelFromRequest($request);

		if($request->get('imgDeleted')) {
			$imageIds = $request->get('imageid');
			foreach($imageIds as $imageId) {
				$status = $recordModel->deleteImage($imageId);
			}
		}
		$recordModel->save();
		$module = $request->getModule();
		$apps = $_POST['appName'];
		$appname = explode("=",$apps);
		$app = $appname[1];
		$recordid = $recordModel->getId();
		$mod = strtolower($module);
		$editmode = $_REQUEST['record'];
		
		
		// Updating of Number Range for all module code added by Roni Modak 17-04-2019 //
			 if($module == 'VendorPayment'){
				 if($editmode==''){
				$type = $_POST['cf_4701'];
				if($type = 'Purchase Invoice')
				{
				$invamount = $_POST['cf_3300'];
			    $paidamount = $_POST['cf_3302'];
				
				$dueamount = $paidamount - $invamount;
				
				if($dueamount > 0)
				{
					
					$vendorid = $_POST['cf_nrl_vendors297_id'];
					$branchid = $_POST['cf_nrl_plantmaster425_id'];
					$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
					$recid = $this->db->query_result($mfcrmid,'0','id');
					$recid = (int)$recid + 1;
					
					$nextrecid = $recid;

		             $updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");
					 
					 
					 
	 	$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
		`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
		VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','VendorPayment','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','VendorPayment')";
		$crmenins = $this->db->pquery($crmin);

		$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'VendorPayment'");
		$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

		$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

		$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'VendorPayment'");
		
		$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");
		
		$postdate = $request->get('cf_4960');
			$tmp = explode("-",$postdate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$postdate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}
			
		$totalcount = $request->get('totalRowCount_Payment_Details');
		$exp = explode(",",$totalcount);
		$linecount = count($exp);
		$valss = '';
		if($linecount > 1){
		$valss = '_1';     
		}
		
		$this->db->pquery("INSERT INTO `arocrm_vendorpayment`(`vendorpaymentid`, `name`, `vendorpaymentno`, `cf_nrl_vendors297_id`, `cf_nrl_plantmaster425_id`) VALUES ('".$recid."','VendorPayment', '".$recserialid."','".$vendorid."','".$branchid."')");	
		$this->db->pquery("INSERT INTO `arocrm_vendorpaymentcf`(`vendorpaymentid`,`cf_4701`,`cf_3302`,`cf_4699`, `cf_4935`, `cf_4958`, `cf_4960`, `cf_5005`, `cf_5007`) VALUES ('".$recid."','Advance Payment','".$dueamount."','".$request->get('cf_4699')."','".$request->get('cf_4633')."','".$request->get('cf_4635')."','".$postdate."','".$request->get('cf_3318'.$valss)."','".$request->get('cf_3320'.$valss)."')");
		
				$doctype = 'VA';
				
				 $deliverto = $request->get('cf_nrl_plantmaster425_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4960'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }
                 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_vendorpayment` SET `vendorpaymentno` = '".$document_numbering."' WHERE `vendorpaymentid` = '".$recid."'");
					$name = 'VendorPayment'."_".$document_numbering;
					$updatename = $this->db->pquery("UPDATE `arocrm_vendorpayment` SET `name` = '".$name."' WHERE `vendorpaymentid` = ".$recid);
					$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recid);
		
				}
			 }
				$ref = trim($request->get('cf_4701'));
				$financial_year = $request->get('cf_4633');
				$doctype = '';
				if($ref=='Debit Note'){
				$doctype = 'VD';	
				}else if($ref=='Credit Note'){
				$doctype = 'VC';
				}else if($ref=='Purchase Invoice'){
				$doctype = 'VP';
				}else{
				$doctype = 'VA';
				}
				 $deliverto = $request->get('cf_nrl_plantmaster425_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4960'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }
                 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_vendorpayment` SET `vendorpaymentno` = '".$document_numbering."' WHERE `vendorpaymentid` = '".$recordid."'");				 
				}	
			 }else if($module == 'CustomerPayment'){
				
				if($editmode==''){
				$type = $request->get('cf_3335');
				if($type == 'Sales Invoice Payment'){
				
				$invamount = $_POST['cf_3340'];
				 
			    $paidamount = $_POST['cf_3342'];
				
				$dueamount = $paidamount - $invamount;
				
				if($dueamount > 0)
				{
					
					$custid = $_POST['cf_nrl_accounts363_id'];
					$branchid = $_POST['cf_nrl_plantmaster1000_id'];
					$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
					$recid = $this->db->query_result($mfcrmid,'0','id');
					$recid = (int)$recid + 1;
					
					$nextrecid = $recid;

		             $updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");
					 
		    $postdate = $request->get('cf_4967');
			$tmp = explode("-",$postdate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$postdate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}		 
	 	$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
		`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
		VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','CustomerPayment','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','CustomerPayment')";
		$crmenins = $this->db->pquery($crmin);

		$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'CustomerPayment'");
		$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

		$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

		$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'CustomerPayment'");
		
		$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");
		$saleinvid = $recordModel->getId();
		$this->db->pquery("INSERT INTO `arocrm_customerpayment`(`customerpaymentid`, `name`, `customerpaymentno`, `cf_nrl_accounts363_id`, `cf_nrl_plantmaster1000_id`, `reference_id`) VALUES ('".$recid."','CustomerPayment', '".$recserialid."','".$custid."','".$branchid."','".$saleinvid."')");	
		$totalcount = $request->get('totalRowCount_Payment_Details');
		$exp = explode(",",$totalcount);
		$linecount = count($exp);
		$valss = '';
		if($linecount > 1){
		$valss = '_1';     
		}
		
		$this->db->pquery("INSERT INTO `arocrm_customerpaymentcf`(`customerpaymentid`,`cf_3335`,`cf_3342`,`cf_3376`, `cf_4963`, `cf_4965`, `cf_4967`, `cf_5001`, `cf_5003`) VALUES ('".$recid."','Advance Payment','".$dueamount."','Approved','".$request->get('cf_4633')."','".$request->get('cf_4635')."','".$postdate."','".$request->get('cf_3360'.$valss)."','".$request->get('cf_3362'.$valss)."')");
		
				$doctype = 'CA';
				
				 $deliverto = $request->get('cf_nrl_plantmaster1000_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year = $request->get('cf_4633');
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1,2);
				 $year_2 = substr($year2,2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($postdate)); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 
				 $document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 
				 $this->db->pquery("UPDATE `arocrm_customerpayment` SET `customerpaymentno` = '".$document_numbering."' WHERE `customerpaymentid` = '".$recid."'");
				 
					$name = 'CustomerPayment'."_".$document_numbering;
					$updatename = $this->db->pquery("UPDATE `arocrm_customerpayment` SET `name` = '".$name."' WHERE `customerpaymentid` = ".$recid);
					$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recid);
		
				}
				
				
				}
				
				
				$ref = trim($request->get('cf_3335'));
				$financial_year = $request->get('cf_4633');
				$doctype = '';
				if($ref=='Debit Note'){
				$doctype = 'CD';	
				}else if($ref=='Credit Note'){
				$doctype = 'CC';
				}else if($ref=='Sales Invoice Payment'){
				$doctype = 'CR';
				}else{
				$doctype = 'CA';
				}
				 $deliverto = $request->get('cf_nrl_plantmaster1000_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4967'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_customerpayment` SET `customerpaymentno` = '".$document_numbering."' WHERE `customerpaymentid` = '".$recordid."'");
				}
			
			}else if($module == 'OutboundDelivery'){
				if($editmode==''){
				$ref = trim($request->get('cf_3067'));
				$financial_year = $request->get('cf_4629');
				$doctype = '';
				if($ref=='With Respect to STPO'){
				$doctype = 'SC';
                $deliverto = $request->get('cf_nrl_plantmaster574_id');				
				}else{
				$doctype = 'OD';
				$deliverto = $request->get('cf_nrl_plantmaster625_id');
				}
				 
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3225'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_outbounddelivery` SET `outbounddeliveryno` = '".$document_numbering."' WHERE `outbounddeliveryid` = '".$recordid."'");
				}
			}else if($module == 'PurchaseOrder'){
				if($editmode==''){
				$ref = trim($request->get('cf_2712'));
				$financial_year = $request->get('cf_4605');
				$doctype = '';
				$deliverto = '';
				if($ref=='Reference to STR'){
				$doctype = 'ST';	
				$deliverto = $request->get('cf_nrl_plantmaster953_id');
				}else{
				$doctype = 'PO';
				$deliverto = $request->get('cf_nrl_plantmaster950_id');
				}
				 
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3653'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_purchaseorder` SET `purchaseorder_no` = '".$document_numbering."' WHERE `purchaseorderid` = '".$recordid."'");
				}
				
			}else if($module == 'InboundDelivery'){
				if($editmode==''){
				$ref = trim($request->get('cf_3193'));
				$financial_year = $request->get('cf_4319');
				$doctype = '';
				if($ref=='With respect to STPO'){
				$doctype = 'SD';	
				}else{
				$doctype = 'ID';
				}
				 $deliverto = $request->get('cf_nrl_plantmaster269_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3200'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_inbounddelivery` SET `inbounddeliveryno` = '".$document_numbering."' WHERE `inbounddeliveryid` = '".$recordid."'");
				}
			}else if($module == 'ServiceContracts'){
				if($editmode==''){
				$doctype = 'WR';	
				if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('Y') +1);
				}
				else {
				$financial_year = (date('Y')-1)."-".date('Y');
				}
				
				 $deliverto = $request->get('cf_nrl_plantmaster460_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime(date('Y-m-d'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_servicecontracts` SET `contract_no` = '".$document_numbering."' WHERE `servicecontractsid` = '".$recordid."'");
				}
			}else if($module == 'QualityInspection'){
				if($editmode==''){
				$ref = trim($request->get('cf_3071'));
				$financial_year = $request->get('cf_4609');
				$doctype = '';
				if($ref=='With respect to Inbound Delivery'){
				$doctype = 'QI';	
				}else{
				$doctype = 'QO';
				}
				 $deliverto = $request->get('cf_nrl_plantmaster114_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3227'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_qualityinspection` SET `qualityinspectionno` = '".$document_numbering."' WHERE `qualityinspectionid` = '".$recordid."'");
				}
			}else if($module == 'StockRequisition'){
				if($editmode==''){
				$financial_year = $request->get('cf_4597');
				$doctype = 'SR';
				
				 $deliverto = $request->get('cf_nrl_plantmaster765_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3221'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_stockrequisition` SET `stockrequisitionno` = '".$document_numbering."' WHERE `stockrequisitionid` = '".$recordid."'");
				 
				 
				 
				 //Additional inserting the data in picklist in Purchase Requisition//
				 
				 $picklist = "SELECT * FROM arocrm_picklistvalues_seq";
				  $picklist_result = $this->db->pquery($picklist);
				  $picklist_row = $this->db->fetch_array($picklist_result);
				  $picklist_seq = $picklist_row['id'];
				  $picklist_seq_new = intval($picklist_seq) + 1;


				  $cf_2765 = "SELECT * FROM arocrm_cf_2765_seq";
				  $cf_2765_result = $this->db->pquery($cf_2765);
				  $cf_2765_row = $this->db->fetch_array($cf_2765_result);
				  $cf_2765_seq = $cf_2765_row['id'];
				  $cf_2765_seq_new = intval($cf_2765_seq) + 1;

				  $cf_2765_picklist = "INSERT INTO arocrm_cf_2765 (cf_2765,presence,picklist_valueid,sortorderid) VALUES (?,?,?,?)";
		          $cf_2765_result = $this->db->pquery($cf_2765_picklist,array($document_numbering,1,$picklist_seq_new,$cf_2765_seq_new));

				  $cf_2765_seq_update = "UPDATE arocrm_cf_2765_seq SET id = ?";
		          $cf_2765_seq_result = $this->db->pquery($cf_2765_seq_update,array($cf_2765_seq_new));

				  $picklistvalues_seq_update = "UPDATE arocrm_picklistvalues_seq SET id = ?";
		          $picklistvalues_seq_result = $this->db->pquery($picklistvalues_seq_update,array($picklist_seq_new));

				        $picklist_qry = "SELECT * FROM arocrm_picklist WHERE name = ?";
						$picklist_qry_result = $this->db->pquery($picklist_qry,array('cf_2765'));
						$picklist_qry_count = $this->db->num_rows($picklist_qry_result);
						if($picklist_qry_count==1)
						{
						  $picklist_qry_row = $this->db->fetch_array($picklist_qry_result);
						  $picklistid = $picklist_qry_row['picklistid'];

						  $role_query = "SELECT * FROM arocrm_role";
						  $role_result = $this->db->pquery($role_query);
						  $role_count = $this->db->num_rows($role_result);
						  if($role_count>0)
						  {
							while($role_row = $this->db->fetch_array($role_result))
							{
							  $roleid = $role_row['roleid'];
							  $roletopicklist = "INSERT INTO arocrm_role2picklist (roleid,picklistvalueid,picklistid,sortid) VALUES (?,?,?,?)";
							  $roletopicklist_result = $this->db->pquery($roletopicklist,array($roleid,$picklist_seq_new,$picklistid,$cf_2765_seq_new));
							}
						  }
						}

			   //END of adding data  in purchase requisition//
				 
				}
		   	}else if($module == 'PurchaseReq'){
				if($editmode==''){
				$financial_year = $request->get('cf_4601');
				$doctype = 'PR';
				 $deliverto = $request->get('cf_nrl_plantmaster436_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3202'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_purchasereq` SET `purchasereqno` = '".$document_numbering."' WHERE `purchasereqid` = '".$recordid."'");
				}
			}else if($module == 'Invoice'){
				if($editmode==''){
				$ref = trim($request->get('cf_3288'));
				$financial_year = $request->get('cf_4623');
				$doctype = '';
				if($ref=='Purchase Invoice'){
				$doctype = 'PI';	
				}else if($ref=='Direct Sales'){
				$doctype = 'DI';	
				}else{
				$refse = $this->db->pquery("SELECT  `arocrm_salesordercf`.`cf_3286` FROM `arocrm_salesorder` INNER JOIN `arocrm_salesordercf` ON `arocrm_salesordercf`.`salesorderid` = `arocrm_salesorder`.`salesorderid` INNER JOIN `arocrm_crmentity` ON  `arocrm_crmentity`.`crmid` = `arocrm_salesorder`.`salesorderid` WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_salesorder`.`salesorderid` = (SELECT `salesorderid` FROM `arocrm_invoice` WHERE `arocrm_invoice`.`invoiceid` = '".$recordid."')");
				$refty = $this->db->query_result($refse,'0','cf_3286');
				if($refty=='Against Warranty'){
				$doctype = 'WI';
				}else{
				$doctype = 'SI';
				}
				}
				 $deliverto = $request->get('cf_nrl_plantmaster164_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4627'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_invoice` SET `invoice_no` = '".$document_numbering."' WHERE `invoiceid` = '".$recordid."'");
				}
			}else if($module == 'GoodsIssue'){
				if($editmode==''){
				$financial_year = $request->get('cf_4633');
				$doctype = 'GI';
				
				 $deliverto = $request->get('cf_nrl_plantmaster280_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3229'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_goodsissue` SET `goodsissueno` = '".$document_numbering."' WHERE `goodsissueid` = '".$recordid."'");
				}
			}else if($module == 'GoodsReceipt'){
				if($editmode==''){
				$financial_year = $request->get('cf_4613');
				$doctype = 'GR';
				
				 $deliverto = $request->get('cf_nrl_plantmaster388_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3223'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_goodsreceipt` SET `goodsreceiptno` = '".$document_numbering."' WHERE `goodsreceiptid` = '".$recordid."'");
				}
			}else if($module == 'SalesPlan'){
				if($editmode==''){
				$financial_year = $request->get('cf_3506');
				$doctype = 'SP';
				
				 $deliverto = $request->get('cf_nrl_plantmaster166_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4850'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_salesplan` SET `salesplanno` = '".$document_numbering."' WHERE `salesplanid` = '".$recordid."'");
				}
			}else if($module == 'SalesBudget'){
				if($editmode==''){
				$financial_year = $request->get('cf_3424');
				$doctype = 'SB';
				
				 $deliverto = $request->get('cf_nrl_plantmaster615_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4782'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_salesbudget` SET `salesbudgetno` = '".$document_numbering."' WHERE `salesbudgetid` = '".$recordid."'");
				}
			}else if($module == 'MarketAnalysis'){
				if($editmode==''){
				if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('Y') +1);
				}
				else {
				$financial_year = (date('Y')-1)."-".date('Y');
				}
				 $doctype = 'MP';
				 $branchcode = 'ED';
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime(date('Y-m-d'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_marketanalysis` SET `marketanalysisno` = '".$document_numbering."' WHERE `marketanalysisid` = '".$recordid."'");
				}
			}else if($module == 'HelpDesk'){
				if($editmode==''){
				if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('Y') +1);
				}
				else {
				$financial_year = (date('Y')-1)."-".date('Y');
				}
				 $doctype = 'CP';
				 $deliverto = $request->get('cf_nrl_plantmaster913_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime(date('Y-m-d'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_troubletickets` SET `ticket_no` = '".$document_numbering."' WHERE `ticketid` = '".$recordid."'");
				}
			}else if($module == 'SalesOrder'){
				if($editmode==''){
				$ref = trim($request->get('cf_3286'));
				$financial_year = $request->get('cf_4618');
				$doctype = '';
				if($ref=='Against Warranty'){
				$doctype = 'WS';	
				}else{
				$doctype = 'SO';
				}
				 $deliverto = $request->get('cf_nrl_plantmaster580_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4306'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_salesorder` SET `salesorder_no` = '".$document_numbering."' WHERE `salesorderid` = '".$recordid."'");
				}
			}else if($module == 'FinalJobProcessingReport'){
				if($editmode==''){
				if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('Y') +1);
				}
				else {
				$financial_year = (date('Y')-1)."-".date('Y');
				}
				$doctype = 'JC';
				 $deliverto = $request->get('cf_nrl_plantmaster472_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime(date('Y-m-d'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_finaljobprocessingreport` SET `finaljobprocessingreportno` = '".$document_numbering."' WHERE `finaljobprocessingreportid` = '".$recordid."'");
				}
			}else if($module == 'SalesReturn'){
				if($editmode==''){
				$financial_year = $request->get('cf_4623');
				$doctype = 'RO';
				
				 $deliverto = $request->get('cf_nrl_plantmaster177_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4817'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_salesreturn` SET `salesreturnno` = '".$document_numbering."' WHERE `salesreturnid` = '".$recordid."'");
				}
				$totalrowcnt = $_POST['totalRowCount_Line_Item'];
				$total = explode(',',$totalrowcnt);
				$totalrow = count($total);
				
					for($i=0; $i<$totalrow;$i++)
					{
						$productid = $_POST['cf_3268_'.$total[$i]];
						$quantity = $_POST['cf_3274_'.$total[$i]];
						$query =  $this->db->pquery("SELECT arocrm_products.*, arocrm_productcf.*, arocrm_crmentity.* FROM arocrm_products
					   INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
					   INNER JOIN arocrm_productcf ON arocrm_productcf.productid=arocrm_products.productid
					   WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$productid);
					   $row = $this->db->fetch_array($query);
					   $point = $row['cf_5189'];
					   $totalpoint = $point * $quantity;
					   $allpoint = $allpoint + $totalpoint;
					}
				$accountid = $_POST['cf_nrl_accounts633_id'];
				$accsql = $this->db->pquery("SELECT arocrm_account.*, arocrm_accountscf.* FROM arocrm_account INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$accountid."'");
				$accrow = $this->db->fetch_array($accsql);
				$point = $accrow['cf_5191'];
				if($point == '')
				{
					$point = 0;
				}
				$curpoint = ($point - $allpoint);
				$upacc = $this->db->pquery("UPDATE arocrm_accountscf SET cf_5191 = '".$curpoint."' WHERE accountid = '".$accountid."'");
			}else if($module == 'PurchaseReturnOrder'){
				if($editmode==''){
				
				$financial_year = $request->get('cf_4623');
				$doctype = 'RE';
				
				 $deliverto = $request->get('cf_nrl_plantmaster447_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_3372'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_purchasereturnorder` SET `purchasereturnorderno` = '".$document_numbering."' WHERE `purchasereturnorderid` = '".$recordid."'");
				}
			}else if($module == 'AssemblyOrder'){
				if($editmode==''){
				
				$financial_year = $request->get('cf_4633');
				$doctype = 'AO';
				
				 $deliverto = $request->get('cf_nrl_plantmaster360_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_5112'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_assemblyorder` SET `assemblyorderno` = '".$document_numbering."' WHERE `assemblyorderid` = '".$recordid."'");
				}
			}else if($module == 'Assembly'){
				if($editmode==''){
				
				$financial_year = $request->get('cf_5140');
				$doctype = 'AS';
				
				 $deliverto = $request->get('cf_nrl_plantmaster837_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_5144'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_assembly` SET `assemblyno` = '".$document_numbering."' WHERE `assemblyid` = '".$recordid."'");
				}
			    }else if($module == 'StockUpload'){
				if($editmode==''){
				
				$financial_year = $request->get('cf_4633');
				$doctype = 'SU';
				
				 $deliverto = $request->get('cf_nrl_plantmaster741_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1, 2);
				 $year_2 = substr($year2, 2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($request->get('cf_4979'))); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				$document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 $this->db->pquery("UPDATE `arocrm_stockupload` SET `stockuploadno` = '".$document_numbering."' WHERE `stockuploadid` = '".$recordid."'");
				}
			}
			
		// End of Updating of Number Range for all module code added by Roni Modak 17-04-2019 //
		
		
		
		
			if($module == 'ServiceContracts')
			{
				$recordchk = $this->db->pquery("SELECT * FROM `arocrm_".$mod."` WHERE `".$mod."id` = ".$recordid);
				$modno = 'contract';
				$moduleno = $this->db->query_result($recordchk,0,$modno.'_no');
				$name = "Warranty Registration_".$moduleno;
				$updatename = $this->db->pquery("UPDATE `arocrm_".$mod."` SET `subject` = '".$name."' WHERE `".$mod."id` = ".$recordid);
				$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recordid);
			}
			else if($module == 'HelpDesk')
			{
				$mod = 'ticket';
				$recordchk = $this->db->pquery("SELECT * FROM `arocrm_troubletickets` WHERE `".$mod."id` = ".$recordid);
				$moduleno = $this->db->query_result($recordchk,0,$mod.'_no');
				$name = 'Claim Process_'.$moduleno;
				$updatename = $this->db->pquery("UPDATE `arocrm_troubletickets` SET `title` = '".$name."' WHERE `".$mod."id` = ".$recordid);
				$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recordid);
			}
			else if($module == 'InitialJobReport')
			{
				$recordchk =  $this->db->pquery("SELECT * FROM `arocrm_".$mod."` WHERE `".$mod."id` = ".$recordid);
				$moduleno = $this->db->query_result($recordchk,0,$mod.'no');
				$name = "Initial Job Card_".$moduleno;
				$updatename = $this->db->pquery("UPDATE `arocrm_".$mod."` SET `name` = '".$name."' WHERE `".$mod."id` = ".$recordid);
				$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recordid);
			}
			else if($module == 'FinalJobProcessingReport')
			{
				$recordchk =  $this->db->pquery("SELECT * FROM `arocrm_".$mod."` WHERE `".$mod."id` = ".$recordid);
				$moduleno = $this->db->query_result($recordchk,0,$mod.'no');
				$name = "Final Job Card_".$moduleno;
				$updatename = $this->db->pquery("UPDATE `arocrm_".$mod."` SET `name` = '".$name."' WHERE `".$mod."id` = ".$recordid);
				$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recordid);
			}
			else if($module =="Invoice" || $module =="SalesOrder" || $module =="PurchaseOrder")
			{
				$recordchk = $this->db->pquery("SELECT * FROM `arocrm_".$mod."` WHERE `".$mod."id` = ".$recordid);
				$moduleno = $this->db->query_result($recordchk,0,$mod.'_no');
				$name = $module."_".$moduleno;
				$updatename = $this->db->pquery("UPDATE `arocrm_".$mod."` SET `subject` = '".$name."' WHERE `".$mod."id` = ".$recordid);
				$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recordid);
			}
			else
			{
				if($module != 'Accounts' && $module != 'Contacts' && $module != 'Leads' && $module != 'Products' && $module!= 'Users' && $module!= 'RouteMaster' && $module != 'Vendors' && $module!= 'PlantMaster' && $module!= 'District' && $module!= 'SerialNumber' && $module!= 'Approvals' && $module!= 'BankMaster' && $module!= 'DiscountMaster')
				{
					$recordchk =  $this->db->pquery("SELECT * FROM `arocrm_".$mod."` WHERE `".$mod."id` = ".$recordid);
					$moduleno = $this->db->query_result($recordchk,0,$mod.'no');
					$name = $module."_".$moduleno;
					$updatename = $this->db->pquery("UPDATE `arocrm_".$mod."` SET `name` = '".$name."' WHERE `".$mod."id` = ".$recordid);
					$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recordid);
				}
			} 
			
		if($request->get('relationOperation')) {
			$parentModuleName = $request->get('sourceModule');
			$parentModuleModel = arocrm_Module_Model::getInstance($parentModuleName);
			$parentRecordId = $request->get('sourceRecord');
			$relatedModule = $recordModel->getModule();
			$relatedRecordId = $recordModel->getId();
			if($relatedModule->getName() == 'Events'){
				$relatedModule = arocrm_Module_Model::getInstance('Calendar');
			}

			$relationModel = arocrm_Relation_Model::getInstance($parentModuleModel, $relatedModule);
			$relationModel->addRelation($parentRecordId, $relatedRecordId);
		}
		$this->savedRecordId = $recordModel->getId();

		/* Code Added Here by Shirsha Kundu on 16/11/2018 */
			$module = $request->getModule();
			if($module == 'FinalJobProcessingReport')
			{
				$foc = $_POST['cf_3960'];
				$prorata = $_POST['cf_3962'];
				$prorataval = $_POST['cf_3964'];
				$okreturn = $_POST['cf_3966'];
				$reject = $_POST['cf_3970'];
				$ticketid = $_POST['cf_nrl_helpdesk468_id'];
				if($foc == 'on' ||  $prorata == 'on')
				{
					$upticket = $this->db->pquery("UPDATE arocrm_ticketcf SET cf_4266 = 'Accepted' WHERE ticketid = '".$ticketid."'");
					if($foc == 'on')
					{
						$upoutcome = $this->db->pquery("UPDATE arocrm_ticketcf SET cf_2996 = 'Replaced and FOC' WHERE ticketid = '".$ticketid."'");
					}
					if($prorata == 'on')
					{
						$upoutcome = $this->db->pquery("UPDATE arocrm_ticketcf SET cf_2996 = 'Replaced and Prorata -'".$prorataval." WHERE ticketid = '".$ticketid."'");
					}
				}
				if($okreturn == 'on')
				{
					$upticket = $this->db->pquery("UPDATE arocrm_ticketcf SET cf_4266 = 'Ok & Return', cf_2996 = 'Ok & Return' WHERE ticketid = '".$ticketid."'");
				}
				if($reject == 'on')
				{
					$upticket = $this->db->pquery("UPDATE arocrm_ticketcf SET cf_4266 = 'Rejected', cf_2996 = 'Rejected' WHERE ticketid = '".$ticketid."'");
				}
			}
			if($module == 'JourneyPlan')
			{
				$pjpid = $recordModel->getId();
				$oldpjpid = $_POST['cf_nrl_journeyplan752_id'];
				$pjptype = $_POST['cf_3588'];
				$revisionreason = $_POST['cf_3590'];
				$normal = $_POST['cf_3592'];
				$calamity = $_POST['cf_3594'];
				if($pjptype == 'Revised')
				{
					$typechk = $this->db->pquery("SELECT arocrm_journeyplan.*,arocrm_journeyplancf.* FROM arocrm_journeyplan 
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_journeyplan.journeyplanid 
					INNER JOIN arocrm_journeyplancf ON arocrm_journeyplancf.journeyplanid = arocrm_journeyplan.journeyplanid 
					WHERE arocrm_crmentity.deleted=0 AND arocrm_journeyplan.journeyplanid = '".$oldpjpid."'");
					$type = $this->db->query_result($typechk,0,'cf_3588');
					if($type == 'Fresh')
					{
						if($revisionreason == 'Normal')
						{
							$uppjp = $this->db->pquery("UPDATE arocrm_journeyplancf SET cf_3592 = '1' WHERE journeyplanid = '".$pjpid."'");
						}
						else
						{
							$uppjp = $this->db->pquery("UPDATE arocrm_journeyplancf SET cf_3594 = '1' WHERE journeyplanid = '".$pjpid."'");
						}
					}
					else
					{
						if($revisionreason == 'Normal')
						{
							$newnormal = $normal + 1;
							$uppjp = $this->db->pquery("UPDATE arocrm_journeyplancf SET cf_3592 = '".$newnormal."' WHERE journeyplanid = '".$pjpid."'");
						}
						else
						{
							$newcalamity = $calamity + 1;
							$uppjp = $this->db->pquery("UPDATE arocrm_journeyplancf SET cf_3594 = '".$newcalamity."' WHERE journeyplanid = '".$pjpid."'");
						}
					}
				}
			}
			if($module == 'HelpDesk')
			{
				$ticketid = $recordModel->getId();
				$today = date("Y-m-d");
				$purchasedate = $_POST['cf_4252'];
				$ts1 = strtotime($purchasedate);
				$ts2 = strtotime($today);

				$year1 = date('Y', $ts1);
				$year2 = date('Y', $ts2);

				$month1 = date('m', $ts1);
				$month2 = date('m', $ts2);

				$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
				
				$warrantyfree = $_POST['cf_4242'];
				$warrantyprorata = $_POST['cf_4244'];
				$totalwarranty = $warrantyfree + $warrantyprorata;
				$calculation = ((1 - ($diff/$totalwarranty)) * 100);
				$upticket = $this->db->pquery("UPDATE arocrm_ticketcf SET cf_4790 = '".$calculation."' WHERE ticketid = '".$ticketid."'");
				
				$matreceived = $_POST['cf_4858'];
				if($matreceived == 'on')
				{
					$serialno = $_POST['cf_2991'];
					$serialchk = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name`='".$serialno."'");
					$plantid = $this->db->query_result($serialchk,0,'cf_nrl_plantmaster496_id');
					$storagechk = $this->db->pquery("SELECT * FROM `arocrm_storagelocation` WHERE `name`='Scrap' AND `cf_nrl_plantmaster561_id` ='".$plantid."'");
					$storeid = $this->db->query_result($storagechk,0,'storagelocationid');
					$upserial = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `name`='".$serialno."'");
				}
			}
			if($module == 'OutboundDelivery')
			{
				$obdid = $recordModel->getId();
				$obdchk = $this->db->pquery("SELECT arocrm_outbounddelivery.*, arocrm_outbounddeliverycf.*, arocrm_outbounddelivery_line_item_lineitem.* FROM arocrm_outbounddelivery 
				INNER JOIN arocrm_outbounddelivery_line_item_lineitem ON arocrm_outbounddelivery_line_item_lineitem.outbounddeliveryid = arocrm_outbounddelivery.outbounddeliveryid 
				INNER JOIN arocrm_outbounddeliverycf ON arocrm_outbounddeliverycf.outbounddeliveryid = arocrm_outbounddelivery.outbounddeliveryid 
				WHERE arocrm_outbounddelivery.outbounddeliveryid = '".$obdid."'");
				$status = $this->db->query_result($obdchk,0,'cf_4826');
				$serial =  $this->db->query_result($obdchk,0,'cf_3076');
				$serialno = explode(",",$serial);
				$scnt = count($serialno);
				for($i=0;$i<$scnt;$i++)
				{
					if($status == 'Cancelled')
					{
						$serialchk = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name`='".$serialno[$i]."'");
						$serialid = $this->db->query_result($serialchk,0,'serialnumberid');
						$upserialno = $this->db->pquery("UPDATE arocrm_serialnumbercf SET cf_2834 = '1', cf_3084 = '', cf_3128 = '' WHERE serialnumberid = '".$serialid."'");
					}
					else
					{
					$serialchk = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name`='".$serialno[$i]."'");
					$plantid = $this->db->query_result($serialchk,0,'cf_nrl_plantmaster496_id');
					$storagechk = $this->db->pquery("SELECT * FROM `arocrm_storagelocation` WHERE `name` LIKE '%Quarantine%' AND `cf_nrl_plantmaster561_id` ='".$plantid."'");
					$storeid = $this->db->query_result($storagechk,0,'storagelocationid');
					$upserial = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `name`='".$serialno[$i]."'");
					}
				}
				$sono = $_POST['cf_nrl_salesorder679_id'];
				$inventoryqtychk = $this->db->pquery("SELECT SUM(quantity) AS quantity FROM arocrm_inventoryproductrel WHERE id = '".$sono."'");
				$invrow = $this->db->fetch_array($inventoryqtychk);
				$qty = $invrow['quantity'];
				
				$prevobd = $this->db->pquery("SELECT SUM(arocrm_outbounddelivery_line_item_lineitem.cf_2014) AS obdqty FROM arocrm_outbounddelivery INNER JOIN arocrm_outbounddelivery_line_item_lineitem ON arocrm_outbounddelivery_line_item_lineitem.outbounddeliveryid = arocrm_outbounddelivery.outbounddeliveryid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_outbounddelivery.outbounddeliveryid WHERE arocrm_outbounddelivery.cf_nrl_salesorder679_id = '".$sono."'");
				$prevrow = $this->db->fetch_array($prevobd);
				$obdqty = $prevrow['obdqty'];
				$totalcount = $request->get('totalRowCount_Line_Item');
				$exp = explode(",",$totalcount);
				$linecount = count($exp);
				for($j=1;$j<=$linecount;$j++)
				{
					$obdqty = $obdqty + $request->get('cf_2014_'.$j);
				}
				if($obdqty == $qty)
				{					
					$this->db->pquery("UPDATE arocrm_salesordercf SET cf_5199 = 'Done' WHERE salesorderid = '".$sono."'");
				}
			}
			
			
			
			if($module == 'GoodsIssue')
			{
				$giid = $recordModel->getId();
				$gichk = $this->db->pquery("SELECT arocrm_goodsissue.*, arocrm_goodsissuecf.*, arocrm_goodsissue_line_item_lineitem.* FROM arocrm_goodsissue 
				INNER JOIN arocrm_goodsissue_line_item_lineitem ON arocrm_goodsissue_line_item_lineitem.goodsissueid = arocrm_goodsissue.goodsissueid 
				INNER JOIN arocrm_goodsissuecf ON arocrm_goodsissuecf.goodsissueid = arocrm_goodsissue.goodsissueid 
				WHERE arocrm_goodsissue.goodsissueid = '".$giid."'");
				$status = $this->db->query_result($gichk,0,'cf_4834');
				$serial =  $this->db->query_result($gichk,0,'cf_3179');
				$serialno = explode(",",$serial);
				$scnt = count($serialno);
				for($i=0;$i<$scnt;$i++)
				{
					if($status == 'Cancelled')
					{
						$serialchk = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name`='".$serialno[$i]."'");
						$serialid = $this->db->query_result($serialchk,0,'serialnumberid');
						$upserialno = $this->db->pquery("UPDATE arocrm_serialnumbercf SET cf_2834 = '1' WHERE serialnumberid = '".$serialid."'");
						$plantid = $this->db->query_result($serialchk,0,'cf_nrl_plantmaster496_id');
						$storagechk = $this->db->pquery("SELECT * FROM `arocrm_storagelocation` WHERE `name` LIKE '%Quarantine%' AND `cf_nrl_plantmaster561_id` ='".$plantid."'");
						$storeid = $this->db->query_result($storagechk,0,'storagelocationid');
						$upserial = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `name`='".$serialno[$i]."'");
					}
				}
			}
			
			
			if($module == 'InboundDelivery')
			{
				$ibdid = $recordModel->getId();
				$ibdchk = $this->db->pquery("SELECT arocrm_inbounddelivery.*, arocrm_inbounddeliverycf.*, arocrm_inbounddelivery_line_item_lineitem.* FROM arocrm_inbounddelivery 
				INNER JOIN arocrm_inbounddelivery_line_item_lineitem ON arocrm_inbounddelivery_line_item_lineitem.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
				INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
				WHERE arocrm_inbounddelivery.inbounddeliveryid = '".$ibdid."'");
				$status = $this->db->query_result($ibdchk,0,'cf_3659');
				$serial =  $this->db->query_result($ibdchk,0,'cf_2888');
				$serialno = explode(",",$serial);
				$scnt = count($serialno);
				for($i=0;$i<$scnt;$i++)
				{
					if($status == 'Cancelled')
					{
						$serialchk = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name`='".$serialno[$i]."'");
						$serialid = $this->db->query_result($serialchk,0,'serialnumberid');
						$delserial = $this->db->pquery("DELETE FROM `arocrm_serialnumber` WHERE name = '".$serialno[$i]."'");
						$delserialcf = $this->db->pquery("DELETE FROM `arocrm_serialnumbercf` WHERE serialnumberid = '".$serialid."'");
					}
				}
			}
			if($module == 'GoodsReceipt')
			{
				$grid = $recordModel->getId();
				$grchk = $this->db->pquery("SELECT arocrm_goodsreceipt.*, arocrm_goodsreceiptcf.*, arocrm_goodsreceipt_line_item_details_lineitem.* FROM arocrm_goodsreceipt 
				INNER JOIN arocrm_goodsreceipt_line_item_details_lineitem ON arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid = arocrm_goodsreceipt.goodsreceiptid 
				INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt.goodsreceiptid 
				WHERE arocrm_goodsreceipt.goodsreceiptid = '".$grid."'");
				$status = $this->db->query_result($grchk,0,'cf_4824');
				$serial =  $this->db->query_result($grchk,0,'cf_3003');
				$serialno = explode(",",$serial);
				$scnt = count($serialno);
				for($i=0;$i<$scnt;$i++)
				{
					if($status == 'Cancelled')
					{
						$serialchk = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name`='".$serialno[$i]."'");
						$serialid = $this->db->query_result($serialchk,0,'serialnumberid');
						$upserialno = $this->db->pquery("UPDATE arocrm_serialnumbercf SET cf_2834 = '0' WHERE serialnumberid = '".$serialid."'");
						$plantid = $this->db->query_result($serialchk,0,'cf_nrl_plantmaster496_id');
						$storagechk = $this->db->pquery("SELECT * FROM `arocrm_storagelocation` WHERE `name` LIKE '%Quarantine%' AND `cf_nrl_plantmaster561_id` ='".$plantid."'");
						$storeid = $this->db->query_result($storagechk,0,'storagelocationid');
						$upserial = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `name`='".$serialno[$i]."'");
					}
				}
			}
			if($module == 'PurchaseOrder')
			{
				$advpay = $_POST['advpay'];
				$len = count($advpay);
				$advval = implode(',',$advpay);
				$advdebitpay = $_POST['advdebitpay'];
				$debitlen = count($advdebitpay);
				$advdebitval = implode(',',$advdebitpay);
				$advcreditpay = $_POST['advcreditpay'];
				$creditlen = count($advcreditpay);
				$advcreditval = implode(',',$advcreditpay);
				$poid = $recordModel->getId();
				$upsql = "UPDATE `arocrm_purchaseorder` SET `advancepaymentid` = ?, `debitpaymentid` = ?, `creditpaymentid` = ?  WHERE `purchaseorderid` = ?";
				$upqry = $this->db->pquery($upsql,array($advval,$advdebitval,$advcreditval,$poid));
				for($i=0;$i<$len;$i++)
				{
					$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_4699 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array('Used',$advpay[$i]));
				}
				for($i=0;$i<$debitlen;$i++)
				{
					$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_4699 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array('Used',$advdebitpay[$i]));
				}
				for($i=0;$i<$creditlen;$i++)
				{
					$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_4699 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array('Used',$advcreditpay[$i]));
				}
			}

			if($module == 'Invoice')
			{
				$soid = $_POST['salesorder_id'];
				$sql =  $this->db->pquery("SELECT arocrm_salesorder.*, arocrm_salesordercf.*, arocrm_crmentity.* FROM arocrm_salesorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
		INNER JOIN arocrm_salesordercf on arocrm_salesordercf.salesorderid = arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = '0' AND arocrm_salesorder.salesorderid = '".$soid."'");
		$rowqry = $this->db->fetch_array($sql);
		$reference = $rowqry['cf_3286'];
				if($reference == 'Against Warranty')
				{
					$totaldiscountamount = $_POST['subtotal']; 
				}
				else
				{
				$monthlyunit = $_POST['overallmonthlycashamount'];
				$monthlytotal = $_POST['totaloverallmonthlycashamount'];
				$monthlycashpercent = $_POST['overallmonthlycashpercent'];
				$monthlycashpercentval = $_POST['overallmonthlycashpercentval'];
				$monthlytargetpercent = $_POST['overallmonthlytargetpercent'];
				$monthlytargetpercentval = $_POST['overallmonthlytargetpercentval'];
				$monthlyretailerpercent = $_POST['overallmonthlyretailerpercent'];
				$monthlyretailerpercentval = $_POST['overallmonthlyretailerpercentval'];
				$quarterlyunit = $_POST['overallquarterlycashamount'];
				$quarterlytotal = $_POST['totaloverallquarterlycashamount'];
				$quarterlycashpercent = $_POST['overallquarterlycashpercent'];
				$quarterlycashpercentval = $_POST['overallquarterlycashpercentval'];
				$quarterlytargetpercent = $_POST['overallquarterlytargetpercent'];
				$quarterlytargetpercentval = $_POST['overallquarterlytargetpercentval'];
				$quarterlyretailerpercent = $_POST['overallquarterlyretailerpercent'];
				$quarterlyretailerpercentval = $_POST['overallquarterlyretailerpercentval'];
				$halfyearlyunit = $_POST['overallhalfyearlycashamount'];
				$halfyearlytotal = $_POST['totaloverallhalfyearlycashamount'];
				$halfyearlycashpercent = $_POST['overallhalfyearlycashpercent'];
				$halfyearlycashpercentval = $_POST['overallhalfyearlycashpercentval'];
				$halfyearlytargetpercent = $_POST['overallhalfyearlytargetpercent'];
				$halfyearlytargetpercentval = $_POST['overallhalfyearlytargetpercentval'];
				$halfyearlyretailerpercent = $_POST['overallhalfyearlyretailerpercent'];
				$halfyearlyretailerpercentval = $_POST['overallhalfyearlyretailerpercentval'];
				$annuallyunit = $_POST['overallannuallycashamount'];
				$annuallytotal = $_POST['totaloverallannuallycashamount'];
				$annuallycashpercent = $_POST['overallannuallycashpercent'];
				$annuallycashpercentval = $_POST['overallannuallycashpercentval'];
				$annuallytargetpercent = $_POST['overallannuallytargetpercent'];
				$annuallytargetpercentval = $_POST['overallannuallytargetpercentval'];
				$annuallyretailerpercent = $_POST['overallannuallyretailerpercent'];
				$annuallyretailerpercentval = $_POST['overallannuallyretailerpercentval'];
				$overalladvancepercent = $_POST['overalladvancepercent'];
				$overalladvancepercentval = $_POST['overalladvancepercentval'];
				$overallsamedaypercent = $_POST['overallsamedaypercent'];
				$overallsamedaypercentval = $_POST['overallsamedaypercentval'];
				$overallsamedaycashpercent = $_POST['overallsamedaycashpercent'];
				$overallsamedaycashpercentval = $_POST['overallsamedaycashpercentval'];
				$overall7dayspercent = $_POST['overall7dayspercent'];
				$overall7dayspercentval = $_POST['overall7dayspercentval'];
				$overall15dayspercent = $_POST['overall15dayspercent'];
				$overall15dayspercentval = $_POST['overall15dayspercentval'];
				$overall30dayspercent = $_POST['overall30dayspercent'];
				$overall30dayspercentval = $_POST['overall30dayspercentval'];
				$samedayinvid = $_POST['samedayInvoiceId'];
				$samedaycashinvid = $_POST['samedaycashInvoiceId'];
				$within7daysinvid = $_POST['within7daysInvoiceId'];
				$within15daysinvid = $_POST['within15daysInvoiceId'];
				$within30daysinvid = $_POST['within30daysInvoiceId'];
				$totaldiscountamount = $monthlytotal + $monthlycashpercentval + $monthlytargetpercentval + $monthlyretailerpercentval + $quarterlytotal + $quarterlycashpercentval + $quarterlytargetpercentval + $quarterlyretailerpercentval + $halfyearlytotal + $halfyearlycashpercentval + $halfyearlytargetpercentval + $halfyearlyretailerpercentval + $annuallytotal + $annuallycashpercentval + $annuallytargetpercentval + $annuallyretailerpercentval+$overalladvancepercentval+$overallsamedaypercentval+$overallsamedaycashpercentval + $overall7dayspercentval+$overall15dayspercentval+$overall30dayspercentval;
				$schemediscount = $_POST['schemediscount'];
				}
				$advpay = $_POST['advpay'];
				$len = count($advpay);
				$advval = implode(',',$advpay);
				$advdebitpay = $_POST['advdebitpay'];
				$debitlen = count($advdebitpay);
				$advdebitval = implode(',',$advdebitpay);
				$advcreditpay = $_POST['advcreditpay'];
				$creditlen = count($advcreditpay);
				$advcreditval = implode(',',$advcreditpay);
				$type = $_POST['cf_3288'];
				$invid = $recordModel->getId();
				$editmode = $_REQUEST['record'];
				if($editmode == '')
				{
				$upsql = "UPDATE `arocrm_invoice` SET `schemediscount` = ?, `advancepaymentid` = ?, `debitpaymentid` = ?, `creditpaymentid` = ?, `overallmonthlycashamount` = ?, `totaloverallmonthlycashamount` = ?, `overallmonthlycashpercent` = ?, `overallmonthlycashpercentval` = ?, `overallmonthlytargetpercent` = ?, `overallmonthlytargetpercentval` = ?, `overallmonthlyretailerpercent` = ?, `overallmonthlyretailerpercentval` = ?, `overallquarterlycashamount` = ?, `totaloverallquarterlycashamount` = ?, `overallquarterlycashpercent` = ?, `overallquarterlycashpercentval` = ?, `overallquarterlytargetpercent` = ?, `overallquarterlytargetpercentval`= ?, `overallquarterlyretailerpercent`= ?, `overallquarterlyretailerpercentval`= ?, `overallhalfyearlycashamount`= ?, `totaloverallhalfyearlycashamount` = ?, `overallhalfyearlycashpercent` = ?, `overallhalfyearlycashpercentval` = ?, `overallhalfyearlytargetpercent` = ?, `overallhalfyearlytargetpercentval` = ?, `overallhalfyearlyretailerpercent` = ?, `overallhalfyearlyretailerpercentval` = ?, `overallannuallycashamount` = ?, `totaloverallannuallycashamount` = ?, `overallannuallycashpercent` = ?, `overallannuallycashpercentval` = ?, `overallannuallytargetpercent` = ?, `overallannuallytargetpercentval` = ?, `overallannuallyretailerpercent` = ?, `overallannuallyretailerpercentval` = ?, `overalladvancepercent` = ?, `overalladvancepercentval` = ?, `overallsamedaypercent` = ?, `overallsamedaypercentval` = ?, `samedayinvoiceid` = ?, `overallsamedaycashpercent` = ?, `overallsamedaycashpercentval` = ?, `samedaycashinvoiceid` = ?, `overall7dayspercent` = ?, `overall7dayspercentval` = ?, `within7daysinvoiceid` = ?, `overall15dayspercent` = ?, `overall15dayspercentval` = ?, `within15daysinvoiceid` = ?, `overall30dayspercent`= ?, `overall30dayspercentval` = ?, `within30daysinvoiceid` = ?, `discount_amount` = ?  WHERE `invoiceid` = ?";
				$upqry = $this->db->pquery($upsql,array($schemediscount,$advval,$advdebitval,$advcreditval,$monthlyunit,$monthlytotal,$monthlycashpercent,$monthlycashpercentval,$monthlytargetpercent,$monthlytargetpercentval,$monthlyretailerpercent,$monthlyretailerpercentval,$quarterlyunit,$quarterlytotal,$quarterlycashpercent,$quarterlycashpercentval,$quarterlytargetpercent,$quarterlytargetpercentval,$quarterlyretailerpercent,$quarterlyretailerpercentval,$halfyearlyunit,$halfyearlytotal,$halfyearlycashpercent,$halfyearlycashpercentval,$halfyearlytargetpercent,$halfyearlytargetpercentval,$halfyearlyretailerpercent,$halfyearlyretailerpercentval,$annuallyunit,$annuallytotal,$annuallycashpercent,$annuallycashpercentval,$annuallytargetpercent,$annuallytargetpercentval,$annuallyretailerpercent,$annuallyretailerpercentval,$overalladvancepercent,$overalladvancepercentval,$overallsamedaypercent,$overallsamedaypercentval,$samedayinvid,$overallsamedaycashpercent,$overallsamedaycashpercentval,$samedaycashinvid,$overall7dayspercent,$overall7dayspercentval,$within7daysinvid,$overall15dayspercent,$overall15dayspercentval,$within15daysinvid,$overall30dayspercent,$overall30dayspercentval,$within30daysinvid,$totaldiscountamount,$invid));
				
				$totalrow = $_POST['totalProductCount'];
				for($i=1; $i<=$totalrow;$i++)
				{
					$productid = $_POST['hdnProductId'.$i];
					$quantity = $_POST['qty'.$i];
					$query =  $this->db->pquery("SELECT arocrm_products.*, arocrm_productcf.*, arocrm_crmentity.* FROM arocrm_products
		           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
				   INNER JOIN arocrm_productcf ON arocrm_productcf.productid=arocrm_products.productid
		           WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$productid);
				   $row = $this->db->fetch_array($query);
				   $point = $row['cf_5189'];
				   $totalpoint = $point * $quantity;
				   $allpoint = $allpoint + $totalpoint;
				}
				$accountid = $_POST['account_id'];
				$accsql = $this->db->pquery("SELECT arocrm_account.*, arocrm_accountscf.* FROM arocrm_account INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$accountid."'");
				$accrow = $this->db->fetch_array($accsql);
				$custpoint = $accrow['cf_5191']; 
				$amount = $accrow['cf_5193'];
				if($custpoint == '')
				{
					$custpoint = 0;
				}
				if($amount == '')
				{
					$amount = 0;
				}
				$allpoint = $allpoint + $custpoint;
				if($allpoint >= 200 && $allpoint < 300)
					{
						$incentive = 1000;
					}
					else if($allpoint >= 300 && $allpoint < 600)
					{
						$incentive = 2000;
					}
					else if($allpoint >= 600 && $allpoint < 1200)
					{
						$incentive = 4500;
					}
					else if($allpoint >= 1200 && $allpoint < 3000)
					{
						$incentive = 10000;
					}
					else if($allpoint >= 3000 && $allpoint < 6000)
					{
						$incentive = 27000;
					}
					else if($allpoint >= 6000 && $allpoint < 9000)
					{
						$incentive = 60000;
					}
					else if($allpoint >= 9000 && $allpoint < 30000)
					{
						$incentive = 200000;
					}
					else if($allpoint >= 30000)
					{
						$incentive = 350000;
					}
					else
					{
						$incentive = 0;
					}
					$schemediscount = $schemediscount + $amount;
				$upacc = $this->db->pquery("UPDATE arocrm_accountscf SET cf_5191 = '".$allpoint."', cf_5193 = '".$schemediscount."' WHERE accountid = '".$accountid."'");
				if($type == 'Purchase Invoice')
				{
					for($i=0;$i<$len;$i++)
					{
						$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_4699 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advpay[$i]));
					}
					for($i=0;$i<$debitlen;$i++)
					{
						$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_4699 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advdebitpay[$i]));
					}
					for($i=0;$i<$creditlen;$i++)
					{
						$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_4699 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advcreditpay[$i]));
					}
				}
				else if($type == 'Direct Sales')
				{
					$totalrow = $_POST['totalProductCount'];
					for($i=1; $i<=$totalrow;$i++)
					{
						$serialno = $_POST['serialno'.$i];
						$serials = explode(',',$serialno);
						$serialcnt = count($serials);
						for($j=0;$j<$serialcnt;$j++)
						{
							$upserial = $this->db->pquery("UPDATE arocrm_serialnumbercf SET cf_2834 = 2, cf_5130 = '".$invid."' WHERE cf_1258 = '".$serials[$j]."'");
							
							$quany = 1;
		$trandate = $request->get('cf_4627');
			$tmp = explode("-", $trandate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serials[$j]."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','DI-O','".$serdetail['cf_1256']."','".$serials[$j]."','".$prevstk."','".$curstk."')");
						}
					}
					for($i=0;$i<$len;$i++)
					{
						$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advpay[$i]));
					}
					for($i=0;$i<$debitlen;$i++)
					{
						$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advdebitpay[$i]));
					}
					for($i=0;$i<$creditlen;$i++)
					{
						$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advcreditpay[$i]));
					}
				}
				else
				{
					for($i=0;$i<$len;$i++)
					{
						$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advpay[$i]));
					}
					for($i=0;$i<$debitlen;$i++)
					{
						$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advdebitpay[$i]));
					}
					for($i=0;$i<$creditlen;$i++)
					{
						$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
						$upqrycp = $this->db->pquery($upcustpay,array('Used',$advcreditpay[$i]));
					}
				}
				}
				else
				{
					$upsql = "UPDATE `arocrm_invoice` SET `schemediscount` = ?, `overallmonthlycashamount` = ?, `totaloverallmonthlycashamount` = ?, `overallmonthlycashpercent` = ?, `overallmonthlycashpercentval` = ?, `overallmonthlytargetpercent` = ?, `overallmonthlytargetpercentval` = ?, `overallmonthlyretailerpercent` = ?, `overallmonthlyretailerpercentval` = ?, `overallquarterlycashamount` = ?, `totaloverallquarterlycashamount` = ?, `overallquarterlycashpercent` = ?, `overallquarterlycashpercentval` = ?, `overallquarterlytargetpercent` = ?, `overallquarterlytargetpercentval`= ?, `overallquarterlyretailerpercent`= ?, `overallquarterlyretailerpercentval`= ?, `overallhalfyearlycashamount`= ?, `totaloverallhalfyearlycashamount` = ?, `overallhalfyearlycashpercent` = ?, `overallhalfyearlycashpercentval` = ?, `overallhalfyearlytargetpercent` = ?, `overallhalfyearlytargetpercentval` = ?, `overallhalfyearlyretailerpercent` = ?, `overallhalfyearlyretailerpercentval` = ?, `overallannuallycashamount` = ?, `totaloverallannuallycashamount` = ?, `overallannuallycashpercent` = ?, `overallannuallycashpercentval` = ?, `overallannuallytargetpercent` = ?, `overallannuallytargetpercentval` = ?, `overallannuallyretailerpercent` = ?, `overallannuallyretailerpercentval` = ?, `overalladvancepercent` = ?, `overalladvancepercentval` = ?, `overallsamedaypercent` = ?, `overallsamedaypercentval` = ?, `overallsamedaycashpercent` = ?, `overallsamedaycashpercentval` = ?, `overall7dayspercent` = ?, `overall7dayspercentval` = ?, `overall15dayspercent` = ?, `overall15dayspercentval` = ?, `overall30dayspercent`= ?, `overall30dayspercentval` = ?, `discount_amount` = ?  WHERE `invoiceid` = ?";
				$upqry = $this->db->pquery($upsql,array($schemediscount,$monthlyunit,$monthlytotal,$monthlycashpercent,$monthlycashpercentval,$monthlytargetpercent,$monthlytargetpercentval,$monthlyretailerpercent,$monthlyretailerpercentval,$quarterlyunit,$quarterlytotal,$quarterlycashpercent,$quarterlycashpercentval,$quarterlytargetpercent,$quarterlytargetpercentval,$quarterlyretailerpercent,$quarterlyretailerpercentval,$halfyearlyunit,$halfyearlytotal,$halfyearlycashpercent,$halfyearlycashpercentval,$halfyearlytargetpercent,$halfyearlytargetpercentval,$halfyearlyretailerpercent,$halfyearlyretailerpercentval,$annuallyunit,$annuallytotal,$annuallycashpercent,$annuallycashpercentval,$annuallytargetpercent,$annuallytargetpercentval,$annuallyretailerpercent,$annuallyretailerpercentval,$overalladvancepercent,$overalladvancepercentval,$overallsamedaypercent,$overallsamedaypercentval,$overallsamedaycashpercent,$overallsamedaycashpercentval,$overall7dayspercent,$overall7dayspercentval,$overall15dayspercent,$overall15dayspercentval,$overall30dayspercent,$overall30dayspercentval,$totaldiscountamount,$invid));
				
				}
				$category = $_POST['productcategory'];
				$year = $_POST['cf_4623'];
				$month = $_POST['cf_4625'];
				$chkinv = $this->db->pquery("SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice 
				INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
				WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4623 = '".$year."' AND arocrm_invoicecf.cf_4625 = '".$month."'");
				$chkrows = $this->db->num_rows($chkinv);
				for($k=0;$k<$chkrows;$k++)
				{
				
					$invoiceid = $this->db->query_result($chkinv,$k,'invoiceid');
					$chkqry = $this->db->pquery("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$invoiceid."'");
					$i = 0;
					if($category == '4W')
					{
						$qty4w = 0;
						while($rowqry = $this->db->fetch_array($chkqry))
						{
							$qty4w = $qty4w + $this->db->query_result($chkqry,$i,'quantity');
							$i++;
						}
					}
					else if($category == '2W')
					{
						$qty2w = 0;
						while($rowqry = $this->db->fetch_array($chkqry))
						{
							$qty2w = $qty2w + $this->db->query_result($chkqry,$i,'quantity');
							$i++;
						}
					}
					else if($category == 'IB')
					{
						$qtyib = 0;
						while($rowqry = $this->db->fetch_array($chkqry))
						{
							$qtyib = $qtyib + $this->db->query_result($chkqry,$i,'quantity');
							$i++;
						}
					}
					else if($category == 'ER')
					{
						$qtyer = 0;
						while($rowqry = $this->db->fetch_array($chkqry))
						{
							$qtyer = $qtyer + $this->db->query_result($chkqry,$i,'quantity');
							$i++;
						}
					}
					
					$productall = $this->db->pquery("SELECT arocrm_products.*, arocrm_crmentity.* FROM arocrm_products 
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid 
					WHERE arocrm_crmentity.deleted = 0");
					$p = 0;
					while($rowpro = $this->db->fetch_array($productall))
					{
						$productid = $this->db->query_result($productall,$p,'productid');
						$quantity[$productid] = 0;
						$chkqry = $this->db->pquery("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$invoiceid."' AND `productid` = '".$productid."'");
						$c = 0;
						$qty = 0;
						while($rowqry = $this->db->fetch_array($chkqry))
						{
							$qty = $qty + $this->db->query_result($chkqry,$c,'quantity');
							$c++;
						}
						$quantity[$productid] = $qty;
						$p++;
					}
				}
				$chksp = $this->db->pquery("SELECT arocrm_salesplan.*, arocrm_salesplancf.* FROM arocrm_salesplan INNER JOIN arocrm_salesplancf ON arocrm_salesplancf.salesplanid = arocrm_salesplan.salesplanid INNER JOIN arocrm_crmentity ON arocrm_salesplan.salesplanid = arocrm_crmentity.crmid WHERE arocrm_crmentity.deleted = 0");
				$cnt = 0;
				while($rowsp = $this->db->fetch_array($chksp))
				{
					$fiscalyear = $this->db->query_result($chksp,$cnt,'cf_3506');
					$quartermonth = $this->db->query_result($chksp,$cnt,'cf_3502');
					$spid = $this->db->query_result($chksp,$cnt,'salesplanid');
					$qmnth = explode(" - ", $quartermonth);
					if($fiscalyear == $year)
					{
						$productall = $this->db->pquery("SELECT arocrm_products.*, arocrm_crmentity.* FROM arocrm_products 
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid 
						WHERE arocrm_crmentity.deleted = 0");
						$p = 0;
						while($rowpro = $this->db->fetch_array($productall))
						{
							$productid = $this->db->query_result($productall,$p,'productid');
							$procategory = $this->db->query_result($productall,$p,'productcategory');
							if($procategory == '4W')
							{
								if($month == $qmnth[0])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_4_wheeler_battery_lineitem` SET cf_3518 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[1])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_4_wheeler_battery_lineitem` SET cf_3522 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[2])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_4_wheeler_battery_lineitem` SET cf_3526 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
							}
							if($procategory == '2W')
							{
								if($month == $qmnth[0])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_2_wheeler_battery_lineitem` SET cf_35138 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[1])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_2_wheeler_battery_lineitem` SET cf_3542 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[2])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_2_wheeler_battery_lineitem` SET cf_3546 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
							}
							if($category == 'IB')
							{
								if($month == $qmnth[0])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_inverter_battery_lineitem` SET cf_3576 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[1])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_inverter_battery_lineitem` SET cf_3580 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[2])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_inverter_battery_lineitem` SET cf_3584 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
							}
							if($category == 'ER')
							{
								if($month == $qmnth[0])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_e-rickshaw_battery_lineitem` SET cf_3558 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[1])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_e-rickshaw_battery_lineitem` SET cf_35262 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
								if($month == $qmnth[2])
								{
									$uppro = $this->db->pquery("UPDATE `arocrm_salesplan_e-rickshaw_battery_lineitem` SET cf_3566 = '".$quantity[$productid]."' WHERE salesplanid = '".$spid."' AND cf_3512 = '".$productid."'");
								}
							}
							$p++;
						}
					}
					$cnt++;
				}
				$chksb = $this->db->pquery("SELECT arocrm_salesbudget.*, arocrm_salesbudgetcf.* FROM arocrm_salesbudget 
				INNER JOIN arocrm_salesbudgetcf ON arocrm_salesbudgetcf.salesbudgetid = arocrm_salesbudget.salesbudgetid 
				INNER JOIN arocrm_crmentity ON arocrm_salesbudget.salesbudgetid = arocrm_crmentity.crmid 
				WHERE arocrm_salesbudget.salesbudgetid NOT IN
				(SELECT arocrm_salesbudget.cf_nrl_salesbudget772_id FROM arocrm_salesbudget 
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid 
				WHERE arocrm_crmentity.deleted = 0) AND arocrm_crmentity.deleted = 0");
				$chknum = $this->db->num_rows($chksb);
				for($j=0;$j<$chknum;$j++)
				{
					$fiscalyear = $this->db->query_result($chksb,$j,'cf_3424');
					if($fiscalyear == $year)
					{
						$salesbudgetid = $this->db->query_result($chksb,$j,'salesbudgetid');
						if($category == '4W')
						{
							if($month == 'April')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4421 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'May')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4427 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'June')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4449 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'July')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4463 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'August')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4469 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'September')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4475 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'October')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4481 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'November')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4487 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'December')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4493 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'January')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4499 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'February')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4505 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'March')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4511 = '".$qty4w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
						}
						if($category == '2W')
						{
							if($month == 'April')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4421 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'May')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4427 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'June')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4449 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'July')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4463 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'August')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4469 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'September')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4475 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'October')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4481 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'November')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4487 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'December')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4493 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'January')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4499 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'February')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4505 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'March')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4511 = '".$qty2w."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
						}
						if($category == 'IB')
						{
							if($month == 'April')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4421 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'May')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4427 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'June')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4449 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'July')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4463 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'August')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4469 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'September')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4475 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'October')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4481 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'November')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4487 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'December')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4493 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'January')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4499 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'February')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4505 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'March')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4511 = '".$qtyib."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
						}
						if($category == 'ER')
						{
							if($month == 'April')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4421 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'May')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4427 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'June')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4449 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'July')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4463 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'August')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4469 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'September')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4475 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'October')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4481 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'November')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4487 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'December')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4493 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'January')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4499 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'February')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4505 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
							if($month == 'March')
							{
								$upqry = $this->db->pquery("UPDATE arocrm_salesbudget_category_wise_lineitem SET cf_4511 = '".$qtyer."'
									WHERE salesbudgetid = '".$salesbudgetid."' AND cf_4399 = '".$category."'");
							}
						}
					}
				}
				$grandtotal = $_POST['total'];
				$itemtotal = $_POST['subtotal'];
				$tax1 = $_POST['tax1_group_amount'];
				$tax2 = $_POST['tax2_group_amount'];
				$tax3 = $_POST['tax3_group_amount'];
				$taxtotal = $tax1 + $tax2 + $tax3;
				$posttaxtotal = $itemtotal + $taxtotal;
				$advance = $_POST['advancePayment'];
				$debit = $_POST['debit'];
				$credit = $_POST['credit'];
				$type = $_POST['cf_3288'];
				if($type == 'Purchase Invoice')
				{
					$grid = $_POST['cf_nrl_goodsreceipt721_id'];
					$qryin = $this->db->pquery("SELECT arocrm_inbounddelivery.cf_nrl_purchaseorder573_id FROM arocrm_inbounddelivery
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid
					WHERE arocrm_inbounddelivery.inbounddeliveryid IN (SELECT arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id FROM arocrm_goodsreceipt
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid
	WHERE arocrm_goodsreceipt.goodsreceiptid = '".$grid."')");
					$po = $this->db->query_result($qryin,0,'cf_nrl_purchaseorder573_id');
					$qrypo = $this->db->pquery("SELECT arocrm_purchaseorder.* FROM arocrm_purchaseorder
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid
					WHERE arocrm_purchaseorder.purchaseorderid = '".$po."'");
					$advancepaymentid = $this->db->query_result($qrypo,0,'advancepaymentid');
					$debitpaymentid = $this->db->query_result($qrypo,0,'debitpaymentid');
					$creditpaymentid = $this->db->query_result($qrypo,0,'creditpaymentid');

					$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_3331 = ?, cf_3300 = ?, cf_4760 = ?, cf_4762 = ?, cf_4764 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array($posttaxtotal,$grandtotal,$advance,$debit,$credit,$advancepaymentid));
					$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_3331 = ?, cf_3300 = ?, cf_4760 = ?, cf_4762 = ?, cf_4764 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array($posttaxtotal,$grandtotal,$advance,$debit,$credit,$debitpaymentid));
					$upcustpay = "UPDATE arocrm_vendorpaymentcf SET cf_3331 = ?, cf_3300 = ?, cf_4760 = ?, cf_4762 = ?, cf_4764 = ? WHERE arocrm_vendorpaymentcf.vendorpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array($posttaxtotal,$grandtotal,$advance,$debit,$credit,$creditpaymentid));

				}
				else
				{


					$soid = $_POST['salesorder_id'];
					$sqlso = "SELECT arocrm_salesorder.* FROM arocrm_salesorder
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
					WHERE arocrm_salesorder.salesorderid = '".$soid."'";

					$qryso = $this->db->pquery($sqlso);
					$advancepaymentid = $this->db->query_result($qryso,0,'advancepaymentid');
					$debitpaymentid = $this->db->query_result($qryso,0,'debitpaymentid');
					$creditpaymentid = $this->db->query_result($qryso,0,'creditpaymentid');

					$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3338 = ?, cf_3340 = ?, cf_4772 = ?, cf_4774 = ?, cf_4776 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array($posttaxtotal,$grandtotal,$advance,$debit,$credit,$advancepaymentid));
					$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3338 = ?, cf_3340 = ?, cf_4772 = ?, cf_4774 = ?, cf_4776 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array($posttaxtotal,$grandtotal,$advance,$debit,$credit,$debitpaymentid));
					$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3338 = ?, cf_3340 = ?, cf_4772 = ?, cf_4774 = ?, cf_4776 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array($posttaxtotal,$grandtotal,$advance,$debit,$credit,$creditpaymentid));
					
					$sqlgi = $this->db->pquery("SELECT arocrm_goodsissue.goodsissueid FROM arocrm_outbounddelivery 
	INNER JOIN arocrm_goodsissue on arocrm_goodsissue.cf_nrl_outbounddelivery617_id = arocrm_outbounddelivery.outbounddeliveryid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_outbounddelivery.outbounddeliveryid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_outbounddelivery.cf_nrl_salesorder679_id = '".$soid."' 
	AND arocrm_goodsissue.goodsissueid IN (SELECT arocrm_goodsissuecf.goodsissueid FROM arocrm_goodsissuecf 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsissuecf.goodsissueid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_goodsissuecf.cf_4927 = 'Not Done')");
					while($rowgi = $this->db->fetch_array($sqlgi))
					{
						$gi = $rowgi['goodsissueid'];
						$upgi = $this->db->pquery("UPDATE arocrm_goodsissuecf SET cf_4927 = 'Done' WHERE goodsissueid = '".$gi."'");
					}
				}
			}
			if($module == 'SalesOrder')
			{
				$reference = $_POST['cf_3286'];
				if($reference == 'Against Warranty')
				{
					$totaldiscountamount = $_POST['subtotal']; 
				}
				else
				{
				$monthlyunit = $_POST['overallmonthlycashamount'];
				$monthlytotal = $_POST['totaloverallmonthlycashamount'];
				$monthlycashpercent = $_POST['overallmonthlycashpercent'];
				$monthlycashpercentval = $_POST['overallmonthlycashpercentval'];
				$monthlytargetpercent = $_POST['overallmonthlytargetpercent'];
				$monthlytargetpercentval = $_POST['overallmonthlytargetpercentval'];
				$monthlyretailerpercent = $_POST['overallmonthlyretailerpercent'];
				$monthlyretailerpercentval = $_POST['overallmonthlyretailerpercentval'];
				$quarterlyunit = $_POST['overallquarterlycashamount'];
				$quarterlytotal = $_POST['totaloverallquarterlycashamount'];
				$quarterlycashpercent = $_POST['overallquarterlycashpercent'];
				$quarterlycashpercentval = $_POST['overallquarterlycashpercentval'];
				$quarterlytargetpercent = $_POST['overallquarterlytargetpercent'];
				$quarterlytargetpercentval = $_POST['overallquarterlytargetpercentval'];
				$quarterlyretailerpercent = $_POST['overallquarterlyretailerpercent'];
				$quarterlyretailerpercentval = $_POST['overallquarterlyretailerpercentval'];
				$halfyearlyunit = $_POST['overallhalfyearlycashamount'];
				$halfyearlytotal = $_POST['totaloverallhalfyearlycashamount'];
				$halfyearlycashpercent = $_POST['overallhalfyearlycashpercent'];
				$halfyearlycashpercentval = $_POST['overallhalfyearlycashpercentval'];
				$halfyearlytargetpercent = $_POST['overallhalfyearlytargetpercent'];
				$halfyearlytargetpercentval = $_POST['overallhalfyearlytargetpercentval'];
				$halfyearlyretailerpercent = $_POST['overallhalfyearlyretailerpercent'];
				$halfyearlyretailerpercentval = $_POST['overallhalfyearlyretailerpercentval'];
				$annuallyunit = $_POST['overallannuallycashamount'];
				$annuallytotal = $_POST['totaloverallannuallycashamount'];
				$annuallycashpercent = $_POST['overallannuallycashpercent'];
				$annuallycashpercentval = $_POST['overallannuallycashpercentval'];
				$annuallytargetpercent = $_POST['overallannuallytargetpercent'];
				$annuallytargetpercentval = $_POST['overallannuallytargetpercentval'];
				$annuallyretailerpercent = $_POST['overallannuallyretailerpercent'];
				$annuallyretailerpercentval = $_POST['overallannuallyretailerpercentval'];
				$overalladvancepercent = $_POST['overalladvancepercent'];
				$overalladvancepercentval = $_POST['overalladvancepercentval'];
				$overallsamedaypercent = $_POST['overallsamedaypercent'];
				$overallsamedaypercentval = $_POST['overallsamedaypercentval'];
				$overallsamedaycashpercent = $_POST['overallsamedaycashpercent'];
				$overallsamedaycashpercentval = $_POST['overallsamedaycashpercentval'];
				$overall7dayspercent = $_POST['overall7dayspercent'];
				$overall7dayspercentval = $_POST['overall7dayspercentval'];
				$overall15dayspercent = $_POST['overall15dayspercent'];
				$overall15dayspercentval = $_POST['overall15dayspercentval'];
				$overall30dayspercent = $_POST['overall30dayspercent'];
				$overall30dayspercentval = $_POST['overall30dayspercentval'];
				$totaldiscountamount = $monthlytotal + $monthlycashpercentval + $monthlytargetpercentval + $monthlyretailerpercentval + $quarterlytotal + $quarterlycashpercentval + $quarterlytargetpercentval + $quarterlyretailerpercentval + $halfyearlytotal + $halfyearlycashpercentval + $halfyearlytargetpercentval + $halfyearlyretailerpercentval + $annuallytotal + $annuallycashpercentval + $annuallytargetpercentval + $annuallyretailerpercentval+$overalladvancepercentval+$overallsamedaypercentval+$overallsamedaycashpercentval + $overall7dayspercentval+$overall15dayspercentval+$overall30dayspercentval;
				$schemediscount = $_POST['schemediscount'];
				}
				$advpay = $_POST['advpay'];
				$len = count($advpay);
				$advval = implode(',',$advpay);
				$advdebitpay = $_POST['advdebitpay'];
				$debitlen = count($advdebitpay);
				$advdebitval = implode(',',$advdebitpay);
				$advcreditpay = $_POST['advcreditpay'];
				$creditlen = count($advcreditpay);
				$advcreditval = implode(',',$advcreditpay);

				$soid = $recordModel->getId();
				
				$editmode = $_REQUEST['record'];
				if($editmode == '')
				{
				$upsql = "UPDATE `arocrm_salesorder` SET `schemediscount` = ?, `advancepaymentid` = ?, `debitpaymentid` = ?, `creditpaymentid` = ?,`overallmonthlycashamount` = ?, `totaloverallmonthlycashamount` = ?, `overallmonthlycashpercent` = ?, `overallmonthlycashpercentval` = ?, `overallmonthlytargetpercent` = ?, `overallmonthlytargetpercentval` = ?, `overallmonthlyretailerpercent` = ?, `overallmonthlyretailerpercentval` = ?, `overallquarterlycashamount` = ?, `totaloverallquarterlycashamount` = ?, `overallquarterlycashpercent` = ?, `overallquarterlycashpercentval` = ?, `overallquarterlytargetpercent` = ?, `overallquarterlytargetpercentval`= ?, `overallquarterlyretailerpercent`= ?, `overallquarterlyretailerpercentval`= ?, `overallhalfyearlycashamount`= ?, `totaloverallhalfyearlycashamount` = ?, `overallhalfyearlycashpercent` = ?, `overallhalfyearlycashpercentval` = ?, `overallhalfyearlytargetpercent` = ?, `overallhalfyearlytargetpercentval` = ?, `overallhalfyearlyretailerpercent` = ?, `overallhalfyearlyretailerpercentval` = ?, `overallannuallycashamount` = ?, `totaloverallannuallycashamount` = ?, `overallannuallycashpercent` = ?, `overallannuallycashpercentval` = ?, `overallannuallytargetpercent` = ?, `overallannuallytargetpercentval` = ?, `overallannuallyretailerpercent` = ?, `overallannuallyretailerpercentval` = ?, `overalladvancepercent` = ?, `overalladvancepercentval` = ?, `overallsamedaypercent` = ?, `overallsamedaypercentval` = ?, `overallsamedaycashpercent` = ?, `overallsamedaycashpercentval` = ?, `overall7dayspercent` = ?, `overall7dayspercentval` = ?, `overall15dayspercent` = ?, `overall15dayspercentval` = ?, `overall30dayspercent`= ?, `overall30dayspercentval` = ?, `discount_amount` = ?  WHERE `salesorderid` = ?";
				
				$upqry = $this->db->pquery($upsql,array($schemediscount,$advval,$advdebitval,$advcreditval,$monthlyunit,$monthlytotal,$monthlycashpercent,$monthlycashpercentval,$monthlytargetpercent,$monthlytargetpercentval,$monthlyretailerpercent,$monthlyretailerpercentval,$quarterlyunit,$quarterlytotal,$quarterlycashpercent,$quarterlycashpercentval,$quarterlytargetpercent,$quarterlytargetpercentval,$quarterlyretailerpercent,$quarterlyretailerpercentval,$halfyearlyunit,$halfyearlytotal,$halfyearlycashpercent,$halfyearlycashpercentval,$halfyearlytargetpercent,$halfyearlytargetpercentval,$halfyearlyretailerpercent,$halfyearlyretailerpercentval,$annuallyunit,$annuallytotal,$annuallycashpercent,$annuallycashpercentval,$annuallytargetpercent,$annuallytargetpercentval,$annuallyretailerpercent,$annuallyretailerpercentval,$overalladvancepercent,$overalladvancepercentval,$overallsamedaypercent,$overallsamedaypercentval,$overallsamedaycashpercent,$overallsamedaycashpercentval,$overall7dayspercent,$overall7dayspercentval,$overall15dayspercent,$overall15dayspercentval,$overall30dayspercent,$overall30dayspercentval,$totaldiscountamount,$soid));
				$subtotal = $_POST['subtotal'];
				$total = $_POST['total'];
				for($i=0;$i<$len;$i++)
				{
					$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array('Used',$advpay[$i]));
				}
				for($i=0;$i<$debitlen;$i++)
				{
					$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array('Used',$advdebitpay[$i]));
				}
				for($i=0;$i<$creditlen;$i++)
				{
					$upcustpay = "UPDATE arocrm_customerpaymentcf SET cf_3376 = ? WHERE arocrm_customerpaymentcf.customerpaymentid = ?";
					$upqrycp = $this->db->pquery($upcustpay,array('Used',$advcreditpay[$i]));
				}

				}
				else
				{
					$upsql = "UPDATE `arocrm_salesorder` SET `schemediscount` = ?,`overallmonthlycashamount` = ?, `totaloverallmonthlycashamount` = ?, `overallmonthlycashpercent` = ?, `overallmonthlycashpercentval` = ?, `overallmonthlytargetpercent` = ?, `overallmonthlytargetpercentval` = ?, `overallmonthlyretailerpercent` = ?, `overallmonthlyretailerpercentval` = ?, `overallquarterlycashamount` = ?, `totaloverallquarterlycashamount` = ?, `overallquarterlycashpercent` = ?, `overallquarterlycashpercentval` = ?, `overallquarterlytargetpercent` = ?, `overallquarterlytargetpercentval`= ?, `overallquarterlyretailerpercent`= ?, `overallquarterlyretailerpercentval`= ?, `overallhalfyearlycashamount`= ?, `totaloverallhalfyearlycashamount` = ?, `overallhalfyearlycashpercent` = ?, `overallhalfyearlycashpercentval` = ?, `overallhalfyearlytargetpercent` = ?, `overallhalfyearlytargetpercentval` = ?, `overallhalfyearlyretailerpercent` = ?, `overallhalfyearlyretailerpercentval` = ?, `overallannuallycashamount` = ?, `totaloverallannuallycashamount` = ?, `overallannuallycashpercent` = ?, `overallannuallycashpercentval` = ?, `overallannuallytargetpercent` = ?, `overallannuallytargetpercentval` = ?, `overallannuallyretailerpercent` = ?, `overallannuallyretailerpercentval` = ?, `overalladvancepercent` = ?, `overalladvancepercentval` = ?, `overallsamedaypercent` = ?, `overallsamedaypercentval` = ?, `overallsamedaycashpercent` = ?, `overallsamedaycashpercentval` = ?, `overall7dayspercent` = ?, `overall7dayspercentval` = ?, `overall15dayspercent` = ?, `overall15dayspercentval` = ?, `overall30dayspercent`= ?, `overall30dayspercentval` = ?,`discount_amount` = ?  WHERE `salesorderid` = ?";
				
				$upqry = $this->db->pquery($upsql,array($schemediscount,$monthlyunit,$monthlytotal,$monthlycashpercent,$monthlycashpercentval,$monthlytargetpercent,$monthlytargetpercentval,$monthlyretailerpercent,$monthlyretailerpercentval,$quarterlyunit,$quarterlytotal,$quarterlycashpercent,$quarterlycashpercentval,$quarterlytargetpercent,$quarterlytargetpercentval,$quarterlyretailerpercent,$quarterlyretailerpercentval,$halfyearlyunit,$halfyearlytotal,$halfyearlycashpercent,$halfyearlycashpercentval,$halfyearlytargetpercent,$halfyearlytargetpercentval,$halfyearlyretailerpercent,$halfyearlyretailerpercentval,$annuallyunit,$annuallytotal,$annuallycashpercent,$annuallycashpercentval,$annuallytargetpercent,$annuallytargetpercentval,$annuallyretailerpercent,$annuallyretailerpercentval,$overalladvancepercent,$overalladvancepercentval,$overallsamedaypercent,$overallsamedaypercentval,$overallsamedaycashpercent,$overallsamedaycashpercentval,$overall7dayspercent,$overall7dayspercentval,$overall15dayspercent,$overall15dayspercentval,$overall30dayspercent,$overall30dayspercentval,$totaldiscountamount,$soid));
				}

		$sqlcstdata = "SELECT * FROM `arocrm_account`
		INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_account`.`accountid`
		INNER JOIN `arocrm_accountscf` ON `arocrm_accountscf`.`accountid` = `arocrm_account`.`accountid`
		WHERE `arocrm_account`.`accountid` = '".$request->get('account_id')."' AND `arocrm_crmentity`.`deleted` = '0'";
		$qrycstdata =  $this->db->pquery($sqlcstdata);
   	$creditlimit = $this->db->query_result($qrycstdata,0,'cf_4313');
  	$creditdays = $this->db->query_result($qrycstdata,0,'cf_4315');
    $totalamt = $request->get('total');

		if(($creditlimit >= $totalamt) && ($creditdays > 0))
		{
			/*$upsale = "UPDATE `arocrm_salesorder` SET `sostatus` = 'Approved' WHERE `salesorderid` = '".$recordModel->getId()."'";
			$upsaleqry = $this->db->pquery($upsale); */
		}else{
	 	$upsale = "UPDATE `arocrm_salesorder` SET `sostatus` = 'Pending For Approval' WHERE `salesorderid` = '".$recordModel->getId()."'";
			$upsaleqry = $this->db->pquery($upsale);
		}

			}



			if($module == 'ServiceContracts')
			{
				$endconsumer = $_POST['cf_2969'];
				$mobile = $_POST['cf_3621'];
				$mail = $_POST['cf_3623'];
				$serialno = $_POST['cf_3628'];
				$street = $_POST['cf_3689'];
				$po = $_POST['cf_3691'];
				$city = $_POST['cf_3693'];
				$state = $_POST['cf_3695'];
				$country = $_POST['cf_3699'];
				$zip = $_POST['cf_3697'];
				$serial = $this->db->pquery("SELECT * FROM `arocrm_serialnumber` WHERE `name` = '".$serialno."'");
				$serialid = $this->db->query_result($serial,0,'serialnumberid');
				$consumername = explode(" ",$endconsumer);
				$fname = $consumername[0];
				$lname = $consumername[1];
				$cust = "SELECT arocrm_account.*, arocrm_crmentity .* FROM arocrm_account INNER JOIN arocrm_crmentity
				ON arocrm_crmentity.crmid = arocrm_account.accountid WHERE arocrm_crmentity.deleted = ? AND arocrm_account.accountname = ?";
				$custqry = $this->db->pquery($cust,array(0,'End Consumer'));
				$custid = $this->db->query_result($custqry,0,'accountid');
				$recordid = $recordModel->getId();
				$warranty = $this->db->pquery("SELECT arocrm_servicecontracts.*,arocrm_servicecontractscf.* FROM arocrm_servicecontracts
				INNER JOIN arocrm_servicecontractscf ON arocrm_servicecontracts.servicecontractsid=arocrm_servicecontractscf.servicecontractsid WHERE arocrm_servicecontracts.servicecontractsid = '".$recordid."'");
				$type = $this->db->query_result($warranty,0,'cf_3707');
				if($type == 'Replacement')
				{
					$oldwarranty = $this->db->query_result($warranty,0,'cf_nrl_servicecontracts149_id');
					$upqr = $this->db->pquery("UPDATE arocrm_servicecontractscf SET cf_3661 = 'Replaced' WHERE servicecontractsid = '".$oldwarranty."'");
				}
				/* Code */

			$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
			$recid = $this->db->query_result($mfcrmid,'0','id');
			$recid = (int)$recid + 1;

			$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
			`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
			VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','Contacts','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$$endconsumer."')";

			$crmenins = $this->db->pquery($crmin);

			$nextrecid = $recid;

			$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");

			$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'Contacts'");
			$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

			$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

			$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'Contacts'");

			$insrtchk = $this->db->pquery("SELECT arocrm_contactdetails.*, arocrm_crmentity.* FROM arocrm_contactdetails
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_contactdetails.contactid
			WHERE arocrm_contactdetails.firstname = '".$fname."' AND arocrm_contactdetails.lastname = '".$lname."' AND arocrm_contactdetails.email = '".$mail."'");
			$chknum = $this->db->num_rows($insrtchk);
			if($chknum == 0)
			{
				$insrtqry = $this->db->pquery("INSERT INTO `arocrm_contactdetails` (`contactid`,`accountid`,`firstname`,`lastname`, `email`, `mobile`) VALUES ('".$recid."','".$custid."','".$fname."','".$lname."', '".$mail."', '".$mobile."')");

				$ins_contact_cf = $this->db->pquery("INSERT INTO `arocrm_contactscf`(`contactid`) VALUES ('".$recid."')");

				$inscontactaddrs = $this->db->pquery("INSERT INTO `arocrm_contactaddress`(`contactaddressid`, `mailingcity`, `mailingstreet`, `mailingcountry`, `othercountry`, `mailingstate`, `mailingpobox`, `othercity`, `otherstate`, `mailingzip`, `otherzip`, `otherstreet`, `otherpobox`) VALUES ('".$recid."', '".$city."', '".$street."', '".$country."', '".$country."', '".$state."', '".$po."', '".$city."', '".$state."', '".$zip."', '".$zip."', '".$street."', '".$po."')");

				$inscontact = $this->db->pquery("INSERT INTO `arocrm_contactsubdetails`(`contactsubscriptionid`) VALUES ('".$recid."')");
			}
			else
			{
				$contactid = $this->db->query_result($insrtchk,'0','contactid');
				$updateqry = $this->db->pquery("UPDATE `arocrm_contactdetails` SET `accountid` = '".$custid."', `mobile` = '".$mobile."' WHERE `contactid` = '".$contactid."'");
				$updateaddress = $this->db->pquery("UPDATE `arocrm_contactaddress` SET `mailingcity`='".$city."',`mailingstreet`='".$street."',`mailingcountry`='".$country."',`othercountry`='".$country."',`mailingstate`='".$state."',`mailingpobox`='".$po."',`othercity`='".$city."',`otherstate`='".$state."',`mailingzip`='".$zip."',`otherzip`='".$zip."',`otherstreet`='".$street."',`otherpobox`='".$po."' WHERE `contactaddressid`='".$contactid."'");
			}
			$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");

			$ins_serial_contact = $this->db->pquery("INSERT INTO `arocrm_crmentityrel`(`crmid`, `module`, `relcrmid`, `relmodule`) VALUES ('".$recid."','Contacts','".$serialid."','SerialNumber')");

			$ins_serial_customer = $this->db->pquery("INSERT INTO `arocrm_crmentityrel`(`crmid`, `module`, `relcrmid`, `relmodule`) VALUES ('".$custid."','Accounts','".$serialid."','SerialNumber')");

			/* Code */
			}
			$sql = "SELECT tabid FROM arocrm_tab WHERE name = ?";
			$query = $this->db->pquery($sql,array($module));
			$tabid = $this->db->query_result($query,0,'tabid');

				//Code added by Rahul Sinha 25/01/2019 (Checkbox value save)//
			$listquery = "SELECT * FROM arocrm_field WHERE tabid = ? AND uitype = ?";
			$listresult = $this->db->pquery($listquery,array($tabid,56));
			$listcount = $this->db->num_rows($listresult);
			if($listcount>0)
			{
			  while($listrow = $this->db->fetch_array($listresult))
			  {
			    $listuitype = $listrow['uitype'];
				$listcolumnname = $listrow['columnname'];
			    $listfieldname = $_POST[''.$listcolumnname.''];
				if($listfieldname == 1)
				{
				  $tablename_array = $this->getFieldTableName($listcolumnname,$module);
				  $tablename = $tablename_array['tablename'];

				$sql12 = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$tablename."' AND TABLE_SCHEMA = 'arocrm_arodekcrm' AND COLUMN_KEY = 'PRI'";
				$result12 = $this->db->pquery($sql12);
				$primary_key = $this->db->fetch_array($result12);


				$primarykey = $primary_key['column_name'];

				$update_check_query = "UPDATE ".$tablename." SET ".$listcolumnname." = ? WHERE ".$primarykey." = ?";
				$update_check_result = $this->db->pquery($update_check_query,array(1,$recordModel->getId()));

				}
			  }
			}

			// End of Code Rahul SInha 25/01/2019 (Checkbox value save)//



			$blcksql = "SELECT * FROM arocrm_blocks WHERE tabid=? AND islineitem=?";
			$blckquery = $this->db->pquery($blcksql,array($tabid,1));
			$blckcnt = $this->db->num_rows($blckquery);
			for($i=0;$i<$blckcnt;$i++)
			{

				$blocklabel = $this->db->query_result($blckquery,$i,'blocklabel');
				$blocklabel=  preg_replace('/\s+/', '_', $blocklabel);
				$directmode = $_POST['directMode_'.$blocklabel];
				$totalRowCount = $_POST['totalRowCount_'.$blocklabel];

				$totalRowCount_array = explode(",",$totalRowCount);
				$totalRowCount_array_count = count($totalRowCount_array);

				$blockid = $this->db->query_result($blckquery,$i,'blockid');
				$chk = "SELECT * FROM `arocrm_".strtolower($module)."_".strtolower($blocklabel)."_lineitem` WHERE ".strtolower($module)."id=?";
				$chkquery = $this->db->pquery($chk,array($recordModel->getId()));
				$chkcount = $this->db->num_rows($chkquery);
				if($chkcount>0)
				{
					$delete_query = "DELETE FROM `arocrm_".strtolower($module)."_".strtolower($blocklabel)."_lineitem` WHERE ".strtolower($module)."id = ?";
					$delete_result = $this->db->pquery($delete_query, array($recordModel->getId()));
				}
				$fldsql = "SELECT * FROM arocrm_field WHERE tabid=? AND block=?";
				$fldquery = $this->db->pquery($fldsql,array($tabid,$blockid));
				$fldcnt = $this->db->num_rows($fldquery);
				for($k=0;$k<$totalRowCount_array_count;$k++)
				{
					$vals = array();
					$strd = "";
					$qmark = "";
					for($j=0;$j<$fldcnt;$j++)
					{
						$fieldlabel = $this->db->query_result($fldquery,$j,'fieldlabel');
						//$fieldlabel=  preg_replace('/\s+/', '_', $fieldlabel);
						$column = $this->db->query_result($fldquery,$j,'columnname');
						$fieldname = $this->db->query_result($fldquery,$j,'fieldname');
						$uitype = $this->db->query_result($fldquery,$j,'uitype');
						if($j==0){
						$strd = "`".$column."`";
						$qmark = $qmark."?";
						}
						else{
						$strd = $strd.",`".$column."`";
						$qmark = $qmark.",?";
						}
						if($uitype == '56')
						{
							if($_POST[$fieldname."_check_".$totalRowCount_array[$k]] == 'on')
							{
								array_push($vals,'1');
							}
							else
							{
								array_push($vals,$_POST[$fieldname."_".$totalRowCount_array[$k]]);
							}
						}
						else if($uitype == '7' || $uitype == '9' || $uitype == '71')
						{
							if($chkcount == 0 && $totalRowCount_array_count == 1 && $directmode == 0)
							{
								if($_POST[$fieldname] == '')
								{
									array_push($vals,'0');
								}
								else
								{
									array_push($vals,$_POST[$fieldname]);
								}
							}
							else
							{
								if($_POST[$fieldname."_".$totalRowCount_array[$k]] == ''){
									array_push($vals,'0');
								}else{
									array_push($vals,$_POST[$fieldname."_".$totalRowCount_array[$k]]);
								}	
							}
						
						}
						else if($uitype == '33')
						{
							$seldata = "";
							$cj = 1;
							if($chkcount == 0 && $totalRowCount_array_count == 1 && $directmode == 0)
							{
								$rwdata = $_POST[$fieldname];
							}
							else
							{
								$rwdata = $_POST[$fieldname."_".$totalRowCount_array[$k]];
							}
							foreach($rwdata as $rdata){
								if($cj==1){
								$seldata = $rdata;
								}else{
								$seldata = $seldata.",".$rdata;
								}

								$cj++;
							}
							array_push($vals,$seldata);
						}
						else if($chkcount == 0 && $totalRowCount_array_count == 1 && $directmode == 0)
						{
							array_push($vals,$_POST[$fieldname]);
						}
						else
						{
							array_push($vals,$_POST[$fieldname."_".$totalRowCount_array[$k]]);
						}
					}
				$blckfld = "INSERT INTO `arocrm_".strtolower($module)."_".strtolower($blocklabel)."_lineitem` (`".strtolower($module)."id`,".$strd.")values('".$recordModel->getId()."',".$qmark.")";
				
				$blcksqry = $this->db->pquery($blckfld,$vals);
				}
				
				
			}
			


		/*Code Ended here*/
		//Customising for Product Code --Roni Modak  31-10-2018 //


		//  PurchaseReq //
		//Code for PurchaseReq  added by Roni Modak 08-02-2019//

/*
		if($request->getModule()=='PurchaseReq'){
		$cf_2765values = $request->get('cf_2765');
		foreach($cf_2765values as $vals){
			$cf_2765_picklist = "UPDATE `arocrm_cf_2765` SET `presence` = ? WHERE `cf_2765` = ?";
			$cf_2765_result = $this->db->pquery($cf_2765_picklist,array('0',$vals));
		}
		}
*/
// End of PurchaseReq //



		//  Vendor //
		//Code for Vendor Master treat as customer added by 14-01-2019//

		if($request->getModule()=='Vendors'){
			$recorddata = $request->get('record');
			if($recorddata==""){
				$chk = $request->get('cf_3370_check');
				if($chk=='on'){

					$name = $request->get('vendorname');

					$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
					$recid = $this->db->query_result($mfcrmid,'0','id');
					$recid = (int)$recid + 1;

					$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
					VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','Accounts','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$name."')";

					$crmenins = $this->db->pquery($crmin);
					$nextrecid = $recid;
					$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");
					$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'Accounts'");
					$recvendid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

					$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

					$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'Accounts'");

					$ins_serial = $this->db->pquery("INSERT INTO `arocrm_account`(`accountid`, `account_no`, `accountname`, `parentid`, `account_type`,
						`industry`, `annualrevenue`, `rating`, `ownership`, `siccode`, `tickersymbol`, `phone`, `otherphone`, `email1`,
						 `email2`, `website`, `fax`, `employees`, `emailoptout`, `notify_owner`, `isconvertedfromlead`, `tags`,
						 `cf_nrl_companymaster576_id`, `cf_nrl_plantmaster183_id`, `vendorid`) VALUES
						  ('".$recid."','".$recvendid."','".$name."','','','','','','','','','','','','','','',
								'','','','','','','','".$recordModel->getId()."')");
        	$sqlins1 = $this->db->pquery("INSERT INTO `arocrm_accountscf`(`accountid`, `cf_2829`, `cf_2831`) VALUES ('".$recid."','','')");
					$sqlins2 = $this->db->pquery("INSERT INTO `arocrm_accountbillads`(`accountaddressid`, `bill_city`, `bill_code`, `bill_country`, `bill_state`, `bill_street`, `bill_pobox`) VALUES ('".$recid."','','','','','','')");
					$sqlins3 = $this->db->pquery("INSERT INTO `arocrm_accountshipads`(`accountaddressid`, `ship_city`, `ship_code`, `ship_country`, `ship_state`, `ship_pobox`, `ship_street`) VALUES ('".$recid."','','','','','','')");

          $ins_uppacc = $this->db->pquery("UPDATE `arocrm_vendor` SET `accountid` = '".$recid."' WHERE  `vendorid`= '".$recordModel->getId()."'");
					$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");

				}

			}else{



			 $chk = $request->get('cf_3370_check');

				if($chk=='on'){

					$mrdid = $this->db->pquery("SELECT * FROM `arocrm_vendor` where `vendorid` = '".$recorddata."'");
				  $mreccdid = $this->db->query_result($mrdid,'0','accountid');

					if($mreccdid==""){

					  $name = $request->get('vendorname');

						$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
						$recid = $this->db->query_result($mfcrmid,'0','id');
						$recid = (int)$recid + 1;

					$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
						VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','Accounts','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$name."')";

						$crmenins = $this->db->pquery($crmin);
						$nextrecid = $recid;
						$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");
						$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'Accounts'");
						$recvendid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

					  $nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;
						$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'Accounts'");
    				$ins_serial = $this->db->pquery("INSERT INTO `arocrm_account`(`accountid`, `account_no`, `accountname`, `parentid`, `account_type`,
							`industry`, `annualrevenue`, `rating`, `ownership`, `siccode`, `tickersymbol`, `phone`, `otherphone`, `email1`,
							 `email2`, `website`, `fax`, `employees`, `emailoptout`, `notify_owner`, `isconvertedfromlead`, `tags`,
							 `cf_nrl_companymaster576_id`, `cf_nrl_plantmaster183_id`, `vendorid`) VALUES
							  ('".$recid."','".$recvendid."','".$name."','','','','','','','','','','','','','','',
									'','','','','','','','".$recordModel->getId()."')");
	        	$sqlins1 = $this->db->pquery("INSERT INTO `arocrm_accountscf`(`accountid`, `cf_2829`, `cf_2831`) VALUES ('".$recid."','','')");
						$sqlins2 = $this->db->pquery("INSERT INTO `arocrm_accountbillads`(`accountaddressid`, `bill_city`, `bill_code`, `bill_country`, `bill_state`, `bill_street`, `bill_pobox`) VALUES ('".$recid."','','','','','','')");
						$sqlins3 = $this->db->pquery("INSERT INTO `arocrm_accountshipads`(`accountaddressid`, `ship_city`, `ship_code`, `ship_country`, `ship_state`, `ship_pobox`, `ship_street`) VALUES ('".$recid."','','','','','','')");

	          $ins_uppacc = $this->db->pquery("UPDATE `arocrm_vendor` SET `accountid` = '".$recid."' WHERE  `vendorid`= '".$recordModel->getId()."'");
						$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");

           }
				}


			}


		}
     /*End here*/
	 
//End of Vendor Master treat as customer added by 14-01-2019//



// Code for CustomerPayment code added by Roni Modak on 07-03-2019//

if($request->getModule()=='CustomerPayment'){
$recorddata = $request->get('record');
if($recorddata==""){
$reef = $request->get('cf_3335');
if($reef=='Sales Invoice Payment'){
$customer = $request->get('cf_nrl_accounts363_id');
$amount = $request->get('cf_3342');
$dirmod = $request->get('directMode_Payment_Details');
$getlimit = $this->db->pquery("SELECT * FROM `arocrm_accountscf` INNER JOIN  `arocrm_account` ON `arocrm_account`.`accountid` = `arocrm_accountscf`.`accountid` WHERE `arocrm_account`.`accountid` = '".$customer."'");
$curlimit = $this->db->query_result($getlimit,'0','cf_4313');
$custtype = $this->db->query_result($getlimit,'0','accounttype');


$newlimit = (float)$curlimit + (float)$amount;
$this->db->pquery("UPDATE `arocrm_accountscf` SET `cf_4313` = '".$newlimit."'  WHERE `accountid` = '".$customer."'");

$totalcount = $request->get('totalRowCount_Payment_Details');
$exp = explode(",",$totalcount);
$linecount = count($exp);
foreach($exp as $eachitm){
$valss = '';
if($dirmod == 1){
$valss = '_'.$eachitm;	
}
$type = $request->get('type'.$valss);	
if($type=='DebitNote'){
$inv = $request->get('cf_3346'.$valss);	
$dueamt = (float)$request->get('cf_3358'.$valss);
if($dueamt==0){
$this->db->pquery("UPDATE `arocrm_customerpaymentcf` SET `cf_3376` = 'Used'  WHERE `customerpaymentid` = '".$inv."'");
}	
}else{
$inv = $request->get('cf_3346'.$valss);	
$dueamt = (float)$request->get('cf_3358'.$valss);
$invtotal = (float)$request->get('cf_3352'.$valss);
$received = $invtotal - $dueamt;
$this->db->pquery("UPDATE `arocrm_invoice` SET `received` = '".$received."',`balance` = '".$dueamt."'  WHERE `invoiceid` = '".$inv."'");
if($dueamt==0){
$this->db->pquery("UPDATE `arocrm_invoice` SET `invoicestatus` = 'Paid'  WHERE `invoiceid` = '".$inv."'");
$dsql = "UPDATE `arocrm_salesorder_creditlimit` SET `amount` = '".$dueamt."',`status`='1'  WHERE `invid` = '".$inv."'";
$this->db->pquery($dsql);	
$cresql = $this->db->pquery("SELECT * FROM `arocrm_salesorder_creditlimit` WHERE `customerid` = '".$customer."' AND `status` = '0' ORDER BY `createddate` ASC LIMIT 0,1");
$crect = $this->db->num_rows($cresql);
$leftdays = $this->db->query_result($getlimit,'0','cf_3461');
$leftamount = $this->db->query_result($getlimit,'0','cf_3459');
if($crect==0){
$this->db->pquery("UPDATE `arocrm_accountscf` SET `cf_4313` = '".$leftamount."',`cf_4315` = '".$leftdays."' WHERE `customerid` = '".$customer."'");		
}else{
$invduedate = $this->db->query_result($cresql,'0','duedate');
$ts1 = strtotime(date('Y-m-d'));
$ts2 = strtotime($invcreatedate);     
$seconds_diff = $ts2 - $ts1;                            
$days = ($seconds_diff/3600)/24;	

$days = $leftdays - $days;	

if($days < 0){
$days = 0;	
}

$this->db->pquery("UPDATE `arocrm_accountscf` SET `cf_4315` = '".$days."' WHERE `customerid` = '".$customer."'");	
}
}else{
$this->db->pquery("UPDATE `arocrm_salesorder_creditlimit` SET `amount` = '".$dueamt."'  WHERE `invid` = '".$inv."'");	
}
}	
}
}else if($reef=='Advance Payment'){
$amount = $request->get('cf_3342');
$customer = $request->get('cf_nrl_accounts363_id');
$getlimit = $this->db->pquery("SELECT * FROM `arocrm_accountscf` INNER JOIN  `arocrm_account` ON `arocrm_account`.`accountid` = `arocrm_accountscf`.`accountid` WHERE `arocrm_account`.`accountid` = '".$customer."'");
$curlimit = $this->db->query_result($getlimit,'0','cf_4313');
$custtype = $this->db->query_result($getlimit,'0','accounttype');
$newlimit = (float)$curlimit + (float)$amount;
$this->db->pquery("UPDATE `arocrm_accountscf` SET `cf_4313` = '".$newlimit."'  WHERE `accountid` = '".$customer."'");
}
}
}

// End of code for CustomerPayment code added by Roni Modak on 07-03-2019//


		//  Accounts //
		//Code for Accounts Master treat as Vendor added by 14-01-2019//

		if($request->getModule()=='Accounts'){
		$recorddata = $request->get('record');

			if($recorddata==""){

      $update_query = $this->db->pquery("UPDATE `arocrm_accountscf` SET  `cf_4313` = '".$request->get('cf_3459')."',`cf_4315` = '".$request->get('cf_3461')."' WHERE `accountid` = '".$recordModel->getId()."'");


				$chk = $request->get('cf_3368_check');
				if($chk=='on'){

					$name = $request->get('accountname');

					$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
					$recid = $this->db->query_result($mfcrmid,'0','id');
					$recid = (int)$recid + 1;

					$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
					VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','Vendors','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$name."')";

					$crmenins = $this->db->pquery($crmin);
					$nextrecid = $recid;
					$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");
					$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'Vendors'");
					$recvendid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

					$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

					$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'Vendors'");

					$ins_serial = $this->db->pquery("INSERT INTO `arocrm_vendor`(`vendorid`, `vendor_no`, `vendorname`,
						 `phone`, `email`, `website`, `glacct`, `category`, `street`, `city`, `state`, `pobox`,
						 `postalcode`, `country`, `description`, `tags`, `accountid`) VALUES
						 ('".$recid."',".$recvendid.",'".$name."','','','','','','','',
							 '','','','','','','".$recordModel->getId()."')");
					$sqlins1 = $this->db->pquery("INSERT INTO `arocrm_vendorcf`(`vendorid`) VALUES ('".$recid."')");

					$ins_uppacc = $this->db->pquery("UPDATE `arocrm_account` SET `vendorid` = '".$recid."' WHERE  `accountid`= '".$recordModel->getId()."'");
					$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");

				}

			}else{



			  $chk = $request->get('cf_3368_check');

				if($chk=='on'){

					$mrdid = $this->db->pquery("SELECT * FROM `arocrm_account` where `accountid` = '".$recorddata."'");
				  $mreccdid = $this->db->query_result($mrdid,'0','vendorid');


					if($mreccdid==""){

						$name = $request->get('accountname');

						$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
						$recid = $this->db->query_result($mfcrmid,'0','id');
						$recid = (int)$recid + 1;

						$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
						VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$_SESSION['authenticated_user_id']."','".$_SESSION['authenticated_user_id']."','Vendors','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$name."')";

						$crmenins = $this->db->pquery($crmin);
						$nextrecid = $recid;
						$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");
						$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'Vendors'");
						$recvendid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

						$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

						$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'Vendors'");

				  	$venql = "INSERT INTO `arocrm_vendor` (`vendorid`, `vendor_no`, `vendorname`,
							 `phone`, `email`, `website`, `glacct`, `category`, `street`, `city`, `state`, `pobox`,
							 `postalcode`, `country`, `description`, `tags`, `accountid`) VALUES
							 ('".$recid."','".$recvendid."','".$name."','','','','','','','',
								 '','','','','','','".$recordModel->getId()."')";


						$ins_serial = $this->db->pquery($venql);
						$sqlins1 = $this->db->pquery("INSERT INTO `arocrm_vendorcf`(`vendorid`, `cf_3370`) VALUES ('".$recid."','')");

						$ins_uppacc = $this->db->pquery("UPDATE `arocrm_account` SET `vendorid` = '".$recid."' WHERE  `accountid`= '".$recordModel->getId()."'");
						$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");

					 }
				}
				
			$startdate = $request->get('cf_5134');
			$tmp = explode("-",$startdate);
			$dates = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			$stdate = strtotime($dates);
			$curdate = strtotime(date('Y-m-d'));
            $numberofdates = $request->get('cf_5136');
			if($stdate==$curdate){
			if($numberofdates==""){
				$numberofdates = 0;
			}
			$NewDate = date('Y-m-d', strtotime($dates . " +".$numberofdates." days"));
			$this->db->pquery("UPDATE `arocrm_accountscf` SET `cf_5138` = '".$NewDate."' WHERE `accountid` = '".$recordModel->getId()."'");
			}
			
			}


		}
		 /*End here*/
		//End of Accounts Master treat as Vendor added by 14-01-2019//

		
		


//StoretoStoreTransfer Module done by Roni Modak on 05-03-2019//

$recorddata = $request->get('record');
if($request->getModule()=='StoretoStoreTransfer' && $recorddata==""){

$plant = $request->get('cf_nrl_plantmaster451_id');
$product = $request->get('cf_nrl_products427_id');
$curstore  = $request->get('cf_nrl_storagelocation69_id');
$newstore  = $request->get('cf_nrl_storagelocation411_id');
$rowct = explode(",",$request->get('totalRowCount_LineItem'));

$serialnumb = '';
$serialnumr = '';
$serialnums = '';
$bno = 0;
$rno = 0;
$sno = 0;


foreach($rowct as $rowitems){
$serialno = $request->get('cf_4747_'.$rowitems);
$netsqldata = "SELECT * FROM `arocrm_serialnumber` INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`serialnumberid` = `arocrm_serialnumber`.`serialnumberid` WHERE 
`arocrm_serialnumber`.`cf_nrl_plantmaster496_id` = '".$plant."' 
AND `arocrm_serialnumber`.`cf_nrl_storagelocation106_id` = '".$curstore."'
AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$product."'
AND `arocrm_serialnumbercf`.`cf_2834`  = '1'
AND `arocrm_serialnumbercf`.`serialnumberid` = '".$serialno."'";
$newqtysql = $this->db->pquery($netsqldata);
$numro = $this->db->num_rows($newqtysql);

$netst = trim($this->db->query_result($newqtysql,'0','cf_1256'));
$serials = $this->db->query_result($newqtysql,'0','cf_1258');

if($netst=='B'){
$serialnumb = $serialnumb.$serials.",";	
$bno = (int)$bno + 1;
}
if($netst=='R'){
$serialnumr = $serialnumr.$serials.",";
$rno = (int)$rno + 1;
}
if($netst=='S'){
$serialnums = $serialnums.$serials.",";
$sno = (int)$sno + 1;
}
}


        $trandate = $request->get('cf_4973');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		if($rno > 0){
		$stkqtysql1 = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$product."' AND `plant` = '".$plant."' AND `store` = '".$curstore."' AND `qualitystatus` = 'R' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql1 = $this->db->pquery($stkqtysql1);
		$qtysqlnum1 = $this->db->num_rows($newqtysql1);
		$prevstk1 = (int)$this->db->query_result($newqtysql1,'0','totqty');
		if($qtysqlnum1==0){
			$prevstk1 = 0;
		}
		$curstk1 = $prevstk1 - $rno;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$product."','".$plant."','".$curstore."','".$trandate."','".date('Y-m-d')."','0','".$rno."','SS','R','".$serialnumr."','".$prevstk1."','".$curstk1."')");
		}
	
		if($bno > 0){
	    $stkqtysq2 = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$product."' AND `plant` = '".$plant."' AND `store` = '".$curstore."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql2 = $this->db->pquery($stkqtysq2);
		$qtysqlnum2 = $this->db->num_rows($newqtysql2);
	    $prevstk2 = (int)$this->db->query_result($newqtysql2,'0','totqty');
		if($qtysqlnum2==0){
			$prevstk2 = 0;
		}
		$curstk2 = $prevstk2 - $bno;
		
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$product."','".$plant."','".$curstore."','".$trandate."','".date('Y-m-d')."','0','".$bno."','SS','B','".$serialnumb."','".$prevstk2."','".$curstk2."')");
		}
		
		if($sno > 0){
	    $stkqtysq3 = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$product."' AND `plant` = '".$plant."' AND `store` = '".$curstore."' AND `qualitystatus` = 'S' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql3 = $this->db->pquery($stkqtysq3);
		$qtysqlnum3 = $this->db->num_rows($newqtysql3);
		$prevstk3 = (int)$this->db->query_result($newqtysql3,'0','totqty');
		if($qtysqlnum3==0){
			$prevstk3 = 0;
		}
		$curstk3 = $prevstk3 - $sno;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$product."','".$plant."','".$curstore."','".$trandate."','".date('Y-m-d')."','0','".$sno."','SS','S','".$serialnums."','".$prevstk3."','".$curstk3."')");
		}


$serialnumb = '';
$serialnumr = '';
$bno = 0;
$rno = 0;

$rowctsd = explode(",",$request->get('totalRowCount_LineItem'));
foreach($rowctsd as $rowite){

$serialno = $request->get('cf_4747_'.$rowite);
$serialnos = $request->get('cf_4747_display_'.$rowite);

$status = $request->get('cf_4749_'.$rowite);
$nst = explode("-",$status);
$netst = trim($nst[0]);

if($netst=='B'){
$serialnumb = $serialnumb.$serialnos.",";	
$bno = (int)$bno + 1;
}
if($netst=='R'){
$serialnumr = $serialnumr.$serialnos.",";
$rno = (int)$rno + 1;
}
$sql = "SELECT * FROM `arocrm_serialnumber`
INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`serialnumberid` = `arocrm_serialnumber`.`serialnumberid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_serialnumber`.`serialnumberid`
WHERE `arocrm_crmentity`.`deleted` = 0
AND `arocrm_serialnumber`.`serialnumberid` = '".$serialno."'
AND `arocrm_serialnumber`.`cf_nrl_plantmaster496_id` = '".$plant."'
AND `arocrm_serialnumber`.`cf_nrl_storagelocation106_id` = '".$curstore."'
AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$product."'
AND `arocrm_serialnumbercf`.`cf_2834` = '1'";
$getpe = $this->db->pquery($sql);
$pro_rcount = $this->db->num_rows($getpe);
if($pro_rcount==1){
$recid = $this->db->query_result($getpe,'0','serialnumberid');
$uph = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$newstore."' WHERE  `serialnumberid` = '".$recid."'");
$UPI = $this->db->pquery("UPDATE `arocrm_serialnumbercf` SET `cf_1256` = '".$netst."' WHERE  `serialnumberid` = '".$recid."'");
}

}
      
	  
        $trandate = $request->get('cf_4973');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		if($rno > 0){
	$stkqtysq4 = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$product."' AND `plant` = '".$plant."' AND `store` = '".$newstore."' AND `qualitystatus` = 'R' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql4 = $this->db->pquery($stkqtysq4);
		$qtysqlnum4 = $this->db->num_rows($newqtysql4);
		$prevstk4 = (int)$this->db->query_result($newqtysql4,'0','totqty');
		if($qtysqlnum4==0){
			$prevstk4 = 0;
		}
		$curstk4 = $prevstk4 + $rno;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$product."','".$plant."','".$newstore."','".$trandate."','".date('Y-m-d')."','".$rno."','0','SS','R','".$serialnumr."','".$prevstk4."','".$curstk4."')");
		}
		
		
		if($bno > 0){
		 $stkqtysq5 = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$product."' AND `plant` = '".$plant."' AND `store` = '".$newstore."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql5 = $this->db->pquery($stkqtysq5);
		$qtysqlnum5 = $this->db->num_rows($newqtysql5);
		$prevstk5 = (int)$this->db->query_result($newqtysql5,'0','totqty');
		if($qtysqlnum5==0){
			$prevstk5 = 0;
		}
		$curstk5 = $prevstk5 + $bno;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$product."','".$plant."','".$newstore."','".$trandate."','".date('Y-m-d')."','".$bno."','0','SS','B','".$serialnumb."','".$prevstk5."','".$curstk5."')");
		}
		


}


//End of StoretoStoreTransfer Module done by Roni Modak on 05-03-2019//






		//  OutboundDelivery //
		// Serial Number Update Automation obd -- by Roni Modak 05-01-2019//
		$recorddata = $request->get('record');
		if($request->getModule()=='OutboundDelivery' && $recorddata==""){

		$ref = $request->get('cf_3067');
		if($ref == 'With respect to Assembly Order'){

			$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
			foreach($rowct as $rowitem){

			$plantid = $request->get('cf_nrl_plantmaster625_id');
			$storeid = $request->get('cf_2010_'.$rowitem);
			$productid = $request->get('cf_2006_'.$rowitem);
			$srnos = explode(",",$request->get('cf_3076_'.$rowitem));
			
			$unitprice = $request->get('cf_2020_'.$rowitem);
			$totalprice = $request->get('cf_4925_'.$rowitem);
			
			$updateobd = $this->db->pquery("UPDATE `arocrm_outbounddelivery_line_item_lineitem` SET `cf_2020` = '".$unitprice."',`cf_4925` = '".$totalprice."' WHERE `outbounddeliveryid` = '".$recordModel->getId()."' AND `cf_2006` = '".$productid."'");
			
			foreach($srnos as $serialnos){
		
		$quany = 1;
		$trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','OBD-AO','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");
		
		
			 $ins_serial = $this->db->pquery("UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'O',`cf_3387` = '".$recordModel->getId()."'
			 WHERE `cf_1258` = '".$serialnos."' AND `serialnumberid` = (SELECT `serialnumberid`
			 FROM `arocrm_serialnumber` WHERE `name` = '".$serialnos."'
				AND `cf_nrl_plantmaster496_id` = '".$plantid."'
				AND `cf_nrl_storagelocation106_id` = '".$storeid."'
				AND `cf_nrl_products16_id` = '".$productid."')");

			}
			
			
        $quany = $request->get('cf_2014_'.$rowitem);
	    $trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'O' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','OBD-AO','O','".$request->get('cf_3076_'.$rowitem)."','".$prevstk."','".$curstk."')");
			
			}


		 }else if($ref == 'With Respect to Purchase Return'){

			$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
			foreach($rowct as $rowitem){

			$plantid = $request->get('cf_nrl_plantmaster625_id');
			$storeid = $request->get('cf_2010_'.$rowitem);
			$productid = $request->get('cf_2006_'.$rowitem);

			$srnos = explode(",",$request->get('cf_3076_'.$rowitem));
			
			$unitprice = $request->get('cf_2020_'.$rowitem);
			$totalprice = $request->get('cf_4925_'.$rowitem);
			
			$updateobd = $this->db->pquery("UPDATE `arocrm_outbounddelivery_line_item_lineitem` SET `cf_2020` = '".$unitprice."',`cf_4925` = '".$totalprice."' WHERE `outbounddeliveryid` = '".$recordModel->getId()."' AND `cf_2006` = '".$productid."'");
			
			foreach($srnos as $serialnos){
			
				$quany = 1;
				$trandate = $request->get('cf_3225');
				$tmp = explode("-", $trandate);
				$tmp1 = strlen($tmp[0]);
				$tmp2 = strlen($tmp[1]);
				$tmp3 = strlen($tmp[2]);

				if($tmp1==2 && $tmp2==2 && $tmp3==4){
				$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
				}

				$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
				$serdetail = $this->db->fetch_array($storeqtysql);

				$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
				$newqtysql = $this->db->pquery($stkqtysql);
				$qtysqlnum = $this->db->num_rows($newqtysql);
				$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
				if($qtysqlnum==0){
				$prevstk = 0;
				}

				$curstk = $prevstk - $quany;

				$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','OBD-PRT','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");

		
			
			
				$ins_serial = $this->db->pquery("UPDATE `arocrm_serialnumbercf` SET `cf_3128` = '".$recordModel->getId()."'
				WHERE `cf_1256` = 'B' AND `cf_2834` = '1' AND `cf_1258` = '".$serialnos."' AND `serialnumberid` = (SELECT `serialnumberid`
				FROM `arocrm_serialnumber` WHERE `name` = '".$serialnos."'
				AND `cf_nrl_plantmaster496_id` = '".$plantid."'
				AND `cf_nrl_storagelocation106_id` = '".$storeid."'
				AND `cf_nrl_products16_id` = '".$productid."')");

			}

			
		$quany = $request->get('cf_2014_'.$rowitem);
	    $trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','OBD-PRT','B','".$request->get('cf_3076_'.$rowitem)."','".$prevstk."','".$curstk."')");
		
		
			}

		}else if($ref == 'With Respect to STPO'){
		$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
		$pono = $request->get('cf_nrl_purchaseorder165_id');
		
		foreach($rowct as $rowitem){

		
		$storeid = $request->get('cf_2010_'.$rowitem);
		$productid = $request->get('cf_2006_'.$rowitem);
		$plantid = $request->get('cf_nrl_plantmaster574_id');
		
		$storcode = $this->db->pquery("SELECT * FROM `arocrm_storagelocation` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid` WHERE `arocrm_storagelocation`.`name` LIKE '%Quarantine%'
		AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."'
		AND `arocrm_crmentity`.`deleted` = 0");
		
		$storcodedet = $this->db->fetch_array($storcode);
		$srnos = explode(",",$request->get('cf_3076_'.$rowitem));
		
		$unitprice = $request->get('cf_2020_'.$rowitem);
		$totalprice = $request->get('cf_4925_'.$rowitem);

		$updateobd = $this->db->pquery("UPDATE `arocrm_outbounddelivery_line_item_lineitem` SET `cf_2020` = '".$unitprice."',`cf_4925` = '".$totalprice."' WHERE `outbounddeliveryid` = '".$recordModel->getId()."' AND `cf_2006` = '".$productid."'");

		foreach($srnos as $serialnos){
			
        $quany = 1;
		$trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','OBD-O','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");
	
		
	   $updatestr = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storcodedet['storagelocationid']."' WHERE `name` = '".$serialnos."'");
		
	   $srlsqlwt = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'O',`cf_3128` = '".$recordModel->getId()."' 
		 WHERE `cf_1256` = 'R' AND `cf_2834` = '1' AND `cf_1258` = '".$serialnos."' AND `serialnumberid` = (SELECT `serialnumberid`
		 FROM `arocrm_serialnumber` WHERE `name` = '".$serialnos."'
		  AND `cf_nrl_plantmaster496_id` = '".$plantid."'
			AND `cf_nrl_products16_id` = '".$productid."')";
			
		 $ins_serial = $this->db->pquery($srlsqlwt);
		 
		 
		 
		 
		 $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		 $stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','OBD-O','".$serdetail['cf_3084']."','".$serialnos."','".$prevstk."','".$curstk."')");

		}
		
		}
		
		
	   }else{
		$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
		foreach($rowct as $rowitem){

		$plantid = $request->get('cf_nrl_plantmaster625_id');
		$storeid = $request->get('cf_2010_'.$rowitem);
		$productid = $request->get('cf_2006_'.$rowitem);
		
        $storcode = $this->db->pquery("SELECT * FROM `arocrm_storagelocation` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid` WHERE `arocrm_storagelocation`.`name` LIKE '%Quarantine%'
		AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."'
		AND `arocrm_crmentity`.`deleted` = 0");
		$storcodedet = $this->db->fetch_array($storcode);
		
		
		$srnos = explode(",",$request->get('cf_3076_'.$rowitem));
		
		$unitprice = $request->get('cf_2020_'.$rowitem);
		$totalprice = $request->get('cf_4925_'.$rowitem);

		$updateobd = $this->db->pquery("UPDATE `arocrm_outbounddelivery_line_item_lineitem` SET `cf_2020` = '".$unitprice."',`cf_4925` = '".$totalprice."' WHERE `outbounddeliveryid` = '".$recordModel->getId()."' AND `cf_2006` = '".$productid."'");

		foreach($srnos as $serialnos){
			
        $quany = 1;
		$trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','OBD-O','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");
				
		
		$updatestr = $this->db->pquery("UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storcodedet['storagelocationid']."' WHERE `name` = '".$serialnos."'");
		
	    $srlsqlwt = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'O',`cf_3128` = '".$recordModel->getId()."'
		 WHERE `cf_1256` = 'R' AND `cf_2834` = '1' AND `cf_1258` = '".$serialnos."' AND `serialnumberid` = (SELECT `serialnumberid`
		 FROM `arocrm_serialnumber` WHERE `name` = '".$serialnos."'
		  AND `cf_nrl_plantmaster496_id` = '".$plantid."'
			AND `cf_nrl_products16_id` = '".$productid."')";
		 $ins_serial = $this->db->pquery($srlsqlwt);

		 
		 
		 
		 
		 
		 $quany = 1;
	    $trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		 $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		 $stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','OBD-O','".$serdetail['cf_3084']."','".$serialnos."','".$prevstk."','".$curstk."')");

		
		
		}
		
		
		
		/*
		
		$quany = $request->get('cf_2014_'.$rowitem);
	    $trandate = $request->get('cf_3225');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'O' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','OBD-O','O','".$request->get('cf_3076_'.$rowitem)."','".$prevstk."','".$curstk."')");
		*/

		}
	   }
	   
	   
		}


		// End of Serial Number Automation in OBD //

		// Serial Number Update Automation IBD -- by Roni Modak 26-12-2018//
		$timezone_offset_minutes = 330;
		$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
		date_default_timezone_set($timezone_name);

		$recorddata = $request->get('record');
		if($request->getModule()=='InboundDelivery' && $recorddata==""){
        $ref = $request->get('cf_3193');

		if($ref=='With respect to Assembly Order'){

			$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
			foreach($rowct as $rowitem){

			$plantid = $request->get('cf_nrl_plantmaster269_id');
			$storeid = $request->get('cf_2874_'.$rowitem);
			$productid = $request->get('cf_2868_'.$rowitem);

			$srnos = explode(",",$request->get('cf_2888_'.$rowitem));
			
			
		$quany = $request->get('cf_2878_'.$rowitem);
	    $trandate = $request->get('cf_3200');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$stkqtysql = "SELECT `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'O' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-AO','O','".$request->get('cf_2888_'.$rowitem)."','".$prevstk."','".$curstk."')");
				
			
			
			
			
		$unitprice = $request->get('cf_2880_'.$rowitem);
		$totalprice = $request->get('cf_2882_'.$rowitem);
		$updateibdline = $this->db->pquery("update `arocrm_inbounddelivery_line_item_lineitem` set `cf_2880` = '".$unitprice."',`cf_2882`='".$totalprice."' where `inbounddeliveryid`='".$recordModel->getId()."' AND `cf_2868` = '".$productid."'");
		$proyrm = $this->db->pquery("SELECT arocrm_productcf.* FROM arocrm_products 
		INNER JOIN arocrm_productcf ON arocrm_productcf.productid = arocrm_products.productid
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
		WHERE arocrm_crmentity.deleted = 0 AND arocrm_products.productid = '".$productid."'");
		$proyrrow =  $this->db->fetch_array($proyrm);
		$proyr = $proyrrow['cf_5126'];
		$proyr = $proyr -1;
		$promonth = $proyrrow['cf_5128'];
		$promonth = $promonth - 1;
			foreach($srnos as $serialnos){
			$mfcyr = substr($serialnos,$proyr,1);
			if($mfcyr == '0')
			{
				$mfcyear = '2020';
			}
			if($mfcyr == '1')
			{
				$mfcyear = '2021';
			}
			if($mfcyr == '2')
			{
				$mfcyear = '2022';
			}
			if($mfcyr == '3')
			{
				$mfcyear = '2023';
			}
			if($mfcyr == '4')
			{
				$mfcyear = '2024';
			}
			if($mfcyr == '5')
			{
				$mfcyear = '2025';
			}
			if($mfcyr == '6')
			{
				$mfcyear = '2026';
			}
			if($mfcyr == '7')
			{
				$mfcyear = '2017';
			}
			if($mfcyr == '8')
			{
				$mfcyear = '2018';
			}
			if($mfcyr == '9')
			{
				$mfcyear = '2019';
			}
		 	$mfcmonth = substr($serialnos,$promonth,1);

			$months = '';
			$mdfdate = '';

			switch($mfcmonth){
  		    CASE 'A':
			$months = '01';
			break;
			CASE 'B':
			$months = '02';
			break;
			CASE 'C':
			$months = '03';
			break;
			CASE 'D':
			$months = '04';
			break;
			CASE 'E':
			$months = '05';
			break;
			CASE 'F':
			$months = '06';
			break;
			CASE 'G':
			$months = '07';
			break;
			CASE 'H':
			$months = '08';
			break;
			CASE 'J':
			$months = '09';
			break;
			CASE 'K':
			$months = '10';
			break;
			CASE 'L':
			$months = '11';
			break;
			CASE 'M':
			$months = '12';
			break;
			}



			$nmonths1 = date("m", strtotime($months));
			$d = cal_days_in_month(CAL_GREGORIAN, $nmonths1, $mfcyear);



			$mdfdate = $mfcyear."-".$months."-01";

			$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
			$recid = $this->db->query_result($mfcrmid,'0','id');
			$recid = (int)$recid + 1;

			$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
			`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
			VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','SerialNumber','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$serialnos."')";

			$crmenins = $this->db->pquery($crmin);

			$nextrecid = $recid;

			$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");

			$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'SerialNumber'");
			$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

			$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

			$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'SerialNumber'");

			$ins_serial = $this->db->pquery("INSERT INTO `arocrm_serialnumber`(`serialnumberid`, `name`, `serialnumberno`, `cf_nrl_plantmaster496_id`, `cf_nrl_storagelocation106_id`, `cf_nrl_products16_id`) VALUES ('".$recid."','".$serialnos."','".$recserialid."','".$plantid."','".$storeid."','".$productid."')");

			$ins_serial_cf = $this->db->pquery("INSERT INTO `arocrm_serialnumbercf`(`serialnumberid`, `cf_1256`, `cf_1258`, `cf_1260` , `cf_1268`, `cf_1270`, `cf_2834`) VALUES ('".$recid."','O','".$serialnos."','".$serialnos."','".$mdfdate."','".$recordModel->getId()."','0')");

			$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");



			}

			}

		}else if($ref=='With respect to PO'){


		
			$QLid = $this->db->pquery("SELECT `cf_2712` FROM `arocrm_purchaseordercf` WHERE `purchaseorderid` = '".$request->get('cf_nrl_purchaseorder573_id')."'");
	        $Rqlid = $this->db->query_result($QLid,'0','cf_2712');
			
			if($Rqlid=='Against Warranty'){

	        $rowct = explode(",",$request->get('totalRowCount_Line_Item'));
			foreach($rowct as $rowitem){

			$plantid = $request->get('cf_nrl_plantmaster269_id');
			$storeid = $request->get('cf_2874_'.$rowitem);
			$productid = $request->get('cf_2868_'.$rowitem);

			$srnos = explode(",",$request->get('cf_2888_'.$rowitem));
			$rserialno = '';
			
			foreach($srnos as $serialns){
             $rserialno = $rserialno.$serialns.',';
			}
		
			
			$unitprice = $request->get('cf_2880_'.$rowitem);
			$totalprice = $request->get('cf_2882_'.$rowitem);
			$updateibdline = $this->db->pquery("update `arocrm_inbounddelivery_line_item_lineitem` set `cf_2880` = '".$unitprice."',`cf_2882`='".$totalprice."' where `inbounddeliveryid`='".$recordModel->getId()."' AND `cf_2868` = '".$productid."'");

			foreach($srnos as $serialnos){
				
	    $quany = 1;
	   
	    $trandate = $request->get('cf_3200');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-AW','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");
				
				

			$dfsql1  = "UPDATE `arocrm_serialnumber` SET  `cf_nrl_plantmaster496_id` = '".$plantid."', `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `cf_nrl_products16_id` = '".$productid."' AND `name` = '".$serialnos."'";
			$ins_serial = $this->db->pquery($dfsql1);

			$dfsql2  = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B',`cf_3348` = '".$recordModel->getId()."' WHERE `serialnumberid` IN (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_plantmaster496_id` = '".$plantid."' AND  `cf_nrl_storagelocation106_id` = '".$storeid."' AND `cf_nrl_products16_id` = '".$productid."' AND `name` = '".$serialnos."')";
			$ins_serial_cf = $this->db->pquery($dfsql2);
			}
			
			
			$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-AW','B','".$request->get('cf_2888_'.$rowitem)."','".$prevstk."','".$curstk."')");
			

			}

       }else{

		$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
		$varcount = count($rowct);
		foreach($rowct as $rowitem){
			
		$directmode = $request->get('directMode_Line_Item');
		if($directmode==0){
		if($varcount==1){
		$rowitem = '';
		}else{
		$rowitem = '_'.$rowitem;	
		}
		}else{
		$rowitem = '_'.$rowitem;	
		}
	    $plantid = $request->get('cf_nrl_plantmaster269_id');
	    $storeid = $request->get('cf_2874'.$rowitem);
	    $productid = $request->get('cf_2868'.$rowitem);

		$srnos = explode(",",$request->get('cf_2888'.$rowitem));
		
	    $quany = $request->get('cf_2878'.$rowitem);
	    $trandate = $request->get('cf_3200');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
	$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'O' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-O','O','".$request->get('cf_2888'.$rowitem)."','".$prevstk."','".$curstk."')");
		
		
		$unitprice = $request->get('cf_2880'.$rowitem);
		$totalprice = $request->get('cf_2882'.$rowitem);
		$updateibdline = $this->db->pquery("update `arocrm_inbounddelivery_line_item_lineitem` set `cf_2880` = '".$unitprice."',`cf_2882`='".$totalprice."' where `inbounddeliveryid`='".$recordModel->getId()."' AND `cf_2868` = '".$productid."'");
		
		$proyrm = $this->db->pquery("SELECT arocrm_productcf.* FROM arocrm_products 
		INNER JOIN arocrm_productcf ON arocrm_productcf.productid = arocrm_products.productid
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
		WHERE arocrm_crmentity.deleted = 0 AND arocrm_products.productid = '".$productid."'");
		$proyrrow =  $this->db->fetch_array($proyrm);
			$proyr = $proyrrow['cf_5126'];
			$proyr = $proyr - 1;
			
			$promonth = $proyrrow['cf_5128'];
			$promonth = $promonth - 1;
			
			foreach($srnos as $serialnos){
			$months = '';
			$mdfdate = '';
		
			
			$mfcyr = substr($serialnos,$proyr,1);
			if($mfcyr == '0')
			{
				$mfcyear = '2020';
			}
			if($mfcyr == '1')
			{
				$mfcyear = '2021';
			}
			if($mfcyr == '2')
			{
				$mfcyear = '2022';
			}
			if($mfcyr == '3')
			{
				$mfcyear = '2023';
			}
			if($mfcyr == '4')
			{
				$mfcyear = '2024';
			}
			if($mfcyr == '5')
			{
				$mfcyear = '2025';
			}
			if($mfcyr == '6')
			{
				$mfcyear = '2026';
			}
			if($mfcyr == '7')
			{
				$mfcyear = '2017';
			}
			if($mfcyr == '8')
			{
				$mfcyear = '2018';
			}
			if($mfcyr == '9')
			{
				$mfcyear = '2019';
			}
			$mfcmonth = substr($serialnos,$promonth,1);
			
			switch($mfcmonth){
  		    CASE 'A':
			$months = '01';
			break;
			CASE 'B':
			$months = '02';
			break;
			CASE 'C':
			$months = '03';
			break;
			CASE 'D':
			$months = '04';
			break;
			CASE 'E':
			$months = '05';
			break;
			CASE 'F':
			$months = '06';
			break;
			CASE 'G':
			$months = '07';
			break;
			CASE 'H':
			$months = '08';
			break;
			CASE 'J':
			$months = '09';
			break;
			CASE 'K':
			$months = '10';
			break;
			CASE 'L':
			$months = '11';
			break;
			CASE 'M':
			$months = '12';
			break;
			}



			$nmonths1 = date("m", strtotime($months));
			$d = cal_days_in_month(CAL_GREGORIAN, $nmonths1, $mfcyear);



	     $mdfdate = $mfcyear."-".$months."-01";

		$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
		$recid = $this->db->query_result($mfcrmid,'0','id');
		$recid = (int)$recid + 1;

		$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
		`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
		VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','SerialNumber','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$serialnos."')";

		$crmenins = $this->db->pquery($crmin);

		$nextrecid = $recid;

		$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");

		$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'SerialNumber'");
		$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

		$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

		$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'SerialNumber'");

		$ins_serial = $this->db->pquery("INSERT INTO `arocrm_serialnumber`(`serialnumberid`, `name`, `serialnumberno`, `cf_nrl_plantmaster496_id`, `cf_nrl_storagelocation106_id`, `cf_nrl_products16_id`) VALUES ('".$recid."','".$serialnos."','".$recserialid."','".$plantid."','".$storeid."','".$productid."')");

		$ins_serial_cf = $this->db->pquery("INSERT INTO `arocrm_serialnumbercf`(`serialnumberid`, `cf_1256`, `cf_1258`, `cf_1260` , `cf_1268`, `cf_1270`, `cf_2834`) VALUES ('".$recid."','O','".$serialnos."','".$serialnos."','".$mdfdate."','".$recordModel->getId()."','0')");

		$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");



		}

		}

}

	}else if($ref=='With respect to Sales Return'){

		$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
		foreach($rowct as $rowitem){

		$plantid = $request->get('cf_nrl_plantmaster269_id');
		$storeid = $request->get('cf_2874_'.$rowitem);
		$productid = $request->get('cf_2868_'.$rowitem);

		$srnos = explode(",",$request->get('cf_2888_'.$rowitem));

		$unitprice = $request->get('cf_2880_'.$rowitem);
		$totalprice = $request->get('cf_2882_'.$rowitem);
		$updateibdline = $this->db->pquery("update `arocrm_inbounddelivery_line_item_lineitem` set `cf_2880` = '".$unitprice."',`cf_2882`='".$totalprice."' where `inbounddeliveryid`='".$recordModel->getId()."' AND `cf_2868` = '".$productid."'");
		

	   $trandate = $request->get('cf_3200');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		

		foreach($srnos as $serialnos){
		
		$quany = 1;
	   
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','0','".$quany."','IBD-PRT','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");
			
			
		$ins_serial = $this->db->pquery("UPDATE `arocrm_serialnumber` SET  `cf_nrl_plantmaster496_id` = '".$plantid."', `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `cf_nrl_products16_id` = '".$productid."' AND `name` = '".$serialnos."'");

		$ins_serial_cf = $this->db->pquery("UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B',`cf_3348` = '".$recordModel->getId()."' WHERE `serialnumberid` = (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_plantmaster496_id` = '".$plantid."' AND  `cf_nrl_storagelocation106_id` = '".$storeid."' AND `cf_nrl_products16_id` = '".$productid."' AND `name` = '".$serialnos."')");
		
		
		$quany = 1;
	    $trandate = $request->get('cf_3200');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-PRT','B','".$request->get('cf_2888_'.$rowitem)."','".$prevstk."','".$curstk."')");
		}

		
		
		
		
		}

	}else{
    // Place for Stock Transfer Order -- Inbound Delivery //
		$rowct = explode(",",$request->get('totalRowCount_Line_Item'));
		$trandate = $request->get('cf_3200');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		foreach($rowct as $rowitem){

		$plantid = $request->get('cf_nrl_plantmaster269_id');
		$storeid = $request->get('cf_2874_'.$rowitem);
		$productid = $request->get('cf_2868_'.$rowitem);

		$srnos = explode(",",$request->get('cf_2888_'.$rowitem));

		$unitprice = $request->get('cf_2880_'.$rowitem);
		$totalprice = $request->get('cf_2882_'.$rowitem);
		$updateibdline = $this->db->pquery("update `arocrm_inbounddelivery_line_item_lineitem` set `cf_2880` = '".$unitprice."',`cf_2882`='".$totalprice."' where `inbounddeliveryid`='".$recordModel->getId()."' AND `cf_2868` = '".$productid."'");
		
		foreach($srnos as $serialnos){
		
		$quany = 1;
	   
	   $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-STO','".$serdetail['cf_3084']."','".$serialnos."','".$prevstk."','".$curstk."')");
		
		
	   
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','IBD-STO','".$serdetail['cf_3084']."','".$serialnos."','".$prevstk."','".$curstk."')");
		
	    $serhup = "UPDATE `arocrm_serialnumber` SET  `cf_nrl_plantmaster496_id` = '".$plantid."', `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `cf_nrl_products16_id` = '".$productid."' AND `name` = '".$serialnos."'";
		$ins_serial = $this->db->pquery($serhup);

		$ins_serial_cf = $this->db->pquery("UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'O',`cf_3196` = '".$recordModel->getId()."' WHERE `serialnumberid` = (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_plantmaster496_id` = '".$plantid."' AND  `cf_nrl_storagelocation106_id` = '".$storeid."' AND `cf_nrl_products16_id` = '".$productid."' AND `name` = '".$serialnos."')");

		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialnos."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
		$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','IBD-STO','".$serdetail['cf_1256']."','".$serialnos."','".$prevstk."','".$curstk."')");
		
		}
		
		}
		// End of  Place for Stock Transfer Order  -- Inbound Delivery //
	}

		}

		
		// End of Serial Number Update Automation IBD  -- by Roni Modak 26-12-2018//



		// Stock Upload Module data Upgrade on 04-03-2019 by Roni Modak //
		$timezone_offset_minutes = 330;
		$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
		date_default_timezone_set($timezone_name);

		$recorddata = $request->get('record');
		if($request->getModule()=='StockUpload' && $recorddata==""){

		$rowct = explode(",",$request->get('totalRowCount_LineItem'));
		$direct = $request->get('directMode_LineItem');
		foreach($rowct as $rowitem){
		if($direct=='1'){
		$rowitem = '_'.$rowitem;
		}else{
		$rowitem = '';
		}
		$plantid = $request->get('cf_nrl_plantmaster741_id');
		$storeid = $request->get('cf_4725'.$rowitem);
		$productid = $request->get('cf_4709'.$rowitem);
        $productcost = $request->get('cf_4717'.$rowitem);
		$srnos = explode(",",$request->get('cf_4721'.$rowitem));
		$status = $request->get('cf_5119'.$rowitem);
		$stmp = explode("-",$status);	
		$statu = trim($stmp[0]);
		$proyrm = $this->db->pquery("SELECT arocrm_productcf.* FROM arocrm_products 
		INNER JOIN arocrm_productcf ON arocrm_productcf.productid = arocrm_products.productid
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
		WHERE arocrm_crmentity.deleted = 0 AND arocrm_products.productid = '".$productid."'");
		$proyrrow =  $this->db->fetch_array($proyrm);
		$proyr = $proyrrow['cf_5126'];
		$proyr = $proyr - 1;
		$promonth = $proyrrow['cf_5128'];
		$promonth = $promonth - 1;
			foreach($srnos as $serialnos){
			$mfcyr = substr($serialnos,$proyr,1);
			if($mfcyr == '0')
			{
				$mfcyear = '2020';
			}
			if($mfcyr == '1')
			{
				$mfcyear = '2021';
			}
			if($mfcyr == '2')
			{
				$mfcyear = '2022';
			}
			if($mfcyr == '3')
			{
				$mfcyear = '2023';
			}
			if($mfcyr == '4')
			{
				$mfcyear = '2024';
			}
			if($mfcyr == '5')
			{
				$mfcyear = '2025';
			}
			if($mfcyr == '6')
			{
				$mfcyear = '2026';
			}
			if($mfcyr == '7')
			{
				$mfcyear = '2017';
			}
			if($mfcyr == '8')
			{
				$mfcyear = '2018';
			}
			if($mfcyr == '9')
			{
				$mfcyear = '2019';
			}
		 	
			$months = '';
			$mdfdate = '';
			
			$mfcmonth = substr($serialnos,$promonth,1);
			
			switch($mfcmonth){
  		    CASE 'A':
			$months = '01';
			break;
			CASE 'B':
			$months = '02';
			break;
			CASE 'C':
			$months = '03';
			break;
			CASE 'D':
			$months = '04';
			break;
			CASE 'E':
			$months = '05';
			break;
			CASE 'F':
			$months = '06';
			break;
			CASE 'G':
			$months = '07';
			break;
			CASE 'H':
			$months = '08';
			break;
			CASE 'J':
			$months = '09';
			break;
			CASE 'K':
			$months = '10';
			break;
			CASE 'L':
			$months = '11';
			break;
			CASE 'M':
			$months = '12';
			break;
			}

			$nmonths1 = date("m", strtotime($months));
			$d = cal_days_in_month(CAL_GREGORIAN, $nmonths1, $mfcyear);

	     $mdfdate = $mfcyear."-".$months."-01";


		$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
		$recid = $this->db->query_result($mfcrmid,'0','id');
		$recid = (int)$recid + 1;

		$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
		`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
		VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','SerialNumber','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','".$serialnos."')";

		$crmenins = $this->db->pquery($crmin);

		$nextrecid = $recid;

		$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");

		$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'SerialNumber'");
		$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

		$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

		$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'SerialNumber'");

		$ins_serial = $this->db->pquery("INSERT INTO `arocrm_serialnumber`(`serialnumberid`, `name`, `serialnumberno`, `cf_nrl_plantmaster496_id`, `cf_nrl_storagelocation106_id`, `cf_nrl_products16_id`) VALUES ('".$recid."','".$serialnos."','".$recserialid."','".$plantid."','".$storeid."','".$productid."')");

		$ins_serial_cf = $this->db->pquery("INSERT INTO `arocrm_serialnumbercf`(`serialnumberid`, `cf_1256`, `cf_1264`,  `cf_1258`, `cf_1260` , `cf_1268`, `cf_1270`, `cf_2834`) VALUES ('".$recid."','".$statu."','".$productcost."','".$serialnos."','".$serialnos."','".$mdfdate."','".$recordModel->getId()."','1')");
		
		$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");


		$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."' LIMIT 0,1)";
		$selqry = $this->db->pquery($selqty);
		$totalqty = (int)$this->db->query_result($selqry,'0','cf_1356') + 1;

		$updateqty = $this->db->pquery("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."')");


		}
		
		
		$quany = $request->get('cf_4715'.$rowitem);
	    $trandate = $request->get('cf_4979');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		

		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$statu."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','SU','".$statu."','".$request->get('cf_4721'.$rowitem)."','".$prevstk."','".$curstk."')");
			

		}

		}

		// End of Stock Upload Module data Upgrade on 04-03-2019  -- by Roni Modak //




		// Serial Number QI Update Automation -- by Roni Modak 26-12-2018//

		$recorddata = $request->get('record');
		if($request->getModule()=='QualityInspection' && $recorddata==""){
		$timezone_offset_minutes = 330;
		$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
		date_default_timezone_set($timezone_name);


		$serial = '';
		$serial1 = '';
		$serial2 = '';
		$rowct = explode(",",$request->get('totalRowCount_Quality_Inspection_Lineitem'));

		$reference = $request->get('cf_3071');
		if($reference=='With respect to Inbound Delivery')
		 {

		$blockcf = $request->get('cf_3648');

		if($blockcf=='on'){

		$ibdno = $request->get('cf_nrl_inbounddelivery39_id');
		$productid = $request->get('cf_nrl_products913_id');

		$spids = $this->db->pquery("SELECT `cf_2888` FROM `arocrm_inbounddelivery_line_item_lineitem`
		 WHERE `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = '".$ibdno."' AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = '".$productid."'");
		$spidsproduct = $this->db->query_result($spids,'0','cf_2888');
		$rowctproduct = explode(",",$spidsproduct);

		foreach($rowctproduct as $rowitems)
		{

		$spids = $this->db->pquery("SELECT `serialnumberid` FROM `arocrm_serialnumber`
		 WHERE `name` = '".$rowitems."'");
		$serialid = $this->db->query_result($spids,'0','serialnumberid');

		$delq = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '3' WHERE `serialnumberid` = '".$serialid."'";
		$delsql_cf = $this->db->pquery($delq);

        $quany = 1;
		$trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$rowitems."'");
		$serdetail = $this->db->fetch_array($storeqtysql);

		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'O' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
		$prevstk = 0;
		}

		$curstk = $prevstk - $quany;

		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','QI-RT','O','".$serialno."','".$prevstk."','".$curstk."')");
    }

		$ssd = $this->db->pquery("SELECT count(*) as qifailcount FROM `arocrm_qualityinspection`
		INNER JOIN `arocrm_qualityinspectioncf` ON `arocrm_qualityinspectioncf`.`qualityinspectionid` = `arocrm_qualityinspection`.`qualityinspectionid`
		 WHERE `arocrm_qualityinspection`.`cf_nrl_inbounddelivery39_id` = '".$ibdno."' AND `arocrm_qualityinspectioncf`.`cf_3648` = '1'");
		$failqicount = $this->db->query_result($ssd,'0','qifailcount');

		$ssdibd = $this->db->pquery("SELECT count(*) as ibdlinecount FROM `arocrm_inbounddelivery_line_item_lineitem`
		 WHERE `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = '".$ibdno."'");
		$ibdlinecount = $this->db->query_result($ssdibd,'0','ibdlinecount');

		if($failqicount==$ibdlinecount){
			$dl1 = $this->db->pquery("UPDATE `arocrm_inbounddeliverycf` SET `cf_3659` = 'Cancelled' WHERE `inbounddeliveryid` = '".$ibdno."'");
		}

		}else{

		$ibdno = $request->get('cf_nrl_inbounddelivery39_id');
		$productid = $request->get('cf_nrl_products913_id');
		foreach($rowct as $rowitem){
		$data = $request->get('cf_2983_'.$rowitem);
		if($data=='B - Blocked'){
		$srl = $request->get('cf_1778_display_'.$rowitem);
		$serial = $serial.",'".$srl."'";
		}
		if($data=='O - Open'){
		$srl1 = $request->get('cf_1778_display_'.$rowitem);
		$serial1 = $serial1.",'".$srl1."'";
		}
		if($data=='S - Semiblocked'){
		$srl2 = $request->get('cf_1778_display_'.$rowitem);
		$serial2 = $serial2.",'".$srl2."'";
		}

		}


		$serial = ltrim($serial,",");
		$serial1 = ltrim($serial1,",");
		$serial2 = ltrim($serial2,",");

		if($serial==""){
		$serial="''";
		}
		if($serial1==""){
		$serial1="''";
		}
		if($serial2==""){
		$serial2="''";
		}

		$ibdrefsql = "SELECT * FROM `arocrm_inbounddelivery` INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`  WHERE `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."'";
		$ibdreftmp = $this->db->pquery($ibdrefsql);
		$ref = $this->db->query_result($ibdreftmp,'0','cf_2862');


		if($ref=='Reference to STR'){
		 $productid = $request->get('cf_nrl_products913_id');
		 $quany = 1;
		 $trandate = $request->get('cf_3227');
			$tmp = explode("-", $trandate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}
		
		$nwqr = $this->db->pquery("SELECT * FROM `arocrm_inbounddelivery_line_item_lineitem` WHERE `inbounddeliveryid` = '".$ibdno."' AND `cf_2868` = '".$productid."'");
		$nwqrrow = $this->db->fetch_array($nwqr);
		$rlnum1 = $nwqrrow['cf_2888']; 
		$rlnum = explode(",",$rlnum1);
		foreach($rlnum as $serialno){
		   $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','QI-STR','".$serdetail['cf_1256']."','".$serialno."','".$prevstk."','".$curstk."')");
		
		}
	
	 

		$qr11 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B' WHERE `cf_1258` IN (".$serial.") AND `cf_3196` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_cf = $this->db->pquery($qr11);
		
		$qr14 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'S' WHERE `cf_1258` IN (".$serial2.") AND `cf_3196` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_cf4 = $this->db->pquery($qr14);

		$qrl2 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'R' WHERE `cf_1258` NOT IN (".$serial.") AND `cf_1258` NOT IN (".$serial2.") AND `cf_3196` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_userassign = $this->db->pquery($qrl2);

		$qrl3 =  "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B' WHERE `cf_1258` IN (".$serial1.") AND `cf_3196` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_cf = $this->db->pquery($qrl3);
		
		
		
		
		
		
		 $quany = 1;
		 $trandate = $request->get('cf_3227');
			$tmp = explode("-", $trandate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}
		
		$nwqr = $this->db->pquery("SELECT * FROM `arocrm_inbounddelivery_line_item_lineitem` WHERE `inbounddeliveryid` = '".$ibdno."' AND `cf_2868` = '".$productid."'");
		$nwqrrow = $this->db->fetch_array($nwqr);
		$rlnum1 = $nwqrrow['cf_2888']; 
		$rlnum = explode(",",$rlnum1);
		foreach($rlnum as $serialno){
		   $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','QI-STR','".$serdetail['cf_1256']."','".$serialno."','".$prevstk."','".$curstk."')");
		
		}
		/*  QI Update for Stock History  */
		
		
		
		
		/*  End of QI Update for Stock History  */
		

		}else{

    $sdf = $this->db->pquery("SELECT `cf_2712` FROM `arocrm_purchaseordercf` where `purchaseorderid` = (SELECT `cf_nrl_purchaseorder573_id` FROM `arocrm_inbounddelivery` WHERE `inbounddeliveryid` = '".$ibdno."')");
  	$refpo = $this->db->query_result($sdf,'0','cf_2712');

			if($refpo=='Against Warranty'){

		   $qr11 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B' WHERE `cf_1258` IN (".$serial.") AND `cf_3348` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
				$ins_serial_cf = $this->db->pquery($qr11);
				
				
				$qr14 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'S' WHERE `cf_1258` IN (".$serial2.") AND `cf_3348` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		        $ins_serial_cf4 = $this->db->pquery($qr14);

		   	$qrl2 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'R' WHERE `cf_1258` NOT IN (".$serial.") AND `cf_1258` NOT IN (".$serial2.") AND `cf_3348` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
				$ins_serial_userassign = $this->db->pquery($qrl2);

		    $qrl3 =  "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B' WHERE `cf_1258` IN (".$serial1.") AND `cf_3348` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
				 $ins_serial_cf = $this->db->pquery($qrl3);

			}else{

		 $qr11 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B' WHERE `cf_1258` IN (".$serial.") AND `cf_1270` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_cf = $this->db->pquery($qr11);
		
		
		 $qr14 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'S' WHERE `cf_1258` IN (".$serial2.") AND `cf_1270` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_cf4 = $this->db->pquery($qr14);

		  $qrl2 = "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'R' WHERE `cf_1258` NOT IN (".$serial.") AND `cf_1258` NOT IN (".$serial2.") AND `cf_1270` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
		$ins_serial_userassign = $this->db->pquery($qrl2);

	   $qrl3 =  "UPDATE `arocrm_serialnumbercf` SET `cf_1256` = 'B' WHERE `cf_1258` IN (".$serial1.") AND `cf_1270` = '".$ibdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
	   $ins_serial_cf = $this->db->pquery($qrl3);
	   
	 

	       }

			  }

}


	 }else{


		 $obdno = $request->get('cf_nrl_outbounddelivery220_id');

	     $refmod = $this->db->pquery("SELECT * FROM `arocrm_outbounddeliverycf` WHERE `arocrm_outbounddeliverycf`.`outbounddeliveryid` = '".$obdno."'");
		 $refrow = $this->db->fetch_array($refmod);
		 $ref = $refrow['cf_3067'];
		 $productid = $request->get('cf_nrl_products913_id');
		 
		 if($ref=='With respect to Sales Order' || $ref=='With Respect to STPO'){
		 $quany = 1;
		 $trandate = $request->get('cf_3227');
			$tmp = explode("-", $trandate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}
		
		$nwqr = $this->db->pquery("SELECT * FROM `arocrm_outbounddelivery_line_item_lineitem` WHERE `outbounddeliveryid` = '".$obdno."' AND `cf_2006` = '".$productid."'");
		$nwqrrow = $this->db->fetch_array($nwqr);
		$rlnum1 = $nwqrrow['cf_3076']; 
		$rlnum = explode(",",$rlnum1);
		foreach($rlnum as $serialno){
		   $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','QI-O','".$serdetail['cf_3084']."','".$serialno."','".$prevstk."','".$curstk."')");
		
		}
	 }
	 
	 
	 
		 
		 
		 
		 
		 foreach($rowct as $rowitem){
		 $data = $request->get('cf_2983_'.$rowitem);
		 
		 if($data=='B - Blocked'){
		 $srl = $request->get('cf_1778_display_'.$rowitem);
		 $serial = $serial.",'".$srl."'";
		 }
		 
		 if($data=='O - Open'){
		 $srl1 = $request->get('cf_1778_display_'.$rowitem);
		 $serial1 = $serial1.",'".$srl1."'";
		 }
		 
		 if($data=='S - Semiblocked'){
		 $srl2 = $request->get('cf_1778_display_'.$rowitem);
		 $serial2 = $serial2.",'".$srl2."'";
		 }
		 

		 }
		 $serial = ltrim($serial,",");
		 $serial1 = ltrim($serial1,",");
		 $serial2 = ltrim($serial2,",");

		 if($serial==""){
		 $serial="''";
		 }
		 if($serial1==""){
		 $serial1="''";
		 }
		 if($serial2==""){
		 $serial2="''";
		 }

		 if($ref=='With respect to Assembly Order'){

			 $qr11 = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'B' WHERE `cf_1258` IN (".$serial.") AND `cf_3387` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
			 
			 $qr14 = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'S' WHERE `cf_1258` IN (".$serial2.") AND `cf_3387` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')"; 
			 
			 $qrl2 = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'R' WHERE `cf_1258` NOT IN (".$serial.") AND `cf_1258` NOT IN (".$serial2.") AND `cf_3387` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
			 
			 $qrl3 =  "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'B' WHERE `cf_1258` IN (".$serial1.") AND `cf_3387` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";

		 }else{

			 $qr11 = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'B' WHERE `cf_1258` IN (".$serial.") AND `cf_3128` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
			 
			 $qr14 = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'S' WHERE `cf_1258` IN (".$serial2.") AND `cf_3128` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')"; 
			 
			 
			 $qrl2 = "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'R' WHERE `cf_1258` NOT IN (".$serial.") AND `cf_1258` NOT IN (".$serial2.") AND `cf_3128` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";
			 $qrl3 =  "UPDATE `arocrm_serialnumbercf` SET `cf_3084` = 'B' WHERE `cf_1258` IN (".$serial1.") AND `cf_3128` = '".$obdno."' AND `serialnumberid` in (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."')";

		 }

		 $ins_serial_cf = $this->db->pquery($qr11);
 	 	 $ins_serial_userassign = $this->db->pquery($qrl2);
         $ins_serial_cf = $this->db->pquery($qrl3);
		 
		 
		 
		 
		 if($ref=='With Respect to STPO' || $ref=='With respect to Sales Order'){
		 $quany = 1;
		 $trandate = $request->get('cf_3227');
			$tmp = explode("-", $trandate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}
		
		$nwqr = $this->db->pquery("SELECT * FROM `arocrm_outbounddelivery_line_item_lineitem` WHERE `outbounddeliveryid` = '".$obdno."' AND `cf_2006` = '".$productid."'");
		$nwqrrow = $this->db->fetch_array($nwqr);
		$rlnum1 = $nwqrrow['cf_3076']; 
		$rlnum = explode(",",$rlnum1);
		foreach($rlnum as $serialno){
		   $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','QI-O','".$serdetail['cf_3084']."','".$serialno."','".$prevstk."','".$curstk."')");
		
		}
	 }

	 }
		}




		// End of Serial Number QI Update Automation -- by Roni Modak 26-12-2018//


		// Serial Number Update Automation Goods Receipt -- by Roni Modak 27-12-2018//

		$timezone_offset_minutes = 330;
		$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
		date_default_timezone_set($timezone_name);

		$recorddata = $request->get('record');
		if($request->getModule()=='GoodsReceipt' && $recorddata==""){

		$ref = $request->get('cf_3453');
		if($ref=='With Respect to Service Order'){

			//If some code need to be added//



			//end of code need to be added//

		}else{

		$ibdno = $request->get('cf_nrl_inbounddelivery708_id');
		$plantid = $request->get('cf_nrl_plantmaster388_id');
		$totalrcount = $request->get('totalRowCount_Line_Item_Details');
		$ibdrefsql = "SELECT * FROM `arocrm_inbounddelivery` INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`  WHERE `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."'";
		$ibdreftmp = $this->db->pquery($ibdrefsql);
		$ref = $this->db->query_result($ibdreftmp,'0','cf_2862');
		$refreturnso  = $this->db->query_result($ibdreftmp,'0','cf_3193');
		if($refreturnso=='With respect to Sales Return'){

			//When the sales Returned and Taking the Stock Inn//

			$vdata = explode(",",$totalrcount);
			
			foreach($vdata as $rowid){

			$productid = $request->get('cf_1897_'.$rowid);
			$storeid = $request->get('cf_1901_'.$rowid);
			$allserialid = explode(",",$request->get('cf_3003_'.$rowid));
			$serialnum = '';
			
			$unitprice = $request->get('cf_1925_'.$rowid);
			$totalprice = $request->get('cf_1943_'.$rowid);
			$grsql = $this->db->pquery("UPDATE `arocrm_goodsreceipt_line_item_details_lineitem` SET `cf_1925` = '".$unitprice."',`cf_1943` = '".$totalprice."' WHERE `goodsreceiptid` = '".$recordModel->getId()."' AND `cf_1897` = '".$productid."'");
			
			$isqlbd = $this->db->pquery("UPDATE `arocrm_inbounddelivery_line_item_lineitem` SET `cpl_st` = '1' WHERE `inbounddeliveryid` = '".$ibdno."' AND `cf_2868` = '".$productid."'");
			
			foreach($allserialid as $serialno){
			$serialnum = $serialnum.$serialno.",";
			
		$quany = 1;
		$trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GR-SRT','".$serdetail['cf_1256']."','".$serialno."','".$prevstk."','".$curstk."')");
			
			
			
			$headerstr = "UPDATE `arocrm_serialnumber`
			 SET `cf_nrl_storagelocation106_id` = '".$storeid."'
			 WHERE `cf_nrl_products16_id` = '".$productid."'
			 AND `cf_nrl_plantmaster496_id` = '".$plantid."'
			 AND `name` = '".$serialno."'";

			$erpsql = "UPDATE `arocrm_serialnumbercf`
			SET `cf_2834` = '1'
			WHERE `cf_3348` = '".$ibdno."'
			AND `cf_1258` = '".$serialno."'
			AND `serialnumberid` = (SELECT `serialnumberid` FROM `arocrm_serialnumber`
			WHERE `cf_nrl_products16_id` = '".$productid."' AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."')";


			$hvaerp = $this->db->pquery($headerstr);
			$vaerp = $this->db->pquery($erpsql);


			$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."' LIMIT 0,1)";
			$selqry = $this->db->pquery($selqty);
			$totalqty = (int)$this->db->query_result($selqry,'0','cf_1356') + 1;

			$updateqty = $this->db->pquery("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."')");
		}
		
		$quany = $request->get('cf_1907_'.$rowid);
	    $trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
	
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$request->get('cf_3003_'.$rowid)."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GR-SRT','".$request->get('cf_1947_'.$rowid)."','".$request->get('cf_3003_'.$rowid)."','".$prevstk."','".$curstk."')");
		
		
	}

//End of the sales Returned and Taking the Stock Inn//

		}else{
		$vdata = explode(",",$totalrcount);
		$serceamount = 0;
		$sdf = $this->db->pquery("SELECT `purchaseorderid`,`cf_2712` FROM `arocrm_purchaseordercf` where `purchaseorderid` = (SELECT `cf_nrl_purchaseorder573_id` FROM `arocrm_inbounddelivery` WHERE `inbounddeliveryid` = '".$ibdno."')");
		$refpo = $this->db->query_result($sdf,'0','cf_2712');
		$acpono =  $this->db->query_result($sdf,'0','purchaseorderid');
		
		$totqty = 0;
		foreach($vdata as $rowidss){
		$qty = $request->get('cf_1907_'.$rowidss);
		$totqty = (float)$totqty + (float)$qty;
		}

		//Retriving of any service order / service existance //
		$servicegr = $this->db->pquery("SELECT COUNT(*) as count FROM `arocrm_goodsreceipt` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_goodsreceipt`.`goodsreceiptid`  WHERE `arocrm_crmentity`.`deleted` = '0' AND `cf_nrl_purchaseorder383_id` = (SELECT `arocrm_purchaseorder`.`purchaseorderid` FROM `arocrm_purchaseorder`
		INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_purchaseorder`.`purchaseorderid`
		INNER JOIN `arocrm_purchaseordercf` ON `arocrm_purchaseordercf`.`purchaseorderid` = `arocrm_purchaseorder`.`purchaseorderid`
		WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_purchaseordercf`.`cf_2709` = 'Service Order' AND `arocrm_purchaseorder`.`postatus` = 'Approved' AND `arocrm_purchaseorder`.`cf_nrl_purchaseorder107_id` = '".$acpono."')");
       $sercecount = $this->db->query_result($servicegr,'0','count');
		if($sercecount > 0){

			$serviceamtgr = $this->db->pquery("SELECT `arocrm_purchaseorder`.`pre_tax_total` FROM `arocrm_purchaseorder`
			INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_purchaseorder`.`purchaseorderid`
			INNER JOIN `arocrm_purchaseordercf` ON `arocrm_purchaseordercf`.`purchaseorderid` = `arocrm_purchaseorder`.`purchaseorderid`
			WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_purchaseordercf`.`cf_2709` = 'Service Order' AND `arocrm_purchaseorder`.`postatus` = 'Approved' AND `arocrm_purchaseorder`.`cf_nrl_purchaseorder107_id` = '".$acpono."'");
            $serceamount = $this->db->query_result($serviceamtgr,'0','pre_tax_total');

		}
		 $serceamount = (float)$serceamount / (float)$totqty;
		

		//End of Retriving of any service order / service existance //

		foreach($vdata as $rowid){

		$productid = $request->get('cf_1897_'.$rowid);
		$storeid = $request->get('cf_1901_'.$rowid);
		$allserialid = explode(",",$request->get('cf_3003_'.$rowid));
		$unitprice = $request->get('cf_1925_'.$rowid);
		$totalprice = $request->get('cf_1943_'.$rowid);
		
		$costprice = (float)$serceamount + (float)$unitprice;
		$serialnum = '';
		
		  	$grsql = $this->db->pquery("UPDATE `arocrm_goodsreceipt_line_item_details_lineitem` SET `cf_1925` = '".$unitprice."',`cf_1943` = '".$totalprice."' WHERE `goodsreceiptid` = '".$recordModel->getId()."' AND `cf_1897` = '".$productid."'");
			$isqlbd = $this->db->pquery("UPDATE `arocrm_inbounddelivery_line_item_lineitem` SET `cpl_st` = '1' WHERE `inbounddeliveryid` = '".$ibdno."' AND `cf_2868` = '".$productid."'");
			
		foreach($allserialid as $serialno){
			
		$serialnum = $serialnum.$serialno.",";
	  	if($ref=='Reference to STR'){

		
		$quany = 1;
		$trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GR-STR','".$serdetail['cf_1256']."','".$serialno."','".$prevstk."','".$curstk."')");
			
			
			
			$headerstr = "UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `cf_nrl_products16_id` = '".$productid."' AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."'";

			$erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '1' WHERE `cf_3196` = '".$ibdno."' AND `cf_1258` = '".$serialno."' AND `cf_1256` = 'R' AND `serialnumberid` = (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."'
			AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."')";
			
		


		$quany = 1;
		$trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$serdetail['cf_1256']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GR-STR','".$serdetail['cf_1256']."','".$serialno."','".$prevstk."','".$curstk."')");
			
		

		}else if($refpo=='Against Warranty'){
			
			
		$quany = 1;
		$trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GR-AW','B','".$serialno."','".$prevstk."','".$curstk."')");
			

		 $headerstr = "UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `cf_nrl_products16_id` = '".$productid."' AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."'";

	   $erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '1' WHERE `cf_3348` = '".$ibdno."' AND `cf_1258` = '".$serialno."' AND `cf_1256` = 'B' AND `serialnumberid` = (SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."'
			AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."')";

		}else{
			
			
		$quany = 1;
		$trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'O' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GR-O','O','".$serialno."','".$prevstk."','".$curstk."')");
			

		$headerstr = "UPDATE `arocrm_serialnumber` SET `cf_nrl_storagelocation106_id` = '".$storeid."' WHERE `cf_nrl_products16_id` = '".$productid."' AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."'";

		$erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '1',`cf_1264` = '".$costprice."' WHERE `cf_1270` = '".$ibdno."' AND `cf_1258` = '".$serialno."'  AND `serialnumberid` = (SELECT `serialnumberid` FROM `arocrm_serialnumber`
		 WHERE `cf_nrl_products16_id` = '".$productid."' AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."')";

        }

		$hvaerp = $this->db->pquery($headerstr);
		$vaerp = $this->db->pquery($erpsql);


		$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."' LIMIT 0,1)";
		$selqry = $this->db->pquery($selqty);
		$totalqty = (int)$this->db->query_result($selqry,'0','cf_1356') + 1;

		$updateqty = $this->db->pquery("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."')");

		}
		
		if($ref=='Reference to STR'){
			
		
		}else if($refpo=='Against Warranty'){
			
		
		
		$quany = $request->get('cf_1907_'.$rowid);
	    $trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$request->get('cf_3003_'.$rowid)."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GR-AWRT','".$request->get('cf_1947_'.$rowid)."','".$request->get('cf_3003_'.$rowid)."','".$prevstk."','".$curstk."')");
		
		
		}else{
		
		$quany = $request->get('cf_1907_'.$rowid);
		$trandate = $request->get('cf_3223');
		
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		$quany = $request->get('cf_1907_'.$rowid);
	    $trandate = $request->get('cf_3223');
		$tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$request->get('cf_3003_'.$rowid)."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GR-O','".$request->get('cf_1947_'.$rowid)."','".$request->get('cf_3003_'.$rowid)."','".$prevstk."','".$curstk."')");
		
		}
		
		}
     }
	 }

		}
		// End of Serial Number Update Automation Goods Receipt -- by Roni Modak 26-12-2018//

		//Sales Order Save Code added by Roni Modak 16-05-2019//
		
		
		    $timezone_offset_minutes = 330;
			$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
			date_default_timezone_set($timezone_name);

			$recorddata = $request->get('record');
			if($request->getModule()=='SalesOrder' && $recorddata==""){
			$received_amount = $request->get('received_amount');
			$balance_amount = $request->get('actual_balance');
			$salesorder_id = $recordModel->getId();
			$this->db->pquery("UPDATE `arocrm_salesorder` SET `received` = '".$received_amount."',`balance` = '".$balance_amount."' WHERE `salesorderid` = '".$salesorder_id."'");
			}
		
		// End of Save of Sales Order  Code added by Roni Modak 16-05-2019//
		
		
		// Invoice Automation  -- by Roni Modak 18-02-2019//

			$timezone_offset_minutes = 330;
			$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
			date_default_timezone_set($timezone_name);

			$recorddata = $request->get('record');
			if($request->getModule()=='Invoice' && $recorddata==""){
			$invtype = $request->get('cf_3288');
			$received_amount = $request->get('received_amount');
			$balance_amount = $request->get('actual_balance');
			$inv_id = $recordModel->getId();
			$invst = '';
			if($balance_amount <= 0){
			$balance_amount = 0;
			$this->db->pquery("UPDATE `arocrm_invoice` SET `invoicestatus` = 'Paid' WHERE `invoiceid` = '".$inv_id."'");
			}
			
			$this->db->pquery("UPDATE `arocrm_invoice` SET `received` = '".$received_amount."',`balance` = '".$balance_amount."' WHERE `invoiceid` = '".$inv_id."'");
			
			if($invtype == 'Sales Invoice'){
			$salesorder_id = $request->get('salesorder_id');
			$this->db->pquery("UPDATE `arocrm_salesordercf` SET `cf_5201` = 'Invoice Done' WHERE `salesorderid` =  '".$salesorder_id."'");
			$account_id = $request->get('account_id');
			$tmpinv = explode("-",$request->get('cf_4627'));
			$invoicedate = $tmpinv[2]."-".$tmpinv[1]."-".$tmpinv[0];
            $sqlcstdata = "SELECT * FROM `arocrm_account`
			INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_account`.`accountid`
			INNER JOIN `arocrm_accountscf` ON `arocrm_accountscf`.`accountid` = `arocrm_account`.`accountid`
			WHERE `arocrm_account`.`accountid` = '".$account_id."' AND `arocrm_crmentity`.`deleted` = '0'";
			$qrycstdata =  $this->db->pquery($sqlcstdata);
			$creditlimit = $this->db->query_result($qrycstdata,0,'cf_4313');
			$creditdays = $this->db->query_result($qrycstdata,0,'cf_4315');
			$totalamt = $request->get('total');
			$duedate = date('Y-m-d', strtotime($invoicedate. ' + '.$creditdays.' days'));

			if(($creditlimit >= $totalamt) && ($creditdays > 0))
			{
				
               $creditins = "INSERT INTO `arocrm_salesorder_creditlimit`(`salesorderid`, `invid`, `customerid`, `amount`, `createddate`, `duedate`, `status`) VALUES ('".$salesorder_id."','".$recordModel->getId()."','".$account_id."','".$request->get('total')."','".$invoicedate."','".$duedate."','0')";
				$creditupqry = $this->db->pquery($creditins);

				$remaincredit = $creditlimit - $totalamt;

				$updatecreditlimit = $this->db->pquery("UPDATE `arocrm_accountscf` SET `cf_4313` = '".$remaincredit."' WHERE `accountid` = '".$account_id."'");

			}else{
				$upsale = "UPDATE `arocrm_invoice` SET `invoicestatus` = ? WHERE `invoiceid` = ?";
				$upsaleqry = $this->db->pquery($upsale,array('Pending For Approval',$recordModel->getId()));
			}
			
			$actualinvbal = (float)$request->get('actual_balance');
			if($actualinvbal < 0){
			$actualvalue = (-1)*($actualinvbal);	
			$custid = $request->get('account_id');
			$tmpinv = explode("-",$request->get('cf_4627'));
			$invoicedate = $tmpinv[2]."-".$tmpinv[1]."-".$tmpinv[0];
            
			        $branchid = $request->get('cf_nrl_plantmaster164_id');
					$mfcrmid = $this->db->pquery("SELECT * FROM `arocrm_crmentity_seq` where 1");
					$recid = $this->db->query_result($mfcrmid,'0','id');
					$recid = (int)$recid + 1;
					
					$nextrecid = $recid;

					$updateeneseq = $this->db->pquery("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."' where 1");

					$postdate = $request->get('cf_4627');
					$tmp = explode("-",$postdate);
					$tmp1 = strlen($tmp[0]);
					$tmp2 = strlen($tmp[1]);
					$tmp3 = strlen($tmp[2]);

					if($tmp1==2 && $tmp2==2 && $tmp3==4){
					$postdate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
					}		 
					$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
					`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`)
					VALUES('".$recid."','".$_SESSION['authenticated_user_id']."','".$request->get('assigned_user_id')."','".$_SESSION['authenticated_user_id']."','CustomerPayment','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','0','1','0','0','CRM','CustomerPayment')";
					$crmenins = $this->db->pquery($crmin);

					$mfnumid = $this->db->pquery("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'CustomerPayment'");
					$recserialid = $this->db->query_result($mfnumid,'0','prefix').$this->db->query_result($mfnumid,'0','cur_id');

					$nextid = (int)$this->db->query_result($mfnumid,'0','cur_id') + 1;

					$updatenumseq = $this->db->pquery("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'CustomerPayment'");

					$ins_serial_userassign = $this->db->pquery("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES ('".$recid."','".$request->get('assigned_user_id')."','0')");

					$this->db->pquery("INSERT INTO `arocrm_customerpayment`(`customerpaymentid`, `name`, `customerpaymentno`, `cf_nrl_accounts363_id`, `cf_nrl_plantmaster1000_id`) VALUES ('".$recid."','CustomerPayment', '".$recserialid."','".$custid."','".$branchid."')");	
					

					$this->db->pquery("INSERT INTO `arocrm_customerpaymentcf`(`customerpaymentid`,`cf_3335`,`cf_3342`,`cf_3376`, `cf_4963`, `cf_4965`, `cf_4967`, `cf_5001`, `cf_5003`) VALUES ('".$recid."','Advance Payment','".$actualvalue."','Approved','".$request->get('cf_4623')."','".$request->get('cf_4625')."','".$postdate."','Bank','Auto Created')");
					
					$doctype = 'CA';
				
				 $deliverto = $request->get('cf_nrl_plantmaster164_id');
				 $branchcode = $this->getBranchCode($deliverto);
				 $financial_year = $request->get('cf_4623');
				 $financial_year_array = explode("-",$financial_year);
				 $year1 = trim($financial_year_array[0]);
				 $year2 = trim($financial_year_array[1]);
				 $year_1 = substr($year1,2);
				 $year_2 = substr($year2,2);
				 $total_year = $year_1."-".$year_2;
				 $postmonth = date("F",strtotime($postdate)); 
				 
				 if($postmonth=='April'){
					$months = 'A'; 
				 }else if($postmonth=='May'){
					$months = 'B'; 
				 }else if($postmonth=='June'){
					$months = 'C'; 
				 }else if($postmonth=='July'){
					$months = 'D'; 
				 }else if($postmonth=='August'){
					$months = 'E'; 
				 }else if($postmonth=='September'){
					$months = 'F'; 
				 }else if($postmonth=='October'){
					$months = 'G'; 
				 }else if($postmonth=='November'){
					$months = 'H'; 
				 }else if($postmonth=='December'){
					$months = 'J'; 
				 }else if($postmonth=='January'){
					$months = 'K'; 
				 }else if($postmonth=='February'){
					$months = 'L'; 
				 }else if($postmonth=='March'){
					$months = 'M'; 
				 }
				 $docno = '';
				 $havsql = $this->db->pquery("SELECT * FROM `arocrm_custom_document_numbering` WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 $havcount = $this->db->num_rows($havsql);
				 if($havcount==0){
				$docno = '001';
				$this->db->pquery("INSERT INTO `arocrm_custom_document_numbering`(`docnumber`, `plant`, `month`, `year`, `serno`) VALUES ('".$doctype."','".$branchcode."','".$months."','".$total_year."','".$docno."')");	 
				 }else{
				 $currentno = $this->db->query_result($havsql,'0','serno'); 
				 $docno = (int)$currentno + 1;
				 if(strlen($docno)==1){
				 $docno = '00'.$docno;
				 }else if(strlen($docno)==2){
				 $docno = '0'.$docno;
				 }
				 }	
				 $this->db->pquery("UPDATE `arocrm_custom_document_numbering` SET `serno` = '".$docno."'  WHERE `docnumber` = '".$doctype."' AND `plant` = '".$branchcode."' AND `month` = '".$months."' AND `year` = '".$total_year."'");
				 
				 $document_numbering = $doctype.$branchcode.$months.$docno."/".$total_year;
				 
				 $this->db->pquery("UPDATE `arocrm_customerpayment` SET `customerpaymentno` = '".$document_numbering."' WHERE `customerpaymentid` = '".$recid."'");
				 
					$name = 'CustomerPayment'."_".$document_numbering;
					$updatename = $this->db->pquery("UPDATE `arocrm_customerpayment` SET `name` = '".$name."' WHERE `customerpaymentid` = ".$recid);
					$updatename1 = $this->db->pquery("UPDATE `arocrm_crmentity` SET `label` = '".$name."' WHERE `crmid` = ".$recid);
		

			
			}
			
			
			
		}
		
			
			}
			// End of Invoice Update Automation  -- by Roni Modak 18-02-2019//

			
	// Serial Number Update Automation -- Module: Goods Issue -- by Roni Modak 06-01-2019//

		$timezone_offset_minutes = 330;
		$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
		date_default_timezone_set($timezone_name);

		$recorddata = $request->get('record');
		if($request->getModule()=='GoodsIssue' && $recorddata==""){

		$obdno = $request->get('cf_nrl_outbounddelivery617_id');
		$plantid = $request->get('cf_nrl_plantmaster280_id');
		$totalrcount = $request->get('totalRowCount_Line_Item');

		$obdrefsql = "SELECT * FROM `arocrm_outbounddelivery` INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid` = `arocrm_outbounddelivery`.`outbounddeliveryid`  WHERE `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$obdno."'";
		$obdreftmp = $this->db->pquery($obdrefsql);
	    $ref = $this->db->query_result($obdreftmp,'0','cf_3067');


		$vdata = explode(",",$totalrcount);
		foreach($vdata as $rowid){

		$productid = $request->get('cf_3163_'.$rowid);
		$storeid = $request->get('cf_3173_'.$rowid);
		$allserialid = explode(",",$request->get('cf_3179_'.$rowid));
		
		foreach($allserialid as $serialno){
		
		
		
		// updating stock table code added by Roni Modak -- 15-04-2019 //
		
			$quany = 1;
			$trandate = $request->get('cf_3229');
			$tmp = explode("-", $trandate);
			$tmp1 = strlen($tmp[0]);
			$tmp2 = strlen($tmp[1]);
			$tmp3 = strlen($tmp[2]);

			if($tmp1==2 && $tmp2==2 && $tmp3==4){
			$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			}

		if($ref=='With respect to Assembly Order'){
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GI-AO','".$serdetail['cf_3084']."','".$request->get('cf_3179_'.$rowid)."','".$prevstk."','".$curstk."')");
		
		
			}else if($ref=='With Respect to Purchase Return'){
			
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk + $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GI-PRT','".$serdetail['cf_3084']."','".$request->get('cf_3179_'.$rowid)."','".$prevstk."','".$curstk."')");
			
			}else if($ref=='With Respect to STPO'){
				
			 $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		
		 
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GI-O','".$serdetail['cf_3084']."','".$request->get('cf_3179_'.$rowid)."','".$prevstk."','".$curstk."')");


		}else{
	
	
	  $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		if($serdetail['cf_3084']=='O'){
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = '".$serdetail['cf_3084']."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GI-O','".$serdetail['cf_3084']."','".$serialno."','".$prevstk."','".$curstk."')");
		}
		
			
			}
		
		
		
		// end of  updating stock table code added by Roni Modak -- 15-04-2019 //
		
		
				if($ref=='With respect to Assembly Order'){

					$erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '2' WHERE `cf_3387` = '".$obdno."'
					AND `cf_1258` = '".$serialno."'
					AND `cf_3084` = 'R' AND `serialnumberid` =
					(SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."'
					AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."')";
					$vaerp = $this->db->pquery($erpsql);

					$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."' LIMIT 0,1)";
					$selqry = $this->db->pquery($selqty);
					$totalqty = (int)$this->db->query_result($selqry,'0','cf_1356') - 1;

					$updateqty = $this->db->pquery("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."')");

				
				
		$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		$serdetail = $this->db->fetch_array($storeqtysql);
		
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'R' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = $this->db->pquery($stkqtysql);
		$qtysqlnum = $this->db->num_rows($newqtysql);
		$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		
		$curstk = $prevstk - $quany;
		
		$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GI-AO','R','".$request->get('cf_3179_'.$rowid)."','".$prevstk."','".$curstk."')");
				
				
				
				}else if($ref=='With Respect to Purchase Return'){

				$erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '2',`cf_1256` = 'B' WHERE `cf_1258` = '".$serialno."'";
				$vaerp = $this->db->pquery($erpsql);
				
				
				$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
				$serdetail = $this->db->fetch_array($storeqtysql);

				$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'B' ORDER BY `id` DESC LIMIT 0,1";
				$newqtysql = $this->db->pquery($stkqtysql);
				$qtysqlnum = $this->db->num_rows($newqtysql);
				$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
				if($qtysqlnum==0){
				$prevstk = 0;
				}

				$curstk = $prevstk - $quany;

				$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GI-AO','B','".$serialno."','".$prevstk."','".$curstk."')");

				}
				else if($ref=='With Respect to STPO'){
					
				 $storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
		         $serdetail = $this->db->fetch_array($storeqtysql);
	
		
				$erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '2',`cf_3084` = 'R' WHERE `cf_3128` = '".$obdno."'
				AND `cf_1258` = '".$serialno."'
			    AND `serialnumberid` =
				(SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$serdetail['cf_nrl_products16_id']."'
				AND `cf_nrl_plantmaster496_id` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `name` = '".$serialno."')";
				$vaerp = $this->db->pquery($erpsql);

				$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."' LIMIT 0,1)";
				$selqry = $this->db->pquery($selqty);
				$totalqty = (int)$this->db->query_result($selqry,'0','cf_1356') - 1;

				$updateqty = $this->db->pquery("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."')");
				
				
				}else{

				$erpsql = "UPDATE `arocrm_serialnumbercf` SET `cf_2834` = '2',`cf_3084` = 'R' WHERE `cf_3128` = '".$obdno."'
				AND `cf_1258` = '".$serialno."'
			    AND `serialnumberid` =
				(SELECT `serialnumberid` FROM `arocrm_serialnumber` WHERE `cf_nrl_products16_id` = '".$productid."'
				AND `cf_nrl_plantmaster496_id` = '".$plantid."' AND `name` = '".$serialno."')";
				$vaerp = $this->db->pquery($erpsql);

				$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."' LIMIT 0,1)";
				$selqry = $this->db->pquery($selqty);
				$totalqty = (int)$this->db->query_result($selqry,'0','cf_1356') - 1;

				$updateqty = $this->db->pquery("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = '".$productid."' AND `cf_nrl_plantmaster103_id` = '".$plantid."')");
				
				
				
				if($serdetail['cf_3084']=='O'){
				
				$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'R' ORDER BY `id` DESC LIMIT 0,1";
				$newqtysql = $this->db->pquery($stkqtysql);
				$qtysqlnum = $this->db->num_rows($newqtysql);
				$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
				if($qtysqlnum==0){
				$prevstk = 0;
				}

				$curstk = $prevstk + $quany;
				
				
				$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
				$serdetail = $this->db->fetch_array($storeqtysql);


				$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$serdetail['cf_nrl_products16_id']."','".$serdetail['cf_nrl_plantmaster496_id']."','".$serdetail['cf_nrl_storagelocation106_id']."','".$trandate."','".date('Y-m-d')."','".$quany."','0','GI-O','R','".$serialno."','".$prevstk."','".$curstk."')");


				}
				
				$storeqtysql = $this->db->pquery("SELECT * FROM `arocrm_serialnumber`  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid` WHERE  `arocrm_serialnumber`.`name` = '".$serialno."'");
				$serdetail = $this->db->fetch_array($storeqtysql);

				$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$serdetail['cf_nrl_products16_id']."' AND `plant` = '".$serdetail['cf_nrl_plantmaster496_id']."' AND `store` = '".$serdetail['cf_nrl_storagelocation106_id']."' AND `qualitystatus` = 'R' ORDER BY `id` DESC LIMIT 0,1";
				$newqtysql = $this->db->pquery($stkqtysql);
				$qtysqlnum = $this->db->num_rows($newqtysql);
				$prevstk = (int)$this->db->query_result($newqtysql,'0','totqty');
				if($qtysqlnum==0){
				$prevstk = 0;
				}

				$curstk = $prevstk - $quany;

				$this->db->pquery("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','0','".$quany."','GI-O','R','".$serialno."','".$prevstk."','".$curstk."')");


				}
		}
		
		
		}
		
		}

		// End of Serial Number Update Automation -- Module: Goods Issue -- by Roni Modak 06-01-2019//



// PurchaseOrder Module Purchase Order Customisation Code added by Roni Modak on 27-02-2019 //

if($request->getModule()=='PurchaseOrder'){

	$recorddata = $request->get('record');
	if(isset($recorddata) && $recorddata==""){
    $exchangerate = $request->get('inr_exchange_rate');
		$updateqty = $this->db->pquery("UPDATE `arocrm_purchaseorder` SET `inr_exchange_rate` = '".$exchangerate."' WHERE `purchaseorderid` = '".$recordModel->getId()."'");
	}
}

// PurchaseOrder Module End of Purchase Order Customisation Code added by Roni Modak on 27-02-2019//

		if($request->getModule()=='Products'){
		$recorddata = $request->get('record');
		if(isset($recorddata) && $recorddata==""){
		$pro_seqence = 0;
		$prodcode = "";



		 $ptype = $_POST['cf_1336'];
		 $pgroup1 = $_POST['cf_1338'];
		 $pggroup2 = explode("-",$pgroup1);
		 $psubgroup = $_POST['cf_1340'];
		 $pmaker = $_POST['cf_1342'];
		 $pgroup = trim($pggroup2[0]);
		 $pronq = $ptype.$pgroup.$pmaker.$psubgroup;

		 $pro_sql = "select * from `arocrm_product_master_seq` where `codeformat` = ?";
		 $pro_query = $this->db->pquery($pro_sql, array($pronq));
		 $pro_rcount = $this->db->num_rows($pro_query);
		 if($pro_rcount==0){
		 $pro_ins = "insert into `arocrm_product_master_seq` (`codeformat`) values(?)";
         $pro_ins_query = $this->db->pquery($pro_ins,array($pronq));
		 $prodcode = $ptype.$pgroup.$pmaker.$psubgroup."001";
		 }else{

		 $pro_seqence = $this->db->query_result($pro_query,0,"sequence");
		 $pro_seqence = (int)$pro_seqence + 1;

		 if(strlen($pro_seqence)=='1'){
		 $next_seqence = "00".$pro_seqence;
		 }

		 if(strlen($pro_seqence)=='2'){
		 $next_seqence = "0".$pro_seqence;
		 }

		 $prodcode = $pronq.$next_seqence;

		 $pro_update_sql_seq = "update `arocrm_product_master_seq` set `sequence` = ? where `codeformat` = ?";
		 $pro_update_query_seq = $this->db->pquery($pro_update_sql_seq,array($next_seqence,$pronq));
		 }


		 $pro_update_sql = "update `arocrm_products` set `product_no` = ?, `productcode` = ?, discontinued = ? where `productid` = ?";
		 $pro_update_query = $this->db->pquery($pro_update_sql,array($prodcode,$prodcode,'1',$recordModel->getId()));


		}
		if(isset($_POST['discontinued'])){
		$pro_update_sql1 = "update `arocrm_products` set `discontinued` = ? where `productid` = ?";
		$pro_update_query1 = $this->db->pquery($pro_update_sql1,array('1',$recordModel->getId()));
		}
		}


		// End Customising for Product Code --Roni Modak  31-10-2018 End //

		if($request->getModule()=='PurchaseRequisition'){
		$totalRowCount = $_POST['totalRowCount'];
		$totalRowCount_array = explode(",",$totalRowCount);
		$c = 1;
		foreach($totalRowCount_array as $rowC){
		$reqiid = $recordModel->getId();
		$seq = $c;
		$qty = $_POST['qty'.$rowC];
		$deldate = $_POST['deliverydate'.$rowC];
		$proid = $_POST['hdnProductId'.$rowC];

		$procd_sql = "SELECT * FROM `arocrm_products` where `productid` = ?";
		$procd_query = $this->db->pquery($procd_sql, array($proid));
		$procd_code = $this->db->query_result($procd_query,0,"product_no");

    $req_sql = "insert into `arocrm_purchaserequisition_lineitem` (`requisition_id`,`sequence`,`product_code`,`quantity`,`delivery_date`)values(?,?,?,?,?)";
		$req_query = $this->db->pquery($req_sql, array($reqiid,$seq,$procd_code,$qty,$deldate));
		$c++;
		}
		}
		// End of Customising for the product ---- 08-11-2018  End//



		/*Code added by Rahul Sinha--Start*/

		$workflowid = $request->get('submit_for_approval');
		$assigned_user_id = $request->get('assigned_user_id');
		$note = $request->get('note');
		$note = trim($note);

		$crmentity_query =  "SELECT * FROM arocrm_crmentity WHERE crmid = ? AND setype = ? AND approval_workflowid=?";
		$crmentity_result = $this->db->pquery($crmentity_query,array($recordModel->getId(),$request->getModule(),0));
		$crmentity_count = $this->db->num_rows($crmentity_result);
		if($crmentity_count==1)
		{
		  $i = 1;
		  $approval_sql = "SELECT com_arocrm_workflows.*, com_arocrm_workflows_approver.* FROM com_arocrm_workflows
                    INNER JOIN com_arocrm_workflows_approver ON com_arocrm_workflows_approver.workflowid = com_arocrm_workflows.workflow_id
					WHERE com_arocrm_workflows.workflow_id=? ORDER BY com_arocrm_workflows_approver.id";
					$approval_query = $this->db->pquery($approval_sql,array($workflowid));
					$approval_count = $this->db->num_rows($approval_query);
					if($approval_count>0)
					{
					     while($row = $this->db->fetch_array($approval_query))
		                 {
						      $crmseq = $this->db->pquery('SELECT * FROM arocrm_crmentity_seq');
							  $crmcnt = $this->db->num_rows($crmseq);
							  $crmrow = $this->db->fetch_array($crmseq);
							  $crm_seq_id = $crmrow['id'];
							  date_default_timezone_set('Asia/Kolkata');
							  $currenttime = date('Y-m-d H:i:s');
							  $crmid = $crm_seq_id + 1;
							  $smownerid = 1;
							  $subject = $row['workflowname'];
							  $module = 'Approvals';
							  $approval_status1 = 'Submitted';
							  $approval_status2 = 'Pending';
							  if($row['approver']=='L1')
							  {
							  $actual_approver = $this->getReportsToId($assigned_user_id);
							  }else if($row['approver']=='L2')
							  {
							  $actual_approver = $this->getReportsToId($assigned_user_id);
							  $actual_approver = $this->getReportsToId($actual_approver);
							  }else if($row['approver']=='L3')
							  {
							  $actual_approver = $this->getReportsToId($assigned_user_id);
							  $actual_approver = $this->getReportsToId($actual_approver);
							  $actual_approver = $this->getReportsToId($actual_approver);
							  }else{
							  $actual_approver = $row['approver'];
							  }

$insert_crmentity = "INSERT INTO arocrm_crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,createdtime,modifiedtime,presence,deleted,label,approval_workflowid) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
$result_crmentity = $this->db->pquery($insert_crmentity,array($crmid,$smownerid,$smownerid,$smownerid,'Approvals',$currenttime,$currenttime,1,0,$subject,$workflowid));

$insert_arocrm_approvals = "INSERT INTO arocrm_approvals (approvalsid,name) VALUES (?,?)";
$result_arocrm_approvals = $this->db->pquery($insert_arocrm_approvals,array($crmid,$subject));

$insert_arocrm_approvalscf = "INSERT INTO arocrm_approvalscf (approvalsid,cf_2787,cf_2797,cf_2795,cf_2789,cf_2793) VALUES (?,?,?,?,?,?)";
$result_arocrm_approvalscf = $this->db->pquery($insert_arocrm_approvalscf,array($crmid,$row['module_name'],$note,$count,$actual_approver,$approval_status2));

$insert_crmentityrel = "INSERT INTO arocrm_crmentityrel (crmid,module,relcrmid,relmodule) VALUES (?,?,?,?)";
$result_crmentityrel = $this->db->pquery($insert_crmentityrel,array($recordModel->getId(),$request->getModule(),$crmid,'Approvals'));

$update_crmentity = "UPDATE arocrm_crmentity_seq SET id =?";
$result_crmentity = $this->db->pquery($update_crmentity,array($crmid));
$i++;
						 }

$parent_crmentity_update = "UPDATE arocrm_crmentity SET approval_workflowid = ? WHERE crmid = ?";
$parent_crmentity_result = $this->db->pquery($parent_crmentity_update,array($workflowid,$recordModel->getId()));

$query1 = "SELECT arocrm_crmentityrel.*, arocrm_approvals.*, arocrm_approvalscf.*, arocrm_crmentity.* FROM arocrm_crmentityrel
           INNER JOIN arocrm_approvals ON arocrm_approvals.approvalsid = arocrm_crmentityrel.relcrmid
		   INNER JOIN arocrm_approvalscf ON arocrm_approvalscf.approvalsid = arocrm_approvals.approvalsid
		   INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_approvals.approvalsid
		   WHERE arocrm_crmentityrel.crmid = ? AND arocrm_crmentityrel.module = ? AND arocrm_crmentityrel.relmodule = ?
		   AND arocrm_approvalscf.cf_2793 = ? ORDER BY arocrm_approvals.approvalsid";
		   $query1_result = $this->db->pquery($query1,array($recordModel->getId(),$request->getModule(),'Approvals','Pending'));
		   $query1_count = $this->db->num_rows($query1_result);
		   if($query1_count>0)
           {
		     $query1_rowArray = array();
			 $q2array = array();
			 $q3array = array();
		     while($query1_row = $this->db->fetch_array($query1_result))
			 {
			    array_push($query1_rowArray,$query1_row['cf_2789']);
				$q2array[] = $query1_row;
			 }
			          if (in_array($assigned_user_id, $query1_rowArray))
					  {
						$query1_rowArrayCount = count($q2array);
						for ($j = 0; $j < $query1_rowArrayCount; $j++)
				         {
						      $actual_approver = $q2array[$j]['cf_2789'];
				              $approvalsid = $q2array[$j]['approvalsid'];
							  if($actual_approver==$assigned_user_id)
			                  {
							    $approved_update = "UPDATE arocrm_approvalscf SET cf_2793 = ? WHERE approvalsid = ?";
							    $approved_update_result = $this->db->pquery($approved_update,array('Approved',$approvalsid));

								$approved_update2 = "UPDATE arocrm_approvals SET app_status = ? WHERE approvalsid = ?";
							    $approved_update_result2 = $this->db->pquery($approved_update2,array(1,$approvalsid));
								$action = 'Approval';



								if(++$j === $query1_rowArrayCount)
								{

								$tasksql = "SELECT * FROM com_arocrm_workflowtasks_lineitem WHERE workflow_id = ?";
								$taskresult = $this->db->pquery($tasksql,array($workflowid));
								$taskcount = $this->db->num_rows($taskresult);
								if($taskcount>0)
								{
								  while($taskrow = $this->db->fetch_array($taskresult))
								  {
						$workflow_field_update = "UPDATE ".$taskrow['table_name']." SET ".$taskrow['fieldname']." = ? WHERE ".$taskrow['primary_key_column']." = ?";
						$workflow_field_result = $this->db->pquery($workflow_field_update,array($taskrow['value'],$recordModel->getId()));
								  }
								}



								}else{
								$next_approvalsid = $q2array[$j]['approvalsid'];
								$user_array = $this->getUserDetails($next_approvalsid);
								$parent_module_array = $this->getParentModuleDetails($recordModel->getId(),$request->getModule());
								
								$submitted_update = "UPDATE arocrm_approvalscf SET cf_2793 = ? WHERE approvalsid = ?";
								$submitted_update_result = $this->db->pquery($submitted_update,array('Submitted',$next_approvalsid));
								}
							  }
						 }
					  }
					else
					  {
					      $query1_rowArrayCount = count($q2array);
						  for ($j = 0; $j < $query1_rowArrayCount; $j++)
				          {
						   if($j==0)
						   {
						      $actual_approver = $q2array[$j]['cf_2789'];
				              $approvalsid = $q2array[$j]['approvalsid'];
							  $user_array = $this->getUserDetails($actual_approver);
							  $parent_module_array = $this->getParentModuleDetails($recordModel->getId(),$request->getModule());
							  
							  $assignedUserid = $parent_module_array['smownerid'];
	                          $assignedid_array = $this->getUserDetails($assignedUserid);
	                          $assigned_fullname = $assignedid_array['first_name']." ".$assignedid_array['last_name'];
							  
							  $approved_update = "UPDATE arocrm_approvalscf SET cf_2793 = ? WHERE approvalsid = ?";
							  $approved_update_result = $this->db->pquery($approved_update,array('Submitted',$approvalsid));
							  
							  require_once('modules/Emails/class.smtp.php');
							  require_once("modules/Emails/class.phpmailer.php");
							  require_once('modules/Emails/mail.php');
							  
							  //$mailStatus = send_mail($request->getModule(),'rahulsinha50@gmail.com','Test','edconsgroup18@gmail.com','test','test','', '', '', '', '', true);
							  
							 $this->email_approver($user_array['first_name'],$user_array['last_name'],$parent_module_array['document_no'],$assigned_fullname,$parent_module_array['createdtime'],$user_array['email'],$request->getModule());
						   }else{
						   }
						  }
					  }
		   }
					}
		  

		
		}
		return $recordModel;
		}

		/*Code added by Rahul Sinha--End*/
public function getReportsToId($id)
{
$sql = "SELECT * FROM arocrm_users WHERE id = ? AND status = ?";
$result = $this->db->pquery($sql,array($id,'Active'));
$count = $this->db->num_rows($result);
if($count==1)
{
$row = $this->db->fetch_array($result);
$reports_to_id = $row['reports_to_id'];
}
return $reports_to_id;
}

public function email_approver($firstname,$lastname,$documentno,$assignedto,$posting_date,$toemail,$module)
{
    
    $fullname = $firstname." ".$lastname;
	$message = "<html><body>";
	$message .= "<table width='100%'>";
	$message .= "<tr><td style='font-size:16px;'>Dear ".$firstname." ".$lastname."</strong></td></tr>";
	$message .= "<tr><td><strong>You have new Pending approval for Document No. ".$documentno."</td></tr>";
	$message .= "<tr><td>Login to ERP application and verify the document and do the needfull.</td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";
	$message .= "<tr><td>Document Details:</td></tr>";
	$message .= "<tr><td><strong>Module: </strong> ".$module."</td></tr>";
	$message .= "<tr><td><strong>Document No.: </strong> ".$documentno."</td></tr>";
	$message .= "<tr><td><strong>Created Time: </strong> ".$posting_date."</td></tr>";
	$message .= "<tr><td><strong>Assigned To: </strong> ".$assignedto."</td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";
	$message .= "<tr><td>Regards</td></tr>";
	$message .= "<tr><td>Administrator</td></tr>";
	$message .= "</table>";
	$message .= "</body></html>";
	
	date_default_timezone_set('Asia/Kolkata');
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->SMTPSecure = 'ssl';
	$mail->Host = 'ssl://smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Port = 465;
	$mail->Username = "edconsgroup18@gmail.com";
	$mail->Password = "Edcons#191218";
	$mail->setFrom('info@arocrm.com', 'EDCONS AUTOMOBILES');
	$mail->addAddress($toemail, $fullname);
	$mail->Subject = 'Approval Pending for Document Number. '.$documentno.''; 
	$mail->IsHTML(true);
	$mail->Body = $message;
	$mail->send();
	return "true";
}

public function getUserDetails($id)
{
$response = array();
$sql =  "SELECT * FROM arocrm_users WHERE id = ? AND status = ?";
$result = $this->db->pquery($sql,array($id,'Active'));
$count = $this->db->num_rows($result);
if($count==1)
{
$row = $this->db->fetch_array($result);
$response['first_name'] = $row['first_name'];
$response['last_name'] = $row['last_name'];
$response['email'] = $row['email1'];
}
return $response;
}

public function getFieldTableName($fieldname,$parentModule)
{
$response = array();
$sql =  "SELECT arocrm_tab.*, arocrm_field.* FROM arocrm_tab
INNER JOIN arocrm_field ON arocrm_field.tabid = arocrm_tab.tabid
WHERE arocrm_tab.name = ? AND arocrm_field.fieldname = ?";
$result = $this->db->pquery($sql,array($parentModule,$fieldname));
$count = $this->db->num_rows($result);
if($count==1)
{
$row = $this->db->fetch_array($result);
$response['tablename'] = $row['tablename'];
$response['columnname'] = $row['columnname'];
}
return $response;
}

public function getParentModuleDetails($id,$module)
{
$modules = strtolower($module);
$response = array();
if($module!='Invoice' || $module!='PurchaseOrder' || $module!='SalesOrder')
{
$sql = "SELECT arocrm_".$modules.".*, arocrm_crmentity.* FROM arocrm_".$modules."
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_".$modules.".".$modules."id
						 WHERE arocrm_crmentity.deleted=? AND arocrm_".$modules.".".$modules."id=?";
						 $result = $this->db->pquery($sql,array(0,$id));
						 $count = $this->db->num_rows($result);
						 if($count==1)
					     {
						   $row = $this->db->fetch_array($result);
						   $document_no = $row[''.$modules.'no'];
						   $smownerid = $row['smownerid'];
						   $createdtime = $row['createdtime'];
						 }
}else{
$sql = "SELECT arocrm_".$modules.".*, arocrm_crmentity.* FROM arocrm_".$modules."
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_".$modules.".".$modules."id
						 WHERE arocrm_crmentity.deleted=? AND arocrm_".$modules.".".$modules."id=?";
						 $result = $this->db->pquery($sql,array(0,$id));
						 $count = $this->db->num_rows($result);
						 if($count==1)
					     {
						   $row = $this->db->fetch_array($result);
						   $document_no = $row[''.$modules.'_no'];
						   $smownerid = $row['smownerid'];
						   $createdtime = $row['createdtime'];
						 }
}
						 $response['document_no'] = $document_no;
						 $response['smownerid'] = $smownerid;
						 $response['createdtime'] = $createdtime;
						 return $response;
}



public function getWorkflowTasks($workflowid,$action,$parentmodule)
{
$sql = "SELECT com_arocrm_workflows.*, com_arocrm_workflowtasks.* FROM com_arocrm_workflows
                     INNER JOIN com_arocrm_workflowtasks ON com_arocrm_workflowtasks.workflow_id = com_arocrm_workflows.workflow_id
					 WHERE com_arocrm_workflows.workflow_id = ? AND com_arocrm_workflows.module_name = ?
					 AND com_arocrm_workflowtasks.approval_action = ?";
					 $result = $this->db->pquery($sql,array($workflowid,$parentmodule,$action));
					 $count = $this->db->num_rows($result);
$task_array = array();
if($count==1)
{
$row = $this->db->fetch_array($result);
$task = $row['task'];
$task = preg_match('#\[(.*?)\]#', $task, $match);
$taskDetails = $match[1];
array_push($task_array,array('tasklist'=>$taskDetails));
}
return $task_array;
}

public function getPrimaryKey($tablename)
{
$sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ? AND COLUMN_KEY = ?";
$result = $this->db->pquery($sql,array($tablename,'edcon_dev','PRI'));
$count = $this->db->num_rows($result);
$row = $this->db->fetch_array($result);
$primary_key = $row['COLUMN_NAME'];
return $primary_key;
}

function getBranchCode($id)
	{
	  $sql = "SELECT arocrm_plantmaster.*,arocrm_plantmastercf.*,arocrm_crmentity.* FROM arocrm_plantmaster
	           INNER JOIN arocrm_plantmastercf ON arocrm_plantmastercf.plantmasterid=arocrm_plantmaster.plantmasterid
	           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
			   WHERE arocrm_plantmaster.plantmasterid=? AND arocrm_crmentity.deleted=?";
			   $sql_result = $this->db->pquery($sql,array($id,0));
			   $sql_count = $this->db->num_rows($sql_result);
			   if($sql_count==1)
			   {
			     $sql_row = $this->db->fetch_array($sql_result);
				 $shortcode = $sql_row['cf_4929'];
				 return $shortcode;
			   }
	}


	/**
	 * Function to get the record model based on the request parameters
	 * @param arocrm_Request $request
	 * @return arocrm_Record_Model or Module specific Record Model instance
	 */


	protected function getRecordModelFromRequest(arocrm_Request $request) {

		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$moduleModel = arocrm_Module_Model::getInstance($moduleName);

		if(!empty($recordId)) {
			$recordModel = arocrm_Record_Model::getInstanceById($recordId, $moduleName);
			$recordModel->set('id', $recordId);
			$recordModel->set('mode', 'edit');
		} else {
			$recordModel = arocrm_Record_Model::getCleanInstance($moduleName);
			$recordModel->set('mode', '');
		}

		$fieldModelList = $moduleModel->getFields();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $request->get($fieldName, null);
			$fieldDataType = $fieldModel->getFieldDataType();
			if($fieldDataType == 'time'){
				$fieldValue = arocrm_Time_UIType::getTimeValueWithSeconds($fieldValue);
			}
			if($fieldValue !== null) {
				if(!is_array($fieldValue) && $fieldDataType != 'currency') {
					$fieldValue = trim($fieldValue);
				}
				$recordModel->set($fieldName, $fieldValue);
			}
		}
		return $recordModel;
	}
}
// JavaScript Document
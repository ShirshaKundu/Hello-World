<?php
include "config.inc.php";
session_start();
$username = $dbconfig['db_username'];
$password = $dbconfig['db_password'];
$hostname = $dbconfig['db_server'];
$database = $dbconfig['db_name'];

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password)
 or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db($database,$dbhandle)
or die("Could not select ".$database."");

if(isset($_GET['action']) && function_exists($_GET['action'])) {
$action = $_GET['action'];
if($action=='importserialnostockupload')
	{
    $module = isset($_GET['module']) ? $_GET['module'] : null;
    $stockupid = isset($_GET['stockupid']) ? $_GET['stockupid'] : null;
    $getData = $action($module,$stockupid);
    $response['message'] = $getData['message'];
	header("location:index.php?module=StockUpload&parent=&page=1&view=List&viewname=138&orderby=&sortorder=&app=TRANSACTION&search_params=%5B%5D&tag_params=%5B%5D&nolistcache=0&list_headers=%5B%22name%22%2C%22assigned_user_id%22%2C%22createdtime%22%2C%22description%22%5D&tag=");	
    echo json_encode($response);
	}
	
	
if($action=='checkupStocktable'){
$getData = $action();	
}	

}


if(isset($_POST['action']) && function_exists($_POST['action'])) {
$response = array();
$action = $_POST['action'];

    if($action=='get_stockmaster')
	{
    $recordid = isset($_POST['recordid']) ? $_POST['recordid'] : null;
    $getData = $action($recordid);
	  $response['message'] = $getData['message'];
    echo json_encode($response);
	}
	
	
  if($action == 'getCustomerLedgerReport')
	{
		$date = isset($_POST['date']) ? $_POST['date'] : null;
		$plant = isset($_POST['plant']) ? $_POST['plant'] : null;
		$getData = $action($date,$plant);
		$response['customerledgerreporthtml'] = $getData['customerledgerreporthtml'];
		echo json_encode($response);
	}
	
  if($action=='getPopulatedSerialNumberforStoretoStore'){
    $nos = isset($_POST['nos']) ? $_POST['nos'] : null;
    $getData = $action($nos);
    $response['message'] = $getData['message'];
    $response['count'] = $getData['count'];
    echo json_encode($response);
  }

  if($action=='getLineItemsforSalesPlan'){
	$postingdate = isset($_POST['postingdate']) ? $_POST['postingdate'] : null;
	$branchid = isset($_POST['branchid']) ? $_POST['branchid'] : null;
    $getData = $action($postingdate, $branchid);
    $response['fourw'] = $getData['fourw'];
    $response['fourwcount'] = $getData['fourwcount'];
    $response['twow'] = $getData['twow'];
    $response['twowcount'] = $getData['twowcount'];
    $response['ibw'] = $getData['ibw'];
    $response['ibwcount'] = $getData['ibwcount'];
    $response['erw'] = $getData['erw'];
    $response['erwcount'] = $getData['erwcount'];
    echo json_encode($response);
  }

  if($action=='loadAllStockReq'){
    $plantid = isset($_POST['plantid']) ? $_POST['plantid'] : null;
    $getData = $action($plantid);
    $response['message'] = $getData['message'];
    echo json_encode($response);
  }

  if($action=='getallsalesbudgetqty'){
    $years = isset($_POST['years']) ? $_POST['years'] : null;
    $month = isset($_POST['month']) ? $_POST['month'] : null;
    $plantid = isset($_POST['plantid']) ? $_POST['plantid'] : null;
	$assignedto = isset($_POST['assignedto']) ? $_POST['assignedto'] : null;
    $getData = $action($years,$month,$plantid,$assignedto);

    $response['fourwqtyone'] = $getData['fourwqtyone'];
    $response['twowqtyone'] = $getData['twowqtyone'];
    $response['ibqtyone'] = $getData['ibqtyone'];
    $response['erqtyone'] = $getData['erqtyone'];
	
    $response['fourwqtytwo'] = $getData['fourwqtytwo'];
    $response['twowqtytwo'] = $getData['twowqtytwo'];
    $response['ibqtytwo'] = $getData['ibqtytwo'];
    $response['erqtytwo'] = $getData['erqtytwo'];

    $response['fourwqtythree'] = $getData['fourwqtythree'];
    $response['twowqtythree'] = $getData['twowqtythree'];
    $response['ibqtythree'] = $getData['ibqtythree'];
    $response['erqtythree'] = $getData['erqtythree'];

    echo json_encode($response);
  }



	if($action=='getApproverList')
	{
  $getData = $action();
  $response['message'] = $getData['message'];
  echo json_encode($response);
	}
	if($action=='getApprovalEmailTemplate')
	{
    $getData = $action();
	  $response['message'] = $getData['message'];
    echo json_encode($response);
	}

	if($action=='getSelectedPlant')
	{
    $plantid = isset($_POST['plantid']) ? $_POST['plantid'] : null;
    $getData = $action($plantid);
    $response['message'] = $getData['message'];
    echo json_encode($response);
	}

	if($action=='getPopulatedSerialNumber')
	{
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$products = isset($_POST['products']) ? $_POST['products'] : null;
	$plant = isset($_POST['plant']) ? $_POST['plant'] : null;
	$storageloc = isset($_POST['storageloc']) ? $_POST['storageloc'] : null;
	$serialnos = isset($_POST['serialnos']) ? $_POST['serialnos'] : null;
	$totalno = isset($_POST['totalno']) ? $_POST['totalno'] : null;

    $getData = $action($id,$products,$plant,$storageloc,$serialnos,$totalno);
	$response['message'] = $getData['message'];
    echo json_encode($response);
	}

	if($action=='getSOLineItemforOBD')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
    $response['savestatestatus'] = $getData['savestatestatus'];
    $response['srvresponse'] = $getData['srvresponse'];
    $response['rowcount'] = $getData['rowcount'];
    $response['message'] = $getData['message'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['customerid'] = $getData['customerid'];
	$response['customername'] = $getData['customername'];
    echo json_encode($response);
	}



  if($action=='getPOLineItemforOBDWSTPO'){
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
    $response['srvresponse'] = $getData['srvresponse'];
    $response['rowcount'] = $getData['rowcount'];
    $response['message'] = $getData['message'];
    $response['delplantid'] = $getData['delplantid'];
    $response['delplantname'] = $getData['delplantname'];
	$response['delfromplantid'] = $getData['delfromplantid'];
  $response['delfromplantname'] = $getData['delfromplantname'];
    echo json_encode($response);
  }

	if($action=='getStockRequisition')
	{
    $plantid = isset($_POST['plantid']) ? $_POST['plantid'] : null;
	$referenceid = isset($_POST['referenceid']) ? $_POST['referenceid'] : null;
    $getData = $action($plantid,$referenceid);
	$response['message'] = $getData['message'];
	$response['rowcount'] = $getData['rowcount'];
    echo json_encode($response);
	}

	if($action=='get_stockmaster_add')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
	$response['message'] = $getData['message'];
    echo json_encode($response);
	}

	if($action=='getRfqLineItem')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
	$response['message'] = $getData['message'];
	$response['requisition_date'] = $getData['requisition_date'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['rowcount'] = $getData['rowcount'];
    echo json_encode($response);
	}

	if($action=='getPurchaseReqLineitem')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
	$plant = isset($_POST['plant']) ? $_POST['plant'] : null;
    $getData = $action($id,$plant);
	$response['message'] = $getData['message'];
	$response['rowcount'] = $getData['rowcount'];
    echo json_encode($response);
	}

	if($action=='getProductCodeUnit'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$vendorid = isset($_POST['vendorid']) ? $_POST['vendorid'] : null;
    $plantid = isset($_POST['plantid']) ? $_POST['plantid'] : null;
    $currid = isset($_POST['currencyid']) ? $_POST['currencyid'] : null;
    $getData = $action($id,$vendorid,$plantid,$currid);
	$response['productcode'] = $getData['productcode'];
	$response['unit'] = $getData['unit'];
	$response['warranty'] = $getData['warranty'];
    $response['listprice'] = $getData['listprice'];
    $response['listinrprice'] = $getData['listinrprice'];
    $response['category'] = $getData['category'];
    $response['ah'] = $getData['ah'];
	echo json_encode($response);
	}

	if($action=='getPopulatedTextBoxSerialNumber'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$totalno = isset($_POST['totalno']) ? $_POST['totalno'] : null;
	$serialnos = isset($_POST['serialnos']) ? $_POST['serialnos'] : null;
    $getData = $action($id,$totalno,$serialnos);
	$response['message'] = $getData['message'];
	echo json_encode($response);
	}

if($action=='getOBDItemforQI'){
$id = isset($_POST['id']) ? $_POST['id'] : null;
$getData = $action($id);
$response['vendorname'] = $getData['vendorname'];
$response['vendorcode'] = $getData['vendorcode'];
$response['plantname'] = $getData['plantname'];
$response['plantcode'] = $getData['plantcode'];
$response['vendorid'] = $getData['vendorid'];
$response['plantid'] = $getData['plantid'];
$response['soid'] = $getData['soid'];
$response['soname'] = $getData['soname'];
$response['customerid'] = $getData['customerid'];
$response['customername'] = $getData['customername'];

echo json_encode($response);
}

	if($action=='getIBDItemforQI'){
$id = isset($_POST['id']) ? $_POST['id'] : null;
$getData = $action($id);
$response['vendorid'] = $getData['vendorid'];
$response['ibdno'] = $getData['ibdno'];
$response['vendorname'] = $getData['vendorname'];
$response['vendorcode'] = $getData['vendorcode'];
$response['plantname'] = $getData['plantname'];
$response['plantcode'] = $getData['plantcode'];
$response['vendorid'] = $getData['vendorid'];
$response['plantid'] = $getData['plantid'];
echo json_encode($response);
	}


  if($action=='getDetailsOBDforGI'){
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
    $response['message'] = $getData['message'];
    $response['vehicleno'] = $getData['vehicleno'];
    $response['rowcount'] = $getData['rowcount'];
    $response['plantid'] = $getData['plantid'];
    $response['plantname'] = $getData['plantname'];
    $response['modeoftransfer'] = $getData['modeoftransfer'];
    $response['curdate'] = date('d-m-Y');
    $response['obddate'] = $getData['obddate'];
    $response['custid'] = $getData['custid'];
    $response['custname'] = $getData['custname'];
    $response['savestatestatus'] = $getData['savestatestatus'];
    echo json_encode($response);
  }

  if($action=='getDetailsforPOwrtSO'){
    $id = isset($_POST['id']) ? $_POST['id'] : null;
  	$getData = $action($id);
  	$response['message'] = $getData['message'];
  	$response['invoice'] = $getData['invoice'];
    $response['totalcount'] = $getData['totalcount'];
    $response['amount'] = $getData['amount'];
    echo json_encode($response);
  }

	if($action=='getDetailsIBDforGR'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($id);
	$response['message'] = $getData['message'];
	$response['poid'] = $getData['poid'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['poname'] = $getData['poname'];
	$response['vendorid'] = $getData['vendorid'];
	$response['vendorname'] = $getData['vendorname'];
	$response['rowcount'] = $getData['rowcount'];
	$response['vehicleno'] = $getData['vehicleno'];
	$response['modeoftransfer'] = $getData['modeoftransfer'];
	
	$response['invoiceno'] = $getData['invoiceno'];
	$response['waybillno'] = $getData['waybillno'];
	$response['invoicedate'] = $getData['invoicedate'];
	$response['awbno'] = $getData['awbno'];
	$response['billofentry'] = $getData['billofentry'];
	$response['cnnumber'] =  $getData['cnnumber'];


	echo json_encode($response);
	}


if($action=='getDetailsPOforGR'){
  $id = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($id);
	$response['message'] = $getData['message'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['vendorid'] = $getData['vendorid'];
	$response['vendorname'] = $getData['vendorname'];
	$response['rowcount'] = $getData['rowcount'];

	echo json_encode($response);
}

if($action=='getINVDetailsforPReturn'){
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $getData = $action($id);
  $response['plantid'] = $getData['plantid'];
  $response['plantname'] = $getData['plantname'];
  $response['vendorid'] = $getData['vendorid'];
	$response['vendorname'] = $getData['vendorname'];
  $response['delvid'] = $getData['delvid'];
  $response['delvname'] = $getData['delvname'];
  $response['grid'] = $getData['grid'];
  $response['grname'] = $getData['grname'];
    $response['poid'] = $getData['poid'];
  $response['poname'] = $getData['poname'];
 $response['receiptdate'] = $getData['receiptdate'];
  $response['message'] = $getData['message'];
  $response['rowcount'] = $getData['rowcount'];
  echo json_encode($response);
}

if($action=='getRPODetailsforOBD'){
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $getData = $action($id);
  $response['plantid'] = $getData['plantid'];
  $response['plantname'] = $getData['plantname'];
  $response['vendorid'] = $getData['vendorid'];
	$response['vendorname'] = $getData['vendorname'];
  $response['invdate'] = $getData['invdate'];
  $response['invno'] =  $getData['invno'];
  $response['message'] = $getData['message'];
  $response['rowcount'] = $getData['rowcount'];
  echo json_encode($response);
}

	if($action=='getIBDLineItemforQI'){
      $ibdno = isset($_POST['ibdno']) ? $_POST['ibdno'] : null;
      $nos = isset($_POST['nos']) ? $_POST['nos'] : null;
      $productid = isset($_POST['productid']) ? $_POST['productid'] : null;
      $getData = $action($ibdno,$nos,$productid);
      $response['message'] = $getData['message'];
      $response['rowcount'] = $getData['rowcount'];
      echo json_encode($response);
	}

  if($action=='getOBDLineItemforQI'){
      $obdno = isset($_POST['obdno']) ? $_POST['obdno'] : null;
      $nos = isset($_POST['nos']) ? $_POST['nos'] : null;
      $productid = isset($_POST['productid']) ? $_POST['productid'] : null;
      $getData = $action($obdno,$nos,$productid);
      $response['message'] = $getData['message'];
      $response['rowcount'] = $getData['rowcount'];
      echo json_encode($response);
  }


	if($action=='getProductCodeforQI'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$ibdno = isset($_POST['ibdno']) ? $_POST['ibdno'] : null;
	$getData = $action($id,$ibdno);
    $response['productcode'] = $getData['productcode'];
	 $response['maxrowcount'] = $getData['maxrowcount'];
	  $response['message'] = $getData['message'];
     $response['rowcount'] = $getData['rowcount'];
	 echo json_encode($response);
	}
	
	if($action=='getInvoiceLineitemfromSTPO'){
		$idtype = isset($_POST['idtype']) ? $_POST['idtype'] : null;
		$pono = isset($_POST['pono']) ? $_POST['pono'] : null;
		$getData = $action($idtype,$pono);
		$response['message'] = $getData['message'];
		$response['totalamount'] =$getData['totalamount'];
		$response['rowcount'] =$getData['rowcount'];
		echo json_encode($response);
	}

	if($action=='getLineItemforQIWOBD'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$obdno = isset($_POST['obdno']) ? $_POST['obdno'] : null;
	$getData = $action($id,$obdno);
    $response['productcode'] = $getData['productcode'];
	 $response['maxrowcount'] = $getData['maxrowcount'];
	  $response['message'] = $getData['message'];
     $response['rowcount'] = $getData['rowcount'];
	 echo json_encode($response);
	}

	if($action=='getPRLineItemforPO')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
   	$vendor_id = isset($_POST['vendorid']) ? $_POST['vendorid'] : null;
    $month = isset($_POST['month']) ? $_POST['month'] : null;
    $getData = $action($id,$vendor_id,$month);
	$response['message'] = $getData['message'];
	$response['req_no'] = $getData['req_no'];
	$response['requisition_date'] = $getData['requisition_date'];
	$response['totalcount'] = $getData['totalcount'];
	$response['amount'] = $getData['amount'];
	$response['status'] = $getData['status'];
    echo json_encode($response);
	}

	if($action=='getPOLineItemforIBD'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($id);
	$response['message'] = $getData['message'];
	$response['podate'] = $getData['podate'];
	$response['pono'] = $getData['pono'];
	$response['reference'] = $getData['reference'];
	$response['plantid'] = $getData['plantid'];
	$response['plantcode'] = $getData['plantcode'];
	$response['plantname'] = $getData['plantname'];
	$response['vendorcode'] = $getData['vendorcode'];
  $response['vendorid'] = $getData['vendorid'];
	$response['vendorname'] = $getData['vendorname'];
	$response['rowcount'] = $getData['rowcount'];
    echo json_encode($response);
	}

  if($action=='getSOReturnDetailsforIBD'){
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
    $response['custid'] = $getData['custid'];
    $response['custname'] = $getData['custname'];
	$response['plantid'] = $getData['plantid'];
    $response['plantname'] = $getData['plantname'];
    $response['rowcount'] = $getData['rowcount'];
    $response['message'] = $getData['message'];
    $response['serialnos'] = $getData['serialnos'];
	$response['invoiceno'] = $getData['invoiceno'];
    $response['invoicedate'] = $getData['invoicedate'];
    echo json_encode($response);
  }

  if($action=='getPlantCodeforPlantID'){
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
    $response['plantcode'] = $getData['plantcode'];
    echo json_encode($response);
  }

  if($action=='getSTPOLineItemforIBD'){
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($id);
	$response['message'] = $getData['message'];
	$response['podate'] = $getData['podate'];
	$response['pono'] = $getData['pono'];
	$response['reference'] = $getData['reference'];
	$response['plantid'] = $getData['plantid'];
	$response['plantcode'] = $getData['plantcode'];
	$response['plantname'] = $getData['plantname'];
	$response['vendorcode'] = $getData['vendorcode'];
	$response['vendorname'] = $getData['vendorname'];
	$response['vendorid'] = $getData['vendorid'];
	$response['rowcount'] = $getData['rowcount'];
    echo json_encode($response);
	}

if($action=='getServiceCodeUnit'){
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $getData = $action($id);
$response['service_no'] = $getData['service_no'];
$response['service_usageunit'] = $getData['service_usageunit'];
  echo json_encode($response);
}

	if($action=='getSTPRLineItemforPO')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
	$response['message'] = $getData['message'];
	$response['str_no'] = $getData['str_no'];
	$response['str_date'] = $getData['str_date'];
	$response['plant_display_name'] = $getData['plant_display_name'];
	$response['plant_id'] = $getData['plant_id'];
	$response['from_plant_display_name'] = $getData['from_plant_display_name'];
	$response['from_plant_id'] = $getData['from_plant_id'];
	$response['totalcount'] = $getData['totalcount'];
	$response['amount'] = $getData['amount'];

    echo json_encode($response);
	}

	if($action=='getSTRLineItemforPO')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id,$vendor_id);
	$response['message'] = $getData['message'];
	$response['str_date'] = $getData['str_date'];
    echo json_encode($response);
	}
}

if($action=='getSODetailsforSOReturn')
{
  $id = isset($_POST['id']) ? $_POST['id'] : null;
  $getData = $action($id);
  $response['assignedto'] = $getData['assignedto'];
  $response['custid'] = $getData['custid'];
  $response['custname'] = $getData['custname'];
  $response['soid'] = $getData['soid'];
  $response['soname'] = $getData['soname'];
  $response['plantid'] = $getData['plantid'];
  $response['plantname'] = $getData['plantname'];
  $response['grandtotal'] = $getData['grandtotal'];
  $response['rowcount'] = $getData['rowcount'];
  $response['message'] = $getData['message'];
  $response['msg'] = $getData['msg'];
  echo json_encode($response);
}


  if($action=='loadSchemeMasterWindow'){
	$id = isset($_POST['recordid']) ? $_POST['recordid'] : null;
    $getData = $action($id);
    $response['productcategory'] = $getData['productcategory'];
	
	$response['sselectedval'] = $getData['sselectedval'];
    $response['productsubcategory'] = $getData['productsubcategory'];
    echo json_encode($response);
  }

  if($action=='getSchemeMasterDetailsRev'){
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $getData = $action($id);
    $response['name'] = $getData['name'];
    $response['startdate'] = $getData['startdate'];
    $response['enddate'] = $getData['enddate'];
    $response['schemetype'] = $getData['schemetype'];
    $response['schemefor'] = $getData['schemefor'];
    $response['productschemerowcount'] = $getData['productschemerowcount'];
    $response['productschemecounthtml'] = $getData['productschemecounthtml'];
    $response['productschemecount'] = $getData['productschemecount'];
    $response['productsubrowcount'] = $getData['productsubrowcount'];
    $response['productschemecounthtml'] = $getData['productschemecounthtml'];
    $response['productschemecount'] = $getData['productschemecount'];
    $response['prorowcount'] = $getData['prorowcount'];
    $response['procounthtml'] = $getData['procounthtml'];
    $response['procount'] = $getData['procount'];
    $response['giftrowcount'] = $getData['giftrowcount'];
    $response['giftcounthtml'] = $getData['giftcounthtml'];
    $response['giftcount'] = $getData['giftcount'];

    echo json_encode($response);
  }

function checkupStocktable(){
$sqldata = mysql_query("SELECT * FROM `arocrm_dailystock_details`");
while($rew = mysql_fetch_array($sqldata)){
$stock = $rew['serialnumbers'];	
$qty = $rew['debit_quantity'];
$ts = explode(",",$stock);
$vart = count($ts);
if($vart!=$qty){
	echo "Incorrrect data ---> ".$rew['id']."   ---> Actual Stock ".$vart."  ---> Given -> ".$qty."<br/>";
}

	
}
 }


 
 
 
function getCustomerLedgerReport($date,$plant)
{
	$response = array();
	$dates = explode(' - ',$date);
	$fromdate = $dates[0];
	$from = explode('/',$fromdate);
	$fdate = $from[2].'-'.$from[0].'-'.$from[1];
	$todate = $dates[1];
	$to = explode('/',$todate);
	$tdate = $to[2].'-'.$to[0].'-'.$to[1];
	$allplant = implode(',',$plant);
	
	$html .= '<tr style="border: 2px solid black !important;">
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Invoice Type</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Invoice Date</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Invoice Subject</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Invoice Number</th>
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Customer Name</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Branch</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Product Category</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Sales Order</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Posting Date</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Line Item Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Line Item Discount Amount</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Subtotal</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Monthly Target Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Monthly Target Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Quarterly Target Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Quarterly Target Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Halfyearly Target Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Halfyearly Target Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Annually Target Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Annually Target Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Adv. Payment Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Adv. Payment Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Sameday Payment Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Sameday Payment Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Sameday Payment Cash Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall Sameday Payment Cash Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall 7 Days Payment Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall 7 Days Payment Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall 15 Days Payment Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall 15 Days Payment Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall 30 Days Payment Discount Percent</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Overall 30 Days Payment Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Scheme Discount Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Pre Tax Total</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">IGST</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">IGST Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">SGST</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">SGST Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">CGST</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">CGST Value</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Total Tax</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Post Tax Total</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Adjustment</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Grand Total</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Balance</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Received</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Due</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Payment Mode</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Payment Details</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Invoice Status</th>
							
	</tr>';
	
	$sql = "SELECT `arocrm_customerpayment_payment_details_lineitem`.*,`arocrm_invoice`.*,`arocrm_invoicecf`.* FROM `arocrm_invoice` 
	INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_invoice`.`invoiceid` 
	INNER JOIN `arocrm_invoicecf` ON `arocrm_invoicecf`.`invoiceid` = `arocrm_invoice`.`invoiceid` 
	LEFT JOIN `arocrm_customerpayment_payment_details_lineitem` ON `arocrm_customerpayment_payment_details_lineitem`.`cf_3346` =  `arocrm_invoice`.`invoiceid` AND `arocrm_customerpayment_payment_details_lineitem`.cf_3356 > 0 AND `arocrm_customerpayment_payment_details_lineitem`.`customerpaymentid` IN (SELECT `arocrm_customerpayment`.`customerpaymentid` FROM `arocrm_customerpayment` INNER JOIN  `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_customerpayment`.`customerpaymentid` INNER JOIN `arocrm_customerpaymentcf` ON `arocrm_customerpaymentcf`.`customerpaymentid` = `arocrm_customerpayment`.`customerpaymentid` WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_customerpaymentcf`.`cf_3376` = 'Approved')
	WHERE `arocrm_invoice`.`cf_nrl_plantmaster164_id` IN (".$allplant.") 
	AND `arocrm_crmentity`.`deleted` = 0 
	AND `arocrm_invoice`.`invoicedate` BETWEEN '".$fdate."' AND '".$tdate."'
	ORDER BY  `arocrm_invoice`.`invoiceid` DESC";
	$invsql = mysql_query($sql);
	while($invrow = mysql_fetch_array($invsql))
	{
	
	$dattmp = explode("-",$invrow['invoicedate']);
	$posttmp = explode("-",$invrow['cf_4627']);
	$invoice = $invrow['invoiceid'];
	$linedisper = 0;
	$linedisamount = 0;
	$igst = 0;
	$cgst = 0;
	$sgst = 0;
	
	$invsqldata = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_inventoryproductrel`.`id`  WHERE `arocrm_inventoryproductrel`.`id` = '".$invoice."' AND `arocrm_crmentity`.`deleted` = 0");
	$preamtsum = 0;
	while($dislrow = mysql_fetch_array($invsqldata)){
	$linedisper = $dislrow['discount_percent'];	
	$qty = $dislrow['quantity'];
    $listprice = $dislrow['listprice'];	
	$amount = (float)$qty * (float)$listprice;
	$preamtsum = $preamtsum + $amount;
	$igst = $dislrow['tax1'];
	$cgst = $dislrow['tax2'];
	$sgst = $dislrow['tax3'];
	}
	
	
	$igstval = ($igst/100) * $invrow['pre_tax_total'];
	$cgstval = ($cgst/100) * $invrow['pre_tax_total'];
	$sgstval = ($sgst/100) * $invrow['pre_tax_total'];
	
	$totaltax = $igstval + $cgstval + $sgstval;
	$posttax = $totaltax + $invrow['pre_tax_total'];
	
	if($linedisper > 0){
	$linedisamount = ($linedisper / 100) * $preamtsum;
	}else{
	$linedisper = 0;
	$linedisamount = 0;	
	}
	
	$html .= '<tr style="border: 1px solid black;" class="drilldown" id="">
		<th nowrap>'.$invrow['cf_3288'].'</th>
		<th wrap>'.$dattmp[2].'/'.$dattmp[1].'/'.$dattmp[0].'</th>
		<th wrap>'.$invrow['subject'].'</th>
		<th wrap>'.$invrow['invoice_no'].'</th>
		<th nowrap>'.getCustomerName($invrow['accountid']).'</th>
		<th nowrap>'.getPlantName($invrow['cf_nrl_plantmaster164_id']).'</th>
		<th wrap>'.$invrow['cf_4766'].'</th>
		<th wrap>'.getSalesOrder($invrow['salesorderid']).'</th>
		<th wrap>'.$posttmp[2].'/'.$posttmp[1].'/'.$posttmp[0].'</th>
		<th wrap>'.$linedisper.'%</th>
		<th wrap>'.$linedisamount.'</th>
		<th wrap>'.$invrow['subtotal'].'</th>
		
		
		<th wrap>'.$invrow['overallmonthlytargetpercent'].'</th>
		<th wrap>'.$invrow['overallmonthlytargetpercentval	'].'</th>
		<th wrap>'.$invrow['overallquarterlytargetpercent'].'</th>
		<th wrap>'.$invrow['overallquarterlytargetpercentval'].'</th>
		<th wrap>'.$invrow['overallhalfyearlytargetpercent'].'</th>
		<th wrap>'.$invrow['overallhalfyearlytargetpercentval'].'</th>
		<th wrap>'.$invrow['overallannuallytargetpercent'].'</th>
		<th wrap>'.$invrow['overallannuallytargetpercentval'].'</th>
		<th wrap>'.$invrow['overalladvancepercent'].'</th>
		<th wrap>'.$invrow['overalladvancepercentval'].'</th>
		<th wrap>'.$invrow['overallsamedaypercent'].'</th>
		<th wrap>'.$invrow['overallsamedaypercentval'].'</th>
		<th wrap>'.$invrow['overallsamedaycashpercent'].'</th>
		<th wrap>'.$invrow['overallsamedaycashpercentval'].'</th>
		<th wrap>'.$invrow['overall7dayspercent'].'</th>
		<th wrap>'.$invrow['overall7dayspercentval'].'</th>
		<th wrap>'.$invrow['overall15dayspercent'].'</th>
		<th wrap>'.$invrow['overall15dayspercentval'].'</th>
		<th wrap>'.$invrow['overall30dayspercent'].'</th>
		<th wrap>'.$invrow['overall30dayspercentval'].'</th>
		<th wrap>'.$invrow['schemediscount'].'</th>
		<th wrap>'.$invrow['pre_tax_total'].'</th>
		<th wrap>'.$igst.'</th>
		<th wrap>'.$igstval.'</th>
		<th wrap>'.$sgst.'</th>
		<th wrap>'.$sgstval.'</th>
		<th wrap>'.$cgst.'</th>
		<th wrap>'.$cgstval.'</th>
		<th wrap>'.$totaltax.'</th>
		<th wrap>'.$posttax.'</th>
		<th wrap>'.$invrow['adjustment'].'</th>
		<th wrap>'.$invrow['total'].'</th>
		
		<th wrap>'.$invrow['cf_3354'].'</th>
		<th wrap>'.$invrow['cf_3356'].'</th>
		<th wrap>'.$invrow['cf_3358'].'</th>
		<th wrap>'.$invrow['cf_3360'].'</th>
		<th wrap>'.$invrow['cf_3362'].'</th>

		<th wrap>'.$invrow['invoicestatus'].'</th>
		</tr>';	
	
	
		
		
	}
	$response['customerledgerreporthtml'] = $html;
	return $response;
}


function getSalesOrder($id){
$customerrow = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_salesorder` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_salesorder`.`salesorderid` WHERE `arocrm_salesorder`.`salesorderid` = '".$id."' AND `arocrm_crmentity`.`deleted` = 0"));
return $customerrow['subject'];	
}

function getCustomerName($id){
$customerrow = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_account` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_account`.`accountid` WHERE `arocrm_account`.`accountid` = '".$id."' AND `arocrm_crmentity`.`deleted` = 0"));
return $customerrow['accountname'];	
}

function getPlantName($id){
$plantrow = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_plantmaster` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_plantmaster`.`plantmasterid` WHERE `arocrm_plantmaster`.`plantmasterid` = '".$id."' AND `arocrm_crmentity`.`deleted` = 0"));
return $plantrow['name'];	
} 


function getInvoiceLineitemfromSTPO($idtype,$pono){
$response = array();
$i=1;
$html = '';
$invsql = mysql_query("SELECT * FROM `arocrm_purchaseorder` INNER JOIN `arocrm_purchaseordercf` ON `arocrm_purchaseordercf`.`purchaseorderid` = `arocrm_purchaseorder`.`purchaseorderid` INNER JOIN `arocrm_inventoryproductrel` ON `arocrm_inventoryproductrel`.`id` = `arocrm_purchaseorder`.`purchaseorderid` WHERE `arocrm_inventoryproductrel`.`productid` IN (SELECT `productid` FROM `arocrm_products` WHERE `productcategory` = '".$idtype."') AND `arocrm_purchaseorder`.`purchaseorderid` = '".$pono."'");


$html .='<tr><td><strong>TOOLS</strong></td><td><span class="redColor">*</span><strong>Item Name</strong></td><td><strong class="pull-right" style="float:left!important;">Item Code</strong></td><td><strong class="pull-right" style="float:left!important;">Unit</strong></td><td><strong>Quantity</strong></td><td><strong class="pull-right" style="float:left!important;">Serial Number</strong></td><td><strong>Selling Price</strong></td><td><strong class="pull-right">Total</strong></td><td><strong class="pull-right">Net Price</strong></td></tr>';
$rwnm = 1;
$sumamount = 0;
while($row = mysql_fetch_array($invsql)){
	$proid = $row['productid'];
	$inv = mysql_query("SELECT arocrm_goodsissue.*, arocrm_goodsissuecf.*, arocrm_goodsissue_line_item_lineitem.* FROM arocrm_goodsissue INNER JOIN arocrm_goodsissuecf ON arocrm_goodsissuecf.goodsissueid = arocrm_goodsissue.goodsissueid INNER JOIN arocrm_goodsissue_line_item_lineitem ON arocrm_goodsissue_line_item_lineitem.goodsissueid = arocrm_goodsissue.goodsissueid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsissue.goodsissueid WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsissue_line_item_lineitem.cf_3163 = '".$proid."' AND arocrm_goodsissue.cf_nrl_outbounddelivery617_id = (SELECT arocrm_outbounddelivery.outbounddeliveryid FROM arocrm_outbounddelivery INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_outbounddelivery.outbounddeliveryid WHERE arocrm_crmentity.deleted = 0 AND arocrm_outbounddelivery.cf_nrl_purchaseorder165_id = '".$pono."')");
$prorow = mysql_fetch_array($inv);
$serial = $prorow['cf_3179'];
$serialno = explode(',',$serial);
$serialsno = '';
$j = 1;
foreach($serialno as $serials)
{
if($j==1){
$serialsno .= "'".$serials."'";
}else{
$serialsno .= ",'".$serials."'";	
}
$j++;
}
	
	$sqlso = mysql_query("SELECT COUNT(`arocrm_serialnumbercf`.`cf_1264`) as count,arocrm_serialnumbercf.cf_1264,arocrm_serialnumber.cf_nrl_products16_id as product FROM arocrm_serialnumber INNER JOIN arocrm_serialnumbercf ON arocrm_serialnumbercf.serialnumberid = arocrm_serialnumber.serialnumberid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_serialnumber.serialnumberid WHERE arocrm_crmentity.deleted = 0 AND arocrm_serialnumber.cf_nrl_products16_id = '".$proid."' AND arocrm_serialnumbercf.cf_1258 IN (".$serialsno.") GROUP BY arocrm_serialnumbercf.cf_1264");
	
	while($rwsc = mysql_fetch_array($sqlso))
	{

$product = $rwsc['product'];
$proarr = getProductDetails($product);
$productcode = $proarr['productcode'];
$productname = $proarr['productname'];
$productunit = $proarr['unit'];
$qty = $rwsc['count'];
$cost = $rwsc['cf_1264'];
$totalcost = (float)$qty * (float)$cost;
$sumamount = (float)$sumamount + (float)$totalcost;

$serl = '';
$rds = "SELECT `arocrm_serialnumber`.* FROM `arocrm_serialnumber` INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`serialnumberid` = `arocrm_serialnumber`.`serialnumberid` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_serialnumber`.`serialnumberid`
WHERE `arocrm_crmentity`.`deleted` = 0 AND arocrm_serialnumbercf.cf_1258 IN (".$serialsno.") AND arocrm_serialnumbercf.cf_1264 = '".$cost."' AND arocrm_serialnumber.cf_nrl_products16_id = '".$product."'";
$seqsql = mysql_query($rds);
$h = 1;
while($serww = mysql_fetch_array($seqsql)){
if($h==1){
$serl .= $serww['name'];		
}else{
$serl .= ','.$serww['name'];		
}

$h++;
}
$html .=  '<tr id="row'.$rwnm.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$rwnm.'">
<td style="text-align:center;">
<i class="fa fa-trash deleteRow cursorPointer" title="Delete" style="display: none;"></i>&nbsp;
<a><img src="layouts/v7/skins/images/drag.png" border="0" title="Drag"></a>
<input type="hidden" class="rowNumber" value="'.$rwnm.'">
</td>

<td>
<input type="hidden" name="hidtax_row_no'.$rwnm.'" id="hidtax_row_no'.$rwnm.'" value="">
<div class="itemNameDiv form-inline">
<div class="row">
<div class="col-lg-10">
<div class="input-group" style="width:100%">
<input type="text" id="productName'.$rwnm.'" name="productName'.$rwnm.'" value="'.$productname.'" class="productName form-control  crmtabMod"  data-rule-required="true" aria-required="true" readonly>
<input type="hidden" id="hdnProductId'.$rwnm.'" name="hdnProductId'.$rwnm.'" value="'.$product.'" class="selectedModuleId">
<input type="hidden" id="lineItemType'.$rwnm.'" name="lineItemType'.$rwnm.'" value="Products" class="lineItemType">
</div>
</div>
</div>
</div>
<input type="hidden" value="" id="subproduct_ids'.$rwnm.'" name="subproduct_ids'.$rwnm.'" class="subProductIds">
<div id="subprod_names'.$rwnm.'" name="subprod_names'.$rwnm.'" class="subInformation">
</div>
<div>
<br>
<textarea id="comment'.$rwnm.'" name="comment'.$rwnm.'" class="lineItemCommentBox"></textarea>
</div>
</td>

<td>
<input id="productcode'.$rwnm.'" name="productcode'.$rwnm.'" type="text" class="productcode inputElement" readonly="readonly" value="'.$productcode.'">
</td>

<td>
<input id="itemunit'.$rwnm.'" name="itemunit'.$rwnm.'" type="text" class="itemunit inputElement" readonly="readonly" value="'.$productunit.'">
</td>

<td>
<input id="qty'.$rwnm.'" name="qty'.$rwnm.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" data-rule-positive="true" data-rule-greater_than_zero="true" value="'.$qty.'" readonly aria-required="true">

<input id="purchaseCost'.$rwnm.'" type="hidden" value="'.$cost.'">
<span style="display:none" class="purchaseCost">0</span>
<input name="purchaseCost'.$rwnm.'" type="hidden" value="'.$cost.'">
<input type="hidden" name="margin'.$rwnm.'" value="0">
<span class="margin pull-right" style="display:none">0</span>
</td>

<td>
<textarea id="serialno'.$rwnm.'" name="serialno'.$rwnm.'" readonly class="serialno inputElement">'.$serl.'</textarea>
</td>

<td>
<div>
<input id="listPrice'.$rwnm.'" name="listPrice'.$rwnm.'" value="'.$cost.'" type="text" data-rule-required="true" data-rule-positive="true" 
class="listPrice smallInputBox inputElement" data-is-price-changed="false" readonly list-info="" data-base-currency-id="" aria-required="true">
<br>&nbsp;
</div>
<div>
<span class="hide">(-)&nbsp;<strong>
<a href="javascript:void(0)" class="individualDiscount hide">
<span class="itemDiscount">(0)</span>
</a>
 : </strong>
 </span>
 </div>
 
 <div class="discountUI validCheck hide" id="discount_div'.$rwnm.'">
 
 <input type="hidden" id="discount_type'.$rwnm.'" name="discount_type'.$rwnm.'" value="zero" class="discount_type">
 <p class="popover_title hide">Set Discount For : 
 <span class="variable"></span>
 </p>
 
 <table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
 <tbody>
 <tr>
 <td><input type="radio" name="discount'.$rwnm.'" checked="" class="discounts" data-discount-type="zero">
 &nbsp;Zero Discount
 </td>
 <td>
 <input type="hidden" class="discountVal" value="0">
 </td>
 </tr>
 <tr>
 <td>
 <input type="radio" name="discount'.$rwnm.'" class="discounts" data-discount-type="percentage">&nbsp; %Price
 </td>
 <td>
 <span class="pull-right">&nbsp;%</span>
 <input type="text" data-rule-positive="true" data-rule-inventory_percentage="true" id="discount_percentage'.$rwnm.'" name="discount_percentage'.$rwnm.'" value="" class="discount_percentage span1 pull-right discountVal hide">
 </td>
 </tr>
 <tr>
 <td class="LineItemDirectPriceReduction">
 <input type="radio" name="discount'.$rwnm.'" class="discounts" data-discount-type="amount">
 &nbsp;Direct Price Reduction
 </td>
 <td>
 <input type="text" data-rule-positive="true" id="discount_amount'.$rwnm.'" name="discount_amount'.$rwnm.'" value="" class="span1 pull-right discount_amount discountVal hide">
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 
 <div style="width:150px;" class="hide"><strong>Total After Discount :</strong></div>
 <div class="individualTaxContainer hide">(+)&nbsp;<strong><a href="javascript:void(0)" class="individualTax">Tax </a> : </strong></div>
 <span class="taxDivContainer">
 <div class="taxUI hide" id="tax_div'.$rwnm.'">
 <p class="popover_title hide">Set Tax for : 
 <span class="variable"></span>
 </p>
 </div>
 </span>
 
 </td>
 
 <td>
 <div id="productTotal'.$rwnm.'" align="right" class="productTotal">'.$totalcost.'</div>
 <div id="discountTotal'.$rwnm.'" align="right" class="discountTotal">0.00</div>
 <div id="totalAfterDiscount'.$rwnm.'" align="right" class="totalAfterDiscount">'.$totalcost.'</div>
 <div id="taxTotal'.$rwnm.'" align="right" class="productTaxTotal hide">0.00</div>
 </td>
 
 <td>
 <span id="netPrice'.$rwnm.'" class="pull-right netPrice">'.$totalcost.'</span>
 </td>
 
</tr>';
	

$rwnm++;	
	}
}
$response['totalamount'] = $sumamount;
$response['rowcount'] = $rwnm;
$response['message'] = $html;
return $response;	
} 



function importserialnostockupload($module,$stockupid){
$response = array();
$hsql = mysql_query("SELECT * FROM `arocrm_stockupload`
INNER JOIN `arocrm_stockuploadcf` ON `arocrm_stockuploadcf`.`stockuploadid` = `arocrm_stockupload`.`stockuploadid`
WHERE `arocrm_stockupload`.`stockuploadid` = '".$stockupid."'");
$hinfo = mysql_fetch_array($hsql);	
$rcount = mysql_num_rows($hsql);
if($rcount > 0){ 
$frsql  =  "SELECT * FROM `arocrm_stockupload_lineitem_lineitem`
WHERE `stockuploadid` = '".$stockupid."' ORDER BY `id` ASC";
$sqlqry = mysql_query($frsql);
while($row = mysql_fetch_array($sqlqry)){

$plantid = $hinfo['cf_nrl_plantmaster741_id'];
$storeid = $row['cf_4725'];
$productid = $row['cf_4709'];
$productcost = $row['cf_4717'];
$srnos = explode(",",$row['cf_4721']);
$quany = $row['cf_4715'];
$trandate = $hinfo['cf_4979'];
$tmp = explode("-",$trandate);
$tmp1 = strlen($tmp[0]);
$tmp2 = strlen($tmp[1]);
$tmp3 = strlen($tmp[2]);
$sttmp = explode("-",$row['cf_5119']);
$status = trim($sttmp[2]);
if($tmp1==2 && $tmp2==2 && $tmp3==4){
$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
}

$proyrm = mysql_query("SELECT arocrm_productcf.* FROM arocrm_products 
                                INNER JOIN arocrm_productcf ON arocrm_productcf.productid = arocrm_products.productid
                                INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
                                WHERE arocrm_crmentity.deleted = 0 AND arocrm_products.productid = '".$productid."'");
                                $proyrrow =  mysql_fetch_array($proyrm);
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




		$mfcrmid = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_crmentity_seq`"));
		$recid = $mfcrmid['id'];
		$recid = (int)$recid + 1;

		$crmin = "INSERT INTO `arocrm_crmentity` (`crmid`,`smcreatorid`,`smownerid`,`modifiedby`,`setype`,`createdtime`,`modifiedtime`,
		`version`,`presence`,`deleted`,`smgroupid`,`source`,`label`) 
		VALUES(".$recid.",".$_SESSION['authenticated_user_id'].",".$_SESSION['authenticated_user_id'].",".$_SESSION['authenticated_user_id'].",'SerialNumber','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."',0,1,0,0,'CRM','".$serialnos."')";

		$crmenins = mysql_query($crmin);

		$nextrecid = $recid;

		$updateeneseq = mysql_query("update `arocrm_crmentity_seq` set `id` = '".$nextrecid."'");

		$mfnumid = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_modentity_num` WHERE `active` = '1' AND `semodule` = 'SerialNumber'"));
		$recserialid = $mfnumid['prefix'].$mfnumid['cur_id'];

		$nextid = (int)$mfnumid['cur_id'] + 1;

		$updatenumseq = mysql_query("update `arocrm_modentity_num` set `cur_id` = '".$nextid."' where  `active` = '1' AND `semodule` = 'SerialNumber'");
		
	    $serm  = "INSERT INTO `arocrm_serialnumber`(`serialnumberid`, `name`, `serialnumberno`, `cf_nrl_plantmaster496_id`, `cf_nrl_storagelocation106_id`, `cf_nrl_products16_id`) VALUES (".$recid.",'".$serialnos."','".$recserialid."',".$plantid.",".$storeid.",".$productid.")";
		$ins_serial = mysql_query($serm);

		$ins_serial_cf = mysql_query("INSERT INTO `arocrm_serialnumbercf`(`serialnumberid`, `cf_1256`, `cf_1264` , `cf_1258` , `cf_1260` , `cf_1268`, `cf_1270`, `cf_2834`) VALUES (".$recid.",'".$status."','".$productcost."','".$serialnos."','".$serialnos."','".$mdfdate."','".$stockupid."','1')");

		$ins_serial_userassign = mysql_query("INSERT INTO `arocrm_crmentity_user_field`(`recordid`, `userid`, `starred`) VALUES (".$recid.",".$_SESSION['authenticated_user_id'].",'0')");


		$selqty = "SELECT * FROM `arocrm_plantproductassignmentcf` WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = ".$productid." AND `cf_nrl_plantmaster103_id` = ".$plantid." LIMIT 0,1)";
		$selqry = mysql_fetch_array(mysql_query($selqty));
		$totalqty = (int)$selqry['cf_1356'] + 1;

		$updateqty = mysql_query("UPDATE `arocrm_plantproductassignmentcf` SET `cf_1356` = '".$totalqty."' WHERE `plantproductassignmentid` = (SELECT `plantproductassignmentid` FROM `arocrm_plantproductassignment` WHERE `cf_nrl_products323_id` = ".$productid." AND `cf_nrl_plantmaster103_id` = ".$plantid.")");


		}
		
		
        $tmp = explode("-", $trandate);
		$tmp1 = strlen($tmp[0]);
		$tmp2 = strlen($tmp[1]);
		$tmp3 = strlen($tmp[2]);
		
		if($tmp1==2 && $tmp2==2 && $tmp3==4){
		$trandate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		
		
		
		if($productid!="" && $plantid!="" && $storeid!=""){
		$stkqtysql = "SELECT  `closing_stock` as totqty FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$status."' ORDER BY `id` DESC LIMIT 0,1";
		$newqtysql = mysql_query($stkqtysql);
		$ftarray = mysql_fetch_array($newqtysql);
		$qtysqlnum = mysql_num_rows($newqtysql);
		$prevstk = (int)$ftarray['totqty'];
		if($qtysqlnum==0){
			$prevstk = 0;
		}
		$curstk = $prevstk + $quany;
		
		mysql_query("INSERT INTO `arocrm_dailystock_details`(`id`, `product`, `plant`, `store`, `transaction_date`, `created_date`, `debit_quantity`, `credit_quantity`, `typeoftransaction`, `qualitystatus`,`serialnumbers`,`opening_stock`,`closing_stock`) VALUES (NULL,'".$productid."','".$plantid."','".$storeid."','".$trandate."','".date('Y-m-d')."','".$quany."','0','SU','".$status."','".$row['cf_4721']."','".$prevstk."','".$curstk."')");
		}
}
}	
$response['message'] = 'Successful -> '.$stockupid;
return $response;

 }


function getPopulatedSerialNumberforStoretoStore($nos){
$response = array();
$array = array();
$html = '';
for($k = 1; $k <= $nos; $k++)
{
$html .= '
<tr id="LineItem__row_'.$k.'" class="tr_clone">

<td>
<i class="fa fa-trash deleteLineItemRow cursorPointer hide" title="Delete" id="'.$k.'" style=""></i>
&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue"><div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'">
<div class="input-group"><input name="cf_4747_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_4747_'.$k.'">
<input id="cf_4747_display_'.$k.'" name="cf_4747_display_'.$k.'" data-fieldname="cf_4747" data-fieldtype="reference" type="text" class="marginLeftZero  inputElement" value="" readonly autocomplete="off">
<a href="#" class="clearReferenceSelection hide"> x </a>
<span class="input-group-addon relatedPopup cursorPointer" id="cf_4747_'.$k.',cf_4747_display_'.$k.'" title="Select">
<i id="'.$k.'" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_4749" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_4749_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_4749_'.$k.'">
<option value="">Select an Option</option>
<option value="R - Released">R - Released</option>
<option value="B - Blocked">B - Blocked</option>
</select>
</td>

</tr>';
array_push($array,$k);
}
$response['message'] = $html;
$response['count'] = implode(',',$array);

return $response;
}


function getallsalesbudgetqty($years,$month,$plantid,$assignedto){
$response = array();

$fourwqtymone = 0;
$twowqtymone = 0;
$ibqtymone = 0;
$erqtymone = 0;


$fourwqtymtwo = 0;
$twowqtymtwo = 0;
$ibqtymtwo = 0;
$erqtymtwo = 0;


$fourwqtymthree = 0;
$twowqtymthree = 0;
$ibqtymthree = 0;
$erqtymthree = 0;

$mont = explode("-",$month);
$month1 = trim($mont[0]);
$month2 = trim($mont[1]);
$month3 = trim($mont[2]);

$months1 = date("m",strtotime($month1));
$months2 = date("m",strtotime($month2));
$months3 = date("m",strtotime($month3));

$salsql = "SELECT * FROM `arocrm_salesbudget`
INNER JOIN `arocrm_salesbudgetcf` ON `arocrm_salesbudgetcf`.`salesbudgetid` = `arocrm_salesbudget`.`salesbudgetid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_salesbudget`.`salesbudgetid`
WHERE `arocrm_crmentity`.`deleted` = 0
AND `arocrm_salesbudget`.`cf_nrl_plantmaster615_id` = '".$plantid."'
AND `arocrm_crmentity`.`smownerid` = '".$assignedto."'
AND `arocrm_salesbudgetcf`.`cf_3424` = '".$years."'
AND `arocrm_salesbudget`.`salesbudgetid` NOT IN
(SELECT `arocrm_salesbudget`.`cf_nrl_salesbudget772_id` FROM `arocrm_salesbudget`
INNER JOIN `arocrm_salesbudgetcf` ON `arocrm_salesbudgetcf`.`salesbudgetid` = `arocrm_salesbudget`.`salesbudgetid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_salesbudget`.`salesbudgetid`
WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_crmentity`.`smownerid` = '".$assignedto."' AND `arocrm_salesbudget`.`cf_nrl_salesbudget772_id` != '0' AND `arocrm_salesbudgetcf`.`cf_4805` = 'Approved') AND `arocrm_salesbudgetcf`.`cf_4805` = 'Approved'";
$salessql = mysql_query($salsql);

while($datasql = mysql_fetch_array($salessql)){
switch($months1){
CASE '01':
$actmonth1 = 'cf_4497';
break;
CASE '02':
$actmonth1 = 'cf_4503';
break;
CASE '03':
$actmonth1 = 'cf_4509';
break;
CASE '04':
$actmonth1 = 'cf_4419';
break;
CASE '05':
$actmonth1 = 'cf_4425';
break;
CASE '06':
$actmonth1 = 'cf_4439';
break;
CASE '07':
$actmonth1 = 'cf_4461';
break;
CASE '08':
$actmonth1 = 'cf_4467';
break;
CASE '09':
$actmonth1 = 'cf_4473';
break;
CASE '10':
$actmonth1 = 'cf_4479';
break;
CASE '11':
$actmonth1 = 'cf_4485';
break;
CASE '12':
$actmonth1 = 'cf_4491';
break;
};


switch($months2){
CASE '01':
$actmonth2 = 'cf_4497';
break;
CASE '02':
$actmonth2 = 'cf_4503';
break;
CASE '03':
$actmonth2 = 'cf_4509';
break;
CASE '04':
$actmonth2 = 'cf_4419';
break;
CASE '05':
$actmonth2 = 'cf_4425';
break;
CASE '06':
$actmonth2 = 'cf_4439';
break;
CASE '07':
$actmonth2 = 'cf_4461';
break;
CASE '08':
$actmonth2 = 'cf_4467';
break;
CASE '09':
$actmonth2 = 'cf_4473';
break;
CASE '10':
$actmonth2 = 'cf_4479';
break;
CASE '11':
$actmonth2 = 'cf_4485';
break;
CASE '12':
$actmonth2 = 'cf_4491';
break;
};


switch($months3){
CASE '01':
$actmonth3 = 'cf_4497';
break;
CASE '02':
$actmonth3 = 'cf_4503';
break;
CASE '03':
$actmonth3 = 'cf_4509';
break;
CASE '04':
$actmonth3 = 'cf_4419';
break;
CASE '05':
$actmonth3 = 'cf_4425';
break;
CASE '06':
$actmonth3 = 'cf_4439';
break;
CASE '07':
$actmonth3 = 'cf_4461';
break;
CASE '08':
$actmonth3 = 'cf_4467';
break;
CASE '09':
$actmonth3 = 'cf_4473';
break;
CASE '10':
$actmonth3 = 'cf_4479';
break;
CASE '11':
$actmonth3 = 'cf_4485';
break;
CASE '12':
$actmonth3 = 'cf_4491';
break;
};

$fourwinnersql = mysql_query("SELECT `".$actmonth1."`,`".$actmonth2."`,`".$actmonth3."` FROM `arocrm_salesbudget_category_wise_lineitem` WHERE `salesbudgetid` = '".$datasql['salesbudgetid']."'
AND `cf_4399` = '4W'");

$fwrow = mysql_fetch_array($fourwinnersql);

$fourwqtymone = (float)$fourwqtymone + (float)$fwrow[$actmonth1];
$fourwqtymtwo = (float)$fourwqtymtwo + (float)$fwrow[$actmonth2];
$fourwqtymthree = (float)$fourwqtymthree + (float)$fwrow[$actmonth3];

$twowinnersql = mysql_query("SELECT `".$actmonth1."`,`".$actmonth2."`,`".$actmonth3."` FROM `arocrm_salesbudget_category_wise_lineitem` WHERE `salesbudgetid` = '".$datasql['salesbudgetid']."'
AND `cf_4399` = '2W'");

$twrow = mysql_fetch_array($twowinnersql);

$twowqtymone = (float)$twowqtymone + (float)$twrow[$actmonth1];
$twowqtymtwo = (float)$twowqtymtwo + (float)$twrow[$actmonth2];
$twowqtymthree = (float)$twowqtymthree + (float)$twrow[$actmonth3];


$ibinnersql = mysql_query("SELECT `".$actmonth1."`,`".$actmonth2."`,`".$actmonth3."` FROM `arocrm_salesbudget_category_wise_lineitem` WHERE `salesbudgetid` = '".$datasql['salesbudgetid']."'
AND `cf_4399` = 'IB'");

$ibrow = mysql_fetch_array($ibinnersql);

$ibqtymone = (float)$ibqtymone + (float)$ibrow[$actmonth1];
$ibqtymtwo = (float)$ibqtymtwo + (float)$ibrow[$actmonth2];
$ibqtymthree = (float)$ibqtymthree + (float)$ibrow[$actmonth3];



$erinnersql = mysql_query("SELECT `".$actmonth1."`,`".$actmonth2."`,`".$actmonth3."` FROM `arocrm_salesbudget_category_wise_lineitem` WHERE `salesbudgetid` = '".$datasql['salesbudgetid']."'
AND `cf_4399` = 'ER'");

$errow = mysql_fetch_array($erinnersql);

$erqtymone = (float)$erqtymone + (float)$errow[$actmonth1];
$erqtymtwo = (float)$erqtymtwo + (float)$errow[$actmonth2];
$erqtymthree = (float)$erqtymthree + (float)$errow[$actmonth3];

}

$response['fourwqtyone'] = $fourwqtymone;
$response['twowqtyone'] = $twowqtymone;
$response['ibqtyone'] = $ibqtymone;
$response['erqtyone'] = $erqtymone;

$response['fourwqtytwo'] = $fourwqtymtwo;
$response['twowqtytwo'] = $twowqtymtwo;
$response['ibqtytwo'] = $ibqtymtwo;
$response['erqtytwo'] = $erqtymtwo;

$response['fourwqtythree'] = $fourwqtymthree;
$response['twowqtythree'] = $twowqtymthree;
$response['ibqtythree'] = $ibqtymthree;
$response['erqtythree'] = $erqtymthree;
return $response;
}



function getSchemeMasterDetailsRev($id){

$response = array();
$procsql  = mysql_query("SELECT `arocrm_schememaster`.*,`arocrm_schememastercf`.* FROM `arocrm_schememaster`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_schememaster`.`schememasterid`
  INNER JOIN `arocrm_schememastercf` ON `arocrm_schememastercf`.`schememasterid` = `arocrm_schememaster`.`schememasterid`
  WHERE `arocrm_crmentity`.`deleted`  = '0' AND `arocrm_schememaster`.`schememasterid` = '".$id."'");
$rwnm = mysql_num_rows($procsql);
if($rwnm == 1){

$row = mysql_fetch_array($procsql);

$tmp1 = explode("-",$row['cf_2066']);
$tmp2 = explode("-",$row['cf_2068']);

$response['startdate'] = $tmp1[2]."-".$tmp1[1]."-".$tmp1[0];
$response['enddate'] = $tmp2[2]."-".$tmp2[1]."-".$tmp2[0];
$response['schemetype'] = $row['cf_3663'];
$response['schemefor'] = $row['cf_2062'];
$response['name'] = $row['name'];

$prodtsql = mysql_query("SELECT * FROM `arocrm_schememaster_product_category_lineitem` WHERE `schememasterid` = '".$id."'");
$response['productschemerowcount'] = mysql_num_rows($prodtsql);
$i = 1;
$array1 = array();
$displayoff1 = '';

if($response['productschemerowcount']==1){
  $displayoff1 = 'style="display:none;"';
}

while($row1 = mysql_fetch_array($prodtsql)){

  $producthtml .= '<tr id="Product_Category__row_'.$i.'" class="tr_clone">

  <td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" '.$displayoff1.' id="'.$i.'"></i>
  &nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
  </td>

  <td class="fieldValue">
  <select data-fieldname="cf_3677" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3677_'.$i.'" data-selected-value=" " title="" tabindex="-1" aria-invalid="false" id="cf_3677_'.$i.'">
  <option value="">Select an Option</option>';

  if($row1['cf_3677']=='4W'){
  $producthtml .= '<option value="4W" selected>4W</option>';
  }else{
  $producthtml .= '<option value="4W">4W</option>';
  }

  if($row1['cf_3677']=='2W'){
  $producthtml .= '<option value="2W" selected>2W</option>';
  }else{
  $producthtml .= '<option value="2W">2W</option>';
  }

  if($row1['cf_3677']=='IB'){
  $producthtml .= '<option value="IB" selected>IB</option>';
  }else{
  $producthtml .= '<option value="IB">IB</option>';
  }

  if($row1['cf_3677']=='IB'){
  $producthtml .= '<option value="ER" selected>ER</option>';
  }else{
  $producthtml .= '<option value="ER">ER</option>';
  }


  $producthtml .= '
  </select>
  </td>

  <td class="fieldValue">
  <input id="cf_3679_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3679_'.$i.'" value="'.$row1['cf_3679'].'">
  </td>

  <td class="fieldValue">
  <input id="cf_4131_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_4131_'.$i.'" value="'.$row1['cf_4131'].'">
  </td>

  <td class="fieldValue">
  <input id="cf_3681_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3681_'.$i.'" value="'.$row1['cf_3681'].'">
  </td>

  </tr>';

array_push($array1,$i);
$i++;
}

$response['productschemecounthtml'] = $producthtml;
$response['productschemecount'] = implode(',',$array1);



$prodsubsql = mysql_query("SELECT * FROM `arocrm_schememaster_product_subcategory_lineitem` WHERE `schememasterid` = '".$id."'");
$response['productsubrowcount'] = mysql_num_rows($prodsubsql);
$j = 1;
$array2 = array();
$displayoff2 = '';
if($response['productsubrowcount']==1){
  $displayoff2 = 'style="display:none;"';
}
while($row2 = mysql_fetch_array($prodsubsql)){

$productsubhtml .= '<tr id="Product_Subcategory__row_'.$j.'" class="tr_clone">

<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$j.'" '.$displayoff2.'></i>&nbsp;
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3683" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3683_'.$j.'" data-selected-value=" " title="" tabindex="-1" id="cf_3683_'.$j.'">
<option value="">Select an Option</option>';

if($row2['cf_3683']=='PCUV'){
$productsubhtml .= '<option value="PCUV" selected>PCUV</option>';
}else{
$productsubhtml .= '<option value="PCUV">PCUV</option>';
}

if($row2['cf_3683']=='COMV'){
$productsubhtml .= '<option value="COMV" selected>COMV</option>';
}else{
$productsubhtml .= '<option value="COMV">COMV</option>';
}

if($row2['cf_3683']=='TRCT'){
$productsubhtml .= '<option value="TRCT" selected>TRCT</option>';
}else{
$productsubhtml .= '<option value="TRCT">TRCT</option>';
}

if($row2['cf_3683']=='ERBT'){
$productsubhtml .= '<option value="ERBT" selected>ERBT</option>';
}else{
$productsubhtml .= '<option value="ERBT">ERBT</option>';
}

if($row2['cf_3683']=='INVT'){
$productsubhtml .= '<option value="INVT" selected>INVT</option>';
}else{
$productsubhtml .= '<option value="INVT">INVT</option>';
}



if($row2['cf_3683']=='TWBT'){
$productsubhtml .= '<option value="TWBT" selected>TWBT</option>';
}else{
$productsubhtml .= '<option value="TWBT">TWBT</option>';
}


$productsubhtml .= '
</select>
</td>

<td class="fieldValue">
<input id="cf_3685_'.$j.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3685_'.$j.'" value="">
</td>

<td class="fieldValue">
<input id="cf_4139_'.$j.'" style="min-width:80px;" type="number" class="inputElement" name="cf_4139_'.$j.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3687_'.$j.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3687_'.$j.'" value="">
</td>

</tr>';

array_push($array2,$j);
$j++;
}

$response['productschemecounthtml'] = $productsubhtml;
$response['productschemecount'] = implode(',',$array2);


$prosql = mysql_query("SELECT * FROM `arocrm_schememaster_product_scheme_lineitem` WHERE `schememasterid` = '".$id."'");
$response['prorowcount'] = mysql_num_rows($prosql);
$k = 1;
$array3 = array();
$displayoff3 = '';
if($response['prorowcount']==1){
  $displayoff3 = 'style="display:none;"';
}
while($row3 = mysql_fetch_array($prosql)){

$productinfo = getProductDetails($row3['cf_3671']);

$prohtml .= '
<tr id="Product_Scheme__row_'.$k.'" class="tr_clone">

<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$k.'" '.$displayoff3.'></i>&nbsp;
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">
<div class="referencefield-wrapper">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$k.'">
<div class="input-group">
<input name="cf_3671_'.$k.'" type="hidden" value="'.$row3['cf_3671'].'" class="sourceField" data-displayvalue="" id="cf_3671_'.$k.'">
<input id="cf_3671_display_'.$k.'" name="cf_3671_display_'.$k.'" data-fieldname="cf_3671" data-fieldtype="reference" type="text"
class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productinfo['productname'].'" placeholder="Type to search" autocomplete="off">
<a href="#" class="clearReferenceSelection hide"> x </a>
<span class="input-group-addon relatedPopup cursorPointer" id="cf_3671_'.$k.',cf_3671_display_'.$k.'" title="Select">
<i id="'.$k.'" class="fa fa-search"></i>
</span>
</div>
<span class="createReferenceRecord cursorPointer clearfix" title="Create">
<i id="'.$k.'" class="fa fa-plus"></i>
</span>
</div>
</td>

<td class="fieldValue">
<input id="cf_3673_'.$k.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3673_'.$k.'" value="'.$row3['cf_3673'].'">
</td>

<td class="fieldValue">
<input id="cf_4123_'.$k.'" style="min-width:80px;" type="number" class="inputElement" name="cf_4123_'.$k.'" value="'.$row3['cf_4123'].'">
</td>

<td class="fieldValue">
<input id="cf_3675_'.$k.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3675_'.$k.'" value="'.$row3['cf_3675'].'">
</td>

</tr>
';

array_push($array3,$k);
$k++;
}

$response['procounthtml'] = $prohtml;
$response['procount'] = implode(',',$array3);


$giftsql = mysql_query("SELECT * FROM `arocrm_schememaster_gift_details_lineitem` WHERE `schememasterid` = '".$id."'");
$response['giftrowcount'] = mysql_num_rows($giftsql);
$l = 1;
$array4 = array();
$displayoff4 = '';
if($response['giftrowcount']==1){
  $displayoff4 = 'style="display:none;"';
}
while($row4 = mysql_fetch_array($giftsql)){

$productinfo = getProductDetails($row4['cf_3717']);

$gifthtml .= '
<tr id="Gift_Details__row_'.$l.'" class="tr_clone">

<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$l.'" style=""></i>&nbsp;
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">

    <select data-fieldname="cf_3713" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3713_'.$l.'" data-selected-value=" " tabindex="-1" title="" id="cf_3713_'.$l.'">
    <option value="">Select an Option</option>';

if($row4['cf_3713']=='Foreign Trip'){
  $gifthtml .= '<option value="Foreign Trip" selected>Foreign Trip</option>';
}else{
  $gifthtml .= '<option value="Foreign Trip">Foreign Trip</option>';
}


if($row4['cf_3713']=='Gifts'){
    $gifthtml .= '<option selected value="Gifts">Gifts</option>';
}else{
    $gifthtml .= '<option value="Gifts">Gifts</option>';
}


if($row4['cf_3713']=='Discount %'){
  $gifthtml .= '<option selected value="Discount %">Discount %</option>';
}else{
  $gifthtml .= '<option value="Discount %">Discount %</option>';
}



if($row4['cf_3713']=='Cash Discount'){
$gifthtml .= '<option value="Cash Discount" selected>Cash Discount</option>';
}else{
$gifthtml .= '<option value="Cash Discount">Cash Discount</option>';
}




if($row4['cf_3713']=='FOC (Product)'){
$gifthtml .= '<option value="FOC (Product)" selected>FOC (Product)</option>';
}else{
$gifthtml .= '<option value="FOC (Product)">FOC (Product)</option>';
}






if($row4['cf_3713']=='FOC (Product category)'){
$gifthtml .= '<option value="FOC (Product category)" selected>FOC (Product category)</option>';
}else{
$gifthtml .= '<option value="FOC (Product category)">FOC (Product category)</option>';
}



if($row4['cf_3713']=='FOC (Product Subcategory)'){
$gifthtml .= '<option value="FOC (Product Subcategory)" selected>FOC (Product Subcategory)</option>';
}else{
$gifthtml .= '<option value="FOC (Product Subcategory)">FOC (Product Subcategory)</option>';
}


    $gifthtml .= '</select>
    </td>

    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_3715_'.$l.'" class="inputElement " name="cf_3715_'.$l.'">'.$row4['cf_3715'].'</textarea>
    </td>

    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_2">
    <div class="input-group">
    <input name="cf_3717_'.$l.'" type="hidden" value="'.$row4['cf_3717'].'" class="sourceField" data-displayvalue="" id="cf_3717_'.$l.'">
    <input id="cf_3717_display_'.$l.'" name="cf_3717_display_'.$l.'" data-fieldname="cf_3717" data-fieldtype="reference"
     type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productinfo['productname'].'" placeholder="Type to search" autocomplete="off">
    <a href="#" class="clearReferenceSelection hide"> x </a>
    <span class="input-group-addon relatedPopup cursorPointer" id="cf_3717_'.$l.',cf_3717_display_'.$l.'" title="Select">
    <i id="'.$l.'" class="fa fa-search"></i>
    </span>
    </div>
    </div>
    </td>

    <td class="fieldValue">
   <select data-fieldname="cf_3719" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3719_'.$l.'" data-selected-value=" " title="" tabindex="-1" id="cf_3719_'.$l.'">
   <option value="">Select an Option</option>';

if($row4['cf_3719']=='4W'){
$gifthtml .= '<option value="4W" selected>4W</option>';
}else{
$gifthtml .= '<option value="4W">4W</option>';
}


if($row4['cf_3719']=='2W'){
$gifthtml .= '<option value="2W" selected>2W</option>';
}else{
$gifthtml .= '<option value="2W">2W</option>';
}


if($row4['cf_3719']=='IB'){
 $gifthtml .= '<option value="IB" selected>IB</option>';
}else{
 $gifthtml .= '<option value="IB">IB</option>';
}


if($row4['cf_3719']=='ER'){
$gifthtml .= '<option value="ER" selected>ER</option>';
}else{
$gifthtml .= '<option value="ER">ER</option>';
}




   $gifthtml .= '</select>

   </td>

   <td class="fieldValue">
   <select data-fieldname="cf_3721" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3721_'.$l.'" data-selected-value=" " title="" tabindex="-1" id="cf_3721_'.$l.'">
   <option value="">Select an Option</option>';

if($row4['cf_3721']=='PCUV'){
$gifthtml .= '<option value="PCUV" selected>PCUV</option>';
}else{
$gifthtml .= '<option value="PCUV">PCUV</option>';
}

if($row4['cf_3721']=='COMV'){
  $gifthtml .= '<option value="COMV" selected>COMV</option>';
}else{
  $gifthtml .= '<option value="COMV">COMV</option>';
}




    if($row4['cf_3721']=='TRCT'){
    $gifthtml .= '<option value="TRCT" selected>TRCT</option>';
    }else{
    $gifthtml .= '<option value="TRCT">TRCT</option>';
    }




    if($row4['cf_3721']=='ERBT'){
    $gifthtml .= '<option value="ERBT" selected>ERBT</option>';
    }else{
    $gifthtml .= '<option value="ERBT">ERBT</option>';
    }

    if($row4['cf_3721']=='INVT'){
    $gifthtml .= '<option value="INVT" selected>INVT</option>';
    }else{
    $gifthtml .= '<option value="INVT">INVT</option>';
    }


    if($row4['cf_3721']=='TWBT'){
    $gifthtml .= '<option value="TWBT" selected>TWBT</option>';
    }else{
    $gifthtml .= '<option value="TWBT">TWBT</option>';
    }

  $gifthtml .= '</select>
   </td>

   <td class="fieldValue">
   <input id="cf_3723_'.$l.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3723_'.$l.'" value="'.$row4['cf_3723'].'">
   </td>

   </tr>
';

array_push($array4,$l);
$l++;
}

$response['giftcounthtml'] = $gifthtml;
$response['giftcount'] = implode(',',$array4);
}
return $response;
}





function loadSchemeMasterWindow($id){
$response = array();
$actualval = array();

$productcategory = '<option value="">Select an Option</option>';
$productsubcategory = '<option value="">Select an Option</option>';

$procsql  = mysql_query("SELECT * FROM `arocrm_productcategory`");
while($procrow = mysql_fetch_array($procsql)){

$productcategory .= '<option value="'.$procrow['productcategory'].'">'.$procrow['productcategory'].'</option>';

}

$sdl = "SELECT * FROM `arocrm_discountmaster_product_category_lineitem` WHERE `discountmasterid` = '".$id."' order by `id` asc";
$sql_data = mysql_query($sdl);
while($dtrow = mysql_fetch_array($sql_data)){
	array_push($actualval,$dtrow['cf_4180']);
}

$prosubcsql  = mysql_query("SELECT * FROM `arocrm_cf_1340`");
while($prosubcrow = mysql_fetch_array($prosubcsql)){
$productsubcategory .= '<option value="'.$prosubcrow['cf_1340'].'">'.$prosubcrow['cf_1340'].'</option>';
}

$response['sselectedval'] = $actualval;
$response['productcategory'] =  $productcategory;
$response['productsubcategory'] =  $productsubcategory;
return $response;
}





function loadAllStockReq($plantid){
$response = array();

$allvals = "";
$jk  = 1;
$ref = mysql_query("SELECT `cf_2765` FROM `arocrm_purchasereqcf` 
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_purchasereqcf`.`purchasereqid` 
WHERE `arocrm_crmentity`.`deleted` = '0'");
while($dref = mysql_fetch_array($ref)){
$datad = $dref['cf_2765'];
$infos = explode("|##|",$datad);
foreach($infos as $rdata){
if($rdata!=""){
if($jk==1){
$allvals .= "'".trim($rdata)."'";
}else{
$allvals .= ",'".trim($rdata)."'";
}
$jk++;
}
}

}

if($allvals==""){
	$allvals = "''";
}
$sqld = "SELECT * FROM `arocrm_stockrequisition`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_stockrequisition`.`stockrequisitionid`
INNER JOIN `arocrm_stockrequisitioncf` ON `arocrm_stockrequisitioncf`.`stockrequisitionid` = `arocrm_stockrequisition`.`stockrequisitionid`
WHERE `arocrm_crmentity`.`deleted` = '0'
AND `arocrm_stockrequisition`.`stockrequisitionno` NOT IN (".$allvals.")
AND `arocrm_stockrequisition`.`cf_nrl_plantmaster587_id` = '".$plantid."'
AND `arocrm_stockrequisitioncf`.`cf_4807` = 'Approved'";
$exe = mysql_query($sqld);
$opt = '';
while($row = mysql_fetch_array($exe)){
$opt .= '<option value="'.$row['stockrequisitionno'].'">'.$row['stockrequisitionno'].'</option>';
}
$response['message'] =  $opt;
return $response;
}



function getLineItemsforSalesPlan($postingdate,$branchid){
$response = array();
$fourwbsql = mysql_query("SELECT * FROM `arocrm_products`
 INNER JOIN  `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_products`.`productid` 
 INNER JOIN `arocrm_productcf` ON `arocrm_productcf`.`productid` = `arocrm_products`.`productid` 
 WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_products`.`productcategory` = '4W'");
$twowbsql = mysql_query("SELECT * FROM `arocrm_products` 
INNER JOIN  `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_products`.`productid` 
 INNER JOIN `arocrm_productcf` ON `arocrm_productcf`.`productid` = `arocrm_products`.`productid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_products`.`productcategory` = '2W'");
$ibsql = mysql_query("SELECT * FROM `arocrm_products` 
INNER JOIN  `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_products`.`productid` 
 INNER JOIN `arocrm_productcf` ON `arocrm_productcf`.`productid` = `arocrm_products`.`productid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_products`.`productcategory` = 'IB'");
$ersql = mysql_query("SELECT * FROM `arocrm_products`
 INNER JOIN  `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_products`.`productid` 
  INNER JOIN `arocrm_productcf` ON `arocrm_productcf`.`productid` = `arocrm_products`.`productid`
 WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_products`.`productcategory` = 'ER'");

 $date = explode('-',$postingdate);
 $curyear = $date[2];
 $curmonth = $date[1];
 if($curmonth == '04')
 {
	 $nextmonth = '05';
	 $next2month = '06';
 }
 if($curmonth == '05')
 {
	 $nextmonth = '06';
	 $next2month = '07';
 }
 if($curmonth == '06')
 {
	 $nextmonth = '07';
	 $next2month = '08';
 }
 if($curmonth == '07')
 {
	 $nextmonth = '08';
	 $next2month = '09';
 }
 if($curmonth == '08')
 {
	 $nextmonth = '09';
	 $next2month = '10';
 }
 if($curmonth == '09')
 {
	 $nextmonth = '10';
	 $next2month = '11';
 }
 if($curmonth == '10')
 {
	 $nextmonth = '11';
	 $next2month = '12';
 }
 if($curmonth == '11')
 {
	 $nextmonth = '12';
	 $nextyear = $curyear + 1;
	 $next2month = '01';
 }
 if($curmonth == '12')
 {
	 $nextyear = $curyear + 1;
	 $nextmonth = '01';
	 $next2month = '02';
 }
 if($curmonth == '01')
 {
	 $nextmonth = '02';
	 $next2month = '03';
 }
 if($curmonth == '02')
 {
	 $nextmonth = '03';
	 $next2month = '04';
 }
 if($curmonth == '03')
 {
	 $nextmonth = '04';
	 $next2month = '05';
 }
$fourw = '';
$fourwcount = array();
$f = 1;

while($frow = mysql_fetch_array($fourwbsql)){
	$month1actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$curmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$frow['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month1salerow = mysql_fetch_array($month1actualsale);
	$month1saleqty = $month1salerow['quantity'];
	$month1actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$curmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$frow['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month1salesreturnrow = mysql_fetch_array($month1actualreturnsql);
$month1salesreturnqty = $month1salesreturnrow['qty'];
$month1actualqty = $month1saleqty - $month1salesreturnqty;

$month2actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$nextmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$frow['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month2salerow = mysql_fetch_array($month2actualsale);
	$month2saleqty = $month2salerow['quantity'];
	$month2actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$nextmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$frow['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month2salesreturnrow = mysql_fetch_array($month2actualreturnsql);
$month2salesreturnqty = $month2salesreturnrow['qty'];
$month2actualqty = $month2saleqty - $month2salesreturnqty;

$month3actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$next2month."-%') 
	AND arocrm_inventoryproductrel.productid = '".$frow['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month3salerow = mysql_fetch_array($month3actualsale);
	$month3saleqty = $month3salerow['quantity'];
	$month3actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$next2month."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$next2month."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$next2month."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$frow['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month3salesreturnrow = mysql_fetch_array($month3actualreturnsql);
$month3salesreturnqty = $month3salesreturnrow['qty'];
$month3actualqty = $month3saleqty - $month3salesreturnqty;

$fourw .= '
<tr id="4_Wheeler_Battery__row_'.$f.'" class="tr_clone">
<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products">
<div class="input-group">
<input name="cf_3512_'.$f.'" type="hidden" value="'.$frow['productid'].'" class="sourceField" data-displayvalue="">
<input id="cf_3512_display_'.$f.'" style="min-width:280px;" name="cf_3512_display_'.$f.'" data-fieldname="cf_3512" data-fieldtype="reference"
type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input"
value="'.$frow['productname'].'" readonly placeholder="Type to search" autocomplete="off">
</div>
</div>
</td>


<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3514_'.$f.'" readonly type="text" data-fieldname="cf_3514" data-fieldtype="string"
 class="inputElement " name="cf_3514_'.$f.'" value="'.$frow['product_no'].'">
</td>


<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3528_'.$f.'" readonly type="text" data-fieldname="cf_3528" data-fieldtype="string"
 class="inputElement " name="cf_3528_'.$f.'" value="'.$frow['usageunit'].'">
</td>


<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_4993_'.$f.'" readonly type="text" style="min-width:110px;" data-fieldname="cf_4993" data-fieldtype="string" class="inputElement " name="cf_4993_'.$f.'" value="'.$frow['cf_3446'].'">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3516_'.$f.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3516_'.$f.'" value="">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3518_'.$f.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3518_'.$f.'" value="'.$month1actualqty.'">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3520_'.$f.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3520_'.$f.'" value="">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3522_'.$f.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3522_'.$f.'" value="'.$month2actualqty.'">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3524_'.$f.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3524_'.$f.'" value="">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_3526_'.$f.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3526_'.$f.'" value="'.$month3actualqty.'">
</td>

</tr>';

array_push($fourwcount, $f);
$f++;
}

$response['fourwcount'] = implode(',',$fourwcount);
$response['fourw'] = $fourw;



$twow = '';
$twowcount = array();
$t = 1;

while($tworw = mysql_fetch_array($twowbsql)){
	$month1actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$curmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$tworw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month1salerow = mysql_fetch_array($month1actualsale);
	$month1saleqty = $month1salerow['quantity'];
	$month1actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$curmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$tworw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month1salesreturnrow = mysql_fetch_array($month1actualreturnsql);
$month1salesreturnqty = $month1salesreturnrow['qty'];
$month1actualqty = $month1saleqty - $month1salesreturnqty;

$month2actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$nextmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$tworw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month2salerow = mysql_fetch_array($month2actualsale);
	$month2saleqty = $month2salerow['quantity'];
	$month2actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$nextmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$tworw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month2salesreturnrow = mysql_fetch_array($month2actualreturnsql);
$month2salesreturnqty = $month2salesreturnrow['qty'];
$month2actualqty = $month2saleqty - $month2salesreturnqty;

$month3actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$next2month."-%') 
	AND arocrm_inventoryproductrel.productid = '".$tworw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month3salerow = mysql_fetch_array($month3actualsale);
	$month3saleqty = $month3salerow['quantity'];
	$month3actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$next2month."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$next2month."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$next2month."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$tworw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month3salesreturnrow = mysql_fetch_array($month3actualreturnsql);
$month3salesreturnqty = $month3salesreturnrow['qty'];
$month3actualqty = $month3saleqty - $month3salesreturnqty;
$twow .= '<tr id="2_Wheeler_Battery__row_'.$t.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>
<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$t.'">
<div class="input-group">
<input name="cf_3530_'.$t.'" type="hidden" value="'.$tworw['productid'].'" class="sourceField" data-displayvalue="" id="cf_3530_'.$t.'">
<input id="cf_3530_display_'.$t.'" style="min-width:280px;" name="cf_3530_display_'.$t.'"  readonly data-fieldname="cf_3530" data-fieldtype="reference" type="text"
 class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$tworw['productname'].'"
 placeholder="Type to search" autocomplete="off">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_3532_'.$t.'" type="text" data-fieldname="cf_3532" data-fieldtype="string" value="'.$tworw['product_no'].'" readonly class="inputElement " name="cf_3532_'.$t.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3534_'.$t.'" type="text" data-fieldname="cf_3534" data-fieldtype="string" value="'.$tworw['usageunit'].'" readonly class="inputElement " name="cf_3534_'.$t.'" value="">
</td>


<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_4995_'.$t.'" type="text" readonly style="min-width:110px;" data-fieldname="cf_4995" data-fieldtype="string" class="inputElement " name="cf_4995_'.$t.'" value="'.$tworw['cf_3446'].'">
</td>


<td class="fieldValue">
<input id="cf_3536_'.$t.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3536_'.$t.'" value="">
</td>


<td class="fieldValue">
<input id="cf_3538_'.$t.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3538_'.$t.'" value="'.$month1actualqty.'">
</td>

<td class="fieldValue">
<input id="cf_3540_'.$t.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3540_'.$t.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3542_'.$t.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3542_'.$t.'" value="'.$month2actualqty.'">
</td>

<td class="fieldValue">
<input id="cf_3544_'.$t.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3544_'.$t.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3546_'.$t.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3546_'.$t.'" value="'.$month3actualqty.'">
</td>

</tr>';

array_push($twowcount, $t);
$t++;
}

$response['twowcount'] = implode(',',$twowcount);
$response['twow'] = $twow;



$ibw = '';
$ibwcount = array();
$i = 1;

while($ibrw = mysql_fetch_array($ibsql)){
	$month1actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$curmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$ibrw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month1salerow = mysql_fetch_array($month1actualsale);
	$month1saleqty = $month1salerow['quantity'];
	$month1actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$curmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$ibrw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month1salesreturnrow = mysql_fetch_array($month1actualreturnsql);
$month1salesreturnqty = $month1salesreturnrow['qty'];
$month1actualqty = $month1saleqty - $month1salesreturnqty;

$month2actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$nextmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$ibrw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month2salerow = mysql_fetch_array($month2actualsale);
	$month2saleqty = $month2salerow['quantity'];
	$month2actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$nextmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$ibrw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month2salesreturnrow = mysql_fetch_array($month2actualreturnsql);
$month2salesreturnqty = $month2salesreturnrow['qty'];
$month2actualqty = $month2saleqty - $month2salesreturnqty;

$month3actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$next2month."-%') 
	AND arocrm_inventoryproductrel.productid = '".$ibrw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month3salerow = mysql_fetch_array($month3actualsale);
	$month3saleqty = $month3salerow['quantity'];
	$month3actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$next2month."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$next2month."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$next2month."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$ibrw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month3salesreturnrow = mysql_fetch_array($month3actualreturnsql);
$month3salesreturnqty = $month3salesreturnrow['qty'];
$month3actualqty = $month3saleqty - $month3salesreturnqty;
$ibw .= '
<tr id="Inverter_Battery__row_'.$i.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_3568_'.$i.'" type="hidden" value="'.$ibrw['productid'].'" class="sourceField" data-displayvalue="" id="cf_3568_'.$i.'">
<input id="cf_3568_display_'.$i.'" name="cf_3568_display_'.$i.'" data-fieldname="cf_3568" data-fieldtype="reference" type="text"
class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$ibrw['productname'].'" readonly
placeholder="Type to search" autocomplete="off">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_3570_'.$i.'" type="text" data-fieldname="cf_3570" value="'.$ibrw['product_no'].'" data-fieldtype="string" readonly class="inputElement " name="cf_3570_'.$i.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3572_'.$i.'" type="text" data-fieldname="cf_3572" value="'.$ibrw['usageunit'].'" data-fieldtype="string" readonly class="inputElement " name="cf_3572_'.$i.'" value="">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_4997_'.$i.'" readonly type="text" style="min-width:110px;" data-fieldname="cf_4997" data-fieldtype="string" class="inputElement " name="cf_4997_'.$i.'" value="'.$ibrw['cf_3446'].'">
</td>

<td class="fieldValue">
<input id="cf_3574_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3574_'.$i.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3576_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3576_'.$i.'" value="'.$month1actualqty.'">
</td>

<td class="fieldValue">
<input id="cf_3578_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3578_'.$i.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3580_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3580_'.$i.'" value="'.$month2actualqty.'">
</td>

<td class="fieldValue">
<input id="cf_3582_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3582_'.$i.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3584_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3584_'.$i.'" value="'.$month3actualqty.'">
</td>

</tr>
';

array_push($ibwcount, $i);
$i++;
}

$response['ibwcount'] = implode(',',$ibwcount);
$response['ibw'] = $ibw;


$erw = '';
$erwcount = array();
$e = 1;

while($errw = mysql_fetch_array($ersql)){
	$month1actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$curmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$errw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month1salerow = mysql_fetch_array($month1actualsale);
	$month1saleqty = $month1salerow['quantity'];
	$month1actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$curmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$curmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$errw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month1salesreturnrow = mysql_fetch_array($month1actualreturnsql);
$month1salesreturnqty = $month1salesreturnrow['qty'];
$month1actualqty = $month1saleqty - $month1salesreturnqty;

$month2actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$nextmonth."-%') 
	AND arocrm_inventoryproductrel.productid = '".$errw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month2salerow = mysql_fetch_array($month2actualsale);
	$month2saleqty = $month2salerow['quantity'];
	$month2actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$nextmonth."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$nextmonth."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$errw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month2salesreturnrow = mysql_fetch_array($month2actualreturnsql);
$month2salesreturnqty = $month2salesreturnrow['qty'];
$month2actualqty = $month2saleqty - $month2salesreturnqty;

$month3actualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
	(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
	INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.cf_nrl_plantmaster164_id = '".$branchid."' AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-".$next2month."-%') 
	AND arocrm_inventoryproductrel.productid = '".$errw['productid']."' GROUP BY arocrm_inventoryproductrel.productid");
	$month3salerow = mysql_fetch_array($month3actualsale);
	$month3saleqty = $month3salerow['quantity'];
	$month3actualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-".$next2month."-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-".$next2month."-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_plantmaster177_id = '".$branchid."' AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-".$next2month."-%')) 
AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 = '".$errw['productid']."' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
$month3salesreturnrow = mysql_fetch_array($month3actualreturnsql);
$month3salesreturnqty = $month3salesreturnrow['qty'];
$month3actualqty = $month3saleqty - $month3salesreturnqty;
$erw .= '
<tr id="E-Rickshaw_Battery__row_'.$e.'" class="tr_clone">

<td>
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_1">
<div class="input-group">
<input name="cf_3548_'.$e.'" type="hidden" value="'.$errw['productid'].'" class="sourceField" data-displayvalue="" id="cf_3548_'.$e.'">
<input id="cf_3548_display_'.$e.'" name="cf_3548_display_'.$e.'" data-fieldname="cf_3548" data-fieldtype="reference" type="text"
class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$errw['productname'].'" readonly placeholder="Type to search" autocomplete="off">
</div>
</div>
</td>


<td class="fieldValue">
<input id="cf_3550_'.$e.'" type="text" data-fieldname="cf_3550" data-fieldtype="string" readonly class="inputElement " name="cf_3550_'.$e.'"
 value="'.$errw['product_no'].'">
</td>

<td class="fieldValue">
<input id="cf_3552_'.$e.'" type="text" data-fieldname="cf_3552" data-fieldtype="string" readonly class="inputElement " name="cf_3552_'.$e.'"
 value="'.$errw['usageunit'].'">
</td>

<td class="fieldValue">
<input id="SalesPlan_editView_fieldName_cf_4999_'.$e.'" readonly type="text" style="min-width:110px;" data-fieldname="cf_4999" data-fieldtype="string" class="inputElement " name="cf_4999_'.$e.'" value="'.$errw['cf_3446'].'">

</td>


<td class="fieldValue">
<input id="cf_3556_'.$e.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3556_'.$e.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3558_'.$e.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3558_'.$e.'" value="'.$month1actualqty.'">
</td>

<td class="fieldValue">
<input id="cf_3560_'.$e.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3560_'.$e.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3562_'.$e.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3562_'.$e.'" value="'.$month2actualqty.'">
</td>

<td class="fieldValue">
<input id="cf_3564_'.$e.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3564_'.$e.'" value="">
</td>

<td class="fieldValue">
<input id="cf_3566_'.$e.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3566_'.$e.'" value="'.$month3actualqty.'">
</td>

</tr>
';

array_push($erwcount, $e);
$e++;
}

$response['erwcount'] = implode(',',$erwcount);
$response['erw'] = $erw;

return $response;
}



function getPlantCodeforPlantID($id){
$response = array();
$plantsql = mysql_fetch_array(mysql_query("SELECT `arocrm_plantmaster`.`plantmasterno` FROM `arocrm_plantmaster`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_plantmaster`.`plantmasterid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_plantmaster`.`plantmasterid` = '".$id."'"));
$response['plantcode'] = $plantsql['plantmasterno'];
return $response;
}

function getSODetailsforSOReturn($id){
$response = array();
$html = '';
$invchk = mysql_query("SELECT arocrm_salesreturn.* FROM arocrm_salesreturn 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturn.cf_nrl_invoice621_id = '".$id."'");
$invrow = mysql_num_rows($invchk);
if($invrow > 0)
{
	$response['msg'] = "Sales Return already done using this Invoice";
}
else
{
/*$custsql = mysql_fetch_array(mysql_query("SELECT `arocrm_salesorder`.*, `arocrm_crmentity`.* FROM `arocrm_salesorder`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_salesorder`.`salesorderid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_salesorder`.`salesorderid` = (SELECT `arocrm_invoice`.`salesorderid` FROM `arocrm_invoice` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_invoice`.`invoiceid` WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_invoice`.`invoiceid` = '".$id."') AND `arocrm_salesorder`.`sostatus` = 'Approved'"));*/
$custsql = mysql_fetch_array(mysql_query("SELECT arocrm_invoice.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$id."' AND arocrm_invoice.invoicestatus = 'Approved'"));
$response['assignedto'] = $custsql['smownerid'];
$response['custid'] = $custsql['accountid'];
$custnamesql = mysql_fetch_array(mysql_query("SELECT `arocrm_account`.`accountname` FROM `arocrm_account`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_account`.`accountid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_account`.`accountid` = '".$response['custid']."'"));
$response['custname'] = $custnamesql['accountname'];

$response['soid'] = $custsql['salesorderid'];
$sosql = mysql_fetch_array(mysql_query("SELECT arocrm_salesorder.* FROM arocrm_salesorder INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = '0' AND arocrm_salesorder.salesorderid = '".$response['soid']."' AND arocrm_salesorder.sostatus = 'Approved'"));
$response['soname'] = $sosql['subject'];

$response['plantid'] = $custsql['cf_nrl_plantmaster164_id'];
$plantnamesql = mysql_fetch_array(mysql_query("SELECT `arocrm_plantmaster`.`name` FROM `arocrm_plantmaster`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_plantmaster`.`plantmasterid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_plantmaster`.`plantmasterid` = '".$response['plantid']."'"));
$response['plantname'] = $plantnamesql['name'];

$sqlitem = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$id."'");
$i = 1;
$rcount = mysql_num_rows($sqlitem);
$dis = '';
if($rcount==1){
  $dis = 'style="display:none;"';
}
$gprice = 0;
while($row = mysql_fetch_array($sqlitem)){

  $productid = $row['productid'];
  $product_array = getProductDetails($productid);
  $productname = $product_array['productname'];
  $productcode = $product_array['productcode'];
  $unit = $product_array['unit'];

  $prevsql = "SELECT SUM(`arocrm_salesreturn_line_item_lineitem`.`cf_3274`) as qty,`arocrm_salesreturn_line_item_lineitem`.`cf_3268` FROM `arocrm_salesreturn`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_salesreturn`.`salesreturnid`
  INNER JOIN `arocrm_salesreturn_line_item_lineitem` ON `arocrm_salesreturn_line_item_lineitem`.`salesreturnid` = `arocrm_salesreturn`.`salesreturnid`
  INNER JOIN `arocrm_salesreturncf` ON `arocrm_salesreturncf`.`salesreturnid` = `arocrm_salesreturn`.`salesreturnid`
  WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_salesreturn`.`cf_nrl_salesorder922_id` = (SELECT `arocrm_invoice`.`salesorderid` FROM `arocrm_invoice` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_invoice`.`invoiceid` WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_invoice`.`invoiceid` = '".$id."') AND `arocrm_salesreturn_line_item_lineitem`.`cf_3268` = '".$productid."'
  GROUP BY `arocrm_salesreturn_line_item_lineitem`.`cf_3268`";
  $prev = mysql_fetch_array(mysql_query($prevsql));

  $qty1 =  number_format((float)$row['quantity'], 2, '.', '');
  $qty = $qty1 - $prev['qty'];
  $listprice = number_format((float)$row['listprice'], 2, '.', '');
  $totalprice = number_format((float)$listprice * $qty, 2, '.', '');
if($qty > 0){
$html .= '
<tr id="Line_Item__row_'.$i.'" class="tr_clone">

<td>
<i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" '.$dis.'></i>&nbsp;
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_3268_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_3268_'.$i.'">
<input id="cf_3268_display_'.$i.'" name="cf_3268_display_'.$i.'" data-fieldname="cf_3268" data-fieldtype="reference"
type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'"
 autocomplete="off" />
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_3270_'.$i.'" type="text" data-fieldname="cf_3270" data-fieldtype="string" class="inputElement "
name="cf_3270_'.$i.'" value="'.$productcode.'" readonly />
</td>

<td class="fieldValue">
<input id="cf_3272_'.$i.'" type="text" data-fieldname="cf_3272" data-fieldtype="string" class="inputElement "
name="cf_3272_'.$i.'" value="'.$unit.'"  readonly />
</td>

<td class="fieldValue">
<input id="cf_3274_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3274_'.$i.'" value="'.$qty.'" max="'.$qty.'" min="1" />
</td>

<td class="fieldValue">
<input id="cf_3276_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_3276_'.$i.'" value="'.$listprice.'">
</td>

<td class="fieldValue">
<input id="cf_3278_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_3278_'.$i.'" value="'.$totalprice.'">
</td>

</tr>';


$gprice = (float)$gprice + (float)$totalprice;
$seqcnt = $seqcnt.','.$i;
$i++;
}

}

$seqc = ltrim($seqcnt,",");
$response['rowcount'] = $seqc;
$response['grandtotal'] = number_format((float)$gprice, 2, '.', '');
$response['message'] = $html;
$response['msg'] = "";
}
return $response;
}

function getPopulatedSerialNumber($id,$products,$plant,$storageloc,$serialnos,$totalno)
{
$response = array();
$sr = explode(",",$serialnos);
$html = '<table class="table table-bordered table-stripped" style="overflow:scroll;">
<thead><tr><th>#</th><th>EAPL Serial Number</th><th>Vendor Serial Number</th></thead><tbody>';
$sql = "SELECT `arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_serialnumber`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_serialnumber`.`serialnumberid`
INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumber`.`serialnumberid`=`arocrm_serialnumbercf`.`serialnumberid`
WHERE `arocrm_crmentity`.`deleted` = '0' 
AND `arocrm_serialnumber`.`cf_nrl_plantmaster496_id` = '".$plant."' 
AND `arocrm_serialnumber`.`cf_nrl_storagelocation106_id` = '".$storageloc."'  
AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$products."'  
AND `arocrm_serialnumbercf`.`cf_1256` = 'R' 
AND `arocrm_serialnumbercf`.`cf_2834` = '1'";
$sqldata = mysql_query($sql);
$i = 1;
$chck = "";
while($row = mysql_fetch_array($sqldata)){

if (in_array($row['cf_1258'],$sr))
  {
  $chck = "checked='true'";
  }else{
	$chck = "";
  }

$html .= '<tr><td><input type="checkbox" '.$chck.' class="serialchk" id="serial__'.$i.'" value="'.$row['cf_1258'].'" /></td><td>'.$row['cf_1258'].'</td><td>'.$row['cf_1260'].'</td></tr>';

$i++;

}
$html .= '</tbody></table>
<input type="hidden" id="trows'.$i.'" value="'.$totalno.'" />
';
$response['message'] = $html;
return $response;
}


function getPopulatedTextBoxSerialNumber($id,$totalno,$serialnos){
$response = array();
$html = '<div class="col-md-12">';
$dst = explode(",",$serialnos);
$j = 0;
for($i = 0;$i<$totalno;$i++){
$j = $i + 1;
$auto = "";
if($i == 0){
$auto = 'autofocus';
}
$html .= '
<div class="input-group inputElement col-md-4" style="float:left !important;width: 50% !important;">
<label>Serial No. '.$j.'</label><br>
<input id="serialno_'.$id.'_'.$i.'" type="text" '.$auto.' class="form-control" style="background:#dee2e2;" value="'.$dst[$i].'" name="serialno_'.$id.'_'.$i.'" />
</div>';
}
$html .= '
<input type="hidden" id="tscount_'.$id.'" value="'.$j.'" />
</div>';
$response['message']  = $html;

return $response;
}

function getDetailsOBDforGI($id){


  $response = array();
  $html = '';
  $savestatestatus = 1;
  $sqlref = mysql_fetch_array(mysql_query("SELECT `arocrm_crmentity`.*,`arocrm_outbounddelivery`.*,`arocrm_outbounddeliverycf`.* FROM `arocrm_outbounddelivery`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."' AND `arocrm_outbounddeliverycf`.`cf_4826` = 'Approved'"));
  $ref = $sqlref['cf_3067'];


  if($ref=='With respect to Assembly Order'){



    $sqlibd = "SELECT `arocrm_crmentity`.*,`arocrm_outbounddelivery`.*,`arocrm_outbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_outbounddelivery`
    INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3387` = `arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
    WHERE arocrm_crmentity.deleted = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'
    AND `arocrm_serialnumbercf`.`cf_3084` = 'R'";
    $queryexe = mysql_query($sqlibd);
    $ros = mysql_fetch_array($queryexe);

    $sqlplant = mysql_fetch_array(mysql_query("select * from `arocrm_plantmaster` where `plantmasterid` = '".$ros['cf_nrl_plantmaster625_id']."'"));

    $response['plantid'] = $ros['cf_nrl_plantmaster625_id'];
    $response['plantname'] = $sqlplant['name'];

    $response['vehicleno'] = $ros['cf_1982'];
    $response['modeoftransfer'] = $ros['cf_1960'];

    $tmpdate1 = explode("-",$ros['cf_3225']);
    $response['obddate'] = $tmpdate1[2]."-".$tmpdate1[1]."-".$tmpdate1[0];

    $sql = "SELECT count(*) as qty,`arocrm_serialnumber`.`cf_nrl_products16_id` FROM `arocrm_outbounddelivery`
    INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3387` = `arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
    WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'
    AND `arocrm_serialnumbercf`.`cf_3084` = 'R'
    AND `arocrm_serialnumbercf`.`cf_2834` = '1' GROUP BY `arocrm_serialnumber`.`cf_nrl_products16_id`";
    $query = mysql_query($sql);
    $dis = '';
    $nomx = mysql_num_rows($query);

    $i = 1;
    $seqcnt = '';
    $gprice = 0;
    while($row = mysql_fetch_array($query)){

    $productid = $row['cf_nrl_products16_id'];
    $product_array = getProductDetails($productid);
    $productname = $product_array['productname'];
    $productcode = $product_array['productcode'];
    $unit = $product_array['unit'];
    $eaplserial = '';
    $vserial = '';

    $ore =   "SELECT `arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_serialnumber`
    INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_serialnumber`.`serialnumberid`
    INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`serialnumberid` = `arocrm_serialnumber`.`serialnumberid`
    WHERE arocrm_crmentity.deleted = '0' AND `arocrm_serialnumbercf`.`cf_3387` = '".$id."'
    AND `arocrm_serialnumbercf`.`cf_3084` = 'R' AND `arocrm_serialnumbercf`.`cf_2834` = '1'
    AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$productid."'";


    $sqlio = mysql_query($ore);

    while($sqlir = mysql_fetch_array($sqlio)){
      $eaplserial = $eaplserial.','.$sqlir['cf_1258'];
      $vserial = $vserial.','.$sqlir['cf_1260'];
    }

    $eaplserial = ltrim($eaplserial,",");
    $vserial = ltrim($vserial,",");


    $sqld = "SELECT `arocrm_outbounddelivery_line_item_lineitem`.*,`arocrm_outbounddelivery`.*,`arocrm_outbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_outbounddelivery`
    INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_outbounddelivery_line_item_lineitem` ON `arocrm_outbounddelivery_line_item_lineitem`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3387` = `arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
    WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'
    AND `arocrm_serialnumbercf`.`cf_3084` = 'R'
    AND `arocrm_serialnumbercf`.`cf_2834` = '1'
    AND `arocrm_outbounddelivery_line_item_lineitem`.`cf_2006` = '".$productid."'";
    $mysqld = mysql_fetch_array(mysql_query($sqld));
    $unitprice = number_format((float)$mysqld['cf_2020'], 2, '.', '');

    $totalprice = $unitprice * $row['qty'];
    $storageid = $mysqld['cf_2010'];
    $storagesl = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_storagelocation` WHERE `storagelocationid` = '".$storageid."'"));
    $storagename = trim($storagesl['name']);

    $html .= '
    <tr id="Line_Item__row_'.$i.'" class="tr_clone">
    <td>
    <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
    </td>

    <td class="fieldValue">
    <div class="referencefield-wrapper">
    <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
    <div class="input-group">
    <input name="cf_3163_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_3163_'.$i.'">
    <input id="cf_3163_display_'.$i.'" name="cf_3163_display_'.$i.'" data-fieldname="cf_3163" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'" placeholder="Type to search" autocomplete="off">
    </div>
    </div>
    </td>

    <td class="fieldValue">
    <input id="cf_3165_'.$i.'" type="text" style="min-width:120px;" data-fieldname="cf_3165" data-fieldtype="string"
    class="inputElement " name="cf_3165_'.$i.'" readonly value="'.$productcode.'">
    </td>

    <td class="fieldValue">
    <input id="cf_3167_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_3167" data-fieldtype="string"
    class="inputElement " name="cf_3167_'.$i.'" readonly value="'.$unit.'">
    </td>

   

    <td class="fieldValue">
    <input id="cf_3171_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="number"
    class="inputElement" step="0.01" name="cf_3171_'.$i.'" readonly value="'.$row['qty'].'">
    </td>

    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
    <div class="input-group">
    <input name="cf_3173_'.$i.'" type="hidden" value="'.$storageid.'" class="sourceField" data-displayvalue="" id="cf_3173_'.$i.'">
    <input id="cf_3173_display_'.$i.'" name="cf_3173_display_'.$i.'"
    data-fieldname="cf_3173" data-fieldtype="reference" type="text"
    class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$storagename.'" placeholder="Type to search" autocomplete="off">
    </div>
    </div>
    
    <input id="cf_3175_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="hidden"
    class="inputElement" step="0.01" name="cf_3175_'.$i.'" readonly value="'.$unitprice.'">
 
    <input id="cf_3177_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="hidden" class="inputElement" step="0.01"
    name="cf_3177_'.$i.'" readonly value="'.$totalprice.'">
    </td>

    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_3179_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3179_'.$i.'">'.$eaplserial.'</textarea>
    </td>

    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_3181_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3181_'.$i.'">'.$vserial.'</textarea>
    </td>


    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_3183_'.$i.'" style="min-width:150px;" class="inputElement" name="cf_3183_'.$i.'"></textarea>
    </td>

   
    </tr>';
    
    $seqcnt = $seqcnt.','.$i;
    $i++;
    }



  }else if($ref=='With Respect to Purchase Return'){

    $sqlibd = "SELECT * FROM `arocrm_outbounddelivery`
    INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
    WHERE arocrm_crmentity.deleted = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'";
    $queryexe = mysql_query($sqlibd);
    $ros = mysql_fetch_array($queryexe);

    $sqlplant = mysql_fetch_array(mysql_query("select * from `arocrm_plantmaster` where `plantmasterid` = '".$ros['cf_nrl_plantmaster625_id']."'"));

    $response['plantid'] = $ros['cf_nrl_plantmaster625_id'];
    $response['plantname'] = $sqlplant['name'];

    $response['vehicleno'] = $ros['cf_1982'];
    $response['modeoftransfer'] = $ros['cf_1960'];

 
    $tmpdate1 = explode("-",$ros['cf_3225']);
    $response['obddate'] = $tmpdate1[2]."-".$tmpdate1[1]."-".$tmpdate1[0];

    $sql = "SELECT * FROM `arocrm_outbounddelivery_line_item_lineitem` WHERE `outbounddeliveryid` = '".$id."'";
    $query = mysql_query($sql);
    $dis = '';
    $nomx = mysql_num_rows($query);

    $i = 1;
    $seqcnt = '';
    $gprice = 0;
    while($row = mysql_fetch_array($query)){

    $productid = $row['cf_2006'];
    $product_array = getProductDetails($productid);
    $productname = $product_array['productname'];
    $productcode = $product_array['productcode'];
    $unit = $product_array['unit'];
    

   
    $eaplserial = $row['cf_3076'];
    $vserial = $row['cf_3078'];

    $unitprice = number_format((float)$row['cf_2020'], 2, '.', '');

    $totalprice = $unitprice * $row['cf_2014'];
    
	$serialnotmp = explode(",",$eaplserial);
	$serialid = $serialnotmp[0];
    $storagesl = mysql_fetch_array(mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation` 
	INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`cf_nrl_storagelocation106_id` = `arocrm_storagelocation`.`storagelocationid`
    WHERE `arocrm_serialnumber`.`cf_nrl_plantmaster496_id` = '".$ros['cf_nrl_plantmaster625_id']."' AND `arocrm_serialnumber`.`name` = '".$serialid."'"));
    $storagename = trim($storagesl['name']);
	$storageid = trim($storagesl['storagelocationid']);

    $html .= '
    <tr id="Line_Item__row_'.$i.'" class="tr_clone">
    <td>
    <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
    </td>

	
  <td class="fieldValue">
  <div class="referencefield-wrapper">
  <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
  <div class="input-group">
  <input name="cf_3163_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_3163_'.$i.'">
  <input id="cf_3163_display_'.$i.'" name="cf_3163_display_'.$i.'" data-fieldname="cf_3163" data-fieldtype="reference"
  type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'" placeholder="Type to search" autocomplete="off">
  </div>
  </div>
  </td>

  <td class="fieldValue">
  <input id="cf_3165_'.$i.'" type="text" style="min-width:120px;" data-fieldname="cf_3165" data-fieldtype="string"
  class="inputElement " name="cf_3165_'.$i.'" readonly value="'.$productcode.'">
  </td>

  <td class="fieldValue">
  <input id="cf_3167_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_3167" data-fieldtype="string"
  class="inputElement " name="cf_3167_'.$i.'" readonly value="'.$unit.'">
  </td>



  <td class="fieldValue">
  <input id="cf_3171_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="number"
   class="inputElement" step="0.01" name="cf_3171_'.$i.'" readonly value="'.$row['cf_2014'].'">
  </td>

  <td class="fieldValue">
  <div class="referencefield-wrapper ">
  <input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
  <div class="input-group">
  <input name="cf_3173_'.$i.'" type="hidden" value="'.$storageid.'" class="sourceField" data-displayvalue="" id="cf_3173_'.$i.'">
  <input id="cf_3173_display_'.$i.'" name="cf_3173_display_'.$i.'"
  data-fieldname="cf_3173" data-fieldtype="reference" type="text"
  class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$storagename.'" placeholder="Type to search" autocomplete="off">
  </div>
  </div>
  
  <input id="cf_3175_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="hidden"
  class="inputElement" step="0.01" name="cf_3175_'.$i.'" readonly value="'.$unitprice.'">
 
  <input id="cf_3177_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="hidden" class="inputElement" step="0.01"
  name="cf_3177_'.$i.'" readonly value="'.$totalprice.'">
  </td>

  <td class="fieldValue">
  <textarea rows="6" cols="8" id="cf_3179_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3179_'.$i.'">'.$eaplserial.'</textarea>
  </td>

  <td class="fieldValue">
  <textarea rows="6" cols="8" id="cf_3181_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3181_'.$i.'">'.$vserial.'</textarea>
  </td>


  <td class="fieldValue">
  <textarea rows="6" cols="8" id="cf_3183_'.$i.'" style="min-width:150px;" class="inputElement" name="cf_3183_'.$i.'"></textarea>
  </td>

  </tr>';

    $seqcnt = $seqcnt.','.$i;
    $i++;
    }



  }else{
  $savestatestatus = 0;
  $sqlibd = "SELECT `arocrm_crmentity`.*,`arocrm_outbounddelivery`.*,`arocrm_outbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_outbounddelivery`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3128` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'
  ";

  $queryexe = mysql_query($sqlibd);
  $ros = mysql_fetch_array($queryexe);

  $sqlcstdata = mysql_query("SELECT * FROM `arocrm_account`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_account`.`accountid`
  INNER JOIN `arocrm_accountscf` ON `arocrm_accountscf`.`accountid` = `arocrm_account`.`accountid`
  WHERE `arocrm_account`.`accountid` = (SELECT `accountid` FROM `arocrm_salesorder` WHERE `salesorderid` = (SELECT `cf_nrl_salesorder679_id` FROM `arocrm_outbounddelivery` WHERE `outbounddeliveryid` = '".$id."')) AND `arocrm_crmentity`.`deleted` = '0'");
  $sqlcstdataarr = mysql_fetch_array($sqlcstdata);

  $creditlimit = $sqlcstdataarr['cf_4313'];
  $creditdays = $sqlcstdataarr['cf_4315'];
  
  if($creditlimit > 0 && $creditdays > 0){
  $savestatestatus = 1;
  }
  
  if($ros['cf_3067']=='With Respect to STPO'){
	$savestatestatus = 1;  
  }


    $sqlplant = mysql_fetch_array(mysql_query("select * from `arocrm_plantmaster` where `plantmasterid` = '".$ros['cf_nrl_plantmaster625_id']."'"));

    $response['plantid'] = $ros['cf_nrl_plantmaster625_id'];
    $response['plantname'] = $sqlplant['name'];

    $response['vehicleno'] = $ros['cf_1982'];
    $response['modeoftransfer'] = $ros['cf_1960'];

    $tmpdate1 = explode("-",$ros['cf_3225']);
    $response['obddate'] = $tmpdate1[2]."-".$tmpdate1[1]."-".$tmpdate1[0];


  $dsql = mysql_fetch_array(mysql_query("SELECT `accountid` FROM `arocrm_salesorder` WHERE `salesorderid` = (SELECT `cf_nrl_salesorder679_id` FROM `arocrm_outbounddelivery` WHERE `outbounddeliveryid` = '".$id."')"));
  $response['custid'] = $dsql['accountid'];
  $custfetchname = mysql_fetch_array(mysql_query("SELECT `accountname` FROM `arocrm_account` WHERE `accountid` = '".$dsql['accountid']."'"));
  $response['custname'] = $custfetchname['accountname'];

  $sql = "SELECT count(*) as qty,`arocrm_serialnumber`.`cf_nrl_products16_id` FROM `arocrm_outbounddelivery`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3128` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'
  AND `arocrm_serialnumbercf`.`cf_2834` = '1' GROUP BY `arocrm_serialnumber`.`cf_nrl_products16_id`";
  $query = mysql_query($sql);
  $dis = '';
  $nomx = mysql_num_rows($query);
  if($nomx==1){
  $dis = 'style="display:none;"';
  }
  $i = 1;
  $seqcnt = '';
  $gprice = 0;
  while($row = mysql_fetch_array($query)){

  $productid = $row['cf_nrl_products16_id'];
  $product_array = getProductDetails($productid);
  $productname = $product_array['productname'];
  $productcode = $product_array['productcode'];
  $unit = $product_array['unit'];
  $eaplserial = '';
  $vserial = '';

  $ore =   "SELECT `arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_serialnumber`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_serialnumber`.`serialnumberid`
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`serialnumberid` = `arocrm_serialnumber`.`serialnumberid`
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_serialnumbercf`.`cf_3128` = '".$id."'
  AND `arocrm_serialnumbercf`.`cf_2834` = '1'
  AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$productid."'";


  $sqlio = mysql_query($ore);

  while($sqlir = mysql_fetch_array($sqlio)){
  	$eaplserial = $eaplserial.','.$sqlir['cf_1258'];
  	$vserial = $vserial.','.$sqlir['cf_1260'];
  }

  $eaplserial = ltrim($eaplserial,",");
  $vserial = ltrim($vserial,",");


  $sqldff = mysql_query("SELECT * FROM `arocrm_outbounddelivery`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_outbounddelivery_line_item_lineitem` ON `arocrm_outbounddelivery_line_item_lineitem`.`outbounddeliveryid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3128` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."'
  AND `arocrm_serialnumbercf`.`cf_2834` = '1'  AND `arocrm_outbounddelivery_line_item_lineitem`.`cf_2006` = '".$productid."'");
  $mysqldff = mysql_fetch_array($sqldff);
  $unitprice = number_format((float)$mysqldff['cf_2020'], 2, '.', '');

  $totalprice = $unitprice * $row['qty'];
  $storageid = $mysqldff['cf_2010'];
  
   if($ros['cf_3067']=='With Respect to STPO'){
	 $plantid = $mysqldff['cf_nrl_plantmaster574_id'];
  }else{
	   $plantid = $mysqldff['cf_nrl_plantmaster625_id'];
  }
 
   $storcode = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_storagelocation` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid` WHERE `arocrm_storagelocation`.`name` LIKE '%Quarantine%' AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."' AND `arocrm_crmentity`.`deleted` = 0"));
		
	 $storageid = $storcode['storagelocationid'];	
  $storagesl = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_storagelocation` WHERE `storagelocationid` = '".$storageid."'"));
  $storagename = trim($storagesl['name']);


  $html .= '
  <tr id="Line_Item__row_'.$i.'" class="tr_clone">
  <td>
  <i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" '.$dis.'></i>
  &nbsp;
  <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
  </td>

<td class="fieldValue">
<div class="referencefield-wrapper">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_3163_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_3163_'.$i.'">
<input id="cf_3163_display_'.$i.'" name="cf_3163_display_'.$i.'" data-fieldname="cf_3163" data-fieldtype="reference"
type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'" placeholder="Type to search" autocomplete="off" />
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_3165_'.$i.'" type="text" style="min-width:120px;" data-fieldname="cf_3165" data-fieldtype="string"
class="inputElement " name="cf_3165_'.$i.'" readonly value="'.$productcode.'" />
</td>

<td class="fieldValue">
<input id="cf_3167_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_3167" data-fieldtype="string"
class="inputElement " name="cf_3167_'.$i.'" readonly value="'.$unit.'" />
</td>

<td class="fieldValue">
<input id="cf_3171_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="number"
 class="inputElement" step="0.01" name="cf_3171_'.$i.'" readonly value="'.$row['qty'].'" />
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'" />
<div class="input-group">
<input name="cf_3173_'.$i.'" type="hidden" value="'.$storageid.'" class="sourceField" data-displayvalue="" id="cf_3173_'.$i.'" />
<input id="cf_3173_display_'.$i.'" name="cf_3173_display_'.$i.'"
data-fieldname="cf_3173" data-fieldtype="reference" type="text"
class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.trim($storagename).'" placeholder="Type to search" autocomplete="off" />
</div>
</div>

<input id="cf_3175_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="hidden"
class="inputElement" step="0.01" name="cf_3175_'.$i.'" readonly value="'.$unitprice.'" />

<input id="cf_3177_'.$i.'" style="min-width:80px;" style="min-width:80px;" type="hidden" class="inputElement" step="0.01"
name="cf_3177_'.$i.'" readonly value="'.$totalprice.'" />
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3179_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3179_'.$i.'">'.$eaplserial.'</textarea>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3181_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3181_'.$i.'">'.$vserial.'</textarea>
</td>


<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3183_'.$i.'" style="min-width:150px;" class="inputElement" name="cf_3183_'.$i.'"></textarea>
</td>


</tr>';
  
  $seqcnt = $seqcnt.','.$i;
  $i++;
  }



}
  $response['savestatestatus'] =   $savestatestatus;
  $seqc = ltrim($seqcnt,",");
  $response['rowcount'] = $seqc;
  $response['message'] = $html;
  return $response;
}

function getRPODetailsforOBD($id){

    $response = array();
    $html = '';

    $sqlibd = "SELECT * FROM `arocrm_purchasereturnorder`
     INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_purchasereturnorder`.`purchasereturnorderid`
     INNER JOIN `arocrm_purchasereturnordercf` ON `arocrm_purchasereturnordercf`.`purchasereturnorderid`=`arocrm_purchasereturnorder`.`purchasereturnorderid`
     WHERE `arocrm_crmentity`.`deleted` = '0' 
	 AND `arocrm_purchasereturnorder`.`purchasereturnorderid` = '".$id."' 
	 AND `arocrm_purchasereturnordercf`.`cf_4832` = 'Approved'";

    $queryexe = mysql_query($sqlibd);
    $ros = mysql_fetch_array($queryexe);

    $sqlplant = mysql_fetch_array(mysql_query("select * from arocrm_plantmaster where plantmasterid = '".$ros['cf_nrl_plantmaster447_id']."'"));

    $response['plantid'] = $ros['cf_nrl_plantmaster447_id'];
    $response['plantname'] = $sqlplant['name'];
	
	$storesql =  mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation`
 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_storagelocation.storagelocationid
 WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$response['plantid']."' AND `arocrm_storagelocation`.`name` like '%%Main Store%%'");
$resultstore = mysql_fetch_array($storesql);


    $sqlvendor  = mysql_fetch_array(mysql_query("select * from arocrm_vendor where vendorid = '".$ros['cf_nrl_vendors780_id']."'"));

    $response['vendorid'] = $ros['cf_nrl_vendors780_id'];
    $response['vendorname'] = $sqlvendor['vendorname'];


    $sql = "SELECT * FROM `arocrm_purchasereturnorder_line_item_lineitem` WHERE `purchasereturnorderid` = '".$id."'";


    $query = mysql_query($sql);
    $nomx = mysql_num_rows($query);
    $i = 1;
    $seqcnt = '';
    $eaplserial = '';
    $vserial = '';
    while($row = mysql_fetch_array($query)){

    $productid = $row['cf_3204'];
    $product_array = getProductDetails($productid);
    $productname = $product_array['productname'];
    $productcode = $product_array['productcode'];
    $unit = $product_array['unit'];

    $unitprices = $row['cf_3214'];

    $sqlio = mysql_query("SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.*
      FROM arocrm_inbounddelivery
      INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
      INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
      INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_1270` = `arocrm_inbounddelivery`.`inbounddeliveryid`
      INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
      WHERE arocrm_crmentity.deleted = '0'
      AND `arocrm_serialnumber`.cf_nrl_products16_id = '".$productid."'
      AND `arocrm_serialnumbercf`.`cf_2834` = '1'
      AND `arocrm_inbounddelivery`.`cf_nrl_purchaseorder573_id` =
       (SELECT `cf_nrl_purchaseorder809_id`  FROM  `arocrm_purchasereturnorder`
        INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_purchasereturnorder`.`purchasereturnorderid`
       WHERE `arocrm_crmentity`.`deleted` = '0' AND `purchasereturnorderid` = '".$id."')");

    $storeid = '';
    $storename = '';

    while($sqlir = mysql_fetch_array($sqlio)){
    	$eaplserial = $eaplserial.','.$sqlir['cf_1258'];
    	$vserial = $vserial.','.$sqlir['cf_1260'];
      $storeid = $sqlir['cf_nrl_storagelocation106_id'];
    }
      $eaplserial = ltrim($eaplserial,",");
      $vserial = ltrim($vserial,",");


      $strsql = mysql_fetch_array(mysql_query("SELECT `name` FROM `arocrm_storagelocation`
      INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid`
      WHERE `arocrm_crmentity`.`deleted` = '0' AND `storagelocationid` = '".$storeid."'"));

      $storename = $strsql['name'];


    $unitprice = number_format((float)$unitprices, 2, '.', '');
    $qty = $row['cf_3210'];
    $totalprice = $unitprice * $qty;

    $html .= '
    <tr id="Line_Item__row_'.$i.'" class="tr_clone">
    <td>
    &nbsp;
    <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag">
    </a>
    </td>

    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
    <div class="input-group">
    <input name="cf_2006_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_2006_'.$i.'">
    <input id="cf_2006_display_'.$i.'" name="cf_2006_display_'.$i.'" data-fieldname="cf_2006" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'" readonly placeholder="Type to search" autocomplete="off">
    </div>
    </div>
    </td>

    <td class="fieldValue">
    <input id="cf_2004_'.$i.'" type="text" data-fieldname="cf_2004" data-fieldtype="string" style="min-width:120px" readonly class="inputElement " name="cf_2004_'.$i.'" value="'.$productcode.'">
    </td>

    <td class="fieldValue">
    <input id="cf_2018_'.$i.'" type="text" data-fieldname="cf_2018" data-fieldtype="string"  style="min-width:100px" readonly class="inputElement " name="cf_2018_'.$i.'" value="'.$unit.'">
    </td>

    <td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2010_'.$i.'" type="hidden" value="'.$resultstore['storagelocationid'].'" class="sourceField" data-displayvalue="" id="cf_2010_'.$i.'">
<input id="cf_2010_display_'.$i.'" name="cf_2010_display_'.$i.'" data-fieldname="cf_2010" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$resultstore['name'].'" placeholder="Type to search" autocomplete="off">
</div>
</td>

    <td class="fieldValue">
    <input id="cf_2012_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_2012_'.$i.'" value="'.$qty.'" />
    </td>

    <td class="fieldValue">
    <input id="cf_2014_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" readonly name="cf_2014_'.$i.'" value="'.$qty.'" max="'.$qty.'" />
  
    <input id="cf_2020_'.$i.'" style="min-width:80px;"  type="hidden" class="inputElement" step="0.01" name="cf_2020_'.$i.'" value="'.$unitprice.'">
   
<input id="cf_4925_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" step="0.01" name="cf_4925_'.$i.'" value="'.$totalprice.'">
</td>

    <td class="fieldValue">
    <div class="input-group inputElement" style="margin-bottom: 3px">
    <input id="cf_2022_'.$i.'" type="date" class="form-control" readonly data-fieldname="cf_2022" name="cf_2022_'.$i.'" value="'.date('Y-m-d').'" data-rule-date="true">
    </div>
    </td>

    <td class="fieldValue">
    <div class="input-group inputElement" style="margin-bottom: 3px">
    <input id="cf_2026_'.$i.'" type="date" class="form-control " data-fieldname="cf_2026" name="cf_2026_'.$i.'" value="'.date('Y-m-d').'" data-rule-date="true">
    </div>
    </td>

    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_3076_'.$i.'" readonly class="inputElement " name="cf_3076_'.$i.'" style="min-width:120px">'.$eaplserial.'</textarea>
    </td>

    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_3078_'.$i.'" readonly class="inputElement " name="cf_3078_'.$i.'" style="min-width:120px">'.$vserial.'</textarea>
    </td>

    <td class="fieldValue">
    <textarea rows="6" cols="8" id="cf_2032_'.$i.'" class="inputElement " name="cf_2032_'.$i.'" style="min-width:120px"></textarea>
    </td>
    </tr>';

    $seqcnt = $seqcnt.','.$i;
    $i++;
    }
    $seqc = ltrim($seqcnt,",");
    $response['rowcount'] = $seqc;
    $response['message'] = $html;
    return $response;
}




function getINVDetailsforPReturn($id){
  $response = array();
  $html = '';

  $sqlibd = "SELECT * FROM `arocrm_invoice`
   INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_invoice`.`invoiceid`
   INNER JOIN `arocrm_invoicecf` ON `arocrm_invoicecf`.`invoiceid`=`arocrm_invoice`.`invoiceid`
   WHERE `arocrm_crmentity`.`deleted` = '0' 
   AND `arocrm_invoice`.`invoiceid` = '".$id."' 
   AND `arocrm_invoice`.`invoicestatus` NOT IN ('Paid')";

  $queryexe = mysql_query($sqlibd);
  $ros = mysql_fetch_array($queryexe);

  
  $grsqldetail = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_goodsreceipt`
   INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_goodsreceipt`.`goodsreceiptid`
   INNER JOIN `arocrm_goodsreceiptcf` ON `arocrm_goodsreceiptcf`.`goodsreceiptid`=`arocrm_goodsreceipt`.`goodsreceiptid`
   WHERE `arocrm_crmentity`.`deleted` = '0' 
  AND `arocrm_goodsreceipt`.`goodsreceiptid` = '".$ros['cf_nrl_goodsreceipt721_id']."'"));

  $response['grid'] = $ros['cf_nrl_goodsreceipt721_id'];
  $response['grname'] = $grsqldetail['name'];
  $tmp = explode("-",$grsqldetail['cf_3223']);
  $response['receiptdate'] = $tmp[2]."-".$tmp[1]."-".$tmp[0];
  
  $invsqldetail = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_inbounddelivery`
   INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   WHERE `arocrm_crmentity`.`deleted` = '0' 
  AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$grsqldetail['cf_nrl_inbounddelivery708_id']."'"));
  
  
  $response['delvid'] = $grsqldetail['cf_nrl_inbounddelivery708_id'];
  $response['delvname'] = $invsqldetail['name'];
  



  $sqlplant = mysql_fetch_array(mysql_query("select * from arocrm_plantmaster where plantmasterid = '".$ros['cf_nrl_plantmaster164_id']."'"));

  $response['plantid'] = $ros['cf_nrl_plantmaster164_id'];
  $response['plantname'] = $sqlplant['name'];

  $sqlpo = mysql_fetch_array(mysql_query("select * from arocrm_purchaseorder where purchaseorderid = '".$invsqldetail['cf_nrl_purchaseorder573_id']."'"));

  $response['poid'] = $invsqldetail['cf_nrl_purchaseorder573_id'];
  $response['poname'] = $sqlpo['subject'];

  $sqlvendor  = mysql_fetch_array(mysql_query("select * from arocrm_vendor where vendorid = '".$ros['cf_nrl_vendors752_id']."'"));

  $response['vendorid'] = $ros['cf_nrl_vendors752_id'];
  $response['vendorname'] = $sqlvendor['vendorname'];

  $response['vehicleno'] = $invsqldetail['cf_1693'];
  $response['modeoftransfer'] = $invsqldetail['cf_1639'];


  $sql = "SELECT * FROM arocrm_inventoryproductrel WHERE `id` = '".$id."'";


  $query = mysql_query($sql);
  $dis = '';
  $nomx = mysql_num_rows($query);
  if($nomx==1){
  $dis = 'style="display:none;"';
  }
  $i = 1;
  $seqcnt = '';
  while($row = mysql_fetch_array($query)){

  $productid = $row['productid'];
  $product_array = getProductDetails($productid);
  $productname = $product_array['productname'];
  $productcode = $product_array['productcode'];
  $unit = $product_array['unit'];

  $unitprices = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_inventoryproductrel` where `id` = '".$invsqldetail['cf_nrl_purchaseorder573_id']."' and `productid` = '".$productid."'"));

  $unitprice = number_format((float)$unitprices['listprice'], 2, '.', '');

  $totalprice = $unitprice * $row['quantity'];

  $html .= '

  <tr id="Line_Item__row_'.$i.'" class="tr_clone">
  <td>
  <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
  </td>

  <td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_3204_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_3204_'.$i.'">
<input id="cf_3204_display_'.$i.'" name="cf_3204_display_'.$i.'" data-fieldname="cf_3204" data-fieldtype="reference" type="text"  readonly class="marginLeftZero inputElement ui-autocomplete-input" value="'.$productname.'" placeholder="Type to search" autocomplete="off" aria-invalid="false">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_3206_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_3206" data-fieldtype="string" class="inputElement" readonly name="cf_3206_'.$i.'" value="'.$productcode.'">
</td>

  <td class="fieldValue">
  <input id="cf_3208_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_3208" data-fieldtype="string" class="inputElement" readonly name="cf_3208_'.$i.'" value="'.$unit.'">
  </td>

  <td class="fieldValue">
  <input id="cf_3210_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3210_'.$i.'" readonly value="'. $row['quantity'].'">
  </td>

  <td class="fieldValue">
  <input id="cf_3214_'.$i.'" style="min-width:80px;" type="number" readonly class="inputElement"  name="cf_3214_'.$i.'" value="'.$unitprice.'">
  </td>

  <td class="fieldValue">
  <input id="cf_3216_'.$i.'" style="min-width:80px;" type="number" readonly class="inputElement"  name="cf_3216_'.$i.'" value="'.$totalprice.'">
  </td>

  </tr>';
  $seqcnt = $seqcnt.','.$i;
  $i++;
  }
  $seqc = ltrim($seqcnt,",");
  $response['rowcount'] = $seqc;
  $response['message'] = $html;
  return $response;
}


function getDetailsforPOwrtSO($id){
  $response = array();
  $html = '';

  $inv = '';
  $invwork = '';
  $sqlinv = mysql_query("SELECT * FROM `arocrm_invoice` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_invoice`.`invoiceid`
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_invoice`.`salesorderid` = '".$id."' AND `arocrm_invoice`.`invoicestatus` = 'Approved'");
  while($rows = mysql_fetch_array($sqlinv)){
  $inv = ",".$rows['invoice_no'].$inv;
  $invwork = ",'".$rows['invoice_no']."'".$invwork;
  }
  $inv = ltrim($inv,",");
  $invwork = ltrim($invwork,",");
  $response['invoice'] = $inv;

$html .= '<tr>
<td><strong>TOOLS</strong></td>
<td><span class="redColor">*</span><strong>Item Name</strong></td>
<td><strong class="pull-right">Item code</strong></td>
<td><strong class="pull-right">Unit</strong></td>
<td><strong>Quantity</strong></td>
<td><strong>Warranty</strong></td>
<td><strong>List Price</strong></td>
<td><strong>Delivery Date</strong></td>
<td><strong class="pull-right">Total</strong></td>
<td><strong class="pull-right">Net Price</strong></td>
</tr>';


$itemqry = mysql_query("SELECT `productid`,SUM(`quantity`) AS qty,`listprice` FROM `arocrm_inventoryproductrel`
WHERE `id` IN (SELECT `invoiceid` FROM `arocrm_invoice` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_invoice`.`invoiceid`
WHERE arocrm_crmentity.deleted = '0' AND `arocrm_invoice`.`salesorderid` = '".$id."') GROUP BY `productid`,`listprice`");
$reponse['totalcount'] = mysql_num_rows($itemqry);
$i = 1;
$dis = '';
if($reponse['totalcount']=='1'){
$dis = 'style="display:none;"';
}

while($data = mysql_fetch_array($itemqry)){


$productid = $data['productid'];
$product_array = getProductDetails($productid);
$productname = $product_array['productname'];
$productname = $product_array['productname'];
$productcode = $product_array['productcode'];
$itemunit = $product_array['unit'];
$warranty = $product_array['warranty'];

$qty = number_format((float)$data['qty'], 2, '.', '');
$listprice = number_format((float)$data['listprice'], 2, '.', '');
$totalprice = number_format((float)$qty * $data['listprice'], 2, '.', '');

$html .= '<tr id="row'.$i.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$i.'">
<td style="text-align:center;">
<i class="fa fa-trash deleteRow cursorPointer" title="Delete" '.$dis.'></i>&nbsp;
<a><img src="layouts/v7/skins/images/drag.png" border="0" title="Drag"></a>

<input type="hidden" class="rowNumber" value="'.$i.'"></td>

<td><input type="hidden" name="hidtax_row_no'.$i.'" id="hidtax_row_no'.$i.'" value="">
<div class="itemNameDiv form-inline">
<div class="row">
<div class="col-lg-12">
<div class="input-group" style="width:100%">
<input type="text" id="productName'.$i.'" name="productName'.$i.'" readonly value="'.$productname.'" class="productName form-control  autoComplete   ui-autocomplete-input" placeholder="Type to search" data-rule-required="true" autocomplete="off" aria-required="true">
<input type="hidden" id="hdnProductId'.$i.'" name="hdnProductId'.$i.'" value="'.$productid.'" class="selectedModuleId">
<input type="hidden" id="lineItemType'.$i.'" name="lineItemType'.$i.'" value="Products" class="lineItemType">
</div>
</div>
</div>
</div>
<div>
<br>
<textarea id="comment'.$i.'" name="comment'.$i.'" class="lineItemCommentBox"></textarea>
</div>
</td>


<td>
<input id="productcode'.$i.'" name="productcode'.$i.'" type="text" class="productcode inputElement" readonly="readonly" value="'.$productcode.'">
</td>

<td>
<input id="itemunit'.$i.'" name="itemunit'.$i.'" type="text" class="itemunit inputElement" readonly="readonly" value="'.$itemunit.'">
</td>

<td>
<input id="qty'.$i.'" name="qty'.$i.'" type="number" class="qty smallInputBox inputElement"
data-rule-required="true" data-rule-positive="true" data-rule-greater_than_zero="true" max="'.$qty.'" value="'.$qty.'"
aria-required="true">
<input type="hidden" name="margin'.$i.'" value="">
</td>

<td>
<input id="no_warranty_card'.$i.'" name="no_warranty_card'.$i.'" type="number" class="no_warranty_card inputElement" value="'.$warranty.'" readonly aria-invalid="false">
</td>


<td>
<div>
<input id="listPrice'.$i.'" name="listPrice'.$i.'" readonly value="'.$listprice.'" type="text" data-rule-required="true"
data-rule-positive="true" class="listPrice smallInputBox inputElement" data-is-price-changed="false" list-info=""
data-base-currency-id="" aria-required="true">&nbsp;</div>
<div style="clear:both"></div>


<div class="discountUI validCheck hide" id="discount_div'.$i.'">
<input type="hidden" id="discount_type'.$i.'" name="discount_type'.$i.'" value="zero" class="discount_type">
<p class="popover_title hide">Set Discount For : <span class="variable"></span>
</p>

<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
<tbody>
<tr>
<td><input type="radio" name="discount'.$i.'" checked="" class="discounts" data-discount-type="zero">&nbsp;Zero Discount</td>
<td><input type="hidden" class="discountVal" value="0"></td>
</tr>

<tr>
<td><input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="percentage">&nbsp; %Price</td>

<td>
<span class="pull-right">&nbsp;%</span>
<input type="text" data-rule-positive="true" data-rule-inventory_percentage="true" id="discount_percentage'.$i.'"
 name="discount_percentage'.$i.'" value="" class="discount_percentage span1 pull-right discountVal hide"></td>
</tr>

<tr>
<td class="LineItemDirectPriceReduction">
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="amount">&nbsp;Direct Price Reduction</td>
<td>
<input type="text" data-rule-positive="true" id="discount_amount'.$i.'" name="discount_amount'.$i.'" value=""
class="span1 pull-right discount_amount discountVal hide">
</td>
</tr>
</tbody>
</table>

</div>

<div style="width:150px;" class="hide">
<strong>Total After Discount :</strong>
</div>

<div class="individualTaxContainer hide">
(+)&nbsp;<strong>
<a href="javascript:void(0)" class="individualTax">Tax </a> : </strong>
</div>

<span class="taxDivContainer">
<div class="taxUI hide" id="tax_div'.$i.'">
<p class="popover_title hide">Set Tax for : <span class="variable"></span>
</p>

</div>
</span>
</td>

<td>
<input id="delivery_date'.$i.'" name="delivery_date'.$i.'" type="date" class="delivery_date inputElement" value="'.date('Y-m-d').'">
</td>

<td>
<div id="productTotal'.$i.'" align="right" class="productTotal">'.$totalprice.'</div>
<div id="discountTotal'.$i.'" align="right" class="discountTotal">0.00</div>
<div id="totalAfterDiscount'.$i.'" align="right" class="totalAfterDiscount">'.$totalprice.'</div>
<div id="taxTotal'.$i.'" align="right" class="productTaxTotal hide">0.00</div>
</td>

<td><span id="netPrice'.$i.'" class="pull-right netPrice">'.$totalprice.'</span></td>

</tr>';

$i++;

$amount = (float)$totalprice + $amount;
}

$response['amount'] = $amount;
$response['message'] = $html;
return $response;
}

function getDetailsPOforGR($id){

  $response = array();
  $html = '';


$sqlibd = "SELECT * FROM `arocrm_purchaseorder`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_purchaseorder`.`purchaseorderid`
INNER JOIN `arocrm_purchaseordercf` ON `arocrm_purchaseordercf`.`purchaseorderid` = `arocrm_purchaseorder`.`purchaseorderid`
WHERE `arocrm_crmentity`.`deleted` = '0'
AND `arocrm_purchaseordercf`.`cf_2709` = 'Service Order'
AND `arocrm_purchaseorder`.`purchaseorderid` = '".$id."'";

     $queryexe = mysql_query($sqlibd);
     $ros = mysql_fetch_array($queryexe);

     $sqlplant = mysql_fetch_array(mysql_query("select * from arocrm_plantmaster where plantmasterid = '".$ros['cf_nrl_plantmaster950_id']."'"));

     $response['plantid'] = $ros['cf_nrl_plantmaster950_id'];
     $response['plantname'] = $sqlplant['name'];

     $sqlvendor  = mysql_fetch_array(mysql_query("select * from arocrm_vendor where vendorid = '".$ros['vendorid']."'"));

     $response['vendorid'] = $ros['vendorid'];
     $response['vendorname'] = $sqlvendor['name'];


     $sql = "SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$id."' ORDER BY `sequence_no` ASC";


     $query = mysql_query($sql);
     $dis = '';

     $i = 1;
     $seqcnt = '';
     while($row = mysql_fetch_array($query)){

     $productid = $row['productid'];
     $product_array = getServiceCodeUnit($productid);
     $productname = $product_array['servicename'];
     $productcode = $product_array['service_no'];
     $unit = $product_array['service_usageunit'];

     $unitprice = number_format((float)$row['listprice'], 2, '.', '');
     $qty =  number_format((float)$row['quantity'], 2, '.', '');

     $totalprice = number_format((float)$unitprice * $qty, 2, '.', '');

     $html .= '
     <tr id="Line_Item_Details__row_'.$i.'" class="tr_clone">
     <td>
     <a>
     <img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
     </td>

     <td class="fieldValue">
     <div class="referencefield-wrapper ">
     <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
     <div class="input-group">
     <input name="cf_1897_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_1897_'.$i.'">
     <input id="cf_1897_display_'.$i.'" name="cf_1897_display_'.$i.'" data-fieldname="cf_1897" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'" placeholder="Type to search" autocomplete="off" aria-invalid="false">
     </div>
     </div>
     </td>

     <td class="fieldValue">
     <input id="cf_1894_'.$i.'" type="text" style="width:120px;" readonly data-fieldname="cf_1894" data-fieldtype="string" class="inputElement " name="cf_1894_'.$i.'" value="'.$productcode.'" />
     </td>

     <td class="fieldValue">
     <input id="cf_1923_'.$i.'" readonly style="width:70px;" type="text" data-fieldname="cf_1923" data-fieldtype="string" class="inputElement " name="cf_1923_'.$i.'" value="'.$unit.'" />
     </td>

     <td class="fieldValue">
     <input id="cf_1907_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly name="cf_1907_'.$i.'" value="'.$qty.'"  />
     </td>

     <td class="fieldValue">
     <div class="referencefield-wrapper ">
     <input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
     <div class="input-group">
     <input name="cf_1901_'.$i.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1901_'.$i.'">
     <input id="cf_1901_display_'.$i.'" name="cf_1901_display_'.$i.'" data-fieldname="cf_1901" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="" placeholder="Value Not Required" readonly autocomplete="off" aria-invalid="false">

     </div>
     </div>
     </td>

     <td class="fieldValue">
     <input id="cf_1925_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly name="cf_1925_'.$i.'" value="'.$unitprice.'" />
     </td>

     <td class="fieldValue">
     <input id="cf_1943_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly name="cf_1943_'.$i.'" value="'.$totalprice.'" />
     </td>

     <td class="fieldValue">
     <textarea rows="6" cols="8" id="cf_3003_'.$i.'" style="min-width:150px;" readonly class="inputElement" placeholder="Value Not Required" readonly name="cf_3003_'.$i.'"></textarea>
     </td>

     <td class="fieldValue">
     <textarea rows="6" cols="8" id="cf_3005_'.$i.'" style="min-width:150px;" readonly class="inputElement" placeholder="Value Not Required" readonly name="cf_3005_'.$i.'"></textarea>
     </td>

     <td class="fieldValue">
     <textarea rows="6" cols="8" id="cf_1945_'.$i.'" style="min-width:150px;" class="inputElement" name="cf_1945_'.$i.'"></textarea>
     </td>

     <td class="fieldValue">
     <input id="cf_1947_'.$i.'" style="min-width:90px;" type="text" data-fieldname="cf_1947" data-fieldtype="string" class="inputElement" readonly name="cf_1947_'.$i.'" value="R">
     </td>

     </tr>';
     $seqcnt = $seqcnt.','.$i;
     $i++;
     }

  $seqc = ltrim($seqcnt,",");
  $response['rowcount'] = $seqc;
  $response['message'] = $html;
  return $response;


}

function getDetailsIBDforGR($id){
$response = array();
$html = '';
$ibdrefsql = "SELECT * FROM `arocrm_inbounddelivery` 
INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_inbounddelivery`.`inbounddeliveryid` 
WHERE `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."' AND `arocrm_crmentity`.`deleted` = 0";
$ibdreftmp = mysql_fetch_array(mysql_query($ibdrefsql));
$ref = $ibdreftmp['cf_2862'];
$refreturnso  = $ibdreftmp['cf_3193'];
$response['vehicleno'] = $ibdreftmp['cf_1693'];
$response['modeoftransfer'] = $ibdreftmp['cf_1639'];
$response['invoiceno'] = $ibdreftmp['cf_2124'];
$response['waybillno'] = $ibdreftmp['cf_1691'];
$tmp = explode("-",$ibdreftmp['cf_1651']);
$response['invoicedate'] = $tmp[2]."-".$tmp[1]."-".$tmp[0];
$response['awbno'] = $ibdreftmp['cf_1695'];
$response['billofentry'] = $ibdreftmp['cf_1927'];
$response['cnnumber'] =  $ibdreftmp['cf_1697'];

if($refreturnso=='With respect to Sales Return'){

  $sqlibd = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
   INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
   WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
   AND `arocrm_serialnumbercf`.`cf_1256` = 'B' AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";

   $queryexe = mysql_query($sqlibd);
   $ros = mysql_fetch_array($queryexe);

   $sqlplant = mysql_fetch_array(mysql_query("select * from arocrm_plantmaster where plantmasterid = '".$ros['cf_nrl_plantmaster269_id']."'"));

   $response['plantid'] = $ros['cf_nrl_plantmaster269_id'];
   $response['plantname'] = $sqlplant['name'];

   $sqlpo = mysql_fetch_array(mysql_query("select * from arocrm_purchaseorder where purchaseorderid = '".$ros['cf_nrl_purchaseorder573_id']."'"));

   $response['poid'] = $ros['cf_nrl_purchaseorder573_id'];
   $response['poname'] = $sqlpo['subject'];

   $sqlvendor  = mysql_fetch_array(mysql_query("select * from arocrm_vendor where vendorid = '".$ros['cf_nrl_vendors331_id']."'"));

   $response['vendorid'] = $ros['cf_nrl_vendors331_id'];
   $response['vendorname'] = $sqlvendor['name'];

  

   $sql = "SELECT count(*) as qty,`arocrm_serialnumber`.`cf_nrl_products16_id`,`arocrm_serialnumbercf`.`cf_1256`
   FROM arocrm_inbounddelivery
   INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
   INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
   INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
    INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.`cf_nrl_products16_id`
   WHERE arocrm_crmentity.deleted = '0' 
   AND `arocrm_serialnumbercf`.`cf_1256`  IN ('B')  
   AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
   AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
   GROUP BY `arocrm_serialnumber`.`cf_nrl_products16_id`,`arocrm_serialnumbercf`.`cf_1256`";

   $query = mysql_query($sql);
   $dis = '';
   $nomx = mysql_num_rows($query);
   if($nomx==1){
   $dis = 'style="display:none;"';
   }
   $i = 1;
   $seqcnt = '';
   while($row = mysql_fetch_array($query)){

   $productid = $row['cf_nrl_products16_id'];
   $product_array = getProductDetails($productid);
   $productname = $product_array['productname'];
   $productcode = $product_array['productcode'];
   $unit = $product_array['unit'];
   $warranty = $product_array['warranty'];
   $eaplserial = '';
   $vserial = '';

     $sqlio = mysql_query("SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
     INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
     INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
     INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
     INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
     WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."' AND
     `arocrm_serialnumbercf`.`cf_1256` = 'B' AND `arocrm_serialnumbercf`.`cf_2834` = '2'
     AND `arocrm_serialnumber`.cf_nrl_products16_id = '".$productid."'");

   while($sqlir = mysql_fetch_array($sqlio)){
   	$eaplserial = $eaplserial.','.$sqlir['cf_1258'];
   	$vserial = $vserial.','.$sqlir['cf_1260'];
   }

   $eaplserial = ltrim($eaplserial,",");
   $vserial = ltrim($vserial,",");


  $sqlinv = "SELECT * FROM `arocrm_inventoryproductrel` where `productid` = '".$productid."' AND `id` = (SELECT `cf_nrl_salesorder922_id` FROM `arocrm_salesreturn` WHERE `salesreturnid` = (SELECT `cf_nrl_salesreturn419_id` FROM `arocrm_inbounddelivery` WHERE `inbounddeliveryid` = '".$id."'))";
   $unitprices = mysql_fetch_array(mysql_query($sqlinv));

   $unitprice = number_format((float)$unitprices['listprice'], 2, '.', '');

   $totalprice = $unitprice * $row['qty'];

   $html .= '
   <tr id="Line_Item_Details__row_'.$i.'" class="tr_clone">
   <td>
   <i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"  '.$dis.'></i>&nbsp;<a>
   <img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
   </td>

   <td class="fieldValue">
   <div class="referencefield-wrapper ">
   <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
   <div class="input-group">
   <input name="cf_1897_'.$i.'" type="hidden" value="'.$row['cf_nrl_products16_id'].'" class="sourceField" data-displayvalue="" id="cf_1897_'.$i.'">
   <input id="cf_1897_display_'.$i.'" name="cf_1897_display_'.$i.'" data-fieldname="cf_1897" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'" placeholder="Type to search" autocomplete="off" aria-invalid="false">
   </div>
   </div>
   </td>

   <td class="fieldValue">
   <input id="cf_1894_'.$i.'" type="text" style="width:120px;" readonly data-fieldname="cf_1894" data-fieldtype="string" class="inputElement " name="cf_1894_'.$i.'" value="'.$productcode.'" />
   </td>

   <td class="fieldValue">
   <input id="cf_1923_'.$i.'" readonly style="width:70px;" type="text" data-fieldname="cf_1923" data-fieldtype="string" class="inputElement " name="cf_1923_'.$i.'" value="'.$unit.'" />
   </td>

   <td class="fieldValue">
   <input id="cf_1907_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly name="cf_1907_'.$i.'" value="'.$row['qty'].'" />
   </td>

   <td class="fieldValue">
   <input id="cf_5033_'.$i.'" readonly type="text" style="min-width:110px;" data-fieldname="cf_5033" data-fieldtype="string" class="inputElement " name="cf_5033_'.$i.'" readonly value="'.$warranty.'">
   </td>
   
   
   <td class="fieldValue">
   <div class="referencefield-wrapper ">
   <input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
   <div class="input-group">
   <input name="cf_1901_'.$i.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1901_'.$i.'">
   <input id="cf_1901_display_'.$i.'" name="cf_1901_display_'.$i.'" data-fieldname="cf_1901" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="" placeholder="Type to search" autocomplete="off" aria-invalid="false">
   <a href="javascript:void(0);" class="clearReferenceSelection hide"> x </a>
   <span class="input-group-addon relatedPopup cursorPointer" id="cf_1901_'.$i.',cf_1901_display_'.$i.'" title="Select">
   <i id="'.$i.'" class="fa fa-search"></i>
   </span>
   </div>

   </div>
   
   <input id="cf_1925_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" readonly name="cf_1925_'.$i.'" value="'.$unitprice.'" />
   
   <input id="cf_1943_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" readonly name="cf_1943_'.$i.'" value="'.$totalprice.'" />
   </td>

   <td class="fieldValue">
   <textarea rows="6" cols="8" id="cf_3003_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3003_'.$i.'">'.$eaplserial.'</textarea>
   </td>

   <td class="fieldValue">
   <textarea rows="6" cols="8" id="cf_3005_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3005_'.$i.'">'.$vserial.'</textarea>
   </td>

   <td class="fieldValue">
   <textarea rows="6" cols="8" id="cf_1945_'.$i.'" style="min-width:150px;" class="inputElement" name="cf_1945_'.$i.'"></textarea>
   </td>

   <td class="fieldValue">
   <input id="cf_1947_'.$i.'" style="min-width:90px;" type="text" data-fieldname="cf_1947" data-fieldtype="string" class="inputElement" readonly name="cf_1947_'.$i.'" value="'.$row['cf_1256'].'">
   </td>

   </tr>';
   $seqcnt = $seqcnt.','.$i;
   $i++;
   }


}else{

$poref1 = mysql_fetch_array(mysql_query("SELECT `cf_2712` FROM `arocrm_purchaseordercf` where `purchaseorderid` = (SELECT `cf_nrl_purchaseorder573_id` FROM `arocrm_inbounddelivery` WHERE `inbounddeliveryid` = '".$id."')"));
$poref = $poref1['cf_2712'];

if($ref=='Reference to STR'){

  $sqlibd = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
   INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3196` = `arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
   WHERE arocrm_crmentity.deleted = '0' 
   AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
   AND `arocrm_serialnumbercf`.`cf_1256` = 'R' 
   AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";

}else if($poref=='Against Warranty'){

  $sqlibd = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
   INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
   WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
   AND `arocrm_serialnumbercf`.`cf_1256` = 'B' AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";

}else{

$sqlibd = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
 INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
 INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid`=`arocrm_inbounddelivery`.`inbounddeliveryid`
 INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_1270` = `arocrm_inbounddelivery`.`inbounddeliveryid`
 INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
 WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
 AND `arocrm_serialnumbercf`.`cf_1256` = 'R' AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";
}


$queryexe = mysql_query($sqlibd);
$ros = mysql_fetch_array($queryexe);

$sqlplant = mysql_fetch_array(mysql_query("select * from arocrm_plantmaster where plantmasterid = '".$ros['cf_nrl_plantmaster269_id']."'"));

$response['plantid'] = $ros['cf_nrl_plantmaster269_id'];
$response['plantname'] = $sqlplant['name'];

$sqlpo = mysql_fetch_array(mysql_query("select * from arocrm_purchaseorder where purchaseorderid = '".$ros['cf_nrl_purchaseorder573_id']."'"));

$response['poid'] = $ros['cf_nrl_purchaseorder573_id'];
$response['poname'] = $sqlpo['subject'];

$sqlvendor  = mysql_fetch_array(mysql_query("select * from arocrm_vendor where vendorid = '".$ros['cf_nrl_vendors866_id']."'"));

$response['vendorid'] = $ros['cf_nrl_vendors866_id'];
$response['vendorname'] = $sqlvendor['vendorname'];

$response['vehicleno'] = $ros['cf_1693'];
$response['modeoftransfer'] = $ros['cf_1639'];

if($ref=='Reference to STR'){
  $sql = "SELECT count(*) as qty,`arocrm_serialnumber`.cf_nrl_products16_id FROM arocrm_inbounddelivery
  INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3196` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.cf_nrl_products16_id
  WHERE arocrm_crmentity.deleted = '0' 
  AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
  AND `arocrm_serialnumbercf`.`cf_1256` = 'R'  
  AND `arocrm_serialnumbercf`.`cf_2834` = '2'
  AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
  GROUP BY `arocrm_serialnumber`.cf_nrl_products16_id";
}else if($poref=='Against Warranty'){

   $sql = "SELECT count(*) as qty,`arocrm_serialnumber`.`cf_nrl_products16_id`,`arocrm_serialnumbercf`.`cf_1256`
   FROM arocrm_inbounddelivery
   INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
   INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
   INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
   INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
   INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.cf_nrl_products16_id
   WHERE arocrm_crmentity.deleted = '0' 
   AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
   AND `arocrm_serialnumbercf`.`cf_1256` NOT IN ('O')  
   AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
    GROUP BY `arocrm_serialnumber`.cf_nrl_products16_id,`arocrm_serialnumbercf`.`cf_1256`";


}else{
$sql = "SELECT count(*) as qty,`arocrm_serialnumber`.`cf_nrl_products16_id`,`arocrm_serialnumbercf`.`cf_1256`
FROM arocrm_inbounddelivery
INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_1270` = `arocrm_inbounddelivery`.`inbounddeliveryid`
INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.cf_nrl_products16_id
WHERE arocrm_crmentity.deleted = '0' 
AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
AND `arocrm_serialnumbercf`.`cf_1256` NOT IN ('O')  
AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
 GROUP BY `arocrm_serialnumber`.cf_nrl_products16_id,`arocrm_serialnumbercf`.`cf_1256`";
}

$query = mysql_query($sql);
$dis = '';
$nomx = mysql_num_rows($query);
if($nomx==1){
$dis = 'style="display:none;"';
}
$i = 1;
$seqcnt = '';
while($row = mysql_fetch_array($query)){

$productid = $row['cf_nrl_products16_id'];
$product_array = getProductDetails($productid);
$productname = $product_array['productname'];
$productcode = $product_array['productcode'];
$unit = $product_array['unit'];
$warranty = $product_array['warranty'];
$eaplserial = '';
$vserial = '';

$warrsql = mysql_fetch_array(mysql_query("SELECT `arocrm_inbounddelivery_line_item_lineitem`.`cf_5031` FROM `arocrm_inbounddelivery`
  INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid`
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."'
  AND `arocrm_inbounddelivery_line_item_lineitem`.cf_2868 = '".$productid."' AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'"));




$store  = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_storagelocation` 
INNER JOIN `arocrm_storagelocationcf` ON `arocrm_storagelocationcf`.`storagelocationid` = `arocrm_storagelocation`.`storagelocationid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid`
WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`name` like '%Main Store%' AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$ros['cf_nrl_plantmaster269_id']."'"));

if($ref=='Reference to STR'){

  $sqlio = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
  INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3196` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.cf_nrl_products16_id
  WHERE arocrm_crmentity.deleted = '0' 
  AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."' 
  AND `arocrm_serialnumbercf`.`cf_1256` = 'R' 
  AND `arocrm_serialnumbercf`.`cf_2834` = '2'
  AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
  AND `arocrm_serialnumber`.cf_nrl_products16_id = '".$productid."' AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";

}else if($poref=='Against Warranty'){

  $sqlio = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
  INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.cf_nrl_products16_id
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."' AND
  `arocrm_serialnumbercf`.`cf_1256` = 'B' AND `arocrm_serialnumbercf`.`cf_2834` = '2'
  AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
  AND `arocrm_serialnumber`.cf_nrl_products16_id = '".$productid."' AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";


}else{

  $sqlio = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
  INNER JOIN `arocrm_crmentity` ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_inbounddeliverycf` ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_1270` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  INNER JOIN `arocrm_inbounddelivery_line_item_lineitem` ON `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid` AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = `arocrm_serialnumber`.cf_nrl_products16_id
  WHERE arocrm_crmentity.deleted = '0' AND `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$id."' AND
  `arocrm_serialnumbercf`.`cf_1256` = '".$row['cf_1256']."' AND `arocrm_serialnumbercf`.`cf_2834` = '0'
  AND `arocrm_serialnumber`.cf_nrl_products16_id = '".$productid."' 
  AND `arocrm_inbounddelivery_line_item_lineitem`.`cpl_st` = '0'
  AND `arocrm_inbounddeliverycf`.`cf_3659` = 'Approved'";

}

$sqlios = mysql_query($sqlio);
while($sqlir = mysql_fetch_array($sqlios)){
	$eaplserial = $eaplserial.','.$sqlir['cf_1258'];
	$vserial = $vserial.','.$sqlir['cf_1260'];
}

$eaplserial = ltrim($eaplserial,",");
$vserial = ltrim($vserial,",");


$unitprices = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_inventoryproductrel` where `id` = '".$ros['cf_nrl_purchaseorder573_id']."' and `productid` = '".$productid."'"));

$rfsql = mysql_query("SELECT * FROM `arocrm_inbounddeliverycf` WHERE `inbounddeliveryid` = '".$id."'");
$rfrw = mysql_fetch_array($rfsql);
if($rfrw['cf_3193']=='With respect to Assembly Order'){
$plantid = $response['plantid'];
$sqlunitpr = "SELECT * FROM `arocrm_plantproductassignment`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_plantproductassignment`.`plantproductassignmentid`
INNER JOIN `arocrm_plantproductassignmentcf` ON `arocrm_plantproductassignmentcf`.`plantproductassignmentid`=`arocrm_plantproductassignment`.`plantproductassignmentid`
WHERE arocrm_crmentity.deleted = '0'
AND `arocrm_plantproductassignment`.`cf_nrl_products323_id` = '".$productid."'
AND `arocrm_plantproductassignment`.`cf_nrl_plantmaster103_id` = '".$plantid."'
";
$unitsdql = mysql_query($sqlunitpr);
$unitrw = mysql_fetch_array($unitsdql);
$unitprice = number_format((float)$unitrw['cf_1950'], 2, '.', '');
}else{
$unitprice = number_format((float)$unitprices['listprice'], 2, '.', '');
}

$totalprice = $unitprice * $row['qty'];

$html .= '
<tr id="Line_Item_Details__row_'.$i.'" class="tr_clone">
<td>
<i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"  '.$dis.'></i>&nbsp;<a>
<img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_1897_'.$i.'" type="hidden" value="'.$row['cf_nrl_products16_id'].'" class="sourceField" data-displayvalue="" id="cf_1897_'.$i.'">
<input id="cf_1897_display_'.$i.'" name="cf_1897_display_'.$i.'" data-fieldname="cf_1897" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$productname.'" placeholder="Type to search" autocomplete="off" aria-invalid="false">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_1894_'.$i.'" type="text" style="width:120px;" readonly data-fieldname="cf_1894" data-fieldtype="string" class="inputElement " name="cf_1894_'.$i.'" value="'.$productcode.'" />
</td>

<td class="fieldValue">
<input id="cf_1923_'.$i.'" readonly style="width:70px;" type="text" data-fieldname="cf_1923" data-fieldtype="string" class="inputElement " name="cf_1923_'.$i.'" value="'.$unit.'" />
</td>


<td class="fieldValue">
<input id="cf_1907_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly name="cf_1907_'.$i.'" value="'.$row['qty'].'" />
</td>

<td class="fieldValue">
   <input id="GoodsReceipt_editView_fieldName_cf_5033_'.$i.'" readonly style="min-width:90px;" type="text" value="'.$warranty.'" class="inputElement" name="cf_5033_'.$i.'" />
   </td>


<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'" />
<div class="input-group">
<input name="cf_1901_'.$i.'" type="hidden" value="'.$store['storagelocationid'].'" class="sourceField" data-displayvalue="" id="cf_1901_'.$i.'">
<input id="cf_1901_display_'.$i.'" name="cf_1901_display_'.$i.'" data-fieldname="cf_1901" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$store['name'].'" readonly placeholder="Type to search" autocomplete="off" aria-invalid="false" />
</div>
</div>


<input id="cf_1925_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" readonly name="cf_1925_'.$i.'" value="'.$unitprice.'" />


<input id="cf_1943_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" readonly name="cf_1943_'.$i.'" value="'.$totalprice.'" />

</td>




<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3003_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3003_'.$i.'">'.$eaplserial.'</textarea>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3005_'.$i.'" style="min-width:150px;" readonly class="inputElement" name="cf_3005_'.$i.'">'.$vserial.'</textarea>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1945_'.$i.'" style="min-width:150px;" class="inputElement" name="cf_1945_'.$i.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1947_'.$i.'" style="min-width:90px;" type="text" data-fieldname="cf_1947" data-fieldtype="string" class="inputElement" readonly name="cf_1947_'.$i.'" value="'.$row['cf_1256'].'">
</td>

</tr>';
$seqcnt = $seqcnt.','.$i;
$i++;
}
}
$seqc = ltrim($seqcnt,",");
$response['rowcount'] = $seqc;
$response['message'] = $html;
return $response;
}





function getLineItemforQIWOBD($id,$obdno){
$response = array();
$sql = "SELECT arocrm_products.*,arocrm_crmentity.* FROM arocrm_products
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid = '".$id."'";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$response['productcode'] = $row['product_no'];

$jsql = "SELECT `arocrm_outbounddelivery`.*,`arocrm_outbounddeliverycf`.*,`arocrm_outbounddelivery_line_item_lineitem`.* FROM `arocrm_outbounddelivery`
INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
INNER JOIN `arocrm_outbounddelivery_line_item_lineitem` ON `arocrm_outbounddelivery_line_item_lineitem`.`outbounddeliveryid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
WHERE  `arocrm_crmentity`.`deleted`='0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$obdno."'
AND `arocrm_outbounddelivery_line_item_lineitem`.`cf_2006` = '".$id."'";

$rownumfetch = mysql_query($jsql);
$rowre = mysql_fetch_array($rownumfetch);
$response['maxrowcount'] = $rowre['cf_2014'];
$html = '';
$dis = '';
if($nos==1){
$dis = 'style="display: none;"';
}
$rowct = '';
for($k=1;$k<=$response['maxrowcount'];$k++){

$html .= '
<tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>


<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'">
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';
$rowct = $rowct.','.$k;
}
$response['rowcount'] = ltrim($rowct,",");
$response['message'] = $html;
return $response;
}



function getProductCodeforQI($id,$ibdno){
$response = array();
$ibdref = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_inbounddelivery` INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`  WHERE `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."'"));
$ref = $ibdref['cf_3193'];
if($ref=='With respect to Sales Return'){
	
 $sql = "SELECT arocrm_products.*,arocrm_crmentity.* FROM arocrm_products
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
  WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid = '".$id."'";
  $query = mysql_query($sql);
  $row = mysql_fetch_array($query);
  $response['productcode'] = $row['product_no'];

  $jsql = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
  INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  WHERE  `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."' AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$id."'
  AND `arocrm_serialnumbercf`.`cf_1256` = 'B'";

  $rownumfetch = mysql_query($jsql);
  $rowre = mysql_fetch_array($rownumfetch);
  $response['maxrowcount'] = mysql_num_rows($rownumfetch);
  $html = '';
  $rowct = '';
  for($k=1;$k<=$response['maxrowcount'];$k++){

  $html .= '
<tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" required name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'" required>
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';
  $rowct = $rowct.','.$k;
  }
  
}else if($ref=='With respect to STPO'){

  $sql = "SELECT arocrm_products.*,arocrm_crmentity.* FROM arocrm_products
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
  WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid = '".$id."'";
  $query = mysql_query($sql);
  $row = mysql_fetch_array($query);
  $response['productcode'] = $row['product_no'];

  $jsql = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
  INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3196` = `arocrm_inbounddelivery`.`inbounddeliveryid`
  INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
  WHERE  `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."' AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$id."'
  AND `arocrm_serialnumbercf`.`cf_1256` = 'O'";

  $rownumfetch = mysql_query($jsql);
  $rowre = mysql_fetch_array($rownumfetch);
  $response['maxrowcount'] = mysql_num_rows($rownumfetch);
  $html = '';
  $rowct = '';
  for($k=1;$k<=$response['maxrowcount'];$k++){

  $html .= '
<tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" required name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'" required>
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';
  $rowct = $rowct.','.$k;
  }

}else{

  $poref = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_purchaseordercf`  where `purchaseorderid` IN (SELECT `cf_nrl_purchaseorder573_id` FROM `arocrm_inbounddelivery` WHERE `inbounddeliveryid` = '".$ibdno."')"));
  $refpo = $poref['cf_2712'];

  if($refpo=='Against Warranty'){

    $sql = "SELECT arocrm_products.*,arocrm_crmentity.* FROM arocrm_products
    INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
    WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid = '".$id."'";
    $query = mysql_query($sql);
    $row = mysql_fetch_array($query);
    $response['productcode'] = $row['product_no'];

    $jsql = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
    INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`
    INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_3348` = `arocrm_inbounddelivery`.`inbounddeliveryid`
    INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
    WHERE  `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."' AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$id."'
    AND `arocrm_serialnumbercf`.`cf_1256` = 'B'";

    $rownumfetch = mysql_query($jsql);
    $rowre = mysql_fetch_array($rownumfetch);
    $response['maxrowcount'] = mysql_num_rows($rownumfetch);
    $html = '';
    $rowct = '';
    for($k=1;$k<=$response['maxrowcount'];$k++){

    $html .= '
    <tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" required name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'" required>
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';
    $rowct = $rowct.','.$k;
    }

  }else{


$sql = "SELECT arocrm_products.*,arocrm_crmentity.* FROM arocrm_products
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid = '".$id."'";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$response['productcode'] = $row['product_no'];

$jsql = "SELECT `arocrm_inbounddelivery`.*,`arocrm_inbounddeliverycf`.*,`arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_inbounddelivery`
INNER JOIN `arocrm_inbounddeliverycf` ON `arocrm_inbounddeliverycf`.`inbounddeliveryid` = `arocrm_inbounddelivery`.`inbounddeliveryid`
INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`cf_1270` = `arocrm_inbounddelivery`.`inbounddeliveryid`
INNER JOIN `arocrm_serialnumber` ON `arocrm_serialnumber`.`serialnumberid` = `arocrm_serialnumbercf`.`serialnumberid`
WHERE  `arocrm_inbounddelivery`.`inbounddeliveryid` = '".$ibdno."' AND `arocrm_serialnumber`.`cf_nrl_products16_id` = '".$id."'
AND `arocrm_serialnumbercf`.`cf_1256` = 'O'";

$rownumfetch = mysql_query($jsql);
$rowre = mysql_fetch_array($rownumfetch);
$response['maxrowcount'] = mysql_num_rows($rownumfetch);
$html = '';
$rowct = '';
for($k=1;$k<=$response['maxrowcount'];$k++){

$html .= '
<tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" required name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'" required>
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';
$rowct = $rowct.','.$k;
}
}

}
$response['rowcount'] = ltrim($rowct,",");
$response['message'] = $html;
return $response;
}



function getOBDLineItemforQI($obdno,$nos,$productid){
  $response = array();
  $sql = "SELECT arocrm_products.*,arocrm_crmentity.* FROM arocrm_products
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
  WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid = '".$productid."'";
  $query = mysql_query($sql);
  $row = mysql_fetch_array($query);
  $response['productcode'] = $row['product_no'];

  $jsql = "SELECT `arocrm_outbounddelivery`.*,`arocrm_outbounddeliverycf`.*,`arocrm_outbounddelivery_line_item_lineitem`.* FROM `arocrm_outbounddelivery`
  INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_outbounddelivery_line_item_lineitem` ON `arocrm_outbounddelivery_line_item_lineitem`.`outbounddeliveryid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  WHERE  `arocrm_crmentity`.`deleted`='0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$obdno."' AND `arocrm_outbounddelivery_line_item_lineitem`.`cf_2006` = '".$productid."'";

  $rownumfetch = mysql_query($jsql);
  $rowre = mysql_fetch_array($rownumfetch);
  $response['maxrowcount'] = $nos;
  $html = '';
  $dis = '';
  if($nos==1){
  $dis = 'style="display: none;"';
  }
  $rowct = '';
  for($k=1;$k<=$response['maxrowcount'];$k++){

  $html .= '
  <tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" required name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'" required>
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';
  $rowct = $rowct.','.$k;
  }
  $response['rowcount'] = ltrim($rowct,",");
  $response['message'] = $html;
  return $response;
}

function getIBDLineItemforQI($ibdno,$nos,$productid){
$response = array();
$html = '';
$dis = '';
if($nos==1){
$dis = 'style="display: none;"';
}
$rowct = '';
for($k=1;$k<=$nos;$k++){

$html .= '
<tr id="Quality_Inspection_Lineitem__row_'.$k.'" class="tr_clone">

<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="SerialNumber" id="popupReferenceModule_'.$k.'" />
<div class="input-group">
<input name="cf_1778_'.$k.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_1778_'.$k.'" />
<input id="cf_1778_display_'.$k.'" required name="cf_1778_display_'.$k.'" data-fieldname="cf_1778" data-fieldtype="reference" type="text" class="marginLeftZero inputElement" value="" readonly />
<span class="input-group-addon relatedPopup cursorPointer" id="cf_1778_'.$k.',cf_1778_display_'.$k.'" title="Select"><i id="QualityInspection_editView_fieldName_cf_1778_select" class="fa fa-search"></i>
</span>
</div>
</div>
</td>

<td class="fieldValue">
<select data-fieldname="cf_3644" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_3644_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_3644_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5094" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5094_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5094_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5096" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5096_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_5096_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5098" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5098_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5098_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_5100" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_5100_'.$k.'" data-selected-value=" " tabindex="-1" title="" id="cf_5100_'.$k.'">
<option value="">Select an Option</option>
<option value="Ok">Ok</option>
<option value="Not Ok">Not Ok</option>
</select>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1812" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1812_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1812_'.$k.'">
<option value="">Select an Option</option>
<option value="Dry">Dry</option>
<option value="Wet">Wet</option>
<option value="Damaged">Damaged</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1793_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1793_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<input id="cf_1783_'.$k.'" type="text" style="min-width:110px;" data-fieldname="cf_1783" data-fieldtype="string" class="inputElement " name="cf_1783_'.$k.'" value="">
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1785_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1785_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_1808" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_1808_'.$k.'" data-selected-value="" tabindex="-1" title="" id="cf_1808_'.$k.'">
<option value="">Select an Option</option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_1810_'.$k.'" style="min-width:110px;" class="inputElement " name="cf_1810_'.$k.'"></textarea>
</td>

<td class="fieldValue">
<select data-fieldname="cf_2983" data-fieldtype="picklist" class="inputElement optionselect2" type="picklist" name="cf_2983_'.$k.'" data-selected-value="" data-rule-required="true" tabindex="-1" title="" aria-required="true" id="cf_2983_'.$k.'" required>
<option value="">Select an Option</option>
<option value="R - Release">R - Release</option>
<option value="B - Blocked">B - Blocked</option>
<option value="S - Semiblocked">S - Semiblocked</option>
</select>
</td>

</tr>';

$rowct = $rowct.','.$k;
}

$response['rowcount'] = ltrim($rowct,",");
$response['message'] = $html;
return $response;
}

function getOBDItemforQI($id){

  $response = array();
  $sql = "SELECT `arocrm_outbounddelivery`.*,`arocrm_crmentity`.* FROM `arocrm_outbounddelivery`
  INNER JOIN `arocrm_outbounddeliverycf` ON `arocrm_outbounddeliverycf`.`outbounddeliveryid` = `arocrm_outbounddelivery`.`outbounddeliveryid`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
  WHERE `arocrm_crmentity`.`deleted`='0' AND `arocrm_outbounddelivery`.`outbounddeliveryid` = '".$id."' AND `arocrm_outbounddeliverycf`.`cf_4826` = 'Approved'";
  $query = mysql_query($sql);
  $row = mysql_fetch_array($query);


  $sql2 = "SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
  WHERE arocrm_crmentity.deleted=0 AND arocrm_plantmaster.plantmasterid = '".$row['cf_nrl_plantmaster625_id']."'";
  $query2 = mysql_query($sql2);
  $row2 = mysql_fetch_array($query2);



$sql3 = mysql_query("SELECT `arocrm_salesorder`.* FROM `arocrm_salesorder` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_salesorder`.`salesorderid` WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_salesorder`.`salesorderid` = '".$row['cf_nrl_salesorder679_id']."'");
$row3 = mysql_fetch_array($sql3);
$accid3 = $row3['accountid'];

$sql14 = mysql_query("SELECT `arocrm_account`.* FROM `arocrm_account` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_account`.`accountid` WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_account`.`accountid` = '".$accid3."'");
$row14 = mysql_fetch_array($sql14);

$accname4 = $row14['accountname'];

$response['soid'] = $row['cf_nrl_salesorder679_id'];
$response['soname'] = $row3['subject'];

$response['customerid'] = $accid3;
$response['customername'] = $accname4;

  $response['vendorid'] = '';
  $response['vendorname'] = '';
  $response['vendorcode'] = '';
  $response['plantname'] = $row2['name'];
  $response['plantcode'] = $row2['plantmasterno'];
  $response['plantid'] = $row['cf_nrl_plantmaster625_id'];

  return $response;
}


function getIBDItemforQI($id){
$response = array();
$sql = "SELECT arocrm_inbounddelivery.*,arocrm_crmentity.* FROM arocrm_inbounddelivery
INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
WHERE arocrm_crmentity.deleted=0 AND arocrm_inbounddelivery.inbounddeliveryid = '".$id."' AND arocrm_inbounddeliverycf.cf_3659 = 'Approved'";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);

$sql1 = "SELECT arocrm_vendor.*,arocrm_crmentity.* FROM arocrm_vendor
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_vendor.vendorid
WHERE arocrm_crmentity.deleted=0 AND arocrm_vendor.vendorid = '".$row['cf_nrl_vendors866_id']."'";
$query1 = mysql_query($sql1);
$row1 = mysql_fetch_array($query1);


$sql2 = "SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
WHERE arocrm_crmentity.deleted=0 AND arocrm_plantmaster.plantmasterid = '".$row['cf_nrl_plantmaster269_id']."'";
$query2 = mysql_query($sql2);
$row2 = mysql_fetch_array($query2);

$response['ibdno'] = $row['inbounddeliveryno'];
$response['vendorid'] = $row['cf_nrl_vendors866_id'];
$response['vendorname'] = $row1['vendorname'];
$response['vendorcode'] = $row1['vendor_no'];
$response['plantname'] = $row2['name'];
$response['plantcode'] = $row2['plantmasterno'];
$response['plantid'] = $row['cf_nrl_plantmaster269_id'];
return $response;
}



function getProductCodeUnit($id,$vendorid,$plantid,$currid){
$response = array();
$unitpp = 0;
$unitinrpp = 0;
$sql = "SELECT arocrm_products.*,arocrm_productcf.*,arocrm_crmentity.* FROM arocrm_products
INNER JOIN arocrm_productcf ON arocrm_productcf.productid = arocrm_products.productid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$id;

$query = mysql_query($sql);
$row = mysql_fetch_array($query);

$gurantee = $row['cf_3122'];
$warranty = $row['cf_3418'];
if($warranty!=0)
{
	$wcard = $gurantee." + ".$warranty;
}
else
{
	$wcard = $gurantee;
}

$rfqu_new = mysql_query("SELECT arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1980 as unit_price FROM arocrm_rfqmaintain
INNER JOIN arocrm_rfqmaintain_rfq_lineitem_lineitem ON arocrm_rfqmaintain_rfq_lineitem_lineitem.rfqmaintainid=arocrm_rfqmaintain.rfqmaintainid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_rfqmaintain.rfqmaintainid
WHERE arocrm_crmentity.deleted=0 AND arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1957 ='".$id."'
and arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1996 = 'Approved' and arocrm_rfqmaintain.cf_nrl_vendors253_id = '".$vendorid."'
ORDER BY `arocrm_crmentity`.`createdtime` DESC LIMIT 0,1");
$rfqcost = mysql_fetch_array($rfqu_new);
$myct = mysql_num_rows($rfqu_new);
if($myct==1){
$unitpp = $row['unit_price'];
}else{
$psql = "SELECT `arocrm_plantproductassignmentcf`.`cf_1950` FROM `arocrm_plantproductassignmentcf`
INNER JOIN `arocrm_plantproductassignment` ON `arocrm_plantproductassignment`.`plantproductassignmentid` = `arocrm_plantproductassignmentcf`.`plantproductassignmentid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_plantproductassignment`.`plantproductassignmentid`
WHERE  `arocrm_crmentity`.`deleted` = '0' AND `arocrm_plantproductassignment`.`cf_nrl_products323_id` = '".$id."' AND `arocrm_plantproductassignment`.`cf_nrl_plantmaster103_id` = '".$plantid."'";
$unitprices = mysql_query($psql);
$prcost = mysql_fetch_array($unitprices);
$unitpp = $prcost['cf_1950'];
}

$cursql = mysql_query("SELECT * FROM `arocrm_currency_info` WHERE `deleted` = '0' AND `id` = '".$currid."'");
$currw  = mysql_fetch_array($cursql);
$dcurp = $currw['currency_code'];

if($dcurp!="INR"){
$rate = (float)(1 / $currw['conversion_rate']);
$unitinrpp = number_format((float)$unitpp * $rate, 2, '.', '');
}else{
$unitinrpp = $unitpp;
}

$response['listinrprice'] = $unitinrpp;
$response['listprice'] = $unitpp;
$response['productcode'] = $row['productcode'];
$response['unit'] = $row['usageunit'];
$response['warranty'] = $wcard;
$response['category'] = $row['productcategory'];
$response['ah'] = $row['cf_3446'];
return $response;
}

function getSTRLineItemforPO($id){
$response = array();
$html = '';
$html .='<tr><td><strong>TOOLS</strong></td><td><span class="redColor">*</span><strong>Item Name</strong></td><td><strong class="pull-right">Item code</strong></td><td><strong class="pull-right">Unit</strong></td><td><strong>Quantity</strong></td><td><strong>List Price</strong></td><td><strong>Delivery Date</strong></td><td><strong class="pull-right">Total</strong></td><td><strong class="pull-right">Net Price</strong></td></tr>';
$i=1;
$countstring = '';
$requisition_date = '';
$str_h = "SELECT arocrm_purchasereq.*,arocrm_purchasereqcf.*,arocrm_crmentity.* FROM arocrm_purchasereq
		              INNER JOIN arocrm_purchasereqcf ON arocrm_purchasereqcf.purchasereqid=arocrm_purchasereq.purchasereqid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid=".$id;
                      $query_h = mysql_query($str_h);
					  $count = mysql_num_rows($query_h);
						if($count > 0)
						{
						  $row = mysql_fetch_array($query_h);
						  $requisition_date = $row['cf_1759'];
						  $requisition_date = date("d-m-Y", strtotime($requisition_date));
						}


					$query_lineitem = mysql_query("SELECT arocrm_purchasereq.*,arocrm_purchasereq_purchase_req_lineitem_lineitem.*,arocrm_crmentity.* FROM arocrm_purchasereq
		              INNER JOIN arocrm_purchasereq_purchase_req_lineitem_lineitem ON arocrm_purchasereq_purchase_req_lineitem_lineitem.purchasereqid=arocrm_purchasereq.purchasereqid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid='".$id."'");
					  $count_lineitem = mysql_num_rows($query_lineitem);
					  $linechkitemct = getNoVendorPRCount($id);
						if($count_lineitem>0)
						{
							$cnt = 0;
						  while($row_lineitem = mysql_fetch_array($query_lineitem))
						  {

						  $productid = $row_lineitem['cf_1730'];
						  $product_array = getProductDetails($productid);
						  $productname = $product_array['productname'];
						  $productcode = $product_array['productcode'];
						  $unit_price_defined = $product_array['unit_price'];
						  $item_description = $product_array['description'];
						  $productunit = $product_array['unit'];
						  $requisite_qty = $row_lineitem['cf_1740'];
						  $unitprice = 0;



						  $chkline = mysql_query("SELECT sum(aip.quantity) as quantity FROM `arocrm_inventoryproductrel` aip INNER JOIN  `arocrm_purchaseorder` apo WHERE apo.purchaseorderid = aip.id AND apo.cf_nrl_purchasereq461_id = '".$id."' and aip.productid = '".$productid."'");
						  $count_rw = mysql_num_rows($chkline);
						  $chkrow = mysql_fetch_array($chkline);
						  if($count_rw > 0){

							$requisite_qty = $row_lineitem['cf_1740'] - $chkrow['quantity'];
						  }

						    $delivery_date = $row_lineitem['cf_1754'];

							$unitprice = number_format((float)$unit_price_defined, 2, '.', '');

						  $total_amount = (float)$unitprice * (float)$requisite_qty;
   						  $total_amount =  number_format((float)$total_amount, 2, '.', '');
						  $displaytype = "";

						if($linechkitemct==1){

					    $displaytype = 'style="display:none;"';
						}

						  if($requisite_qty > 0)
						  {
							  $prosql = mysql_query("SELECT * FROM arocrm_products 
							  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid 
							  WHERE arocrm_crmentity.deleted = 0 AND arocrm_products.productid ='".$productid."'");
							  $prorow = mysql_fetch_array($prosql);
							  $warranty = $prorow['cf_3418'];
							  $gurantee = $prorow['cf_3122'];
							  if($warranty!=0)
							  {
									$wcard = $gurantee." + ".$warranty;
							  }
							  else
							  {
									$wcard = $gurantee;
							  }

$html .= '<tr id="row'.$i.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$i.'">
<td style="text-align:center;">
<i class="fa fa-trash deleteRow cursorPointer" title="Delete" '.$displaytype.'></i>&nbsp;
<a><img src="layouts/v7/skins/images/drag.png" border="0" title="Drag"></a>
<input type="hidden" class="rowNumber" value="'.$i.'"></td>

<td><input type="hidden" name="hidtax_row_no'.$i.'" id="hidtax_row_no'.$i.'" value="">
<div class="itemNameDiv form-inline">
<div class="row">
<div class="col-lg-10">
<div class="input-group" style="width:100%">
<input type="text" id="productName'.$i.'" name="productName'.$i.'" value="'.$productname.'" class="productName form-control  autoComplete   ui-autocomplete-input" readonly placeholder="Type to search" data-rule-required="true" autocomplete="off" aria-required="true">

<input type="hidden" id="hdnProductId'.$i.'" name="hdnProductId'.$i.'" value="'.$productid.'" class="selectedModuleId">
<input type="hidden" id="lineItemType'.$i.'" name="lineItemType'.$i.'" value="Products" class="lineItemType">

</div>
</div>
</div>
</div>
<input type="hidden" value="" id="subproduct_ids'.$i.'" name="subproduct_ids'.$i.'" class="subProductIds">
<div id="subprod_names'.$i.'" name="subprod_names'.$i.'" class="subInformation">
<span class="subProductsContainer"></span>
</div>
<div>
<br>
<textarea id="comment'.$i.'" name="comment'.$i.'" class="lineItemCommentBox">'.$item_description.'</textarea>
</div>
</td>
<td>
<input id="productcode'.$i.'" name="productcode'.$i.'" type="text" class="productcode inputElement" readonly="readonly" value="'.$productcode.'">
</td>
<td>
<input id="itemunit'.$i.'" name="itemunit'.$i.'" type="text" class="itemunit inputElement" readonly="readonly" value="'.$productunit.'">
</td>
<td>
<input id="qty'.$i.'" name="qty'.$i.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" data-rule-positive="true" data-rule-greater_than_zero="true" value="'.$requisite_qty.'" aria-required="true" max="'.$requisite_qty.'">
<input type="hidden" name="margin'.$i.'" value="0">
<span class="margin pull-right" style="display:none"></span></td>
<td><input id="no_warranty_card'.$i.'" name="no_warranty_card'.$i.'" type="text" class="no_warranty_card inputElement" readonly="" value="'.$wcard.'" aria-invalid="false"></td><td><div>
<input id="listPrice'.$i.'" readonly name="listPrice'.$i.'" value="'.$unitprice.'" type="text" data-rule-required="true" data-rule-positive="true" class="listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true">&nbsp;</div>
<div style="clear:both"></div>
<div><span>(-)&nbsp;<strong><a href="javascript:void(0)" class="individualDiscount">Discount<span class="itemDiscount">(0)</span></a> : </strong></span></div>
<div class="discountUI validCheck hide" id="discount_div'.$i.'">
<input type="hidden" id="discount_type'.$i.'" name="discount_type'.$i.'" value="zero" class="discount_type">
<p class="popover_title hide">Set Discount For : <span class="variable"></span></p>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
<tbody>
<tr>
<td>
<input type="radio" name="discount'.$i.'" checked="" class="discounts" data-discount-type="zero">&nbsp;Zero Discount
</td>
<td>
<input type="hidden" class="discountVal" value="0"></td>
</tr>
<tr>
<td>
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="percentage">&nbsp; %Price
</td>
<td>
<span class="pull-right">&nbsp;%</span>
<input type="text" data-rule-positive="true" data-rule-inventory_percentage="true" id="discount_percentage'.$i.'" name="discount_percentage'.$i.'" value="" class="discount_percentage span'.$i.' pull-right discountVal hide">
</td>
</tr>
<tr>
<td class="LineItemDirectPriceReduction">
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="amount">&nbsp;Direct Price Reduction
</td>
<td>
<input type="text" data-rule-positive="true" id="discount_amount'.$i.'" name="discount_amount'.$i.'" value="" class="span1 pull-right discount_amount discountVal hide">
</td>
</tr>
</tbody>
</table>
</div>
<div style="width:150px;"><strong>Total After Discount :</strong></div>
<div class="individualTaxContainer hide">(+)&nbsp;<strong><a href="javascript:void(0)" class="individualTax">Tax </a> : </strong></div>
<span class="taxDivContainer">
<div class="taxUI hide" id="tax_div'.$i.'">
<p class="popover_title hide">Set Tax for : <span class="variable"></span></p>
</div>
</span>
</td>

<td>
<input id="delivery_date'.$i.'" value="'.$row_lineitem['cf_1754'].'" name="delivery_date'.$i.'" type="date" class="delivery_date inputElement" />
</td>
<td>
<div id="productTotal'.$i.'" align="right" class="productTotal">'.$total_amount.'</div>
<div id="discountTotal'.$i.'" align="right" class="discountTotal">0.00</div>
<div id="totalAfterDiscount'.$i.'" align="right" class="totalAfterDiscount">'.$total_amount.'</div>
<div id="taxTotal'.$i.'" align="right" class="productTaxTotal hide">0.00</div>
</td>
<td>
<span id="netPrice'.$i.'" class="pull-right netPrice">'.$total_amount.'</span>
</td>
</tr>';

						  $i++;

						  }
						  }

						}

						$response['str_date'] = $requisition_date;
						$response['message'] = $html;
						return $response;

}


function getPOLineItemforOBDWSTPO($id){
  $response = array();
  $serv = 0;
  $hid = "";
  $html = '';
  $countstring = '';
  $str_h = "SELECT `arocrm_purchaseorder`.*,`arocrm_purchaseordercf`.*,`arocrm_crmentity`.* FROM `arocrm_purchaseorder`
  INNER JOIN `arocrm_purchaseordercf` ON `arocrm_purchaseordercf`.`purchaseorderid`=`arocrm_purchaseorder`.`purchaseorderid`
  INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_purchaseorder`.`purchaseorderid`
  WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_purchaseorder`.`purchaseorderid` = '".$id."'
  AND `arocrm_purchaseordercf`.`cf_2712` = 'Reference to STR' AND `arocrm_purchaseorder`.`postatus` = 'Approved'";
  $query_h = mysql_query($str_h);
  $count = mysql_num_rows($query_h);
  if($count == 1)
  {
  $rows = mysql_fetch_array($query_h);

  $plantsql  = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_plantmaster` WHERE `plantmasterid` = '".$rows['cf_nrl_plantmaster953_id']."'"));
  $deliveryplant = $rows['cf_nrl_plantmaster953_id'];
  $delplantname = $plantsql['name'];
  
  $plantfromsql  = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_plantmaster` WHERE `plantmasterid` = '".$rows['cf_nrl_plantmaster950_id']."'"));
  $delfromplant = $rows['cf_nrl_plantmaster950_id'];
  $delfromplantname = $plantfromsql['name'];
  
  $storesql =  mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation`
 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_storagelocation.storagelocationid
 WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$deliveryplant."' AND `arocrm_storagelocation`.`name` like '%%Main Store%%'");
$resultstore = mysql_fetch_array($storesql);

  $response['delplantid'] = $deliveryplant;
  $response['delplantname'] = $delplantname;
  
  $response['delfromplantid'] = $delfromplant;
  $response['delfromplantname'] = $delfromplantname;

  $deldate =  $rows['cf_3080'];
  $itemlevel = "SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$id."' ORDER BY `arocrm_inventoryproductrel`.`sequence_no` ASC";
  $sql_i = mysql_query($itemlevel);
  $sql_num = mysql_num_rows($sql_i);
  if($sql_num==1){
  $hid = 'style="display:none;"';
  }
  $i = 1;
  $rcnt = "";
  while($row = mysql_fetch_array($sql_i)){
  $deldate =  $row['delivery_date'];
  $productid = $row['productid'];
  $product_array = getProductDetails($productid);
  $productname = $product_array['productname'];
  $productcode = $product_array['productcode'];
  $productunit = $product_array['unit'];

  $qty = number_format((float)$row['quantity'], 2, '.', '');
  $listprice = number_format((float)$row['listprice'], 2, '.', '');

  $str_h_i = mysql_query("SELECT SUM(arocrm_outbounddelivery_line_item_lineitem.cf_2014) as totalqty FROM arocrm_outbounddelivery
  INNER JOIN arocrm_outbounddelivery_line_item_lineitem ON arocrm_outbounddelivery_line_item_lineitem.outbounddeliveryid=arocrm_outbounddelivery.outbounddeliveryid
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_outbounddelivery.outbounddeliveryid
  WHERE arocrm_crmentity.deleted = '0' AND arocrm_outbounddelivery.cf_nrl_salesorder679_id = '".$id."'
  AND arocrm_outbounddelivery_line_item_lineitem.cf_2006 = '".$productid."'");
  $res_h_i = mysql_fetch_array($str_h_i);
  $tqy = $res_h_i['totalqty'];
  if($tqy==""){
  $tqy = 0;
  }
  $rty = (float)$row['quantity'] - (float)$tqy;
  $reqty = number_format($rty, 2, '.', '');
  if($reqty > 0){
  $serv = 1;
  $html .= '
  <tr id="Line_Item__row_'.$i.'" class="tr_clone">
  <td>
  <i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" '.$hid.'></i>
  &nbsp;
  <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag">
  </a>
  </td>

  <td class="fieldValue">
  <div class="referencefield-wrapper ">
  <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
  <div class="input-group">
  <input name="cf_2006_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_2006_'.$i.'">
  <input id="cf_2006_display_'.$i.'" name="cf_2006_display_'.$i.'" data-fieldname="cf_2006" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'" readonly placeholder="Type to search" autocomplete="off">
  </div>
  </div>
  </td>

  <td class="fieldValue">
  <input id="cf_2004_'.$i.'" type="text" data-fieldname="cf_2004" data-fieldtype="string" style="min-width:120px" readonly class="inputElement " name="cf_2004_'.$i.'" value="'.$productcode.'">
  </td>

  <td class="fieldValue">
  <input id="cf_2018_'.$i.'" type="text" data-fieldname="cf_2018" data-fieldtype="string"  style="min-width:100px" readonly class="inputElement " name="cf_2018_'.$i.'" value="'.$productunit.'">
  </td>

  <td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2010_'.$i.'" type="hidden" value="'.trim($resultstore['storagelocationid']).'" class="sourceField" data-displayvalue="" id="cf_2010_'.$i.'">
<input id="cf_2010_display_'.$i.'" name="cf_2010_display_'.$i.'" data-fieldname="cf_2010" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.trim($resultstore['name']).'" placeholder="Type to search" autocomplete="off">
</div>
</td>

  <td class="fieldValue">
  <input id="cf_2012_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_2012_'.$i.'" value="'.$qty.'" />
  </td>

  <td class="fieldValue">
  <input id="cf_2014_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2014_'.$i.'" value="'.$reqty.'" max="'.$reqty.'" />
 
  <input id="cf_2020_'.$i.'" style="min-width:80px;"  type="hidden" class="inputElement" name="cf_2020_'.$i.'" value="'.$listprice.'">
 
<input id="cf_4925_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" name="cf_4925_'.$i.'" value="'.$reqty*$listprice.'">
</td>

  <td class="fieldValue">
  <div class="input-group inputElement" style="margin-bottom: 3px">
  <input id="cf_2022_'.$i.'" type="date" class="form-control" readonly data-fieldname="cf_2022" name="cf_2022_'.$i.'" value="'.$deldate.'" data-rule-date="true">
  </div>
  </td>

  <td class="fieldValue">
  <div class="input-group inputElement" style="margin-bottom: 3px">
  <input id="cf_2026_'.$i.'" type="date" class="form-control " data-fieldname="cf_2026" name="cf_2026_'.$i.'" value="'.date('Y-m-d').'" data-rule-date="true">
  </div>
  </td>

  <td class="fieldValue">
  <textarea rows="6" cols="8" id="cf_3076_'.$i.'" readonly class="inputElement " name="cf_3076_'.$i.'" style="min-width:120px"></textarea>
  <br/>
  &nbsp;&nbsp;&nbsp;<input type="button" data-target="#myModal'.$i.'" data-toggle="modal" value="Select Serial Number" id="selserial'.$i.'" name="selserial'.$i.'" class="btn btn-primary selectserialwindow" />

  <!--Start The Modal -->
  <div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">

                  <h4 class="modal-title" id="myModalLabel">Serial Number List</h4>
          </div>
  		<div class="modalbody_content">
               <div class="modal-body'.$i.'">

  		     </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-success save_sno" id="save_'.$i.'">Save</button>
                   <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                  </div>
  		</div>
         </div>
      </div>
  </div>
  <!--End The Modal -->
  </td>

  <td class="fieldValue">
  <textarea rows="6" cols="8" id="cf_3078_'.$i.'" readonly class="inputElement " name="cf_3078_'.$i.'" style="min-width:120px"></textarea>
  </td>

  <td class="fieldValue">
  <textarea rows="6" cols="8" id="cf_2032_'.$i.'" class="inputElement " name="cf_2032_'.$i.'" style="min-width:120px"></textarea>
  </td>
  </tr>';
  $rcnt = $rcnt.",".$i;
  $i++;
  }
  }


  }
  $rcnt = ltrim($rcnt,",");
  $response['rowcount'] = $rcnt;
  $response['message'] = $html;
  $response['srvresponse'] = $serv;
  return $response;

}

function getSOLineItemforOBD($id){
$response = array();
$serv = 0;
$hid = "";
$html = '';
$countstring = '';
$savestatestatus = 0;
$str_h = "SELECT arocrm_salesorder.*,arocrm_salesordercf.*,arocrm_crmentity.* FROM arocrm_salesorder
INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid=arocrm_salesorder.salesorderid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_salesorder.salesorderid
WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesorder.salesorderid = '".$id."' AND arocrm_salesorder.sostatus = 'Approved'";
$query_h = mysql_query($str_h);
$count = mysql_num_rows($query_h);
if($count == 1)
{

$rows = mysql_fetch_array($query_h);



$response['customerid'] = $rows['accountid'];
$custnm = mysql_fetch_array(mysql_query("SELECT `accountname` FROM `arocrm_account` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_account`.`accountid` WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_account`.`accountid` = '".$rows['accountid']."'"));
$response['customername'] = $custnm['accountname'];



$plantid = $rows['cf_nrl_plantmaster580_id'];
$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
						  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid 
						  WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
						  $plantrow = mysql_fetch_array($plantsql);
						  $plantname = $plantrow['name'];

						  
$storesql =  mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation`
 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_storagelocation.storagelocationid
 WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."' AND `arocrm_storagelocation`.`name` like '%Main Store%'");
$resultstore = mysql_fetch_array($storesql);

$sqlcstdata = mysql_query("SELECT * FROM `arocrm_account`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_account`.`accountid`
INNER JOIN `arocrm_accountscf` ON `arocrm_accountscf`.`accountid` = `arocrm_account`.`accountid`
WHERE `arocrm_account`.`accountid` = '".$rows['accountid']."' AND `arocrm_crmentity`.`deleted` = '0'");
$sqlcstdataarr = mysql_fetch_array($sqlcstdata);

$creditlimit = $sqlcstdataarr['cf_4313'];
$creditdays = $sqlcstdataarr['cf_4315'];
if($creditlimit > 0 && $creditdays > 0){
$savestatestatus = 1;
}

$deldate =  $rows['cf_3080'];
$sql_i = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$id."' ORDER BY `arocrm_inventoryproductrel`.`sequence_no` ASC");
$sql_num = mysql_num_rows($sql_i);
if($sql_num==1){
$hid = "style='display:none;'";

}
$i = 1;
$rcnt = "";
while($row = mysql_fetch_array($sql_i)){

$productid = $row['productid'];
$product_array = getProductDetails($productid);
$productname = $product_array['productname'];
$productcode = $product_array['productcode'];
$productunit = $product_array['unit'];

$qty = number_format((float)$row['quantity'], 2, '.', '');
$listprice = number_format((float)$row['listprice'], 2, '.', '');

$str_h_i = mysql_query("SELECT SUM(arocrm_outbounddelivery_line_item_lineitem.cf_2014) as totalqty FROM arocrm_outbounddelivery
INNER JOIN arocrm_outbounddelivery_line_item_lineitem ON arocrm_outbounddelivery_line_item_lineitem.outbounddeliveryid=arocrm_outbounddelivery.outbounddeliveryid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_outbounddelivery.outbounddeliveryid
WHERE arocrm_crmentity.deleted = '0' AND arocrm_outbounddelivery.cf_nrl_salesorder679_id = '".$id."' AND arocrm_outbounddelivery_line_item_lineitem.cf_2006 = '".$productid."'");
$res_h_i = mysql_fetch_array($str_h_i);
$tqy = $res_h_i['totalqty'];
if($tqy==""){
$tqy = 0;
}
$rty = (float)$row['quantity'] - (float)$tqy;
$reqty = number_format($rty, 2, '.', '');
if($reqty > 0){
$serv = 1;
$html .= '
<tr id="Line_Item__row_'.$i.'" class="tr_clone">
<td>
<i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" '.$hid.'></i>
&nbsp;
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag">
</a>
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2006_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_2006_'.$i.'">
<input id="cf_2006_display_'.$i.'" name="cf_2006_display_'.$i.'" data-fieldname="cf_2006" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'" readonly placeholder="Type to search" autocomplete="off">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_2004_'.$i.'" type="text" data-fieldname="cf_2004" data-fieldtype="string" style="min-width:120px" readonly class="inputElement " name="cf_2004_'.$i.'" value="'.$productcode.'">
</td>

<td class="fieldValue">
<input id="cf_2018_'.$i.'" type="text" data-fieldname="cf_2018" data-fieldtype="string"  style="min-width:100px" readonly class="inputElement " name="cf_2018_'.$i.'" value="'.$productunit.'">
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2010_'.$i.'" type="hidden" value="'.trim($resultstore['storagelocationid']).'" class="sourceField" data-displayvalue="" id="cf_2010_'.$i.'">
<input id="cf_2010_display_'.$i.'" name="cf_2010_display_'.$i.'" data-fieldname="cf_2010" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.trim($resultstore['name']).'" placeholder="Type to search" autocomplete="off">
</div>
</td>

<td class="fieldValue">
<input id="cf_2012_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_2012_'.$i.'" value="'.$qty.'" />
</td>

<td class="fieldValue">
<input id="cf_2014_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2014_'.$i.'" value="'.$reqty.'" max="'.$reqty.'" />

<input id="cf_2020_'.$i.'" style="min-width:80px;" readonly type="hidden" class="inputElement" step="0.01" name="cf_2020_'.$i.'" value="'.$listprice.'">

<input id="cf_4925_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" step="0.01" name="cf_4925_'.$i.'" value="'.$reqty*$listprice.'">
</td>


<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2026_'.$i.'" type="date" class="form-control " data-fieldname="cf_2026" name="cf_2026_'.$i.'" value="'.date('Y-m-d').'" data-rule-date="true" />
</div>
</td>


<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2022_'.$i.'" type="date" class="form-control" readonly data-fieldname="cf_2022" name="cf_2022_'.$i.'" value="'.$deldate.'" data-rule-date="true" />
</div>
</td>



<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3076_'.$i.'" readonly class="inputElement " name="cf_3076_'.$i.'" style="min-width:120px"></textarea>
<br/>
&nbsp;&nbsp;&nbsp;<input type="button" data-target="#myModal'.$i.'" data-toggle="modal" value="Select Serial Number" id="selserial'.$i.'" name="selserial'.$i.'" class="btn btn-primary selectserialwindow" />

<!--Start The Modal -->
<div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Serial Number List</h4>
        </div>
		<div class="modalbody_content">
             <div class="modal-body'.$i.'">

		     </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-success save_sno" id="save_'.$i.'">Save</button>
                 <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
		</div>
       </div>
    </div>
</div>
<!--End The Modal -->
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_3078_'.$i.'" readonly class="inputElement " name="cf_3078_'.$i.'" style="min-width:120px"></textarea>
</td>

<td class="fieldValue">
<textarea rows="6" cols="8" id="cf_2032_'.$i.'" class="inputElement " name="cf_2032_'.$i.'" style="min-width:120px"></textarea>
</td>
</tr>';
$rcnt = $rcnt.",".$i;
$i++;
}
}


}
$rcnt = ltrim($rcnt,",");
$response['rowcount'] = $rcnt;
$response['message'] = $html;
$response['srvresponse'] = $serv;
$response['savestatestatus'] = $savestatestatus;
$response['plantid'] = $plantid;
$response['plantname'] = $plantname;
return $response;
}

function getSTPOLineItemforIBD($id){

  $response = array();
  $html = '';
  $countstring = '';
  $str_h = "SELECT arocrm_purchaseorder.*,arocrm_purchaseordercf.*,arocrm_crmentity.* FROM arocrm_purchaseorder
  INNER JOIN arocrm_purchaseordercf ON arocrm_purchaseordercf.purchaseorderid=arocrm_purchaseorder.purchaseorderid
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchaseorder.purchaseorderid
  WHERE arocrm_crmentity.deleted = 0 AND arocrm_purchaseorder.purchaseorderid = '".$id."' AND arocrm_purchaseorder.postatus = 'Approved'";
  $query_h = mysql_query($str_h);
  $count = mysql_num_rows($query_h);
  if($count == 1)
  {
  $row = mysql_fetch_array($query_h);

  $pono = $row['purchaseorder_no'];
  $podate = $row['cf_2756'];
  $vendorid = $row['vendorid'];

  $response['podate'] = $podate;
  $response['pono'] = $pono;
  $response['reference'] = $row['cf_2712'];
  $response['plantid'] = $row['cf_nrl_plantmaster950_id'];

  $str_p = "SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
  WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$row['cf_nrl_plantmaster950_id']."'";

  $query_p = mysql_query($str_p);
  $countp = mysql_num_rows($query_p);

  if($countp == 1){
  $rowp = mysql_fetch_array($query_p);
  $response['plantcode'] = $rowp['plantmasterno'];
  $response['plantname'] = $rowp['name'];
  }


  $str_v = "SELECT arocrm_vendor.*,arocrm_crmentity.* FROM arocrm_vendor
  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_vendor.vendorid
  WHERE arocrm_crmentity.deleted=0 AND arocrm_vendor.vendorid = '".$vendorid."'";

  $query_v = mysql_query($str_v);
  $countv = mysql_num_rows($query_v);

  if($countv == 1){
  $rowv = mysql_fetch_array($query_v);

  $response['vendorcode'] = $rowv['vendor_no'];
  $response['vendorname'] = $rowv['vendorname'];
  $response['vendorid'] = $rowv['vendorid'];

  }

  $i = 1;
  $dis = "";
  $str_i = "SELECT `arocrm_goodsissue`.*,`arocrm_goodsissuecf`.*,`arocrm_goodsissue_line_item_lineitem`.* FROM `arocrm_goodsissue`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_goodsissue`.`goodsissueid`
INNER JOIN `arocrm_goodsissuecf` ON `arocrm_goodsissuecf`.`goodsissueid` = `arocrm_goodsissue`.`goodsissueid`
INNER JOIN `arocrm_goodsissue_line_item_lineitem` ON `arocrm_goodsissue_line_item_lineitem`.`goodsissueid` = `arocrm_goodsissue`.`goodsissueid`
WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_goodsissue`.`cf_nrl_outbounddelivery617_id` = (SELECT `arocrm_outbounddelivery`.`outbounddeliveryid`
  FROM `arocrm_outbounddelivery`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_outbounddelivery`.`outbounddeliveryid`
WHERE `cf_nrl_purchaseorder165_id` = '".$id."' AND `arocrm_crmentity`.`deleted` = '0')";
  $query_i = mysql_query($str_i);
  $counti = mysql_num_rows($query_i);

  $plantid = $row['cf_nrl_plantmaster950_id'];

  if($counti==1){
  $dis = 'style="display: none;"';
  }
  if($counti > 0){
  while($rowi = mysql_fetch_array($query_i)){

  $productid = $rowi['cf_3163'];
  $product_array = getProductDetails($productid);
  $productname = $product_array['productname'];
  $productcode = $product_array['productcode'];
  $productunit = $product_array['unit'];
  $productwarranty = $product_array['warranty'];
  $serialnus = $rowi['cf_3179'];
  
  $stores = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_storagelocation` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid` WHERE `arocrm_storagelocation`.`name` LIKE '%Quarantine%' AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."'"));
  

  $reqty = number_format((float)$rowi['cf_3171'], 2, '.', '');


  $price = number_format((float)$rowi['cf_3175'], 2, '.', '');

  $totalprice = $price * $reqty;

  $rowpoitem = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$id."' AND `productid` = '".$productid."'"));
  $delivery_date = $rowpoitem['delivery_date'];

  $html .='
  <tr id="Inbound_Delivery_LineItem__row_'.$i.'" class="tr_clone">
  <td>&nbsp;
  <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
  </td>

  <td class="fieldValue">
  <div class="referencefield-wrapper ">
  <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
  <div class="input-group">
  <input name="cf_2868_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="'.$productname.'" id="cf_2868_'.$i.'">
  <input id="cf_2868_display_'.$i.'" name="cf_2868_display_'.$i.'" readonly data-fieldname="cf_2868" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"  autocomplete="off">
  </div>
  </div>
  </td>

  <td class="fieldValue">
  <input id="cf_2870_'.$i.'" style="min-width:140px;" type="text" data-fieldname="cf_2870" data-fieldtype="string" readonly class="inputElement " name="cf_2870_'.$i.'" value="'.$productcode.'" />
  </td>


  <td class="fieldValue">
  <input id="cf_2872_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_2872" data-fieldtype="string" class="inputElement " name="cf_2872_'.$i.'" readonly value="'.$productunit.'" />
  </td>


  <td class="fieldValue">
  <div class="referencefield-wrapper ">
  <input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
  <div class="input-group">
  <input name="cf_2874_'.$i.'" type="hidden" value="'.trim($stores['storagelocationid']).'" class="sourceField" data-displayvalue="" id="cf_2874_'.$i.'">
  <input id="cf_2874_display_'.$i.'" name="cf_2874_display_'.$i.'" data-fieldname="cf_2874" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.trim($stores['name']).'" readonly data-rule-required="true" data-rule-reference_required="true" autocomplete="off"> 
  </div>

  </div>
  </td>

  <td class="fieldValue">
  <input id="cf_2876_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly step="0.01" name="cf_2876_'.$i.'" value="'.$reqty.'">
  </td>

  <td class="fieldValue">
  <input id="cf_2878_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly min="1" max="'.$reqty.'"  step="0.01" name="cf_2878_'.$i.'" value="'.$reqty.'">
  <script>
  jQuery("[name=cf_2878_'.$i.']").keyup(function(){
  var qty = $("[name=cf_2880_'.$i.']").val();
  var unit = $(this).val();
  if(unit=="" || unit==undefined){
  unit = 0;
  }
  $("[name=cf_2882_'.$i.']").val(parseFloat(qty) * parseFloat(unit));
  });
  </script>
  
  <input id="cf_2880_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2880_'.$i.'" value="'.$price.'">
  
  <input id="cf_2882_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2882_'.$i.'" value="'.$totalprice.'">
  </td>

  <td class="fieldValue">
  <input id="InboundDelivery_editView_fieldName_cf_5031_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_5031" data-fieldtype="string" class="inputElement" readonly name="cf_5031_'.$i.'" value="'.$productwarranty.'">
  </td>
  
  
   <td class="fieldValue">
  <div class="input-group inputElement" style="margin-bottom: 3px">
  <input id="cf_2886_'.$i.'" type="date" class="form-control" readonly data-fieldname="cf_2886" name="cf_2886_'.$i.'" value="'.date('Y-m-d').'" />
  </div>
  </td>
  
  <td class="fieldValue">
  <div class="input-group inputElement" style="margin-bottom: 3px">
  <input id="cf_2884_'.$i.'" type="date" class="form-control " data-fieldname="cf_2884" name="cf_2884_'.$i.'" readonly value="'.$delivery_date.'" />
  </div>
  </td>

 

  <td class="fieldValue">
  <textarea rows="6" readonly  style="min-width:140px;" id="cf_2888_'.$i.'"  readonly class="inputElement " name="cf_2888_'.$i.'">'.$serialnus.'</textarea><br/>
  </td>

  <td class="fieldValue">
  <textarea rows="6" readonly  style="min-width:140px;" id="cf_2890_'.$i.'" class="inputElement" readonly name="cf_2890_'.$i.'">'.$serialnus.'</textarea>
  </td>

  <td class="fieldValue">
  <textarea rows="6"  style="min-width:140px;" id="cf_2892_'.$i.'" class="inputElement " name="cf_2892_'.$i.'"> </textarea>
  </td>

  </tr>
  ';

  $countstring .= $i.',';
  $i++;


  }

  $countstring = rtrim($countstring,',');
  $response['rowcount'] = $countstring;
  $response['message'] = $html;
  }


  }

  return $response;

}

function getSOReturnDetailsforIBD($id){
  $response = array();
  $html = '';
  $serials = array();

$serialsql = mysql_query("SELECT `arocrm_serialnumber`.*,`arocrm_serialnumbercf`.* FROM `arocrm_serialnumber`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_serialnumber`.`serialnumberid`
INNER JOIN `arocrm_serialnumbercf` ON `arocrm_serialnumbercf`.`serialnumberid` = `arocrm_serialnumber`.`serialnumberid`
WHERE `arocrm_crmentity`.`deleted` = '0'
AND `arocrm_serialnumbercf`.`cf_1256` = 'R' AND `arocrm_serialnumbercf`.`cf_2834` = '2' AND `arocrm_serialnumbercf`.`cf_3084` = 'R'
AND `arocrm_serialnumbercf`.`cf_3128` IN (SELECT `outbounddeliveryid` FROM `arocrm_outbounddelivery`
WHERE `cf_nrl_salesorder679_id` IN (SELECT `cf_nrl_salesorder922_id` FROM `arocrm_salesreturn`
  WHERE `salesreturnid` = '".$id."'))");
while($sr = mysql_fetch_array($serialsql)){
array_push($serials,$sr['cf_nrl_products16_id']."__".$sr['cf_1258']);
}


    $invsql = mysql_fetch_array(mysql_query("SELECT `arocrm_invoice`.*,`arocrm_invoicecf`.* FROM `arocrm_invoice` 
	INNER JOIN `arocrm_invoicecf` ON `arocrm_invoicecf`.`invoiceid`=`arocrm_invoice`.`invoiceid` 
	INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_invoice`.`invoiceid` 
	WHERE `arocrm_invoice`.`invoiceid` = (SELECT `cf_nrl_invoice621_id` FROM `arocrm_salesreturn` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_salesreturn`.`salesreturnid` INNER JOIN `arocrm_salesreturncf` ON `arocrm_salesreturncf`.`salesreturnid`=`arocrm_salesreturn`.`salesreturnid` WHERE `arocrm_salesreturn`.`salesreturnid` = '".$id."' AND `arocrm_crmentity`.`deleted` = 0 AND `arocrm_salesreturncf`.`cf_4819` = 'Approved') AND `arocrm_crmentity`.`deleted` = 0"));
	
	$tmp1 = explode("-",$invsql['cf_4627']);
	
	$response['invoiceno'] = $invsql['invoice_no'];
    $response['invoicedate'] = $tmp1[2]."-".$tmp1[1]."-".$tmp1[0];

	$response['serialnos'] = $serials;
	$custsql = mysql_fetch_array(mysql_query("SELECT `arocrm_salesreturn`.* FROM `arocrm_salesreturn`
	INNER JOIN `arocrm_salesreturncf` ON `arocrm_salesreturncf`.`salesreturnid` = `arocrm_salesreturn`.`salesreturnid`
	INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_salesreturn`.`salesreturnid`
	WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_salesreturn`.`salesreturnid` = '".$id."' AND `arocrm_salesreturncf`.`cf_4819` = 'Approved'"));
	$response['custid'] = $custsql['cf_nrl_accounts633_id'];
	
	$custnamesql = mysql_fetch_array(mysql_query("SELECT `arocrm_account`.`accountname` FROM `arocrm_account`
	INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_account`.`accountid`
	WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_account`.`accountid` = '".$response['custid']."'"));
	$response['custname'] = $custnamesql['accountname'];

	$response['plantid'] = $custsql['cf_nrl_plantmaster177_id'];
	$plantnamesql = mysql_fetch_array(mysql_query("SELECT `arocrm_plantmaster`.`name` FROM `arocrm_plantmaster`
	INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_plantmaster`.`plantmasterid`
	WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_plantmaster`.`plantmasterid` = '".$response['plantid']."'"));
	$response['plantname'] = $plantnamesql['name'];

  $ssitem = "SELECT * FROM `arocrm_salesreturn_line_item_lineitem` WHERE `salesreturnid` = '".$id."'";
  $sqlitem = mysql_query($ssitem);
  $i = 1;
  $seqcnt = '';
  while($row = mysql_fetch_array($sqlitem)){

    $productid = $row['cf_3268'];
    $product_array = getProductDetails($productid);
    $productname = $product_array['productname'];
    $productcode = $product_array['productcode'];
    $unit = $product_array['unit'];
	$warranty = $product_array['warranty'];
	
    $qty =  number_format((float)$row['cf_3274'], 2, '.', '');
    $listprice = number_format((float)$row['cf_3276'], 2, '.', '');
    $totalprice = number_format((float)$listprice * $qty, 2, '.', '');
	
	$storesql =  mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation`
 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_storagelocation.storagelocationid
 WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$custsql['cf_nrl_plantmaster177_id']."' AND `arocrm_storagelocation`.`name` like '%%Quarantine%%'");
$resultstore = mysql_fetch_array($storesql);

    $html .='
    <tr id="Inbound_Delivery_LineItem__row_'.$i.'" class="tr_clone">
    <td>
    <a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
    </td>

    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
    <div class="input-group">
    <input name="cf_2868_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="'.$productname.'" id="cf_2868_'.$i.'">
    <input id="cf_2868_display_'.$i.'" name="cf_2868_display_'.$i.'" readonly data-fieldname="cf_2868" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"  autocomplete="off">
    </div>
    </div>
    </td>

    <td class="fieldValue">
    <input id="cf_2870_'.$i.'" style="min-width:140px;" type="text" data-fieldname="cf_2870" data-fieldtype="string" readonly class="inputElement " name="cf_2870_'.$i.'" value="'.$productcode.'" />
    </td>


    <td class="fieldValue">
    <input id="cf_2872_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_2872" data-fieldtype="string" class="inputElement " name="cf_2872_'.$i.'" readonly value="'.$unit.'" />
    </td>


    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
    <div class="input-group">
    <input name="cf_2874_'.$i.'" type="hidden" value="'.$resultstore['storagelocationid'].'" class="sourceField" data-displayvalue="" id="cf_2874_'.$i.'">
    <input id="cf_2874_display_'.$i.'" name="cf_2874_display_'.$i.'" data-fieldname="cf_2874" readonly data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$resultstore['name'].'" data-rule-required="true" data-rule-reference_required="true" autocomplete="off">
    
    
    </div>
   
    </div>
    </td>

    <td class="fieldValue">
    <input id="cf_2876_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly step="0.01" name="cf_2876_'.$i.'" value="'.$qty.'">
    </td>

    <td class="fieldValue">
    <input id="cf_2878_'.$i.'" style="min-width:80px;" type="number" class="inputElement" min="1" readonly max="'.$qty.'"  step="0.01" name="cf_2878_'.$i.'" value="'.$qty.'">

    
    <input id="cf_2880_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2880_'.$i.'" value="'.$listprice.'">

    <input id="cf_2882_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2882_'.$i.'" value="'.$totalprice.'">
    </td>

	
	<td class="fieldValue">
	<input id="cf_5031_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_5031" data-fieldtype="string" class="inputElement " name="cf_5031_'.$i.'" readonly value="'.$warranty.'">
	</td>
	
    <td class="fieldValue">
    <div class="input-group inputElement" style="margin-bottom: 3px">
    <input id="cf_2884_'.$i.'" type="date" class="form-control " data-fieldname="cf_2884" name="cf_2884_'.$i.'" readonly value="'.date('Y-m-d').'" />
    </div>
    </td>

    <td class="fieldValue">
    <div class="input-group inputElement" style="margin-bottom: 3px">
    <input id="cf_2886_'.$i.'" type="date" class="form-control" readonly data-fieldname="cf_2886" name="cf_2886_'.$i.'" value="'.date('Y-m-d').'" />
    </div>
    </td>

    <td class="fieldValue">
    <textarea rows="6"  style="min-width:140px;" id="cf_2888_'.$i.'"  readonly class="inputElement " name="cf_2888_'.$i.'"></textarea><br/>
    &nbsp;&nbsp;&nbsp;<input type="button" data-target="#myModal'.$i.'" data-toggle="modal" value="Serial Number" id="serial'.$i.'" name="serial'.$i.'" class="btn btn-success serialwindow" />

    <!--Start The Modal -->
    <div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Serial Number List</h4>
            </div>
    		<div class="modalbody_content">
                 <div class="modal-body'.$i.'">

    		     </div>
                    <div class="modal-footer">
                     <button type="button" class="btn btn-success save_sno" id="save_'.$i.'">Save</button>
                     <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
    		</div>
           </div>
        </div>
    </div>
    <!--End The Modal -->

    </td>

    <td class="fieldValue">
    <textarea rows="6"  style="min-width:140px;" id="cf_2890_'.$i.'" class="inputElement" readonly name="cf_2890_'.$i.'"></textarea>
    </td>

    <td class="fieldValue">
    <textarea rows="6"  style="min-width:140px;" id="cf_2892_'.$i.'" class="inputElement " name="cf_2892_'.$i.'"> </textarea>
    </td>

    </tr>
    ';

  $seqcnt = $seqcnt.','.$i;
  $i++;

  }

  $seqc = ltrim($seqcnt,",");
  $response['rowcount'] = $seqc;
  $response['message'] = $html;
  return $response;


}


function getPOLineItemforIBD($id){
$response = array();
$html = '';
$countstring = '';
$str_h = "SELECT arocrm_purchaseorder.*,arocrm_purchaseordercf.*,arocrm_crmentity.* FROM arocrm_purchaseorder
INNER JOIN `arocrm_purchaseordercf` ON arocrm_purchaseordercf.purchaseorderid=arocrm_purchaseorder.purchaseorderid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchaseorder.purchaseorderid
WHERE arocrm_crmentity.deleted = 0 AND arocrm_purchaseorder.purchaseorderid = '".$id."'
AND `arocrm_purchaseorder`.`postatus` = 'Approved' AND `arocrm_purchaseordercf`.`cf_2709` NOT IN ('Provisional')";
$query_h = mysql_query($str_h);
$count = mysql_num_rows($query_h);
if($count == 1)
{
$row = mysql_fetch_array($query_h);

$pono = $row['purchaseorder_no'];
$podate = $row['cf_3653'];
$vendorid = $row['vendorid'];

$response['podate'] = $podate;
$response['pono'] = $pono;
$response['reference'] = $row['cf_2712'];
$response['plantid'] = $row['cf_nrl_plantmaster950_id'];

$str_p = "SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$row['cf_nrl_plantmaster950_id']."'";

$query_p = mysql_query($str_p);
$countp = mysql_num_rows($query_p);

if($countp == 1){
$rowp = mysql_fetch_array($query_p);
$response['plantcode'] = $rowp['plantmasterno'];
$response['plantname'] = $rowp['name'];
}


$str_v = "SELECT arocrm_vendor.*,arocrm_crmentity.* FROM arocrm_vendor
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_vendor.vendorid
WHERE arocrm_crmentity.deleted=0 AND arocrm_vendor.vendorid = '".$vendorid."'";

$query_v = mysql_query($str_v);
$countv = mysql_num_rows($query_v);

if($countv == 1){
$rowv = mysql_fetch_array($query_v);

$response['vendorid'] = $vendorid;
$response['vendorcode'] = $rowv['vendor_no'];
$response['vendorname'] = $rowv['vendorname'];

}

$i = 1;
$dis = "";
$str_i = "SELECT * FROM arocrm_inventoryproductrel WHERE id = '".$id."'";
$query_i = mysql_query($str_i);
$counti = mysql_num_rows($query_i);

$plantid = $row['cf_nrl_plantmaster950_id'];

if($counti==1){
$dis = 'style="display: none;"';
}
if($counti > 0){
while($rowi = mysql_fetch_array($query_i)){

$productid = $rowi['productid'];
$product_array = getProductDetails($productid);
$productname = $product_array['productname'];
$productcode = $product_array['productcode'];
$productunit = $product_array['unit'];
$warranty = $product_array['warranty'];

$reqty = number_format((float)$rowi['quantity'], 2, '.', '');
$warry = $warranty;

$chkline = mysql_query("SELECT sum(arocrm_inbounddelivery_line_item_lineitem.cf_2878) as qty,sum(arocrm_inbounddelivery_line_item_lineitem.cf_5031) as warr FROM arocrm_inbounddelivery
INNER JOIN arocrm_inbounddelivery_line_item_lineitem ON arocrm_inbounddelivery_line_item_lineitem.inbounddeliveryid=arocrm_inbounddelivery.inbounddeliveryid
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_inbounddelivery.inbounddeliveryid
WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddelivery.cf_nrl_purchaseorder573_id = '".$id."' and arocrm_inbounddelivery_line_item_lineitem.cf_2868 = '".$productid."'");

$count_rw = mysql_num_rows($chkline);
$chkrow = mysql_fetch_array($chkline);
if($count_rw > 0){

$reqty = number_format((float)$rowi['quantity'] - $chkrow['qty'], 2, '.', '');

}
$price = number_format((float)$rowi['listprice'], 2, '.', '');

if($reqty > 0){

$totalprice = $price * $reqty;

$storesql =  mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation`
 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_storagelocation.storagelocationid
 WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."' AND `arocrm_storagelocation`.`name` like '%%Quarantine%%'");
$resultstore = mysql_fetch_array($storesql);


$html .='
<tr id="Line_Item__row_'.$i.'" class="tr_clone">
<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" '.$dis.'></i>&nbsp;
<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
</td>

<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2868_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="'.$productname.'" id="cf_2868_'.$i.'">
<input id="cf_2868_display_'.$i.'" name="cf_2868_display_'.$i.'" readonly data-fieldname="cf_2868" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"  autocomplete="off">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_2870_'.$i.'" style="min-width:140px;" type="text" data-fieldname="cf_2870" data-fieldtype="string" readonly class="inputElement " name="cf_2870_'.$i.'" value="'.$productcode.'" />
</td>


<td class="fieldValue">
<input id="cf_2872_'.$i.'" type="text" style="min-width:80px;" data-fieldname="cf_2872" data-fieldtype="string" class="inputElement " name="cf_2872_'.$i.'" readonly value="'.$productunit.'" />
</td>


<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2874_'.$i.'" type="hidden" value="'.trim($resultstore['storagelocationid']).'" class="sourceField" data-displayvalue="" id="cf_2874_'.$i.'">
<input id="cf_2874_display_'.$i.'" name="cf_2874_display_'.$i.'" data-fieldname="cf_2874" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.trim($resultstore['name']).'" data-rule-required="true" data-rule-reference_required="true" autocomplete="off">
</div>
</div>
</td>

<td class="fieldValue">
<input id="cf_2876_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly step="0.01" name="cf_2876_'.$i.'" value="'.$rowi['quantity'].'">
</td>

<td class="fieldValue">
<input id="cf_2878_'.$i.'" style="min-width:80px;" type="number" class="inputElement" min="1" max="'.$reqty.'"  step="0.01" name="cf_2878_'.$i.'" value="'.$reqty.'">
<script>
jQuery("[name=cf_2878_'.$i.']").keyup(function(){
var qty = $("[name=cf_2880_'.$i.']").val();
var unit = $(this).val();
if(unit=="" || unit==undefined){
unit = 0;
}
$("[name=cf_2882_'.$i.']").val(parseFloat(qty) * parseFloat(unit));
});
</script>


<input id="cf_2880_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2880_'.$i.'" value="'.$price.'">

<input id="cf_2882_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2882_'.$i.'" value="'.$totalprice.'">
</td>

<td class="fieldValue"><input id="cf_5031_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_5031" data-fieldtype="string" class="inputElement " name="cf_5031_'.$i.'" readonly value="'.$warranty.'"></td>


<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2886_'.$i.'" type="date" class="form-control" readonly data-fieldname="cf_2886" name="cf_2886_'.$i.'" value="'.date('Y-m-d').'" />
</div>
</td>


<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2884_'.$i.'" type="date" class="form-control " data-fieldname="cf_2884" name="cf_2884_'.$i.'" readonly value="'.$rowi['delivery_date'].'" />
</div>
</td>


<td class="fieldValue">
<textarea rows="6"  style="min-width:140px;" id="cf_2888_'.$i.'"  class="inputElement " name="cf_2888_'.$i.'"></textarea><br/>
&nbsp;&nbsp;&nbsp;<input type="button" data-target="#myModal'.$i.'" data-toggle="modal" value="Serial Number" id="serial'.$i.'" name="serial'.$i.'" class="btn btn-success serialwindow" />

<!--Start The Modal -->
<div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Serial Number List</h4>
        </div>
		<div class="modalbody_content">
             <div class="modal-body'.$i.'">

		     </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-success save_sno" id="save_'.$i.'">Save</button>
                 <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
		</div>
       </div>
    </div>
</div>
<!--End The Modal -->

</td>

<td class="fieldValue">
<textarea rows="6"  style="min-width:140px;" id="cf_2890_'.$i.'" class="inputElement" readonly name="cf_2890_'.$i.'"></textarea>
</td>

<td class="fieldValue">
<textarea rows="6"  style="min-width:140px;" id="cf_2892_'.$i.'" class="inputElement " name="cf_2892_'.$i.'"> </textarea>
</td>

</tr>
';

$countstring .= $i.',';
$i++;

}
}

$countstring = rtrim($countstring,',');
$response['rowcount'] = $countstring;
$response['message'] = $html;
}


}

return $response;
}


function getSTPRLineItemforPO($id){
$response = array();
$html = '';
$amount = 0;
$html .='
<tr><td><strong>TOOLS</strong></td><td><span class="redColor">*</span><strong>Item Name</strong></td><td><strong class="pull-right" style="float:left!important;">Item Code</strong></td><td><strong class="pull-right" style="float:left!important;">Unit</strong></td><td><strong>Quantity</strong></td><td><strong>Warranty</strong></td><td><strong>List Price</strong></td><td><strong>INR Rate</strong></td><td><strong>Delivery Date</strong></td><td><strong class="pull-right">Total</strong></td><td><strong class="pull-right">Net Price</strong></td></tr>';
$i=1;
$countstring = '';
$requisition_date = '';
$str_h = "SELECT arocrm_stockrequisition.*,arocrm_stockrequisitioncf.*,arocrm_crmentity.* FROM arocrm_stockrequisition
		              INNER JOIN arocrm_stockrequisitioncf ON arocrm_stockrequisitioncf.stockrequisitionid=arocrm_stockrequisition.stockrequisitionid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_stockrequisition.stockrequisitionid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_stockrequisition.stockrequisitionid = '".$id."' AND arocrm_stockrequisitioncf.cf_4807 = 'Approved'";
                      $query_h = mysql_query($str_h);
					  $count = mysql_num_rows($query_h);
						if($count == 1)
						{
						  $row = mysql_fetch_array($query_h);
						  $plantid = $row['cf_nrl_plantmaster765_id'];

							$str_hpla = mysql_query("SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
							INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
							WHERE arocrm_crmentity.deleted=0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
							$row_plant = mysql_fetch_array($str_hpla);
							
							$frmstr_hpla = mysql_query("SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
							INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantmaster.plantmasterid
							WHERE arocrm_crmentity.deleted=0 AND arocrm_plantmaster.plantmasterid = '".$row['cf_nrl_plantmaster587_id']."'");
							$from_row_plant = mysql_fetch_array($frmstr_hpla);


   						  $str_date = $row['cf_1497'];
						  $req_no = $row['stockrequisitionno'];
						  $str_date = date("d-m-Y", strtotime($str_date));

						  $response['str_no'] = $req_no;
						  $response['str_date'] = $str_date;
						  $response['plant_display_name'] = $row_plant['name'];
						  $response['plant_id'] = $plantid;
						  
						  $response['from_plant_display_name'] = $from_row_plant['name'];
	                      $response['from_plant_id'] = $row['cf_nrl_plantmaster587_id'];



				    $linesdsql = "SELECT arocrm_stockrequisition.*,arocrm_stockrequisitioncf.*,arocrm_crmentity.*,arocrm_stockrequisition_line_item_details_lineitem .* FROM arocrm_stockrequisition
		              INNER JOIN arocrm_stockrequisitioncf ON arocrm_stockrequisitioncf.stockrequisitionid=arocrm_stockrequisition.stockrequisitionid
					   INNER JOIN arocrm_stockrequisition_line_item_details_lineitem ON arocrm_stockrequisition.stockrequisitionid=arocrm_stockrequisition_line_item_details_lineitem.stockrequisitionid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_stockrequisition.stockrequisitionid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_stockrequisition.stockrequisitionid = '".$id."' AND arocrm_stockrequisitioncf.cf_4807 = 'Approved'";
					$query_lineitem = mysql_query($linesdsql);
					  $count_lineitem = mysql_num_rows($query_lineitem);

						if($count_lineitem > 0)
						{
							$cnt = 0;
						  while($row_lineitem = mysql_fetch_array($query_lineitem))
						  {

						  $productid = $row_lineitem['cf_1553'];
						  $product_array = getProductDetails($productid);
						  $productname = $product_array['productname'];
						  $productcode = $product_array['productcode'];
						  $warranty = $product_array['warranty'];
						  

						$unitprsql = "SELECT arocrm_plantproductassignment.*,arocrm_plantproductassignmentcf.*,arocrm_crmentity.* FROM arocrm_plantproductassignment
						INNER JOIN arocrm_plantproductassignmentcf ON arocrm_plantproductassignmentcf.plantproductassignmentid=arocrm_plantproductassignment.plantproductassignmentid
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantproductassignment.plantproductassignmentid
						WHERE arocrm_crmentity.deleted=0 AND arocrm_plantproductassignment.cf_nrl_plantmaster103_id = '".$plantid."' AND arocrm_plantproductassignment.cf_nrl_products323_id = '".$productid."'";
						
						$unitprsql = "SELECT `arocrm_rfqmaintain_rfq_lineitem_lineitem`.* FROM `arocrm_rfqmaintain_rfq_lineitem_lineitem` 
						INNER JOIN `arocrm_rfqmaintain` ON `arocrm_rfqmaintain`.`rfqmaintainid` = `arocrm_rfqmaintain_rfq_lineitem_lineitem`.`rfqmaintainid`
						INNER JOIN `arocrm_rfqmaintaincf` ON `arocrm_rfqmaintaincf`.`rfqmaintainid` = `arocrm_rfqmaintain`.`rfqmaintainid`
						INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_rfqmaintain`.`rfqmaintainid`
						WHERE `arocrm_crmentity`.`deleted` = 0 
						AND `arocrm_rfqmaintain_rfq_lineitem_lineitem`.`cf_1957` = '".$productid."'
						AND `arocrm_rfqmaintaincf`.`cf_4811` = 'Approved'
						ORDER BY `arocrm_crmentity`.`createdtime` DESC LIMIT 0,1";
						
						$unitprc = mysql_query($unitprsql);
						$unitprcrw = mysql_fetch_array($unitprc);
						$unit_price_defined = $unitprcrw['cf_1980'];
						$item_description = $product_array['description'];
						$productunit = $product_array['unit'];
						$requisite_qty = $row_lineitem['cf_1561'];
						$unitprice = 0;


						   $chkline = mysql_query("SELECT sum(aip.quantity) as quantity FROM `arocrm_inventoryproductrel` aip INNER JOIN  `arocrm_purchaseorder` apo WHERE apo.purchaseorderid = aip.id AND apo.cf_nrl_stockrequisition812_id = '".$id."' and aip.productid = '".$productid."'");
						  $count_rw = mysql_num_rows($chkline);
						  $chkrow = mysql_fetch_array($chkline);
						  if($count_rw > 0){

							 $requisite_qty = $row_lineitem['cf_1561'] - $chkrow['quantity'];
						  }

						    $delivery_date = $row_lineitem['cf_1567'];

							$unitprice = number_format((float)$unit_price_defined, 2, '.', '');

						  $total_amount = (float)$unitprice * (float)$requisite_qty;
   						  $total_amount =  number_format((float)$total_amount, 2, '.', '');
						  $displaytype = "";
						  $amount = number_format((float)$amount + $total_amount, 2, '.', '');
						if($count_lineitem==1){

					    $displaytype = 'style="display:none;"';
						}

						  if($requisite_qty > 0)
						  {

$html .= '<tr id="row'.$i.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$i.'">
<td style="text-align:center;">
<i class="fa fa-trash deleteRow cursorPointer" title="Delete" '.$displaytype.'></i>&nbsp;
<a><img src="layouts/v7/skins/images/drag.png" border="0" title="Drag"></a>
<input type="hidden" class="rowNumber" value="'.$i.'"></td>

<td><input type="hidden" name="hidtax_row_no'.$i.'" id="hidtax_row_no'.$i.'" value="">
<div class="itemNameDiv form-inline">
<div class="row">
<div class="col-lg-10">
<div class="input-group" style="width:100%">
<input type="text" id="productName'.$i.'" name="productName'.$i.'" value="'.$productname.'" class="productName form-control  autoComplete   ui-autocomplete-input" readonly placeholder="Type to search" data-rule-required="true" autocomplete="off" aria-required="true">

<input type="hidden" id="hdnProductId'.$i.'" name="hdnProductId'.$i.'" value="'.$productid.'" class="selectedModuleId">
<input type="hidden" id="lineItemType'.$i.'" name="lineItemType'.$i.'" value="Products" class="lineItemType">

</div>
</div>
</div>
</div>
<input type="hidden" value="" id="subproduct_ids'.$i.'" name="subproduct_ids'.$i.'" class="subProductIds">
<div id="subprod_names'.$i.'" name="subprod_names'.$i.'" class="subInformation">
<span class="subProductsContainer"></span>
</div>
<div>
<br>
<textarea id="comment'.$i.'" name="comment'.$i.'" class="lineItemCommentBox">'.$item_description.'</textarea>
</div>
</td>
<td>
<input id="productcode'.$i.'" name="productcode'.$i.'" type="text" class="productcode inputElement" readonly="readonly" value="'.$productcode.'">
</td>
<td>
<input id="itemunit'.$i.'" name="itemunit'.$i.'" type="text" class="itemunit inputElement" readonly="readonly" value="'.$productunit.'">
</td>
<td>
<input id="qty'.$i.'" name="qty'.$i.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" data-rule-positive="true" data-rule-greater_than_zero="true" value="'.$requisite_qty.'" aria-required="true" max="'.$requisite_qty.'">
<input type="hidden" name="margin'.$i.'" value="0">
<span class="margin pull-right" style="display:none"></span>
</td>

<td>
<input id="no_warranty_card'.$i.'" name="no_warranty_card'.$i.'" type="text" class="no_warranty_card inputElement" readonly value="'.$warranty.'">
</td>

<td><div>
<input id="listPrice'.$i.'" readonly name="listPrice'.$i.'" value="'.$unitprice.'" type="text"
data-rule-required="true" data-rule-positive="true" class="listPrice smallInputBox inputElement" data-is-price-changed="false" data-base-currency-id="" aria-required="true">&nbsp;</div>
<div style="clear:both"></div>
<div style="display:none;">
<input type="hidden" id="discount_type'.$i.'" name="discount_type'.$i.'" value="zero" class="discount_type">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
<tbody>
<tr>
<td>
<input type="radio" name="discount'.$i.'" checked="" class="discounts" data-discount-type="zero">&nbsp;Zero Discount
</td>
<td>
<input type="hidden" class="discountVal" value="0"></td>
</tr>
<tr>
<td>
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="percentage">&nbsp; %Price
</td>
<td>
<span class="pull-right">&nbsp;%</span>
<input type="text" data-rule-positive="true" data-rule-inventory_percentage="true" id="discount_percentage'.$i.'" name="discount_percentage'.$i.'" value="" class="discount_percentage span'.$i.' pull-right discountVal hide">
</td>
</tr>
<tr>
<td class="LineItemDirectPriceReduction">
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="amount">&nbsp;Direct Price Reduction
</td>
<td>
<input type="text" data-rule-positive="true" id="discount_amount'.$i.'" name="discount_amount'.$i.'" value="" class="span1 pull-right discount_amount discountVal hide">
</td>
</tr>
</tbody>
</table>
</div>
</div>
<div style="width:150px;display:none;"><strong>Total After Discount :</strong></div>
<div class="individualTaxContainer hide">(+)&nbsp;<strong><a href="javascript:void(0)" class="individualTax">Tax </a> : </strong></div>
<span class="taxDivContainer">
<div class="taxUI hide" id="tax_div'.$i.'">
<p class="popover_title hide">Set Tax for : <span class="variable"></span></p>
</div>
</span>
</td>


<td>
<div>
<input id="inr_rate'.$i.'" name="inr_rate'.$i.'"  readonly value="'.$unitprice.'" type="text" data-rule-required="true" data-rule-positive="true"
 class="inr_rate smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id=""
 aria-required="true">
 </div>
 </td>


<td>
<input id="delivery_date'.$i.'" value="'.$delivery_date.'" name="delivery_date'.$i.'" type="date" class="delivery_date inputElement" />
</td>
<td>
<div id="productTotal'.$i.'" align="right" class="productTotal">'.$total_amount.'</div>
<div id="discountTotal'.$i.'" align="right" class="discountTotal">0.00</div>
<div id="totalAfterDiscount'.$i.'" align="right" class="totalAfterDiscount">'.$total_amount.'</div>
<div id="taxTotal'.$i.'" align="right" class="productTaxTotal hide">0.00</div>
</td>
<td>
<span id="netPrice'.$i.'" class="pull-right netPrice">'.$total_amount.'</span>
</td>
</tr>';

						  $i++;

						  }
						  }

						}
						$response['totalcount'] = $i - 1;
						$response['message'] = $html;
						$response['amount'] = $amount;


						}
						return $response;


}


					function getPRLineItemforPO($id,$vendor_id,$month){
					$response = array();
					$html = '';
					$status = 0;
					$amount = 0;
					$html .='
					<tr>
					<td><strong>TOOLS</strong></td>
					<td><span class="redColor">*</span><strong>Item Name</strong></td>
					<td><strong class="pull-right">Item code</strong></td>
					<td><strong class="pull-right">Unit</strong></td>
					<td><strong>Warranty</strong></td>
					<td><strong>Quantity</strong></td>
					<td><strong>List Price</strong></td>
					<td><strong>INR Rate</strong></td>
					<td><strong>Delivery Date</strong></td>
					<td><strong class="pull-right">Total</strong>
					</td><td><strong class="pull-right">Net Price</strong></td>
					</tr>';
					$i=1;
					$countstring = '';
					$requisition_date = '';


					$str_h = "SELECT arocrm_purchasereq.*,arocrm_purchasereqcf.*,arocrm_crmentity.* FROM arocrm_purchasereq
					INNER JOIN arocrm_purchasereqcf ON arocrm_purchasereqcf.purchasereqid=arocrm_purchasereq.purchasereqid
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
					WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid = ".$id." AND arocrm_purchasereqcf.cf_4809 = 'Approved'";
					$query_h = mysql_query($str_h);
					$count = mysql_num_rows($query_h);
					if($count > 0)
					{
						
					$row = mysql_fetch_array($query_h);
					$requisition_date = $row['cf_1759'];
					$req_no = $row['purchasereqno'];
					$requisition_date = date("d-m-Y", strtotime($requisition_date));
					$plantid = $row['cf_nrl_plantmaster436_id'];
					$displaytype = '';

					
					$rfqu_new = "SELECT arocrm_purchasereq_purchase_req_lineitem_lineitem.*  FROM arocrm_purchasereq_purchase_req_lineitem_lineitem
					INNER JOIN arocrm_purchasereq ON arocrm_purchasereq.purchasereqid=arocrm_purchasereq_purchase_req_lineitem_lineitem.purchasereqid
					INNER JOIN arocrm_purchasereqcf ON arocrm_purchasereq.purchasereqid=arocrm_purchasereqcf.purchasereqid
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
					WHERE arocrm_crmentity.deleted = 0 AND arocrm_purchasereqcf.cf_4809 = 'Approved' AND arocrm_purchasereq.purchasereqid='".$id."' AND arocrm_purchasereq_purchase_req_lineitem_lineitem.cf_1730 IN 
					( 
					SELECT arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1957 FROM arocrm_rfqmaintain_rfq_lineitem_lineitem 
					INNER JOIN arocrm_rfqmaintain ON  arocrm_rfqmaintain.rfqmaintainid = arocrm_rfqmaintain_rfq_lineitem_lineitem.rfqmaintainid
					INNER JOIN arocrm_rfqmaintaincf ON arocrm_rfqmaintaincf.rfqmaintainid = arocrm_rfqmaintain.rfqmaintainid
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_rfqmaintain.rfqmaintainid
					WHERE arocrm_crmentity.deleted = 0 
					AND arocrm_rfqmaintain.cf_nrl_vendors253_id = '".$vendor_id."'
					GROUP BY arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1957
					HAVING arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1957 NOT IN 
					(
					SELECT arocrm_inventoryproductrel.productid FROM arocrm_inventoryproductrel
					INNER JOIN arocrm_purchaseorder ON arocrm_purchaseorder.purchaseorderid = arocrm_inventoryproductrel.id
					INNER JOIN arocrm_purchaseordercf ON arocrm_purchaseordercf.purchaseorderid = arocrm_purchaseorder.purchaseorderid
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid
					WHERE arocrm_crmentity.deleted = 0 
					AND arocrm_purchaseorder.postatus = 'Approved'
					AND arocrm_purchaseorder.cf_nrl_purchasereq461_id = '".$id."'
					AND arocrm_purchaseordercf.cf_4300 = '".$month."'))";

					
					$query_lineitem = mysql_query($rfqu_new);
					$count_lineitem = mysql_num_rows($query_lineitem);
					if($count_lineitem==1){
						 $displaytype = 'style="display:none;"';
					}
					
					if($count_lineitem > 0)
					{
					while($row_lineitem = mysql_fetch_array($query_lineitem))
					{

					$productid = $row_lineitem['cf_1730'];
					$deliverydate = $row_lineitem['cf_1754'];
					$product_array = getProductDetails($productid);
					$productname = $product_array['productname'];
					$productcode = $product_array['productcode'];
					$item_description = $product_array['description'];
					$productunit = $product_array['unit'];
					$warranty = $product_array['warranty'];
					
					$unitprice = 0;
					$readly = '';
					$requisite_qty = 0;
					
					if($month=='Next Month'){
						$requisite_qty = $row_lineitem['cf_4792'];
					}else{
						$requisite_qty = $row_lineitem['cf_4794'];
					}
					
					

						    
						    

						    $delivery_date = $row_lineitem['cf_1754'];

							$rfqu = "SELECT arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1980 as unit_price FROM arocrm_rfqmaintain
							INNER JOIN arocrm_rfqmaintain_rfq_lineitem_lineitem ON arocrm_rfqmaintain_rfq_lineitem_lineitem.rfqmaintainid=arocrm_rfqmaintain.rfqmaintainid
							INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_rfqmaintain.rfqmaintainid
							WHERE arocrm_crmentity.deleted=0 AND arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1957 = '".$productid."' and arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1996 = 'Selected' and arocrm_rfqmaintain.cf_nrl_vendors253_id = '".$vendor_id."'
							ORDER BY arocrm_crmentity.createdtime DESC LIMIT 0,1";

							$rfqqry = mysql_query($rfqu);
						    $count_rfq_set = mysql_num_rows($rfqqry);
							$prunit = mysql_fetch_array($rfqqry);
							$unitprice = $prunit['unit_price'];
							
							if($count_rfq_set == 0){
								$unitprsql = "SELECT arocrm_plantproductassignment.*,arocrm_plantproductassignmentcf.*,arocrm_crmentity.* FROM arocrm_plantproductassignment
								INNER JOIN arocrm_plantproductassignmentcf ON arocrm_plantproductassignmentcf.plantproductassignmentid=arocrm_plantproductassignment.plantproductassignmentid
								INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantproductassignment.plantproductassignmentid
								WHERE arocrm_crmentity.deleted=0 AND arocrm_plantproductassignment.cf_nrl_plantmaster103_id = '".$plantid."' AND arocrm_plantproductassignment.cf_nrl_products323_id = '".$productid."'";
								$unitprc = mysql_query($unitprsql);
								$unitprcrw = mysql_fetch_array($unitprc);
								$unitprice = $unitprcrw['cf_1950'];
							}

							$total_amount = (float)$unitprice * (float)$requisite_qty;
							$total_amount =  number_format((float)$total_amount, 2, '.', '');
							$amount = number_format((float)$amount + $total_amount, 2, '.', '');


$html .= '<tr id="row'.$i.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$i.'">
<td style="text-align:center;">
<i class="fa fa-trash deleteRow cursorPointer" title="Delete" '.$displaytype.'></i>&nbsp;
<a><img src="layouts/v7/skins/images/drag.png" border="0" title="Drag"></a>
<input type="hidden" class="rowNumber" value="'.$i.'"></td>

<td><input type="hidden" name="hidtax_row_no'.$i.'" id="hidtax_row_no'.$i.'" value="">
<div class="itemNameDiv form-inline">
<div class="row">
<div class="col-lg-10">
<div class="input-group" style="width:100%">
<input type="text" id="productName'.$i.'" name="productName'.$i.'" value="'.$productname.'" class="productName form-control  autoComplete   ui-autocomplete-input" readonly placeholder="Type to search" data-rule-required="true" autocomplete="off" aria-required="true">

<input type="hidden" id="hdnProductId'.$i.'" name="hdnProductId'.$i.'" value="'.$productid.'" class="selectedModuleId">
<input type="hidden" id="lineItemType'.$i.'" name="lineItemType'.$i.'" value="Products" class="lineItemType">

</div>
</div>
</div>
</div>
<input type="hidden" value="" id="subproduct_ids'.$i.'" name="subproduct_ids'.$i.'" class="subProductIds">
<div id="subprod_names'.$i.'" name="subprod_names'.$i.'" class="subInformation">
<span class="subProductsContainer"></span>
</div>
<div>
<br>
<textarea id="comment'.$i.'" name="comment'.$i.'" class="lineItemCommentBox">'.$item_description.'</textarea>
</div>
</td>
<td>
<input id="productcode'.$i.'" name="productcode'.$i.'" type="text" class="productcode inputElement" readonly="readonly" value="'.$productcode.'">
</td>
<td>
<input id="itemunit'.$i.'" name="itemunit'.$i.'" type="text" class="itemunit inputElement" readonly="readonly" value="'.$productunit.'">
</td>

<td>
<input id="no_warranty_card'.$i.'" name="no_warranty_card'.$i.'" type="text" class="no_warranty_card inputElement" readonly value="'.$warranty.'" aria-invalid="false">
</td>

<td>
<input id="qty'.$i.'" name="qty'.$i.'" type="text" class="qty smallInputBox inputElement" '.$readly.' data-rule-required="true" data-rule-positive="true" data-rule-greater_than_zero="true" value="'.$requisite_qty.'" aria-required="true" max="'.$requisite_qty.'">
<input type="hidden" name="margin'.$i.'" value="0">
<span class="margin pull-right" style="display:none"></span>
</td>

<td>
<div>
<input id="listPrice'.$i.'" readonly name="listPrice'.$i.'" value="'.$unitprice.'" type="text" data-rule-required="true" data-rule-positive="true" class="listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true">&nbsp;</div>
<div style="clear:both"></div>
<div><span>(-)&nbsp;<strong><a href="javascript:void(0)" class="individualDiscount">Discount<span class="itemDiscount">(0)</span></a> : </strong></span></div>
<div class="discountUI validCheck hide" id="discount_div'.$i.'">
<input type="hidden" id="discount_type'.$i.'" name="discount_type'.$i.'" value="zero" class="discount_type">
<p class="popover_title hide">Set Discount For : <span class="variable"></span></p>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
<tbody>
<tr>
<td>
<input type="radio" name="discount'.$i.'" checked="" class="discounts" data-discount-type="zero">&nbsp;Zero Discount
</td>
<td>
<input type="hidden" class="discountVal" value="0"></td>
</tr>
<tr>
<td>
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="percentage">&nbsp; %Price
</td>
<td>
<span class="pull-right">&nbsp;%</span>
<input type="text" data-rule-positive="true" data-rule-inventory_percentage="true" id="discount_percentage'.$i.'" name="discount_percentage'.$i.'" value="" class="discount_percentage span'.$i.' pull-right discountVal hide">
</td>
</tr>
<tr>
<td class="LineItemDirectPriceReduction">
<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="amount">&nbsp;Direct Price Reduction
</td>
<td>
<input type="text" data-rule-positive="true" id="discount_amount'.$i.'" name="discount_amount'.$i.'" value="" class="span1 pull-right discount_amount discountVal hide">
</td>
</tr>
</tbody>
</table>
</div>
<div style="width:150px;"><strong>Total After Discount :</strong></div>
<div class="individualTaxContainer hide">(+)&nbsp;<strong><a href="javascript:void(0)" class="individualTax">Tax </a> : </strong></div>
<span class="taxDivContainer">
<div class="taxUI hide" id="tax_div'.$i.'">
<p class="popover_title hide">Set Tax for : <span class="variable"></span></p>
</div>
</span>
</td>

<td>
<div>
<input id="inr_rate'.$i.'" name="inr_rate'.$i.'" value="'.$unitprice.'" readonly type="text" data-rule-required="true" data-rule-positive="true"
 class="inr_rate smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id=""
 aria-required="true">
 </div>
 </td>

<td>
<input id="delivery_date'.$i.'" value="'.$deliverydate.'" name="delivery_date'.$i.'" type="date" class="delivery_date inputElement" />
</td>
<td>
<div id="productTotal'.$i.'" align="right" class="productTotal">'.$total_amount.'</div>
<div id="discountTotal'.$i.'" align="right" class="discountTotal">0.00</div>
<div id="totalAfterDiscount'.$i.'" align="right" class="totalAfterDiscount">'.$total_amount.'</div>
<div id="taxTotal'.$i.'" align="right" class="productTaxTotal hide">0.00</div>
</td>
<td>
<span id="netPrice'.$i.'" class="pull-right netPrice">'.$total_amount.'</span>
</td>
</tr>';


$status = 1;
						  $i++;
						 
						  }

					}
 	}

          	            $response['totalcount'] = $i - 1;
						$response['req_no'] = $req_no;
						$response['requisition_date'] = $requisition_date;
						$response['amount'] = $amount;
						$response['message'] = $html;
						$response['status'] = $status;
						return $response;

}


function getRFQVendorPRCount($vendor_id,$id){
                      $j = 0;
                      $query_lineitem = mysql_query("SELECT arocrm_purchasereq.*,arocrm_purchasereq_purchase_req_lineitem_lineitem.*,arocrm_crmentity.* FROM arocrm_purchasereq
		              INNER JOIN arocrm_purchasereq_purchase_req_lineitem_lineitem ON arocrm_purchasereq_purchase_req_lineitem_lineitem.purchasereqid=arocrm_purchasereq.purchasereqid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid='".$id."'");
					  $count_lineitem = mysql_num_rows($query_lineitem);

						if($count_lineitem>0)
						{
						  while($row_lineitem = mysql_fetch_array($query_lineitem))
						  {
						  $productid = $row_lineitem['cf_1730'];
						  $requisite_qty = $row_lineitem['cf_1740'];
						  $unitprice = 0;



						  $chkline = mysql_query("SELECT sum(aip.quantity) as quantity FROM `arocrm_inventoryproductrel` aip INNER JOIN  `arocrm_purchaseorder` apo WHERE apo.purchaseorderid = aip.id AND apo.cf_nrl_purchasereq461_id = '".$id."' and aip.productid = '".$productid."'");
						  $count_rw = mysql_num_rows($chkline);
						  $chkrow = mysql_fetch_array($chkline);
						  if($count_rw > 0){
							$requisite_qty = $row_lineitem['cf_1740'] - $chkrow['quantity'];
						  }

						    $delivery_date = $row_lineitem['cf_1754'];

							$rfqu = "SELECT arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1980 as unit_price FROM arocrm_rfqmaintain
							INNER JOIN arocrm_rfqmaintain_rfq_lineitem_lineitem ON arocrm_rfqmaintain_rfq_lineitem_lineitem.rfqmaintainid=arocrm_rfqmaintain.rfqmaintainid
							INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_rfqmaintain.rfqmaintainid
							WHERE arocrm_crmentity.deleted=0 AND arocrm_rfqmaintain.cf_nrl_purchasereq704_id ='".$id."' and arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1957 = '".$productid."' and arocrm_rfqmaintain_rfq_lineitem_lineitem.cf_1996 = 'Approved' and arocrm_rfqmaintain.cf_nrl_vendors253_id = '".$vendor_id."'";

							$rfqqry = mysql_query($rfqu);
						    $count_rfq_set = mysql_num_rows($rfqqry);

						    if($count_rfq_set != 0){

							$chkrowrfqs = mysql_fetch_array($rfqqry);
							$unitprice = number_format((float)$chkrowrfqs['unit_price'], 2, '.', '');
							}

						  $total_amount = (float)$unitprice * (float)$requisite_qty;
   						  $total_amount =  number_format((float)$total_amount, 2, '.', '');
						   $displaytype = "";
						   if($count_noVendor==1){

							 $displaytype = 'style="display:none;"';
						  }

						  if($requisite_qty > 0 && $count_rfq_set != 0)
						  {
						  $j++;
						  }
						  }

						}
return $j;
}




function getNoVendorPRCount($id){
	    $i=0;
		$query_lineitem = mysql_query("SELECT arocrm_purchasereq.*,arocrm_purchasereq_purchase_req_lineitem_lineitem.*,arocrm_crmentity.* FROM arocrm_purchasereq
		INNER JOIN arocrm_purchasereq_purchase_req_lineitem_lineitem ON arocrm_purchasereq_purchase_req_lineitem_lineitem.purchasereqid=arocrm_purchasereq.purchasereqid
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
		WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid='".$id."'");
		$count_lineitem = mysql_num_rows($query_lineitem);
		if($count_lineitem>0)
		{
		$cnt = 0;
		while($row_lineitem = mysql_fetch_array($query_lineitem))
		{

		$productid = $row_lineitem['cf_1730'];
		$requisite_qty = $row_lineitem['cf_1740'];

		$chkline = mysql_query("SELECT sum(aip.quantity) as quantity FROM `arocrm_inventoryproductrel` aip INNER JOIN  `arocrm_purchaseorder` apo WHERE apo.purchaseorderid = aip.id AND apo.cf_nrl_purchasereq461_id = '".$id."' and aip.productid = '".$productid."'");
		$count_rw = mysql_num_rows($chkline);
		$chkrow = mysql_fetch_array($chkline);
		if($count_rw > 0){

		$requisite_qty = $row_lineitem['cf_1740'] - $chkrow['quantity'];
		}


		if($requisite_qty > 0)
		{


		$i++;
		}
		}

		}
	return $i;
}



function getServiceCodeUnit($id){
  $product_array = array();
  $query = mysql_query("SELECT `arocrm_service`.* FROM `arocrm_service`
              INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid`=`arocrm_service`.`serviceid`
             WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_service`.`serviceid`=".$id);
         $rowCount = mysql_num_rows($query);
         if($rowCount==1)
         {
         $row = mysql_fetch_array($query);
         $product_array['servicename'] = $row['servicename'];
         $product_array['service_no'] = $row['service_no'];
         $product_array['service_usageunit'] = $row['service_usageunit'];
         }
         return $product_array;

}

function getProductDetails($id)
{
	  $product_array = array();
	  $query = mysql_query("SELECT arocrm_products.*,arocrm_productcf.*,arocrm_crmentity.* FROM arocrm_products
		           INNER JOIN arocrm_productcf ON arocrm_productcf.productid = arocrm_products.productid
				   INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
		           WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$id);
				   $rowCount = mysql_num_rows($query);
				   if($rowCount==1)
				   {
				     $row = mysql_fetch_array($query);
					 $product_array['productname'] = $row['productname'];
					 $product_array['productcode'] = $row['productcode'];
					 $product_array['product_no'] = $row['product_no'];
					 $product_array['unit_price'] = $row['unit_price'];
					 $product_array['unit'] = $row['usageunit'];
					 $product_array['description'] = $row['description'];
					 $product_array['ah'] = $row['cf_3446'];
					 if($row['cf_3418']==0){
					 $product_array['warranty'] = $row['cf_3122']; 
					 }else{
					 $product_array['warranty'] = $row['cf_3122']." + ".$row['cf_3418']; 
					 }
					 $product_array['category'] = $row['productcategory'];
				   }
				   return $product_array;
}

function getSelectedPlant($id)
{
$response = array();
$result = mysql_query("SELECT arocrm_plantmaster.*,arocrm_crmentity.* FROM arocrm_plantmaster
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
						 WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid=".$id);
						 $count = mysql_num_rows($result);
						 if($count==1)
						 {
						  $row = mysql_fetch_array($result);
						  $plantname = $row['name'];
						 }else{
						 $plantname = '';
						 }
						 $response['message'] = $plantname;
                         return $response;
}


function getPlantProductAssignment($id,$productid)
{
$response = array();
$query = mysql_query("SELECT arocrm_plantproductassignment.*,arocrm_plantproductassignmentcf.*,arocrm_crmentity.* FROM arocrm_plantproductassignment
		           INNER JOIN arocrm_plantproductassignmentcf ON arocrm_plantproductassignmentcf.plantproductassignmentid=arocrm_plantproductassignment.plantproductassignmentid
		           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_plantproductassignment.plantproductassignmentid
		           WHERE arocrm_crmentity.deleted=0 AND arocrm_plantproductassignment.cf_nrl_plantmaster103_id=".$id." AND arocrm_plantproductassignment.cf_nrl_products323_id=".$productid);
						 $count = mysql_num_rows($query);
						 if($count>0)
						 {
						  $row = mysql_fetch_array($query);
						  $unitcost = $row['cf_1950'];
						 }else{
						 $unitcost = '';
						 }
						 $response['unitcost'] = $unitcost;
                         return $response;
}

function getApprovalEmailTemplate()
{
$response = array();
$html = '';
$query = mysql_query("SELECT * FROM arocrm_emailtemplates WHERE module='Approvals'");
$count = mysql_num_rows($query);
if($count>0)
{
  $html .= '<option value="">Select an Option</option>';
  while($row = mysql_fetch_array($query))
  {
    $html .= '<option value="'.$row['templateid'].'">'.$row['templatename'].'</option>';
  }
}
$response['message'] = $html;
return $response;
}

function getApproverList()
{
$response = array();
$html = '';
$query = mysql_query("SELECT * FROM arocrm_users WHERE status='Active'");
$count = mysql_num_rows($query);
if($count>0)
{
  $html .= '<option value="">Select an Option</option>';
  $html .= '<optgroup label="Users">';
  while($row = mysql_fetch_array($query))
  {
    if($row['first_name']!='')
	{
    $username = $row['first_name']." ".$row['last_name'];
	}else{
	$username = $row['last_name'];
	}
    $html .= '<option value="'.$row['id'].'">'.$username.'</option>';
  }
  $html .= '</optgroup>';
  $html .= '<optgroup label="Related Users">';
  $html .= '<option value="Level 1">Level 1 Manager</option>';
  $html .= '<option value="Level 2">Level 2 Manager</option>';
  $html .= '<option value="Level 3">Level 3 Manager</option>';
  $html .= '</optgroup>';
}
$response['message'] = $html;
return $response;
}




function get_stockmaster($recordid)
{
  $response = array();
  $html = '';

  $result = mysql_query("SELECT arocrm_stockmaster.*,arocrm_stockmastercf.*,arocrm_crmentity.* FROM arocrm_stockmaster
                         INNER JOIN arocrm_stockmastercf ON arocrm_stockmastercf.stockmasterid = arocrm_stockmaster.stockmasterid
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_stockmaster.stockmasterid
						 WHERE arocrm_crmentity.deleted = 0 AND arocrm_stockmaster.stockmasterid=".$recordid);
						 $count = mysql_num_rows($result);
						 if($count==1)
						 {
						  $row = mysql_fetch_array($result);
						  $plantid = $row['cf_plantmaster_id'];
						  $productid = $row['cf_products_id'];

						 $storage_result = mysql_query("SELECT arocrm_storagelocation.*,arocrm_crmentity.* FROM arocrm_storagelocation
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_storagelocation.storagelocationid
						 WHERE arocrm_crmentity.deleted = 0 AND arocrm_storagelocation.cf_plantmaster_id=".$plantid);
						 $storage_count = mysql_num_rows($storage_result);
						 if($storage_count>0)
						 {
	   $html .= '<table width="100%" class="table table-bordered ui-sortable" id="lineitemtab">';
	   $html .= '<thead>';
	   $html .= '<tr>';
	   $html .= '<th style="text-align:center;" valign="top">Storage Location</th>';
	   $html .= '<th style="text-align:center;" valign="top">Quantity</th>';
	   $html .= '<th style="text-align:center;" valign="top">Quality Status</th>';
	   $html .= '<th style="text-align:center;" valign="top">Serial Number</th>';
	   $html .= '</tr>';
	   $html .= '</thead>';
	   $html .= '<tbody>';
	  while($storage_row = mysql_fetch_array($storage_result))
	  {
	   $html .= '<tr>';
	   $html .= '<td style="text-align:center;" valign="top">'.$storage_row['name'].'</td>';
	   $html .= '<td style="text-align:center;" valign="top"><input type="text" name="" id="" class="form-control" value="" /></td>';
	   $html .= '<td style="text-align:center;" valign="top"><input type="text" name="" id="" class="form-control" value="" /></td>';
	   $html .= '<td style="text-align:center;" valign="top"><input type="text" name="" id="" class="form-control" value="" /></td>';
	   $html .= '</tr>';
	  }
	  $html .= '</tbody>';
	  $html .= '</table>';
						 }
						 }

  $response['message'] = $html;
  return $response;
}

function getDeliveredPlant($id)
{
$stockreq_array = array();
$query = mysql_query("SELECT arocrm_stockrequisition.*,arocrm_crmentity.* FROM arocrm_stockrequisition
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_stockrequisition.stockrequisitionid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_stockrequisition.stockrequisitionid=".$id);
					  $count = mysql_num_rows($query);
						if($count>0)
						{
						  $row = mysql_fetch_array($query);
						  $stockreq_array['delivered_plantid'] = $row['cf_nrl_plantmaster765_id'];
						}else{
						  $stockreq_array['delivered_plantid'] = '';
						}
						return $stockreq_array;
}

function getRfqLineItem($id)
{
$response = array();
$html = '';
$i=1;
$countstring = '';
$query = mysql_query("SELECT arocrm_purchasereq.*,arocrm_purchasereqcf.*,arocrm_crmentity.* FROM arocrm_purchasereq
		              INNER JOIN arocrm_purchasereqcf ON arocrm_purchasereqcf.purchasereqid=arocrm_purchasereq.purchasereqid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid=".$id." AND arocrm_purchasereqcf.cf_4809 = 'Approved'");
					  $count = mysql_num_rows($query);
						if($count > 0)
						{
						  $row = mysql_fetch_array($query);
						  $plantid = $row['cf_nrl_plantmaster436_id'];
						  $plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
						  INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid 
						  WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
						  $plantrow = mysql_fetch_array($plantsql);
						  $plantname = $plantrow['name'];
						  $requisition_date = $row['cf_1759'];
						  $requisition_date = date("d-m-Y", strtotime($requisition_date));
						

                      $query_lineitem = mysql_query("SELECT arocrm_purchasereq.*,arocrm_purchasereq_purchase_req_lineitem_lineitem.*,arocrm_crmentity.* FROM arocrm_purchasereq
		              INNER JOIN arocrm_purchasereq_purchase_req_lineitem_lineitem ON arocrm_purchasereq_purchase_req_lineitem_lineitem.purchasereqid=arocrm_purchasereq.purchasereqid
		              INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_purchasereq.purchasereqid
		              WHERE arocrm_crmentity.deleted=0 AND arocrm_purchasereq.purchasereqid=".$id);
					  $count_lineitem = mysql_num_rows($query_lineitem);
						if($count_lineitem>0)
						{
						  while($row_lineitem = mysql_fetch_array($query_lineitem))
						  {
						  $productid = $row_lineitem['cf_1730'];
						  $product_array = getProductDetails($productid);
						  $productname = $product_array['productname'];
						  $item_description = $product_array['description'];
						  $productunit = $product_array['unit'];
						  $productcode = $product_array['productcode'];
						  $ah = $product_array['ah'];
						  $category = $product_array['category'];
						  
						  $requisite_qty = $row_lineitem['cf_4792'];
						  $delivery_date = $row_lineitem['cf_1754'];
						  $stockreqid = $row_lineitem['cf_nrl_stockrequisition832_id'];
						  $stockreq_array = getDeliveredPlant($stockreqid);
						  $delivered_plantid = $stockreq_array['delivered_plantid'];
						  $plantProduct_array = getPlantProductAssignment($delivered_plantid,$productid);
						  $item_unitcost = $plantProduct_array['unitcost'];
						  $countstring .= $i.',';
						  $total_amount = $requisite_qty * $item_unitcost;
						  $total_amount =  number_format((float)$total_amount, 2, '.', '');
						  $readmod = "";

						$readmod = '<select data-fieldname="cf_1996_'.$i.'" data-fieldtype="picklist" class="inputElement select2 customPicklistSelect2" required name="cf_1996_'.$i.'" id="cf_1996_'.$i.'" style="width: 120px;">
						  <option value="">Select an Option</option>
						  <option value="Selected" >Selected</option>
						  <option value="Not Selected">Not Selected</option>
						  </select>';
						

						  $html .= '<tr id="RFQ_Lineitem__row_1'.$i.'" class="tr_clone">';
						 $html .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

						 $html .= '<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_1"><div class="input-group"><input name="cf_1957_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_1957_'.$i.'"><input id="cf_1957_display_'.$i.'" name="cf_1957_display_'.$i.'" data-fieldname="cf_1957" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'" placeholder="Type to search" autocomplete="off" readonly="readonly"></div></div>
						 </td>';

						 
						$html .= '<td class="fieldValue"><input id="RFQMaintain_editView_fieldName_cf_1965_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_1965" data-fieldtype="string" readonly class="inputElement " name="cf_1965_'.$i.'" value="'.$productcode.'"></td>';

						
						
						$html .= '<td class="fieldValue"><input id="RFQMaintain_editView_fieldName_cf_4872_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_4872" data-fieldtype="string" class="inputElement " name="cf_4872_'.$i.'" value="'.$ah.'" readonly /></td>';
						
						$html .= '<td class="fieldValue"><div class="inputElement" style="margin-bottom: 3px"><input id="RFQMaintain_editView_fieldName_cf_1969_'.$i.'" type="text" class="form-control" data-fieldname="cf_1969" name="cf_1969_'.$i.'" style="width: 150px;" value="'.$productunit.'" readonly="readonly"></div></td>';
						
						$html .= '<td class="fieldValue"><input id="RFQMaintain_editView_fieldName_cf_4874_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_4874" data-fieldtype="string" class="inputElement " name="cf_4874_'.$i.'" value="'.$category.'" readonly /></td>';
						
						$html .= '<td class="fieldValue"><input id="RFQMaintain_editView_fieldName_cf_1967_'.$i.'" type="number" class="inputElement" step="0.01" name="cf_1967_'.$i.'" style="width: 100px;" value="'.$requisite_qty.'" readonly="readonly"></td>';


						  $html .= '<td class="fieldValue"><div class="inputElement" style="margin-bottom: 3px"><input id="RFQMaintain_editView_fieldName_cf_1973_'.$i.'" type="date" class="form-control" data-fieldname="cf_1973" name="cf_1973_'.$i.'" style="width: 150px;" value="'.$delivery_date.'" readonly="readonly"></div></td>';

						  $html .= '<td class="fieldValue"><input id="RFQMaintain_editView_fieldName_cf_1980_'.$i.'" type="number" class="inputElement" step="0.01" name="cf_1980_'.$i.'" style="width: 100px;" value="'.$item_unitcost.'" />
						  <script>
						  jQuery("[name=cf_1980_'.$i.']").keyup(function(){
						  var qty = $("[name=cf_1967_'.$i.']").val();
                          var unit = $(this).val();
						  if(unit=="" || unit==undefined){
							unit = 0;
						  }
						  $("[name=cf_1986_'.$i.']").val(parseFloat(qty) * parseFloat(unit));
						  });
						  </script>

						  </td>';

						  $html .= '<td class="fieldValue"><input id="RFQMaintain_editView_fieldName_cf_1986_'.$i.'" type="number" class="inputElement" step="0.01" name="cf_1986_'.$i.'" style="width: 100px;" value="'.$total_amount.'" readonly="readonly"></td>';

						  $html .= '<td class="fieldValue"><div class="inputElement" style="margin-bottom: 3px"><input id="RFQMaintain_editView_fieldName_cf_1990_'.$i.'" type="date" class="form-control" data-fieldname="cf_1990" name="cf_1990_'.$i.'" data-rule-date="true" style="width: 150px;"></div></td>';



						  $html .= '<td class="fieldValue">
						  '.$readmod.'
						  </td>';

						  $html .= '<td class="fieldValue"><textarea rows="5" id="RFQMaintain_editView_fieldName_cf_1998_'.$i.'" class="inputElement" name="cf_1998_'.$i.'"></textarea></td>';
						 $html .= '</tr>';

						  $i++;
						  }

						}
						
						}
						$response['requisition_date'] = $requisition_date;
						$response['plantid'] = $plantid;
						$response['plantname'] = $plantname;
						$response['message'] = $html;
						$countstring = rtrim($countstring,',');
						$response['rowcount'] = $countstring;
						return $response;
}

function getPurchaseReqLineitem($id,$plant)
{
$response = array();
$html = '';
$i=1;
$countstring = '';
$idstr = "";
$ict = 1;
$qid = explode(",",$id);
foreach($qid as $qsid){
if($ict==1){
$idstr = "'".$qsid."'";
}else{
$idstr = $idstr.",'".$qsid."'";
}
$ict++;
}

$msql = "SELECT `arocrm_stockrequisition`.*,`arocrm_stockrequisition_line_item_details_lineitem`.`cf_1553`,sum(`arocrm_stockrequisition_line_item_details_lineitem`.`cf_1561`) as qty1,sum(`arocrm_stockrequisition_line_item_details_lineitem`.`cf_3609`) as qty2,sum(`arocrm_stockrequisition_line_item_details_lineitem`.`cf_3611`) as qty3 FROM arocrm_stockrequisition
		           INNER JOIN `arocrm_stockrequisition_line_item_details_lineitem` ON `arocrm_stockrequisition_line_item_details_lineitem`.`stockrequisitionid`=`arocrm_stockrequisition`.`stockrequisitionid`
                   INNER JOIN `arocrm_stockrequisitioncf` ON `arocrm_stockrequisitioncf`.`stockrequisitionid`=`arocrm_stockrequisition`.`stockrequisitionid`
		           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_stockrequisition.stockrequisitionid
		           WHERE arocrm_crmentity.deleted = 0 AND arocrm_stockrequisition.stockrequisitionno in (".$idstr.") AND arocrm_stockrequisitioncf.cf_4807 = 'Approved' AND arocrm_stockrequisition.cf_nrl_plantmaster587_id = '".$plant."' group by arocrm_stockrequisition_line_item_details_lineitem.cf_1553";
                   $query = mysql_query($msql);
				   $count = mysql_num_rows($query);
				    if($count > 0)
				    {
					  while($row = mysql_fetch_array($query))
					  {

                         $productid = $row['cf_1553'];
						 $product_array = getProductDetails($productid);
						 $productname = $product_array['productname'];
						 $item_code = $product_array['productcode'];
						 $ah = $product_array['ah'];
						 $category = $product_array['category'];
						 $warranty = $product_array['warranty'];
						 	
							$reqty = $row['qty1'];
							$reqty2 = $row['qty2'];
							$reqty3 = $row['qty3'];
						
					     $reordersql1 = "SELECT  arocrm_plantproductassignmentcf.cf_1354 as reorderqty FROM arocrm_plantproductassignment
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_plantproductassignment.plantproductassignmentid
						 INNER JOIN arocrm_plantproductassignmentcf ON arocrm_plantproductassignmentcf.plantproductassignmentid = arocrm_plantproductassignment.plantproductassignmentid
						 WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantproductassignment.cf_nrl_products323_id = '".$productid."' and arocrm_plantproductassignment.cf_nrl_plantmaster103_id = '".$plant."'";
					     $reordersql = mysql_query($reordersql1);
						 $req = mysql_fetch_array($reordersql);
						 $reorderqty = $req['reorderqty'];


						$stocksum = 0;
						$storesql = mysql_query("SELECT `arocrm_storagelocation`.`storagelocationid` FROM `arocrm_storagelocation` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid` WHERE `cf_nrl_plantmaster561_id` = '".$plantid."' AND `arocrm_crmentity`.`deleted` = '0'");
						while($str = mysql_fetch_array($storesql)){
							
						$query = "SELECT `closing_stock`  FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$str['storagelocationid']."' AND `qualitystatus` = 'R' AND `transaction_date` <= '".date('Y')."-".date('m')."-01' AND `id`  = (SELECT `id`  FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$str['storagelocationid']."' AND `qualitystatus` = 'R' AND `transaction_date` <= '".date('Y')."-".date('m')."-01' ORDER BY `id` DESC LIMIT 0,1) GROUP BY `product`,`plant`,`store` ORDER BY `id` DESC LIMIT 0,1";
						
						$result = mysql_query($query);
						$rowCount = mysql_num_rows($result);
						if($rowCount==1)
						{
						$row = mysql_fetch_array($result);
						$stocksum = $stocksum + $row['closing_stock'];
						}
						
						}
						$curst = $stocksum;



             // Checking for Open Items in PO //
$openqty1 = "SELECT SUM(`arocrm_inventoryproductrel`.`quantity`) AS `poqty` FROM `arocrm_purchaseorder`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_purchaseorder`.`purchaseorderid`
INNER JOIN `arocrm_inventoryproductrel` ON `arocrm_inventoryproductrel`.`id` = `arocrm_purchaseorder`.`purchaseorderid`
WHERE `arocrm_crmentity`.`deleted` = 0
AND `arocrm_inventoryproductrel`.`productid` = '".$productid."'
AND `arocrm_purchaseorder`.`cf_nrl_plantmaster950_id` = '".$plant."'
GROUP BY `arocrm_inventoryproductrel`.`productid`";
$openpoqty = mysql_query($openqty1);
$poarray = mysql_fetch_array($openpoqty);
$poqty = $poarray['poqty'];
if($poqty==''){
$poqty = 0;	
}

$openibdqty1 = "SELECT SUM(`arocrm_inbounddelivery_line_item_lineitem`.`cf_2878`) AS `ibdqty` FROM `arocrm_inbounddelivery_line_item_lineitem`
INNER JOIN `arocrm_inbounddelivery` ON `arocrm_inbounddelivery`.`inbounddeliveryid` = `arocrm_inbounddelivery_line_item_lineitem`.`inbounddeliveryid`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_inbounddelivery`.`inbounddeliveryid`
WHERE `arocrm_crmentity`.`deleted` = 0
AND `arocrm_inbounddelivery`.`cf_nrl_plantmaster269_id` = '".$plant."'
AND `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868` = '".$productid."'
GROUP BY `arocrm_inbounddelivery_line_item_lineitem`.`cf_2868`";
$openibdqty = mysql_query($openibdqty1);
$ibdarray = mysql_fetch_array($openibdqty);
$ibdqty = $ibdarray['ibdqty'];
if($ibdqty==''){
$ibdqty = 0;	
}
$openqtys = (float)$poqty - (float)$ibdqty;

   // End of Open Item in PO //

$nextmonthopensingstk = (float)($curst + $openqtys) - (float)$reqty;
/*if($nextmonthopensingstk < 0){
$nextmonthopensingstk = 0;
}*/
$reqty2 = (float)($reorderqty + $reqty2) - (float)$nextmonthopensingstk;
$reqty2 = number_format((float) $reqty2, 2, '.', '');
if($reqty2 < 0)
{
	$reqty2 = 0;
}
if($reqty3 < 0){
	$reqty3 = 0;
}


if($reqty2 != 0 || $reqty3 != 0)
{
		$requisite_qty =  (float)($reqty + $reorderqty) - (float)$curst;
             $requisite_qty =  number_format((float) $requisite_qty, 2, '.', '');
						 $productunit = $product_array['unit'];
						 $countstring .= $i.',';


						 $html .= '<tr id="Purchase_Req_Lineitem__row_'.$i.'" class="tr_clone">';
						 $html .= '<td><!--<i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp; --><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

						 $html .= '<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Products" id="popupReferenceModule_1"><div class="input-group"><input name="cf_1730_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_1730_'.$i.'"><input id="cf_1730_display_'.$i.'" name="cf_1730_display_'.$i.'" data-fieldname="cf_1730" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'" placeholder="Type to search" autocomplete="off" readonly="readonly"></div></div></td>';

						 $html .= '<td class="fieldValue"><input id="PurchaseReq_editView_fieldName_cf_2836_'.$i.'" type="text" class="form-control" data-fieldname="cf_2836" name="cf_2836_'.$i.'" style="min-width:150px;"  value="'.$item_code.'" readonly></td>';

						 
			$html .= '<td class="fieldValue"><input id="PurchaseReq_editView_fieldName_cf_4870_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_4870" data-fieldtype="string" class="inputElement " name="cf_4870_'.$i.'" value="'.$ah.'" readonly /></td>';
			
             $html .= '<td class="fieldValue"><input id="PurchaseReq_editView_fieldName_cf_1742_'.$i.'" type="text" class="form-control" data-fieldname="cf_1742" name="cf_1742_'.$i.'" style="width: 150px;" value="'.$productunit.'" readonly="readonly"></td>';

			 $html .= '<td class="fieldValue"><input id="PurchaseReq_editView_fieldName_cf_4868_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_4868" data-fieldtype="string" class="inputElement " name="cf_4868_'.$i.'" value="'.$category.'" readonly /></td>';
			 
			 $html .= '<td class="fieldValue"><input id="cf_5029_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_5029" data-fieldtype="string" class="inputElement " name="cf_5029_'.$i.'" readonly value="'.$warranty.'"></td>';
			 
             $html .= '<td class="fieldValue"><input id="cf_3634_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3634_'.$i.'" value="'.$reqty2.'" readonly /></td>';
			 
			 $html .= '<td class="fieldValue"><input id="cf_4792_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4792_'.$i.'" value="'.$reqty2.'" required /></td>';
			 
             $html .= '<td class="fieldValue"><input id="cf_3636_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3636_'.$i.'" value="'.$reqty3.'" readonly /></td>';
			 
			 $html .= '<td class="fieldValue"><input id="cf_4794_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4794_'.$i.'" value="'.$reqty3.'"  required /></td>';

		  $html .= '<td class="fieldValue"><div class="inputElement" style="margin-bottom: 3px"><input id="PurchaseReq_editView_fieldName_cf_1754_'.$i.'" type="date" class="form-control" data-fieldname="cf_1754" name="cf_1754_'.$i.'" data-rule-date="true" required min="'.date('Y-m-d').'" style="width: 150px;"></div></td>';

		  $html .= '<td class="fieldValue"><textarea rows="5" id="PurchaseReq_editView_fieldName_cf_1756_'.$i.'" class="inputElement" name="cf_1756_'.$i.'"></textarea></td>';
		  $html .= '</tr>';
		 $i++;
}
					  }
					}
					$response['message'] = $html;
					$countstring = rtrim($countstring,',');
                    $response['rowcount'] = $countstring;
					return $response;

}

function getStockRequisition($id,$referenceid)
{
$timezone_offset_minutes = 330;
$timezone_name           = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
date_default_timezone_set($timezone_name);
$response = array();
$district = array();
$html = '';
$i=1;
$countstring = '';
/* Code Added here */
$disqry = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_accountbillads.*, arocrm_crmentity.* FROM arocrm_account
                         INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
						 INNER JOIN arocrm_accountbillads ON arocrm_accountbillads.accountaddressid = arocrm_account.accountid
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_account.cf_nrl_plantmaster183_id = ".$id);
while($disrow = mysql_fetch_array($disqry))
{
	array_push($district,$disrow['bill_city']);
}
$query = mysql_query("SELECT * FROM arocrm_stockreq_temp WHERE plantid=".$id." AND referenceid=".$referenceid." AND month1_required > 0");

				   $count = mysql_num_rows($query);
				    if($count>0)
				    {
					  while($row = mysql_fetch_array($query))
					  {
						  $qty = 0;
						 $productid = $row['productid'];
						 $product_array = getProductDetails($productid);
						 $productname = $product_array['productname'];
						 $productcode = $product_array['productcode'];
						 $productah = $product_array['ah'];
						 $productunit = $product_array['unit'];
						 $item_description = $product_array['description'];
						 $category = $product_array['category'];
						 $year = $row['year'];
						 $month = $row['month1'];
						 $nmonth = date("m", strtotime($month));
						 if($nmonth == '04' || $nmonth == '05' || $nmonth == '06' || $nmonth == '07' || $nmonth == '08' || $nmonth == '09')
						 {
							 $m = substr($nmonth, -1);
							 $year1 = $year;
							 $prevmonth1 = $m - 1;
							 $prevmnlen1 =  strlen($prevmonth1);
							 if($prevmnlen1 == '1')
							 {
								 $prevmonth1 = '0'.$prevmonth1;
							 }
							 $year2 = $year;
							 $prevmonth2 = $m - 2;
							 $prevmnlen2 =  strlen($prevmonth2);
							 if($prevmnlen2 == '1')
							 {
								 $prevmonth2 = '0'.$prevmonth2;
							 }
							 $year3 = $year;
							 $prevmonth3 = $m - 3;
							 $prevmnlen3 =  strlen($prevmonth3);
							 if($prevmnlen3 == '1')
							 {
								 $prevmonth3 = '0'.$prevmonth3;
							 }
						 }
						 else if($nmonth == '03')
						 {
							 $m = substr($nmonth, -1);
							 $year1 = $year;
							 $prevmonth1 = $m - 1;
							 $prevmnlen1 =  strlen($prevmonth1);
							 if($prevmnlen1 == '1')
							 {
								 $prevmonth1 = '0'.$prevmonth1;
							 }
							 $year2 = $year;
							 $prevmonth2 = $m - 2;
							 $prevmnlen2 =  strlen($prevmonth2);
							 if($prevmnlen2 == '1')
							 {
								 $prevmonth2 = '0'.$prevmonth2;
							 }
							 $year3 = $year - 1;
							 $prevmonth3 = '12';
						 }
						 else if($nmonth == '02')
						 {
							 $m = substr($nmonth, -1);
							 $year1 = $year;
							 $prevmonth1 = $m - 1;
							 $prevmnlen1 =  strlen($prevmonth1);
							 if($prevmnlen1 == '1')
							 {
								 $prevmonth1 = '0'.$prevmonth1;
							 }
							 $year2 = $year - 1;
							 $prevmonth2 = '12';
							 $year3 = $year - 1;
							 $prevmonth3 = $prevmonth2 - 1;
						 }
						 else if($nmonth == '01')
						 {
							 $year1 = $year - 1;
							 $prevmonth1 = '12';
							 $year2 = $year - 1;
							 $prevmonth2 = $prevmonth1 - 1;
							 $year3 = $year - 1;
							 $prevmonth3 = $prevmonth1 - 2;
						 }
						 else if($nmonth == '10' || $nmonth == '11' || $nmonth == '12')
						 {
							 $year1 = $year;
							$prevmonth1 = $nmonth - 1;
							$prevmnlen1 =  strlen($prevmonth1);
							if($prevmnlen1 == '1')
							{
							 $prevmonth1 = '0'.$prevmonth1;
							}
							$year2 = $year;
							$prevmonth2 = $nmonth - 2;
							$prevmnlen2 =  strlen($prevmonth2);
							if($prevmnlen2 == '1')
							{
							 $prevmonth2 = '0'.$prevmonth2;
							}
							$year3 = $year;
							$prevmonth3 = $nmonth - 3;
							$prevmnlen3 =  strlen($prevmonth3);
							if($prevmnlen3 == '1')
							{
							 $prevmonth3 = '0'.$prevmonth3;
							}
						 }
						 $qry =  mysql_query("SELECT arocrm_invoice.*,arocrm_invoicecf.*,arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0");
						 while($rw = mysql_fetch_array($qry))
						{
							$date = $rw['cf_4627'];
							$dt = explode("-",$date);
							if($dt[0] == $year1 && $dt[1] == $prevmonth1)
							{
								$invoice1 = $rw['invoiceid'];
								$sqlqry = mysql_query("SELECT arocrm_invoice.*,arocrm_crmentity.* FROM arocrm_invoice 
														INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
														WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoice1);
								$rws = mysql_fetch_array($sqlqry);
								$account1 = $rws['customerno'];
								$result = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_accountbillads.*, arocrm_crmentity.* FROM arocrm_account
												 INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
												 INNER JOIN arocrm_accountbillads ON arocrm_accountbillads.accountaddressid = arocrm_account.accountid
												 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
												 WHERE arocrm_crmentity.deleted=0 AND arocrm_account.accountid=".$account1);
								$rows = mysql_fetch_array($result);
								$city1 = $rows['bill_city'];
								if (in_array($city1, $district))
								{
								  $rel = mysql_query("SELECT arocrm_inventoryproductrel.*, arocrm_crmentity.* FROM  arocrm_inventoryproductrel
									INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id
									WHERE arocrm_crmentity.deleted=0 AND arocrm_inventoryproductrel.id=".$invoice1);
									while($relrw = mysql_fetch_array($rel))
									{
										$productid1 = $relrw['productid'];
										if($productid == $productid1)
										{
											$qty = $qty + $relrw['quantity'];
										}
									}
								}
							}
							else if($dt[0] == $year2 && $dt[1] == $prevmonth2)
							{
								$invoice2 = $rw['invoiceid'];
								$sqlqry = mysql_query("SELECT arocrm_invoice.*,arocrm_crmentity.* FROM arocrm_invoice 
								INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
								WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoice2);
								$rws = mysql_fetch_array($sqlqry);
								$account2 = $rws['customerno'];
								$result = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_accountbillads.*, arocrm_crmentity.* FROM arocrm_account
												 INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
												 INNER JOIN arocrm_accountbillads ON arocrm_accountbillads.accountaddressid = arocrm_account.accountid
												 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
												 WHERE arocrm_crmentity.deleted=0 AND arocrm_account.accountid=".$account2);
								$rows = mysql_fetch_array($result);
								$city2 = $rows['bill_city'];
								if (in_array($city2, $district))
								{
									$rel = mysql_query("SELECT arocrm_inventoryproductrel.*, arocrm_crmentity.* FROM  arocrm_inventoryproductrel
									INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id
									WHERE arocrm_crmentity.deleted=0 AND arocrm_inventoryproductrel.id=".$invoice2);
									while($relrw = mysql_fetch_array($rel))
									{
										$productid2 = $relrw['productid'];
										if($productid == $productid2)
										{
											$qty = $qty + $relrw['quantity'];
										}
									}
								}
							}
							else if($dt[0] == $year3 && $dt[1] == $prevmonth3)
							{
								$invoice3 = $rw['invoiceid'];
								$sqlqry = mysql_query("SELECT arocrm_invoice.*,arocrm_crmentity.* FROM arocrm_invoice 
								INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
								WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoice3);
								$rws = mysql_fetch_array($sqlqry);
								$account3 = $rws['customerno'];
								$result = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_accountbillads.*, arocrm_crmentity.* FROM arocrm_account
												 INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
												 INNER JOIN arocrm_accountbillads ON arocrm_accountbillads.accountaddressid = arocrm_account.accountid
												 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
												 WHERE arocrm_crmentity.deleted=0 AND arocrm_account.accountid=".$account3);
								$rows = mysql_fetch_array($result);
								$city3 = $rows['bill_city'];
								if (in_array($city3, $district))
								{
									$rel = mysql_query("SELECT arocrm_inventoryproductrel.*, arocrm_crmentity.* FROM  arocrm_inventoryproductrel
									INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id
									WHERE arocrm_crmentity.deleted=0 AND arocrm_inventoryproductrel.id=".$invoice3);
									while($relrw = mysql_fetch_array($rel))
									{
										$productid3 = $relrw['productid'];
										if($productid == $productid3)
										{
											$qty = $qty + $relrw['quantity'];
										}
									}
								}
							}
						}

			$average_qty = $qty/3;
			/*Code Ended here */
			$average_sb = ($row['month1_required'] + $row['month2_required'] + $row['month3_required'])/3;
			$average_sb =  number_format((float)$average_sb, 2, '.', '');
			$average_qty =  number_format((float)$average_qty, 2, '.', '');
			$unit_cost = $row['unit_cost'];

			if($row['month1_required']!=0 || $row['month2_required']!=0 || $row['month3_required']!=0){
			
			$countstring .= $i.',';
			$html .= '<tr id="Line_Item_Details__row_'.$i.'" class="tr_clone">';

			$html .= '<td><i class="fa fa-copy cloneLineItemRow cursorPointer" title="Clone" id="'.$i.'"></i>&nbsp;&nbsp;<i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i></td>';

			$html .= '<td class="fieldValue">
             <div class="referencefield-wrapper ">
             <input name="popupReferenceModule" type="hidden" value="PlantMaster" id="popupReferenceModule_1">
             <div class="input-group">
             <input name="cf_1553_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue="" id="cf_1553_'.$i.'">
             <input id="cf_1553_display_'.$i.'" name="cf_1553_display_'.$i.'" data-fieldname="cf_1553" data-fieldtype="reference" type="text"
             class="marginLeftZero autoComplete inputElement ui-autocomplete-input" style="width: 250px;"
             value="'.$productname.'" placeholder="Type to search" autocomplete="off" readonly="readonly">
             </div>
             </div>
             </td>';

			$html .= '<td class="fieldValue"><textarea rows="5" id="StockRequisition_editView_fieldName_cf_1555_'.$i.'" style="width: 120px;"
			class="inputElement" readonly name="cf_1555_'.$i.'">'.$productcode.'</textarea></td>';

			$html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_3457_'.$i.'" type="text" data-fieldname="cf_3457" data-fieldtype="string" class="inputElement " name="cf_3457_'.$i.'" style="width: 100px;" value="'.$productah.'" readonly="readonly" /></td>';

             $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_1563_'.$i.'" style="width:80px" type="text" class="inputElement" name="cf_1563_'.$i.'" value="'.$productunit.'" readonly="readonly"></td>';

             $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_4778_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_4778" data-fieldtype="string" class="inputElement " name="cf_4778_'.$i.'" readonly="readonly" value="'.$category.'">
</td>';

             $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_1559_'.$i.'" type="text" class="inputElement" step="0.01" name="cf_1559_'.$i.'" style="width: 100px;" value="'.round($average_qty).'" readonly="readonly"></td>';

             $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_3455" style="min-width:80px;" type="text" class="inputElement" name="cf_3455_'.$i.'" readonly value="'.round($average_sb).'"></td>';


			 $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_1557_'.$i.'" type="text" class="inputElement" step="0.01" name="cf_1557_'.$i.'" style="width: 100px;" value="'.$row['month1_required'].'" readonly="readonly" /></td>';

			 $html .= '<td class="fieldValue">
             <input id="StockRequisition_editView_fieldName_cf_1561_'.$i.'" type="number" class="inputElement" value="'.$row['month1_required'].'" step="1" min="0" name="cf_1561_'.$i.'" />
             </td>';
			 
             $html .= '<td class="fieldValue"><input id="cf_3609_'.$i.'" style="min-width:80px;" readonly type="text" class="inputElement" step="0.01" name="cf_3609_'.$i.'" value="'.$row['month2_required'].'"></td>';

			 $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_4513_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4513_'.$i.'" value="'.$row['month2_required'].'" /></td>';

			 
             $html .= '<td class="fieldValue"><input id="cf_3611_'.$i.'" style="min-width:80px;" readonly type="text" class="inputElement" step="0.01" name="cf_3611_'.$i.'" value="'.$row['month3_required'].'"></td>';

		     $html .= '<td class="fieldValue"><input id="StockRequisition_editView_fieldName_cf_4515_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4515_'.$i.'" value="'.$row['month3_required'].'" /></td>';


			 $html .= '<td class="fieldValue"><div class="inputElement" style="margin-bottom: 3px"><input id="StockRequisition_editView_fieldName_cf_1567_'.$i.'" min="'.date('Y-m-d').'" required type="date" class="form-control" data-fieldname="cf_1567" name="cf_1567_'.$i.'" data-rule-date="true" style="width: 150px;"></div></td>';

			 $html .= '<td class="fieldValue"><textarea rows="5" id="StockRequisition_editView_fieldName_cf_1569_'.$i.'" class="inputElement" name="cf_1569_'.$i.'"></textarea></td>';

			 $html .= '</tr>';
						 $i++;
						 
			}	 
						 
					  }
					}
$response['message'] = $html;
$countstring = rtrim($countstring,',');
$response['rowcount'] = $countstring;
return $response;
}

function get_stockmaster_add($id)
{
  $response = array();
  $html = '';
						 $storage_result = mysql_query("SELECT arocrm_storagelocation.*,arocrm_crmentity.* FROM arocrm_storagelocation
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_storagelocation.storagelocationid
						 WHERE arocrm_crmentity.deleted = 0 AND arocrm_storagelocation.cf_plantmaster_id=".$id);
						 $storage_count = mysql_num_rows($storage_result);
						 if($storage_count>0)
						 {
						   $html .= '<table width="100%" class="table table-bordered ui-sortable" id="lineitemtab">';
						   $html .= '<thead>';
						   $html .= '<tr>';
						   $html .= '<th style="text-align:center;" valign="top">Storage Location</th>';
						   $html .= '<th style="text-align:center;" valign="top">Quantity</th>';
						   $html .= '<th style="text-align:center;" valign="top">Quality Status</th>';
						   $html .= '<th style="text-align:center;" valign="top">Serial Number</th>';
						   $html .= '</tr>';
						   $html .= '</thead>';
						   $html .= '<tbody>';
						  while($storage_row = mysql_fetch_array($storage_result))
						  {
						   $html .= '<tr>';
						   $html .= '<td style="text-align:center;" valign="top">'.$storage_row['name'].'</td>';
						   $html .= '<td style="text-align:center;" valign="top"><input type="text" name="" id="" class="form-control" value="" /></td>';
						   $html .= '<td style="text-align:center;" valign="top"><input type="text" name="" id="" class="form-control" value="" /></td>';
						   $html .= '<td style="text-align:center;" valign="top"><input type="text" name="" id="" class="form-control" value="" /></td>';
						   $html .= '</tr>';
						  }
						  $html .= '</tbody>';
						  $html .= '</table>';
						  }



  $response['message'] = $html;
  return $response;
}



mysql_close($dbhandle);
?>
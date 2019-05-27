<?php
ob_start();
session_start();
include "config.inc.php";
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


if(isset($_POST['action']) && function_exists($_POST['action'])) {
$response = array();
$action = $_POST['action'];
if($action == 'getAllPlant')
	{
		$getData = $action();
		$response['plantid'] = $getData['plantid'];
		//$response['plantname'] = $getData['plantname'];
		echo json_encode($response);
	}
	if($action == 'getAllStore')
	{
		$plant = isset($_POST['plantid']) ? $_POST['plantid'] : null;
		$getData = $action($plant);
		$response['store'] = $getData['store'];
		echo json_encode($response);
	}
	if($action == 'getAllProduct')
	{
		$plant = isset($_POST['plantid']) ? $_POST['plantid'] : null;
		$getData = $action($plant);
		$response['product'] = $getData['product'];
		echo json_encode($response);
	}
	if($action == 'getStockReport')
	{
		$date = isset($_POST['date']) ? $_POST['date'] : null;
		$plant = isset($_POST['plant']) ? $_POST['plant'] : null;
		$store = isset($_POST['store']) ? $_POST['store'] : null;
		$product = isset($_POST['product']) ? $_POST['product'] : null;
		$statuss = isset($_POST['statuss']) ? $_POST['statuss'] : null;
		$getData = $action($date,$plant,$store,$product,$statuss);
		$response['stockreporthtml'] = $getData['stockreporthtml'];
		echo json_encode($response);
	}
if($action == 'getPlant')
	{
		$getData = $action();
		$response['plantid'] = $getData['plantid'];
		$response['plantname'] = $getData['plantname'];
		$response['allplant'] = $getData['allplant'];
		echo json_encode($response);
	}
	if($action == 'removeEdit')
	{
		$module = isset($_POST['moduleName']) ? $_POST['moduleName'] : null;
		$recordId = isset($_POST['recordId']) ? $_POST['recordId'] : null;
		$getData = $action($module,$recordId);
		$response['ticketstatus'] = $getData['ticketstatus'];
		 echo json_encode($response);
	}
	if($action == 'getVendorDetails')
	{
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($id);
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getPlantUsers')
	{
		$plantid = isset($_POST['parentId']) ? $_POST['parentId'] : null;
		$getData = $action($plantid);
		$response['html'] = $getData['html'];
		echo json_encode($response);
	}
    if($action=='getAllChannel')
	{
    $channel = isset($_POST['chnl']) ? $_POST['chnl'] : null;
	$date = isset($_POST['dt']) ? $_POST['dt'] : null;
	$row = isset($_POST['rownum']) ? $_POST['rownum'] : null;
	$leadno = isset($_POST['leadno']) ? $_POST['leadno'] : null;
	$leads = isset($_POST['lead']) ? $_POST['lead'] : null;
	$year = isset($_POST['year']) ? $_POST['year'] : null;
	$route = isset($_POST['route']) ? $_POST['route'] : null;
	$routetype = isset($_POST['routetype']) ? $_POST['routetype'] : null;
    $getData = $action($channel,$date,$row,$leadno,$leads,$year,$route,$routetype);
	$response['actual'] = $getData['actual'];
	$response['rowcount'] = $getData['rowcount'];
	$response['tbodyBill'] = $getData['tbodyBill'];
	$response['rowcountBill'] = $getData['rowcountBill'];
    echo json_encode($response);
	}
	if($action=='getSerialNoDetails')
	{
		$serial = isset($_POST['serial']) ? $_POST['serial'] : null;
		$getData = $action($serial);
		$response['serialno'] = $getData['serialno'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='checkStock')
	{
		$qtyval = isset($_POST['qtyval']) ? $_POST['qtyval'] : null;
		$plantid = isset($_POST['plantid']) ? $_POST['plantid'] : null;
		$productid = isset($_POST['productid']) ? $_POST['productid'] : null;
		$getData = $action($qtyval,$plantid,$productid);
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getPJP')
	{
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($id);
		$response['year'] = $getData['year'];
		$response['month'] = $getData['month'];
		$response['normal'] = $getData['normal'];
		$response['calamity'] = $getData['calamity'];
		$response['tbodyBasic'] = $getData['tbodyBasic'];
		$response['tbodyWorking'] = $getData['tbodyWorking'];
		$response['tbodyBill'] = $getData['tbodyBill'];
		$response['rowcountBasic'] = $getData['rowcountBasic'];
		$response['rowcountWorking'] = $getData['rowcountWorking'];
		$response['rowcountBill'] = $getData['rowcountBill'];
		echo json_encode($response);
	}
	if($action == 'getallCategory')
	{
		$postingdate = isset($_POST['postingdate']) ? $_POST['postingdate'] : null;
		$getData = $action($postingdate);
		$response['tbodycat'] = $getData['tbodycat'];
		$response['rowcountcat'] = $getData['rowcountcat'];
		echo json_encode($response);
	}
	if($action == 'getPriceforProduct')
	{
		$pricebookid = isset($_POST['id']) ? $_POST['id'] : null;
		$productid = isset($_POST['productid']) ? $_POST['productid'] : null;
		$getData = $action($pricebookid,$productid);
		$response['price'] = $getData['price'];
		echo json_encode($response);
	}
	if($action == 'getClaimforSO')
	{
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($id);
		$response['productid'] = $getData['productid'];
		$response['productname'] = $getData['productname'];
		$response['productcode'] = $getData['productcode'];
		$response['productunit'] = $getData['productunit'];
		$response['productcategory'] = $getData['productcategory'];
		$response['customerid'] = $getData['customerid'];
		$response['custname'] = $getData['custname'];
		$response['contactid'] = $getData['contactid'];
		$response['contactname'] = $getData['contactname'];
		$response['plantid'] = $getData['plantid'];
		$response['plantname'] = $getData['plantname'];
		echo json_encode($response);
	}
	if($action=='getSalesBudget')
	{
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($id);
		$response['cpid'] = $getData['cpid'];
		$response['cpname'] = $getData['cpname'];
		$response['custid'] = $getData['custid'];
		$response['customer'] = $getData['customer'];
		$response['district'] = $getData['district'];
		$response['state'] = $getData['state'];
		$response['place'] = $getData['place'];
		$response['nature'] = $getData['nature'];
		$response['grade'] = $getData['grade'];
		$response['year'] = $getData['year'];
		$response['tbodycat'] = $getData['tbodycat'];
		$response['rowcountcat'] = $getData['rowcountcat'];
		/*$response['tbody4W'] = $getData['tbody4W'];
		$response['tbody2W'] = $getData['tbody2W'];
		$response['tbodyIB'] = $getData['tbodyIB'];
		$response['tbodyER'] = $getData['tbodyER'];
		$response['rowcount4W'] = $getData['rowcount4W'];
		$response['rowcount2W'] = $getData['rowcount2W'];
		$response['rowcountIB'] = $getData['rowcountIB'];
		$response['rowcountER'] = $getData['rowcountER'];*/
		echo json_encode($response);
	}
	if($action=='getTotalSalesOrderforProduct')
	{
		$yr = isset($_POST['yr']) ? $_POST['yr'] : null;
		$district = isset($_POST['district']) ? $_POST['district'] : null;
		$code = isset($_POST['product']) ? $_POST['product'] : null;
		$div = isset($_POST['div']) ? $_POST['div'] : null;
		$getData = $action($yr,$district,$code,$div);
		$response['qty4w'] = $getData['qty4w'];
		$response['qty2w'] = $getData['qty2w'];
		$response['qtyib'] = $getData['qtyib'];
		$response['qtyer'] = $getData['qtyer'];
		echo json_encode($response);
	}
	if($action=='getAOItem')
	{
		$aoid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($aoid);
		$response['totalcount'] = $getData['totalcount'];
		$response['tbody'] = $getData['tbody'];
		$response['plantid'] = $getData['plantid'];
		$response['plant'] = $getData['plant'];
		echo json_encode($response);
	}
	if($action=='getAssemblyLineItemforIBD')
	{
		$aid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($aid);
		$response['totalcount'] = $getData['totalcount'];
		$response['tbody'] = $getData['tbody'];
		$response['plantid'] = $getData['plantid'];
		$response['plantname'] = $getData['plantname'];
		$response['plantcode'] = $getData['plantcode'];
		echo json_encode($response);
	}
	if($action=='getAOLineItemforOBD')
	{
		$aoid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($aoid);
		$response['totalcount'] = $getData['totalcount'];
		$response['plantid'] =$getData['plantid'];
		$response['plant'] = $getData['plant'];
		$response['tbody'] = $getData['tbody'];
		echo json_encode($response);
	}
	if($action=='getContact')
	{
		$customerid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($customerid);
		$response['custno'] = $getData['custno'];
		$response['custgst'] = $getData['custgst'];
		$response['custpan'] = $getData['custpan'];
		$response['contactid'] = $getData['contactid'];
		$response['contactname'] = $getData['contactname'];
		echo json_encode($response);
	}
	if($action=='getProductDetailsfromSO')
	{
		$soid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($soid);
		$response['totalrow'] = $getData['totalrow'];
		$response['schemediscount'] = $getData['schemediscount'];
		$response['reference'] = $getData['reference'];
		$response['discountallow'] = $getData['discountallow'];
		$response['discountapply'] = $getData['discountapply'];
		$response['taxregion'] = $getData['taxregion'];
		$response['currency'] = $getData['currency'];
		$response['taxtype'] = $getData['taxtype'];
		$response['assignedto'] = $getData['assignedto'];
		$response['totalcount'] = $getData['totalcount'];
		$response['gst'] = $getData['gst'];
		$response['pan'] = $getData['pan'];
		$response['alltotal'] = $getData['alltotal'];
		$response['subtotal'] = $getData['subtotal'];
		$response['category'] = $getData['category'];
		$response['tc'] = $getData['tc'];
		$response['tax1'] = $getData['tax1'];
		$response['tax2'] = $getData['tax2'];
		$response['tax3'] = $getData['tax3'];
		$response['totaltaxval'] = $getData['totaltaxval'];
		$response['customer'] = $getData['customer'];
		$response['custno'] = $getData['custno'];
		$response['contactid'] = $getData['contactid'];
		$response['contactname'] = $getData['contactname'];
		$response['plantid'] = $getData['plantid'];
		$response['plantname'] = $getData['plantname'];
		$response['adjusticon'] = $getData['adjusticon'];
		$response['adjustval'] = $getData['adjustval'];
		$response['accname'] = $getData['accname'];
		$response['html'] = $getData['html'];
		$response['message'] = $getData['message'];
		$response['savestatestatus'] = $getData['savestatestatus'];
		$response['paymentid'] = $getData['paymentid'];
		$response['paymentname'] = $getData['paymentname'];
		$response['paymentval'] = $getData['paymentval'];
		$response['paymentlength'] = $getData['paymentlength'];
		$response['debitpaymentid'] = $getData['debitpaymentid'];
		$response['debitpaymentname'] = $getData['debitpaymentname'];
		$response['debitpaymentval'] = $getData['debitpaymentval'];
		$response['debitpaymentlength'] = $getData['debitpaymentlength'];
		$response['creditpaymentid'] = $getData['creditpaymentid'];
		$response['creditpaymentname'] = $getData['creditpaymentname'];
		$response['creditpaymentval'] = $getData['creditpaymentval'];
		$response['creditpaymentlength'] = $getData['creditpaymentlength'];
		$response['advance'] = $getData['advance'];
		$response['debit'] = $getData['debit'];
		$response['credit'] = $getData['credit'];
		echo json_encode($response);
	}
	if($action == 'getInvoiceCredit')
	{
		$invoiceid = isset($_POST['invoiceid']) ? $_POST['invoiceid'] : null;
		$getData = $action($invoiceid);
		$response['creditval'] = $getData['creditval'];
		echo json_encode($response);
	}
	if($action == 'getInvoiceDebit')
	{
		$invoiceid = isset($_POST['invoiceid']) ? $_POST['invoiceid'] : null;
		$getData = $action($invoiceid);
		$response['debitval'] = $getData['debitval'];
		echo json_encode($response);
	}
	if($action == 'purchasePaymentInvoiceDetails')
	{
		$invoiceid = isset($_POST['recordid']) ? $_POST['recordid'] : null;
		$grid = isset($_POST['grid']) ? $_POST['grid'] : null;
		$soid = isset($_POST['soid']) ? $_POST['soid'] : null;
		$type = isset($_POST['type']) ? $_POST['type'] : null;
		$getData = $action($invoiceid, $grid, $soid, $type);
		$response['advanceval'] = $getData['advanceval'];
		$response['debitval'] = $getData['debitval'];
		$response['creditval'] = $getData['creditval'];
		echo json_encode($response);
	}
	if($action == 'salesPaymentDetails')
	{
		$po = isset($_POST['recordid']) ? $_POST['recordid'] : null;
		$getData = $action($po);
		$response['advanceval'] = $getData['advanceval'];
		$response['debitval'] = $getData['debitval'];
		$response['creditval'] = $getData['creditval'];
		echo json_encode($response);
	}
	if($action == 'purchasePaymentDetails')
	{
		$po = isset($_POST['recordid']) ? $_POST['recordid'] : null;
		$getData = $action($po);
		$response['advanceval'] = $getData['advanceval'];
		$response['debitval'] = $getData['debitval'];
		$response['creditval'] = $getData['creditval'];
		echo json_encode($response);
	}
	if($action=='getProductDetailsfromGR')
	{
		$grid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($grid);
		$response['totalrow'] = $getData['totalrow'];
		$response['po'] = $getData['po'];
		$response['taxregion'] = $getData['taxregion'];
		$response['currency'] = $getData['currency'];
		$response['taxtype'] = $getData['taxtype'];
		$response['adjusticon'] = $getData['adjusticon'];
		$response['adjustval'] = $getData['adjustval'];
		$response['poname'] = $getData['poname'];
		$response['vendor'] = $getData['vendor'];
		$response['vendorname'] = $getData['vendorname'];
		$response['vendorstreet'] = $getData['vendorstreet'];
		$response['vendorcity'] = $getData['vendorcity'];
		$response['vendorstate'] = $getData['vendorstate'];
		$response['vendorpobox'] = $getData['vendorpobox'];
		$response['vendorpostalcode'] = $getData['vendorpostalcode'];
		$response['vendorcountry'] = $getData['vendorcountry'];
		$response['category'] = $getData['category'];
		$response['plant'] = $getData['plant'];
		$response['plantname'] = $getData['plantname'];
		$response['totalcount'] = $getData['totalcount'];
		$response['subtotal'] = $getData['subtotal'];
		$response['html'] = $getData['html'];
		$response['paymentid'] = $getData['paymentid'];
		$response['paymentname'] = $getData['paymentname'];
		$response['paymentval'] = $getData['paymentval'];
		$response['paymentlength'] = $getData['paymentlength'];
		$response['debitpaymentid'] = $getData['debitpaymentid'];
		$response['debitpaymentname'] = $getData['debitpaymentname'];
		$response['debitpaymentval'] = $getData['debitpaymentval'];
		$response['debitpaymentlength'] = $getData['debitpaymentlength'];
		$response['creditpaymentid'] = $getData['creditpaymentid'];
		$response['creditpaymentname'] = $getData['creditpaymentname'];
		$response['creditpaymentval'] = $getData['creditpaymentval'];
		$response['creditpaymentlength'] = $getData['creditpaymentlength'];
		$response['advance'] = $getData['advance'];
		$response['debit'] = $getData['debit'];
		$response['credit'] = $getData['credit'];
		$response['tax1val'] = $getData['tax1val'];
		$response['tax2val'] = $getData['tax2val'];
		$response['tax3val'] = $getData['tax3val'];
		$response['totaltaxval'] = $getData['totaltaxval'];
		$response['grandtotal'] = $getData['grandtotal'];
		echo json_encode($response);
	}
	if($action=='getTotalSalesOrder')
	{
		$yr = isset($_POST['yr']) ? $_POST['yr'] : null;
		$districtid = isset($_POST['district']) ? $_POST['district'] : null;
		$div = isset($_POST['div']) ? $_POST['div'] : null;
		$getData = $action($yr,$districtid,$div);
		$response['qty4w'] = $getData['qty4w'];
		$response['qty2w'] = $getData['qty2w'];
		$response['qtyib'] = $getData['qtyib'];
		$response['qtyer'] = $getData['qtyer'];
		echo json_encode($response);
	}
	if($action=='getFiscalDetails')
	{
		$plantid = isset($_POST['plant']) ? $_POST['plant'] : null;
		$year = isset($_POST['year']) ? $_POST['year'] : null;
		$month = isset($_POST['month']) ? $_POST['month'] : null;
		$module = isset($_POST['module']) ? $_POST['module'] : null;
		$getData = $action($plantid, $year, $month, $module);
		$response['days'] = $getData['days'];
		$response['fiscalval'] = $getData['fiscalval'];
		echo json_encode($response);
	}
	if($action=='getAllDays')
	{
    $yr = isset($_POST['year']) ? $_POST['year'] : null;
	$month = isset($_POST['month']) ? $_POST['month'] : null;
    $getData = $action($yr,$month);
	$response['days'] = $getData['days'];
	$response['month'] = $getData['month'];
	echo json_encode($response);
	}
	if($action=='getVendorAdvancePayments')
	{
    $vendorid = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($vendorid);
	$response['paymentlength'] = $getData['paymentlength'];
	$response['paymentname'] = $getData['paymentname'];
	$response['paymentval'] = $getData['paymentval'];
	$response['paymentid'] = $getData['paymentid'];
	$response['debitpaymentid'] = $getData['debitpaymentid'];
	$response['debitpaymentname'] = $getData['debitpaymentname'];
	$response['debitpaymentval'] = $getData['debitpaymentval'];
	$response['debitpaymentlength'] = $getData['debitpaymentlength'];
	$response['creditpaymentid'] = $getData['creditpaymentid'];
	$response['creditpaymentname'] = $getData['creditpaymentname'];
	$response['creditpaymentval'] = $getData['creditpaymentval'];
	$response['creditpaymentlength'] = $getData['creditpaymentlength'];
	echo json_encode($response);
	}
	if($action=='getAdvancePayments')
	{
    $accountid = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($accountid);
	$response['paymentlength'] = $getData['paymentlength'];
	$response['paymentname'] = $getData['paymentname'];
	$response['paymentval'] = $getData['paymentval'];
	$response['paymentid'] = $getData['paymentid'];
	$response['debitpaymentid'] = $getData['debitpaymentid'];
	$response['debitpaymentname'] = $getData['debitpaymentname'];
	$response['debitpaymentval'] = $getData['debitpaymentval'];
	$response['debitpaymentlength'] = $getData['debitpaymentlength'];
	$response['creditpaymentid'] = $getData['creditpaymentid'];
	$response['creditpaymentname'] = $getData['creditpaymentname'];
	$response['creditpaymentval'] = $getData['creditpaymentval'];
	$response['creditpaymentlength'] = $getData['creditpaymentlength'];
	echo json_encode($response);
	}
	if($action=='purchaseInvoiceDetails')
	{
    $vendorid = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($vendorid);
	$response['tbody'] = $getData['tbody'];
	$response['rowcount'] = $getData['rowcount'];
	$response['total'] = $getData['total'];
	$response['alltotal'] = $getData['alltotal'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['message'] = $getData['message'];
	echo json_encode($response);
	}
	if($action=='getdebitedVendorInvoice')
	{
		$vendorpaymentid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($vendorpaymentid);
		$response['header'] = $getData['header'];
		$response['tbody'] = $getData['tbody'];
		$response['tbodyend'] = $getData['tbodyend'];
		$response['rowcount'] = $getData['rowcount'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getcreditedVendorInvoice')
	{
		$vendorpaymentid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($vendorpaymentid);
		$response['header'] = $getData['header'];
		$response['tbody'] = $getData['tbody'];
		$response['tbodyend'] = $getData['tbodyend'];
		$response['rowcount'] = $getData['rowcount'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getadvanceVendorInvoice')
	{
		$vendorpaymentid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($vendorpaymentid);
		$response['header'] = $getData['header'];
		$response['tbody'] = $getData['tbody'];
		$response['tbodyend'] = $getData['tbodyend'];
		$response['rowcount'] = $getData['rowcount'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getSalesInvoice')
	{
		$customerid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($customerid);
		$response['header'] = $getData['header'];
		$response['tbody'] = $getData['tbody'];
		$response['tbodyend'] = $getData['tbodyend'];
		$response['rowcount'] = $getData['rowcount'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getSalesdebitInvoice')
	{
		$customerid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($customerid);
		$response['header'] = $getData['header'];
		$response['tbody'] = $getData['tbody'];
		$response['tbodyend'] = $getData['tbodyend'];
		$response['rowcount'] = $getData['rowcount'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getSalescreditInvoice')
	{
		$customerid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($customerid);
		$response['header'] = $getData['header'];
		$response['tbody'] = $getData['tbody'];
		$response['tbodyend'] = $getData['tbodyend'];
		$response['rowcount'] = $getData['rowcount'];
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	
	if($action=='salesInvoiceDetails')
	{
    $customerid = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($customerid);
	$response['tbody'] = $getData['tbody'];
	$response['rowcount'] = $getData['rowcount'];
	$response['directmode'] = $getData['directmode'];
	$response['total'] = $getData['total'];
	$response['sumdueamount'] =  $getData['sumdueamount'];
	$response['alltotal'] = $getData['alltotal'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['message'] = $getData['message'];
	echo json_encode($response);
	}
	
	if($action == 'getDiscountValues')
	{
		$soid = isset($_POST['recordid']) ? $_POST['recordid'] : null;
		$getData = $action($soid);
		$response['totaldiscount'] = $getData['totaldiscount'];
		$response['annualunitamount'] = $getData['annualunitamount'];
		$response['annnualtotaldeduct'] = $getData['annnualtotaldeduct'];
		$response['annualcashpercent'] = $getData['annualcashpercent'];
		$response['annualcashpercentval'] = $getData['annualcashpercentval'];
		$response['annualtargetpercent'] = $getData['annualtargetpercent'];
		$response['annualtargetpercentval'] = $getData['annualtargetpercentval'];
		$response['annualretailerpercent'] = $getData['annualretailerpercent'];
		$response['annualretailerpercentval'] = $getData['annualretailerpercentval'];
		$response['halfyearunitamount'] = $getData['halfyearunitamount'];
		$response['halfyeartotaldeduct'] = $getData['halfyeartotaldeduct'];
		$response['halfyearcashpercent'] = $getData['halfyearcashpercent'];
		$response['halfyearcashpercentval'] = $getData['halfyearcashpercentval'];
		$response['halfyeartargetpercent'] = $getData['halfyeartargetpercent'];
		$response['halfyeartargetpercentval'] = $getData['halfyeartargetpercentval'];
		$response['halfyearretailerpercent'] = $getData['halfyearretailerpercent'];
		$response['halfyearretailerpercentval'] = $getData['halfyearretailerpercentval'];
		$response['quarterunitamount'] = $getData['quarterunitamount'];
		$response['quartertotaldeduct'] = $getData['quartertotaldeduct'];
		$response['quartercashpercent'] = $getData['quartercashpercent'];
		$response['quartercashpercentval'] = $getData['quartercashpercentval'];
		$response['quartertargetpercent'] = $getData['quartertargetpercent'];
		$response['quartertargetpercentval'] = $getData['quartertargetpercentval'];
		$response['quarterretailerpercent'] = $getData['quarterretailerpercent'];
		$response['quarterretailerpercentval'] = $getData['quarterretailerpercentval'];
		$response['monthunitamount'] = $getData['monthunitamount'];
		$response['monthtotaldeduct'] = $getData['monthtotaldeduct'];
		$response['monthcashpercent'] = $getData['monthcashpercent'];
		$response['monthcashpercentval'] = $getData['monthcashpercentval'];
		$response['monthtargetpercent'] = $getData['monthtargetpercent'];
		$response['monthtargetpercentval'] = $getData['monthtargetpercentval'];
		$response['monthretailerpercent'] = $getData['monthretailerpercent'];
		$response['monthretailerpercentval'] = $getData['monthretailerpercentval'];
		$response['advpercent'] = $getData['advpercent'];
		$response['advpercentamount'] =  $getData['advpercentamount'];
		$response['paypercent'] =  $getData['paypercent'];
		$response['paypercentamount'] =  $getData['paypercentamount'];
		$response['paycashpercent'] =  $getData['paycashpercent'];
		$response['paycashpercentamount'] =  $getData['paycashpercentamount'];
		$response['pay7percent'] =  $getData['pay7percent'];
		$response['pay7percentamount'] =  $getData['pay7percentamount'];
		$response['pay15percent'] =  $getData['pay15percent'];
		$response['pay15percentamount'] =  $getData['pay15percentamount'];
		$response['pay30percent'] =  $getData['pay30percent'];
		$response['pay30percentamount'] =  $getData['pay30percentamount'];
		$response['schemediscount'] = $getData['schemediscount'];
		echo json_encode($response);
	}
	if($action == 'InvoiceDetails')
	{
		$invoiceid = isset($_POST['recordid']) ? $_POST['recordid'] : null;
		$getData = $action($invoiceid);
		$response['type'] = $getData['type'];
		$response['soid'] = $getData['soid'];
		$response['grid'] = $getData['grid'];
		echo json_encode($response);
	}
	if($action == 'getInvoiceDiscountValues')
	{
		$soid = isset($_POST['recordid']) ? $_POST['recordid'] : null;
		$getData = $action($soid);
		$response['totaldiscount'] = $getData['totaldiscount'];
		$response['annualunitamount'] = $getData['annualunitamount'];
		$response['annnualtotaldeduct'] = $getData['annnualtotaldeduct'];
		$response['annualcashpercent'] = $getData['annualcashpercent'];
		$response['annualcashpercentval'] = $getData['annualcashpercentval'];
		$response['annualtargetpercent'] = $getData['annualtargetpercent'];
		$response['annualtargetpercentval'] = $getData['annualtargetpercentval'];
		$response['annualretailerpercent'] = $getData['annualretailerpercent'];
		$response['annualretailerpercentval'] = $getData['annualretailerpercentval'];
		$response['halfyearunitamount'] = $getData['halfyearunitamount'];
		$response['halfyeartotaldeduct'] = $getData['halfyeartotaldeduct'];
		$response['halfyearcashpercent'] = $getData['halfyearcashpercent'];
		$response['halfyearcashpercentval'] = $getData['halfyearcashpercentval'];
		$response['halfyeartargetpercent'] = $getData['halfyeartargetpercent'];
		$response['halfyeartargetpercentval'] = $getData['halfyeartargetpercentval'];
		$response['halfyearretailerpercent'] = $getData['halfyearretailerpercent'];
		$response['halfyearretailerpercentval'] = $getData['halfyearretailerpercentval'];
		$response['quarterunitamount'] = $getData['quarterunitamount'];
		$response['quartertotaldeduct'] = $getData['quartertotaldeduct'];
		$response['quartercashpercent'] = $getData['quartercashpercent'];
		$response['quartercashpercentval'] = $getData['quartercashpercentval'];
		$response['quartertargetpercent'] = $getData['quartertargetpercent'];
		$response['quartertargetpercentval'] = $getData['quartertargetpercentval'];
		$response['quarterretailerpercent'] = $getData['quarterretailerpercent'];
		$response['quarterretailerpercentval'] = $getData['quarterretailerpercentval'];
		$response['monthunitamount'] = $getData['monthunitamount'];
		$response['monthtotaldeduct'] = $getData['monthtotaldeduct'];
		$response['monthcashpercent'] = $getData['monthcashpercent'];
		$response['monthcashpercentval'] = $getData['monthcashpercentval'];
		$response['monthtargetpercent'] = $getData['monthtargetpercent'];
		$response['monthtargetpercentval'] = $getData['monthtargetpercentval'];
		$response['monthretailerpercent'] = $getData['monthretailerpercent'];
		$response['monthretailerpercentval'] = $getData['monthretailerpercentval'];
		$response['advpercent'] = $getData['advpercent'];
		$response['advpercentamount'] =  $getData['advpercentamount'];
		$response['paypercent'] =  $getData['paypercent'];
		$response['paypercentamount'] =  $getData['paypercentamount'];
		$response['paycashpercent'] =  $getData['paycashpercent'];
		$response['paycashpercentamount'] =  $getData['paycashpercentamount'];
		$response['pay7percent'] =  $getData['pay7percent'];
		$response['pay7percentamount'] =  $getData['pay7percentamount'];
		$response['pay15percent'] =  $getData['pay15percent'];
		$response['pay15percentamount'] =  $getData['pay15percentamount'];
		$response['pay30percent'] =  $getData['pay30percent'];
		$response['pay30percentamount'] =  $getData['pay30percentamount'];
		$response['schemediscount'] = $getData['schemediscount'];
		echo json_encode($response);
	}
	if($action == 'checkTotalDiscountforInvoice')
	{
		$branchid = isset($_POST['branch']) ? $_POST['branch'] : null;
		$category = isset($_POST['category']) ? $_POST['category'] : null;
		$totalqty = isset($_POST['totalqty']) ? $_POST['totalqty'] : null;
		$nettotalprice = isset($_POST['nettotalprice']) ? $_POST['nettotalprice'] : null;
		$date = isset($_POST['date']) ? $_POST['date'] : null;
		$accountid = isset($_POST['accountid']) ? $_POST['accountid'] : null;
		$discountallow = isset($_POST['discountallow']) ? $_POST['discountallow'] : null;
		$getData = $action($branchid,$category,$totalqty,$nettotalprice,$date,$accountid,$discountallow);
		$response['discountapply'] = $getData['discountapply'];
		$response['totalamount'] = $getData['totalamount'];
		$response['totaldeductamount'] = $getData['totaldeductamount'];
		$response['advpercent'] = $getData['advpercent'];
		$response['advpercentamount'] =  $getData['advpercentamount'];
		$response['paypercent'] =  $getData['paypercent'];
		$response['paypercentamount'] =  $getData['paypercentamount'];
		$response['paypercentcash'] = $getData['paypercentcash'];
		$response['paypercentcashamount'] = $getData['paypercentcashamount'];
		$response['pay7percent'] =  $getData['pay7percent'];
		$response['pay7percentamount'] =  $getData['pay7percentamount'];
		$response['pay15percent'] =  $getData['pay15percent'];
		$response['pay15percentamount'] =  $getData['pay15percentamount'];
		$response['pay30percent'] =  $getData['pay30percent'];
		$response['pay30percentamount'] =  $getData['pay30percentamount'];
		$response['annualunitamount'] = $getData['annualunitamount'];
		$response['annnualtotaldeduct'] = $getData['annnualtotaldeduct'];
		$response['annualcashpercent'] = $getData['annualcashpercent'];
		$response['annualcashpercentval'] = $getData['annualcashpercentval'];
		$response['annualtargetpercent'] = $getData['annualtargetpercent'];
		$response['annualtargetpercentval'] = $getData['annualtargetpercentval'];
		$response['annualretailerpercent'] = $getData['annualretailerpercent'];
		$response['annualretailerpercentval'] = $getData['annualretailerpercentval'];
		$response['annuallydiscountstatus'] = $getData['annuallydiscountstatus'];
		$response['halfyearunitamount'] = $getData['halfyearunitamount'];
		$response['halfyeartotaldeduct'] = $getData['halfyeartotaldeduct'];
		$response['halfyearcashpercent'] = $getData['halfyearcashpercent'];
		$response['halfyearcashpercentval'] = $getData['halfyearcashpercentval'];
		$response['halfyeartargetpercent'] = $getData['halfyeartargetpercent'];
		$response['halfyeartargetpercentval'] = $getData['halfyeartargetpercentval'];
		$response['halfyearretailerpercent'] = $getData['halfyearretailerpercent'];
		$response['halfyearretailerpercentval'] = $getData['halfyearretailerpercentval'];
		$response['halfyearlydiscountstatus'] = $getData['halfyearlydiscountstatus'];
		$response['quarterunitamount'] = $getData['quarterunitamount'];
		$response['quartertotaldeduct'] = $getData['quartertotaldeduct'];
		$response['quartercashpercent'] = $getData['quartercashpercent'];
		$response['quartercashpercentval'] = $getData['quartercashpercentval'];
		$response['quartertargetpercent'] = $getData['quartertargetpercent'];
		$response['quartertargetpercentval'] = $getData['quartertargetpercentval'];
		$response['quarterretailerpercent'] = $getData['quarterretailerpercent'];
		$response['quarterretailerpercentval'] = $getData['quarterretailerpercentval'];
		$response['quarterlydiscountstatus'] = $getData['quarterlydiscountstatus'];
		$response['monthunitamount'] = $getData['monthunitamount'];
		$response['monthtotaldeduct'] = $getData['monthtotaldeduct'];
		$response['monthcashpercent'] = $getData['monthcashpercent'];
		$response['monthcashpercentval'] = $getData['monthcashpercentval'];
		$response['monthtargetpercent'] = $getData['monthtargetpercent'];
		$response['monthtargetpercentval'] = $getData['monthtargetpercentval'];
		$response['monthretailerpercent'] = $getData['monthretailerpercent'];
		$response['monthretailerpercentval'] = $getData['monthretailerpercentval'];
		$response['monthlydiscountstatus'] = $getData['monthlydiscountstatus'];
		echo json_encode($response);
	}
	if($action == 'checkTotalDiscount')
	{
		$branchid = isset($_POST['branch']) ? $_POST['branch'] : null;
		$category = isset($_POST['category']) ? $_POST['category'] : null;
		$totalqty = isset($_POST['totalqty']) ? $_POST['totalqty'] : null;
		$nettotalprice = isset($_POST['nettotalprice']) ? $_POST['nettotalprice'] : null;
		$date = isset($_POST['date']) ? $_POST['date'] : null;
		$accountid = isset($_POST['accountid']) ? $_POST['accountid'] : null;
		$discountallow = isset($_POST['discountallow']) ? $_POST['discountallow'] : null;
		$advdiscount = isset($_POST['advdiscountallow']) ? $_POST['advdiscountallow'] : null;
		$getData = $action($branchid,$category,$totalqty,$nettotalprice,$date,$accountid,$discountallow,$advdiscount);
		$response['discountapply'] = $getData['discountapply'];
		$response['totalamount'] = $getData['totalamount'];
		$response['totaldeductamount'] = $getData['totaldeductamount'];
		$response['involdsame'] = $getData['involdsame'];
		$response['involdsamecash'] = $getData['involdsamecash'];
		$response['invold7'] = $getData['invold7'];
		$response['invold15'] = $getData['invold15'];
		$response['invold30'] = $getData['invold30'];
		$response['advpercent'] = $getData['advpercent'];
		$response['advpercentamount'] =  $getData['advpercentamount'];
		$response['paypercent'] =  $getData['paypercent'];
		$response['paypercentamount'] =  $getData['paypercentamount'];
		$response['paypercentcash'] = $getData['paypercentcash'];
		$response['paypercentcashamount'] = $getData['paypercentcashamount'];
		$response['pay7percent'] =  $getData['pay7percent'];
		$response['pay7percentamount'] =  $getData['pay7percentamount'];
		$response['pay15percent'] =  $getData['pay15percent'];
		$response['pay15percentamount'] =  $getData['pay15percentamount'];
		$response['pay30percent'] =  $getData['pay30percent'];
		$response['pay30percentamount'] =  $getData['pay30percentamount'];
		$response['annualunitamount'] = $getData['annualunitamount'];
		$response['annnualtotaldeduct'] = $getData['annnualtotaldeduct'];
		$response['annualcashpercent'] = $getData['annualcashpercent'];
		$response['annualcashpercentval'] = $getData['annualcashpercentval'];
		$response['annualtargetpercent'] = $getData['annualtargetpercent'];
		$response['annualtargetpercentval'] = $getData['annualtargetpercentval'];
		$response['annualretailerpercent'] = $getData['annualretailerpercent'];
		$response['annualretailerpercentval'] = $getData['annualretailerpercentval'];
		$response['annuallydiscountstatus'] = $getData['annuallydiscountstatus'];
		$response['halfyearunitamount'] = $getData['halfyearunitamount'];
		$response['halfyeartotaldeduct'] = $getData['halfyeartotaldeduct'];
		$response['halfyearcashpercent'] = $getData['halfyearcashpercent'];
		$response['halfyearcashpercentval'] = $getData['halfyearcashpercentval'];
		$response['halfyeartargetpercent'] = $getData['halfyeartargetpercent'];
		$response['halfyeartargetpercentval'] = $getData['halfyeartargetpercentval'];
		$response['halfyearretailerpercent'] = $getData['halfyearretailerpercent'];
		$response['halfyearretailerpercentval'] = $getData['halfyearretailerpercentval'];
		$response['halfyearlydiscountstatus'] = $getData['halfyearlydiscountstatus'];
		$response['quarterunitamount'] = $getData['quarterunitamount'];
		$response['quartertotaldeduct'] = $getData['quartertotaldeduct'];
		$response['quartercashpercent'] = $getData['quartercashpercent'];
		$response['quartercashpercentval'] = $getData['quartercashpercentval'];
		$response['quartertargetpercent'] = $getData['quartertargetpercent'];
		$response['quartertargetpercentval'] = $getData['quartertargetpercentval'];
		$response['quarterretailerpercent'] = $getData['quarterretailerpercent'];
		$response['quarterretailerpercentval'] = $getData['quarterretailerpercentval'];
		$response['quarterlydiscountstatus'] = $getData['quarterlydiscountstatus'];
		$response['monthunitamount'] = $getData['monthunitamount'];
		$response['monthtotaldeduct'] = $getData['monthtotaldeduct'];
		$response['monthcashpercent'] = $getData['monthcashpercent'];
		$response['monthcashpercentval'] = $getData['monthcashpercentval'];
		$response['monthtargetpercent'] = $getData['monthtargetpercent'];
		$response['monthtargetpercentval'] = $getData['monthtargetpercentval'];
		$response['monthretailerpercent'] = $getData['monthretailerpercent'];
		$response['monthretailerpercentval'] = $getData['monthretailerpercentval'];
		$response['monthlydiscountstatus'] = $getData['monthlydiscountstatus'];
		echo json_encode($response);
	}
	if($action == 'checkDiscount')
	{
		$productid = isset($_POST['productid']) ? $_POST['productid'] : null;
		$curqty = isset($_POST['curqty']) ? $_POST['curqty'] : null;
		$netprice = isset($_POST['netprice']) ? $_POST['netprice'] : null;
		$getData = $action($productid,$curqty,$netprice);
		$response['totalamount'] = $getData['totalamount'];
		$response['totaldeductamount'] = $getData['totaldeductamount'];
		$response['overtotalamount'] = $getData['overtotalamount'];
		$response['overtotaldeductamount'] = $getData['overtotaldeductamount'];
		$response['cashamount'] = $getData['cashamount'];
		$response['totalcashamount'] = $getData['totalcashamount'];
		$response['cashpercent'] = $getData['cashpercent'];
		$response['targetpercent'] = $getData['targetpercent'];
		$response['retailerpercent'] = $getData['retailerpercent'];
		$response['cashpercentval'] = $getData['cashpercentval'];
		$response['targetpercentval'] = $getData['targetpercentval'];
		$response['retailerpercentval'] = $getData['retailerpercentval'];
		$response['overcashamount'] = $getData['overcashamount'];
		$response['overtotalcashamount'] = $getData['overtotalcashamount'];
		$response['overcashpercent'] = $getData['overcashpercent'];
		$response['overtargetpercent'] = $getData['overtargetpercent'];
		$response['overretailerpercent'] = $getData['overretailerpercent'];
		$response['overcashpercentval'] = $getData['overcashpercentval'];
		$response['overtargetpercentval'] = $getData['overtargetpercentval'];
		$response['overretailerpercentval'] = $getData['overretailerpercentval'];
		echo json_encode($response);
	}
	if($action=='getProductPoints')
	{
		$productid = isset($_POST['productid']) ? $_POST['productid'] : null;
		$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
		$getData = $action($productid,$quantity);
		$response['point'] = $getData['point'];
		echo json_encode($response);
	}
	if($action=='getProductAllDetails')
	{
		$productid = isset($_POST['productid']) ? $_POST['productid'] : null;
		$getData = $action($productid);
		$response['productcode'] = $getData['productcode'];
		$response['productunit'] = $getData['productunit'];
		$response['ah'] = $getData['ah'];
		$response['point'] = $getData['point'];
		$response['category'] = $getData['category'];
		echo json_encode($response);
	}
	if($action=='getcustDetails')
	{
		$accountid = isset($_POST['custid']) ? $_POST['custid'] : null;
		$getData = $action($accountid);
		$response['custpoint'] = $getData['custpoint'];
		$response['amount'] = $getData['amount'];
		echo json_encode($response);
	}
	if($action=='getCurrency')
	{
		$getData = $action();
		$response['id'] = $getData['id'];
		$response['code'] = $getData['code'];
		echo json_encode($response);
	}
	if($action=='getVendorCurreny')
	{
		$vendorid = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($vendorid);
		$response['code'] = $getData['code'];
		echo json_encode($response);
	}
	if($action=='allProductDetails')
	{
    $getData = $action();
	$response['tbody4W'] = $getData['tbody4W'];
	$response['tbody2W'] = $getData['tbody2W'];
	$response['tbodyIB'] = $getData['tbodyIB'];
	$response['tbodyER'] = $getData['tbodyER'];
	$response['rowcount4W'] = $getData['rowcount4W'];
	$response['rowcount2W'] = $getData['rowcount2W'];
	$response['rowcountIB'] = $getData['rowcountIB'];
	$response['rowcountER'] = $getData['rowcountER'];
	echo json_encode($response);
	}
	if($action=='getSerialWarrantyConsumerDetails')
	{
    $serial = isset($_POST['serial']) ? $_POST['serial'] : null;
	$getData = $action($serial);
	$response['warrantyno'] = $getData['warrantyno'];
	$response['warranty'] = $getData['warranty'];
	$response['mfgdate'] = $getData['mfgdate'];
	$response['selldate'] = $getData['selldate'];
	$response['productid'] = $getData['productid'];
	$response['productname'] = $getData['productname'];
	$response['productcode'] = $getData['productcode'];
	$response['productgroup'] = $getData['productgroup'];
	$response['productcategory'] = $getData['productcategory'];
	$response['invoiceno'] = $getData['invoiceno'];
	$response['cp'] = $getData['cp'];
	$response['plantid'] = $getData['plantid'];
	$response['plantname'] = $getData['plantname'];
	$response['sellperiod'] = $getData['sellperiod'];
	$response['eaplsellperiod'] = $getData['eaplsellperiod'];
	$response['wfreeperiod'] = $getData['wfreeperiod'];
	$response['wprorataperiod'] = $getData['wprorataperiod'];
	$response['cfreedate'] = $getData['cfreedate'];
	$response['cproratadate'] = $getData['cproratadate'];
	$response['afreedate'] = $getData['afreedate'];
	$response['aproratadate'] = $getData['aproratadate'];
	$response['stage'] = $getData['stage'];
	$response['status'] = $getData['status'];
	$response['purchasedate'] = $getData['purchasedate'];
	$response['consumer'] = $getData['consumer'];
	$response['mail'] = $getData['mail'];
	$response['mobile'] = $getData['mobile'];
	$response['street'] = $getData['street'];
	$response['city'] = $getData['city'];
	$response['po'] = $getData['po'];
	$response['state'] = $getData['state'];
	$response['country'] = $getData['country'];
	$response['zip'] = $getData['zip'];
	$response['ppoint'] = $getData['ppoint'];
	$response['pplace'] = $getData['pplace'];
	$response['pdis'] = $getData['pdis'];
	$response['pstate'] = $getData['pstate'];
	$response['vehiclemake'] = $getData['vehiclemake'];
	$response['modelno'] = $getData['modelno'];
	$response['voltagewithoutload'] = $getData['voltagewithoutload'];
	$response['acligthsload'] = $getData['acligthsload'];
	$response['leakage'] = $getData['leakage'];
	$response['OEFitment'] = $getData['OEFitment'];
	$response['datecheckap'] = $getData['datecheckap'];
	$response['make'] = $getData['make'];
	$response['modeltype'] = $getData['modeltype'];
	$response['my'] = $getData['my'];
	$response['life'] = $getData['life'];
	$response['installdp'] = $getData['installdp'];
	$response['invertermake'] = $getData['invertermake'];
	$response['invertermodel'] = $getData['invertermodel'];
	$response['capacity'] = $getData['capacity'];
	$response['sysvoltage'] = $getData['sysvoltage'];
	$response['dcvoltagerate'] = $getData['dcvoltagerate'];
	$response['charge'] = $getData['charge'];
	$response['discharge'] = $getData['discharge'];
	$response['line'] = $getData['line'];
	$response['inverterop'] = $fetData['inverterop'];
	$response['drivermotor'] = $getData['drivermotor'];
	$response['controlsys'] = $getData['controlsys'];
	$response['amps'] = $getData['amps'];
	$response['erleakage'] = $getData['erleakage'];
	$response['extra'] = $getData['extra'];
	$response['datecheck'] = $getData['datecheck'];
	$response['message'] = $getData['message'];
	echo json_encode($response);
	}
	if($action == 'getSerialCheckNew')
	{
		$productid = isset($_POST['productid']) ? $_POST['productid'] : null;
		$serialno = isset($_POST['serialval']) ? $_POST['serialval'] : null;
		$getData = $action($productid,$serialno);
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action == 'getSerialCheck')
	{
		$serialno = isset($_POST['serialval']) ? $_POST['serialval'] : null;
		$getData = $action($serialno);
		$response['message'] = $getData['message'];
		echo json_encode($response);
	}
	if($action=='getTicketDetails')
	{
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$getData = $action($id);
		$response['serialno'] = $getData['serialno'];
		$response['regdate'] = $getData['regdate'];
		$response['selldate'] = $getData['selldate'];
		$response['productname'] = $getData['productname'];
		$response['productcategory'] = $getData['productcategory'];
		$response['cp'] = $getData['cp'];
		$response['consumer'] = $getData['consumer'];
		$response['plantid'] = $getData['plantid'];
		$response['plantname'] = $getData['plantname'];
		$response['mobile'] = $getData['mobile'];
		$response['street'] = $getData['street'];
		$response['city'] = $getData['city'];
		$response['po'] = $getData['po'];
		$response['state'] = $getData['state'];
		$response['country'] = $getData['country'];
		$response['zip'] = $getData['zip'];
		$response['ppoint'] = $getData['ppoint'];
		$response['pplace'] = $getData['pplace'];
		$response['vrno'] = $getData['vrno'];
		$response['vmodel'] = $getData['vmodel'];
		echo json_encode($response);
	}
	if($action=='getWarrantyDetails')
	{
    $id = isset($_POST['id']) ? $_POST['id'] : null;
	$getData = $action($id);
	$response['serialno'] = $getData['serialno'];
	$response['mfgdate'] = $getData['mfgdate'];
	$response['selldate'] = $getData['selldate'];
	$response['productid'] = $getData['productid'];
	$response['productname'] = $getData['productname'];
	$response['productcode'] = $getData['productcode'];
	$response['productgroup'] = $getData['productgroup'];
	$response['productcategory'] = $getData['productcategory'];
	$response['cp'] = $getData['cp'];
	$response['sellperiod'] = $getData['sellperiod'];
	$response['wfreeperiod'] = $getData['wfreeperiod'];
	$response['wprorataperiod'] = $getData['wprorataperiod'];
	$response['cfreedate'] = $getData['cfreedate'];
	$response['cproratadate'] = $getData['cproratadate'];
	$response['afreedate'] = $getData['afreedate'];
	$response['aproratadate'] = $getData['aproratadate'];
	$response['stage'] = $getData['stage'];
	$response['status'] = $getData['status'];
	$response['purchasedate'] = $getData['purchasedate'];
	$response['consumer'] = $getData['consumer'];
	$response['mail'] = $getData['mail'];
	$response['mobile'] = $getData['mobile'];
	$response['street'] = $getData['street'];
	$response['city'] = $getData['city'];
	$response['po'] = $getData['po'];
	$response['state'] = $getData['state'];
	$response['country'] = $getData['country'];
	$response['zip'] = $getData['zip'];
	$response['make'] = $getData['make'];
	$response['mode'] = $getData['mode'];
	$response['pmy'] = $getData['pmy'];
	$response['life'] = $getData['life'];
	$response['ppoint'] = $getData['ppoint'];
	$response['pplace'] = $getData['pplace'];
	$response['pdis'] = $getData['pdis'];
	$response['pstate'] = $getData['pstate'];
	echo json_encode($response);
	}
}

function getProductDetails($id)
{
	  $product_array = array();
	  $query = mysql_query("SELECT arocrm_products.*, arocrm_productcf.*, arocrm_crmentity.* FROM arocrm_products
		           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
				   INNER JOIN arocrm_productcf ON arocrm_productcf.productid=arocrm_products.productid
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
					 $product_array['ah'] = $row['cf_3446'];
					 $product_array['point'] = $row['cf_5189'];
					 $warranty = '';
					 if($row['cf_3418']!=0){
						 $warranty = $row['cf_3122']." + ".$row['cf_3418'];
					 }else{
						 $warranty = $row['cf_3122'];
					 }
					 $product_array['warranty'] = $warranty;
					 $product_array['category'] = $row['productcategory'];
					 $product_array['description'] = $row['description'];
				   }
				   return $product_array;
}
function getProductAllDetails($productid)
{
	$response = array();
	$product = getProductDetails($productid);
	$response['productname'] = $product['productname'];
	$response['productcode'] = $product['productcode'];
	$response['productunit'] = $product['unit'];
	$response['ah'] = $product['ah'];
	$response['point'] = $product['point'];
	$response['category'] = $product['category'];
	return $response;
}
function getProductPoints($productid, $quantity)
{
	$response = array();
	$cnt = count($productid);
	$allpoint = 0;
	for($i = 0; $i<$cnt; $i++)
	{
	 $query = mysql_query("SELECT arocrm_products.*, arocrm_productcf.*, arocrm_crmentity.* FROM arocrm_products
		           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
				   INNER JOIN arocrm_productcf ON arocrm_productcf.productid=arocrm_products.productid
		           WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$productid[$i]);
				   $row = mysql_fetch_array($query);
				   $point = $row['cf_5189'];
				   $totalpoint = $point * $quantity[$i];
				   $allpoint = $allpoint + $totalpoint;
	}				 
	$response['point'] = $allpoint;
	return $response;
}
function getcustDetails($accountid)
{
	$response = array();
	$accsql = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.* FROM arocrm_account INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$accountid."'");
	$accrow = mysql_fetch_array($accsql);
	$response['custpoint'] = $accrow['cf_5191'];
	$response['amount'] = $accrow['cf_5193'];
	return $response;
}
function checkStock($qtyval,$plantid,$productid)
{
	$response = array();
	$response['message'] = "";
	$getsql = mysql_query("SELECT * FROM arocrm_serialnumber 
	INNER JOIN arocrm_serialnumbercf ON arocrm_serialnumbercf.serialnumberid = arocrm_serialnumber.serialnumberid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_serialnumber.serialnumberid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_serialnumber.cf_nrl_plantmaster496_id = '".$plantid."' AND arocrm_serialnumber.cf_nrl_products16_id = '".$productid."' AND arocrm_serialnumber.cf_nrl_storagelocation106_id = 
	(SELECT storagelocationid FROM arocrm_storagelocation 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_storagelocation.storagelocationid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_storagelocation.cf_nrl_plantmaster561_id = '".$plantid."' AND arocrm_storagelocation.name LIKE '%Main Store%') AND arocrm_serialnumbercf.cf_1256 = 'R' AND arocrm_serialnumbercf.cf_2834 = '1'"); 
	$chkrows = mysql_num_rows($getsql);
	if($qtyval>$chkrows)
	{
		$response['message'] = "Stock for seleceted Product is ".$chkrows.", so put that quantity or less than that quantity";
	}
	return $response;
}
function getPriceforProduct($pricebookid,$productid)
{
	$response = array();
	$sql = mysql_query("SELECT * FROM arocrm_pricebookproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_pricebookproductrel.pricebookid 
	WHERE arocrm_pricebookproductrel.pricebookid = '".$pricebookid."' AND arocrm_pricebookproductrel.productid = '".$productid."'");
	$row = mysql_fetch_array($sql);
	$response['price'] = $row['listprice'];
	return $response;
}
function getSerialCheck($serialno)
{
	$response = array();
	$sql = mysql_query("SELECT * FROM arocrm_serialnumber 
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_serialnumber.serialnumberid
						WHERE arocrm_crmentity.deleted = 0 AND arocrm_serialnumber.name = '".$serialno."'");
	$row = mysql_fetch_array($sql);
	if($row>0)
	{
		$message = "Same Serial Number found";
	}
	else
	{
		$message = "";
	}
	$response['message'] = $message;
	return $response;
}
function getSerialCheckNew($productid,$serialno)
{
	$response = array();
	$sql = mysql_query("SELECT * FROM arocrm_serialnumber 
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_serialnumber.serialnumberid
						WHERE arocrm_crmentity.deleted = 0 AND arocrm_serialnumber.cf_nrl_products16_id = '".$productid."' AND arocrm_serialnumber.name = '".$serialno."'");
	$row = mysql_fetch_array($sql);
	if($row==0)
	{
		$message = "Serial No doesnot exist";
	}
	else
	{
		$message = "";
	}
	$response['message'] = $message;
	return $response;
}
function getPlant()
{
	$response = array();
	$isadmin = $_SESSION['assigned_as_admin'];
	$assignedplant = $_SESSION['assigned_plant'];
	$cnt = count($assignedplant);
	if($isadmin == 'off')
	{
		if($cnt == 1)
		{
			$response['plantid'] = $assignedplant[0];
			$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster
		  INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
		  WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$response['plantid']."'");
			$rowplant = mysql_fetch_array($plantsql);
			$response['plantname'] = $rowplant['name'];
		}
	}
	return $response;
}
function getVendorCurreny($vendorid)
{
	$response = array();
	$vensql = mysql_query("SELECT arocrm_vendorcf.* FROM arocrm_vendor 
	INNER JOIN arocrm_vendorcf ON arocrm_vendorcf.vendorid = arocrm_vendor.vendorid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_vendor.vendorid
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_vendor.vendorid = '".$vendorid."'");
	$venrow = mysql_fetch_array($vensql);
	$vencurcode = $venrow['cf_5121'];
	$cursql = mysql_query("SELECT * FROM `arocrm_currency_info` WHERE `deleted` = 0 AND `currency_code` = '".$vencurcode."'");
	$currow = mysql_fetch_array($cursql);
	$curcode = $currow['currency_code'];
	$response['code'] = $curcode;
	return $response;
}
function getCurrency()
{
	$response = array();
	$id = array();
	$code = array();
	$sql = mysql_query("SELECT * FROM `arocrm_currency_info` WHERE `deleted` = 0");
	while($row = mysql_fetch_array($sql))
	{
		array_push($id,$row['id']);
		array_push($code,$row['currency_code']);
	}
	$response['id'] = $id;
	$response['code'] = $code;
	return $response;
}
function getPlantUsers($plantid)
{
	$response = array();
	$html = "";
	$sql = mysql_query("SELECT arocrm_users.* FROM arocrm_users
				INNER JOIN arocrm_crmentityrel ON arocrm_users.id = arocrm_crmentityrel.relcrmid and arocrm_crmentityrel.module = 'PlantMaster'
				INNER JOIN arocrm_plantmaster ON arocrm_plantmaster.plantmasterid = arocrm_crmentityrel.crmid
			    WHERE arocrm_plantmaster.plantmasterid = ".$plantid);
	while($row=mysql_fetch_array($sql))
	{
		if($row['is_admin'] == 'off')
		{
			$admin = 'No';
		}
		else
		{
			$admin = 'Yes';
		}
		$html .='<tr class="listViewEntries" data-id="'.$row['id'].'" data-recordurl="index.php?module=Users&amp;parent=Settings&amp;view=Detail&amp;record=">
					<td class="related-list-actions"><span class="actionImages">&nbsp;&nbsp;&nbsp;<a name="relationEdit" data-url="index.php?module=Users&amp;parent=Settings&amp;view=Edit&amp;record='.$row['id'].'"><i class="fa fa-pencil" title="Edit"></i></a> &nbsp;&nbsp;<a data-id="'.$row['id'].'" class="relationDelete"><i title="Unlink" class="vicon-linkopen"></i></a></span></td><td class="relatedListEntryValues" title="'.$row['first_name'].'" data-field-type="string" nowrap=""><span class="value textOverflowEllipsis"><a href="index.php?module=Users&amp;parent=Settings&amp;view=Detail&amp;record=">'.$row['first_name'].'</a></span></td><td class="relatedListEntryValues" title="'.$row['last_name'].'" data-field-type="string" nowrap=""><span class="value textOverflowEllipsis"><a href="index.php?module=Users&amp;parent=Settings&amp;view=Detail&amp;record=">'.$row['last_name'].'</a></span></td>
					<td class="relatedListEntryValues" title="" data-field-type="userRole" nowrap=""><span class="value textOverflowEllipsis"><a href="index.php?module=Roles&amp;parent=Settings&amp;view=Edit&amp;record="></a></span></td>
					<td class="relatedListEntryValues" title="'.$row['user_name'].'" data-field-type="string" nowrap=""><span class="value textOverflowEllipsis">'.$row['user_name'].'</span></td>
					<td class="relatedListEntryValues" title="'.$row['status'].'" data-field-type="picklist" nowrap=""><span class="value textOverflowEllipsis"><span class="picklist-color picklist-483-Active"> '.$row['status'].' </span></span></td>
					<td class="relatedListEntryValues" title="'.$row['email1'].'" data-field-type="email" nowrap=""><span class="value textOverflowEllipsis"><a class="emailField cursorPointer" href="mailto:'.$row['email1'].'">'.$row['email1'].'</a></span></td>
					<td class="relatedListEntryValues" title="" data-field-type="email" nowrap=""><span class="value textOverflowEllipsis"></span></td>
					<td class="relatedListEntryValues" title="'.$admin.'" data-field-type="boolean" nowrap=""><span class="value textOverflowEllipsis">'.$admin.'</span></td>
					<td class="relatedListEntryValues" title="" data-field-type="phone" nowrap=""><span class="value textOverflowEllipsis"></span></td>
				</tr>';
	}
	$response['html'] = $html;
	return $response;
}
function removeEdit($module,$recordId)
{
	$response = array();
	$isadmin = $_SESSION['assigned_as_admin'];
	if($module == 'HelpDesk')
	{
		$sqlticket = mysql_query("SELECT arocrm_troubletickets.* FROM arocrm_troubletickets
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_troubletickets.ticketid WHERE arocrm_crmentity.deleted = 0 AND arocrm_troubletickets.ticketid = '".$recordId."'");
		$row = mysql_fetch_array($sqlticket);
		$status = $row['status'];
		if($isadmin == 'off')
		{
			if($status == 'Closed' || $status  == 'Pending for Approval')
			{
				$ticketStatus = 0;
			}
			else
			{
				$ticketStatus = 1;
			}
		}
		else
		{
			$ticketStatus = 1;
		}
	}
	else if($moduleName == 'MarketAnalysis')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_marketanalysis.*, arocrm_marketanalysiscf.* FROM arocrm_marketanalysis
				INNER JOIN arocrm_marketanalysiscf ON arocrm_marketanalysiscf.marketanalysisid = arocrm_marketanalysis.marketanalysisid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_marketanalysis.marketanalysisid WHERE arocrm_crmentity.deleted = 0 AND arocrm_marketanalysis.marketanalysisid = '".$recordId."'"));
				$status = $sqlticket['cf_4803'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'SalesBudget')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudgetcf.* FROM arocrm_salesbudget
				INNER JOIN arocrm_salesbudgetcf ON arocrm_salesbudgetcf.salesbudgetid = arocrm_salesbudget.salesbudgetid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesbudget.salesbudgetid = '".$recordId."'"));
				$status = $sqlticket['cf_4805'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'SalesPlan')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_salesplan.*, arocrm_salesplancf.* FROM arocrm_salesplan
				INNER JOIN arocrm_salesplancf ON arocrm_salesplancf.salesplanid = arocrm_salesplan.salesplanid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesplan.salesplanid WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesplan.salesplanid = '".$recordId."'"));
				$status = $sqlticket['cf_4541'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'StockRequisition')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_stockrequisition.*, arocrm_stockrequisitioncf.* FROM arocrm_stockrequisition
				INNER JOIN arocrm_stockrequisitioncf ON arocrm_stockrequisitioncf.stockrequisitionid = arocrm_stockrequisition.stockrequisitionid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_stockrequisition.stockrequisitionid WHERE arocrm_crmentity.deleted = 0 AND arocrm_stockrequisition.stockrequisitionid = '".$recordId."'"));
				$status = $sqlticket['cf_4807'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'PurchaseReq')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_purchasereq.*, arocrm_purchasereqcf.* FROM arocrm_purchasereq
				INNER JOIN arocrm_purchasereqcf ON arocrm_purchasereqcf.purchasereqid = arocrm_purchasereq.purchasereqid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_purchasereq.purchasereqid WHERE arocrm_crmentity.deleted = 0 AND arocrm_purchasereq.purchasereqid = '".$recordId."'"));
				$status = $sqlticket['cf_4809'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'RFQMaintain')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_rfqmaintain.*, arocrm_rfqmaintaincf.* FROM arocrm_rfqmaintain
				INNER JOIN arocrm_rfqmaintaincf ON arocrm_rfqmaintaincf.rfqmaintainid = arocrm_rfqmaintain.rfqmaintainid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_rfqmaintain.rfqmaintainid WHERE arocrm_crmentity.deleted = 0 AND arocrm_rfqmaintain.rfqmaintainid = '".$recordId."'"));
				$status = $sqlticket['cf_4811'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'PurchaseOrder')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_purchaseorder.* FROM arocrm_purchaseorder
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid WHERE arocrm_crmentity.deleted = 0 AND arocrm_purchaseorder.purchaseorderid = '".$recordId."'"));
				$status = $sqlticket['postatus'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'InboundDelivery')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_inbounddelivery.*, arocrm_inbounddeliverycf.* FROM arocrm_inbounddelivery
				INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_inbounddelivery.inbounddeliveryid = '".$recordId."'"));
				$status = $sqlticket['cf_3659'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'GoodsReceipt')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_goodsreceipt.*, arocrm_goodsreceiptcf.* FROM arocrm_goodsreceipt
				INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt.goodsreceiptid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_goodsreceipt.goodsreceiptid = '".$recordId."'"));
				$status = $sqlticket['cf_4824'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'Invoice')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_invoice.* FROM arocrm_invoice
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_invoice.invoiceid = '".$recordId."'"));
				$status = $sqlticket['invoicestatus'];
			
				if($status == 'Approved' || $status == 'Paid')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_invoice.* FROM arocrm_invoice
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_invoice.invoiceid = '".$recordId."'"));
				$status = $sqlticket['invoicestatus'];
			
				if($status == 'Paid')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
		}
		else if($moduleName == 'SalesOrder')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_salesorder.* FROM arocrm_salesorder
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_salesorder.salesorderid = '".$recordId."'"));
				$status = $sqlticket['sostatus'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'OutboundDelivery')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_outbounddelivery.*, arocrm_outbounddeliverycf.* FROM arocrm_outbounddelivery
				INNER JOIN arocrm_outbounddeliverycf ON arocrm_outbounddeliverycf.outbounddeliveryid = arocrm_outbounddelivery.outbounddeliveryid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_outbounddelivery.outbounddeliveryid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_outbounddelivery.outbounddeliveryid = '".$recordId."'"));
				$status = $sqlticket['cf_4826'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'GoodsIssue')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_goodsissue.*, arocrm_goodsissuecf.* FROM arocrm_goodsissue
				INNER JOIN arocrm_goodsissuecf ON arocrm_goodsissuecf.goodsissueid = arocrm_goodsissue.goodsissueid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsissue.goodsissueid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_goodsissue.goodsissueid = '".$recordId."'"));
				$status = $sqlticket['cf_4834'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'JourneyPlan')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_fetch_array(mysql_query("SELECT arocrm_journeyplan.*, arocrm_journeyplancf.* FROM arocrm_journeyplan
				INNER JOIN arocrm_journeyplancf ON arocrm_journeyplancf.journeyplanid = arocrm_journeyplan.journeyplanid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_journeyplan.journeyplanid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_journeyplan.journeyplanid = '".$recordId."'"));
				$status = $sqlticket['cf_1955'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'SalesReturn')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_query("SELECT arocrm_salesreturn.*, arocrm_salesreturncf.* FROM arocrm_salesreturn
				INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_salesreturn.salesreturnid = '".$recordId."'");
				$row = mysql_fetch_array($sqlticket);
				$status = $row['cf_4819'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'PurchaseReturnOrder')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_query("SELECT arocrm_purchasereturnorder.*, arocrm_purchasereturnordercf.* FROM arocrm_purchasereturnorder
				INNER JOIN arocrm_purchasereturnordercf ON arocrm_purchasereturnordercf.purchasereturnorderid = arocrm_purchasereturnorder.purchasereturnorderid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_purchasereturnorder.purchasereturnorderid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_purchasereturnorder.purchasereturnorderid = '".$recordId."'");
				$row = mysql_fetch_array($sqlticket);
				$status = $row['cf_4832'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'AssemblyOrder')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_query("SELECT arocrm_assemblyorder.*, arocrm_assemblyordercf.* FROM arocrm_assemblyorder
				INNER JOIN arocrm_assemblyordercf ON arocrm_assemblyordercf.assemblyorderid = arocrm_assemblyorder.assemblyorderid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_assemblyorder.assemblyorderid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_assemblyorder.assemblyorderid = '".$recordId."'");
				$row = mysql_fetch_array($sqlticket);
				$status = $row['cf_4933'];
			
				if($status == 'Approved')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'VendorPayment')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.* FROM arocrm_vendorpayment
				INNER JOIN arocrm_vendorpaymentcf ON arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_vendorpayment.vendorpaymentid = '".$recordId."'");
				$row = mysql_fetch_array($sqlticket);
				$status = $row['cf_4699'];
			
				if($status == 'Approved' || $status == 'Used')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
		else if($moduleName == 'CustomerPayment')
		{
			if($isadmin == 'off')
			{
				$sqlticket = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.* FROM arocrm_customerpayment
				INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid
				INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 
				AND arocrm_customerpayment.customerpaymentid = '".$recordId."'");
				$row = mysql_fetch_array($sqlticket);
				$status = $row['cf_3376'];
			
				if($status == 'Approved' || $status == 'Used')
				{
					$ticketStatus = 0;
				}
				else
				{
					$ticketStatus = 1;
				}
			}
			else
			{
					$ticketStatus = 1;
			}
		}
	$response['ticketstatus'] = $ticketStatus;
	return $response;
}
function checkTotalDiscountforInvoice($branchid, $category, $totalqty, $nettotalprice, $date, $accountid, $discountallow)
{
	$response = array();
	$curdate = date("Y-m-d", strtotime($date));
	$cur = explode('-',$date);
	$curmonth = $cur[1];
	$curyear = $cur[2];
	$accsql = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_crmentity.* FROM arocrm_account 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid 
	INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
	WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$accountid."'");
	$accrow = mysql_fetch_array($accsql);
	$accounttype = $accrow['account_type'];
	$discountapply = $accrow['cf_5205'];
	if($discountapply == 'Yes')
	{
	$sql = mysql_query("SELECT arocrm_discountmaster.*, arocrm_discountmastercf.*, arocrm_discountmaster_product_category_lineitem.*, arocrm_crmentity.*, arocrm_crmentityrel.* FROM arocrm_discountmaster
	INNER JOIN arocrm_discountmastercf ON arocrm_discountmastercf.discountmasterid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_crmentityrel ON arocrm_crmentityrel.crmid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_discountmaster_product_category_lineitem ON arocrm_discountmaster_product_category_lineitem.discountmasterid = arocrm_discountmaster.discountmasterid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_crmentityrel.module = 'DiscountMaster' AND arocrm_crmentityrel.relmodule = 'PlantMaster' AND arocrm_crmentityrel.relcrmid = '".$branchid."' AND arocrm_discountmaster_product_category_lineitem.cf_4180='".$category."'");
	$chknum = mysql_num_rows($sql);
	if($chknum >0)
	{
		while($row=mysql_fetch_array($sql))
		{
			$discountid = $row['discountmasterid'];
			$discountfor = $row['cf_4292'];
			$discounttype = $row['cf_4162'];
			$discountstatus = $row['cf_4784'];
			$frmqty = $row['cf_4184'];
			$toqty = $row['cf_4186'];
			$condition = $row['cf_4539'];
			$amount = $row['cf_4529'];
			$cashtype = $row['cf_4188'];
			if($discountfor == 'Primary')
			{
				$acctype = "Channel Partner"; 
			}
			else if($discountfor == 'Secondary')
			{
				$acctype = "Dealer";
			}
			else if($discountfor == 'Tertiary')
			{
				$acctype = "Mechanic";
			}
			$advpaymentsql = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
								WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Advance Payment'");
								$advpaymentrow = mysql_fetch_array($advpaymentsql);
								$advpaymentpercent = $advpaymentrow['cf_5185'];
							

$paymentsql = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Deposited on Invoice Date by Cheque'");
									$paymentrow = mysql_fetch_array($paymentsql);
									$paymentpercent = $paymentrow['cf_5185'];

$paymentsqlcash = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Deposited on Invoice Date by Cash'");
									$paymentrowcash = mysql_fetch_array($paymentsql);
									$paymentpercentcash = $paymentrow['cf_5185'];
								
$paymentsql7 = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Within 7 Days from Invoice Date'");
									$paymentrow7 = mysql_fetch_array($paymentsql7);
									$paymentpercent7 = $paymentrow7['cf_5185'];
									
$paymentsql15 = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Within 15 Days from Invoice Date'");
									$paymentrow15 = mysql_fetch_array($paymentsql15);
									$paymentpercent15 = $paymentrow15['cf_5185'];
									
$paymentsql30 = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Within 30 Days from Invoice Date'");
									$paymentrow30 = mysql_fetch_array($paymentsql30);
									$paymentpercent30 = $paymentrow30['cf_5185'];
									
			$isql =  mysql_query("SELECT arocrm_invoice.* FROM arocrm_invoice
							INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid
							WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.accountid = '".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoice.advancepaymentid!='0' AND arocrm_invoice.total = 0 ORDER BY arocrm_invoice.invoiceid DESC LIMIT 1");
			$irow = mysql_num_rows($isql);
			if($irow>0)
			{
				$invrows = mysql_fetch_array($isql);
				$invitemtotal = $invrows['subtotal'];  
				$advpaypercentamount = ($invitemtotal*$advpaymentpercent)/100;
				$advpaypercentamount = number_format((float)$advpaypercentamount, 2, '.', '');
			}
			$invnsql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Paid' AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Paid' AND arocrm_invoicecf.cf_3288 = 'Direct Sales')");
							$inettotalsame = 0;
							$inettotalsamecash = 0;
							$inettotal7 = 0;
							$inettotal15 = 0;
							$inettotal30 = 0;
							while($invnrow = mysql_fetch_array($invnsql))
							{
								$invid = $invnrow['invoiceid'];
								$invdate = $invnrow['cf_4627'];
								
								$payallsql = mysql_query("
SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3360 IN ('Cheque', 'Bank') AND arocrm_customerpayment_payment_details_lineitem.cf_3358 = '0.00' AND arocrm_customerpaymentcf.cf_3335 = 'Sales Invoice Payment' ORDER BY arocrm_customerpayment_payment_details_lineitem.customerpaymentid DESC LIMIT 0,1");
								$payrow = mysql_num_rows($payallsql);
								if($payrow > 0)
								{
									$payallrow = mysql_fetch_array($payallsql);
									$crdate = $payallrow['cf_4967'];
									$paydate = date("Y-m-d", strtotime($crdate));
									$invoicedate = date("Y-m-d", strtotime($invdate));
									if($paydate == $invoicedate)
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotalsame = $inettotalsame + $invnettotal;
									}
								}
								$payallsqlcash = mysql_query("
SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3360 IN ('Cheque', 'Bank') AND arocrm_customerpayment_payment_details_lineitem.cf_3358 = '0.00' AND arocrm_customerpaymentcf.cf_3335 = 'Sales Invoice Payment' ORDER BY arocrm_customerpayment_payment_details_lineitem.customerpaymentid DESC LIMIT 0,1");
								$paycrow = mysql_num_rows($payallsqlcash);
								if($paycrow > 0)
								{
									$payallcashrow = mysql_fetch_array($payallsqlcash);
									$crcashdate = $payallcashrow['cf_4967'];
									$paycashdate = date("Y-m-d", strtotime($crcashdate));
									$invoicedate = date("Y-m-d", strtotime($invdate));
									if($paycashdate == $invoicedate)
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotalsamecash = $inettotalsamecash + $invnettotal;
									}
								}
								$payalsql = mysql_query("
SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3360 IN ('Cash', 'Cheque', 'Bank') AND arocrm_customerpayment_payment_details_lineitem.cf_3358 = '0.00' AND arocrm_customerpaymentcf.cf_3335 = 'Sales Invoice Payment' ORDER BY arocrm_customerpayment_payment_details_lineitem.customerpaymentid DESC LIMIT 0,1");
								$payalrow = mysql_num_rows($payalsql);
								if($payalrow > 0)
								{	
									$payrw = mysql_fetch_array($payalsql);
									$cpdate = $payrw['cf_4967'];
									$paydate = date("Y-m-d", strtotime($cpdate));
									$invoicedate = date("Y-m-d", strtotime($invdate));
									$date1=date_create($invoicedate);
									$date2=date_create($paydate);
									$diff=date_diff($date1,$date2);
									$dif = $diff->format("%a");
									if($dif >= '1' && $dif <= '7')
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotal7 = $inettotal7 + $invnettotal;
									}
									if($dif >= '8' && $dif <= '15')
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotal15 = $inettotal15 + $invnettotal;
									}
									if($dif >= '16' && $dif <= '30')
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotal30 = $inettotal30 + $invnettotal;
									}
								}
							}
							$paypercentamount = ($inettotalsame*$paymentpercent)/100;
							$paypercentamount = number_format((float)$paypercentamount, 2, '.', '');
							$paypercentcashamount = ($inettotalsamecash*$paymentpercentcash)/100;
							$paypercentcashamount = number_format((float)$paypercentcashamount, 2, '.', '');
							$paypercentamount7 = ($inettotal7*$paymentpercent7)/100;
							$paypercentamount7 = number_format((float)$paypercentamount7, 2, '.', '');
							$paypercentamount15 = ($inettotal15*$paymentpercent15)/100;
							$paypercentamount15 = number_format((float)$paypercentamount15, 2, '.', '');
							$paypercentamount30 = ($inettotal30*$paymentpercent30)/100;
							$paypercentamount30 = number_format((float)$paypercentamount30, 2, '.', '');
			if($discounttype == 'Monthly')
			{
				$pdate = '01-'.$curmonth.'-'.$curyear;
				$prevdate = date('Y-m-d',strtotime($pdate));
				
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
						$qty = $totalqty;
						$totalprice = $nettotalprice;
						$disamount = 0;
						$discashpercentval = 0;
						$distargetpercentval = 0;
						$disretailerpercentval = 0;
						if($chkrow>0)
						{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$totalprice = $totalprice + $sorow['subtotal'];
								$disamount = $disamount + $sorow['totaloverallmonthlycashamount'];
								$discashpercentval = $discashpercentval + $sorow['overallmonthlycashpercentval'];
								$distargetpercentval = $distargetpercentval + $sorow['overallmonthlytargetpercentval'];
								$disretailerpercentval = $disretailerpercentval + $sorow['overallmonthlyretailerpercentval'];
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
						}
							if($toqty == '0')
							{
								$cond1 = ($qty >= $frmqty);
							}
							else
							{
								$cond1 = ($qty >= $frmqty && $qty <= $toqty);
							} 
							if($cond1 == '')
							{
								$cond1 = '0';
							}
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$monthlydiscountstatus = $row['cf_4784'];
									$monthlycashamount = $row['cf_4190'];
									$monthlycashdiscount = $row['cf_4296'];
									$monthlytargetdiscount = $row['cf_4192'];
									$monthlyretailerdiscount = $row['cf_4194'];
									$monthlycashpercentamount = (($totalprice*$monthlycashdiscount)/100) - $discashpercentval;
									$monthlycashpercentamount = number_format((float)$monthlycashpercentamount, 2, '.', ''); 
									$monthlytargetpercentamount = (($totalprice*$monthlytargetdiscount)/100) - $distargetpercentval;
									$monthlytargetpercentamount = number_format((float)$monthlytargetpercentamount, 2, '.', ''); 
									$monthlyretailerpercentamount = (($totalprice*$monthlyretailerdiscount)/100) - $disretailerpercentval;
									$monthlyretailerpercentamount = number_format((float)$monthlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$monthlycashdisamount = $monthlycashamount - $disamount;
										$monthlycashamount = 0;
										$monthlytotaldeductamount = $monthlycashdisamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
									else
									{
										$monthlycashdisamount = ($qty * $monthlycashamount) - $disamount;
										$monthlytotaldeductamount = $monthlycashdisamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if($cond1 && $cond2 && $cond3)
								{
									$monthlydiscountstatus = $row['cf_4784'];
									$monthlycashamount = $row['cf_4190'];
									$monthlycashdiscount = $row['cf_4296'];
									$monthlytargetdiscount = $row['cf_4192'];
									$monthlyretailerdiscount = $row['cf_4194'];
									$monthlycashpercentamount = (($totalprice*$monthlycashdiscount)/100) - $discashpercentval;
									$monthlycashpercentamount = number_format((float)$monthlycashpercentamount, 2, '.', ''); 
									$monthlytargetpercentamount = (($totalprice*$monthlytargetdiscount)/100) - $distargetpercentval;
									$monthlytargetpercentamount = number_format((float)$monthlytargetpercentamount, 2, '.', ''); 
									$monthlyretailerpercentamount = (($totalprice*$monthlyretailerdiscount)/100) - $disretailerpercentval;
									$monthlyretailerpercentamount = number_format((float)$monthlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$monthlycashamount = $monthlycashamount - $disamount;
										$monthlytotaldeductamount = $monthlycashamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
									else
									{
										$monthlycashunitamount = $monthlycashamount;
										$monthlycashamount = ($qty * $monthlycashunitamount) - $disamount;
										$monthlytotaldeductamount = $monthlycashamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
								}	
							}
			}
			else if($discounttype == 'Quarterly')
			{
				if($curmonth == '04' || $curmonth == '05' || $curmonth == '06')
				{
					$pdate = '01-04-'.$curyear;
				}
				else if($curmonth == '07' || $curmonth == '08' || $curmonth == '09')
				{
					$pdate = '01-07-'.$curyear;
				}
				else if($curmonth == '10' || $curmonth == '11' || $curmonth == '12')
				{
					$pdate = '01-10-'.$curyear;
				}
				else if($curmonth == '01' || $curmonth == '02' || $curmonth == '03')
				{
					$pdate = '01-01-'.$curyear;
				}
				$prevdate = date('Y-m-d',strtotime($pdate));
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
						
							$qty = $totalqty;
							$totalprice = $nettotalprice;
							$disamount = 0;
							$discashpercentval = 0;
							$distargetpercentval = 0;
							$disretailerpercentval = 0;
							if($chkrow>0)
							{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$discountallowinv = $sorow['cf_5197'];
								$totalprice = $totalprice + $sorow['subtotal'];
								if($discountallowinv == 'Yes')
								{
									$disamount = $disamount + $sorow['totaloverallquarterlycashamount'];
									$discashpercentval = $discashpercentval + $sorow['overallquarterlycashpercentval'];
									$distargetpercentval = $distargetpercentval + $sorow['overallquarterlytargetpercentval'];
									$disretailerpercentval = $disretailerpercentval + $sorow['overallquarterlyretailerpercentval'];
								}
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
							}
							if($toqty == '0')
							{
								$cond1 = ($qty>=$frmqty);
							}
							else
							{
								$cond1 = ($qty>=$frmqty && $qty<=$toqty);
							} 
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$quarterlydiscountstatus = $row['cf_4784'];
									$quarterlycashamount = $row['cf_4190'];
									$quarterlycashdiscount = $row['cf_4296'];
									$quarterlytargetdiscount = $row['cf_4192'];
									$quarterlyretailerdiscount = $row['cf_4194'];
				
									$quarterlycashpercentamount = (($totalprice*$quarterlycashdiscount)/100) - $discashpercentval;
									$quarterlycashpercentamount = number_format((float)$quarterlycashpercentamount, 2, '.', ''); 
									$quarterlytargetpercentamount = (($totalprice*$quarterlytargetdiscount)/100) - $distargetpercentval;
									$quarterlytargetpercentamount = number_format((float)$quarterlytargetpercentamount, 2, '.', ''); 
									$quarterlyretailerpercentamount = (($totalprice*$quarterlyretailerdiscount)/100) - $disretailerpercentval;
									$quarterlyretailerpercentamount = number_format((float)$quarterlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$quarterlycashdisamount = $quarterlycashamount - $disamount;
										$quarterlycashamount = 0;
										$quarterlytotaldeductamount = $quarterlycashdisamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
									else
									{
										$quarterlycashdisamount = ($qty * $quarterlycashamount) - $disamount;
										$quarterlytotaldeductamount = $quarterlycashdisamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if(($cond1 && $cond2) && $cond3)
								{
									$quarterlydiscountstatus = $row['cf_4784'];
									$quarterlycashamount = $row['cf_4190'];
									$quarterlycashdiscount = $row['cf_4296'];
									$quarterlytargetdiscount = $row['cf_4192'];
									$quarterlyretailerdiscount = $row['cf_4194'];
				
									$quarterlycashpercentamount = (($totalprice*$quarterlycashdiscount)/100) - $discashpercentval;
									$quarterlycashpercentamount = number_format((float)$quarterlycashpercentamount, 2, '.', ''); 
									$quarterlytargetpercentamount = (($totalprice*$quarterlytargetdiscount)/100) - $distargetpercentval;
									$quarterlytargetpercentamount = number_format((float)$quarterlytargetpercentamount, 2, '.', ''); 
									$quarterlyretailerpercentamount = (($totalprice*$quarterlyretailerdiscount)/100) - $disretailerpercentval;
									$quarterlyretailerpercentamount = number_format((float)$quarterlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$quarterlycashamount = $quarterlycashamount - $disamount;
										$quarterlytotaldeductamount = $quarterlycashamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
									else
									{
										$quarterlycashunitamount = $quarterlycashamount;
										$quarterlycashamount = ($qty * $quarterlycashunitamount) - $disamount;
										$quarterlytotaldeductamount = $quarterlycashamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
								}	
							}
			}
			else if($discounttype == 'Halfyearly')
			{
				
				if($curmonth == '04' || $curmonth == '05' || $curmonth == '06' || $curmonth == '07' || $curmonth == '08' || $curmonth == '09')
				{
					$pdate = '01-04-'.$curyear;
				}
				else if($curmonth == '10' || $curmonth == '11' || $curmonth == '12' || $curmonth == '01' || $curmonth == '02' || $curmonth == '03')
				{
					if($curmonth == '10' || $curmonth == '11' || $curmonth == '12')
					{
						$pdate = '01-10-'.$curyear;
					}
					else
					{
						$prevyear = $curyear - 1;
						$pdate = '01-10-'.$prevyear;
					}
				}
				$prevdate = date('Y-m-d',strtotime($pdate));
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
							$qty = $totalqty;
							$totalprice = $nettotalprice;
							$disamount = 0;
							$discashpercentval = 0;
							$distargetpercentval = 0;
							$disretailerpercentval = 0;
							if($chkrow>0)
							{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$totalprice = $totalprice + $sorow['subtotal'];
								$disamount = $disamount + $sorow['totaloverallhalfyearlycashamount'];
								$discashpercentval = $discashpercentval + $sorow['overallhalfyearlycashpercentval'];
								$distargetpercentval = $distargetpercentval + $sorow['overallhalfyearlytargetpercentval'];
								$disretailerpercentval = $disretailerpercentval + $sorow['overallhalfyearlyretailerpercentval'];
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
							}
							if($toqty == '0')
							{
								$cond1 = ($qty>=$frmqty);
							}
							if($toqty > '0')
							{
								$cond1 = ($qty>=$frmqty && $qty<=$toqty);
							}  
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$halfyearlydiscountstatus = $row['cf_4784'];
				$halfyearlycashamount = $row['cf_4190'];
				$halfyearlycashdiscount = $row['cf_4296'];
				$halfyearlytargetdiscount = $row['cf_4192'];
				$halfyearlyretailerdiscount = $row['cf_4194'];
									$halfyearlycashpercentamount = (($totalprice*$halfyearlycashdiscount)/100) - $discashpercentval;
									$halfyearlycashpercentamount = number_format((float)$halfyearlycashpercentamount, 2, '.', ''); 
									$halfyearlytargetpercentamount = (($totalprice*$halfyearlytargetdiscount)/100) - $distargetpercentval;
									$halfyearlytargetpercentamount = number_format((float)$halfyearlytargetpercentamount, 2, '.', ''); 
									$halfyearlyretailerpercentamount = (($totalprice*$halfyearlyretailerdiscount)/100) - $disretailerpercentval;
									$halfyearlyretailerpercentamount = number_format((float)$halfyearlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$halfyearlycashdisamount = $halfyearlycashamount - $disamount;
										$halfyearlycashamount = 0;
										$halfyearlytotaldeductamount = $halfyearlycashdisamount + $halfyearlycashpercentamount + $halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
									else
									{
										$halfyearlycashdisamount = ($qty * $halfyearlycashamount) - $disamount;
										$halfyearlytotaldeductamount = $halfyearlycashdisamount + $halfyearlycashpercentamount + $halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if(($cond1 && $cond2) && $cond3)
								{
									$halfyearlydiscountstatus = $row['cf_4784'];
				$halfyearlycashamount = $row['cf_4190'];
				$halfyearlycashdiscount = $row['cf_4296'];
				$halfyearlytargetdiscount = $row['cf_4192'];
				$halfyearlyretailerdiscount = $row['cf_4194'];
									$halfyearlycashpercentamount = (($totalprice*$halfyearlycashdiscount)/100) - $discashpercentval;
									$halfyearlycashpercentamount = number_format((float)$halfyearlycashpercentamount, 2, '.', ''); 
									$halfyearlytargetpercentamount = (($totalprice*$halfyearlytargetdiscount)/100) - $distargetpercentval;
									$halfyearlytargetpercentamount = number_format((float)$halfyearlytargetpercentamount, 2, '.', ''); 
									$halfyearlyretailerpercentamount = (($totalprice*$halfyearlyretailerdiscount)/100) - $disretailerpercentval;
									$halfyearlyretailerpercentamount = number_format((float)$halfyearlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$halfyearlycashamount = $halfyearlycashamount - $disamount;
										$halfyearlytotaldeductamount = $halfyearlycashamount + $halfyearlycashpercentamount + $halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
									else
									{
										$halfyearlycashunitamount = $halfyearlycashamount;
										$halfyearlycashamount = ($qty * $halfyearlycashunitamount) - $disamount;
										$halfyearlytotaldeductamount = $halfyearlycashamount + $halfyearlycashpercentamount +$halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
								}	
							}
			}
			else if($discounttype == 'Annually')
			{
				
				if($curmonth == '01' || $curmonth == '02' || $curmonth == '03')
				{
					$prevyear = $curyear - 1;
					$pdate = '01-04-'.$prevyear;
				}
				else
				{
					$pdate = '01-04-'.$curyear;
				}
				$prevdate = date('Y-m-d',strtotime($pdate));
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Approved' AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
							$qty = $totalqty;
							$totalprice = $nettotalprice;
							$disamount = 0;
							$discashpercentval = 0;
							$distargetpercentval = 0;
							$disretailerpercentval = 0;
							if($chkrow>0)
							{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$discountallowinv = $sorow['cf_5197'];
								$totalprice = $totalprice + $sorow['subtotal'];
								if($discountallowinv == 'Yes')
								{
									$disamount = $disamount + $sorow['totaloverallannuallycashamount'];
									$discashpercentval = $discashpercentval + $sorow['overallannuallycashpercentval'];
									$distargetpercentval = $distargetpercentval + $sorow['overallannuallytargetpercentval'];
									$disretailerpercentval = $disretailerpercentval + $sorow['overallannuallyretailerpercentval'];
								}
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
							}
							if($toqty == '0')
							{
								$cond1 = ($qty>=$frmqty);
							}
							if($toqty > '0')
							{
								$cond1 = ($qty>=$frmqty && $qty<=$toqty);
							}
							if($cond1 == '')
							{
								$cond1 = '0';
							}
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$annuallydiscountstatus = $row['cf_4784'];
				$annuallycashamount = $row['cf_4190'];
				$annuallycashdiscount = $row['cf_4296'];
				$annuallytargetdiscount = $row['cf_4192'];
				$annuallyretailerdiscount = $row['cf_4194'];
									$annuallycashpercentamount = (($totalprice*$annuallycashdiscount)/100) - $discashpercentval;
									$annuallycashpercentamount = number_format((float)$annuallycashpercentamount, 2, '.', ''); 
									$annuallytargetpercentamount = (($totalprice*$annuallytargetdiscount)/100) - $distargetpercentval;
									$annuallytargetpercentamount = number_format((float)$annuallytargetpercentamount, 2, '.', ''); 
									$annuallyretailerpercentamount = (($totalprice*$annuallyretailerdiscount)/100) - $disretailerpercentval;
									$annuallyretailerpercentamount = number_format((float)$annuallyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$annuallycashdisamount = $annuallycashamount - $disamount;
										$annuallycashamount = 0;
										$annuallytotaldeductamount = $annuallycashdisamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
									else
									{
										$annuallycashdisamount = ($qty * $annuallycashamount) - $disamount;
										$annuallytotaldeductamount = $annuallycashdisamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if(($cond1 && $cond2) && $cond3)
								{
									$annuallydiscountstatus = $row['cf_4784'];
				$annuallycashamount = $row['cf_4190'];
				$annuallycashdiscount = $row['cf_4296'];
				$annuallytargetdiscount = $row['cf_4192'];
				$annuallyretailerdiscount = $row['cf_4194'];
									$annuallycashpercentamount = (($totalprice*$annuallycashdiscount)/100) - $discashpercentval;
									$annuallycashpercentamount = number_format((float)$annuallycashpercentamount, 2, '.', ''); 
									$annuallytargetpercentamount = (($totalprice*$annuallytargetdiscount)/100) - $distargetpercentval;
									$annuallytargetpercentamount = number_format((float)$annuallytargetpercentamount, 2, '.', ''); 
									$annuallyretailerpercentamount = (($totalprice*$annuallyretailerdiscount)/100) - $disretailerpercentval;
									$annuallyretailerpercentamount = number_format((float)$annuallyretailerpercentamount, 2, '.', '');
									if($cashtype == 'Overall Discount')
									{
										$annuallycashamount = $annuallycashamount - $disamount;
										$annuallytotaldeductamount = $annuallycashamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
									else
									{
										$annuallycashunitamount = $annuallycashamount;
										$annuallycashamount = ($qty * $annuallycashunitamount) - $disamount;
										$annuallytotaldeductamount = $annuallycashamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
								}	
							}
			}	
		}
		if($discountallow == 'Yes')
		{
			$totaldeductamount = $monthlytotaldeductamount + $quarterlytotaldeductamount + $halfyearlytotaldeductamount + $annuallytotaldeductamount + $advpaypercentamount + $paypercentamount + $paypercentamount7 + $paypercentamount15 + $paypercentamount30;
		}
		else
		{
			$totaldeductamount = $monthlytotaldeductamount + $halfyearlytotaldeductamount + $advpaypercentamount + $paypercentamount + $paypercentamount7 + $paypercentamount15 + $paypercentamount30;
		}
		if($nettotalprice<$totaldeductamount)
		{
			$totaldeductamount = 0;
		}			
		$totalamount = $nettotalprice - $totaldeductamount;
	}
	}
	else
	{
		$totalamount = $nettotalprice - $totaldeductamount;
		$totaldeductamount = 0;
		$advpaymentpercent = 0;
		$advpaypercentamount = 0;
		$paymentpercent = 0;
		$paypercentamount = 0;
		$paymentpercentcash = 0;
		$paypercentcashamount = 0;
		$paymentpercent7 = 0;
		$paypercentamount7 = 0;
		$paymentpercent15 = 0;
		$paypercentamount15 = 0;
		$paymentpercent30 = 0;
		$paypercentamount30 = 0;
		$annuallycashunitamount = 0;
		$annuallycashamount = 0;
		$annuallycashdiscount = 0;
		$annuallycashpercentamount = 0;
		$annuallytargetdiscount = 0;
		$annuallytargetpercentamount = 0;
		$annuallyretailerdiscount = 0;
		$annuallyretailerpercentamount = 0;
	$annuallydiscountstatus = 0;
	$halfyearlycashunitamount = 0;
	$halfyearlycashamount = 0;
	$halfyearlycashdiscount = 0;
	$halfyearlycashpercentamount = 0;
	$halfyearlytargetdiscount = 0;
	$halfyearlytargetpercentamount = 0;
	$halfyearlyretailerdiscount = 0;
	$halfyearlyretailerpercentamount = 0;
	$halfyearlydiscountstatus = 0;
	$quarterlycashunitamount = 0;
	$quarterlycashamount = 0;
	$quarterlycashdiscount = 0;
	$quarterlycashpercentamount = 0;
	$quarterlytargetdiscount = 0;
	$quarterlytargetpercentamount = 0;
	$quarterlyretailerdiscount = 0;
	$quarterlyretailerpercentamount = 0;
	$quarterlydiscountstatus = 0;
	$monthlycashunitamount = 0;
	$monthlycashamount = 0;
	$monthlycashdiscount = 0;
	$monthlycashpercentamount = 0;
	$monthlytargetdiscount = 0;
	$monthlytargetpercentamount = 0;
	$monthlyretailerdiscount = 0;
	$monthlyretailerpercentamount = 0;
	$monthlydiscountstatus = 0;
	}
	$response['discountapply'] = $discountapply;
	$response['totalamount'] = $totalamount;
	$response['totaldeductamount'] = $totaldeductamount;
	$response['advpercent'] = $advpaymentpercent;
	$response['advpercentamount'] = $advpaypercentamount;
	$response['paypercent'] = $paymentpercent;
	$response['paypercentamount'] = $paypercentamount;
	$response['paypercentcash'] = $paymentpercentcash;
	$response['paypercentcashamount'] = $paypercentcashamount;
	$response['pay7percent'] = $paymentpercent7;
	$response['pay7percentamount'] = $paypercentamount7;
	$response['pay15percent'] = $paymentpercent15;
	$response['pay15percentamount'] = $paypercentamount15;
	$response['pay30percent'] = $paymentpercent30;
	$response['pay30percentamount'] = $paypercentamount30;
	$response['annualunitamount'] = $annuallycashunitamount;
	$response['annnualtotaldeduct'] = $annuallycashamount;
	$response['annualcashpercent'] = $annuallycashdiscount;
	$response['annualcashpercentval'] = $annuallycashpercentamount;
	$response['annualtargetpercent'] = $annuallytargetdiscount;
	$response['annualtargetpercentval'] = $annuallytargetpercentamount;
	$response['annualretailerpercent'] = $annuallyretailerdiscount;
	$response['annualretailerpercentval'] = $annuallyretailerpercentamount;
	$response['annuallydiscountstatus'] = $annuallydiscountstatus;
	$response['halfyearunitamount'] = $halfyearlycashunitamount;
	$response['halfyeartotaldeduct'] = $halfyearlycashamount;
	$response['halfyearcashpercent'] = $halfyearlycashdiscount;
	$response['halfyearcashpercentval'] = $halfyearlycashpercentamount;
	$response['halfyeartargetpercent'] = $halfyearlytargetdiscount;
	$response['halfyeartargetpercentval'] = $halfyearlytargetpercentamount;
	$response['halfyearretailerpercent'] = $halfyearlyretailerdiscount;
	$response['halfyearretailerpercentval'] = $halfyearlyretailerpercentamount;
	$response['halfyearlydiscountstatus'] = $halfyearlydiscountstatus;
	$response['quarterunitamount'] = $quarterlycashunitamount;
	$response['quartertotaldeduct'] = $quarterlycashamount;
	$response['quartercashpercent'] = $quarterlycashdiscount;
	$response['quartercashpercentval'] = $quarterlycashpercentamount;
	$response['quartertargetpercent'] = $quarterlytargetdiscount;
	$response['quartertargetpercentval'] = $quarterlytargetpercentamount;
	$response['quarterretailerpercent'] = $quarterlyretailerdiscount;
	$response['quarterretailerpercentval'] = $quarterlyretailerpercentamount;
	$response['quarterlydiscountstatus'] = $quarterlydiscountstatus;
	$response['monthunitamount'] = $monthlycashunitamount;
	$response['monthtotaldeduct'] = $monthlycashamount;
	$response['monthcashpercent'] = $monthlycashdiscount;
	$response['monthcashpercentval'] = $monthlycashpercentamount;
	$response['monthtargetpercent'] = $monthlytargetdiscount;
	$response['monthtargetpercentval'] = $monthlytargetpercentamount;
	$response['monthretailerpercent'] = $monthlyretailerdiscount;
	$response['monthretailerpercentval'] = $monthlyretailerpercentamount;
	$response['monthlydiscountstatus'] = $monthlydiscountstatus;
	return $response;
}
function checkTotalDiscount($branchid, $category, $totalqty, $nettotalprice, $date, $accountid, $discountallow, $advdiscount)
{
	$response = array();
	$curdate = date("Y-m-d", strtotime($date));
	$cur = explode('-',$date);
	$curmonth = $cur[1];
	$curyear = $cur[2];
	$accsql = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_crmentity.* FROM arocrm_account 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid 
	INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
	WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$accountid."'");
	$accrow = mysql_fetch_array($accsql);
	$accounttype = $accrow['account_type'];
	$discountapply = $accrow['cf_5205'];
	if($discountapply == 'Yes')
	{
	$sql = mysql_query("SELECT arocrm_discountmaster.*, arocrm_discountmastercf.*, arocrm_discountmaster_product_category_lineitem.*, arocrm_crmentity.*, arocrm_crmentityrel.* FROM arocrm_discountmaster
	INNER JOIN arocrm_discountmastercf ON arocrm_discountmastercf.discountmasterid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_crmentityrel ON arocrm_crmentityrel.crmid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_discountmaster_product_category_lineitem ON arocrm_discountmaster_product_category_lineitem.discountmasterid = arocrm_discountmaster.discountmasterid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_crmentityrel.module = 'DiscountMaster' AND arocrm_crmentityrel.relmodule = 'PlantMaster' AND arocrm_crmentityrel.relcrmid = '".$branchid."' AND arocrm_discountmaster_product_category_lineitem.cf_4180='".$category."'");
	$chknum = mysql_num_rows($sql);
	if($chknum >0)
	{
		while($row=mysql_fetch_array($sql))
		{
			$discountid = $row['discountmasterid'];
			$discountfor = $row['cf_4292'];
			$discounttype = $row['cf_4162'];
			$discountstatus = $row['cf_4784'];
			$frmqty = $row['cf_4184'];
			$toqty = $row['cf_4186'];
			$condition = $row['cf_4539'];
			$amount = $row['cf_4529'];
			$cashtype = $row['cf_4188'];
			if($discountfor == 'Primary')
			{
				$acctype = "Channel Partner"; 
			}
			else if($discountfor == 'Secondary')
			{
				$acctype = "Dealer";
			}
			else if($discountfor == 'Tertiary')
			{
				$acctype = "Mechanic";
			}
			$advpaymentsql = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
								WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Advance Payment'");
								$advpaymentrow = mysql_fetch_array($advpaymentsql);
								$advpaymentpercent = $advpaymentrow['cf_5185'];
							

$paymentsql = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Deposited on Invoice Date by Cheque'");
									$paymentrow = mysql_fetch_array($paymentsql);
									$paymentpercent = $paymentrow['cf_5185'];

$paymentsqlcash = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Deposited on Invoice Date by Cash'");
									$paymentrowcash = mysql_fetch_array($paymentsqlcash);
									$paymentpercentcash = $paymentrowcash['cf_5185'];
								
$paymentsql7 = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Within 7 Days from Invoice Date'");
									$paymentrow7 = mysql_fetch_array($paymentsql7);
									$paymentpercent7 = $paymentrow7['cf_5185'];
									
$paymentsql15 = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Within 15 Days from Invoice Date'");
									$paymentrow15 = mysql_fetch_array($paymentsql15);
									$paymentpercent15 = $paymentrow15['cf_5185'];
									
$paymentsql30 = mysql_query("SELECT arocrm_discountmaster_payment_scheme_lineitem.* FROM arocrm_discountmaster_payment_scheme_lineitem INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster_payment_scheme_lineitem.discountmasterid
									WHERE arocrm_crmentity.deleted = 0 AND arocrm_discountmaster_payment_scheme_lineitem.cf_5183 = 'Payment Within 30 Days from Invoice Date'");
									$paymentrow30 = mysql_fetch_array($paymentsql30);
									$paymentpercent30 = $paymentrow30['cf_5185'];
									
			if($advdiscount == 'Yes')
			{
				$advpaypercentamount = ($nettotalprice*$advpaymentpercent)/100;
				$advpaypercentamount = number_format((float)$advpaypercentamount, 2, '.', '');
			}
			else
			{
				$advpaypercentamount = '0.00';
			}
			$involdid = array();
			$involdsqlforsame = mysql_query("SELECT DISTINCT arocrm_invoice.samedayinvoiceid, arocrm_invoice.samedaycashinvoiceid, arocrm_invoice.within7daysinvoiceid,arocrm_invoice.within15daysinvoiceid, arocrm_invoice.within30daysinvoiceid FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Paid', 'Approved')");
			$involdrowsno = mysql_num_rows($involdsqlforsame);
			if($involdrowsno > 0)
			{
				while($involdrows = mysql_fetch_array($involdsqlforsame))
				{
					if($involdrows['samedayinvoiceid'] != '' && $involdrows['samedayinvoiceid'] != NULL)
					{
						array_push($involdid,$involdrows['samedayinvoiceid']);
					}
					if($involdrows['samedaycashinvoiceid'] != '' && $involdrows['samedaycashinvoiceid'] != NULL)
					{
						array_push($involdid,$involdrows['samedaycashinvoiceid']);
					}
					if($involdrows['within7daysinvoiceid'] != '' && $involdrows['within7daysinvoiceid'] != NULL)
					{
						array_push($involdid,$involdrows['within7daysinvoiceid']);
					}
					if($involdrows['within15daysinvoiceid'] != '' && $involdrows['within15daysinvoiceid'] != NULL)
					{
						array_push($involdid,$involdrows['within15daysinvoiceid']);
					}
					if($involdrows['within30daysinvoiceid'] != '' && $involdrows['within30daysinvoiceid'] != NULL)
					{
						array_push($involdid,$involdrows['within30daysinvoiceid']);
					}
				}
			}
			$oldinvoiceid = implode($involdid,',');
			$invnsql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Paid' AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoice.invoiceid NOT IN(".$oldinvoiceid.")) UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus ='Paid' AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoice.invoiceid NOT IN(".$oldinvoiceid."))");
							$inettotalsame = 0;
							$inettotalsamecash = 0;
							$inettotal7 = 0;
							$inettotal15 = 0;
							$inettotal30 = 0;
							$invsame = array();
							$invsamecash = array();
							$inv7 = array();
							$inv15 = array();
							$inv30 = array();
							while($invnrow = mysql_fetch_array($invnsql))
							{
								$invid = $invnrow['invoiceid'];
								$invdate = $invnrow['invoicedate'];
								
								$payallsql = mysql_query("
SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3360 IN ('Cheque', 'Bank') AND arocrm_customerpayment_payment_details_lineitem.cf_3358 = '0.00' AND arocrm_customerpaymentcf.cf_3335 = 'Sales Invoice Payment' ORDER BY arocrm_customerpayment_payment_details_lineitem.customerpaymentid DESC LIMIT 0,1");
								$payrow = mysql_num_rows($payallsql);
								if($payrow > 0)
								{
									$payallrow = mysql_fetch_array($payallsql);
									$crdate = $payallrow['cf_4967'];
									$paydate = date("Y-m-d", strtotime($crdate));
									$invoicedate = date("Y-m-d", strtotime($invdate));
									if($paydate == $invoicedate)
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotalsame = $inettotalsame + $invnettotal;
										array_push($invsame,$invnrow['invoiceid']);
									}
								}
								$payallsqlcash = mysql_query("
SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3360 IN ('Cash') AND arocrm_customerpayment_payment_details_lineitem.cf_3358 = '0.00' AND arocrm_customerpaymentcf.cf_3335 = 'Sales Invoice Payment' ORDER BY arocrm_customerpayment_payment_details_lineitem.customerpaymentid DESC LIMIT 0,1");
								$paycrow = mysql_num_rows($payallsqlcash);
								if($paycrow > 0)
								{
									$payallcashrow = mysql_fetch_array($payallsqlcash);
									$crcashdate = $payallcashrow['cf_4967'];
									$paycashdate = date("Y-m-d", strtotime($crcashdate));
									$invoicedate = date("Y-m-d", strtotime($invdate));
									if($paycashdate == $invoicedate)
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotalsamecash = $inettotalsamecash + $invnettotal;
										array_push($invsamecash,$invnrow['invoiceid']);
									}
								}
								$payalsql = mysql_query("
SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_customerpayment_payment_details_lineitem.* FROM arocrm_customerpayment INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid WHERE arocrm_crmentity.deleted = 0 AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invid."' AND arocrm_customerpayment_payment_details_lineitem.cf_3360 IN ('Cash', 'Cheque', 'Bank') AND arocrm_customerpayment_payment_details_lineitem.cf_3358 = '0.00' AND arocrm_customerpaymentcf.cf_3335 = 'Sales Invoice Payment' ORDER BY arocrm_customerpayment_payment_details_lineitem.customerpaymentid DESC LIMIT 0,1");
								$payalrow = mysql_num_rows($payalsql);
								if($payalrow > 0)
								{	
									$payrw = mysql_fetch_array($payalsql);
									$cpdate = $payrw['cf_4967'];
									$paydate = date("Y-m-d", strtotime($cpdate));
									$invoicedate = date("Y-m-d", strtotime($invdate));
									$date1=date_create($invoicedate);
									$date2=date_create($paydate);
									$diff=date_diff($date1,$date2);
									$dif = $diff->format("%a");
									if($dif >= '1' && $dif <= '7')
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotal7 = $inettotal7 + $invnettotal;
										array_push($inv7,$invnrow['invoiceid']);
									}
									if($dif >= '8' && $dif <= '15')
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotal15 = $inettotal15 + $invnettotal;
										array_push($inv15,$invnrow['invoiceid']);
									}
									if($dif >= '16' && $dif <= '30')
									{
										$invnettotal = $invnrow['subtotal'];
										$inettotal30 = $inettotal30 + $invnettotal;
										array_push($inv30,$invnrow['invoiceid']);
									}
								}
							}
							$involdsame = implode($invsame,',');
							$involdsamecash = implode($invsamecash,',');
							$invold7 = implode($inv7,',');
							$invold15 = implode($inv15,',');
							$invold30 = implode($inv30,',');
							$paypercentamount = ($inettotalsame*$paymentpercent)/100;
							$paypercentamount = number_format((float)$paypercentamount, 2, '.', '');
							$paypercentcashamount = ($inettotalsamecash*$paymentpercentcash)/100;
							$paypercentcashamount = number_format((float)$paypercentcashamount, 2, '.', '');
							$paypercentamount7 = ($inettotal7*$paymentpercent7)/100;
							$paypercentamount7 = number_format((float)$paypercentamount7, 2, '.', '');
							$paypercentamount15 = ($inettotal15*$paymentpercent15)/100;
							$paypercentamount15 = number_format((float)$paypercentamount15, 2, '.', '');
							$paypercentamount30 = ($inettotal30*$paymentpercent30)/100;
							$paypercentamount30 = number_format((float)$paypercentamount30, 2, '.', '');
			if($discounttype == 'Monthly')
			{
				$pdate = '01-'.$curmonth.'-'.$curyear;
				$prevdate = date('Y-m-d',strtotime($pdate));
				
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
						$qty = $totalqty;
						$totalprice = $nettotalprice;
						$disamount = 0;
						$discashpercentval = 0;
						$distargetpercentval = 0;
						$disretailerpercentval = 0;
						if($chkrow>0)
						{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$totalprice = $totalprice + $sorow['subtotal'];
								$disamount = $disamount + $sorow['totaloverallmonthlycashamount'];
								$discashpercentval = $discashpercentval + $sorow['overallmonthlycashpercentval'];
								$distargetpercentval = $distargetpercentval + $sorow['overallmonthlytargetpercentval'];
								$disretailerpercentval = $disretailerpercentval + $sorow['overallmonthlyretailerpercentval'];
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
						}
							if($toqty == '0')
							{
								$cond1 = ($qty >= $frmqty);
							}
							else
							{
								$cond1 = ($qty >= $frmqty && $qty <= $toqty);
							} 
							if($cond1 == '')
							{
								$cond1 = '0';
							}
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$monthlydiscountstatus = $row['cf_4784'];
									$monthlycashamount = $row['cf_4190'];
									$monthlycashdiscount = $row['cf_4296'];
									$monthlytargetdiscount = $row['cf_4192'];
									$monthlyretailerdiscount = $row['cf_4194'];
									$monthlycashpercentamount = (($totalprice*$monthlycashdiscount)/100) - $discashpercentval;
									$monthlycashpercentamount = number_format((float)$monthlycashpercentamount, 2, '.', ''); 
									$monthlytargetpercentamount = (($totalprice*$monthlytargetdiscount)/100) - $distargetpercentval;
									$monthlytargetpercentamount = number_format((float)$monthlytargetpercentamount, 2, '.', ''); 
									$monthlyretailerpercentamount = (($totalprice*$monthlyretailerdiscount)/100) - $disretailerpercentval;
									$monthlyretailerpercentamount = number_format((float)$monthlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$monthlycashdisamount = $monthlycashamount - $disamount;
										$monthlycashamount = 0;
										$monthlytotaldeductamount = $monthlycashdisamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
									else
									{
										$monthlycashdisamount = ($qty * $monthlycashamount) - $disamount;
										$monthlytotaldeductamount = $monthlycashdisamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if($cond1 && $cond2 && $cond3)
								{
									$monthlydiscountstatus = $row['cf_4784'];
									$monthlycashamount = $row['cf_4190'];
									$monthlycashdiscount = $row['cf_4296'];
									$monthlytargetdiscount = $row['cf_4192'];
									$monthlyretailerdiscount = $row['cf_4194'];
									$monthlycashpercentamount = (($totalprice*$monthlycashdiscount)/100) - $discashpercentval;
									$monthlycashpercentamount = number_format((float)$monthlycashpercentamount, 2, '.', ''); 
									$monthlytargetpercentamount = (($totalprice*$monthlytargetdiscount)/100) - $distargetpercentval;
									$monthlytargetpercentamount = number_format((float)$monthlytargetpercentamount, 2, '.', ''); 
									$monthlyretailerpercentamount = (($totalprice*$monthlyretailerdiscount)/100) - $disretailerpercentval;
									$monthlyretailerpercentamount = number_format((float)$monthlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$monthlycashamount = $monthlycashamount - $disamount;
										$monthlytotaldeductamount = $monthlycashamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
									else
									{
										$monthlycashunitamount = $monthlycashamount;
										$monthlycashamount = ($qty * $monthlycashunitamount) - $disamount;
										$monthlytotaldeductamount = $monthlycashamount + $monthlycashpercentamount + $monthlytargetpercentamount + $monthlyretailerpercentamount;
									}
								}	
							}
			}
			else if($discounttype == 'Quarterly')
			{
				if($curmonth == '04' || $curmonth == '05' || $curmonth == '06')
				{
					$pdate = '01-04-'.$curyear;
				}
				else if($curmonth == '07' || $curmonth == '08' || $curmonth == '09')
				{
					$pdate = '01-07-'.$curyear;
				}
				else if($curmonth == '10' || $curmonth == '11' || $curmonth == '12')
				{
					$pdate = '01-10-'.$curyear;
				}
				else if($curmonth == '01' || $curmonth == '02' || $curmonth == '03')
				{
					$pdate = '01-01-'.$curyear;
				}
				$prevdate = date('Y-m-d',strtotime($pdate));
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
						
							$qty = $totalqty;
							$totalprice = $nettotalprice;
							$disamount = 0;
							$discashpercentval = 0;
							$distargetpercentval = 0;
							$disretailerpercentval = 0;
							if($chkrow>0)
							{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$discountallowinv = $sorow['cf_5197'];
								$totalprice = $totalprice + $sorow['subtotal'];
								if($discountallowinv == 'Yes')
								{
									$disamount = $disamount + $sorow['totaloverallquarterlycashamount'];
									$discashpercentval = $discashpercentval + $sorow['overallquarterlycashpercentval'];
									$distargetpercentval = $distargetpercentval + $sorow['overallquarterlytargetpercentval'];
									$disretailerpercentval = $disretailerpercentval + $sorow['overallquarterlyretailerpercentval'];
								}
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
							}
							if($toqty == '0')
							{
								$cond1 = ($qty>=$frmqty);
							}
							else
							{
								$cond1 = ($qty>=$frmqty && $qty<=$toqty);
							} 
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$quarterlydiscountstatus = $row['cf_4784'];
									$quarterlycashamount = $row['cf_4190'];
									$quarterlycashdiscount = $row['cf_4296'];
									$quarterlytargetdiscount = $row['cf_4192'];
									$quarterlyretailerdiscount = $row['cf_4194'];
				
									$quarterlycashpercentamount = (($totalprice*$quarterlycashdiscount)/100) - $discashpercentval;
									$quarterlycashpercentamount = number_format((float)$quarterlycashpercentamount, 2, '.', ''); 
									$quarterlytargetpercentamount = (($totalprice*$quarterlytargetdiscount)/100) - $distargetpercentval;
									$quarterlytargetpercentamount = number_format((float)$quarterlytargetpercentamount, 2, '.', ''); 
									$quarterlyretailerpercentamount = (($totalprice*$quarterlyretailerdiscount)/100) - $disretailerpercentval;
									$quarterlyretailerpercentamount = number_format((float)$quarterlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$quarterlycashdisamount = $quarterlycashamount - $disamount;
										$quarterlycashamount = 0;
										$quarterlytotaldeductamount = $quarterlycashdisamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
									else
									{
										$quarterlycashdisamount = ($qty * $quarterlycashamount) - $disamount;
										$quarterlytotaldeductamount = $quarterlycashdisamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if(($cond1 && $cond2) && $cond3)
								{
									$quarterlydiscountstatus = $row['cf_4784'];
									$quarterlycashamount = $row['cf_4190'];
									$quarterlycashdiscount = $row['cf_4296'];
									$quarterlytargetdiscount = $row['cf_4192'];
									$quarterlyretailerdiscount = $row['cf_4194'];
				
									$quarterlycashpercentamount = (($totalprice*$quarterlycashdiscount)/100) - $discashpercentval;
									$quarterlycashpercentamount = number_format((float)$quarterlycashpercentamount, 2, '.', ''); 
									$quarterlytargetpercentamount = (($totalprice*$quarterlytargetdiscount)/100) - $distargetpercentval;
									$quarterlytargetpercentamount = number_format((float)$quarterlytargetpercentamount, 2, '.', ''); 
									$quarterlyretailerpercentamount = (($totalprice*$quarterlyretailerdiscount)/100) - $disretailerpercentval;
									$quarterlyretailerpercentamount = number_format((float)$quarterlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$quarterlycashamount = $quarterlycashamount - $disamount;
										$quarterlytotaldeductamount = $quarterlycashamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
									else
									{
										$quarterlycashunitamount = $quarterlycashamount;
										$quarterlycashamount = ($qty * $quarterlycashunitamount) - $disamount;
										$quarterlytotaldeductamount = $quarterlycashamount + $quarterlycashpercentamount + $quarterlytargetpercentamount + $quarterlyretailerpercentamount;
									}
								}	
							}
			}
			else if($discounttype == 'Halfyearly')
			{
				
				if($curmonth == '04' || $curmonth == '05' || $curmonth == '06' || $curmonth == '07' || $curmonth == '08' || $curmonth == '09')
				{
					$pdate = '01-04-'.$curyear;
				}
				else if($curmonth == '10' || $curmonth == '11' || $curmonth == '12' || $curmonth == '01' || $curmonth == '02' || $curmonth == '03')
				{
					if($curmonth == '10' || $curmonth == '11' || $curmonth == '12')
					{
						$pdate = '01-10-'.$curyear;
					}
					else
					{
						$prevyear = $curyear - 1;
						$pdate = '01-10-'.$prevyear;
					}
				}
				$prevdate = date('Y-m-d',strtotime($pdate));
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
							$qty = $totalqty;
							$totalprice = $nettotalprice;
							$disamount = 0;
							$discashpercentval = 0;
							$distargetpercentval = 0;
							$disretailerpercentval = 0;
							if($chkrow>0)
							{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$totalprice = $totalprice + $sorow['subtotal'];
								$disamount = $disamount + $sorow['totaloverallhalfyearlycashamount'];
								$discashpercentval = $discashpercentval + $sorow['overallhalfyearlycashpercentval'];
								$distargetpercentval = $distargetpercentval + $sorow['overallhalfyearlytargetpercentval'];
								$disretailerpercentval = $disretailerpercentval + $sorow['overallhalfyearlyretailerpercentval'];
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
							}
							if($toqty == '0')
							{
								$cond1 = ($qty>=$frmqty);
							}
							if($toqty > '0')
							{
								$cond1 = ($qty>=$frmqty && $qty<=$toqty);
							}  
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$halfyearlydiscountstatus = $row['cf_4784'];
				$halfyearlycashamount = $row['cf_4190'];
				$halfyearlycashdiscount = $row['cf_4296'];
				$halfyearlytargetdiscount = $row['cf_4192'];
				$halfyearlyretailerdiscount = $row['cf_4194'];
									$halfyearlycashpercentamount = (($totalprice*$halfyearlycashdiscount)/100) - $discashpercentval;
									$halfyearlycashpercentamount = number_format((float)$halfyearlycashpercentamount, 2, '.', ''); 
									$halfyearlytargetpercentamount = (($totalprice*$halfyearlytargetdiscount)/100) - $distargetpercentval;
									$halfyearlytargetpercentamount = number_format((float)$halfyearlytargetpercentamount, 2, '.', ''); 
									$halfyearlyretailerpercentamount = (($totalprice*$halfyearlyretailerdiscount)/100) - $disretailerpercentval;
									$halfyearlyretailerpercentamount = number_format((float)$halfyearlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$halfyearlycashdisamount = $halfyearlycashamount - $disamount;
										$halfyearlycashamount = 0;
										$halfyearlytotaldeductamount = $halfyearlycashdisamount + $halfyearlycashpercentamount + $halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
									else
									{
										$halfyearlycashdisamount = ($qty * $halfyearlycashamount) - $disamount;
										$halfyearlytotaldeductamount = $halfyearlycashdisamount + $halfyearlycashpercentamount + $halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if(($cond1 && $cond2) && $cond3)
								{
									$halfyearlydiscountstatus = $row['cf_4784'];
				$halfyearlycashamount = $row['cf_4190'];
				$halfyearlycashdiscount = $row['cf_4296'];
				$halfyearlytargetdiscount = $row['cf_4192'];
				$halfyearlyretailerdiscount = $row['cf_4194'];
									$halfyearlycashpercentamount = (($totalprice*$halfyearlycashdiscount)/100) - $discashpercentval;
									$halfyearlycashpercentamount = number_format((float)$halfyearlycashpercentamount, 2, '.', ''); 
									$halfyearlytargetpercentamount = (($totalprice*$halfyearlytargetdiscount)/100) - $distargetpercentval;
									$halfyearlytargetpercentamount = number_format((float)$halfyearlytargetpercentamount, 2, '.', ''); 
									$halfyearlyretailerpercentamount = (($totalprice*$halfyearlyretailerdiscount)/100) - $disretailerpercentval;
									$halfyearlyretailerpercentamount = number_format((float)$halfyearlyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$halfyearlycashamount = $halfyearlycashamount - $disamount;
										$halfyearlytotaldeductamount = $halfyearlycashamount + $halfyearlycashpercentamount + $halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
									else
									{
										$halfyearlycashunitamount = $halfyearlycashamount;
										$halfyearlycashamount = ($qty * $halfyearlycashunitamount) - $disamount;
										$halfyearlytotaldeductamount = $halfyearlycashamount + $halfyearlycashpercentamount +$halfyearlytargetpercentamount + $halfyearlyretailerpercentamount;
									}
								}	
							}
			}
			else if($discounttype == 'Annually')
			{
				
				if($curmonth == '01' || $curmonth == '02' || $curmonth == '03')
				{
					$prevyear = $curyear - 1;
					$pdate = '01-04-'.$prevyear;
				}
				else
				{
					$pdate = '01-04-'.$curyear;
				}
				$prevdate = date('Y-m-d',strtotime($pdate));
				$sosql = mysql_query("(SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."') UNION (SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$accountid."' AND arocrm_invoice.invoicestatus IN ('Approved', 'Paid') AND arocrm_invoicecf.cf_3288 = 'Direct Sales' AND arocrm_invoicecf.cf_4766 = '".$category."' AND arocrm_invoicecf.cf_4627>='".$prevdate."' AND arocrm_invoicecf.cf_4627<='".$curdate."')");
						$chkrow = mysql_num_rows($sosql);
							$qty = $totalqty;
							$totalprice = $nettotalprice;
							$disamount = 0;
							$discashpercentval = 0;
							$distargetpercentval = 0;
							$disretailerpercentval = 0;
							if($chkrow>0)
							{
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['invoiceid'];
								$discountallowinv = $sorow['cf_5197'];
								$totalprice = $totalprice + $sorow['subtotal'];
								if($discountallowinv == 'Yes')
								{
									$disamount = $disamount + $sorow['totaloverallannuallycashamount'];
									$discashpercentval = $discashpercentval + $sorow['overallannuallycashpercentval'];
									$distargetpercentval = $distargetpercentval + $sorow['overallannuallytargetpercentval'];
									$disretailerpercentval = $disretailerpercentval + $sorow['overallannuallyretailerpercentval'];
								}
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."'");
								while($invrow = mysql_fetch_array($invsql))
								{
									$qty = $qty + $invrow['quantity'];
								}
							}
							}
							if($toqty == '0')
							{
								$cond1 = ($qty>=$frmqty);
							}
							if($toqty > '0')
							{
								$cond1 = ($qty>=$frmqty && $qty<=$toqty);
							}
							if($cond1 == '')
							{
								$cond1 = '0';
							}
							$cond2 = ($totalprice>=$amount);
							$cond3 = ($accounttype == $acctype);
							if($condition=='OR')
							{
								if(($cond1 || $cond2) && $cond3)
								{
									$annuallydiscountstatus = $row['cf_4784'];
				$annuallycashamount = $row['cf_4190'];
				$annuallycashdiscount = $row['cf_4296'];
				$annuallytargetdiscount = $row['cf_4192'];
				$annuallyretailerdiscount = $row['cf_4194'];
									$annuallycashpercentamount = (($totalprice*$annuallycashdiscount)/100) - $discashpercentval;
									$annuallycashpercentamount = number_format((float)$annuallycashpercentamount, 2, '.', ''); 
									$annuallytargetpercentamount = (($totalprice*$annuallytargetdiscount)/100) - $distargetpercentval;
									$annuallytargetpercentamount = number_format((float)$annuallytargetpercentamount, 2, '.', ''); 
									$annuallyretailerpercentamount = (($totalprice*$annuallyretailerdiscount)/100) - $disretailerpercentval;
									$annuallyretailerpercentamount = number_format((float)$annuallyretailerpercentamount, 2, '.', ''); 
									if($cashtype == 'Overall Discount')
									{
										$annuallycashdisamount = $annuallycashamount - $disamount;
										$annuallycashamount = 0;
										$annuallytotaldeductamount = $annuallycashdisamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
									else
									{
										$annuallycashdisamount = ($qty * $annuallycashamount) - $disamount;
										$annuallytotaldeductamount = $annuallycashdisamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
								}	
							}
							else if($condition=='AND')
							{
								if(($cond1 && $cond2) && $cond3)
								{
									$annuallydiscountstatus = $row['cf_4784'];
				$annuallycashamount = $row['cf_4190'];
				$annuallycashdiscount = $row['cf_4296'];
				$annuallytargetdiscount = $row['cf_4192'];
				$annuallyretailerdiscount = $row['cf_4194'];
									$annuallycashpercentamount = (($totalprice*$annuallycashdiscount)/100) - $discashpercentval;
									$annuallycashpercentamount = number_format((float)$annuallycashpercentamount, 2, '.', ''); 
									$annuallytargetpercentamount = (($totalprice*$annuallytargetdiscount)/100) - $distargetpercentval;
									$annuallytargetpercentamount = number_format((float)$annuallytargetpercentamount, 2, '.', ''); 
									$annuallyretailerpercentamount = (($totalprice*$annuallyretailerdiscount)/100) - $disretailerpercentval;
									$annuallyretailerpercentamount = number_format((float)$annuallyretailerpercentamount, 2, '.', '');
									if($cashtype == 'Overall Discount')
									{
										$annuallycashamount = $annuallycashamount - $disamount;
										$annuallytotaldeductamount = $annuallycashamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
									else
									{
										$annuallycashunitamount = $annuallycashamount;
										$annuallycashamount = ($qty * $annuallycashunitamount) - $disamount;
										$annuallytotaldeductamount = $annuallycashamount + $annuallycashpercentamount + $annuallytargetpercentamount + $annuallyretailerpercentamount;
									}
								}	
							}
			}	
		}
		if($discountallow == 'Yes')
		{
			$totaldeductamount = $monthlytotaldeductamount + $quarterlytotaldeductamount + $halfyearlytotaldeductamount + $annuallytotaldeductamount + $advpaypercentamount + $paypercentamount + $paypercentcashamount + $paypercentamount7 + $paypercentamount15 + $paypercentamount30;
		}
		else
		{
			$totaldeductamount = $monthlytotaldeductamount + $halfyearlytotaldeductamount + $advpaypercentamount + $paypercentamount + $paypercentcashamount + $paypercentamount7 + $paypercentamount15 + $paypercentamount30;
		}
		if($nettotalprice<$totaldeductamount)
		{
			$totaldeductamount = 0;
		}			
		$totalamount = $nettotalprice - $totaldeductamount;
	}
	}
	else
	{
		$totalamount = $nettotalprice - $totaldeductamount;
		$totaldeductamount = 0;
		$advpaymentpercent = 0;
		$advpaypercentamount = 0;
		$paymentpercent = 0;
		$paypercentamount = 0;
		$paymentpercentcash = 0;
		$paypercentcashamount = 0;
		$paymentpercent7 = 0;
		$paypercentamount7 = 0;
		$paymentpercent15 = 0;
		$paypercentamount15 = 0;
		$paymentpercent30 = 0;
		$paypercentamount30 = 0;
		$annuallycashunitamount = 0;
		$annuallycashamount = 0;
		$annuallycashdiscount = 0;
		$annuallycashpercentamount = 0;
		$annuallytargetdiscount = 0;
		$annuallytargetpercentamount = 0;
		$annuallyretailerdiscount = 0;
		$annuallyretailerpercentamount = 0;
	$annuallydiscountstatus = 0;
	$halfyearlycashunitamount = 0;
	$halfyearlycashamount = 0;
	$halfyearlycashdiscount = 0;
	$halfyearlycashpercentamount = 0;
	$halfyearlytargetdiscount = 0;
	$halfyearlytargetpercentamount = 0;
	$halfyearlyretailerdiscount = 0;
	$halfyearlyretailerpercentamount = 0;
	$halfyearlydiscountstatus = 0;
	$quarterlycashunitamount = 0;
	$quarterlycashamount = 0;
	$quarterlycashdiscount = 0;
	$quarterlycashpercentamount = 0;
	$quarterlytargetdiscount = 0;
	$quarterlytargetpercentamount = 0;
	$quarterlyretailerdiscount = 0;
	$quarterlyretailerpercentamount = 0;
	$quarterlydiscountstatus = 0;
	$monthlycashunitamount = 0;
	$monthlycashamount = 0;
	$monthlycashdiscount = 0;
	$monthlycashpercentamount = 0;
	$monthlytargetdiscount = 0;
	$monthlytargetpercentamount = 0;
	$monthlyretailerdiscount = 0;
	$monthlyretailerpercentamount = 0;
	$monthlydiscountstatus = 0;
	$involdsame = '';
	$involdsamecash = '';
	$invold7 = '';
	$invold15 = '';
	$invold30 = '';
	}
	$response['discountapply'] = $discountapply;
	$response['totalamount'] = $totalamount;
	$response['totaldeductamount'] = $totaldeductamount;
	$response['involdsame'] = $involdsame;
	$response['involdsamecash'] = $involdsamecash;
	$response['invold7'] = $invold7;
	$response['invold15'] = $invold15;
	$response['invold30'] = $invold30;
	$response['advpercent'] = $advpaymentpercent;
	$response['advpercentamount'] = $advpaypercentamount;
	$response['paypercent'] = $paymentpercent;
	$response['paypercentamount'] = $paypercentamount;
	$response['paypercentcash'] = $paymentpercentcash;
	$response['paypercentcashamount'] = $paypercentcashamount;
	$response['pay7percent'] = $paymentpercent7;
	$response['pay7percentamount'] = $paypercentamount7;
	$response['pay15percent'] = $paymentpercent15;
	$response['pay15percentamount'] = $paypercentamount15;
	$response['pay30percent'] = $paymentpercent30;
	$response['pay30percentamount'] = $paypercentamount30;
	$response['annualunitamount'] = $annuallycashunitamount;
	$response['annnualtotaldeduct'] = $annuallycashamount;
	$response['annualcashpercent'] = $annuallycashdiscount;
	$response['annualcashpercentval'] = $annuallycashpercentamount;
	$response['annualtargetpercent'] = $annuallytargetdiscount;
	$response['annualtargetpercentval'] = $annuallytargetpercentamount;
	$response['annualretailerpercent'] = $annuallyretailerdiscount;
	$response['annualretailerpercentval'] = $annuallyretailerpercentamount;
	$response['annuallydiscountstatus'] = $annuallydiscountstatus;
	$response['halfyearunitamount'] = $halfyearlycashunitamount;
	$response['halfyeartotaldeduct'] = $halfyearlycashamount;
	$response['halfyearcashpercent'] = $halfyearlycashdiscount;
	$response['halfyearcashpercentval'] = $halfyearlycashpercentamount;
	$response['halfyeartargetpercent'] = $halfyearlytargetdiscount;
	$response['halfyeartargetpercentval'] = $halfyearlytargetpercentamount;
	$response['halfyearretailerpercent'] = $halfyearlyretailerdiscount;
	$response['halfyearretailerpercentval'] = $halfyearlyretailerpercentamount;
	$response['halfyearlydiscountstatus'] = $halfyearlydiscountstatus;
	$response['quarterunitamount'] = $quarterlycashunitamount;
	$response['quartertotaldeduct'] = $quarterlycashamount;
	$response['quartercashpercent'] = $quarterlycashdiscount;
	$response['quartercashpercentval'] = $quarterlycashpercentamount;
	$response['quartertargetpercent'] = $quarterlytargetdiscount;
	$response['quartertargetpercentval'] = $quarterlytargetpercentamount;
	$response['quarterretailerpercent'] = $quarterlyretailerdiscount;
	$response['quarterretailerpercentval'] = $quarterlyretailerpercentamount;
	$response['quarterlydiscountstatus'] = $quarterlydiscountstatus;
	$response['monthunitamount'] = $monthlycashunitamount;
	$response['monthtotaldeduct'] = $monthlycashamount;
	$response['monthcashpercent'] = $monthlycashdiscount;
	$response['monthcashpercentval'] = $monthlycashpercentamount;
	$response['monthtargetpercent'] = $monthlytargetdiscount;
	$response['monthtargetpercentval'] = $monthlytargetpercentamount;
	$response['monthretailerpercent'] = $monthlyretailerdiscount;
	$response['monthretailerpercentval'] = $monthlyretailerpercentamount;
	$response['monthlydiscountstatus'] = $monthlydiscountstatus;
	return $response;
}
function InvoiceDetails($invoiceid)
{
	$response = array();
	$sosql = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid =  arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoiceid ='".$invoiceid."'");
	$sorow = mysql_fetch_array($sosql);
	$response['type'] = $sorow['cf_3288'];
	$response['soid'] = $sorow['salesorderid'];
	$response['grid'] = $sorow['cf_nrl_goodsreceipt721_id'];
	return $response;
}
function getInvoiceDiscountValues($invid)
{
	$response = array();
	$sosql = mysql_query("SELECT * FROM arocrm_invoice WHERE invoiceid ='".$invid."'");
	$sorow = mysql_fetch_array($sosql);
	$totaldiscount = $sorow['discount_amount'];
	$monthlycashamount = $sorow['overallmonthlycashamount'];
	$monthlycashdisamount = $sorow['totaloverallmonthlycashamount'];
	$monthlycashdiscount = $sorow['overallmonthlycashpercent'];
	$monthlycashpercentamount = $sorow['overallmonthlycashpercentval'];
	$monthlytargetdiscount = $sorow['overallmonthlytargetpercent'];
	$monthlytargetpercentamount = $sorow['overallmonthlytargetpercentval'];
	$monthlyretailerdiscount = $sorow['overallmonthlyretailerpercent'];
	$monthlyretailerpercentamount = $sorow['overallmonthlyretailerpercentval'];
	$quarterlycashamount = $sorow['overallquarterlycashamount'];
	$quarterlycashdisamount = $sorow['totaloverallquarterlycashamount'];
	$quarterlycashdiscount = $sorow['overallquarterlycashpercent'];
	$quarterlycashpercentamount = $sorow['overallquarterlycashpercentval'];
	$quarterlytargetdiscount = $sorow['overallquarterlytargetpercent'];
	$quarterlytargetpercentamount = $sorow['overallquarterlytargetpercentval'];
	$quarterlyretailerdiscount = $sorow['overallquarterlyretailerpercent'];
	$quarterlyretailerpercentamount = $sorow['overallquarterlyretailerpercentval'];
	$halfyearlycashamount = $sorow['overallhalfyearlycashamount'];
	$halfyearlycashdisamount = $sorow['totaloverallhalfyearlycashamount'];
	$halfyearlycashdiscount = $sorow['overallhalfyearlycashpercent'];
	$halfyearlycashpercentamount = $sorow['overallhalfyearlycashpercentval'];
	$halfyearlytargetdiscount = $sorow['overallhalfyearlytargetpercent'];
	$halfyearlytargetpercentamount = $sorow['overallhalfyearlytargetpercentval'];
	$halfyearlyretailerdiscount = $sorow['overallhalfyearlyretailerpercent'];
	$halfyearlyretailerpercentamount = $sorow['overallhalfyearlyretailerpercentval'];
	$annuallycashamount = $sorow['overallannuallycashamount'];
	$annuallycashdisamount = $sorow['totaloverallannuallycashamount'];
	$annuallycashdiscount = $sorow['overallannuallycashpercent'];
	$annuallycashpercentamount = $sorow['overallannuallycashpercentval'];
	$annuallytargetdiscount = $sorow['overallannuallytargetpercent'];
	$annuallytargetpercentamount = $sorow['overallannuallytargetpercentval'];
	$annuallyretailerdiscount = $sorow['overallannuallyretailerpercent'];
	$annuallyretailerpercentamount = $sorow['overallannuallyretailerpercentval'];
	$response['totaldiscount'] = $totaldiscount;
	$response['advpercent'] = $sorow['overalladvancepercent'];
	$response['advpercentamount'] = $sorow['overalladvancepercentval'];
	$response['paypercent'] = $sorow['overallsamedaypercent'];
	$response['paypercentamount'] = $sorow['overallsamedaypercentval'];
	$response['paycashpercent'] = $sorow['overallsamedaycashpercent'];
	$response['paycashpercentamount'] = $sorow['overallsamedaycashpercentval'];
	$response['pay7percent'] = $sorow['overall7dayspercent'];
	$response['pay7percentamount'] = $sorow['overall7dayspercentval'];
	$response['pay15percent'] = $sorow['overall15dayspercent'];
	$response['pay15percentamount'] = $sorow['overall15dayspercentval'];
	$response['pay30percent'] = $sorow['overall30dayspercent'];
	$response['pay30percentamount'] = $sorow['overall30dayspercentval'];
	$response['schemediscount'] = $sorow['schemediscount'];
	$response['annualunitamount'] = $annuallycashamount;
	$response['annnualtotaldeduct'] = $annuallycashdisamount;
	$response['annualcashpercent'] = $annuallycashdiscount;
	$response['annualcashpercentval'] = $annuallycashpercentamount;
	$response['annualtargetpercent'] = $annuallytargetdiscount;
	$response['annualtargetpercentval'] = $annuallytargetpercentamount;
	$response['annualretailerpercent'] = $annuallyretailerdiscount;
	$response['annualretailerpercentval'] = $annuallyretailerpercentamount;
	$response['halfyearunitamount'] = $halfyearlycashamount;
	$response['halfyeartotaldeduct'] = $halfyearlycashdisamount;
	$response['halfyearcashpercent'] = $halfyearlycashdiscount;
	$response['halfyearcashpercentval'] = $halfyearlycashpercentamount;
	$response['halfyeartargetpercent'] = $halfyearlytargetdiscount;
	$response['halfyeartargetpercentval'] = $halfyearlytargetpercentamount;
	$response['halfyearretailerpercent'] = $halfyearlyretailerdiscount;
	$response['halfyearretailerpercentval'] = $halfyearlyretailerpercentamount;
	$response['quarterunitamount'] = $quarterlycashamount;
	$response['quartertotaldeduct'] = $quarterlycashdisamount;
	$response['quartercashpercent'] = $quarterlycashdiscount;
	$response['quartercashpercentval'] = $quarterlycashpercentamount;
	$response['quartertargetpercent'] = $quarterlytargetdiscount;
	$response['quartertargetpercentval'] = $quarterlytargetpercentamount;
	$response['quarterretailerpercent'] = $quarterlyretailerdiscount;
	$response['quarterretailerpercentval'] = $quarterlyretailerpercentamount;
	$response['monthunitamount'] = $monthlycashamount;
	$response['monthtotaldeduct'] = $monthlycashdisamount;
	$response['monthcashpercent'] = $monthlycashdiscount;
	$response['monthcashpercentval'] = $monthlycashpercentamount;
	$response['monthtargetpercent'] = $monthlytargetdiscount;
	$response['monthtargetpercentval'] = $monthlytargetpercentamount;
	$response['monthretailerpercent'] = $monthlyretailerdiscount;
	$response['monthretailerpercentval'] = $monthlyretailerpercentamount;
	return $response;
}
function getDiscountValues($soid)
{
	$response = array();
	$sosql = mysql_query("SELECT * FROM arocrm_salesorder WHERE salesorderid ='".$soid."'");
	$sorow = mysql_fetch_array($sosql);
	$totaldiscount = $sorow['discount_amount'];
	$monthlycashamount = $sorow['overallmonthlycashamount'];
	$monthlycashdisamount = $sorow['totaloverallmonthlycashamount'];
	$monthlycashdiscount = $sorow['overallmonthlycashpercent'];
	$monthlycashpercentamount = $sorow['overallmonthlycashpercentval'];
	$monthlytargetdiscount = $sorow['overallmonthlytargetpercent'];
	$monthlytargetpercentamount = $sorow['overallmonthlytargetpercentval'];
	$monthlyretailerdiscount = $sorow['overallmonthlyretailerpercent'];
	$monthlyretailerpercentamount = $sorow['overallmonthlyretailerpercentval'];
	$quarterlycashamount = $sorow['overallquarterlycashamount'];
	$quarterlycashdisamount = $sorow['totaloverallquarterlycashamount'];
	$quarterlycashdiscount = $sorow['overallquarterlycashpercent'];
	$quarterlycashpercentamount = $sorow['overallquarterlycashpercentval'];
	$quarterlytargetdiscount = $sorow['overallquarterlytargetpercent'];
	$quarterlytargetpercentamount = $sorow['overallquarterlytargetpercentval'];
	$quarterlyretailerdiscount = $sorow['overallquarterlyretailerpercent'];
	$quarterlyretailerpercentamount = $sorow['overallquarterlyretailerpercentval'];
	$halfyearlycashamount = $sorow['overallhalfyearlycashamount'];
	$halfyearlycashdisamount = $sorow['totaloverallhalfyearlycashamount'];
	$halfyearlycashdiscount = $sorow['overallhalfyearlycashpercent'];
	$halfyearlycashpercentamount = $sorow['overallhalfyearlycashpercentval'];
	$halfyearlytargetdiscount = $sorow['overallhalfyearlytargetpercent'];
	$halfyearlytargetpercentamount = $sorow['overallhalfyearlytargetpercentval'];
	$halfyearlyretailerdiscount = $sorow['overallhalfyearlyretailerpercent'];
	$halfyearlyretailerpercentamount = $sorow['overallhalfyearlyretailerpercentval'];
	$annuallycashamount = $sorow['overallannuallycashamount'];
	$annuallycashdisamount = $sorow['totaloverallannuallycashamount'];
	$annuallycashdiscount = $sorow['overallannuallycashpercent'];
	$annuallycashpercentamount = $sorow['overallannuallycashpercentval'];
	$annuallytargetdiscount = $sorow['overallannuallytargetpercent'];
	$annuallytargetpercentamount = $sorow['overallannuallytargetpercentval'];
	$annuallyretailerdiscount = $sorow['overallannuallyretailerpercent'];
	$annuallyretailerpercentamount = $sorow['overallannuallyretailerpercentval'];
	$response['totaldiscount'] = $totaldiscount;
	$response['advpercent'] = $sorow['overalladvancepercent'];
	$response['advpercentamount'] = $sorow['overalladvancepercentval'];
	$response['paypercent'] = $sorow['overallsamedaypercent'];
	$response['paypercentamount'] = $sorow['overallsamedaypercentval'];
	$response['paycashpercent'] = $sorow['overallsamedaycashpercent'];
	$response['paycashpercentamount'] = $sorow['overallsamedaycashpercentval'];
	$response['pay7percent'] = $sorow['overall7dayspercent'];
	$response['pay7percentamount'] = $sorow['overall7dayspercentval'];
	$response['pay15percent'] = $sorow['overall15dayspercent'];
	$response['pay15percentamount'] = $sorow['overall15dayspercentval'];
	$response['pay30percent'] = $sorow['overall30dayspercent'];
	$response['pay30percentamount'] = $sorow['overall30dayspercentval'];
	$response['schemediscount'] = $sorow['schemediscount'];
	$response['annualunitamount'] = $annuallycashamount;
	$response['annnualtotaldeduct'] = $annuallycashdisamount;
	$response['annualcashpercent'] = $annuallycashdiscount;
	$response['annualcashpercentval'] = $annuallycashpercentamount;
	$response['annualtargetpercent'] = $annuallytargetdiscount;
	$response['annualtargetpercentval'] = $annuallytargetpercentamount;
	$response['annualretailerpercent'] = $annuallyretailerdiscount;
	$response['annualretailerpercentval'] = $annuallyretailerpercentamount;
	$response['halfyearunitamount'] = $halfyearlycashamount;
	$response['halfyeartotaldeduct'] = $halfyearlycashdisamount;
	$response['halfyearcashpercent'] = $halfyearlycashdiscount;
	$response['halfyearcashpercentval'] = $halfyearlycashpercentamount;
	$response['halfyeartargetpercent'] = $halfyearlytargetdiscount;
	$response['halfyeartargetpercentval'] = $halfyearlytargetpercentamount;
	$response['halfyearretailerpercent'] = $halfyearlyretailerdiscount;
	$response['halfyearretailerpercentval'] = $halfyearlyretailerpercentamount;
	$response['quarterunitamount'] = $quarterlycashamount;
	$response['quartertotaldeduct'] = $quarterlycashdisamount;
	$response['quartercashpercent'] = $quarterlycashdiscount;
	$response['quartercashpercentval'] = $quarterlycashpercentamount;
	$response['quartertargetpercent'] = $quarterlytargetdiscount;
	$response['quartertargetpercentval'] = $quarterlytargetpercentamount;
	$response['quarterretailerpercent'] = $quarterlyretailerdiscount;
	$response['quarterretailerpercentval'] = $quarterlyretailerpercentamount;
	$response['monthunitamount'] = $monthlycashamount;
	$response['monthtotaldeduct'] = $monthlycashdisamount;
	$response['monthcashpercent'] = $monthlycashdiscount;
	$response['monthcashpercentval'] = $monthlycashpercentamount;
	$response['monthtargetpercent'] = $monthlytargetdiscount;
	$response['monthtargetpercentval'] = $monthlytargetpercentamount;
	$response['monthretailerpercent'] = $monthlyretailerdiscount;
	$response['monthretailerpercentval'] = $monthlyretailerpercentamount;
	return $response;
}
function checkDiscount($productid, $curqty, $netprice)
{
	$response = array();
	$today = date("Y-m-d");
	$sql =  mysql_query("SELECT arocrm_discountmaster.*, arocrm_discountmastercf.*, arocrm_crmentity.* FROM arocrm_discountmaster
	INNER JOIN arocrm_discountmastercf ON arocrm_discountmastercf.discountmasterid = arocrm_discountmaster.discountmasterid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_discountmaster.discountmasterid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_discountmastercf.cf_4156<='".$today."' AND arocrm_discountmastercf.cf_4158>='".$today."'");
	$chknum = mysql_num_rows($sql);
	if($chknum >0)
	{
			while($row = mysql_fetch_array($sql))
			{
				$discountid = $row['discountmasterid'];
				$discountfor = $row['cf_4160'];
				$stime = $row['cf_4156'];
				$etime = $row['cf_4158'];
				if($discountfor == 'Product')
				{
					$tablename = strtolower($discountfor)."_scheme";
					$prosql = mysql_query("SELECT * FROM `arocrm_discountmaster_".$tablename."_lineitem`
					WHERE `discountmasterid` = '".$discountid."' AND `cf_4164` = '".$productid."'");
						$prorow = mysql_fetch_array($prosql);
						$frmqty = $prorow['cf_4166'];
						$toqty = $prorow['cf_4168'];
						$cashdistype = $prorow['cf_4170'];
						$cashdisamnt = $prorow['cf_4172'];
						$cashdispercent = $prorow['cf_4294'];
						$targetdispercent = $prorow['cf_4174'];
						$retailerdispercent = $prorow['cf_4176'];
						$totaldispercent = $prorow['cf_4178'];
						$sosql = mysql_query("SELECT arocrm_salesorder.*, arocrm_salesordercf.*, arocrm_crmentity.* FROM arocrm_salesorder
						INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_salesorder.salesorderid
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
						WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesordercf.cf_4306>='".$stime."' AND arocrm_salesordercf.cf_4306<='".$etime."'");
						$chkrow = mysql_num_rows($sosql);
						if($chkrow>0)
						{
							$qty = $curqty;
							$price = $netprice;
							$disamnt = 0;
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['salesorderid'];
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."' AND `productid` = '".$productid."'");
								$invrow = mysql_fetch_array($invsql);
								$qty = $qty + $invrow['quantity'];
								$price = $price + $invrow['margin'];
								$amount = $invrow['discount_amount'];
								if($amount == "")
								{
									$amount = 0;
								}
								$disamnt = $disamnt + $amount;
								$percent = $invrow['discount_percent'];
								if($percent == "")
								{
									$percent = 0;
								}
							}
							if($qty>=$frmqty && $qty<=$toqty)
							{
								$cashpercentamount = ($price*$cashdispercent)/100;
								$targetpercentamount = ($price*$targetdispercent)/100;
								$retailerpercentamount = ($price*$retailerdispercent)/100;
								if($cashdistype == 'Overall Discount')
								{
									$cashdisamount = $cashdisamnt;
									$cashdisamnt = 0;
									$totaldeductamount = $cashdisamount + $cashpercentamount + $targetpercentamount + $retailerpercentamount;
									$totalamount = $netprice - $totaldeductamount;
								}
								else
								{
									$cashdisamount = $curqty * $cashdisamnt;
									$totaldeductamount = $cashdisamount + $cashpercentamount + $targetpercentamount + $retailerpercentamount;
									$totalamount = $netprice - $totaldeductamount;
								}
							}
							else
							{
								$totalamount = $netprice;
								$totaldeductamount = 0;
							}
						}
				}
				else
				{
					$discount = explode(" ",$discountfor);
					$tablename = strtolower($discount[0])."_".strtolower($discount[1]);
					$product = getProductDetails($productid);
					$productcategory = $product['category'];
					$chk = "SELECT * FROM `arocrm_discountmaster_".$tablename."_lineitem`
					WHERE `discountmasterid` = '".$discountid."' AND `cf_4180` = '".$productcategory."'";
					$prosql = mysql_query("SELECT * FROM `arocrm_discountmaster_".$tablename."_lineitem`
					WHERE `discountmasterid` = '".$discountid."' AND `cf_4180` = '".$productcategory."'");
						$prorow = mysql_fetch_array($prosql);
						$frmqty = $prorow['cf_4184'];
						$toqty = $prorow['cf_4186'];
						$overcashdistype = $prorow['cf_4188'];
						$overcashdisamnt = $prorow['cf_4190'];
						$overcashdispercent = $prorow['cf_4296'];
						$overtargetdispercent = $prorow['cf_4192'];
						$overretailerdispercent = $prorow['cf_4194'];
						$overtotaldispercent = $prorow['cf_4196'];
						$sosql = mysql_query("SELECT arocrm_salesorder.*, arocrm_salesordercf.*, arocrm_crmentity.* FROM arocrm_salesorder
						INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_salesorder.salesorderid
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
						WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesordercf.cf_4306>='".$stime."' AND arocrm_salesordercf.cf_4306<='".$etime."'");
						$chkrow = mysql_num_rows($sosql);
						if($chkrow>0)
						{
							$qty = $curqty;
							$price = $netprice;
							$disamnt = 0;
							while($sorow = mysql_fetch_array($sosql))
							{
								$soid = $sorow['salesorderid'];
								$invsql = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$soid."' AND `productid` = '".$productid."'");
								$invrow = mysql_fetch_array($invsql);
								$qty = $qty + $invrow['quantity'];
								$price = $price + $invrow['margin'];
								$amount = $invrow['discount_amount'];
								if($amount == "")
								{
									$amount = 0;
								}
								$disamnt = $disamnt + $amount;
								$percent = $invrow['discount_percent'];
								if($percent == "")
								{
									$percent = 0;
								}
							}
							if($qty>=$frmqty && $qty<=$toqty)
							{
								$overcashpercentamount = ($totalamount*$overcashdispercent)/100;
								$overtargetpercentamount = ($totalamount*$overtargetdispercent)/100;
								$overretailerpercentamount = ($totalamount*$overretailerdispercent)/100;
								if($overcashdistype == 'Overall Discount')
								{
									$overcashdisamount = $overcashdisamnt;
									$overcashdisamnt = 0;
									$overtotaldeductamount = $overcashdisamount + $overcashpercentamount + $overtargetpercentamount + $overretailerpercentamount;
									$overtotalamount = $totalamount - $overtotaldeductamount;
								}
								else
								{
									$overcashdisamount = $curqty * $overcashdisamnt;
									$overtotaldeductamount = $overcashdisamount + $overcashpercentamount + $overtargetpercentamount + $overretailerpercentamount;
									$overtotalamount = $totalamount - $overtotaldeductamount;
								}
							}
							else
							{
								$totalamount = $netprice;
								$totaldeductamount = 0;
							}
						}
				}
			}
	}
	else
	{
		$totalamount = $netprice;
		$totaldeductamount = 0;
	}
	$response['totalamount'] = $totalamount;
	$response['totaldeductamount'] = $totaldeductamount;
	$response['overtotalamount'] = $overtotalamount;
	$response['overtotaldeductamount'] = $overtotaldeductamount;
	$response['cashamount'] = $cashdisamnt;
	$response['totalcashamount'] = $cashdisamount;
	$response['cashpercent'] = $cashdispercent;
	$response['targetpercent'] = $targetdispercent;
	$response['retailerpercent'] = $retailerdispercent;
	$response['cashpercentval'] = $cashpercentamount;
	$response['targetpercentval'] = $targetpercentamount;
	$response['retailerpercentval'] = $retailerpercentamount;
	$response['overcashamount'] = $overcashdisamnt;
	$response['overtotalcashamount'] = $overcashdisamount;
	$response['overcashpercent'] = $overcashdispercent;
	$response['overtargetpercent'] = $overtargetdispercent;
	$response['overretailerpercent'] = $overretailerdispercent;
	$response['overcashpercentval'] = $overcashpercentamount;
	$response['overtargetpercentval'] = $overtargetpercentamount;
	$response['overretailerpercentval'] = $overretailerpercentamount;
	return $response;
}
function getAllPlant()
{
	$response = array();
	$plantid = array();
	$assignedplant = $_SESSION['assigned_plant'];
	$cnt = count($assignedplant);
	for($i=0;$i<$cnt;$i++)
	{
		array_push($plantid,$assignedplant[$i]);

	}
	$response['plantid'] = $plantid;
	
	return $response;
}

function getAllStore($plant)
{
	$response = array();
	$allplant = implode(',',$plant);
	$stores = '';
	$stores .= '<select class="optionselect3" id="storeName" name="storeName[]" required="true" style="width: 200px"  multiple>';
	$storesql = mysql_query("SELECT arocrm_storagelocation.*, arocrm_crmentity.* FROM arocrm_storagelocation
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_storagelocation.storagelocationid
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_storagelocation.cf_nrl_plantmaster561_id IN (".$allplant.")");
	while($rowstore = mysql_fetch_array($storesql))
	{
		$stores  .='<option value="'.$rowstore['storagelocationid'].'">'.$rowstore['name'].'</option>';
	}
	$stores .='</select>';
	$response['store'] = $stores;
	return $response;
}
function getAllProduct($plant)
{
	$response = array();
	$allplant = implode(',',$plant);
	$products = '';
	$products .= '<select class="optionselect4" id="productName" name="productName[]" required="true" style="width: 200px" multiple>';
	$storesql = mysql_query("SELECT arocrm_plantproductassignment.*, arocrm_crmentity.* FROM arocrm_plantproductassignment
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantproductassignment.plantproductassignmentid
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantproductassignment.cf_nrl_plantmaster103_id IN (".$allplant.") GROUP BY arocrm_plantproductassignment.cf_nrl_products323_id");
		while($rowstore = mysql_fetch_array($storesql))
		{
			$prosql = mysql_query("SELECT arocrm_products.*, arocrm_productcf.*, arocrm_crmentity.* FROM arocrm_products
		           INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_products.productid
				   INNER JOIN arocrm_productcf ON arocrm_productcf.productid=arocrm_products.productid
		           WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$rowstore['cf_nrl_products323_id']);
				   $rowpro = mysql_fetch_array($prosql);
			$products  .='<option value="'.$rowpro['productid'].'">'.$rowpro['productname'].'</option>';
		}
	$products .='</select>';
	$response['product'] = $products;
	return $response;
}
function getStockReport($date,$plant,$store,$product,$statuss)
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
	$allstore = implode(',',$store);
	$allproduct = implode(',',$product);
	$statuss = implode(',',$statuss);
	$stock = '';
	$statustmp = explode(',',$statuss);
	$released = 0;
	$semiblocked = 0;
	$open = 0;
	$blocked = 0;
	if(in_array("R",$statustmp))
	{
	$released = 1;
	}
	if(in_array("B",$statustmp))
	{
	$blocked = 1;
	}
	if(in_array("S",$statustmp))
	{
	$semiblocked = 1;
	}
	if(in_array("O",$statustmp))
	{
	$open = 1;
	}
	
	
	$sql = "SELECT * FROM `arocrm_plantmaster` INNER JOIN `arocrm_plantmastercf` ON `arocrm_plantmastercf`.`plantmasterid` = `arocrm_plantmaster`.`plantmasterid` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_plantmaster`.`plantmasterid` 
	WHERE `arocrm_plantmaster`.`plantmasterid` IN (".$allplant.") AND `arocrm_crmentity`.`deleted` = 0 ORDER BY `arocrm_plantmaster`.`plantmasterno`";
	$stocksql = mysql_query($sql);
	while($rowstock = mysql_fetch_array($stocksql))
	{
	
	$stock .= '<tr><td colspan="12" style="background:#3B5998;color:#FFFFFF;"> BRANCH : '.$rowstock['name'].' ('.$rowstock['plantmasterno'].') </td></tr>';
	
		$sql_store = "SELECT * FROM `arocrm_storagelocation` INNER JOIN `arocrm_storagelocationcf` ON `arocrm_storagelocationcf`.`storagelocationid` = `arocrm_storagelocation`.`storagelocationid` INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_storagelocation`.`storagelocationid` WHERE `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$rowstock['plantmasterid']."' AND `arocrm_crmentity`.`deleted` = 0";
		if($allstore!="")
		{
		$sql_store .= " AND `arocrm_storagelocation`.`storagelocationid` IN (".$allstore.")";
		}

		$sql_store .= " ORDER BY `arocrm_storagelocation`.`storagelocationno`";
		$storesql = mysql_query($sql_store);
		while($rowstore = mysql_fetch_array($storesql))
		{
     $stock .= '<tr><td colspan="7" style="background:#55ACEE;color:#FFFFFF;"> STORE : '.$rowstore['name'].' ('.$rowstore['storagelocationno'].') </td></tr>';
	
	$stock .= '<tr style="border: 2px solid black !important;">
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Item Code</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Item Name</th>
							<th wrap style="background:#0889e7;color:#FFFFFF;">Item Unit</th>
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Ah</th>
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Category</th>
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Quality Status</th>
							<th nowrap style="background:#0889e7;color:#FFFFFF;">Stock</th>
	</tr>';

	$getproductsql = "SELECT * FROM `arocrm_plantproductassignment` 
	INNER JOIN `arocrm_plantproductassignmentcf` ON `arocrm_plantproductassignmentcf`.`plantproductassignmentid` = `arocrm_plantproductassignment`.`plantproductassignmentid` 
	INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_plantproductassignment`.`plantproductassignmentid`
	WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_plantproductassignment`.`cf_nrl_plantmaster103_id` = '".$rowstock['plantmasterid']."'"; 
	if($allproduct!=""){
	$getproductsql .= " AND `arocrm_plantproductassignment`.`cf_nrl_products323_id` IN (".$allproduct.")";	
	}
     $getproductsql .= " ORDER BY `arocrm_plantproductassignment`.`cf_nrl_products323_id` ASC";	
	 $plantqry = mysql_query($getproductsql);
	while($productrow = mysql_fetch_array($plantqry)){
		
	$productid = $productrow['cf_nrl_products323_id'];
	$getProduct = getProductAllDetails($productid);

	if($released==1){
	
		$stock .= '<tr style="border: 1px solid black;" class="drilldown" id="'.$fdate.':::'.$tdate.':::'.$productid.':::'.$rowstock['plantmasterid'].':::'.$rowstore['storagelocationid'].':::R">
		<th nowrap>'.$getProduct['productcode'].'</th>
		<th wrap>'.$getProduct['productname'].'</th>
		<th wrap>'.$getProduct['productunit'].'</th>
		<th nowrap>'.$getProduct['ah'].'</th>
		<th nowrap>'.$getProduct['category'].'</th>
		<th nowrap>R - Released</th>
		<th nowrap>'.getcurrentStock($fdate,$tdate,$productid,$rowstock['plantmasterid'],$rowstore['storagelocationid'],'R').'</th>
		</tr>';	
		
	}	
	
	if($blocked==1){
	
		$stock .= '<tr style="border: 1px solid black;" class="drilldown" id="'.$fdate.':::'.$tdate.':::'.$productid.':::'.$rowstock['plantmasterid'].':::'.$rowstore['storagelocationid'].':::B">
		<th nowrap>'.$getProduct['productcode'].'</th>
		<th wrap>'.$getProduct['productname'].'</th>
		<th wrap>'.$getProduct['productunit'].'</th>
		<th nowrap>'.$getProduct['ah'].'</th>
		<th nowrap>'.$getProduct['category'].'</th>
		<th nowrap>B - Blocked</th>
		<th nowrap>'.getcurrentStock($fdate,$tdate,$productid,$rowstock['plantmasterid'],$rowstore['storagelocationid'],'B').'</th>
		</tr>';	
		
	}

	if($semiblocked==1){

		$stock .= '<tr style="border: 1px solid black;" class="drilldown" id="'.$fdate.':::'.$tdate.':::'.$productid.':::'.$rowstock['plantmasterid'].':::'.$rowstore['storagelocationid'].':::S">
		<th nowrap>'.$getProduct['productcode'].'</th>
		<th wrap>'.$getProduct['productname'].'</th>
		<th wrap>'.$getProduct['productunit'].'</th>
		<th nowrap>'.$getProduct['ah'].'</th>
		<th nowrap>'.$getProduct['category'].'</th>
		<th nowrap>S - Semiblocked</th>
		<th nowrap>'.getcurrentStock($fdate,$tdate,$productid,$rowstock['plantmasterid'],$rowstore['storagelocationid'],'S').'</th>
		</tr>';	
		
	}	
	
	if($open==1){

		$stock .= '<tr style="border: 1px solid black;" class="drilldown" id="'.$fdate.':::'.$tdate.':::'.$productid.':::'.$rowstock['plantmasterid'].':::'.$rowstore['storagelocationid'].':::O">
		<th nowrap>'.$getProduct['productcode'].'</th>
		<th wrap>'.$getProduct['productname'].'</th>
		<th wrap>'.$getProduct['productunit'].'</th>
		<th nowrap>'.$getProduct['ah'].'</th>
		<th nowrap>'.$getProduct['category'].'</th>
		<th nowrap>O - Open</th>
		<th nowrap>'.getcurrentStock($fdate,$tdate,$productid,$rowstock['plantmasterid'],$rowstore['storagelocationid'],'O').'</th>
		</tr>';	
		
	  }			
	}
	
		}
		
	}
	$response['stockreporthtml'] = $stock;
	return $response;
}

function getcurrentStock($startdate,$enddate,$productid,$plantid,$storeid,$stockst){

$opendsql = mysql_query("SELECT SUM(`debit_quantity`) AS opend FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$stockst."' AND `transaction_date` < '".$startdate."'");
$opendq = mysql_fetch_array($opendsql);	
$opendeb = $opendq['opend'];

$opencsql = mysql_query("SELECT SUM(`credit_quantity`) AS openc FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$stockst."' AND `transaction_date` < '".$startdate."'");
$opencq = mysql_fetch_array($opencsql);	
$openceb = $opencq['openc'];

$openingstk = (float)$opendeb - (float)$openceb;
	
$opencsqls = mysql_query("SELECT SUM(`debit_quantity`) AS opends FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$stockst."' AND `transaction_date` BETWEEN '".$startdate."' AND '".$enddate."'");
$openqs = mysql_fetch_array($opencsqls);	
$opendebs = $openqs['opends'];

$opensqls = mysql_query("SELECT SUM(`credit_quantity`) AS opencs FROM `arocrm_dailystock_details` WHERE `product` = '".$productid."' AND `plant` = '".$plantid."' AND `store` = '".$storeid."' AND `qualitystatus` = '".$stockst."' AND `transaction_date` BETWEEN '".$startdate."' AND '".$enddate."'");
$opencsq = mysql_fetch_array($opensqls);	
$opencebs = $opencsq['opencs'];

$netqty = (float)$opendebs - (float)$opencebs;
$totalqty = (float)$openingstk + (float)$netqty;
return $totalqty;
}
function getClaimforSO($id)
{
	$response = array();
	$sql =  mysql_query("SELECT arocrm_troubletickets.*, arocrm_ticketcf.*, arocrm_crmentity.* FROM arocrm_troubletickets
	INNER JOIN arocrm_ticketcf ON arocrm_ticketcf.ticketid = arocrm_troubletickets.ticketid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_troubletickets.ticketid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_troubletickets.ticketid='".$id."'");
	$row = mysql_fetch_array($sql);
	$response['productid'] = $row['product_id'];
	$product = getProductDetails($response['productid']);
	$response['productname'] = $product['productname'];
	$response['productcode'] = $product['productcode'];
	$response['productunit'] = $product['unit'];
	$response['productcategory'] = $row['cf_4236'];
	$response['invoiceno'] = $row['cf_4923'];
	$invqry = mysql_query("SELECT arocrm_invoice.* FROM arocrm_invoice 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoice_no = '".$response['invoiceno']."'");
	$invrow = mysql_fetch_array($invqry);
	$response['customerid'] = $invrow['accountid'];
	$cussql = mysql_query("SELECT arocrm_account.* FROM arocrm_account 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_account.accountid ='".$response['customerid']."'");
	$cusrow = mysql_fetch_array($cussql);
	$response['custname'] = $cusrow['accountname'];
	$contactsql = mysql_query("SELECT arocrm_contactdetails.* FROM arocrm_contactdetails 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_contactdetails.contactid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_contactdetails.accountid ='".$response['customerid']."'");
	$contactrow = mysql_fetch_array($contactsql);
	$response['contactid']= $contactrow['contactid'];
	$response['contactname'] = $contactrow['firstname']." ".$contactrow['lastname'];
	$response['plantid'] = $row['cf_nrl_plantmaster913_id'];
	$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster
      INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
      WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$response['plantid']."'");
		$rowplant = mysql_fetch_array($plantsql);
		$response['plantname'] = $rowplant['name'];
		
	return $response;
}
function getTicketDetails($id)
{
	$response = array();
	$sql =  mysql_query("SELECT arocrm_troubletickets.*, arocrm_ticketcf.*, arocrm_crmentity.* FROM arocrm_troubletickets
	INNER JOIN arocrm_ticketcf ON arocrm_ticketcf.ticketid = arocrm_troubletickets.ticketid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_troubletickets.ticketid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_troubletickets.ticketid='".$id."'");
	$row = mysql_fetch_array($sql);
	$warrantyno = $row['cf_nrl_servicecontracts843_id'];
	$wchk = mysql_query("SELECT * FROM arocrm_crmentity WHERE arocrm_crmentity.crmid = '".$warrantyno."'");
	$wrow = mysql_fetch_array($wchk);
	$wdate = $wrow['createdtime'];
	$wnodate = explode(' ',$wdate);
	$response['regdate'] = $wnodate[0];
	$response['serialno'] = $row['cf_2991'];
	$response['selldate'] = $row['cf_4230'];
	$response['productid'] = $row['product_id'];
	$product = getProductDetails($response['productid']);
	$response['productname'] = $product['productname'];
	$response['plantid'] = $row['cf_nrl_plantmaster913_id'];
	$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster
      INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
      WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$response['plantid']."'");
		$rowplant = mysql_fetch_array($plantsql);
	$response['plantname'] = $rowplant['name'];
	$response['productcategory'] = $row['cf_4236'];
	$response['cp'] = $row['cf_4238'];
	$response['consumer'] = $row['cf_4250'];
	$response['mobile'] = $row['cf_4256'];
	$response['street'] = $row['cf_4280'];
	$response['city'] = $row['cf_4284'];
	$response['po'] = $row['cf_4282'];
	$response['state'] = $row['cf_4286'];
	$response['country'] = $row['cf_4290'];
	$response['zip'] = $row['cf_4288'];
	$response['ppoint'] = $row['cf_4258'];
	$response['pplace'] = $row['cf_4260'];
	$response['vrno'] = $row['cf_4545'];
	$response['vmodel'] = $row['cf_4543'];
	return $response;
}
function getSerialWarrantyConsumerDetails($serial)
{
	$response = array();
	$sql =  mysql_query("SELECT arocrm_servicecontracts.*, arocrm_servicecontractscf.*, arocrm_crmentity.* FROM  arocrm_servicecontracts
	INNER JOIN arocrm_servicecontractscf ON arocrm_servicecontractscf.servicecontractsid = arocrm_servicecontracts.servicecontractsid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_servicecontracts.servicecontractsid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_servicecontractscf.cf_3628='".$serial."'");
	$chknum = mysql_num_rows($sql);
	if($chknum>0)
	{
		$sqlticket =  mysql_query("SELECT arocrm_troubletickets.*, arocrm_ticketcf.*, arocrm_crmentity.* FROM arocrm_troubletickets
	INNER JOIN arocrm_ticketcf ON arocrm_ticketcf.ticketid = arocrm_troubletickets.ticketid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_troubletickets.ticketid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_troubletickets.cf_2991='".$serial."'");
		$chkrow = mysql_num_rows($sqlticket);
		if($chkrow==0)
		{
		$row = mysql_fetch_array($sql);
		$response['warrantyno'] = $row['servicecontractsid'];
		$response['warranty'] = $row['subject'];
		$response['mfgdate'] = $row['cf_2989'];
		$response['selldate'] = $row['cf_2971'];
		$response['productid'] = $row['cf_nrl_products997_id'];
		$product = getProductDetails($response['productid']);
		$response['productname'] = $product['productname'];
		$response['productcode'] = $row['cf_2973'];
		$response['productgroup'] = $row['cf_3709'];
		$response['productcategory'] = $row['cf_3711'];
		$response['invoiceno'] = $row['cf_4921'];
		$response['cp'] = $row['cf_3615'];
		$response['plantid'] = $row['cf_nrl_plantmaster460_id'];
		$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster
      INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
      WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$response['plantid']."'");
		$rowplant = mysql_fetch_array($plantsql);
		$response['plantname'] = $rowplant['name'];
		$response['sellperiod'] = $row['cf_3124'];
		$response['eaplsellperiod'] = $row['cf_4846'];
		$response['wfreeperiod'] = $row['cf_3420'];
		$response['wprorataperiod'] = $row['cf_3126'];
		$response['cfreedate'] = $row['cf_3638'];
		$response['cproratadate'] = $row['cf_2975'];
		$response['afreedate'] = $row['cf_3640'];
		$response['aproratadate'] = $row['cf_2977'];
		$response['stage'] = $row['contract_status'];
		$response['status'] = $row['cf_3661'];
		$response['consumer'] = $row['cf_2969'];
		$response['mail'] = $row['cf_3623'];
		$response['mobile'] = $row['cf_3621'];
		$response['street'] = $row['cf_3689'];
		$response['city'] = $row['cf_3693'];
		$response['po'] = $row['cf_3691'];
		$response['state'] = $row['cf_3695'];
		$response['country'] = $row['cf_3699'];
		$response['zip'] = $row['cf_3697'];
		$response['ppoint'] = $row['cf_3617'];
		$response['pplace'] = $row['cf_3701'];
		$response['pdis'] = $row['cf_3703'];
		$response['pstate'] = $row['cf_3705'];
		$response['purchasedate'] = $row['cf_3619'];
		$response['vehiclemake'] = $row['cf_4533'];
		$response['modelno'] = $row['cf_4535'];
		$response['voltagewithoutload'] = $row['cf_3751'];
		$response['acligthsload'] = $row['cf_3753'];
		$response['leakage'] = $row['cf_3755'];
		$response['OEFitment'] = $row['cf_3757'];
		$response['datecheckap'] = $row['cf_4595'];
		$response['make'] = $row['cf_3725'];
		$response['modeltype'] = $row['cf_3727'];
		$response['my'] = $row['cf_3729'];
		$response['life'] = $row['cf_3731'];
		$response['installdp'] = $row['cf_3733'];
		$response['invertermake'] = $row['cf_3735'];
		$response['invertermodel'] = $row['cf_5039'];
		$response['capacity'] = $row['cf_3737'];
		$response['sysvoltage'] = $row['cf_3739'];
		$response['dcvoltagerate'] = $row['cf_3741'];
		$response['charge'] = $row['cf_3743'];
		$response['discharge'] = $row['cf_3745'];
		$response['line'] = $row['cf_3747'];
		$response['inverterop'] = $row['cf_3749'];
		$response['drivermotor'] = $row['cf_3761'];
		$response['controlsys'] = $row['cf_3763'];
		$response['amps'] = $row['cf_3765'];
		$response['erleakage'] = $row['cf_3767'];
		$response['extra'] = $row['cf_3769'];
		$response['datecheck'] = $row['cf_3771'];
		$response['message'] = "";
		}
		else
		{
			$response['message'] = "Already claim lodged against this Serial No.";
		}
	}
	else
	{
		$response['message'] = "Serial No. not Registered in Warranty, First fillup Warranty Registration with this Serial No.";
	}
	 return $response;
}
function getWarrantyDetails($id)
{
	$response = array();
	$sql =  mysql_query("SELECT arocrm_servicecontracts.*, arocrm_servicecontractscf.*, arocrm_crmentity.* FROM  arocrm_servicecontracts
	INNER JOIN arocrm_servicecontractscf ON arocrm_servicecontractscf.servicecontractsid = arocrm_servicecontracts.servicecontractsid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_servicecontracts.servicecontractsid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_servicecontracts.servicecontractsid=".$id);
	$rows = mysql_fetch_array($sql);
	$response['serialno'] = $rows['cf_3628'];
	$response['mfgdate'] = $rows['cf_2989'];
	$response['selldate'] = $rows['cf_2971'];
	$response['productid'] = $rows['cf_nrl_products997_id'];
	$product = getProductDetails($response['productid']);
	$response['productname'] = $product['productname'];
	$response['productcode'] = $rows['cf_2973'];
	$response['productgroup'] = $rows['cf_3709'];
	$response['productcategory'] = $rows['cf_3711'];
	$response['cp'] = $rows['cf_3615'];
	$response['sellperiod'] = $rows['cf_3124'];
	$response['wfreeperiod'] = $rows['cf_3420'];
	$response['wprorataperiod'] = $rows['cf_3126'];
	$response['cfreedate'] = $rows['cf_3638'];
	$response['cproratadate'] = $rows['cf_2975'];
	$response['afreedate'] = $rows['cf_3640'];
	$response['aproratadate'] = $rows['cf_2977'];
	$response['stage'] = $rows['contract_status'];
	$response['status'] = $rows['cf_3661'];
	$response['consumer'] = $rows['cf_2969'];
	$response['mail'] = $rows['cf_3623'];
	$response['mobile'] = $rows['cf_3621'];
	$response['street'] = $rows['cf_3689'];
	$response['city'] = $rows['cf_3693'];
	$response['po'] = $rows['cf_3691'];
	$response['state'] = $rows['cf_3695'];
	$response['country'] = $rows['cf_3699'];
	$response['zip'] = $rows['cf_3697'];
	$response['ppoint'] = $rows['cf_3617'];
	$response['pplace'] = $rows['cf_3701'];
	$response['pdis'] = $rows['cf_3703'];
	$response['pstate'] = $rows['cf_3705'];
	$response['make'] = $rows['cf_3725'];
	$response['mode'] = $rows['cf_3727'];
	$response['pmy'] = $rows['cf_3729'];
	$response['life'] = $rows['cf_3731'];
	$response['purchasedate'] = $rows['cf_3619'];
	return $response;
}
function getSerialNoDetails($serial)
{
	$response = array();
	$message = "";
	$result = mysql_query("SELECT arocrm_serialnumber.*, arocrm_serialnumbercf.*, arocrm_crmentity.* FROM arocrm_serialnumber
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_serialnumber.serialnumberid
	INNER JOIN arocrm_serialnumbercf ON arocrm_serialnumbercf.serialnumberid = arocrm_serialnumber.serialnumberid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_serialnumber.name LIKE '%".$serial."%'
	AND arocrm_serialnumbercf.cf_1256 = 'R' AND arocrm_serialnumbercf.cf_1270 != '' AND arocrm_serialnumbercf.cf_2834 = '2' AND arocrm_serialnumbercf.cf_3084 = 'R' AND arocrm_serialnumbercf.cf_3128!=''");
	$numrow = mysql_num_rows($result);
	if($numrow > 0)
	{
		$res = mysql_query("SELECT arocrm_servicecontracts.* FROM arocrm_servicecontracts 
		INNER JOIN arocrm_servicecontractscf ON arocrm_servicecontractscf.servicecontractsid = arocrm_servicecontractscf.servicecontractsid 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_servicecontractscf.servicecontractsid 
		WHERE arocrm_crmentity.deleted = 0 AND arocrm_servicecontractscf.cf_3628 = '".$serial."'");
		$numres = mysql_num_rows($res);
		if($numres > 0)
		{
			$message = "Already warranty registered for this Serial Number";
		}
		else
		{
			$row = mysql_fetch_array($result);
			$serialno = $row['serialnumberid'];
		}
	}
	else
	{
		$message = "This serial no is not out from our stock";
	}
	$response['serialno'] = $serialno;
	$response['message'] = $message;
	return $response;
}
function getPJP($id)
{
	$response = array();
	$result = mysql_query("SELECT arocrm_journeyplan.*, arocrm_journeyplancf.*, arocrm_crmentity.* FROM arocrm_journeyplan
	INNER JOIN arocrm_journeyplancf ON arocrm_journeyplancf.journeyplanid = arocrm_journeyplan.journeyplanid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_journeyplan.journeyplanid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_journeyplan.journeyplanid ='".$id."'");
	$row = mysql_fetch_array($result);
	$year = $row['cf_1491'];
	$month = $row['cf_1493'];
	$normal = $row['cf_3592'];
	$calamity = $row['cf_3594'];

	$tbodyBasic = "";
	$rowcountBasic = 1;
	$resultBasic = mysql_query("SELECT arocrm_journeyplan.*, arocrm_journeyplan_basic_details_lineitem.*, arocrm_crmentity.* FROM arocrm_journeyplan
	INNER JOIN arocrm_journeyplan_basic_details_lineitem ON arocrm_journeyplan_basic_details_lineitem.journeyplanid = arocrm_journeyplan.journeyplanid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_journeyplan.journeyplanid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_journeyplan.journeyplanid ='".$id."'");
	$countBasic = mysql_num_rows($resultBasic);
	$i = 1;
	while($rowBasic = mysql_fetch_array($resultBasic))
	{
		$tbodyBasic .= '<tr id="Basic_Details__row_'.$i.'" class="tr_clone">';
			if($countBasic == '1')
			{
				$tbodyBasic .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			else
			{
				$tbodyBasic .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			$routeResult = mysql_query("SELECT arocrm_routemaster.*, arocrm_crmentity.* FROM arocrm_routemaster
			INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = '0' AND arocrm_routemaster.routemasterid = '".$rowBasic['cf_nrl_routemaster499_id']."'");
			$routeRow = mysql_fetch_array($routeResult);
			$routename = $routeRow['name'];
			$channel = explode(",",$rowBasic['cf_2070']);
			$lead = explode(",",$rowBasic['cf_3130']);
			$cussql = mysql_query("SELECT arocrm_account.* FROM arocrm_account 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid 
	WHERE arocrm_crmentity.deleted = 0");
	
			$leadsql = mysql_query("SELECT arocrm_leaddetails.* FROM arocrm_leaddetails 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_leaddetails.leadid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_leaddetails.company!=''");
	
			$tbodyBasic .= '<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_1962_'.$i.'" type="date" class="form-control " data-fieldname="cf_1962" name="cf_1962_'.$i.'" value="'.$rowBasic['cf_1962'].'" data-rule-date="true"></div></td>
							<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="RouteMaster" id="popupReferenceModule_'.$i.'"><div class="input-group"><input name="cf_nrl_routemaster499_id_'.$i.'" type="hidden" value="'.$rowBasic['cf_nrl_routemaster499_id'].'" class="sourceField" data-displayvalue="" id="cf_nrl_routemaster499_id_'.$i.'"><input id="cf_nrl_routemaster499_id_display_'.$i.'" name="cf_nrl_routemaster499_id_display_'.$i.'" data-fieldname="cf_nrl_routemaster499_id" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$routename.'" readonly placeholder="Type to search" autocomplete="off"><a href="#" class="clearReferenceSelection hide"> x </a><span class="input-group-addon relatedPopup cursorPointer" id="cf_nrl_routemaster499_id_'.$i.',cf_nrl_routemaster499_id_display_'.$i.'" title="Select"><i id="JourneyPlan_editView_fieldName_cf_nrl_routemaster499_id_select" class="fa fa-search"></i></span></div><span class="createReferenceRecord cursorPointer clearfix" title="Create"><i id="JourneyPlan_editView_fieldName_cf_nrl_routemaster499_id_create" class="fa fa-plus"></i></span></div></td>
							<td class="fieldValue"><input id="cf_1988_'.$i.'" type="text" data-fieldname="cf_1988" data-fieldtype="string" class="inputElement " name="cf_1988_'.$i.'" readonly value="'.$rowBasic['cf_1988'].'"></td>
							<td class="fieldValue"><input id="cf_2000_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2000_'.$i.'" readonly value="'.$rowBasic['cf_2000'].'"></td>
							<td class="fieldValue"><select id="cf_2070_'.$i.'" multiple="" class="select2 multipicklist select2-offscreen" name="cf_2070_'.$i.'[]" data-fieldtype="multipicklist" style="width:210px;height:30px;" tabindex="-1">';
							while($cusrow = mysql_fetch_array($cussql))
							{
								$custname = $cusrow['accountname'];
								$tbodyBasic .='<option value="'.$custname.'" '.((in_array($custname, $channel))?"selected='selected'":'').'>'.$custname.'</option>';
							}
							$tbodyBasic .='</select>
							<script>
							$(document).ready(function(){
							$("#cf_2070_'.$i.'").select2();
							});
							</script>
							</td>
							<td class="fieldValue"><select id="cf_3130_'.$i.'" multiple="" class="select2 multipicklist select2-offscreen" name="cf_3130_'.$i.'[]" data-fieldtype="multipicklist" style="width:210px;height:30px;" tabindex="-1">';
							while($leadrow = mysql_fetch_array($leadsql))
							{
								$company = $leadrow['company'];
								$tbodyBasic .='<option value="'.$company.'" '.((in_array($company, $lead))?"selected='selected'":'').'>'.$company.'</option>';
							}
							$tbodyBasic .='</select>
							<script>
							$(document).ready(function(){
							$("#cf_3130_'.$i.'").select2();
							});
							</script>
							</td>
							<td class="fieldValue"><input id="cf_2072_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2072_'.$i.'" value="'.$rowBasic['cf_2072'].'"></td>
							<td class="fieldValue"><select data-fieldname="cf_2016" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_2016_'.$i.'" data-selected-value=" " tabindex="-1" title="" id="cf_2016_'.$i.'"><option value="">Select an Option</option><option value="1" '.(($rowBasic['cf_2016']=="1")?"selected='selected'":'').'>1</option><option value="2" '.(($rowBasic['cf_2016']=="2")?"selected='selected'":'').'>2</option><option value="3" '.(($rowBasic['cf_2016']=="3")?"selected='selected'":'').'>3</option><option value="4" '.(($rowBasic['cf_2016']=="4")?"selected='selected'":'').'>4</option><option value="5" '.(($rowBasic['cf_2016']=="5")?"selected='selected'":'').'>5</option><option value="6" '.(($rowBasic['cf_2016']=="6")?"selected='selected'":'').'>6</option><option value="7" '.(($rowBasic['cf_2016']=="7")?"selected='selected'":'').'>7</option><option value="8" '.(($rowBasic['cf_2016']=="8")?"selected='selected'":'').'>8</option><option value="9" '.(($rowBasic['cf_2016']=="9")?"selected='selected'":'').'>9</option></select>
							<script>
							$(document).ready(function(){
							$("#cf_2016_'.$i.'").select2();
							});
							</script>
							</td>
							<td class="fieldValue"><select data-fieldname="cf_2024" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_2024_'.$i.'" data-selected-value=" " tabindex="-1" title="" id="cf_2024_'.$i.'"><option value="">Select an Option</option><option value="1" '.(($rowBasic['cf_2024']=="1")?"selected='selected'":'').'>1</option><option value="2" '.(($rowBasic['cf_2024']=="2")?"selected='selected'":'').'>2</option><option value="3" '.(($rowBasic['cf_2024']=="3")?"selected='selected'":'').'>3</option><option value="4" '.(($rowBasic['cf_2024']=="4")?"selected='selected'":'').'>4</option><option value="5" '.(($rowBasic['cf_2024']=="5")?"selected='selected'":'').'>5</option><option value="6" '.(($rowBasic['cf_2024']=="6")?"selected='selected'":'').'>6</option><option value="7" '.(($rowBasic['cf_2024']=="7")?"selected='selected'":'').'>7</option><option value="8" '.(($rowBasic['cf_2024']=="8")?"selected='selected'":'').'>8</option><option value="9" '.(($rowBasic['cf_2024']=="9")?"selected='selected'":'').'>9</option></select>
							<script>
							$(document).ready(function(){
							$("#cf_2024_'.$i.'").select2();
							});
							</script>
							</td>
							<td class="fieldValue"><input id="cf_2034_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2034_'.$i.'" value="'.$rowBasic['cf_2034'].'"></td>
							<td class="fieldValue"><input id="cf_2036_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2036_'.$i.'" value="'.$rowBasic['cf_2036'].'"></td>
							<td class="fieldValue"><input id="cf_2038_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2038_'.$i.'" value="'.$rowBasic['cf_2038'].'"></td>
							<td class="fieldValue"><input id="cf_2040_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2040_'.$i.'" readonly value="'.$rowBasic['cf_2040'].'"></td>
						</tr>';
						if($i != '1')
						{
							$rowcountBasic = $rowcountBasic.",".$i;
						}
						$i++;
	}


	$tbodyWorking = "";
	$rowcountWorking = 1;
	$resultWorking = mysql_query("SELECT arocrm_journeyplan.*, arocrm_journeyplan_actual_working_details_lineitem.*, arocrm_crmentity.* FROM arocrm_journeyplan
	INNER JOIN arocrm_journeyplan_actual_working_details_lineitem ON arocrm_journeyplan_actual_working_details_lineitem.journeyplanid = arocrm_journeyplan.journeyplanid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_journeyplan.journeyplanid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_journeyplan.journeyplanid ='".$id."'");
	$countWorking = mysql_num_rows($resultWorking);
	$i = 1;
	while($rowWorking = mysql_fetch_array($resultWorking))
	{
		$tbodyWorking .= '<tr id="Actual_Working_Details__row_'.$i.'" class="tr_clone">';
			if($countWorking == '1')
			{
				$tbodyWorking .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			else
			{
				$tbodyWorking .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			$tbodyWorking .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2086_'.$i.'" type="date" class="form-control " data-fieldname="cf_2086" name="cf_2086_'.$i.'" readonly value="'.$rowWorking['cf_2086'].'" data-rule-date="true"></div></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2102_'.$i.'" type="text" data-fieldname="cf_2102" data-fieldtype="string" class="inputElement " name="cf_2102_'.$i.'" readonly value="'.$rowWorking['cf_2102'].'"></td>';

			$purpose = explode(",",$rowWorking['cf_2104']);
			$tbodyWorking .='<td class="fieldValue"><select id="cf_2104_'.$i.'" multiple="" class="multipicklist" name="cf_2104_'.$i.'[]" data-fieldtype="multipicklist" style="width:210px;height:30px;" tabindex="-1"><option value="Channel Reaction" '.((in_array("Channel Reaction", $purpose))?"selected='selected'":'').'>Channel Reaction</option><option value="Channel Convinced" '.((in_array("Channel Convinced", $purpose))?"selected='selected'":'').'>Channel Convinced</option><option value="No of W Card - 4W" '.((in_array("No of W Card - 4W", $purpose))?"selected='selected'":'').'>No of W Card - 4W</option><option value="No of W Card - 2W" '.((in_array("No of W Card - 2W", $purpose))?"selected='selected'":'').'>No of W Card - 2W</option><option value="No of W Card - IB" '.((in_array("No of W Card - IB", $purpose))?"selected='selected'":'').'>No of W Card - IB</option><option value="No of W Card - ER" '.((in_array("No of W Card - ER", $purpose))?"selected='selected'":'').'>No of W Card - ER</option><option value="Compt of Stock Details - 4W" '.((in_array("Compt of Stock Details - 4W", $purpose))?"selected='selected'":'').'>Compt of Stock Details - 4W</option><option value="Compt of Stock Details - 2W" '.((in_array("Compt of Stock Details - 2W", $purpose))?"selected='selected'":'').'>Compt of Stock Details - 2W</option><option value="Compt of Stock Details - IB" '.((in_array("Compt of Stock Details - IB", $purpose))?"selected='selected'":'').'>Compt of Stock Details - IB</option><option value="Compt of Stock Details - ER" '.((in_array("Compt of Stock Details - ER", $purpose))?"selected='selected'":'').'>Compt of Stock Details - ER</option><option value="Centurion Stock Details - 4W" '.((in_array("Centurion Stock Details - 4W", $purpose))?"selected='selected'":'').'>Centurion Stock Details - 4W</option><option value="Centurion Stock Details - 2W" '.((in_array("Centurion Stock Details - 2W", $purpose))?"selected='selected'":'').'>Centurion Stock Details - 2W</option><option value="Centurion Stock Details - IB" '.((in_array("Centurion Stock Details - IB", $purpose))?"selected='selected'":'').'>Centurion Stock Details - IB</option><option value="Centurion Stock Details - ER" '.((in_array("Centurion Stock Details - ER", $purpose))?"selected='selected'":'').'>Centurion Stock Details - ER</option><option value="No of SO - 4W" '.((in_array("No of SO - 4W", $purpose))?"selected='selected'":'').'>No of SO - 4W</option><option value="No of SO - 2W" '.((in_array("No of SO - 2W", $purpose))?"selected='selected'":'').'>No of SO - 2W</option><option value="No of SO - IB" '.((in_array("No of SO - IB", $purpose))?"selected='selected'":'').'>No of SO - IB</option><option value="No of SO - ER" '.((in_array("No of SO - ER", $purpose))?"selected='selected'":'').'>No of SO - ER</option><option value="Realisation of OS or Adv" '.((in_array("Realisation of OS or Adv", $purpose))?"selected='selected'":'').'>Realisation of OS or Adv</option><option value="Mode of Payment" '.((in_array("Mode of Payment", $purpose))?"selected='selected'":'').'>Mode of Payment</option><option value="Cheque Number" '.((in_array("Cheque Number", $purpose))?"selected='selected'":'').'>Cheque Number</option><option value="Cheque Date" '.((in_array("Cheque Date", $purpose))?"selected='selected'":'').'>Cheque Date</option><option value="Outcome of BTL Activity" '.((in_array("Outcome of BTL Activity", $purpose))?"selected='selected'":'').'>Outcome of BTL Activity</option><option value="Other Details" '.((in_array("Other Details", $purpose))?"selected='selected'":'').'>Other Details</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2104_'.$i.'").select2();
			});
			</script>
			</td>';
			$tbodyWorking .= '<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3657_'.$i.'" class="inputElement " name="cf_3657_'.$i.'"></textarea></td>';

			$tbodyWorking .='<td class="fieldValue"><select data-fieldname="cf_2106" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2106_'.$i.'" data-selected-value="" tabindex="-1" title="" id="cf_2106_'.$i.'"><option value="">Select an Option</option><option value="Happy" '.(($rowWorking['cf_2106']=="Happy")?"selected='selected'":'').'>Happy</option><option value="Unhappy" '.(($rowWorking['cf_2106']=="Unhappy")?"selected='selected'":'').'>Unhappy</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2106_'.$i.'").select2();
			});
			</script>
			</td>';

			$tbodyWorking .='<td class="fieldValue"><select data-fieldname="cf_2108" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2108_'.$i.'" data-selected-value=" " tabindex="-1" title="" id="cf_2108_'.$i.'"><option value="">Select an Option</option><option value="Yes" '.(($rowWorking['cf_2108']=="Yes")?"selected='selected'":'').'>Yes</option><option value="No" '.(($rowWorking['cf_2108']=="No")?"selected='selected'":'').'>No</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2108_'.$i.'").select2();
			});
			</script>
			</td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2110_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2110_'.$i.'" readonly value="'.$rowWorking['cf_2110'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2112_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2112_'.$i.'" readonly value="'.$rowWorking['cf_2112'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2114_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2114_'.$i.'" readonly value="'.$rowWorking['cf_2114'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2116_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2116_'.$i.'" readonly value="'.$rowWorking['cf_2116'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2118_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2118_'.$i.'" readonly value="'.$rowWorking['cf_2118'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2120_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2120_'.$i.'" readonly value="'.$rowWorking['cf_2120'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2122_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2122_'.$i.'" readonly value="'.$rowWorking['cf_2122'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2126_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2126_'.$i.'" readonly value="'.$rowWorking['cf_2124'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2128_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2128_'.$i.'" readonly value="'.$rowWorking['cf_2128'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2130_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2130_'.$i.'" readonly value="'.$rowWorking['cf_2130'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2132_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2132_'.$i.'" readonly value="'.$rowWorking['cf_2132'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2134_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2134_'.$i.'" readonly value="'.$rowWorking['cf_2134'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2136_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2136_'.$i.'" readonly value="'.$rowWorking['cf_2136'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2138_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2138_'.$i.'" readonly value="'.$rowWorking['cf_2138'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2140_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2140_'.$i.'" readonly value="'.$rowWorking['cf_2140'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2142_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2142_'.$i.'" readonly value="'.$rowWorking['cf_2142'].'"></td>';
			
			$tbodyWorking .='<td class="fieldValue"><input id="cf_2144_'.$i.'" type="text" data-fieldname="cf_2144" data-fieldtype="string" class="inputElement " name="cf_2144_'.$i.'" value="'.$rowWorking['cf_2144'].'"></td>';
			
			$tbodyWorking .='<td class="fieldValue"><select data-fieldname="cf_2146" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2146_'.$i.'" data-selected-value=" " tabindex="-1" title="" id="cf_2146_'.$i.'"><option value="">Select an Option</option><option value="Cash" '.(($rowWorking['cf_2146']=="Cash")?"selected='selected'":'').'>Cash</option><option value="Cheque" '.(($rowWorking['cf_2146']=="Cheque")?"selected='selected'":'').'>Cheque</option><option value="RTGS" '.(($rowWorking['cf_2146']=="RTGS")?"selected='selected'":'').'>RTGS</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2146_'.$i.'").select2();
			});
			</script>
			</td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2148_'.$i.'" type="text" data-fieldname="cf_2148" data-fieldtype="string" class="inputElement " name="cf_2148_'.$i.'" readonly value="'.$rowWorking['cf_2148'].'"></td>';

			$tbodyWorking .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2150_'.$i.'" type="date" class="form-control " data-fieldname="cf_2150" name="cf_2150_'.$i.'" readonly value="'.$rowWorking['cf_2150'].'" data-rule-date="true"></div></td>';

			$tbodyWorking .='<td class="fieldValue"><input id="cf_2152_'.$i.'" type="text" data-fieldname="cf_2152" data-fieldtype="string" class="inputElement " name="cf_2152_'.$i.'" value="'.$rowWorking['cf_2152'].'"></td>';
			
			$tbodyWorking .='<td class="fieldValue"><textarea rows="5" id="cf_2154_'.$i.'" class="inputElement " readonly name="cf_2154_'.$i.'">'.$rowWorking['cf_2154'].'</textarea></td>';

			$tbodyWorking .='</tr>';

			if($i!='1')
			{
				$rowcountWorking = $rowcountWorking.",".$i;
			}
			$i++;
	}

	$tbodyBill = "";
	$rowcountBill = 1;
	$resultBill = mysql_query("SELECT arocrm_journeyplan.*, arocrm_journeyplan_actual_bill_details_lineitem.*, arocrm_crmentity.* FROM arocrm_journeyplan
	INNER JOIN arocrm_journeyplan_actual_bill_details_lineitem ON arocrm_journeyplan_actual_bill_details_lineitem.journeyplanid = arocrm_journeyplan.journeyplanid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_journeyplan.journeyplanid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_journeyplan.journeyplanid ='".$id."'");
	$countBill = mysql_num_rows($resultBill);
	$i = 1;
	while($rowBill = mysql_fetch_array($resultBill))
	{
		$tbodyBill .= '<tr id="Actual_Bill_Details__row_'.$i.'" class="tr_clone">';
			if($countBill == '1')
			{
				$tbodyBill .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			else
			{
				$tbodyBill .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}

			$tbodyBill .= '<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_3597_'.$i.'" type="date" class="form-control " data-fieldname="cf_3597" name="cf_3597_'.$i.'" readonly value="'.$rowBill['cf_3597'].'" data-rule-date="true"></div></td>
			<td class="fieldValue"><input id="cf_3599_'.$i.'" type="text" data-fieldname="cf_3599" data-fieldtype="string" class="inputElement " name="cf_3599_'.$i.'" readonly value="'.$rowBill['cf_3599'].'"></td>
			<td class="fieldValue"><input id="cf_3601_'.$i.'" type="text" data-fieldname="cf_3601" data-fieldtype="string" class="inputElement " name="cf_3601_'.$i.'" readonly value="'.$rowBill['cf_3601'].'"></td>
			<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3603_'.$i.'" class="inputElement " readonly name="cf_3603_'.$i.'">'.$rowBill['cf_3603'].'</textarea></td>
			<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3605_'.$i.'" class="inputElement " readonly name="cf_3605_'.$i.'">'.$rowBill['cf_3605'].'</textarea> </td>
			<td class="fieldValue"><input id="cf_3607_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3607_'.$i.'" readonly value="'.$rowBill['cf_3607'].'"></td>
			<td class="fieldValue"><input id="cf_2046_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2046_'.$i.'" readonly value="'.$rowBill['cf_2046'].'"></td>
			<td class="fieldValue"><input id="cf_2048_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2048_'.$i.'" readonly value="'.$rowBill['cf_2048'].'"></td>
			<td class="fieldValue"><input id="cf_2050_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2050_'.$i.'" readonly value="'.$rowBill['cf_2050'].'"></td>
			<td class="fieldValue"><input id="cf_2052_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2052_'.$i.'" readonly value="'.$rowBill['cf_2052'].'"></td>
			<td class="fieldValue"><input id="cf_2054_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2054_'.$i.'" readonly value="'.$rowBill['cf_2054'].'"></td>
			<td class="fieldValue"><input id="cf_2058_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2058_'.$i.'" readonly value="'.$rowBill['cf_2058'].'"></td>
			<td class="fieldValue"><input id="cf_2064_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2064_1" readonly value="'.$rowBill['cf_2064'].'"></td>
		</tr>';
		if($i != '1')
		{
			$rowcountBill = $rowcountBill.",".$i;
		}
		$i++;
	}

	$response['year'] = $year;
	$response['month'] = $month;
	$response['normal'] = $normal;
	$response['calamity'] = $calamity;
	$response['tbodyBasic'] = $tbodyBasic;
	$response['rowcountBasic'] = $rowcountBasic;
	$response['tbodyWorking'] = $tbodyWorking;
	$response['rowcountWorking'] = $rowcountWorking;
	$response['tbodyBill'] = $tbodyBill;
	$response['rowcountBill'] = $rowcountBill;
	return $response;
}

function getAssemblyLineItemforIBD($aid)
{
	$response = array();
	$cnt = array();
	$html = "";
	$result = mysql_query("SELECT arocrm_assemblyorder.*, arocrm_assemblyordercf.*, arocrm_crmentity.* FROM arocrm_assemblyorder
    INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_assemblyorder.assemblyorderid
	INNER JOIN arocrm_assemblyordercf on arocrm_assemblyordercf.assemblyorderid = arocrm_assemblyorder.assemblyorderid
    WHERE arocrm_crmentity.deleted = '0'
    AND arocrm_assemblyorder.assemblyorderid = '".$aid."' AND arocrm_assemblyordercf.cf_4933 = 'Approved'");
	$rowcount = mysql_num_rows($result);
	if($rowcount > 0)
	{
		$row = mysql_fetch_array($result);
		$plantid = $row['cf_nrl_plantmaster360_id'];
		$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster
      INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
      WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plantname = $rowplant['name'];
		$plantcode = $rowplant['plantmasterno'];

   $asssql = "SELECT * FROM `arocrm_assemblyorder`
INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_assemblyorder`.`assemblyorderid`
INNER JOIN `arocrm_assemblyorder_line_item_lineitem` ON `arocrm_assemblyorder_line_item_lineitem`.`assemblyorderid` = `arocrm_assemblyorder`.`assemblyorderid`
INNER JOIN `arocrm_bommaster` ON `arocrm_bommaster`.`bommasterid`=`arocrm_assemblyorder_line_item_lineitem`.`cf_3243`
INNER JOIN `arocrm_assembly` ON  `arocrm_assembly`.`cf_nrl_assemblyorder34_id` = `arocrm_assemblyorder`.`assemblyorderid`
INNER JOIN `arocrm_assembly_line_item_lineitem` ON  `arocrm_assembly_line_item_lineitem`.`assemblyid` =  `arocrm_assembly`.`assemblyid` AND `arocrm_assembly_line_item_lineitem`.`cf_3248` = `arocrm_bommaster`.`cf_nrl_products1000_id`
WHERE `arocrm_crmentity`.`deleted` = '0'
AND `arocrm_assembly_line_item_lineitem`.`cf_3254` = 'Completed'
AND `arocrm_assemblyorder`.`assemblyorderid` = '".$aid."'";


		$alisql = mysql_query($asssql);
		$i = 1;
    $numrw = mysql_num_rows($alisql);

		while($rowali = mysql_fetch_array($alisql))
		{
			$productid = $rowali['cf_3248'];
			$product_array = getProductDetails($productid);
			$productname = $product_array['productname'];
			$productcode = $rowali['cf_3250'];
			$productunit = $rowali['cf_3252'];
			$warranty = $product_array['warranty'];
			$qty =  number_format((float)$rowali['cf_3391'], 2, '.', '');
			$price = number_format((float)$product_array['unit_price'], 2, '.', '');
			$totalprice = number_format((float)$qty * $price, 2, '.', '');


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
<div class="referencefield-wrapper">
<input name="popupReferenceModule" type="hidden" value="StorageLocation" id="popupReferenceModule_'.$i.'">
<div class="input-group">
<input name="cf_2874_'.$i.'" type="hidden" value="" class="sourceField" data-displayvalue="" id="cf_2874_'.$i.'">
<input id="cf_2874_display_'.$i.'" name="cf_2874_display_'.$i.'" data-fieldname="cf_2874" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="" autocomplete="off">
<a href="#" class="clearReferenceSelection hide"> x </a>
<span class="input-group-addon relatedPopup cursorPointer" id="cf_2874_'.$i.',cf_2874_display_'.$i.'" title="Select">
<i id="'.$i.'" class="fa fa-search"></i>
</span>
</div>
<span class="createReferenceRecord cursorPointer clearfix" title="Create">
<i id="'.$i.'" class="fa fa-plus"></i>
</span>
</div>
</td>

<td class="fieldValue">
<input id="cf_2876_'.$i.'" style="min-width:80px;" type="number" class="inputElement" readonly  name="cf_2876_'.$i.'" value="'.$qty.'">
</td>

<td class="fieldValue">
<input id="cf_2878_'.$i.'" style="min-width:80px;" type="number" class="inputElement" min="1" readonly  name="cf_2878_'.$i.'" value="'.$qty.'">
<script>
jQuery("[name=cf_2878_'.$i.']").keyup(function(){
var qty = $("[name=cf_2880_'.$i.']").val();
var unit = $(this).val();
if(unit=="" || unit==undefined){
unit = 0;
}
$("[name=cf_2882_'.$i.']").val(parseFloat(qty) * parseFloat(productunit));
});
</script>
</td>

<td class="fieldValue">
	<input id="cf_5031_'.$i.'" type="text" style="min-width:110px;" data-fieldname="cf_5031" data-fieldtype="string" class="inputElement " name="cf_5031_'.$i.'" readonly value="'.$warranty.'">
	<input id="cf_2880_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2880_'.$i.'" value="'.$price.'">
	<input id="cf_2882_'.$i.'" style="min-width:80px;" type="hidden" readonly class="inputElement" step="0.01" name="cf_2882_'.$i.'" value="'.$totalprice.'">
</td>

<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2884_'.$i.'" type="date" class="form-control " data-fieldname="cf_2884" name="cf_2884_'.$i.'" value="" />
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

</tr>';
array_push($cnt, $i);
$i++;
		}
	}
	$count = implode(',',$cnt);
	$response['totalcount'] = $count;
	$response['tbody'] = $html;
	$response['plantid'] = $plantid;
	$response['plantname'] = $plantname;
	$response['plantcode'] = $plantcode;
	return $response;
}
function getAOItem($aoid)
{
	$response = array();
	$cnt = array();
	$html = "";
	$result = mysql_query("SELECT arocrm_assemblyorder.*, arocrm_crmentity.* FROM arocrm_assemblyorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_assemblyorder.assemblyorderid WHERE arocrm_crmentity.deleted = '0' AND arocrm_assemblyorder.assemblyorderid = '".$aoid."'");
	$rowcount = mysql_num_rows($result);
	if($rowcount > 0)
	{
		$row = mysql_fetch_array($result);
		$plantid = $row['cf_nrl_plantmaster360_id'];
		$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plant = $rowplant['name'];
		$alisql = mysql_query("SELECT * FROM `arocrm_assemblyorder_line_item_lineitem` WHERE assemblyorderid = '".$aoid."'");
		$i = 1;
		while($rowali = mysql_fetch_array($alisql))
		{
			$bomid = $rowali['cf_3243'];
			$bomqty = $rowali['cf_3245'];
			$bomsql = mysql_query("SELECT arocrm_bommaster.*, arocrm_crmentity.* FROM arocrm_bommaster INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_bommaster.bommasterid WHERE arocrm_crmentity.deleted = '0' AND arocrm_bommaster.bommasterid = '".$bomid."'");
			$rwcnt = mysql_num_rows($bomsql);
			if($rwcnt > 0)
			{
				$rowbom = mysql_fetch_array($bomsql);
				$productid = $rowbom['cf_nrl_products1000_id'];
				$product_array = getProductDetails($productid);
				$productname = $product_array['productname'];
				$productcode = $product_array['productcode'];
				$productunit = $product_array['unit'];

				$html .= '<tr id="Line_Item__row_'.$i.'" class="tr_clone">

<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Products"><div class="input-group"><input name="cf_3248_'.$i.'" type="hidden" value="'.$productid.'" class="sourceField" data-displayvalue=""><input id="cf_3248_display_'.$i.'" name="cf_3248_display_'.$i.'" data-fieldname="cf_3248" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'" placeholder="Type to search" autocomplete="off"><a href="#" class="clearReferenceSelection hide"> x </a><span class="input-group-addon relatedPopup cursorPointer" id="cf_3248_'.$i.',cf_3248_display_'.$i.'" title="Select"><i id="Assembly_editView_fieldName_cf_3248_select" class="fa fa-search"></i></span></div><span class="createReferenceRecord cursorPointer clearfix" title="Create"><i id="Assembly_editView_fieldName_cf_3248_create" class="fa fa-plus"></i></span></div></td>

<td class="fieldValue"><input id="cf_3250_'.$i.'" type="text" data-fieldname="cf_3250" data-fieldtype="string" class="inputElement " name="cf_3250_'.$i.'" value="'.$productcode.'"></td>

<td class="fieldValue"><input id="cf_3252_'.$i.'" type="text" data-fieldname="cf_3252" data-fieldtype="string" class="inputElement " name="cf_3252_'.$i.'" value="'.$productunit.'"></td>

<td class="fieldValue"><input id="cf_3391_'.$i.'" type="number" class="inputElement" style="min-width:80px;" name="cf_3391_'.$i.'" value="'.$bomqty.'"></td>

<td class="fieldValue"><select data-fieldname="cf_3254" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3254_'.$i.'" data-selected-value="In-Progress" tabindex="-1" title="" id="cf_3254_'.$i.'"><option value="">Select an Option</option><option value="In-Progress" selected="">In-Progress</option><option value="Completed">Completed</option></select>
				<script>
					$(document).ready(function(){
					$("#cf_3254_'.$i.'").select2();
					});
				</script>

</td>

</tr>';
			}
			array_push($cnt, $i);
			$i++;
		}
	}
	$count = implode(',',$cnt);
	$response['totalcount'] = $count;
	$response['tbody'] = $html;
	$response['plantid'] = $plantid;
	$response['plant'] = $plant;
	return $response;
}


function getAOLineItemforOBD($aoid)
{
	$response = array();
	$cnt = array();
	$bomarr = array();
	$html = '';
	$result = mysql_query("SELECT arocrm_assemblyorder.*, arocrm_crmentity.* FROM arocrm_assemblyorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_assemblyorder.assemblyorderid WHERE arocrm_crmentity.deleted = '0' AND arocrm_assemblyorder.assemblyorderid = '".$aoid."'");
	$rowcount = mysql_num_rows($result);
	if($rowcount > 0)
	{
		$rw = mysql_fetch_array($result);
		$plantid = $rw['cf_nrl_plantmaster360_id'];
		$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid WHERE arocrm_crmentity.deleted = '0' AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plant = $rowplant['name'];
		
		$storesql =  mysql_query("SELECT `arocrm_storagelocation`.* FROM `arocrm_storagelocation`
 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid=arocrm_storagelocation.storagelocationid
 WHERE `arocrm_crmentity`.`deleted` = 0 AND `arocrm_storagelocation`.`cf_nrl_plantmaster561_id` = '".$plantid."' AND `arocrm_storagelocation`.`name` LIKE '%Main Store%'");
$resultstore = mysql_fetch_array($storesql);

		$alisql = mysql_query("SELECT * FROM `arocrm_assemblyorder_line_item_lineitem` WHERE assemblyorderid = '".$aoid."'");
		while($rowali = mysql_fetch_array($alisql))
		{
			$bomid = $rowali['cf_3243'];
			array_push($bomarr, $bomid);
		}
				$bom = implode(',',$bomarr);
				$bomli = mysql_query("SELECT `arocrm_bommaster_bom_lineitem_lineitem`.`cf_3009`, `cf_3011`, `cf_3231`, SUM(`arocrm_bommaster_bom_lineitem_lineitem`.`cf_3233`*`arocrm_assemblyorder_line_item_lineitem`.`cf_3245`) AS qty FROM `arocrm_bommaster` INNER JOIN `arocrm_assemblyorder_line_item_lineitem` on `arocrm_assemblyorder_line_item_lineitem`.`cf_3243` = `arocrm_bommaster`.`bommasterid` INNER JOIN `arocrm_crmentity` on `arocrm_crmentity`.`crmid` = `arocrm_bommaster`.`bommasterid` INNER JOIN `arocrm_bommaster_bom_lineitem_lineitem` on `arocrm_bommaster_bom_lineitem_lineitem`.`bommasterid` = `arocrm_bommaster`.`bommasterid` WHERE `arocrm_bommaster`.`bommasterid` IN (".$bom.") AND `arocrm_assemblyorder_line_item_lineitem`.`assemblyorderid` = '".$aoid."' GROUP BY `cf_3009`");
				$i = 1;
				while($rowbom = mysql_fetch_array($bomli))
				{
					$productid = $rowbom['cf_3009'];
					$product_array = getProductDetails($productid);
					$productname = $product_array['productname'];
					$listprice = number_format((float)$product_array['unit_price'], 2, '.', '');
					$item_description = $product_array['description'];
					$productcode = $rowbom['cf_3011'];
					$productunit = $rowbom['cf_3231'];
					$qty = number_format((float)$rowbom['qty'], 2, '.', '');
					$total_amount = number_format((float)$qty * $listprice, 2, '.', '');
					$html .= '<tr id="Line_Item__row_'.$i.'" class="tr_clone">

<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>

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
<input name="cf_2010_'.$i.'" type="hidden" value="'.$resultstore['storagelocationid'].'" class="sourceField" data-displayvalue="" id="cf_2010_'.$i.'">
<input id="cf_2010_display_'.$i.'" name="cf_2010_display_'.$i.'" data-fieldname="cf_2010" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$resultstore['name'].'" placeholder="Type to search" autocomplete="off">
</div>
</td>

<td class="fieldValue">
<input id="cf_2012_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_2012_'.$i.'" value="'.$qty.'" />
</td>

<td class="fieldValue">
<input id="cf_2014_'.$i.'" style="min-width:80px;" readonly type="number" class="inputElement" step="0.01" name="cf_2014_'.$i.'" value="'.$qty.'" max="'.$qty.'" />
<input id="cf_2020_'.$i.'" style="min-width:80px;" readonly type="hidden" class="inputElement" step="0.01" name="cf_2020_'.$i.'" value="'.$listprice.'">
<input id="cf_4925_'.$i.'" style="min-width:80px;" type="hidden" class="inputElement" step="0.01" name="cf_4925_'.$i.'" value="'.$reqty*$listprice.'">
</td>

<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2026_'.$i.'" type="date" class="form-control " data-fieldname="cf_2026" name="cf_2026_'.$i.'" value="'.date('Y-m-d').'" data-rule-date="true">
</div>
</td>

<td class="fieldValue">
<div class="input-group inputElement" style="margin-bottom: 3px">
<input id="cf_2022_'.$i.'" type="date" class="form-control" data-fieldname="cf_2022" name="cf_2022_'.$i.'" value="" data-rule-date="true">
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


				array_push($cnt, $i);
				$i++;
				}

	}
	$count = implode(',',$cnt);
	$response['totalcount'] = $count;
	$response['plantid'] = $plantid;
	$response['plant'] = $plant;
	$response['tbody'] = $html;
	return $response;
}



function getProductDetailsfromSO($soid)
{
	$response = array();
	$html = '';
	$message = "";
	$amount = 0;
	$savestatestatus = 0;
	$detailsql = mysql_query("SELECT arocrm_inventoryproductrel.* FROM arocrm_inventoryproductrel 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_inventoryproductrel.id = '".$soid."'");
	$tax1 = 0;
	$tax2 = 0;
	$tax3 = 0;
	while($detailrow = mysql_fetch_array($detailsql))
	{
		$discountpercent = $detailrow['discount_percent'];
		$discountamount = $detailrow['discount_amount'];
		$comment = $detailrow['comment'];
		//$deldate = $detailrow['delivery_date'];
		$tax1 = $detailrow['tax1'];
		$tax2 = $detailrow['tax2'];
		$tax3 = $detailrow['tax3'];
	}
	$advanceval = 0;
	$debitval = 0;
	$creditval = 0;
	$soall = mysql_query("SELECT arocrm_salesorder.*, arocrm_salesordercf.* FROM arocrm_salesorder 
	INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_salesorder.salesorderid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_salesorder.salesorderid = '".$soid."' AND arocrm_salesorder.sostatus = 'Approved'");
	$rowall = mysql_fetch_array($soall);
	$schemediscount = $rowall['schemediscount'];
	if($schemediscount == "" || $schemediscount == null)
	{
		$schemediscount = 0;
	}
	$accountid = $rowall['accountid'];
	$category = $rowall['cf_4537'];
	$taxregion = $rowall['region_id'];
	$currency = $rowall['currency_id'];
	$taxtype = $rowall['taxtype'];
	$advance = $rowall['advancepaymentid'];
		$advancearr = explode(",",$advance);
		$advancecnt = count($advancearr);
		for($i=0;$i<$advancecnt;$i++)
		{
			$advancesql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$advancearr[$i]."'");
			$rowadvance = mysql_fetch_array($advancesql);
			$advanceval = $advanceval + $rowadvance['cf_3342'];
		}
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4697'];
		}
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4697'];
		}			
		
		$paymentid = array();
		$paymentname = array();
		$paymentval = array();
		$sqladv = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
		INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpaymentcf.cf_3335 ='Advance Payment' AND arocrm_customerpaymentcf.cf_3376 ='Approved'");
		$advancenumrow = mysql_num_rows($sqladv);
		while($rowadv = mysql_fetch_array($sqladv))
		{
			array_push($paymentid,$rowadv['customerpaymentid']);
			array_push($paymentname,$rowadv['name']);
			array_push($paymentval,$rowadv['cf_3342']);
		}
		$debitpaymentid = array();
		$debitpaymentname = array();
		$debitpaymentval = array();
		$sqldebit = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
		INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpaymentcf.cf_3335 ='Debit Note' AND arocrm_customerpaymentcf.cf_3376 ='Approved'");
		$debitnumrow = mysql_num_rows($sqldebit);
		while($rowdebit = mysql_fetch_array($sqldebit))
		{
			array_push($debitpaymentid,$rowdebit['customerpaymentid']);
			array_push($debitpaymentname,$rowdebit['name']);
			array_push($debitpaymentval,$rowdebit['cf_4697']);
		}
		$creditpaymentid = array();
		$creditpaymentname = array();
		$creditpaymentval = array();
	$sqlcredit = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
		INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpaymentcf.cf_3335 ='Credit Note' AND arocrm_customerpaymentcf.cf_3376 ='Approved'");
		$creditnumrow = mysql_num_rows($sqlcredit);
		while($rowcredit = mysql_fetch_array($sqlcredit))
		{
			array_push($creditpaymentid,$rowcredit['customerpaymentid']);
			array_push($creditpaymentname,$rowcredit['name']);
			array_push($creditpaymentval,$rowcredit['cf_4697']);
		}
	$html .='<tr><td><strong>TOOLS</strong></td><td><span class="redColor">*</span><strong>Item Name</strong></td><td><strong class="pull-right">Item code</strong></td><td><strong class="pull-right">Unit</strong></td><td><strong>Quantity</strong></td><td><strong class="pull-right" style="float:left!important;">Serial Number</strong></td><td><strong>List Price</strong></td><td><strong>Delievry Date</strong></td><td><strong class="pull-right">Total</strong></td><td><strong class="pull-right">Net Price</strong></td></tr>';
	$grid = array();
	$res = mysql_query("SELECT arocrm_goodsissue.goodsissueid FROM arocrm_outbounddelivery 
	INNER JOIN arocrm_goodsissue on arocrm_goodsissue.cf_nrl_outbounddelivery617_id = arocrm_outbounddelivery.outbounddeliveryid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_outbounddelivery.outbounddeliveryid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_outbounddelivery.cf_nrl_salesorder679_id = '".$soid."' 
	AND arocrm_goodsissue.goodsissueid IN (SELECT arocrm_goodsissuecf.goodsissueid FROM arocrm_goodsissuecf 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsissuecf.goodsissueid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_goodsissuecf.cf_4927 = 'Not Done')");
	$reschk = mysql_num_rows($res);
	while($resrow = mysql_fetch_array($res))
	{
		array_push($grid,$resrow['goodsissueid']);
    }
	$gr = implode(",",$grid);
    $sqlcstdata = mysql_query("SELECT * FROM `arocrm_account`
    INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_account`.`accountid`
    INNER JOIN `arocrm_accountscf` ON `arocrm_accountscf`.`accountid` = `arocrm_account`.`accountid`
    WHERE `arocrm_account`.`accountid` = (SELECT `accountid` FROM `arocrm_salesorder` WHERE `salesorderid` = '".$soid."') AND `arocrm_crmentity`.`deleted` = '0'");
    $sqlcstdataarr = mysql_fetch_array($sqlcstdata);

    $creditlimit = $sqlcstdataarr['cf_4313'];
    $creditdays = $sqlcstdataarr['cf_4315'];
	$custgst = $sqlcstdataarr['cf_3416'];
	$custpan = $sqlcstdataarr['cf_3414'];
    if($creditlimit > 0 && $creditdays > 0){
    $savestatestatus = 1;
    }

	if($reschk>0)
	{
		$result = mysql_query("SELECT `arocrm_goodsissue`.`cf_nrl_plantmaster280_id`, `arocrm_goodsissue_line_item_lineitem`.`cf_3163`, `arocrm_goodsissue_line_item_lineitem`.`cf_3179`, `arocrm_goodsissue_line_item_lineitem`.`cf_3175`, SUM(`arocrm_goodsissue_line_item_lineitem`.`cf_3171`) AS totalqty, `arocrm_goodsissuecf`.`cf_3229` FROM `arocrm_goodsissue` 
		INNER JOIN `arocrm_crmentity` on `arocrm_crmentity`.`crmid` = `arocrm_goodsissue`.`goodsissueid`
		INNER JOIN `arocrm_goodsissuecf` ON `arocrm_goodsissuecf`.`goodsissueid` = `arocrm_goodsissue`.`goodsissueid`
		INNER JOIN `arocrm_goodsissue_line_item_lineitem` ON `arocrm_goodsissue_line_item_lineitem`.`goodsissueid` = `arocrm_goodsissue`.`goodsissueid`
		WHERE `arocrm_crmentity`.`deleted` = '0' AND `arocrm_goodsissue`.`goodsissueid` in (".$gr.") AND `arocrm_goodsissuecf`.`cf_4834` = 'Approved' 
		GROUP BY arocrm_goodsissue_line_item_lineitem.cf_3163");
		$i = 1;
		$total_amount = 0;
		
			
		while($row = mysql_fetch_array($result))
		{
			$salesids = $soid;
			$productid = $row['cf_3163'];
			
			$listsql = mysql_fetch_array(mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$salesids."' AND `productid` = '".$productid."'"));
			$qty = $row['totalqty'];
			$listprice = $listsql['listprice'];
			$serialno = $row['cf_3179'];
			$deldate = $row['cf_3229'];
			$plant = $row['cf_nrl_plantmaster280_id'];
			$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
			INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plant."'");
			$rowplant = mysql_fetch_array($plantsql);
		    $plantname = $rowplant['name'];
			//$total_amount = $detailrow['margin'];
			$totalamnt = number_format((float)$qty*$listprice,2,'.','');
			$total_amount = number_format((float)$total_amount + $totalamnt,2,'.','');
			$product_array = getProductDetails($productid);
			$productname = $product_array['productname'];
			$item_description = $product_array['description'];
			$productunit = $product_array['unit'];
			$productcode = $product_array['productcode'];
			$html .= '<tr id="row'.$i.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$i.'">
						<td style="text-align:center;">
	<i class="fa fa-trash deleteRow cursorPointer" title="Delete" style="display: none;"></i>&nbsp;
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
	<input id="qty'.$i.'" name="qty'.$i.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" data-rule-positive="true" data-rule-greater_than_zero="true" value="'.$qty.'" aria-required="true" max="'.$qty.'">
	<input type="hidden" name="margin'.$i.'" value="0">
	<span class="margin pull-right" style="display:none"></span></td>
	<td><textarea id="serialno'.$i.'" name="serialno'.$i.'" readonly class="serialno inputElement">'.$serialno.'</textarea></td>
	<td><div>
	<input id="listPrice'.$i.'" readonly name="listPrice'.$i.'" value="'.$listprice.'" type="text" data-rule-required="true" data-rule-positive="true" class="listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true">&nbsp;</div>
	<div style="clear:both"></div>
	<div><span>(-)&nbsp;<strong><a href="javascript:void(0)" class="individualDiscount">Discount<span class="itemDiscount">(0)</span></a> : </strong></span></div>
	<div class="discountUI validCheck hide" id="discount_div'.$i.'">
	<input type="hidden" id="discount_type'.$i.'" name="discount_type'.$i.'" value="zero" class="discount_type">
	<p class="popover_title hide">Set Discount For : <span class="variable"></span></p>';
	if($discountpercent != '')
	{
	$html .='<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
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
	</table>';
	}
	else
	{
		$html .='<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table table-nobordered popupTable">
	<tbody>
	<tr>
	<td>
	<input type="radio" name="discount'.$i.'" class="discounts" data-discount-type="zero">&nbsp;Zero Discount
	</td>
	<td>
	<input type="hidden" class="discountVal" value="0"></td>
	</tr>
	<tr>
	<td>
	<input type="radio" name="discount'.$i.'" checked="true" class="discounts" data-discount-type="percentage">&nbsp; %Price
	</td>
	<td>
	<span class="pull-right">&nbsp;%</span>
	<input type="text" data-rule-positive="true" data-rule-inventory_percentage="true" id="discount_percentage'.$i.'" name="discount_percentage'.$i.'" value="'.$discountpercent.'" class="discount_percentage span'.$i.' pull-right discountVal hide">
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
	</table>';
	}
	$html .='</div>
	<div style="width:150px;"><strong>Total After Discount :</strong></div>
	<div class="individualTaxContainer hide">(+)&nbsp;<strong><a href="javascript:void(0)" class="individualTax">Tax </a> : </strong></div>
	<span class="taxDivContainer">
	<div class="taxUI hide" id="tax_div'.$i.'">
	<p class="popover_title hide">Set Tax for : <span class="variable"></span></p>
	</div>
	</span>
	</td>

	<td>
	<input id="delivery_date'.$i.'" value="'.$deldate.'" name="delivery_date'.$i.'" type="date" class="delivery_date inputElement" />
	</td>
	<td>
	<div id="productTotal'.$i.'" align="right" class="productTotal">'.$totalamnt.'</div>';
	if($discountpercent != '')
	{
	$html .='<div id="discountTotal'.$i.'" align="right" class="discountTotal">0.00</div>
	<div id="totalAfterDiscount'.$i.'" align="right" class="totalAfterDiscount">'.$totalamnt.'</div>
	<div id="taxTotal'.$i.'" align="right" class="productTaxTotal hide">0.00</div>
	</td>
	<td>
	<span id="netPrice'.$i.'" class="pull-right netPrice">'.$totalamnt.'</span>
	</td>
	</tr>';
	}
	else
	{
		$discountamount = ($totalamnt * $discountpercent)/100;
		$afterdiscount = $totalamnt - $discountamount;
		$html .='<div id="discountTotal'.$i.'" align="right" class="discountTotal">'.$discountamount.'</div>
		<div id="totalAfterDiscount'.$i.'" align="right" class="totalAfterDiscount">'.$afterdiscount.'</div>
		<div id="taxTotal'.$i.'" align="right" class="productTaxTotal hide">0.00</div>
		</td>
		<td>
		<span id="netPrice'.$i.'" class="pull-right netPrice">'.$afterdiscount.'</span>
		</td>
		</tr>';
	}
	

							  $i++;
	}
	$totalrow = $i - 1;
		$sql = mysql_query("SELECT arocrm_salesorder.*, arocrm_salesordercf.*, arocrm_crmentity.* FROM arocrm_salesorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
		INNER JOIN arocrm_salesordercf on arocrm_salesordercf.salesorderid = arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = '0' AND arocrm_salesorder.salesorderid = '".$soid."'");
		$rowqry = mysql_fetch_array($sql);
		$discountallow = $rowqry['cf_5195'];
		$discountapply = $rowqry['cf_5207'];
		$reference = $rowqry['cf_3286'];
		$contactid = $rowqry['contactid'];
		$adjust = $rowqry['adjustment'];
		if($adjust == "")
		{
			$adjusticon = "+";
			$adjustval = 0;
		}
		else
		{
			$adjusticon = substr($adjust,0,1);
			$adjustval = substr($adjust,1);
		}
		$tax1val = ($tax1 * $total_amount)/100; 
		$tax2val = ($tax2 * $total_amount)/100; 
		$tax3val = ($tax3 * $total_amount)/100;
		$totaltaxval = $tax1val + $tax2val + $tax3val;
		if($adjusticon == '+')
		{
			$alltotal = number_format((float)((((($total_amount + $totaltaxval)- $advanceval) + $debitval) - $creditval) - $schemediscount) + $adjustval,2,'.','');
		}
		else
		{
			$alltotal = number_format((float)((((($total_amount + $totaltaxval)- $advanceval) + $debitval) - $creditval) - $schemediscount) - $adjustval,2,'.','');
		}
		$subtotal = $total_amount;
		$assignedto = $rowqry['smownerid'];
		$customer = $rowqry['accountid'];
		$cussql = mysql_query("SELECT arocrm_account.*, arocrm_crmentity.* FROM arocrm_account INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$customer."'");
		$cusrow = mysql_fetch_array($cussql);
		$accname = $cusrow['accountname'];
		$custno = $cusrow['account_no'];
		$contactsql = mysql_query("SELECT arocrm_contactdetails.* FROM arocrm_contactdetails 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_contactdetails.contactid 
	WHERE arocrm_crmentity.deleted = 0 AND arocrm_contactdetails.contactid ='".$contactid."'");
	$contactrow = mysql_fetch_array($contactsql);
	$contactname = $contactrow['firstname']." ".$contactrow['lastname'];
	
		$tc = $rowqry['terms_conditions'];
	}
	else
	{
		$message = "Goods not issued against Sales Order";
	}
	$response['totalrow'] = $totalrow;
	$response['schemediscount'] = $schemediscount;
	$response['reference'] = $reference;
	$response['discountallow'] = $discountallow;
	$response['discountapply'] = $discountapply;
	$response['taxregion'] = $taxregion;
	$response['currency'] = $currency;
	$response['taxtype'] = $taxtype;
	$response['assignedto'] = $assignedto;
	$response['totalcount'] = $i - 1;
	$response['gst'] = $custgst;
	$response['pan'] = $custpan;
	$response['category'] = $category;
	$response['alltotal'] = $alltotal;
	$response['subtotal'] = $subtotal;
	$response['tc'] = $tc;
	$response['adjusticon'] = $adjusticon;
	$response['adjustval'] = $adjustval;
	$response['tax'] = $taxtotal;
	$response['customer'] = $customer;
	$response['custno'] =$custno;
	$response['accname'] = $accname;
	$response['contactid'] = $contactid;
	$response['contactname'] = $contactname;
	$response['plantid'] = $plant;
	$response['plantname'] = $plantname;
	$response['html'] = $html;
	$response['message'] = $message;
	$response['savestatestatus'] = $savestatestatus;
	$response['paymentid'] = $paymentid;
	$response['paymentname'] = $paymentname;
	$response['paymentval'] = $paymentval;
	$response['paymentlength'] = $advancenumrow;
	$response['debitpaymentid'] = $debitpaymentid;
	$response['debitpaymentname'] = $debitpaymentname;
	$response['debitpaymentval'] = $debitpaymentval;
	$response['debitpaymentlength'] = $debitnumrow;
	$response['creditpaymentid'] = $creditpaymentid;
	$response['creditpaymentname'] = $creditpaymentname;
	$response['creditpaymentval'] = $creditpaymentval;
	$response['creditpaymentlength'] = $creditnumrow;
	$response['advance'] = $advanceval;
	$response['debit'] = $debitval;
	$response['credit'] = $creditval;
	$response['tax1'] = $tax1;
	$response['tax2'] = $tax2;
	$response['tax3'] = $tax3;
	$response['totaltaxval'] = $totaltaxval;
	return $response;
}



function getContact($customerid)
{
	$response = array();
	$cussql = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*,arocrm_crmentity.* FROM arocrm_account 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid 
	INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
	WHERE arocrm_crmentity.deleted='0' AND arocrm_account.accountid='".$customerid."'");
	$cusrow = mysql_fetch_array($cussql);
	$custno = $cusrow['account_no'];
	$custgst = $cusrow['cf_3416'];
	$custpan = $cusrow['cf_3414'];
	$contactsql = mysql_query("SELECT arocrm_contactdetails.* FROM arocrm_contactdetails INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_contactdetails.contactid WHERE arocrm_crmentity.deleted='0' AND arocrm_contactdetails.accountid='".$customerid."'");
	$contactrow = mysql_num_rows($contactsql);
	if($contactrow == '1')
	{
		$contactrow = mysql_fetch_array($contactsql);
		$contactid = $contactrow['contactid'];
		$contactname = $contactrow['firstname']." ".$contactrow['lastname'];
	}
	$response['custno'] = $custno;
	$response['custgst'] = $custgst;
	$response['custpan'] = $custpan;
	$response['contactid'] = $contactid;
	$response['contactname'] = $contactname;
	return $response;
}
function getInvoiceCredit($invoiceid)
{
	$response = array();
	$creditval = 0;
	$invall = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
	$rowall = mysql_fetch_array($invall);
	$soid = $rowall['salesorderid'];
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4697'];
		}			
		
		$soall = mysql_query("SELECT arocrm_salesorder.* FROM arocrm_salesorder
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
					WHERE arocrm_salesorder.salesorderid = '".$soid."'");
		$rowall = mysql_fetch_array($soall);
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4697'];
		}		
	$response['creditval'] = $creditval;
	return $response;
}
function getInvoiceDebit($invoiceid)
{
	$response = array();
	$debitval = 0;
	$invall = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
	$rowall = mysql_fetch_array($invall);
	$grid = $rowall['cf_nrl_goodsreceipt721_id'];
	$debit = $rowall['debitpaymentid'];
	$debitarr = explode(",",$debit);
	$debitcnt = count($debitarr);
	for($i=0;$i<$debitcnt;$i++)
	{
		$debitsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$debitarr[$i]."'");
		$rowdebit = mysql_fetch_array($debitsql);
		$debitval = $debitval + $rowdebit['cf_4705'];
	}
	$posql = mysql_query("SELECT arocrm_inbounddelivery.cf_nrl_purchaseorder573_id FROM arocrm_inbounddelivery 
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
						WHERE arocrm_inbounddelivery.inbounddeliveryid IN (SELECT arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id FROM arocrm_goodsreceipt 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid
		WHERE arocrm_goodsreceipt.goodsreceiptid = '".$grid."')");
		$rowpo = mysql_fetch_array($posql);
		$po = $rowpo['cf_nrl_purchaseorder573_id'];
		$poall = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_purchaseorder.purchaseorderid = '".$po."'");
		$rowall = mysql_fetch_array($poall);
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4705'];
		}
		$response['debitval'] = $debitval;
		return $response;
}
function purchasePaymentInvoiceDetails($invoiceid, $grid, $soid, $type)
{
	$response = array();
	$advanceval = 0;
	$debitval = 0;
	$creditval = 0;
	$invall = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
	$rowall = mysql_fetch_array($invall);
	if($type == "Purchase Invoice")
	{
		$advance = $rowall['advancepaymentid'];
		$advancearr = explode(",",$advance);
		$advancecnt = count($advancearr);
		for($i=0;$i<$advancecnt;$i++)
		{
			$advancesql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$advancearr[$i]."'");
			$rowadvance = mysql_fetch_array($advancesql);
			$advanceval = $advanceval + $rowadvance['cf_3302'];
		}
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4705'];
		}
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4705'];
		}
		$posql = mysql_query("SELECT arocrm_inbounddelivery.cf_nrl_purchaseorder573_id FROM arocrm_inbounddelivery 
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
						WHERE arocrm_inbounddelivery.inbounddeliveryid IN (SELECT arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id FROM arocrm_goodsreceipt 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid
		WHERE arocrm_goodsreceipt.goodsreceiptid = '".$grid."')");
		$rowpo = mysql_fetch_array($posql);
		$po = $rowpo['cf_nrl_purchaseorder573_id'];
		$poall = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_purchaseorder.purchaseorderid = '".$po."'");
		$rowall = mysql_fetch_array($poall);
		$advance = $rowall['advancepaymentid'];
		$advancearr = explode(",",$advance);
		$advancecnt = count($advancearr);
		for($i=0;$i<$advancecnt;$i++)
		{
			$advancesql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$advancearr[$i]."'");
			$rowadvance = mysql_fetch_array($advancesql);
			$advanceval = $advanceval + $rowadvance['cf_3302'];
		}
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4705'];
		}
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
			INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
			INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
			WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4705'];
		}
	}
	else
	{
		$advance = $rowall['advancepaymentid'];
		$advancearr = explode(",",$advance);
		$advancecnt = count($advancearr);
		for($i=0;$i<$advancecnt;$i++)
		{
			$advancesql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$advancearr[$i]."'");
			$rowadvance = mysql_fetch_array($advancesql);
			$advanceval = $advanceval + $rowadvance['cf_3342'];
		}
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4697'];
		}
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4697'];
		}			
		
		$soall = mysql_query("SELECT arocrm_salesorder.* FROM arocrm_salesorder
					INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
					WHERE arocrm_salesorder.salesorderid = '".$soid."'");
		$rowall = mysql_fetch_array($soall);
		$accountid = $rowall['accountid'];
		$advance = $rowall['advancepaymentid'];
		$advancearr = explode(",",$advance);
		$advancecnt = count($advancearr);
		for($i=0;$i<$advancecnt;$i++)
		{
			$advancesql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$advancearr[$i]."'");
			$rowadvance = mysql_fetch_array($advancesql);
			$advanceval = $advanceval + $rowadvance['cf_3342'];
		}
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4697'];
		}
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4697'];
		}		
	}
	$response['advanceval'] = $advanceval;
	$response['debitval'] = $debitval;
	$response['creditval'] = $creditval;
	return $response;
	
}
function salesPaymentDetails($so)
{
	$response = array();
	$soall = mysql_query("SELECT arocrm_salesorder.* FROM arocrm_salesorder 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_salesorder.salesorderid = '".$so."'");
	$rowall = mysql_fetch_array($soall);
	$accountid = $rowall['accountid'];
	$advance = $rowall['advancepaymentid'];
		$advancearr = explode(",",$advance);
		$advancecnt = count($advancearr);
		for($i=0;$i<$advancecnt;$i++)
		{
			$advancesql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$advancearr[$i]."'");
			$rowadvance = mysql_fetch_array($advancesql);
			$advanceval = $advanceval + $rowadvance['cf_3342'];
		}
		$debit = $rowall['debitpaymentid'];
		$debitarr = explode(",",$debit);
		$debitcnt = count($debitarr);
		for($i=0;$i<$debitcnt;$i++)
		{
			$debitsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$debitarr[$i]."'");
			$rowdebit = mysql_fetch_array($debitsql);
			$debitval = $debitval + $rowdebit['cf_4697'];
		}
		$credit = $rowall['creditpaymentid'];
		$creditarr = explode(",",$credit);
		$creditcnt = count($creditarr);
		for($i=0;$i<$creditcnt;$i++)
		{
			$creditsql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
				INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
				INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
				WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.customerpaymentid = '".$creditarr[$i]."'");
			$rowcredit = mysql_fetch_array($creditsql);
			$creditval = $creditval + $rowcredit['cf_4697'];
		}
	$response['advanceval'] = $advanceval;
	$response['debitval'] = $debitval;
	$response['creditval'] = $creditval;
	return $response;
}
function purchasePaymentDetails($po)
{
	$response = array();
	$advanceval = 0;
	$debitval = 0;
	$creditval = 0;
	$poall = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_purchaseorder.purchaseorderid = '".$po."'");
	$rowall = mysql_fetch_array($poall);
	$advance = $rowall['advancepaymentid'];
	$advancearr = explode(",",$advance);
	$advancecnt = count($advancearr);
	for($i=0;$i<$advancecnt;$i++)
	{
		$advancesql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$advancearr[$i]."'");
		$rowadvance = mysql_fetch_array($advancesql);
		$advanceval = $advanceval + $rowadvance['cf_3302'];
	}
	$debit = $rowall['debitpaymentid'];
	$debitarr = explode(",",$debit);
	$debitcnt = count($debitarr);
	for($i=0;$i<$debitcnt;$i++)
	{
		$debitsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$debitarr[$i]."'");
		$rowdebit = mysql_fetch_array($debitsql);
		$debitval = $debitval + $rowdebit['cf_4705'];
	}
	$credit = $rowall['creditpaymentid'];
	$creditarr = explode(",",$credit);
	$creditcnt = count($creditarr);
	for($i=0;$i<$creditcnt;$i++)
	{
		$creditsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$creditarr[$i]."'");
		$rowcredit = mysql_fetch_array($creditsql);
		$creditval = $creditval + $rowcredit['cf_4705'];
	}
	
	$response['advanceval'] = $advanceval;
	$response['debitval'] = $debitval;
	$response['creditval'] = $creditval;
	return $response;
}
function getProductDetailsfromGR($grid)
{
	$response = array();
	$html = '';
	$message = "";
	$amount = 0;
	$html .='<tr><td><strong>TOOLS</strong></td><td><span class="redColor">*</span><strong>Item Name</strong></td><td><strong class="pull-right">Item code</strong></td><td><strong class="pull-right">Unit</strong></td><td><strong>Released Quantity</strong></td><td><strong>Blocked Quantity</strong></td><td><strong>Total Quantity</strong></td><td><strong>Serial Number</strong></td><td><strong>List Price</strong></td><td><strong>Delievery Date</strong></td><td><strong class="pull-right">Total</strong></td><td><strong class="pull-right">Net Price</strong></td></tr>';
	$posql = mysql_query("SELECT cf_nrl_purchaseorder573_id FROM arocrm_inbounddelivery WHERE arocrm_inbounddelivery.inbounddeliveryid in(SELECT cf_nrl_inbounddelivery708_id FROM `arocrm_goodsreceipt` WHERE goodsreceiptid = '".$grid."')");
	$rowpo = mysql_fetch_array($posql);
	$po = $rowpo['cf_nrl_purchaseorder573_id'];
	$tax1 = 0;
	$tax2 = 0;
	$tax3 = 0;
	$potax = mysql_query("SELECT * FROM `arocrm_inventoryproductrel` WHERE `id` = '".$po."'");
	while($rowtax = mysql_fetch_array($potax))
	{
		$tax1 = $rowtax['tax1'];
		$tax2 = $rowtax['tax2'];
		$tax3 = $rowtax['tax3'];
	}
	$advanceval = 0;
	$debitval = 0;
	$creditval = 0;
	$poall = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_purchaseorder.purchaseorderid = '".$po."'");
	$rowall = mysql_fetch_array($poall);
	$poname = $rowall['subject'];
	$itemtotal = $rowall['subtotal'];
	//$grandtotal = $rowall['total'];
	$vendorid = $rowall['vendorid'];
	$taxregion = $rowall['region_id'];
	$currency = $rowall['currency_id'];
	$taxtype = $rowall['taxtype'];
	$adjust = $rowall['adjustment'];
		if($adjust == "")
		{
			$adjusticon = "+";
			$adjustval = 0;
		}
		else
		{
			$adjusticon = substr($adjust,0,1);
			$adjustval = substr($adjust,1);
		}
	$advance = $rowall['advancepaymentid'];
	$advancearr = explode(",",$advance);
	$advancecnt = count($advancearr);
	for($i=0;$i<$advancecnt;$i++)
	{
		$advancesql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$advancearr[$i]."'");
		$rowadvance = mysql_fetch_array($advancesql);
		$advanceval = $advanceval + $rowadvance['cf_3302'];
	}
	$debit = $rowall['debitpaymentid'];
	$debitarr = explode(",",$debit);
	$debitcnt = count($debitarr);
	for($i=0;$i<$debitcnt;$i++)
	{
		$debitsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$debitarr[$i]."'");
		$rowdebit = mysql_fetch_array($debitsql);
		$debitval = $debitval + $rowdebit['cf_4705'];
	}
	$credit = $rowall['creditpaymentid'];
	$creditarr = explode(",",$credit);
	$creditcnt = count($creditarr);
	for($i=0;$i<$creditcnt;$i++)
	{
		$creditsql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.vendorpaymentid = '".$creditarr[$i]."'");
		$rowcredit = mysql_fetch_array($creditsql);
		$creditval = $creditval + $rowcredit['cf_4705'];
	}

		$paymentid = array();
		$paymentname = array();
		$paymentval = array();
		$sql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 ='Advance Payment' AND arocrm_vendorpaymentcf.cf_4699 ='Approved'");
		$advancenumrow = mysql_num_rows($sql);
		while($row = mysql_fetch_array($sql))
		{
			array_push($paymentid,$row['vendorpaymentid']);
			array_push($paymentname,$row['name']);
			array_push($paymentval,$row['cf_3302']);
		}
		$debitpaymentid = array();
		$debitpaymentname = array();
		$debitpaymentval = array();
		$sqldebit = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 ='Debit Note' AND arocrm_vendorpaymentcf.cf_4699 ='Approved'");
		$debitnumrow = mysql_num_rows($sqldebit);
		while($rowdebit = mysql_fetch_array($sqldebit))
		{
			array_push($debitpaymentid,$rowdebit['vendorpaymentid']);
			array_push($debitpaymentname,$rowdebit['name']);
			array_push($debitpaymentval,$rowdebit['cf_4705']);
		}
		$creditpaymentid = array();
		$creditpaymentname = array();
		$creditpaymentval = array();
	$sqlcredit = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
		WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 ='Credit Note' AND arocrm_vendorpaymentcf.cf_4699 ='Approved'");
		$creditnumrow = mysql_num_rows($sqlcredit);
		while($rowcredit = mysql_fetch_array($sqlcredit))
		{
			array_push($creditpaymentid,$rowcredit['vendorpaymentid']);
			array_push($creditpaymentname,$rowcredit['name']);
			array_push($creditpaymentval,$rowcredit['cf_4705']);
		}
	$vendorsql = mysql_query("SELECT arocrm_vendor.*, arocrm_crmentity.* FROM arocrm_vendor INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_vendor.vendorid = '".$vendorid."'");
	$rowvendor = mysql_fetch_array($vendorsql);
	$vendorname = $rowvendor['vendorname'];
	$vendorstreet = $rowvendor['street'];
	$vendorcity = $rowvendor['city'];
	$vendorstate = $rowvendor['state'];
	$vendorpobox = $rowvendor['pobox'];
	$vendorpostalcode = $rowvendor['postalcode'];
	$vendorcountry = $rowvendor['country'];
	
	$sql = mysql_query("SELECT arocrm_goodsreceipt.*,arocrm_goodsreceiptcf.cf_3223, arocrm_goodsreceipt_line_item_details_lineitem.* FROM arocrm_goodsreceipt INNER JOIN arocrm_goodsreceiptcf on arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt.goodsreceiptid INNER JOIN arocrm_goodsreceipt_line_item_details_lineitem on arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid = arocrm_goodsreceipt.goodsreceiptid INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid WHERE arocrm_crmentity.deleted = '0' AND arocrm_goodsreceipt.goodsreceiptid = '".$grid."' AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' GROUP BY arocrm_goodsreceipt_line_item_details_lineitem.cf_1897");
	$numrow = mysql_num_rows($sql);
	if($numrow > 0)
	{
		$nettotal = 0;
		while($rowqry = mysql_fetch_array($sql))
		{
		$plant = $rowqry['cf_nrl_plantmaster388_id'];
		$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
		INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plant."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plantname = $rowplant['name'];
		$productid = $rowqry['cf_1897'];
		$deldate = $rowqry['cf_3223'];
		$result = mysql_query("SELECT * FROM arocrm_goodsreceipt_line_item_details_lineitem WHERE goodsreceiptid = '".$grid."' AND cf_1897 = '".$productid."'");
		$i = 1;
		$releaseqty = 0;
		$semiquantity = 0;
		$blockqty = 0;
		$serials = array();
		while($row = mysql_fetch_array($result))
		{
			$quality = $row['cf_1947'];
			if($quality == 'R')
			{
				$releaseqty = $row['cf_1907'];	
			}
			if($quality == 'S')
			{
				$semiquantity = $row['cf_1907'];	
			}
			if($quality == 'B')
			{
				$blockqty = $row['cf_1907'];
			}
			$netprice = $row['cf_1925'];
			array_push($serials,$row['cf_3003']);
		}
		$serialno = implode(',',$serials);
			$releaseqty = $releaseqty + $semiquantity;
			$totalqty = $releaseqty + $blockqty;
			$total_amount = $netprice * $totalqty;
			$nettotal = $nettotal + $total_amount;
			$product_array = getProductDetails($productid);
			$productname = $product_array['productname'];
			$productcategory = $product_array['category'];
			$item_description = $product_array['description'];
			$productunit = $product_array['unit'];
			$productcode = $product_array['productcode'];
			$html .= '<tr id="row'.$i.'" class="lineItemRow ui-sortable-handle" data-row-num="'.$i.'">
						<td style="text-align:center;">
	<i class="fa fa-trash deleteRow cursorPointer" title="Delete" style="display: none;"></i>&nbsp;
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
	<input id="rqty'.$i.'" name="rqty'.$i.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" value="'.$releaseqty.'" aria-required="true" max="'.$releaseqty.'">
	<input type="hidden" name="margin'.$i.'" value="0">
	<span class="margin pull-right" style="display:none"></span></td>
	<td>
	<input id="bqty'.$i.'" name="bqty'.$i.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" data-rule-positive="true" value="'.$blockqty.'" aria-required="true" max="'.$blockqty.'">
	<input type="hidden" name="margin'.$i.'" value="0">
	<span class="margin pull-right" style="display:none"></span></td>
	<td>
	<input id="qty'.$i.'" name="qty'.$i.'" type="text" class="qty smallInputBox inputElement" data-rule-required="true" data-rule-positive="true" value="'.$totalqty.'" aria-required="true" max="'.$totalqty.'">
	<input type="hidden" name="margin'.$i.'" value="0">
	<span class="margin pull-right" style="display:none"></span></td>
	<td><textarea id="serialno'.$i.'" name="serialno'.$i.'" readonly class="serialno inputElement">'.$serialno.'</textarea></td>
	<td><div>
	<input id="listPrice'.$i.'" readonly name="listPrice'.$i.'" value="'.$netprice.'" type="text" data-rule-required="true" data-rule-positive="true" class="listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true">&nbsp;</div>
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
	<input id="delivery_date'.$i.'" value="'.$deldate.'" name="delivery_date'.$i.'" type="date" class="delivery_date inputElement" />
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
	$totalrow = $i - 1;
	$tax1val = ($tax1 * $nettotal)/100; 
	$tax2val = ($tax2 * $nettotal)/100; 
	$tax3val = ($tax3 * $nettotal)/100;
	$totaltaxval = $tax1val + $tax2val + $tax3val;
	if($adjusticon == '+')
		{
			$grandtotal = number_format((float)((((($total_amount + $totaltaxval)- $advanceval) - $debitval) + $creditval) - $schemediscount) + $adjustval,2,'.','');
		}
		else
		{
			$grandtotal = number_format((float)((((($total_amount + $totaltaxval)- $advanceval) - $debitval) + $creditval) - $schemediscount) - $adjustval,2,'.','');
		}
	}
	$response['totalrow'] = $totalrow;
	$response['vendor'] = $vendorid;
	$response['taxregion'] = $taxregion;
	$response['currency'] = $currency;
	$response['taxtype'] = $taxtype;
	$response['adjusticon'] = $adjusticon;
	$response['adjustval'] = $adjustval;
	$response['vendorname'] = $vendorname;
	$response['vendorstreet'] = $vendorstreet;
	$response['vendorcity'] = $vendorcity;
	$response['vendorstate'] = $vendorstate;
	$response['vendorpobox'] = $vendorpobox;
	$response['vendorpostalcode'] = $vendorpostalcode;
	$response['vendorcountry'] = $vendorcountry;
	$response['po'] = $po;
	$response['poname'] = $poname;
	$response['category'] = $productcategory;
	$response['plant'] = $plant;
	$response['plantname'] = $plantname;
	$response['totalcount'] = $i - 1;
	$response['subtotal'] = $nettotal;
	$response['html'] = $html;
	$response['paymentid'] = $paymentid;
	$response['paymentname'] = $paymentname;
	$response['paymentval'] = $paymentval;
	$response['paymentlength'] = $advancenumrow;
	$response['debitpaymentid'] = $debitpaymentid;
	$response['debitpaymentname'] = $debitpaymentname;
	$response['debitpaymentval'] = $debitpaymentval;
	$response['debitpaymentlength'] = $debitnumrow;
	$response['creditpaymentid'] = $creditpaymentid;
	$response['creditpaymentname'] = $creditpaymentname;
	$response['creditpaymentval'] = $creditpaymentval;
	$response['creditpaymentlength'] = $creditnumrow;
	$response['advance'] = $advanceval;
	$response['debit'] = $debitval;
	$response['credit'] = $creditval;
	$response['tax1'] = $tax1;
	$response['tax2'] = $tax2;
	$response['tax3'] = $tax3;
	$response['totaltaxval'] = $totaltaxval;
	$response['grandtotal'] = $grandtotal;
	return $response;
}
function getVendorAdvancePayments($vendorid)
{
	$response = array();
	$paymentid = array();
	$paymentname = array();
	$paymentval = array();
	$debitpaymentid = array();
	$debitpaymentname = array();
	$debitpaymentval = array();
	$creditpaymentid = array();
	$creditpaymentname = array();
	$creditpaymentval = array();
	$sql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
	INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 ='Advance Payment' AND arocrm_vendorpaymentcf.cf_4699 ='Approved'");
	$numrow = mysql_num_rows($sql);
	while($row = mysql_fetch_array($sql))
	{
		array_push($paymentid,$row['vendorpaymentid']);
		array_push($paymentname,$row['name']);
		array_push($paymentval,$row['cf_3302']);
	}
	$sqldebit = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
	INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 ='Debit Note' AND arocrm_vendorpaymentcf.cf_4699 ='Approved'");
	$debitnumrow = mysql_num_rows($sqldebit);
	while($rowdebit = mysql_fetch_array($sqldebit))
	{
		array_push($debitpaymentid,$rowdebit['vendorpaymentid']);
		array_push($debitpaymentname,$rowdebit['name']);
		array_push($debitpaymentval,$rowdebit['cf_4705']);
	}
	$sqlcredit = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment 
	INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 ='Credit Note' AND arocrm_vendorpaymentcf.cf_4699 ='Approved'");
	$creditnumrow = mysql_num_rows($sqlcredit);
	while($rowcredit = mysql_fetch_array($sqlcredit))
	{
		array_push($creditpaymentid,$rowcredit['vendorpaymentid']);
		array_push($creditpaymentname,$rowcredit['name']);
		array_push($creditpaymentval,$rowcredit['cf_4705']);
	}
	$response['paymentid'] = $paymentid;
	$response['paymentname'] = $paymentname;
	$response['paymentval'] = $paymentval;
	$response['paymentlength'] = $numrow;
	$response['debitpaymentid'] = $debitpaymentid;
	$response['debitpaymentname'] = $debitpaymentname;
	$response['debitpaymentval'] = $debitpaymentval;
	$response['debitpaymentlength'] = $debitnumrow;
	$response['creditpaymentid'] = $creditpaymentid;
	$response['creditpaymentname'] = $creditpaymentname;
	$response['creditpaymentval'] = $creditpaymentval;
	$response['creditpaymentlength'] = $creditnumrow;
	return $response;
}
function getAdvancePayments($accountid)
{
	$response = array();
	$paymentid = array();
	$paymentname = array();
	$paymentval = array();
	$debitpaymentid = array();
	$debitpaymentname = array();
	$debitpaymentval = array();
	$creditpaymentid = array();
	$creditpaymentname = array();
	$creditpaymentval = array();
	$sql = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
	INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpaymentcf.cf_3335 ='Advance Payment' AND arocrm_customerpaymentcf.cf_3376 ='Approved'");
	$numrow = mysql_num_rows($sql);
	while($row = mysql_fetch_array($sql))
	{
		array_push($paymentid,$row['customerpaymentid']);
		array_push($paymentname,$row['name']);
		array_push($paymentval,$row['cf_3342']);
	}
	$sqldebit = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
	INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpaymentcf.cf_3335 ='Debit Note' AND arocrm_customerpaymentcf.cf_3376 ='Approved'");
	$debitnumrow = mysql_num_rows($sqldebit);
	while($rowdebit = mysql_fetch_array($sqldebit))
	{
		array_push($debitpaymentid,$rowdebit['customerpaymentid']);
		array_push($debitpaymentname,$rowdebit['name']);
		array_push($debitpaymentval,$rowdebit['cf_4697']);
	}
	$sqlcredit = mysql_query("SELECT arocrm_customerpayment.*, arocrm_customerpaymentcf.*, arocrm_crmentity.* FROM arocrm_customerpayment 
	INNER JOIN arocrm_customerpaymentcf on arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid 
	INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
	WHERE arocrm_crmentity.deleted = '0' AND arocrm_customerpayment.cf_nrl_accounts363_id = '".$accountid."' AND arocrm_customerpaymentcf.cf_3335 ='Credit Note' AND arocrm_customerpaymentcf.cf_3376 ='Approved'");
	$creditnumrow = mysql_num_rows($sqlcredit);
	while($rowcredit = mysql_fetch_array($sqlcredit))
	{
		array_push($creditpaymentid,$rowcredit['customerpaymentid']);
		array_push($creditpaymentname,$rowcredit['name']);
		array_push($creditpaymentval,$rowcredit['cf_4697']);
	}
	$response['paymentid'] = $paymentid;
	$response['paymentname'] = $paymentname;
	$response['paymentval'] = $paymentval;
	$response['paymentlength'] = $numrow;
	$response['debitpaymentid'] = $debitpaymentid;
	$response['debitpaymentname'] = $debitpaymentname;
	$response['debitpaymentval'] = $debitpaymentval;
	$response['debitpaymentlength'] = $debitnumrow;
	$response['creditpaymentid'] = $creditpaymentid;
	$response['creditpaymentname'] = $creditpaymentname;
	$response['creditpaymentval'] = $creditpaymentval;
	$response['creditpaymentlength'] = $creditnumrow;
	return $response;
}
function getcreditedVendorInvoice($vendorpaymentid)
{
	$response = array();
	$header = "";
	$tbody = "";
	$count = "";
	$message = "";
	$rowcount = array();
	$header .='<thead><tr><th>Invoice No</th><th>Invoice Name</th><th>Invoice Date</th><th>Invoice Amount</th></tr></thead><tbody>';
	$sql = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid WHERE arocrm_crmentity.deleted = '0'");
	while($rws = mysql_fetch_array($sql))
	{
		$custpay = $rws['creditpaymentid'];
		$custpayarr = explode(",",$custpay);
		if (in_array($vendorpaymentid, $custpayarr))
		{
		  $poid = $rws['purchaseorderid'];
		}
	}
	if($poid == "")
	{
		$qryin = mysql_query("SELECT arocrm_invoice.*, aroccrm_invoicecf.* FROM arocrm_invoice 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
		INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
		WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.cf_nrl_goodsreceipt721_id in
		(SELECT arocrm_goodsreceipt.goodsreceiptid FROM arocrm_goodsreceipt 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid 
		WHERE arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id in
		(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid WHERE arocrm_inbounddelivery.cf_nrl_purchaseorder573_id = ".$poid."))");
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" value="'.$rw['total'].'"></td>';

					$tbody .='</tr>';
				
					array_push($rowcount, $i);
					$i++;
		}
	}
	else
	{
		$sql = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0'");
		while($rws = mysql_fetch_array($sql))
		{
			$custpay = $rws['creditpaymentid'];
			$custpayarr = explode(",",$custpay);
			if (in_array($vendorpaymentid, $custpayarr))
			{
			  $invoiceid = $rws['invoice'];
			}
		}
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
					WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" value="'.$rw['invoice_no'].'"></td>';
					
					$tbody .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" value="'.$rw['total'].'"></td>';

					$tbody .='</tr>';
				
					array_push($rowcount, $i);
					$i++;
		}
	}
	$tbodyend ='</tbody>';
	$count = implode(',',$rowcount);
	$response['tbody'] = $tbody;
	$response['header'] = $header;
	$response['tbodyend'] = $tbodyend;
	$response['rowcount'] = $count;
	$response['message'] = $message;
	return $response;
}
function getdebitedVendorInvoice($vendorpaymentid)
{
	$response = array();
	$header = "";
	$tbody = "";
	$count = "";
	$message = "";
	$rowcount = array();
	$header .='<thead><tr><th>Invoice No</th><th>Invoice Name</th><th>Invoice Date</th><th>Invoice Amount</th></tr></thead><tbody>';
	$sql = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid WHERE arocrm_crmentity.deleted = '0'");
	while($rws = mysql_fetch_array($sql))
	{
		$custpay = $rws['debitpaymentid'];
		$custpayarr = explode(",",$custpay);
		if (in_array($vendorpaymentid, $custpayarr))
		{
		  $poid = $rws['purchaseorderid'];
		}
	}
	if($poid == "")
	{
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
		INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
		WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.cf_nrl_goodsreceipt721_id in
		(SELECT arocrm_goodsreceipt.goodsreceiptid FROM arocrm_goodsreceipt 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid 
		WHERE arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id in
		(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid WHERE arocrm_inbounddelivery.cf_nrl_purchaseorder573_id = ".$poid."))");
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
					
					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" value="'.$rw['total'].'"></td>';

					$tbody .='</tr>';
				
					array_push($rowcount, $i);
					$i++;
		}
	}
	else
	{
		$sql = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0'");
		while($rws = mysql_fetch_array($sql))
		{
			$custpay = $rws['debitpaymentid'];
			$custpayarr = explode(",",$custpay);
			if (in_array($vendorpaymentid, $custpayarr))
			{
			  $invoiceid = $rws['invoice'];
			}
		}
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
					 WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" value="'.$rw['total'].'"></td>';

					$tbody .='</tr>';
				
					array_push($rowcount, $i);
					$i++;
		}
	}
	$tbodyend  ='</tbody>';
	$count = implode(',',$rowcount);
	$response['header'] = $header;
	$response['tbody'] = $tbody;
	$response['tbodyend'] = $tbodyend;
	$response['rowcount'] = $count;
	$response['message'] = $message;
	return $response;
}
function getadvanceVendorInvoice($vendorpaymentid)
{
	$response = array();
	$header = "";
	$tbody = "";
	$count = "";
	$message = "";
	$rowcount = array();
	$header .='<thead><tr><th>Tools</th><th>Invoice No</th><th>Invoice Name</th><th>Invoice Date</th><th>Invoice Amount</th></tr></thead><tbody>';
	$sql = mysql_query("SELECT arocrm_purchaseorder.*, arocrm_crmentity.* FROM arocrm_purchaseorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_purchaseorder.purchaseorderid WHERE arocrm_crmentity.deleted = '0'");
	while($rws = mysql_fetch_array($sql))
	{
		$custpay = $rws['advancepaymentid'];
		$custpayarr = explode(",",$custpay);
		if (in_array($vendorpaymentid, $custpayarr))
		{
		  $poid = $rws['purchaseorderid'];
		}
	}
	if($poid == "")
	{
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
		INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
		WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.cf_nrl_goodsreceipt721_id in
		(SELECT arocrm_goodsreceipt.goodsreceiptid FROM arocrm_goodsreceipt 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt.goodsreceiptid 
		WHERE arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id in
		(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid WHERE arocrm_inbounddelivery.cf_nrl_purchaseorder573_id = ".$poid."))");
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" value="'.$rw['invoice_no'].'"></td>';
					
					$tbody .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" value="'.$rw['total'].'"></td>';

					$tbody .='</tr>';
				
					array_push($rowcount, $i);
					$i++;
		}
	}
	else
	{
		$sql = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0'");
		while($rws = mysql_fetch_array($sql))
		{
			$custpay = $rws['advancepaymentid'];
			$custpayarr = explode(",",$custpay);
			if (in_array($vendorpaymentid, $custpayarr))
			{
			  $invoiceid = $rws['invoice'];
			}
		}
		$qryin = mysql_query("SELECT arocrm_invoice.*, aroccrm_invoicecf.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
					 WHERE arocrm_crmentity.deleted = '0' AND arocrm_invoice.invoiceid = '".$invoiceid."'");
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" value="'.$rw['total'].'"></td>';

					$tbody .='</tr>';
				
					array_push($rowcount, $i);
					$i++;
		}
	}
	$tbodyend ='</tbody>';
	$count = implode(',',$rowcount);
	$response['header'] = $header;
	$response['tbody'] = $tbody;
	$response['tbodyend'] = $tbodyend;
	$response['rowcount'] = $count;
	$response['message'] = $message;
	return $response;
}
function getSalesInvoice($customerid)
{
	$response = array();
	$header = "";
	$tbody = "";
	$header .='<thead><tr><th>Invoice No</th><th>Invoice Name</th><th>Invoice Date</th><th>Invoice Amount</th></tr></thead><tbody>';
	$count = "";
	$message = "";
	$rowcount = array();
	$sql = mysql_query("SELECT arocrm_salesorder.*, arocrm_crmentity.* FROM arocrm_salesorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = '0'");
	while($rws = mysql_fetch_array($sql))
	{
		$custpay = $rws['advancepaymentid'];
		$custpayarr = explode(",",$custpay);
		if (in_array($customerid, $custpayarr))
		{
		  $soid = $rws['salesorderid'];
		}
	}
	if($soid != "")
	{
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
					 WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.salesorderid=".$soid);
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="text" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" value="'.$rw['total'].'"></td>';
					
					$tbody .='</tr>';
					array_push($rowcount, $i);
					$i++;
		}
	}
	else
	{
		$sql = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0'");
		while($rws = mysql_fetch_array($sql))
		{
			$custpay = $rws['advancepaymentid'];
			$custpayarr = explode(",",$custpay);
			if (in_array($customerid, $custpayarr))
			{
			  $invoiceid = $rws['invoiceid'];
			}
		}
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*, arocrm_crmentity.* FROM arocrm_invoice 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
		INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid
		WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoiceid);
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="text" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" value="'.$rw['total'].'"></td>';
					
					$tbody .='</tr>';
					array_push($rowcount, $i);
					$i++;
		}
	}
    $tbodyend ='</tbody>';
	$count = implode(',',$rowcount);
	$response['tbody'] = $tbody;
	$response['header'] = $header;
	$response['tbodyend'] = $tbodyend;
	$response['rowcount'] = $count;
	$response['message'] = $message;
	return $response;
}
function getSalesdebitInvoice($customerid)
{
	$response = array();
	$header = "";
	$tbody = "";
	$header .='<thead><tr><th>Invoice No</th><th>Invoice Name</th><th>Invoice Date</th><th>Invoice Amount</th></tr></thead><tbody>';
	$count = "";
	$message = "";
	$rowcount = array();
	$sql = mysql_query("SELECT arocrm_salesorder.*, arocrm_crmentity.* FROM arocrm_salesorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = '0'");
	while($rws = mysql_fetch_array($sql))
	{
		$custpay = $rws['debitpaymentid'];
		$custpayarr = explode(",",$custpay);
		if (in_array($customerid, $custpayarr))
		{
		  $soid = $rws['salesorderid'];
		}
	}
	if($soid != "")
	{
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.salesorderid=".$soid);
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="text" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" value="'.$rw['total'].'"></td>';
					
					$tbody .='</tr>';
					array_push($rowcount, $i);
					$i++;
		}
	}
	else
	{
		$sql = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0'");
		while($rws = mysql_fetch_array($sql))
		{
			$custpay = $rws['debitpaymentid'];
			$custpayarr = explode(",",$custpay);
			if (in_array($customerid, $custpayarr))
			{
			  $invoiceid = $rws['invoiceid'];
			}
		}
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoiceid);
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="text" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" value="'.$rw['total'].'"></td>';
					
					$tbody .='</tr>';
					array_push($rowcount, $i);
					$i++;
		}
	}
			$tbodyend ='</tbody>';
	$count = implode(',',$rowcount);
	$response['tbody'] = $tbody;
	$response['header'] = $header;
	$response['tbodyend'] = $tbodyend;
	$response['rowcount'] = $count;
	$response['message'] = $message;
	return $response;
}
function getSalescreditInvoice($customerid)
{
	$response = array();
	$header = "";
	$tbody = "";
	$header .='<thead><tr><th>Invoice No</th><th>Invoice Name</th><th>Invoice Date</th><th>Invoice Amount</th></tr></thead><tbody>';
	$count = "";
	$message = "";
	$rowcount = array();
	$sql = mysql_query("SELECT arocrm_salesorder.*, arocrm_crmentity.* FROM arocrm_salesorder INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_salesorder.salesorderid WHERE arocrm_crmentity.deleted = '0'");
	while($rws = mysql_fetch_array($sql))
	{
		$custpay = $rws['creditpaymentid'];
		$custpayarr = explode(",",$custpay);
		if (in_array($customerid, $custpayarr))
		{
		  $soid = $rws['salesorderid'];
		}
	}
	if($soid != "")
	{
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.salesorderid=".$soid);
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="text" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" value="'.$rw['total'].'"></td>';
					
					$tbody .='</tr>';
					array_push($rowcount, $i);
					$i++;
		}
	}
	else
	{
		$sql = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted = '0'");
		while($rws = mysql_fetch_array($sql))
		{
			$custpay = $rws['creditpaymentid'];
			$custpayarr = explode(",",$custpay);
			if (in_array($customerid, $custpayarr))
			{
			  $invoiceid = $rws['invoiceid'];
			}
		}
		$qryin = mysql_query("SELECT arocrm_invoice.*, arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoiceid);
		$i = 1;
		while($rw = mysql_fetch_array($qryin))
		{
			$tbody .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$tbody .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$rw['invoiceid'].'" class="sourceField" data-displayvalue="'.$rw['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$rw['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" value="'.$rw['invoice_no'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="text" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" value="'.$rw['cf_4627'].'"></td>';

					$tbody .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" value="'.$rw['total'].'"></td>';
					
					$tbody .='</tr>';
					array_push($rowcount, $i);
					$i++;
		}
	}
	$tbodyend ='</tbody>';
	$count = implode(',',$rowcount);
	$response['tbody'] = $tbody;
	$response['header'] = $header;
	$response['tbodyend'] = $tbodyend;
	$response['rowcount'] = $count;
	$response['message'] = $message;
	return $response;
}


function salesInvoiceDetails($customerid)
{
	$response = array();
	$invid = array();
	$html = "";
	$count = "";
	$message = "";
	$rowcount = array();
	$total = 0;
	$alltotal = 0;
	$directMode_Payment_Details = 0;
	$i = 1;
	$totalinvamount = 0;
		$sumtotalpaid = 0;
		$sumdueamount = 0;
		$plantid  = '';
		$plantname  = '' ;
		
		$invqry =  mysql_query("(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$customerid."' AND arocrm_invoice.invoicestatus!='Paid' AND arocrm_invoice.invoicestatus='Approved'AND arocrm_invoicecf.cf_3288 = 'Direct Sales') UNION (SELECT arocrm_invoice.invoiceid FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid INNER JOIN arocrm_salesordercf ON arocrm_salesordercf.salesorderid = arocrm_invoice.salesorderid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.accountid='".$customerid."' AND arocrm_invoice.invoicestatus!='Paid' AND arocrm_invoice.invoicestatus='Approved'AND arocrm_invoicecf.cf_3288 ='Sales Invoice' AND arocrm_salesordercf.cf_3286 != 'Against Warranty')");
		while($invrows = mysql_fetch_array($invqry))
		{
			array_push($invid,$invrows['invoiceid']);
		}
		$invids = implode(',',$invid);
		$qry = mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid IN (".$invids.") ORDER BY arocrm_invoicecf.cf_4627");
		$invrowtotal = mysql_num_rows($qry);


		$debitqry =  mysql_query("SELECT * FROM `arocrm_customerpayment` 
		INNER JOIN `arocrm_customerpaymentcf` ON `arocrm_customerpaymentcf`.`customerpaymentid` = `arocrm_customerpayment`.`customerpaymentid`
		INNER JOIN `arocrm_crmentity` ON `arocrm_crmentity`.`crmid` = `arocrm_customerpayment`.`customerpaymentid`
		INNER JOIN `arocrm_customerpayment_payment_details_lineitem` ON `arocrm_customerpayment_payment_details_lineitem`.`customerpaymentid` = `arocrm_customerpayment`.`customerpaymentid`
		WHERE `arocrm_crmentity`.`deleted` = 0
		AND `arocrm_customerpaymentcf`.`cf_3335` = 'Debit Note' 
		AND `arocrm_customerpaymentcf`.`cf_3376` = 'Approved'
		AND `arocrm_customerpayment`.`cf_nrl_accounts363_id` = '".$customerid."'");
		$debitrowtotal = mysql_num_rows($debitqry);
		
	    $rowtotal = (int)$invrowtotal + (int)$debitrowtotal;	
	
	if($rowtotal > 1){
		$directMode_Payment_Details = 1;
	}
	
	if($rowtotal > 0)
	{
	
		
				while($rows = mysql_fetch_array($debitqry))
		{
		
			 if($rowtotal == '1')
			 {
			 $n = "";
			 }
			 else
			 {
			 $n = "_".$i;
			 }
			$invoiceno = $rows['customerpaymentno'];
			$invoiceid = $rows['customerpaymentid'];
			
	$mysqlq = mysql_query("SELECT SUM(arocrm_customerpayment_payment_details_lineitem.cf_3356) AS paid FROM arocrm_customerpayment 
	INNER JOIN arocrm_customerpayment_payment_details_lineitem ON arocrm_customerpayment_payment_details_lineitem.customerpaymentid = arocrm_customerpayment.customerpaymentid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_customerpayment.customerpaymentid 
	INNER JOIN arocrm_customerpaymentcf ON arocrm_customerpaymentcf.customerpaymentid = arocrm_customerpayment.customerpaymentid
	WHERE arocrm_crmentity.deleted = 0 AND `arocrm_customerpaymentcf`.`cf_3376` = 'Approved'
	AND arocrm_customerpayment_payment_details_lineitem.cf_3346 = '".$invoiceid."' GROUP BY arocrm_customerpayment_payment_details_lineitem.cf_3346");
	$msdata = mysql_fetch_array($mysqlq);
	$amountpaid = $msdata['paid'];
	$totalinvoiceamount = $rows['cf_4697'];
	$totaldue = number_format((float)$totalinvoiceamount - (float)$amountpaid, 2, '.', '');

	
	$sumtotalpaid = (float)$totalinvoiceamount + (float)$sumtotalpaid;
	$sumdueamount = (float)$totaldue + (float)$sumdueamount;
	$totalinvamount = (float)$totalinvoiceamount + (float)$totalinvamount;
	
			if($totaldue > 0){
			$html .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

						$html .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
						<input id="type'.$n.'" style="min-width:80px;" type="hidden" class="inputElement" name="type'.$n.'" value="DebitNote">
						</td>';
	
						$html .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$invoiceid.'" class="sourceField" data-displayvalue="'.$rows['name'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$rows['name'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

						$html .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" readonly value="'.$invoiceno.'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="date" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" readonly value="'.$rows['cf_4967'].'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" readonly value="'.$totalinvoiceamount.'"></td>';
				
						$html .='<td class="fieldValue"><input id="cf_3354'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3354'.$n.'" readonly value="'.$totaldue.'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3356'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3356'.$n.'" max="'.$totaldue.'" value="0.00"></td>';

						$html .='<td class="fieldValue"><input id="cf_3358'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3358'.$n.'" value="'.$totaldue.'"></td>';

						$html .='<td class="fieldValue"><select data-fieldname="cf_3360'.$n.'" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3360'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_3360'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="Bank">Bank</option><option value="Online">Online</option><option value="Credit">Credit</option></select>
						<script>
							$(document).ready(function(){
							$("#cf_3360'.$n.'").select2();
							});
						</script>
						</td>';

						$html .='<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3362'.$n.'" class="inputElement " name="cf_3362'.$n.'"></textarea></td>';

						$html .='</tr>';
						array_push($rowcount, $i);
						$i++;
			}
		}
	

		
		
			
		while($row = mysql_fetch_array($qry))
		{
			$plantid = $row['cf_nrl_plantmaster164_id'];
			$plantsql = mysql_fetch_array(mysql_query("SELECT arocrm_plantmaster.* FROM arocrm_plantmaster 
			INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'"));
			$plantname = $plantsql['name'];
		
			 if($rowtotal == '1')
			 {
			 $n = "";
			 }
			 else
			 {
			 $n = "_".$i;
			 }
				$invoiceno = $row['invoice_no'];
				$invoiceid = $row['invoiceid'];
				$amountpaid = $row['received'];
				$totalinvoiceamount = $row['total'];
				if($amountpaid > $totalinvoiceamount){
					$amountpaid = $totalinvoiceamount;
				}
				
				$totaldue = number_format((float)$totalinvoiceamount - (float)$amountpaid, 2, '.', '');

				if($totaldue > 0){
				$sumtotalpaid = (float)$totalinvoiceamount + (float)$sumtotalpaid;
				$sumdueamount = (float)$totaldue + (float)$sumdueamount;
				$totalinvamount = (float)$totalinvoiceamount + (float)$totalinvamount;
 
			            $html .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

						$html .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a>
						<input id="type'.$n.'" style="min-width:80px;" type="hidden" class="inputElement" name="type'.$n.'" value="SalesInvoice">
						</td>';
	
						$html .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3346'.$n.'" type="hidden" value="'.$invoiceid.'" class="sourceField" data-displayvalue="'.$row['subject'].'"><input id="cf_3346_display'.$n.'" name="cf_3346_display'.$n.'" data-fieldname="cf_3346" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$row['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

						$html .='<td class="fieldValue"><input id="cf_3374'.$n.'" type="text" data-fieldname="cf_3374" data-fieldtype="string" class="inputElement " name="cf_3374'.$n.'" readonly value="'.$invoiceno.'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3350'.$n.'" type="date" data-fieldname="cf_3350" data-fieldtype="string" class="inputElement " name="cf_3350'.$n.'" readonly value="'.$row['cf_4627'].'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3352'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3352'.$n.'" readonly value="'.$totalinvoiceamount.'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3354'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3354'.$n.'" readonly value="'.$totaldue.'"></td>';

						$html .='<td class="fieldValue"><input id="cf_3356'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3356'.$n.'" max="'.$totaldue.'" value="0.00"></td>';

						$html .='<td class="fieldValue"><input id="cf_3358'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3358'.$n.'" value="'.$totaldue.'"></td>';
			

						$html .='<td class="fieldValue"><select data-fieldname="cf_3360'.$n.'" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3360'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_3360'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="Bank">Bank</option><option value="Online">Online</option><option value="Credit">Credit</option></select>
						<script>
							$(document).ready(function(){
							$("#cf_3360'.$n.'").select2();
							});
						</script>
						</td>';

						$html .='<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3362'.$n.'" class="inputElement " name="cf_3362'.$n.'"></textarea></td>';

						$html .='</tr>';
						array_push($rowcount, $i);
						$i++;
				}
						
						
		}
		$count = implode(',',$rowcount);
	}
	else
	{
		$message =  "Already paid total amount of all invoices against this Customer, select other customer";
				
	}
	$response['tbody'] = $html;
	$response['rowcount'] = $count;
	$response['directmode'] = $directMode_Payment_Details;
	$response['total'] = $totalinvamount;
	$response['sumdueamount'] = $sumdueamount;
	$response['alltotal'] = $sumtotalpaid;
	$response['plantid'] = $plantid;
	$response['plantname'] = $plantname;
	$response['message'] = $message;
	return $response;
}


function getVendorDetails($id)
{
$response = array();
$qry =  mysql_query("SELECT arocrm_vendor.*,arocrm_vendorcf.*,arocrm_crmentity.* FROM arocrm_vendor
			INNER JOIN arocrm_vendorcf on arocrm_vendorcf.vendorid = arocrm_vendor.vendorid
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_vendor.vendorid
			WHERE arocrm_crmentity.deleted=0 AND arocrm_vendor.vendorid=".$id);
			$count = mysql_num_rows($qry);
			if($count==1)
			{
			  $row = mysql_fetch_array($qry);
			  $response['message'] = $row['cf_4786'];
			}
			return $response;
}
function purchaseInvoiceDetails($vendorid)
{
	$response = array();
	$html = "";
	$count = "";
	$message = "";
	$rowcount = array();
	$total = 0;
	$alltotal = 0;
	$qry =  mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*,arocrm_crmentity.* FROM  arocrm_invoice
			INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid
								 WHERE arocrm_crmentity.deleted=0 AND arocrm_invoicecf.cf_3288 ='Purchase Invoice' AND arocrm_invoice.cf_nrl_vendors752_id='".$vendorid."' AND arocrm_invoice.invoicestatus!='Paid' AND arocrm_invoice.invoicestatus='Approved'");
			
	$rowtotal = mysql_num_rows($qry);
	if($rowtotal > 0)
	{
		$i = 1;
		$sumtotalpaid = 0;
		$sumdueamount = 0;
		$plantid  = '';
		$plantname  = '' ;
			
		while($row = mysql_fetch_array($qry))
		{
			$plantid = $row['cf_nrl_plantmaster164_id'];
			$plantsql = mysql_fetch_array(mysql_query("SELECT arocrm_plantmaster.* FROM arocrm_plantmaster 
			INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'"));
			$plantname = $plantsql['name'];
		
			 if($rowtotal == '1')
			 {
			 $n = "";
			 }
			 else
			 {
			 $n = "_".$i;
			 }
			$invoiceno = $row['invoice_no'];
			$invoiceid = $row['invoiceid'];
			
			$mysqlq = mysql_query("SELECT SUM(arocrm_vendorpayment_payment_details_lineitem.cf_3314) AS paid FROM arocrm_vendorpayment 
	INNER JOIN arocrm_vendorpayment_payment_details_lineitem ON arocrm_vendorpayment_payment_details_lineitem.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid 
	WHERE arocrm_crmentity.deleted = 0 
	AND arocrm_vendorpayment_payment_details_lineitem.cf_3306 = '".$invoiceid."' GROUP BY arocrm_vendorpayment_payment_details_lineitem.cf_3306");
	$msdata = mysql_fetch_array($mysqlq);
	$amountpaid = $msdata['paid'];
	$totalinvoiceamount = $row['total'];
	$totaldue = (float)$totalinvoiceamount - (float)$amountpaid;
	
	$sumtotalpaid = (float)$totalinvoiceamount + (float)$sumtotalpaid;
	$sumdueamount = (float)$totaldue + (float)$sumdueamount;
	
			$html .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$html .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$html .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$row['invoiceid'].'" class="sourceField" data-displayvalue="'.$row['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$row['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$html .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" readonly value="'.$row['invoice_no'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" readonly value="'.$row['cf_4627'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" readonly value="'.$row['total'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3312'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3312'.$n.'" readonly value="'.$row['total'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3314'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3314'.$n.'" readonly max="'.$row['total'].'" value="0.00"></td>';

					$html .='<td class="fieldValue"><input id="cf_3316'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3316'.$n.'" readonly value="'.$row['total'].'"></td>';

					$html .='<td class="fieldValue"><select data-fieldname="cf_3318'.$n.'" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3318'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_3318'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="Bank">Bank</option><option value="Online">Online</option><option value="Debit">Debit</option></select>
					<script>
						$(document).ready(function(){
						$("#cf_3318'.$n.'").select2();
						});
					</script>
					</td>';

					$html .='<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3320'.$n.'" class="inputElement " name="cf_3320'.$n.'"></textarea></td>';

					$html .='</tr>';
					array_push($rowcount, $i);
					$i++;
				}
				$count = implode(',',$rowcount);
			}
			else
			{
				$message =  "Already paid total amount of all invoices against this Vendor, select other vendor";
						
			}
	$response['tbody'] = $html;
	$response['rowcount'] = $count;
	$response['total'] = $sumdueamount;
	$response['alltotal'] = $sumtotalpaid;
	$response['plantid'] = $plantid;
	$response['plantname'] = $plantname;
	$response['message'] = $message;
	return $response;
}

/*function purchaseInvoiceDetails($vendorid)
{
	$response = array();
	$html = "";
	$count = "";
	$message = "";
	$rowcount = array();
	$total = 0;
	$alltotal = 0;
	$qry =  mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*,arocrm_crmentity.* FROM  arocrm_invoice
			INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid
								 WHERE arocrm_crmentity.deleted=0 AND arocrm_invoicecf.cf_3288 ='Purchase Invoice' AND arocrm_invoice.cf_nrl_vendors752_id='".$vendorid."' AND arocrm_invoice.invoicestatus!='Paid' AND arocrm_invoice.invoicestatus='Approved'");
			
	$row = mysql_num_rows($qry);
	if($row > 0)
	{
		$rowno = 0;
		while($row = mysql_fetch_array($qry))
		{
			$invoiceno = $row['invoice_no'];
			$result = mysql_query("SELECT * FROM arocrm_vendorpayment_payment_details_lineitem WHERE cf_3378 = '".$invoiceno."'");
			$norow = mysql_num_rows($result);	
			$rowno = $rowno + $norow;
		}
		if($rowno > 0)
		{
		$sql = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpaymentcf.*, arocrm_crmentity.* FROM arocrm_vendorpayment
		INNER JOIN arocrm_vendorpaymentcf on arocrm_vendorpaymentcf.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid
		INNER JOIN arocrm_crmentity on arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid WHERE arocrm_crmentity.deleted = '0' AND arocrm_vendorpayment.cf_nrl_vendors297_id = '".$vendorid."' AND arocrm_vendorpaymentcf.cf_4701 = 'Purchase Invoice' order by arocrm_vendorpayment.vendorpaymentid desc LIMIT 0,1");
		$rowno = mysql_num_rows($sql);
		$rws = mysql_fetch_array($sql);
		$total = (float)$rws['cf_3304'];
		if($rowno > 0)
		{
			
			if($total == '0.00')
			{
				$message = "Already paid total amount of all invoices against this Vendor, select other vendor";
				$qryup =  mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*,arocrm_crmentity.* FROM  arocrm_invoice
			INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid
								 WHERE arocrm_crmentity.deleted=0 AND arocrm_invoicecf.cf_3288 ='Purchase Invoice' AND arocrm_invoice.cf_nrl_vendors752_id=".$vendorid);
				while($rowup = mysql_fetch_array($qryup))
				{
					$invoice = $rowup['invoiceid'];
					//$update = mysql_query("UPDATE arocrm_invoice SET invoicestatus = 'Paid' WHERE invoiceid = '".$invoice."'");
				}
				while($rwap = mysql_fetch_array($sql))
				{
					$vendpayid = $rwap['vendorpaymentid'];
					$upvendpay = mysql_query("UPDATE arocrm_vendorpaymentcf SET cf_3304 = '0.00' WHERE arocrm_vendorpaymentcf.vendorpaymentid ='".$vendpayid."'");
				}
			}
			else
			{
				$paymentid = $rws['vendorpaymentid'];
				$result = mysql_query("SELECT arocrm_vendorpayment.*, arocrm_vendorpayment_payment_details_lineitem.* FROM arocrm_vendorpayment
INNER JOIN arocrm_vendorpayment_payment_details_lineitem ON arocrm_vendorpayment_payment_details_lineitem.vendorpaymentid = arocrm_vendorpayment.vendorpaymentid 
INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_vendorpayment.vendorpaymentid
WHERE arocrm_crmentity.deleted = 0  AND arocrm_vendorpayment.vendorpaymentid = ".$paymentid." AND arocrm_vendorpayment_payment_details_lineitem.cf_3316!='0.00'");
				$rownum = mysql_num_rows($result);
				$i = 1;
				while($rw = mysql_fetch_array($result))
				{
					if($rownum == '1')
					{
						$n = "";
					}
					else
					{
						$n = "_".$i;
					}
					$plantid = $rw['cf_nrl_plantmaster425_id'];
					$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
		INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plantname = $rowplant['name'];
					$inv = mysql_query("SELECT arocrm_invoice.* FROM arocrm_invoice 
						INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
						WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoiceid = '".$rw['cf_3306']."'");
					$invrow = mysql_fetch_array($inv);
					$invsub = $invrow['subject'];
					
					$alltotal = $alltotal + $rw['cf_3333'];
					$html .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$html .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$html .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$rw['cf_3306'].'" class="sourceField" data-displayvalue="'.$invsub.'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$invsub.'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$html .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" readonly value="'.$rw['cf_3378'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" readonly value="'.$rw['cf_3308'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" readonly value="'.$rw['cf_3333'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3312'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3312'.$n.'" readonly value="'.$rw['cf_3316'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3314'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3314'.$n.'" readonly max="'.$rw['cf_3316'].'" value="0.00"></td>';

					$html .='<td class="fieldValue"><input id="cf_3316'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3316'.$n.'" readonly value="'.$rw['cf_3316'].'"></td>';

					$html .='<td class="fieldValue"><select data-fieldname="cf_3318'.$n.'" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3318'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_3318'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="Bank">Bank</option><option value="Online">Online</option><option value="Debit">Debit</option></select>
					<script>
						$(document).ready(function(){
						$("#cf_3318'.$n.'").select2();
						});
					</script>
					</td>';

					$html .='<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3320'.$n.'" class="inputElement " name="cf_3320'.$n.'"></textarea></td>';

					$html .='</tr>';
					array_push($rowcount, $i);
					$i++;
				}
				$count = implode(',',$rowcount);
			}
		}
		else
		{
			$qry =  mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*,arocrm_crmentity.* FROM  arocrm_invoice
			INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid
								 WHERE arocrm_crmentity.deleted=0 AND arocrm_invoicecf.cf_3288 ='Purchase Invoice' AND arocrm_invoice.cf_nrl_vendors752_id=".$vendorid." AND arocrm_invoice.invoicestatus!='Paid' AND arocrm_invoice.invoicestatus='Approved'");
			$rownum = mysql_num_rows($qry);
			if($rownum == '0')
			{
				$message = "No invoice against this customer, select other customer";
				$count = $rownum;
			}
			else
			{
				$i = 1;
			while($row = mysql_fetch_array($qry))
			{
				if($rownum == '1')
				{
					$n = "";
				}
				else
				{
					$n = "_".$i;
				}
				$plantid = $row['cf_nrl_plantmaster164_id'];
				$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
		INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plantname = $rowplant['name'];
				$total = $total + $row['total'];
				$alltotal = $total;
				$html .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

				$html .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

				$html .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$row['invoiceid'].'" class="sourceField" data-displayvalue="'.$row['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$row['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

				$html .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" readonly value="'.$row['invoice_no'].'"></td>';

				$html .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" readonly value="'.$row['cf_4627'].'"></td>';

				$html .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" readonly value="'.$row['total'].'"></td>';

				$html .='<td class="fieldValue"><input id="cf_3312'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3312'.$n.'" readonly value="'.$row['total'].'"></td>';

				$html .='<td class="fieldValue"><input id="cf_3314'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3314'.$n.'" max="'.$row['total'].'" readonly value="0.00"></td>';

				$html .='<td class="fieldValue"><input id="cf_3316'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3316'.$n.'" readonly value="'.$row['total'].'"></td>';

				$html .='<td class="fieldValue"><select data-fieldname="cf_3318'.$n.'" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3318'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_3318'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="Bank">Bank</option><option value="Online">Online</option><option value="Debit">Debit</option></select>
				<script>
					$(document).ready(function(){
					$("#cf_3318'.$n.'").select2();
					});
				</script>
				</td>';

				$html .='<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3320'.$n.'" class="inputElement " name="cf_3320'.$n.'"></textarea></td>';

				$html .='</tr>';
				array_push($rowcount, $i);
				$i++;
			}
			$count = implode(',',$rowcount);
			}
		}
	}
	else
		{
			$qry =  mysql_query("SELECT arocrm_invoice.*, arocrm_invoicecf.*,arocrm_crmentity.* FROM  arocrm_invoice
			INNER JOIN arocrm_invoicecf on arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid
								 WHERE arocrm_crmentity.deleted=0 AND arocrm_invoicecf.cf_3288 ='Purchase Invoice' AND arocrm_invoice.cf_nrl_vendors752_id=".$vendorid." AND arocrm_invoice.invoicestatus!='Paid' AND arocrm_invoice.invoicestatus='Approved'");
			$rownum = mysql_num_rows($qry);
			if($rownum == '0')
			{
				$message = "No invoice against this vendor, select other vendor";
				$count = $rownum;
			}
			else
			{
				$i = 1;
				while($row = mysql_fetch_array($qry))
				{
					if($rownum == '1')
					{
						$n = "";
					}
					else
					{
						$n = "_".$i;
					}
					$plantid = $row['cf_nrl_plantmaster164_id'];
				$plantsql = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster 
		INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = 0 AND arocrm_plantmaster.plantmasterid = '".$plantid."'");
		$rowplant = mysql_fetch_array($plantsql);
		$plantname = $rowplant['name'];
					$total = $total + $row['total'];
					$alltotal = $total;
					$html .='<tr id="Payment_Details__row_'.$i.'" class="tr_clone">';

					$html .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$html .='<td class="fieldValue"><div class="referencefield-wrapper "><input name="popupReferenceModule" type="hidden" value="Invoice"><div class="input-group"><input name="cf_3306'.$n.'" type="hidden" value="'.$row['invoiceid'].'" class="sourceField" data-displayvalue="'.$row['subject'].'"><input id="cf_3306_display'.$n.'" name="cf_3306_display'.$n.'" data-fieldname="cf_3306" data-fieldtype="reference" type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" readonly value="'.$row['subject'].'" placeholder="Type to search" autocomplete="off"></div></div></td>';

					$html .='<td class="fieldValue"><input id="cf_3378'.$n.'" type="text" data-fieldname="cf_3378" data-fieldtype="string" class="inputElement " name="cf_3378'.$n.'" readonly value="'.$row['invoice_no'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3308'.$n.'" type="text" data-fieldname="cf_3308" data-fieldtype="string" class="inputElement " name="cf_3308'.$n.'" readonly value="'.$row['cf_4627'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3333'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3333'.$n.'" readonly value="'.$row['total'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3312'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3312'.$n.'" readonly value="'.$row['total'].'"></td>';

					$html .='<td class="fieldValue"><input id="cf_3314'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3314'.$n.'" readonly max="'.$row['total'].'" value="0.00"></td>';

					$html .='<td class="fieldValue"><input id="cf_3316'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3316'.$n.'" readonly value="'.$row['total'].'"></td>';

					$html .='<td class="fieldValue"><select data-fieldname="cf_3318'.$n.'" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_3318'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_3318'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="Bank">Bank</option><option value="Online">Online</option><option value="Debit">Debit</option></select>
					<script>
						$(document).ready(function(){
						$("#cf_3318'.$n.'").select2();
						});
					</script>
					</td>';

					$html .='<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3320'.$n.'" class="inputElement " name="cf_3320'.$n.'"></textarea></td>';

					$html .='</tr>';
					array_push($rowcount, $i);
					$i++;
				}
				$count = implode(',',$rowcount);
			}
		
		}
	}
	else
	{
		$message =  "Already paid total amount of all invoices against this Vendor, select other vendor";
				
	}
	$response['tbody'] = $html;
	$response['rowcount'] = $count;
	$response['total'] = $total;
	$response['alltotal'] = $alltotal;
	$response['plantid'] = $plantid;
	$response['plantname'] = $plantname;
	$response['message'] = $message;
	return $response;
}*/



function getFiscalDetails($plantid, $year, $month, $module)
{
	$response = array();
	$qry =  mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM  arocrm_plantmaster INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_plantmaster.plantmasterid=".$plantid);
	$row = mysql_fetch_array($qry);
	$plantname = $row['name'];
	//$monthName = date("F", mktime(0, 0, 0, $month, 10));
	$result = mysql_query("SELECT * FROM  arocrm_fiscalyear WHERE year = '".$year."' AND month = '".$month."'");
	$rows = mysql_fetch_array($result);
	$days = $rows['gracedays'];
	$label = strtolower($module)."_".strtolower($plantname);
	$val = $rows[$label];
	$response['days'] = $days;
	$response['fiscalval'] = $val;
	return $response;
}
function getTotalSalesOrderforProduct($yr, $district, $code, $div)
{
	$response = array();
	$qty4W = 0;
	$qty2W = 0;
	$qtyib = 0;
	$qtyer = 0;
	$qry =  mysql_query("SELECT arocrm_crmentity.* FROM  arocrm_crmentity WHERE deleted=0 AND setype='SalesOrder'");
	while($row = mysql_fetch_array($qry))
	{
		$datetime = $row['createdtime'];
		$date = explode(" ",$datetime);
		$dt = explode("-",$date[0]);
		$year = $yr - 1;
		if($dt[0] == $yr)
		{
			if($dt[1]=='01' || $dt[1] == '02' || $dt[1] == '03')
			{
				$salesorder = $row['crmid'];
			}
		}
		if($dt[0] == $year)
		{
			if($dt[1]=='04' || $dt[1] == '05' || $dt[1] == '06' || $dt[1] == '07' || $dt[1] == '08' || $dt[1] == '09' || $dt[1] == '10' || $dt[1] == '11' || $dt[1] == '12')
			{
				$salesorder = $row['crmid'];
			}
		}
		$sqlqry = mysql_query("SELECT arocrm_salesorder.*, arocrm_crmentity.* FROM  arocrm_salesorder
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesorder.salesorderid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_salesorder.salesorderid=".$salesorder);
		$rws = mysql_fetch_array($sqlqry);
		$account = $rws['accountid'];
		$result = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_accountbillads.*, arocrm_crmentity.* FROM arocrm_account
                         INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
						 INNER JOIN arocrm_accountbillads ON arocrm_accountbillads.accountaddressid = arocrm_account.accountid
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_account.accountid=".$account);
		$rows = mysql_fetch_array($result);
		$city = $rows['bill_city'];
		if($city == $district)
		{
			$rel = mysql_query("SELECT arocrm_inventoryproductrel.*, arocrm_crmentity.* FROM  arocrm_inventoryproductrel
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_inventoryproductrel.id=".$salesorder);
			while($relrw = mysql_fetch_array($rel))
			{
				$productid = $relrw['productid'];
				$pqry = mysql_query("SELECT arocrm_products.*, arocrm_crmentity.* FROM  arocrm_products
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$productid);
				$prow = mysql_fetch_array($pqry);
				$category = $prow['productcategory'];
				$productcode = $prow['productcode'];
				if($category == '4W')
				{
					if($productcode == $code)
					{
						$qty4W = $qty4W + $relrw['quantity'];
						$qty4W = round($qty4W/$div);
					}
				}
				if($category == '2W')
				{
					if($productcode == $code)
					{
						$qty2W = $qty2W + $relrw['quantity'];
						$qty2W = round($qty2W/$div);
					}
				}
				if($category == 'IB')
				{
					if($productcode == $code)
					{
						$qtyib = $qtyib + $relrw['quantity'];
						$qtyib = round($qtyib/$div);
					}
				}
				if($category == 'ER')
				{
					if($productcode == $code)
					{
						$qtyer = $qtyer + $relrw['quantity'];
						$qtyer = round($qtyer/$div);
					}
				}
			}
		}
	}
	$response['qty4w'] = $qty4W;
	$response['qty2w'] = $qty2W;
	$response['qtyib'] = $qtyib;
	$response['qtyer'] = $qtyer;
	return $response;
}
function getTotalSalesOrder($yr, $districtid, $div)
{
	$response = array();
	$qty4W = 0;
	$qty2W = 0;
	$qtyib = 0;
	$qtyer = 0;
	$sql = mysql_query("SELECT arocrm_district.*, arocrm_crmentity.* FROM  arocrm_district
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_district.districtid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_district.districtid=".$districtid);
	$rw = mysql_fetch_array($sql);
	$district = $rw['name'];
	$qry =  mysql_query("SELECT arocrm_invoice.*,arocrm_invoicecf.*,arocrm_crmentity.* FROM arocrm_invoice INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid WHERE arocrm_crmentity.deleted=0");
	while($row = mysql_fetch_array($qry))
	{
		$date = $row['cf_4627'];
		$dt = explode("-",$date[0]);
		$year = $yr - 1;
		if($dt[0] == $yr)
		{
			if($dt[1]=='01' || $dt[1] == '02' || $dt[1] == '03')
			{
				$invoice = $row['invoiceid'];
			}
		}
		if($dt[0] == $year)
		{
			if($dt[1]=='04' || $dt[1] == '05' || $dt[1] == '06' || $dt[1] == '07' || $dt[1] == '08' || $dt[1] == '09' || $dt[1] == '10' || $dt[1] == '11' || $dt[1] == '12')
			{
				$invoice = $row['invoiceid'];
			}
		}
		$sqlqry = mysql_query("SELECT arocrm_invoice.*,arocrm_crmentity.* FROM arocrm_invoice 
		INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
		WHERE arocrm_crmentity.deleted=0 AND arocrm_invoice.invoiceid=".$invoice);
		$rws = mysql_fetch_array($sqlqry);
		$account = $rws['customerno'];
		$result = mysql_query("SELECT arocrm_account.*, arocrm_accountscf.*, arocrm_accountbillads.*, arocrm_crmentity.* FROM arocrm_account
                         INNER JOIN arocrm_accountscf ON arocrm_accountscf.accountid = arocrm_account.accountid
						 INNER JOIN arocrm_accountbillads ON arocrm_accountbillads.accountaddressid = arocrm_account.accountid
						 INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_account.accountid=".$account);
		$rows = mysql_fetch_array($result);
		$city = $rows['bill_city'];
		if($city == $district)
		{
			$rel = mysql_query("SELECT arocrm_inventoryproductrel.*, arocrm_crmentity.* FROM  arocrm_inventoryproductrel
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_inventoryproductrel.id=".$invoice);
			while($relrw = mysql_fetch_array($rel))
			{
				$productid = $relrw['productid'];
				$pqry = mysql_query("SELECT arocrm_products.*, arocrm_crmentity.* FROM  arocrm_products
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productid=".$productid);
				$prow = mysql_fetch_array($pqry);
				$category = $prow['productcategory'];
				if($category == '4W')
				{
					$qty4W = $qty4W + $relrw['quantity'];
					$qty4W = round($qty4W/$div);
				}
				if($category == '2W')
				{
					$qty2W = $qty2W + $relrw['quantity'];
					$qty2W = round($qty2W/$div);
				}
				if($category == 'IB')
				{
					$qtyib = $qtyib + $relrw['quantity'];
					$qtyib = round($qtyib/$div);
				}
				if($category == 'ER')
				{
					$qtyer = $qtyer + $relrw['quantity'];
					$qtyer = round($qtyer/$div);
				}
			}
		}
	}
	$response['qty4w'] = $qty4W;
	$response['qty2w'] = $qty2W;
	$response['qtyib'] = $qtyib;
	$response['qtyer'] = $qtyer;
	return $response;
}
function getallCategory($postingdate)
{
	$response = array();
	$date = explode('-',$postingdate);
	$curyear = $date[2];
	$tbodycat = "";
	$rowcountcat = 1;
	$resultcat = mysql_query("SELECT * FROM `arocrm_productcategory`");
	$i = 1;
	while($rowcat = mysql_fetch_array($resultcat))
		{
			$aprilactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-04-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$aprilsalerow = mysql_fetch_array($aprilactualsale);
			$aprilsaleqty = $aprilsalerow['quantity'];
			$aprilactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-04-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-04-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-04-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$aprilsalesreturnrow = mysql_fetch_array($aprilactualreturnsql);
			$aprilsalesreturnqty = $aprilsalesreturnrow['qty'];
			$aprilactualqty = $aprilsaleqty - $aprilsalesreturnqty;
			
			$mayactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-05-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$maysalerow = mysql_fetch_array($mayactualsale);
			$maysaleqty = $maysalerow['quantity'];
			$mayactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-05-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-05-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-05-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$maysalesreturnrow = mysql_fetch_array($mayactualreturnsql);
			$maysalesreturnqty = $maysalesreturnrow['qty'];
			$mayactualqty = $maysaleqty - $maysalesreturnqty;
			
			$juneactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-06-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$junesalerow = mysql_fetch_array($juneactualsale);
			$junesaleqty = $junesalerow['quantity'];
			$juneactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-06-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-06-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-06-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$junesalesreturnrow = mysql_fetch_array($juneactualreturnsql);
			$junesalesreturnqty = $junesalesreturnrow['qty'];
			$juneactualqty = $junesaleqty - $junesalesreturnqty;
			
			$julyactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-07-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$julysalerow = mysql_fetch_array($julyactualsale);
			$julysaleqty = $julysalerow['quantity'];
			$julyactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-07-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-07-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-07-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$julysalesreturnrow = mysql_fetch_array($julyactualreturnsql);
			$julysalesreturnqty = $julysalesreturnrow['qty'];
			$julyactualqty = $julysaleqty - $julysalesreturnqty;
			
			$augactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-08-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$augsalerow = mysql_fetch_array($augactualsale);
			$augsaleqty = $augsalerow['quantity'];
			$augactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-08-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-08-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-08-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$augsalesreturnrow = mysql_fetch_array($augactualreturnsql);
			$augsalesreturnqty = $augsalesreturnrow['qty'];
			$augactualqty = $augsaleqty - $augsalesreturnqty;
			
			$sepactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-09-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$sepsalerow = mysql_fetch_array($sepactualsale);
			$sepsaleqty = $sepsalerow['quantity'];
			$sepactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-09-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-09-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-09-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$sepsalesreturnrow = mysql_fetch_array($sepactualreturnsql);
			$sepsalesreturnqty = $sepsalesreturnrow['qty'];
			$sepactualqty = $sepsaleqty - $sepsalesreturnqty;
			
			$octactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-10-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$octsalerow = mysql_fetch_array($octactualsale);
			$octsaleqty = $octsalerow['quantity'];
			$octactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-10-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-10-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-10-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$octsalesreturnrow = mysql_fetch_array($octactualreturnsql);
			$octsalesreturnqty = $octsalesreturnrow['qty'];
			$octactualqty = $octsaleqty - $octsalesreturnqty;
			
			$novactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-11-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$novsalerow = mysql_fetch_array($novactualsale);
			$novsaleqty = $novsalerow['quantity'];
			$novactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-11-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-11-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-11-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$novsalesreturnrow = mysql_fetch_array($novactualreturnsql);
			$novsalesreturnqty = $novsalesreturnrow['qty'];
			$novactualqty = $novsaleqty - $novsalesreturnqty;
			
			$decactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-12-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$decsalerow = mysql_fetch_array($decactualsale);
			$decsaleqty = $decsalerow['quantity'];
			$decactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-12-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-12-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-12-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$decsalesreturnrow = mysql_fetch_array($decactualreturnsql);
			$decsalesreturnqty = $decsalesreturnrow['qty'];
			$decactualqty = $decsaleqty - $decsalesreturnqty;
			
			$janactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-01-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$jansalerow = mysql_fetch_array($janactualsale);
			$jansaleqty = $jansalerow['quantity'];
			$janactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-01-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-01-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-01-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$jansalesreturnrow = mysql_fetch_array($janactualreturnsql);
			$jansalesreturnqty = $jansalesreturnrow['qty'];
			$janactualqty = $jansaleqty - $jansalesreturnqty;
			
			$febactualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-02-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$febsalerow = mysql_fetch_array($febactualsale);
			$febsaleqty = $febsalerow['quantity'];
			$febactualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-02-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-02-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-02-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$febsalesreturnrow = mysql_fetch_array($febactualreturnsql);
			$febsalesreturnqty = $febsalesreturnrow['qty'];
			$febactualqty = $febsaleqty - $febsalesreturnqty;
			
			$maractualsale = mysql_query("SELECT SUM(arocrm_inventoryproductrel.quantity) AS quantity FROM arocrm_inventoryproductrel 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inventoryproductrel.id 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inventoryproductrel.id IN 
			(SELECT arocrm_invoice.invoiceid FROM arocrm_invoice 
			INNER JOIN arocrm_invoicecf ON arocrm_invoicecf.invoiceid = arocrm_invoice.invoiceid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_invoice.invoiceid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_invoice.invoicestatus = 'Approved' AND arocrm_invoicecf.cf_4627 LIKE '".$curyear."-03-%') 
			AND arocrm_inventoryproductrel.productid IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$marsalerow = mysql_fetch_array($maractualsale);
			$marsaleqty = $marsalerow['quantity'];
			$maractualreturnsql = mysql_query("SELECT SUM(arocrm_goodsreceipt_line_item_details_lineitem.cf_1907) AS qty FROM arocrm_goodsreceipt_line_item_details_lineitem 
			INNER JOIN arocrm_goodsreceiptcf ON arocrm_goodsreceiptcf.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_goodsreceipt ON arocrm_goodsreceipt.goodsreceiptid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_goodsreceipt_line_item_details_lineitem.goodsreceiptid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_goodsreceiptcf.cf_4824 = 'Approved' AND arocrm_goodsreceiptcf.cf_3223 LIKE '".$curyear."-03-%' AND arocrm_goodsreceipt.cf_nrl_inbounddelivery708_id IN 
			(SELECT arocrm_inbounddelivery.inbounddeliveryid FROM arocrm_inbounddelivery 
			INNER JOIN arocrm_inbounddeliverycf ON arocrm_inbounddeliverycf.inbounddeliveryid = arocrm_inbounddelivery.inbounddeliveryid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_inbounddelivery.inbounddeliveryid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_inbounddeliverycf.cf_3659 = 'Approved' AND arocrm_inbounddeliverycf.cf_3200 LIKE '".$curyear."-03-%' AND arocrm_inbounddelivery.cf_nrl_salesreturn419_id IN 
			(SELECT arocrm_salesreturn.salesreturnid FROM arocrm_salesreturn 
			INNER JOIN arocrm_salesreturncf ON arocrm_salesreturncf.salesreturnid = arocrm_salesreturn.salesreturnid 
			INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesreturn.salesreturnid 
			WHERE arocrm_crmentity.deleted = 0 AND arocrm_salesreturncf.cf_4819 = 'Approved' AND arocrm_salesreturncf.cf_4817 LIKE '".$curyear."-03-%')) 
			AND arocrm_goodsreceipt_line_item_details_lineitem.cf_1897 IN (SELECT productid FROM arocrm_products WHERE productcategory = '".$rowcat['productcategory']."' GROUP BY productcategory)");
			$marsalesreturnrow = mysql_fetch_array($maractualreturnsql);
			$marsalesreturnqty = $marsalesreturnrow['qty'];
			$maractualqty = $marsaleqty - $marsalesreturnqty;
			
			$tbodycat .='<tr id="Category_Wise__row_'.$i.'" class="tr_clone">';
				$tbodycat .='<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				$tbodycat .='<td class="fieldValue"><select data-fieldname="cf_4399" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_4399_'.$i.'" data-selected-value=" " tabindex="-1" title="" id="cf_4399_'.$i.'"><option value="">Select an Option</option><option value="4W" '.(($rowcat['productcategory']=="4W")?"selected='selected'":'').'>4W</option><option value="2W" '.(($rowcat['productcategory']=="2W")?"selected='selected'":'').'>2W</option><option value="IB" '.(($rowcat['productcategory']=="IB")?"selected='selected'":'').'>IB</option><option value="ER" '.(($rowcat['productcategory']=="ER")?"selected='selected'":'').'>ER</option></select>
				<script>
							$(document).ready(function(){
							$("#cf_4399_'.$i.'").select2();
							});
							$("#cf_4399_'.$i.'").select2().select2("readonly","true");
				</script>
				</td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4401_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4401_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4403_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4403_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4405_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4405_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4407_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4407_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4409_'.$i.'" type="text" data-fieldname="cf_4409" data-fieldtype="string" class="inputElement " name="cf_4409_'.$i.'" readonly value=""></td>';
				//$tbodycat .='<td class="fieldValue"><input id="cf_4411_'.$i.'"" type="text" data-fieldname="cf_4411" data-fieldtype="string" class="inputElement " name="cf_4411_'.$i.'" readonly value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4413_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4413_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4415_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4415_'.$i.'" value="" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4417_'.$i.'" type="text" class="form-control" data-field-id="4418" name="cf_4417_'.$i.'" value="" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4419_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4419_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4421_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4421_'.$i.'" value="'.$aprilactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4423_'.$i.'" type="text" class="form-control" data-field-id="4424" name="cf_4423_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4425_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4425_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4427_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4427_'.$i.'" value="'.$mayactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4429_'.$i.'" type="text" class="form-control" data-field-id="4430" name="cf_4429_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4439_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4439_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4449_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4449_'.$i.'" value="'.$juneactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4459_'.$i.'" type="text" class="form-control" data-field-id="4460" name="cf_4459_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4461_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4461_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4463_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4463_'.$i.'" value="'.$julyactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4465_'.$i.'" type="text" class="form-control" data-field-id="4466" name="cf_4465_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4467_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4467_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4469_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4469_'.$i.'" value="'.$augactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4471_'.$i.'" type="text" class="form-control" data-field-id="4472" name="cf_4471_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4473_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4473_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4475_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4475_'.$i.'" value="'.$sepactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4477_'.$i.'" type="text" class="form-control" data-field-id="4478" name="cf_4477_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4479_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4479_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4481_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4481_'.$i.'" value="'.$octactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4483_'.$i.'" type="text" class="form-control" data-field-id="4484" name="cf_4483_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4485_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4485_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4487_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4487_'.$i.'" value="'.$novactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4489_'.$i.'" type="text" class="form-control" data-field-id="4490" name="cf_4489_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4491_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4491_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4493_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4493_'.$i.'" value="'.$decactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4495_'.$i.'" type="text" class="form-control" data-field-id="4496" name="cf_4495_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4497_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4497_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4499_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4499_'.$i.'" value="'.$janactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4501_'.$i.'" type="text" class="form-control" data-field-id="4502" name="cf_4501_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4503_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4503_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4505_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4505_'.$i.'" value="'.$febactualqty.'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4507_'.$i.'" type="text" class="form-control" data-field-id="4508" name="cf_4507_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4509_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4509_'.$i.'" value=""></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4511_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4511_'.$i.'" value="'.$maractualqty.'" readonly></td>';
			$tbodycat .='</tr>';
			if($i!='1')
			{
				$rowcountcat = $rowcountcat.",".$i;
			}
			$i++;
		}
		$response['tbodycat'] = $tbodycat;
		$response['rowcountcat'] = $rowcountcat;
		return $response;
}
function getSalesBudget($id)
{
	$response = array();
	$result = mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudgetcf.*, arocrm_crmentity.* FROM  arocrm_salesbudget
	INNER JOIN arocrm_salesbudgetcf ON arocrm_salesbudgetcf.salesbudgetid = arocrm_salesbudget.salesbudgetid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_salesbudget.salesbudgetid='".$id."'");
	$row = mysql_fetch_array($result);
	$cpid = $row['cf_nrl_plantmaster615_id'];
	$rescp = mysql_query("SELECT arocrm_plantmaster.*, arocrm_crmentity.* FROM arocrm_plantmaster
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_plantmaster.plantmasterid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_plantmaster.plantmasterid='".$cpid."'");
	$rowcp = mysql_fetch_array($rescp);
	$cpname = $rowcp['name'];
	$customerid = $row['cf_nrl_accounts462_id'];
	$rescus = mysql_query("SELECT arocrm_account.*, arocrm_crmentity.* FROM arocrm_account
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_account.accountid
	WHERE arocrm_crmentity.deleted=0 AND arocrm_account.accountid='".$customerid."'");
	$rowcus = mysql_fetch_array($rescus);
	$customer = $rowcus['accountname'];
	$district = $row['cf_2819'];
	$state = $row['cf_2821'];
	$place = $row['cf_3473'];
	$nature = $row['cf_2823'];
	$grade = $row['cf_2825'];
	$year = $row['cf_3424'];
	$resultcat = mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudget_category_wise_lineitem.*, arocrm_crmentity.* FROM  arocrm_salesbudget
	INNER JOIN arocrm_salesbudget_category_wise_lineitem ON arocrm_salesbudget_category_wise_lineitem.salesbudgetid = arocrm_salesbudget.salesbudgetid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_salesbudget.salesbudgetid='".$id."'");
	$countcat = mysql_num_rows($resultcat);
	$tbodycat = "";
	$rowcountcat = 1;
	$i = 1;
	if($countcat > 0)
	{
		while($rowcat = mysql_fetch_array($resultcat))
		{
			$tbodycat .='<tr id="Category_Wise__row_'.$i.'" class="tr_clone">';
				if($countcat == 1)
				{
					$tbodycat .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				else
				{
					$tbodycat .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				$tbodycat .='<td class="fieldValue"><select data-fieldname="cf_4399" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="cf_4399_'.$i.'" data-selected-value=" " tabindex="-1" title="" id="cf_4399_'.$i.'"><option value="">Select an Option</option><option value="4W" '.(($rowcat['cf_4399']=="4W")?"selected='selected'":'').'>4W</option><option value="2W" '.(($rowcat['cf_4399']=="2W")?"selected='selected'":'').'>2W</option><option value="IB" '.(($rowcat['cf_4399']=="IB")?"selected='selected'":'').'>IB</option><option value="ER" '.(($rowcat['cf_4399']=="ER")?"selected='selected'":'').'>ER</option></select>
				<script>
							$(document).ready(function(){
							$("#cf_4399_'.$i.'").select2();
							});
							$("#cf_4399_'.$i.'").select2().select2("readonly","true");
				</script>
				</td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4401_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4401_'.$i.'" readonly value="'.$rowcat['cf_4401'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4403_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4403_'.$i.'" readonly value="'.$rowcat['cf_4403'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4405_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4405_'.$i.'" readonly value="'.$rowcat['cf_4405'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4407_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4407_'.$i.'" readonly value="'.$rowcat['cf_4407'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4409_'.$i.'" type="text" data-fieldname="cf_4409" data-fieldtype="string" class="inputElement " name="cf_4409_'.$i.'" readonly value="'.$rowcat['cf_4409'].'"></td>';
				//$tbodycat .='<td class="fieldValue"><input id="cf_4411_'.$i.'"" type="text" data-fieldname="cf_4411" data-fieldtype="string" class="inputElement " name="cf_4411_'.$i.'" readonly value="'.$rowcat['cf_4411'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4413_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4413_'.$i.'" readonly value="'.$rowcat['cf_4413'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4415_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4415_'.$i.'" value="'.$rowcat['cf_4415'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4417_'.$i.'" type="text" class="form-control" data-field-id="4418" name="cf_4417_'.$i.'" readonly value="'.$rowcat['cf_4417'].'" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4419_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4419_'.$i.'" readonly value="'.$rowcat['cf_4419'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4421_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4421_'.$i.'" value="'.$rowcat['cf_4421'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4423_'.$i.'" type="text" class="form-control" data-field-id="4424" name="cf_4423_'.$i.'" readonly value="'.$rowcat['cf_4423'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4425_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4425_'.$i.'" readonly value="'.$rowcat['cf_4425'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4427_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4427_'.$i.'" value="'.$rowcat['cf_4427'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4429_'.$i.'" type="text" class="form-control" data-field-id="4430" name="cf_4429_'.$i.'" readonly value="'.$rowcat['cf_4429'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4439_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4439_'.$i.'" readonly value="'.$rowcat['cf_4439'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4449_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4449_'.$i.'" value="'.$rowcat['cf_4449'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4459_'.$i.'" type="text" class="form-control" data-field-id="4460" name="cf_4459_'.$i.'" readonly value="'.$rowcat['cf_4459'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4461_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4461_'.$i.'" readonly value="'.$rowcat['cf_4461'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4463_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4463_'.$i.'" value="'.$rowcat['cf_4463'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4465_'.$i.'" type="text" class="form-control" data-field-id="4466" name="cf_4465_'.$i.'" readonly value="'.$rowcat['cf_4465'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4467_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4467_'.$i.'" readonly value="'.$rowcat['cf_4467'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4469_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4469_'.$i.'" value="'.$rowcat['cf_4469'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4471_'.$i.'" type="text" class="form-control" data-field-id="4472" name="cf_4471_'.$i.'" readonly value="'.$rowcat['cf_4471'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4473_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4473_'.$i.'" readonly value="'.$rowcat['cf_4473'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4475_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4475_'.$i.'" value="'.$rowcat['cf_4475'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4477_'.$i.'" type="text" class="form-control" data-field-id="4478" name="cf_4477_'.$i.'" readonly value="'.$rowcat['cf_4477'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4479_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4479_'.$i.'" readonly value="'.$rowcat['cf_4479'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4481_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4481_'.$i.'" value="'.$rowcat['cf_4481'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4483_'.$i.'" type="text" class="form-control" data-field-id="4484" name="cf_4483_'.$i.'" readonly value="'.$rowcat['cf_4483'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4485_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4485_'.$i.'" readonly value="'.$rowcat['cf_4485'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4487_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4487_'.$i.'" value="'.$rowcat['cf_4487'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4489_'.$i.'" type="text" class="form-control" data-field-id="4490" name="cf_4489_'.$i.'" readonly value="'.$rowcat['cf_4489'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4491_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4491_'.$i.'" readonly value="'.$rowcat['cf_4491'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4493_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4493_'.$i.'" value="'.$rowcat['cf_4493'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4495_'.$i.'" type="text" class="form-control" data-field-id="4496" name="cf_4495_'.$i.'" readonly value="'.$rowcat['cf_4495'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4497_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4497_'.$i.'" readonly value="'.$rowcat['cf_4497'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4499_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4499_'.$i.'" value="'.$rowcat['cf_4499'].'" readonly></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4501_'.$i.'" type="text" class="form-control" data-field-id="4502" name="cf_4501_'.$i.'" readonly value="'.$rowcat['cf_4501'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4503_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4503_'.$i.'" readonly value="'.$rowcat['cf_4503'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4505_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4505_'.$i.'" value="'.$rowcat['cf_4505'].'" readonly=""></td>';
				$tbodycat .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_4507_'.$i.'" type="text" class="form-control" data-field-id="4508" name="cf_4507_'.$i.'" readonly value="'.$rowcat['cf_4507'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4509_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4509_'.$i.'" readonly value="'.$rowcat['cf_4509'].'"></td>';
				$tbodycat .='<td class="fieldValue"><input id="cf_4511_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_4511_'.$i.'" value="'.$rowcat['cf_4511'].'" readonly></td>';
			$tbodycat .='</tr>';
			if($i!='1')
			{
				$rowcountcat = $rowcountcat.",".$i;
			}
			$i++;
		}
	}
	/*
	$result4W = mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudget_4w_lineitem.*, arocrm_crmentity.* FROM  arocrm_salesbudget
	INNER JOIN arocrm_salesbudget_4w_lineitem ON arocrm_salesbudget_4w_lineitem.salesbudgetid = arocrm_salesbudget.salesbudgetid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_salesbudget.salesbudgetid='".$id."'");
	$count4W = mysql_num_rows($result4W);
	$tbody4W = "";
	$rowcount4W = 1;
	$i = 1;
	if($count4W > 0)
	{
		while($row4W = mysql_fetch_array($result4W))
		{
			$tbody4W .='<tr id="4W__row_'.$i.'" class="tr_clone">';
				if($count4W == 1)
				{
					$tbody4W .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				else
				{
					$tbody4W .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				$product = getProductDetails($row4W['cf_2805']);
				$productname = $product['productname'];
				
				$tbody4W .='
    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products">
    <div class="input-group">
    <input name="cf_2805_'.$i.'" type="hidden" value="'.$row4W['cf_2805'].'" class="sourceField" data-displayvalue="">
    <input id="cf_2805_display_'.$i.'" style="min-width:280px;" name="cf_2805_display_'.$i.'" data-fieldname="cf_2805" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"
    readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
    </div>
    </div>
    </td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2803_'.$i.'" type="text" data-fieldname="cf_2803" data-fieldtype="string" class="inputElement " name="cf_2803_'.$i.'" readonly value="'.$row4W['cf_2803'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_3106_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3106_'.$i.'" readonly value="'.$row4W['cf_3106'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_3104_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3104_'.$i.'" readonly value="'.$row4W['cf_3104'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2337_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2337_'.$i.'" readonly value="'.$row4W['cf_2337'].'"></td>';

				$tbody4W .='<td class="fieldValue"><input id="cf_2343_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2343_'.$i.'" readonly value="'.$row4W['cf_2343'].'"></td>';

				$tbody4W .='<td class="fieldValue"><input id="cf_2341_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2341_'.$i.'" value="'.$row4W['cf_2341'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2353_'.$i.'" type="text" class="form-control" data-field-id="2354" name="cf_2353_'.$i.'" readonly value="'.$row4W['cf_2353'].'" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2355_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2355_'.$i.'" readonly value="'.$row4W['cf_2355'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2357_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2357_'.$i.'" value="'.$row4W['cf_2357'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2359_'.$i.'" type="text" class="form-control" data-field-id="2360" name="cf_2359_'.$i.'" readonly value="'.$row4W['cf_2359'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2361_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2361_'.$i.'" readonly value="'.$row4W['cf_2361'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2363_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2363_'.$i.'" value="'.$row4W['cf_2363'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2365_'.$i.'" type="text" class="form-control" data-field-id="2366" name="cf_2365_'.$i.'" readonly value="'.$row4W['cf_2365'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2367_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2367_'.$i.'" readonly value="'.$row4W['cf_2367'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2369_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2369_'.$i.'" value="'.$row4W['cf_2369'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2371_'.$i.'" type="text" class="form-control" data-field-id="2372" name="cf_2371_'.$i.'" readonly value="'.$row4W['cf_2371'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2373_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2373_'.$i.'" readonly value="'.$row4W['cf_2373'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2375_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2375_'.$i.'" value="'.$row4W['cf_2375'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2377_'.$i.'" type="text" class="form-control" data-field-id="2378" name="cf_2377_'.$i.'" readonly value="'.$row4W['cf_2377'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2379_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2379_'.$i.'" readonly value="'.$row4W['cf_2379'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2381_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2381_'.$i.'" value="'.$row4W['cf_2381'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2383_'.$i.'" type="text" class="form-control" data-field-id="2384" name="cf_2383_'.$i.'" readonly value="'.$row4W['cf_2383'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2385_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2385_'.$i.'" readonly value="'.$row4W['cf_2385'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2387_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2387_'.$i.'" value="'.$row4W['cf_2387'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2389_'.$i.'" type="text" class="form-control" data-field-id="2390" name="cf_2389_'.$i.'" readonly value="'.$row4W['cf_2389'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2391_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2391_'.$i.'" readonly value="'.$row4W['cf_2391'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2393_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2393_'.$i.'" value="'.$row4W['cf_2393'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2395_'.$i.'" type="text" class="form-control" data-field-id="2396" name="cf_2395_'.$i.'" readonly value="'.$row4W['cf_2395'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2397_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2397_'.$i.'" readonly value="'.$row4W['cf_2397'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2399_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2399_'.$i.'" value="'.$row4W['cf_2399'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2401_'.$i.'" type="text" class="form-control" data-field-id="2402" name="cf_2401_'.$i.'" readonly value="'.$row4W['cf_2401'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2403_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2403_'.$i.'" readonly value="'.$row4W['cf_2403'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2405_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2405_'.$i.'" value="'.$row4W['cf_2405'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2407_'.$i.'" type="text" class="form-control" data-field-id="2408" name="cf_2407_'.$i.'" readonly value="'.$row4W['cf_2407'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2409_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2409_'.$i.'" readonly value="'.$row4W['cf_2409'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2411_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2411_'.$i.'" value="'.$row4W['cf_2411'].'" readonly></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2415_'.$i.'" type="text" class="form-control" data-field-id="2416" name="cf_2415_'.$i.'" readonly value="'.$row4W['cf_2415'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2418_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2418_'.$i.'" readonly value="'.$row4W['cf_2418'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2420_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2420_'.$i.'" value="'.$row4W['cf_2420'].'" readonly=""></td>';
				$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2423_'.$i.'" type="text" class="form-control" data-field-id="2424" name="cf_2423_'.$i.'" readonly value="'.$row4W['cf_2423'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2426_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2426_'.$i.'" readonly value="'.$row4W['cf_2426'].'"></td>';
				$tbody4W .='<td class="fieldValue"><input id="cf_2428_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2428_'.$i.'" value="'.$row4W['cf_2428'].'" readonly></td>';
			$tbody4W .='</tr>';
			if($i!='1')
			{
				$rowcount4W = $rowcount4W.",".$i;
			}
			$i++;
		}
	}
	$result2W = mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudget_2w_lineitem.*, arocrm_crmentity.* FROM  arocrm_salesbudget
	INNER JOIN arocrm_salesbudget_2w_lineitem ON arocrm_salesbudget_2w_lineitem.salesbudgetid = arocrm_salesbudget.salesbudgetid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_salesbudget.salesbudgetid='".$id."'");
	$count2W = mysql_num_rows($result2W);
	$tbody2W = "";
	$rowcount2W = 1;
	$i = 1;
	if($count2W > 0)
	{
		while($row2W = mysql_fetch_array($result2W))
		{
			$tbody2W .='<tr id="2W__row_'.$i.'" class="tr_clone">';
				if($count2W == '1')
				{
					$tbody2W .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				else
				{
					$tbody2W .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				$product = getProductDetails($row2W['cf_2809']);
				$productname = $product['productname'];
				
				$tbody2W .='
<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products">
<div class="input-group">
<input name="cf_2809_'.$i.'" type="hidden" value="'.$row2W['cf_2809'].'" class="sourceField" data-displayvalue="">
<input id="cf_2809_display_'.$i.'" style="min-width:280px;" name="cf_2809_display_'.$i.'" data-fieldname="cf_2809" data-fieldtype="reference"
type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"
readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
</div>
</div>
</td>';

				$tbody2W .='<td class="fieldValue"><input id="cf_2807_'.$i.'" type="text" data-fieldname="cf_2807" data-fieldtype="string" class="inputElement " name="cf_2807_'.$i.'" readonly value="'.$row2W['cf_2807'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_3110_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3110_'.$i.'" readonly value="'.$row2W['cf_3110'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_3108_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3108_'.$i.'" readonly value="'.$row2W['cf_3108'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2444_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2444_'.$i.'" readonly value="'.$row2W['cf_2444'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2448_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2448_'.$i.'" readonly value="'.$row2W['cf_2448'].'"></td>';

				$tbody2W .='<td class="fieldValue"><input id="cf_2446_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2446_'.$i.'" value="'.$row2W['cf_2446'].'" readonly></td>';

				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2450_'.$i.'" type="text" class="form-control" data-field-id="2451" name="cf_2450_'.$i.'" value="'.$row2W['cf_2450'].'" readonly step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2452_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2452_'.$i.'" readonly value="'.$row2W['cf_2452'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2456_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2456_'.$i.'" value="'.$row2W['cf_2456'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2462_'.$i.'" type="text" class="form-control" data-field-id="2463" name="cf_2462_'.$i.'" value="'.$row2W['cf_2462'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2466_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2466_'.$i.'" readonly value="'.$row2W['cf_2466'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2470_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2370_'.$i.'" readonly value="'.$row2W['cf_2370'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2472_'.$i.'" type="text" class="form-control" data-field-id="2473" name="cf_2472_'.$i.'" value="'.$row2W['cf_2472'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2476_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2476_'.$i.'" readonly value="'.$row2W['cf_2476'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2478_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2478_'.$i.'" readonly value="'.$row2W['cf_2478'].'"></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2482_'.$i.'" type="text" class="form-control" data-field-id="2483" name="cf_2482_'.$i.'" readonly value="'.$row2W['cf_2482'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2486_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2486_'.$i.'" readonly value="'.$row2W['cf_2486'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2488_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2488_'.$i.'" value="'.$row2W['cf_2488'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2492_'.$i.'" type="text" class="form-control" data-field-id="2493" name="cf_2492_'.$i.'" value="'.$row2W['cf_2492'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2494_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2494_'.$i.'" readonly value="'.$row2W['cf_2494'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2498_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2498_'.$i.'" value="'.$row2W['cf_2498'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2502_'.$i.'" type="text" class="form-control" data-field-id="2503" name="cf_2502_'.$i.'" value="'.$row2W['cf_2502'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2506_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2506_'.$i.'" readonly value="'.$row2W['cf_2506'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2510_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2510_'.$i.'" value="'.$row2W['cf_2510'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2514_'.$i.'" type="text" class="form-control" data-field-id="2515" name="cf_2514_'.$i.'" value="'.$row2W['cf_2514'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2518_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2518_'.$i.'" readonly value="'.$row2W['cf_2518'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2522_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2522_'.$i.'" value="'.$row2W['cf_2522'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2524_'.$i.'" type="text" class="form-control" data-field-id="2525" name="cf_2524_'.$i.'" value="'.$row2W['cf_2524'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2526_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2526_'.$i.'" readonly value="'.$row2W['cf_2526'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2530_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2530_'.$i.'" value="'.$row2W['cf_2530'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2534_'.$i.'" type="text" class="form-control" data-field-id="2535" name="cf_2534_'.$i.'" value="'.$row2W['cf_2534'].'" readonly step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2538_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2538_'.$i.'" readonly value="'.$row2W['cf_2538'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2542_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2542_'.$i.'" value="'.$row2W['cf_2542'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2546_'.$i.'" type="text" class="form-control" data-field-id="2547" name="cf_2546_'.$i.'" readonly value="'.$row2W['cf_2546'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2550_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2550_'.$i.'" readonly value="'.$row2W['cf_2550'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2554_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2554_'.$i.'" value="'.$row2W['cf_2554'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2558_'.$i.'" type="text" class="form-control" data-field-id="2559" name="cf_2558_'.$i.'" readonly value="'.$row2W['cf_2558'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2562_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2562_'.$i.'" readonly value="'.$row2W['cf_2562'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2566_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2566_'.$i.'" value="'.$row2W['cf_2566'].'" readonly></td>';
				$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2570_'.$i.'" type="text" class="form-control" data-field-id="2571" name="cf_2570_'.$i.'" readonly value="'.$row2W['cf_2570'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2574_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2574_'.$i.'" readonly value="'.$row2W['cf_2574'].'"></td>';
				$tbody2W .='<td class="fieldValue"><input id="cf_2578_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2578_'.$i.'" value="'.$row2W['cf_2578'].'" readonly></td>';
			$tbody2W .='</tr>';
			if($i!='1')
			{
				$rowcount2W = $rowcount2W.",".$i;
			}
			$i++;
		}
	}

	$resultIB = mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudget_ib_lineitem.*, arocrm_crmentity.* FROM  arocrm_salesbudget
	INNER JOIN arocrm_salesbudget_ib_lineitem ON arocrm_salesbudget_ib_lineitem.salesbudgetid = arocrm_salesbudget.salesbudgetid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_salesbudget.salesbudgetid='".$id."'");
	$countIB = mysql_num_rows($resultIB);
	$tbodyIB = "";
	$rowcountIB = 1;
	$i = 1;
	if($countIB > 0)
	{
		while($rowIB = mysql_fetch_array($resultIB))
		{
			$tbodyIB .='<tr id="IB__row_'.$i.'" class="tr_clone">';
				if($countIB == '1')
				{
					$tbodyIB .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				else
				{
					$tbodyIB .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				$product = getProductDetails($rowIB['cf_2813']);
				$productname = $product['productname'];
				
				$tbodyIB .='
    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products">
    <div class="input-group">
    <input name="cf_2813_'.$i.'" type="hidden" value="'.$rowIB['cf_2813'].'" class="sourceField" data-displayvalue="">
    <input id="cf_2813_display_'.$i.'" style="min-width:280px;" name="cf_2813_display_'.$i.'" data-fieldname="cf_2813" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"
    readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
    </div>
    </div>
    </td>';

				$tbodyIB .='<td class="fieldValue"><input id="cf_2811_'.$i.'" type="text" data-fieldname="cf_2811" data-fieldtype="string" class="inputElement " name="cf_2811_'.$i.'" readonly value="'.$rowIB['cf_2811'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_3114_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3114_'.$i.'" readonly value="'.$rowIB['cf_3114'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_3112_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3112_'.$i.'" readonly value="'.$rowIB['cf_3112'].'"></td>';

				$tbodyIB .='<td class="fieldValue"><input id="cf_2468_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2468_'.$i.'" readonly value="'.$rowIB['cf_2468'].'"></td>';

				$tbodyIB .='<td class="fieldValue"><input id="cf_2480_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2480_'.$i.'" readonly value="'.$rowIB['cf_2480'].'"></td>';

				$tbodyIB .='<td class="fieldValue"><input id="cf_2474_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2474_'.$i.'" value="'.$rowIB['cf_2474'].'" readonly></td>';

				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2484_'.$i.'" type="text" class="form-control" data-field-id="2485" name="cf_2484_'.$i.'" readonly value="'.$rowIB['cf_2484'].'" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2490_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2490_'.$i.'" readonly value="'.$rowIB['cf_2490'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2496_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2496_'.$i.'" value="'.$rowIB['cf_2496'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2500_'.$i.'" type="text" class="form-control" data-field-id="2501" name="cf_2500_'.$i.'" readonly value="'.$rowIB['cf_2500'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2504_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2504_'.$i.'" readonly value="'.$rowIB['cf_2504'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2508_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2508_'.$i.'" value="'.$rowIB['cf_2508'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2512_'.$i.'" type="text" class="form-control" data-field-id="2513" name="cf_2512_'.$i.'" readonly value="'.$rowIB['cf_2512'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2516_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2516_'.$i.'" readonly value="'.$rowIB['cf_2516'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2520_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2520_'.$i.'" value="'.$rowIB['cf_2520'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2528_'.$i.'" type="text" class="form-control" data-field-id="2529" name="cf_2528_'.$i.'" readonly value="'.$rowIB['cf_2528'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2532_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2532_'.$i.'" readonly value="'.$rowIB['cf_2532'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2536_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2536_'.$i.'" value="'.$rowIB['cf_2536'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2540_'.$i.'" type="text" class="form-control" data-field-id="2541" name="cf_2540_'.$i.'" readonly value="'.$rowIB['cf_2540'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2544_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2544_'.$i.'" readonly value="'.$rowIB['cf_2544'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2548_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2548_'.$i.'" value="'.$rowIB['cf_2548'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2552_'.$i.'" type="text" class="form-control" data-field-id="2553" name="cf_2552_'.$i.'" readonly value="'.$rowIB['cf_2552'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2556_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2556_'.$i.'" readonly value="'.$rowIB['cf_2556'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2560_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2560_'.$i.'" value="'.$rowIB['cf_2560'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2564_'.$i.'" type="text" class="form-control" data-field-id="2565" name="cf_2564_'.$i.'" readonly value="'.$rowIB['cf_2564'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2568_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2568_'.$i.'" readonly value="'.$rowIB['cf_2568'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2572_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2572_'.$i.'" value="'.$rowIB['cf_2572'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2576_'.$i.'" type="text" class="form-control" data-field-id="2577" name="cf_2576_'.$i.'" readonly value="'.$rowIB['cf_2576'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2580_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2580_'.$i.'" readonly value="'.$rowIB['cf_2580'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2582_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2582_'.$i.'" value="'.$rowIB['cf_2582'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2586_'.$i.'" type="text" class="form-control" data-field-id="2587" name="cf_2586_'.$i.'" readonly value="'.$rowIB['cf_2586'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2588_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2588_'.$i.'" readonly value="'.$rowIB['cf_2588'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2592_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2592_'.$i.'" value="'.$rowIB['cf_2592'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2596_'.$i.'" type="text" class="form-control" data-field-id="2597" name="cf_2596_'.$i.'" readonly value="'.$rowIB['cf_2596'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2598_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2598_'.$i.'" readonly value="'.$rowIB['cf_2598'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2602_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2602_'.$i.'" value="'.$rowIB['cf_2602'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2606_'.$i.'" type="text" class="form-control" data-field-id="2607" name="cf_2606_'.$i.'" readonly value="'.$rowIB['cf_2606'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2608_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2608_'.$i.'" readonly value="'.$rowIB['cf_2608'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2612_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2612_'.$i.'" value="'.$rowIB['cf_2612'].'" readonly></td>';
				$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2616_'.$i.'" type="text" class="form-control" data-field-id="2617" name="cf_2616_'.$i.'" readonly value="'.$rowIB['cf_2616'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2620_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2620_'.$i.'" readonly value="'.$rowIB['cf_2620'].'"></td>';
				$tbodyIB .='<td class="fieldValue"><input id="cf_2622_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2622_'.$i.'" value="'.$rowIB['cf_2622'].'" readonly></td>';
			$tbodyIB .='</tr>';
			if($i!='1')
			{
				$rowcountIB = $rowcountIB.",".$i;
			}
			$i++;
		}
	}
	$resultER = mysql_query("SELECT arocrm_salesbudget.*, arocrm_salesbudget_er_lineitem.*, arocrm_crmentity.* FROM  arocrm_salesbudget
	INNER JOIN arocrm_salesbudget_er_lineitem ON arocrm_salesbudget_er_lineitem.salesbudgetid = arocrm_salesbudget.salesbudgetid
	INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_salesbudget.salesbudgetid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_salesbudget.salesbudgetid='".$id."'");
	$countER = mysql_num_rows($resultER);
	$tbodyER = "";
	$rowcountER = 1;
	$i = 1;
	if($countER > 0)
	{
		while($rowER = mysql_fetch_array($resultER))
		{
			$tbodyER .='<tr id="ER__row_'.$i.'" class="tr_clone">';
				if($countER == '1')
				{
					$tbodyER .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				else
				{
					$tbodyER .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
				}
				$product = getProductDetails($rowER['cf_2817']);
				$productname = $product['productname'];

				$tbodyER .='
    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products">
    <div class="input-group">
    <input name="cf_2817_'.$i.'" type="hidden" value="'.$rowER['cf_2817'].'" class="sourceField" data-displayvalue="">
    <input id="cf_2817_display_'.$i.'" style="min-width:280px;" name="cf_2817_display_'.$i.'" data-fieldname="cf_2817" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname.'"
    readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
    </div>
    </div>
    </td>';

				$tbodyER .='<td class="fieldValue"><input id="cf_2815_'.$i.'" type="text" data-fieldname="cf_2815" data-fieldtype="string" class="inputElement " name="cf_2815_'.$i.'" readonly value="'.$rowER['cf_2815'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_3118_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3118_'.$i.'" readonly value="'.$rowER['cf_3118'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_3116_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3116_'.$i.'" readonly value="'.$rowER['cf_3116'].'"></td>';

				$tbodyER .='<td class="fieldValue"><input id="cf_2594_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2594_'.$i.'" readonly value="'.$rowER['cf_2594'].'"></td>';

				$tbodyER .='<td class="fieldValue"><input id="cf_2604_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2604_'.$i.'" readonly value="'.$rowER['cf_2604'].'"></td>';


				$tbodyER .='<td class="fieldValue"><input id="cf_2600_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2600_'.$i.'" value="'.$rowER['cf_2600'].'" readonly></td>';

				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2610_'.$i.'" type="text" class="form-control" data-field-id="2611" name="cf_2610_'.$i.'" readonly value="'.$rowER['cf_2610'].'" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2614_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2614_'.$i.'" readonly value="'.$rowER['cf_2614'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2618_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2618_'.$i.'" value="'.$rowER['cf_2618'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2624_'.$i.'" type="text" class="form-control" data-field-id="2625" name="cf_2624_'.$i.'" readonly value="'.$rowER['cf_2624'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2626_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2626_'.$i.'" readonly value="'.$rowER['cf_2626'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2628_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2628_'.$i.'" value="'.$rowER['cf_2628'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2630_'.$i.'" type="text" class="form-control" data-field-id="2631" name="cf_2630_'.$i.'" readonly value="'.$rowER['cf_2630'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2632_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2632_'.$i.'" readonly value="'.$rowER['cf_2632'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2634_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2634_'.$i.'" value="'.$rowER['cf_2634'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2636_'.$i.'" type="text" class="form-control" data-field-id="2637" name="cf_2636_'.$i.'" readonly value="'.$rowER['cf_2636'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2638_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2638_'.$i.'" readonly value="'.$rowER['cf_2638'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2640_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2640_'.$i.'" value="'.$rowER['cf_2640'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2642_'.$i.'" type="text" class="form-control" data-field-id="2643" name="cf_2642_'.$i.'" readonly value="'.$rowER['cf_2642'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2644_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2644_'.$i.'" readonly value="'.$rowER['cf_2644'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2646_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2646_'.$i.'" value="'.$rowER['cf_2646'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2648_'.$i.'" type="text" class="form-control" data-field-id="2649" name="cf_2648_'.$i.'" readonly value="'.$rowER['cf_2648'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2650_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2650_'.$i.'" readonly value="'.$rowER['cf_2650'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2652_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2652_'.$i.'" value="'.$rowER['cf_2652'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2654_'.$i.'" type="text" class="form-control" data-field-id="2655" name="cf_2654_'.$i.'" readonly value="'.$rowER['cf_2654'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2656_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2656_'.$i.'" readonly value="'.$rowER['cf_2656'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2658_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2658_'.$i.'" value="'.$rowER['cf_2654'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2660_'.$i.'" type="text" class="form-control" data-field-id="2661" name="cf_2660_'.$i.'" readonly value="'.$rowER['cf_2660'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2662_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2662_'.$i.'" readonly value="'.$rowER['cf_2662'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2664_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2664_'.$i.'" value="'.$rowER['cf_2664'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2666_'.$i.'" type="text" class="form-control" data-field-id="2667" name="cf_2666_'.$i.'" readonly value="'.$rowER['cf_2666'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2668_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2668_'.$i.'" readonly value="'.$rowER['cf_2668'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2670_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2670_'.$i.'" value="'.$rowER['cf_2670'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2672_'.$i.'" type="text" class="form-control" data-field-id="2673" name="cf_2672_'.$i.'" readonly value="'.$rowER['cf_2672'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2674_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2674_'.$i.'" readonly value="'.$rowER['cf_2674'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2676_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2676_'.$i.'" value="'.$rowER['cf_2676'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2678_'.$i.'" type="text" class="form-control" data-field-id="2679" name="cf_2678_'.$i.'" readonly value="'.$rowER['cf_2678'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2680_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2680_'.$i.'" readonly value="'.$rowER['cf_2680'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2682_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2682_'.$i.'" value="'.$rowER['cf_2682'].'" readonly></td>';
				$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2684_'.$i.'" type="text" class="form-control" data-field-id="2685" name="cf_2684_'.$i.'" readonly value="'.$rowER['cf_2684'].'" step="any"><span class="input-group-addon">%</span></div></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2686_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2686_'.$i.'" readonly value="'.$rowER['cf_2686'].'"></td>';
				$tbodyER .='<td class="fieldValue"><input id="cf_2688_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2688_'.$i.'" value="'.$rowER['cf_2688'].'" readonly></td>';
			$tbodyER .='</tr>';
			if($i!='1')
			{
				$rowcountER = $rowcountER.",".$i;
			}
			$i++;
		}
	}
	*/
	$response['cpid'] = $cpid;
	$response['cpname'] = $cpname;
	$response['custid'] = $customerid;
	$response['customer'] = $customer;
	$response['district'] = $district;
	$response['state'] = $state;
	$response['place'] = $place;
	$response['nature'] = $nature;
	$response['grade'] = $grade;
	$response['year'] = $year;
	$response['tbodycat'] = $tbodycat;
	$response['rowcountcat'] = $rowcountcat;
	/*$response['tbody4W'] = $tbody4W;
	$response['tbody2W'] = $tbody2W;
	$response['tbodyIB'] = $tbodyIB;
	$response['tbodyER'] = $tbodyER;
	$response['rowcount4W'] = $rowcount4W;
	$response['rowcount2W'] = $rowcount2W;
	$response['rowcountIB'] = $rowcountIB;
	$response['rowcountER'] = $rowcountER;*/
	return $response;
}



function allProductDetails()
{
	$response = array();
	$result4W = mysql_query("SELECT arocrm_products.*, arocrm_crmentity.* FROM  arocrm_products INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productcategory='4W'");
	$count4W = mysql_num_rows($result4W);
	$tbody4W = "";
	$rowcount4W = 1;
	for($i=1;$i<=$count4W;$i++)
	{
		$tbody4W .='<tr id="4W__row_'.$i.'" class="tr_clone">';


		$row4W = mysql_fetch_array($result4W);
		$productcode4w = $row4W['product_no'];
		$productname4w = $row4W['productname'];
    $productcode4wid = $row4W['productid'];


    $tbody4W .='<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

    $tbody4W .='
    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products">
    <div class="input-group">
    <input name="cf_2805_'.$i.'" type="hidden" value="'.$productcode4wid.'" class="sourceField" data-displayvalue="">
    <input id="cf_2805_display_'.$i.'" style="min-width:280px;" name="cf_2805_display_'.$i.'" data-fieldname="cf_2805" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname4w.'"
    readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
    </div>
    </div>
    </td>';

    $tbody4W .='<td class="fieldValue"><input id="cf_2803_'.$i.'" type="text" data-fieldname="cf_2803" data-fieldtype="string" class="inputElement " name="cf_2803_'.$i.'" value="'.$productcode4w.'"></td>';
    $tbody4W .='<td class="fieldValue"><input id="cf_3106_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3106_'.$i.'" value=""></td>';
    $tbody4W .='<td class="fieldValue"><input id="cf_3104_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3104_'.$i.'" value=""></td>';
    $tbody4W .='<td class="fieldValue"><input id="cf_2337_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2337_'.$i.'" value=""></td>';

    $tbody4W .='<td class="fieldValue"><input id="cf_2343_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2343_'.$i.'" value=""></td>';

	$tbody4W .='<td class="fieldValue"><input id="cf_2341_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2341_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2353_'.$i.'" type="text" class="form-control" data-field-id="2354" name="cf_2353_'.$i.'" value="" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2355_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2355_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2357_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2357_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2359_'.$i.'" type="text" class="form-control" data-field-id="2360" name="cf_2359_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2361_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2361_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2363_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2363_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2365_'.$i.'" type="text" class="form-control" data-field-id="2366" name="cf_2365_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2367_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2367_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2369_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2369_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2371_'.$i.'" type="text" class="form-control" data-field-id="2372" name="cf_2371_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2373_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2373_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2375_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2375_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2377_'.$i.'" type="text" class="form-control" data-field-id="2378" name="cf_2377_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2379_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2379_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2381_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2381_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2383_'.$i.'" type="text" class="form-control" data-field-id="2384" name="cf_2383_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2385_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2385_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2387_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2387_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2389_'.$i.'" type="text" class="form-control" data-field-id="2390" name="cf_2389_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2391_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2391_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2393_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2393_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2395_'.$i.'" type="text" class="form-control" data-field-id="2396" name="cf_2395_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2397_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2397_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2399_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2399_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2401_'.$i.'" type="text" class="form-control" data-field-id="2402" name="cf_2401_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2403_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2403_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2405_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2405_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2407_'.$i.'" type="text" class="form-control" data-field-id="2408" name="cf_2407_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2409_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2409_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2411_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2411_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2415_'.$i.'" type="text" class="form-control" data-field-id="2416" name="cf_2415_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2418_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2418_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2420_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2420_'.$i.'" value="" readonly=""></td>';
																																		$tbody4W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2423_'.$i.'" type="text" class="form-control" data-field-id="2424" name="cf_2423_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2426_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2426_'.$i.'" value=""></td>';
																																		$tbody4W .='<td class="fieldValue"><input id="cf_2428_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2428_'.$i.'" value="" readonly=""></td>';
	$tbody4W .='</tr>';
	if($i!='1')
	{
		$rowcount4W = $rowcount4W.",".$i;
	}
	}

	$result2W = mysql_query("SELECT arocrm_products.*, arocrm_crmentity.* FROM  arocrm_products INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productcategory='2W'");
	$count2W = mysql_num_rows($result2W);
	$tbody2W = "";
	$rowcount2W = 1;
	for($i=1;$i<=$count2W;$i++)
	{
		$tbody2W .='<tr id="2W__row_'.$i.'" class="tr_clone">';
		$row2W = mysql_fetch_array($result2W);
		$productcode2w = $row2W['product_no'];
$productname2w = $row2W['productname'];
$productcode2wid = $row2W['productid'];


$tbody2W .='<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

$tbody2W .='
<td class="fieldValue">
<div class="referencefield-wrapper ">
<input name="popupReferenceModule" type="hidden" value="Products">
<div class="input-group">
<input name="cf_2809_'.$i.'" type="hidden" value="'.$productcode2wid.'" class="sourceField" data-displayvalue="">
<input id="cf_2809_display_'.$i.'" style="min-width:280px;" name="cf_2809_display_'.$i.'" data-fieldname="cf_2809" data-fieldtype="reference"
type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productname2w.'"
readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
</div>
</div>
</td>';

$tbody2W .='<td class="fieldValue"><input id="cf_2807_'.$i.'" type="text" data-fieldname="cf_2807" data-fieldtype="string" class="inputElement " name="cf_2807_'.$i.'" value="'.$productcode2w.'"></td>';


$tbody2W .='<td class="fieldValue"><input id="cf_3110_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3110_'.$i.'" value=""></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_3108_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3108_'.$i.'" value=""></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_2444_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2444_'.$i.'" value=""></td>';

$tbody2W .='<td class="fieldValue"><input id="cf_2448_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2448_'.$i.'" value=""></td>';

$tbody2W .='<td class="fieldValue"><input id="cf_2446_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2446_'.$i.'" value="" readonly=""></td>';

$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2450_'.$i.'" type="text" class="form-control" data-field-id="2451" name="cf_2450_'.$i.'" value="" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_2452_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2452_'.$i.'" value=""></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_2456_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2456_'.$i.'" value="" readonly=""></td>';
$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2462_'.$i.'" type="text" class="form-control" data-field-id="2463" name="cf_2462_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_2466_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2466_'.$i.'" value=""></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_2470_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2470_'.$i.'" value="" readonly=""></td>';
$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2472_'.$i.'" type="text" class="form-control" data-field-id="2473" name="cf_2472_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
$tbody2W .='<td class="fieldValue"><input id="cf_2476_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2476_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2478_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2478_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2482_'.$i.'" type="text" class="form-control" data-field-id="2483" name="cf_2482_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2486_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2486_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2488_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2488_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2492_'.$i.'" type="text" class="form-control" data-field-id="2493" name="cf_2492_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2494_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2494_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2498_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2498_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2502_'.$i.'" type="text" class="form-control" data-field-id="2503" name="cf_2502_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2506_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2506_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2510_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2510_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2514_'.$i.'" type="text" class="form-control" data-field-id="2515" name="cf_2514_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2518_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2518_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2522_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2522_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2524_'.$i.'" type="text" class="form-control" data-field-id="2525" name="cf_2524_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2526_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2526_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2530_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2530_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2534_'.$i.'" type="text" class="form-control" data-field-id="2535" name="cf_2534_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2538_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2538_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2542_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2542_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2546_'.$i.'" type="text" class="form-control" data-field-id="2547" name="cf_2546_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2550_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2550_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2554_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2554_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2558_'.$i.'" type="text" class="form-control" data-field-id="2559" name="cf_2558_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2562_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2562_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2566_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2566_'.$i.'" value="" readonly=""></td>';
		$tbody2W .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2570_'.$i.'" type="text" class="form-control" data-field-id="2571" name="cf_2570_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2574_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2574_'.$i.'" value=""></td>';
		$tbody2W .='<td class="fieldValue"><input id="cf_2578_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2578_'.$i.'" value="" readonly=""></td>';
$tbody2W .='</tr>';
	if($i!='1')
	{
		$rowcount2W = $rowcount2W.",".$i;
	}
	}
	$resultIB = mysql_query("SELECT arocrm_products.*, arocrm_crmentity.* FROM  arocrm_products INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productcategory='IB'");
	$countIB = mysql_num_rows($resultIB);
	$tbodyIB = "";
	$rowcountIB = 1;
	for($i=1;$i<=$countIB;$i++)
	{
		$tbodyIB .='<tr id="IB__row_'.$i.'" class="tr_clone">';


		$rowIB = mysql_fetch_array($resultIB);
		$productcodeIB = $rowIB['product_no'];
		$productnameIB = $rowIB['productname'];

    $productcodeIBid = $rowIB['productid'];

    $tbodyIB .='<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

    $tbodyIB .='
    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products">
    <div class="input-group">
    <input name="cf_2813_'.$i.'" type="hidden" value="'.$productcodeIBid.'" class="sourceField" data-displayvalue="">
    <input id="cf_2813_display_'.$i.'" style="min-width:280px;" name="cf_2813_display_'.$i.'" data-fieldname="cf_2813" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productnameIB.'"
    readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
    </div>
    </div>
    </td>';

$tbodyIB .='<td class="fieldValue"><input id="cf_2811_'.$i.'" type="text" data-fieldname="cf_2811" data-fieldtype="string" class="inputElement " name="cf_2811_'.$i.'" value="'.$productcodeIB.'"></td>';
$tbodyIB .='<td class="fieldValue"><input id="cf_3114_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3114_'.$i.'" value=""></td>';
$tbodyIB .='<td class="fieldValue"><input id="cf_3112_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3112_'.$i.'" value=""></td>';

			$tbodyIB .='<td class="fieldValue"><input id="cf_2468_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2468_'.$i.'" value=""></td>';

			$tbodyIB .='<td class="fieldValue"><input id="cf_2480_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2480_'.$i.'" value=""></td>';

			$tbodyIB .='<td class="fieldValue"><input id="cf_2474_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2474_'.$i.'" value="" readonly=""></td>';

			$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2484_'.$i.'" type="text" class="form-control" data-field-id="2485" name="cf_2484_'.$i.'" value="" step="any" aria-invalid="false"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2490_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2490_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2496_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2496_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2500_'.$i.'" type="text" class="form-control" data-field-id="2501" name="cf_2500_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2504_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2504_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2508_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2508_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2512_'.$i.'" type="text" class="form-control" data-field-id="2513" name="cf_2512_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2516_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2516_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2520_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2520_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2528_'.$i.'" type="text" class="form-control" data-field-id="2529" name="cf_2528_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2532_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2532_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2536_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2536_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2540_'.$i.'" type="text" class="form-control" data-field-id="2541" name="cf_2540_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2544_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2544_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2548_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2548_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2552_'.$i.'" type="text" class="form-control" data-field-id="2553" name="cf_2552_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2556_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2556_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2560_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2560_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2564_'.$i.'" type="text" class="form-control" data-field-id="2565" name="cf_2564_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2568_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2568_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2572_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2572_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2576_'.$i.'" type="text" class="form-control" data-field-id="2577" name="cf_2576_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2580_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2580_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2582_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2582_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2586_'.$i.'" type="text" class="form-control" data-field-id="2587" name="cf_2586_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2588_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2588_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2592_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2592_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2596_'.$i.'" type="text" class="form-control" data-field-id="2597" name="cf_2596_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2598_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2598_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2602_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2602_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2606_'.$i.'" type="text" class="form-control" data-field-id="2607" name="cf_2606_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2608_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2608_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2612_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2612_'.$i.'" value="" readonly=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2616_'.$i.'" type="text" class="form-control" data-field-id="2617" name="cf_2616_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2620_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2620_'.$i.'" value=""></td>';
																																		$tbodyIB .='<td class="fieldValue"><input id="cf_2622_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2622_'.$i.'" value="" readonly=""></td>';
	$tbodyIB .='</tr>';
	if($i!='1')
	{
		$rowcountIB = $rowcountIB.",".$i;
	}
	}
	$resultER = mysql_query("SELECT arocrm_products.*, arocrm_crmentity.* FROM  arocrm_products INNER JOIN arocrm_crmentity ON arocrm_crmentity.crmid = arocrm_products.productid
						 WHERE arocrm_crmentity.deleted=0 AND arocrm_products.productcategory='ER'");
	$countER = mysql_num_rows($resultER);
	$tbodyER = "";
	$rowcountER = 1;
	for($i=1;$i<=$countER;$i++)
	{
		$tbodyER .='<tr id="ER__row_'.$i.'" class="tr_clone">';

		$tbodyER .='<td><a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';


		$rowER = mysql_fetch_array($resultER);
		$productcodeER = $rowER['product_no'];
		$productnameER = $rowER['productname'];

    $productcodeERid = $rowER['productid'];

    $tbodyER .='
    <td class="fieldValue">
    <div class="referencefield-wrapper ">
    <input name="popupReferenceModule" type="hidden" value="Products">
    <div class="input-group">
    <input name="cf_2817_'.$i.'" type="hidden" value="'.$productcodeERid.'" class="sourceField" data-displayvalue="">
    <input id="cf_2817_display_'.$i.'" style="min-width:280px;" name="cf_2817_display_'.$i.'" data-fieldname="cf_2817" data-fieldtype="reference"
    type="text" class="marginLeftZero autoComplete inputElement ui-autocomplete-input" value="'.$productnameER.'"
    readonly placeholder="Type to search" autocomplete="off" aria-invalid="false">
    </div>
    </div>
    </td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2815_'.$i.'" type="text" data-fieldname="cf_2815" data-fieldtype="string" class="inputElement " name="cf_2815_'.$i.'" readonly value="'.$productcodeER.'"></td>';
  $tbodyER .='<td class="fieldValue"><input id="cf_3118_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3118_'.$i.'" value=""></td>';
  $tbodyER .='<td class="fieldValue"><input id="cf_3116_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_3116_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2594_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2594_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2604_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2604_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2600_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2600_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement">
  <input id="cf_2610_'.$i.'" type="text" class="form-control" data-field-id="2611" name="cf_2610_'.$i.'" value="" step="any" aria-invalid="false">
  <span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2614_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2614_'.$i.'" value=""></td>';

	$tbodyER .='<td class="fieldValue"><input id="cf_2618_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2618_'.$i.'" value="" readonly></td>';

	$tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2624_'.$i.'" type="text" class="form-control" data-field-id="2625" name="cf_2624_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2626_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2626_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2628_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2628_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2630_'.$i.'" type="text" class="form-control" data-field-id="2631" name="cf_2630_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2632_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2632_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2634_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2634_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2636_'.$i.'" type="text" class="form-control" data-field-id="2637" name="cf_2636_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2638_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2638_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2640_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2640_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2642_'.$i.'" type="text" class="form-control" data-field-id="2643" name="cf_2642_'.$i.'" value="" step="any">
  <span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2644_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2644_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2646_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2646_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2648_'.$i.'" type="text" class="form-control" data-field-id="2649" name="cf_2648_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2650_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2650_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2652_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2652_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2654_'.$i.'" type="text" class="form-control" data-field-id="2655" name="cf_2654_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2656_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2656_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2658_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2658_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2660_'.$i.'" type="text" class="form-control" data-field-id="2661" name="cf_2660_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2662_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2662_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2664_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2664_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2666_'.$i.'" type="text" class="form-control" data-field-id="2667" name="cf_2666_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2668_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2668_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2670_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2670_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2672_'.$i.'" type="text" class="form-control" data-field-id="2673" name="cf_2672_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2674_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2674_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2676_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2676_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2678_'.$i.'" type="text" class="form-control" data-field-id="2679" name="cf_2678_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2680_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2680_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2682_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2682_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='<td class="fieldValue"><div class="input-group inputElement"><input id="cf_2684_'.$i.'" type="text" class="form-control" data-field-id="2685" name="cf_2684_'.$i.'" value="" step="any"><span class="input-group-addon">%</span></div></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2686_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2686_'.$i.'" value=""></td>';

  $tbodyER .='<td class="fieldValue"><input id="cf_2688_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2688_'.$i.'" value="" readonly=""></td>';

  $tbodyER .='</tr>';
	if($i!='1')
	{
		$rowcountER = $rowcountER.",".$i;
	}
	}
	$response['tbody4W'] = $tbody4W;
	$response['tbody2W'] = $tbody2W;
	$response['tbodyIB'] = $tbodyIB;
	$response['tbodyER'] = $tbodyER;
	$response['rowcount4W'] = $rowcount4W;
	$response['rowcount2W'] = $rowcount2W;
	$response['rowcountIB'] = $rowcountIB;
	$response['rowcountER'] = $rowcountER;
	return $response;
}
function getAllDays($yr,$month)
{
	$response = array();
	$nmonth = date("m", strtotime($month));
	$d = cal_days_in_month(CAL_GREGORIAN, $nmonth, $yr);
	$response['days'] = $d;
	$response['month'] = $nmonth;
	return $response;
}
function getAllChannel($channel, $date, $row, $leadno, $leads, $year, $route, $routetype)
{
	$response = array();
	$ln = count($date);
	$rowcount = "";
	$actual = "";
	$tbodyBill = "";
	$rowcountBill = 1;
	for($i=1;$i<=$row;$i++)
	{
			$month = date('F', strtotime($date[$i-1]));
			$nmonth = date("m", strtotime($month));

			/*if($date[$i-1] == $year.'-'.$nmonth.'-0'.$i || $date[$i-1] == $year.'-'.$nmonth.'-'.$i)
			{*/

				$chnl = $channel[$i-1];
				if($chnl == "")
				{
					$lenth = 0;
				}
				else
				{
					$lenth = count($chnl);
				}
				$lead = $leads[$i-1];
				if($lead == "")
				{
					$len = 0;
				}
				else
				{
					$len = count($lead);
				}
				$lenth = $lenth + $leadno[$i-1] + $len;
				$cnt = 0;
				$k = 0;
				for($j=1;$j<=$lenth;$j++)
				{
					$n++;
					if($lenth == '1')
					{
						$rn = "";
						$rid = "_".$n;
						$count = 1;
					}
					else
					{
						$rn = "_".$n;
						$rid = "_".$n;
						$count = ltrim($n,"_");
					}
					$actual .= '<tr id="Actual_Working_Details__row'.$rid.'" class="tr_clone">';

					$actual .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$count.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

					$actual .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2086'.$rn.'" type="date" class="form-control " data-fieldname="cf_2086" name="cf_2086'.$rn.'" value="'.$date[$i-1].'" data-rule-date="true"></div></td>';

					if($chnl[$j-1] == "")
					{
						$actual .='<td class="fieldValue"><input id="cf_2102'.$rn.'" type="text" data-fieldname="cf_2102" data-fieldtype="string" class="inputElement " name="cf_2102'.$rn.'" value="'.$lead[$k].'"></td>';
						$k++;
					}
					else if($chnl[$j-1] == "" && $lead[$k] == "")
					{
						$actual .='<td class="fieldValue"><input id="cf_2102'.$rn.'" type="text" data-fieldname="cf_2102" data-fieldtype="string" class="inputElement " name="cf_2102'.$rn.'" value=""></td>';
					}
					else
					{
						$actual .='<td class="fieldValue"><input id="cf_2102'.$rn.'" type="text" data-fieldname="cf_2102" data-fieldtype="string" class="inputElement " name="cf_2102'.$rn.'" value="'.$chnl[$j-1].'"></td>';
					}

					$actual .='<td class="fieldValue"><select id="cf_2104'.$rn.'" multiple="" class="multipicklist" name="cf_2104'.$rn.'[]" data-fieldtype="multipicklist" style="width:210px;height:30px;" tabindex="-1"><option value="Channel Reaction">Channel Reaction</option><option value="Channel Convinced">Channel Convinced</option><option value="No of W Card - 4W">No of W Card - 4W</option><option value="No of W Card - 2W">No of W Card - 2W</option><option value="No of W Card - IB">No of W Card - IB</option><option value="No of W Card - ER">No of W Card - ER</option><option value="Compt of Stock Details - 4W">Compt of Stock Details - 4W</option><option value="Compt of Stock Details - 2W">Compt of Stock Details - 2W</option><option value="Compt of Stock Details - IB">Compt of Stock Details - IB</option><option value="Compt of Stock Details - ER">Compt of Stock Details - ER</option><option value="Centurion Stock Details - 4W">Centurion Stock Details - 4W</option><option value="Centurion Stock Details - 2W">Centurion Stock Details - 2W</option><option value="Centurion Stock Details - IB">Centurion Stock Details - IB</option><option value="Centurion Stock Details - ER">Centurion Stock Details - ER</option><option value="No of SO - 4W">No of SO - 4W</option><option value="No of SO - 2W">No of SO - 2W</option><option value="No of SO - IB">No of SO - IB</option><option value="No of SO - ER">No of SO - ER</option><option value="Realisation of OS or Adv" class="picklistColor_cf_2104_Realisation_of_OS_or_Adv">Realisation of OS or Adv</option><option value="Mode of Payment">Mode of Payment</option><option value="Cheque Number">Cheque Number</option><option value="Cheque Date">Cheque Date</option><option value="Outcome of BTL Activity">Outcome of BTL Activity</option><option value="Other Details">Other Details</option></select>
					<script>
			$(document).ready(function(){
			$("#cf_2104'.$rn.'").select2();
			});
			</script>
					</td>';

					$actual .= '<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3657'.$rn.'" class="inputElement " name="cf_3657'.$rn.'"></textarea></td>';

			$actual .='<td class="fieldValue"><select data-fieldname="cf_2106" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2106'.$rn.'" data-selected-value="" tabindex="-1" title="" id="cf_2106'.$rn.'"><option value="">Select an Option</option><option value="Happy">Happy</option><option value="Unhappy">Unhappy</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2106'.$rn.'").select2();
			});
			</script>
			</td>';

			$actual .='<td class="fieldValue"><select data-fieldname="cf_2108" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2108'.$rn.'" data-selected-value=" " tabindex="-1" title="" id="cf_2108'.$rn.'"><option value="">Select an Option</option><option value="Yes">Yes</option><option value="No">No</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2108'.$rn.'").select2();
			});
			</script>
			</td>';

			$actual .='<td class="fieldValue"><input id="cf_2110'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2110'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2112'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2112'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2114'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2114'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2116'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2116'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2118'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2118'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2120'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2120'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2122'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2122'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2126'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2126'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2128'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2128'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2130'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2130'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2132'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2132'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2134'.$rn.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2134'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2136'.$rn.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2136'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2138'.$rn.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2138'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2140'.$rn.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2140'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2142'.$rn.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2142'.$rn.'" value=""></td>';
			
			$actual .='<td class="fieldValue"><input id="cf_2144'.$rn.'" type="text" data-fieldname="cf_2144" data-fieldtype="string" class="inputElement " name="cf_2144'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><select data-fieldname="cf_2146" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2146'.$rn.'" data-selected-value=" " tabindex="-1" title="" id="cf_2146'.$rn.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2146'.$rn.'").select2();
			});
			</script>
			</td>';

			$actual .='<td class="fieldValue"><input id="cf_2148'.$rn.'" type="text" data-fieldname="cf_2148" data-fieldtype="string" class="inputElement " name="cf_2148'.$rn.'" value=""></td>';

			$actual .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2150'.$rn.'" type="date" class="form-control " data-fieldname="cf_2150" name="cf_2150'.$rn.'" value="" data-rule-date="true"></div></td>';

			$actual .='<td class="fieldValue"><input id="cf_2152'.$rn.'" type="text" data-fieldname="cf_2152" data-fieldtype="string" class="inputElement " name="cf_2152'.$rn.'" value=""></td>';
			
			$actual .='<td class="fieldValue"><textarea rows="5" id="cf_2154'.$rn.'" class="inputElement " name="cf_2154'.$rn.
			'"></textarea></td>';

			$actual .='</tr>';
			$length = $lenth * $ln;
					if($j!=$lenth)
					{
						$rowcount = $rowcount.",".$count;
					}
			}
		/*	}
			else
			{
				$n++;
				$actual .= '<tr id="Actual__row_'.$n.'" class="tr_clone">';
				$actual .='<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$n.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';

				if($i<10)
					{
							$actual .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2086_'.$n.'" type="date" class="form-control " data-fieldname="cf_2086" name="cf_2086_'.$n.'" value="'.$year.'-'.$nmonth.'-0'.$i.'" data-rule-date="true"></div></td>';
					}
					else
					{
						$actual .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2086_'.$n.'" type="date" class="form-control " data-fieldname="cf_2086" name="cf_2086_'.$n.'" value="'.$year.'-'.$nmonth.'-'.$i.'" data-rule-date="true"></div></td>';
					}
					$actual .='<td class="fieldValue"><input id="cf_2102_'.$n.'" type="text" data-fieldname="cf_2102" data-fieldtype="string" class="inputElement " name="cf_2102_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><select id="cf_2104_'.$n.'" multiple="" class="select2 multipicklist optionselect2 select2-offscreen" name="cf_2104_'.$n.'[]" data-fieldtype="multipicklist" style="width:210px;height:30px;" tabindex="-1"><option value="Channel Reaction">Channel Reaction</option><option value="Channel Convinced">Channel Convinced</option><option value="No of W Card - 4W">No of W Card - 4W</option><option value="No of W Card - 2W">No of W Card - 2W</option><option value="No of W Card - IB">No of W Card - IB</option><option value="No of W Card - ER">No of W Card - ER</option><option value="Compt of Stock Details - 4W">Compt of Stock Details - 4W</option><option value="Compt of Stock Details - 2W">Compt of Stock Details - 2W</option><option value="Compt of Stock Details - IB">Compt of Stock Details - IB</option><option value="Compt of Stock Details - ER">Compt of Stock Details - ER</option><option value="Centurion Stock Details - 4W">Centurion Stock Details - 4W</option><option value="Centurion Stock Details - 2W">Centurion Stock Details - 2W</option><option value="Centurion Stock Details - IB">Centurion Stock Details - IB</option><option value="Centurion Stock Details - ER">Centurion Stock Details - ER</option><option value="No of SO - 4W">No of SO - 4W</option><option value="No of SO - 2W">No of SO - 2W</option><option value="No of SO - IB">No of SO - IB</option><option value="No of SO - ER">No of SO - ER</option><option value="Realisation of OS or Adv" class="picklistColor_cf_2104_Realisation_of_OS_or_Adv">Realisation of OS or Adv</option><option value="Mode of Payment">Mode of Payment</option><option value="Cheque Number">Cheque Number</option><option value="Cheque Date">Cheque Date</option><option value="Outcome of BTL Activity">Outcome of BTL Activity</option><option value="Other Details">Other Details</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2104_'.$n.'").select2();
			});
			</script>
			</td>';
			$actual .= '<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3657'.$rn.'" class="inputElement " name="cf_3657'.$rn.'"></textarea></td>';

			$actual .='<td class="fieldValue"><select data-fieldname="cf_2106" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2106_'.$n.'" data-selected-value="" tabindex="-1" title="" id="cf_2106_'.$n.'"><option value="">Select an Option</option><option value="Happy">Happy</option><option value="Unhappy">Unhappy</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2106_'.$n.'").select2();
			});
			</script>
			</td>';

			$actual .='<td class="fieldValue"><select data-fieldname="cf_2108" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2108_'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_2108_'.$n.'"><option value="">Select an Option</option><option value="Yes">Yes</option><option value="No">No</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2108_'.$n.'").select2();
			});
			</script>
			</td>';

			$actual .='<td class="fieldValue"><input id="cf_2110_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2110_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2112_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2112_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2114_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2114_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2116_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2116_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2118_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2118_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2120_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2120_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2122_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2122_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2126_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2126_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2128_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2128_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2130_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2130_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2132_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2132_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2134_'.$n.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2134_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2136_'.$n.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2136_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2138_'.$n.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2138_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2140_'.$n.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2140_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><input id="cf_2142_'.$n.'" style="min-width:80px;" type="number" class="inputElement" name="cf_2142_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><textarea rows="5" id="cf_2144_'.$n.'" class="inputElement " name="cf_2144_'.$n.'"></textarea></td>';

			$actual .='<td class="fieldValue"><select data-fieldname="cf_2146" data-fieldtype="picklist" class="inputElement" type="picklist" name="cf_2146_'.$n.'" data-selected-value=" " tabindex="-1" title="" id="cf_2146_'.$n.'"><option value="">Select an Option</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option></select>
			<script>
			$(document).ready(function(){
			$("#cf_2146_'.$n.'").select2();
			});
			</script>
			</td>';

			$actual .='<td class="fieldValue"><input id="cf_2148_'.$n.'" type="text" data-fieldname="cf_2148" data-fieldtype="string" class="inputElement " name="cf_2148_'.$n.'" value=""></td>';

			$actual .='<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_2150_'.$n.'" type="date" class="form-control " data-fieldname="cf_2150" name="cf_2150_'.$n.'" value="" data-rule-date="true"></div></td>';

			$actual .='<td class="fieldValue"><textarea rows="5" id="cf_2152_'.$n.'" class="inputElement " name="cf_2152_'.$n.'"></textarea></td>';

			$actual .='<td class="fieldValue"><textarea rows="5" id="cf_2154_'.$n.'" class="inputElement " name="cf_2154_'.$n.
			'"></textarea></td>';

			$actual .='</tr>';
			}
			*/
			
			$rowcount = $rowcount.",".$n;


			$chnl = $channel[$i-1];
			$chnlCount = count($chnl);
			if($chnlCount > '0')
			{
				$channelName = implode(",",$chnl);
			}
			else
			{
				$channelName = $chnl;
			}
			$lead = $leads[$i-1];
			$leadCount = count($lead);
			if($leadCount > '0')
			{
				$leadName = implode(",",$lead);
			}
			else
			{
				$leadName = $lead;
			}

			$tbodyBill .= '<tr id="Actual_Bill_Details__row_'.$i.'" class="tr_clone">';
			if($row == '1')
			{
				$tbodyBill .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'" style="display: none;"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			else
			{
				$tbodyBill .= '<td><i class="fa fa-trash deleteLineItemRow cursorPointer" title="Delete" id="'.$i.'"></i>&nbsp;<a><img src="layouts/vlayout/skins/images/drag.png" border="0" title="Drag"></a></td>';
			}
			$routeResult = mysql_query("SELECT arocrm_routemaster.*, arocrm_crmentity.* FROM arocrm_routemaster
			INNER JOIN arocrm_crmentity WHERE arocrm_crmentity.deleted = '0' AND arocrm_routemaster.routemasterid = '".$route[$i-1]."'");
			$routeRow = mysql_fetch_array($routeResult);
			$routename = $routeRow['name'];

			$tbodyBill .= '<td class="fieldValue"><div class="input-group inputElement" style="margin-bottom: 3px"><input id="cf_3597_'.$i.'" type="date" class="form-control " data-fieldname="cf_3597" name="cf_3597_'.$i.'" readonly value="'.$date[$i-1].'" data-rule-date="true"></div></td>
			<td class="fieldValue"><input id="cf_3599_'.$i.'" type="text" data-fieldname="cf_3599" data-fieldtype="string" class="inputElement " name="cf_3599_'.$i.'" readonly value="'.$routename.'"></td>
			<td class="fieldValue"><input id="cf_3601_'.$i.'" type="text" data-fieldname="cf_3601" data-fieldtype="string" class="inputElement " name="cf_3601_'.$i.'" readonly value="'.$routetype[$i-1].'"></td>
			<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3603_'.$i.'" class="inputElement " readonly name="cf_3603_'.$i.'">'.$channelName.'</textarea></td>
			<td class="fieldValue"><textarea rows="6" cols="8" id="cf_3605_'.$i.'" class="inputElement " readonly name="cf_3605_'.$i.'">'.$leadName.'</textarea> </td>
			<td class="fieldValue"><input id="cf_3607_'.$i.'" style="min-width:80px;" type="number" class="inputElement" name="cf_3607_'.$i.'" readonly value="'.$leadno[$i-1].'"></td>
			<td class="fieldValue"><input id="cf_2046_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2046_'.$i.'" readonly value=""></td>
			<td class="fieldValue"><input id="cf_2048_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2048_'.$i.'" readonly value=""></td>
			<td class="fieldValue"><input id="cf_2050_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2050_'.$i.'" readonly value=""></td>
			<td class="fieldValue"><input id="cf_2052_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2052_'.$i.'" readonly value=""></td>
			<td class="fieldValue"><input id="cf_2054_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2054_'.$i.'" readonly value=""></td>
			<td class="fieldValue"><input id="cf_2058_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2058_'.$i.'" readonly value=""></td>
			<td class="fieldValue"><input id="cf_2064_'.$i.'" style="min-width:80px;" type="number" class="inputElement" step="0.01" name="cf_2064_'.$i.'" readonly value=""></td>
		</tr>';
		if($i != '1')
		{
			$rowcountBill = $rowcountBill.",".$i;
		}
	}
	$rowcount = ltrim($rowcount,",");
	$response['actual'] = $actual;
	$response['rowcount'] = $rowcount;
	$response['tbodyBill'] = $tbodyBill;
	$response['rowcountBill'] = $rowcountBill;
	return $response;
}
mysql_close($dbhandle);
?>


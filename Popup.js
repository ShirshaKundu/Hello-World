/*+***********************************************************************************
 * The contents of this file are subject to the arocrm CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: arocrm CRM Open Source
 * The Initial Developer of the Original Code is arocrm.
 * Portions created by arocrm are Copyright (C) arocrm.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("arocrm_Popup_Js",{

    getInstance: function(module){
		if(!module || typeof module == 'undefined') {
			var module = app.getModuleName();
		}
		var className = jQuery('#popUpClassName').val();
		if(typeof className != 'undefined'){
			var moduleClassName = className;
		}else{
			var moduleClassName = module+"_Popup_Js";
		}
		var fallbackClassName = arocrm_Popup_Js;
	    if(typeof window[moduleClassName] != 'undefined'){
			var instance = new window[moduleClassName]();
		}else{
			var instance = new fallbackClassName();
		}
	    return instance;
	}

},{

    //holds the event name that child window need to trigger
	eventName : '',
	popupPageContentsContainer : false,
	sourceModule : false,
	sourceRecord : false,
	sourceField : false,
	multiSelect : false,
	relatedParentModule : false,
	relatedParentRecord : false,

    getView : function(){
	    var view = jQuery('#view',this.getPopupPageContainer()).val();
	    if(view == '') {
		    view = 'PopupAjax';
	    } else {
		    view = view + 'Ajax';
	    }
	    return view;
	},

    isMultiSelectMode : function() {
		if(this.multiSelect == false){
			this.multiSelect = jQuery('#multi_select',this.getPopupPageContainer());
		}
		var value = this.multiSelect.val();
		if(value) {
			return value;
		}
		return false;
	},

	/**
	 * Function to get source module
	 */
	getSourceModule : function(){
		if(this.sourceModule == false){
			this.sourceModule = jQuery('#parentModule',this.getPopupPageContainer()).val();
		}
		return this.sourceModule;
	},

	/**
	 * Function to get source record
	 */
	getSourceRecord : function(){
		if(this.sourceRecord == false){
			this.sourceRecord = jQuery('#sourceRecord',this.getPopupPageContainer()).val();
		}
		return this.sourceRecord;
	},

	/**
	 * Function to get source field
	 */
	getSourceField : function(){
		if(this.sourceField == false){
			this.sourceField = jQuery('#sourceField',this.getPopupPageContainer()).val();
		}
		return this.sourceField;
	},

	/**
	 * Function to get related parent module
	 */
	getRelatedParentModule : function(){
		if(this.relatedParentModule == false){
			this.relatedParentModule = jQuery('#relatedParentModule',this.getPopupPageContainer()).val();
		}
		return this.relatedParentModule;
	},
	/**
	 * Function to get related parent id
	 */
	getRelatedParentRecord : function(){
		if(this.relatedParentRecord == false){
			this.relatedParentRecord = jQuery('#relatedParentId',this.getPopupPageContainer()).val();
		}
		return this.relatedParentRecord;
	},

	/**
	 * Function to get Search key
	 */

	getSearchKey : function(){
		return jQuery('#searchableColumnsList',this.getPopupPageContainer()).val();
	},

	/**
	 * Function to get Search value
	 */
	getSearchValue : function(){
		return jQuery('#searchvalue',this.getPopupPageContainer()).val();
	},

	/**
	 * Function to get Order by
	 */
	getOrderBy : function(){
		return jQuery('#orderBy',this.getPopupPageContainer()).val();
	},

	/**
	 * Function to get Sort Order
	 */
	getSortOrder : function(){
			return jQuery("#sortOrder",this.getPopupPageContainer()).val();
	},

	/**
	 * Function to get Page Number
	 */
	getPageNumber : function(){
		return jQuery('#pageNumber',this.getPopupPageContainer()).val();
	},

    getRelationId : function (){
        return jQuery('#relationId',this.getPopupPageContainer()).val();
    },



    getPopupPageContainer : function(){
		if(this.popupPageContentsContainer == false) {
			this.popupPageContentsContainer = jQuery('#popupPageContainer');
		}
		return this.popupPageContentsContainer;

	},

    getPopupContents : function(){
        return jQuery("#popupContents");
    },

    setEventName : function(eventName) {
		this.eventName = eventName;
	},

	getEventName : function() {
		return this.eventName;
	},

    getModuleName : function() {
        return this.getPopupPageContainer().find('#module').val();
    },

    /**
	 * Function to get complete params
	 */
	 
	 
	getCompleteParams : function(){
		var params = {};
		params['view'] = this.getView();
		params['src_module'] = this.getSourceModule();
		params['src_record'] = this.getSourceRecord();
		params['src_field'] = this.getSourceField();
		params['search_key'] =  this.getSearchKey();
		params['search_value'] =  this.getSearchValue();
		params['orderby'] =  this.getOrderBy();
		params['sortorder'] =  this.getSortOrder();
		params['page'] = this.getPageNumber();
		params['related_parent_module'] = this.getRelatedParentModule();
		params['related_parent_id'] = this.getRelatedParentRecord();
		params['module'] = this.getModuleName();
        params.search_params = JSON.stringify(this.getPopupListSearchParams());
		if(this.isMultiSelectMode()) {
			params['multi_select'] = true;
		}
        params['relationId'] = this.getRelationId();

		// Carry forward meta (LineItem Pricebook Popup > Search)
		var getUrl = this.getPopupPageContainer().find('#getUrl');
		if (getUrl.length) params['get_url'] = getUrl.val();

		return params;
	},


    getPopupListSearchParams : function(){
            var listViewPageDiv = jQuery('div.popupEntriesDiv');
            var listViewTable = listViewPageDiv.find('.listViewEntriesTable');
            var searchParams = new Array();
            var currentSearchParams = new Array();
            if(jQuery('#currentSearchParams').val())
            currentSearchParams = JSON.parse(jQuery('#currentSearchParams').val());
            listViewTable.find('.listSearchContributor').each(function(index,domElement){
            var searchInfo = new Array();
            var searchContributorElement = jQuery(domElement);
            var fieldName = searchContributorElement.attr('name');
            var fieldInfo = searchContributorElement.data('fieldinfo');
            if(fieldName in currentSearchParams) {
            delete currentSearchParams[fieldName];
            }

              var searchValue = searchContributorElement.val();
			      	if(typeof searchValue == "object") {
                    if(searchValue == null) {
                    searchValue = "";
                    }else{
                        searchValue = searchValue.join(',');
                    }
                }
                searchValue = searchValue.trim();
                if(searchValue.length <=0 ) {
                //continue
                    return true;
                }
                var searchOperator = 'c';
                if(fieldInfo.type == "date" || fieldInfo.type == "datetime") {
                    searchOperator = 'bw';
                }else if (fieldInfo.type == 'percentage' || fieldInfo.type == "double" || fieldInfo.type == "integer"
                || fieldInfo.type == 'currency' || fieldInfo.type == "number" || fieldInfo.type == "boolean" ||
                fieldInfo.type == "picklist") {
                searchOperator = 'e';
            }
            searchInfo.push(fieldName);
            searchInfo.push(searchOperator);
            searchInfo.push(searchValue);
            searchParams.push(searchInfo);
        });
		var referenceModule = jQuery('#popupPageContainer').find('#module').val();
		var sourcemodule = this.getSourceModule();
		if(referenceModule == "PurchaseOrder" && sourcemodule == "GoodsReceipt")
		{
			var searchInfonew = new Array();
			searchInfonew.push('postatus');
			searchInfonew.push('e');
			searchInfonew.push('Approved');
			searchParams.push(searchInfonew);
			var reference = $('[name="cf_3453"]').val();
			if(reference == 'With Respect to Service Order')
			{
				var searchInfonew1 = new Array();
				searchInfonew1.push('cf_2709');
				searchInfonew1.push('e');
				searchInfonew1.push('Service Order');
				searchParams.push(searchInfonew1);
			}
		}
		if(referenceModule == "SalesOrder" && sourcemodule == "OutboundDelivery")
		{
				var searchInfonew = new Array();
				searchInfonew.push('sostatus');
				searchInfonew.push('e');
				searchInfonew.push('Approved');
				searchParams.push(searchInfonew);
				var searchInfonew2 = new Array();
				searchInfonew2.push('cf_5199');
				searchInfonew2.push('e');
				searchInfonew2.push('Not Done');
				searchParams.push(searchInfonew2);
				var assigned_to = $('[name="assigned_user_id"]').val();
				if(assigned_to != '1')
				{
					var plant = $('input[name="cf_nrl_plantmaster625_id_display"]').val();
					var searchInfonew1 = new Array();
					searchInfonew1.push('cf_nrl_plantmaster580_id');
					searchInfonew1.push('e');
					searchInfonew1.push(plant);
					searchParams.push(searchInfonew1);
				}
			
		}
		if(referenceModule == "PurchaseOrder" && sourcemodule == "OutboundDelivery")
		{
			var searchInfonew = new Array();
			searchInfonew.push('postatus');
			searchInfonew.push('e');
			searchInfonew.push('Approved');
			searchParams.push(searchInfonew);
		}
		if(referenceModule == "SalesOrder" && sourcemodule == "Invoice")
		{
			var searchInfonew = new Array();
			searchInfonew.push('sostatus');
			searchInfonew.push('e');
			searchInfonew.push('Approved');
			searchParams.push(searchInfonew);
			var searchInfonew2 = new Array();
			searchInfonew2.push('cf_5201');
			searchInfonew2.push('e');
			searchInfonew2.push('Invoice Not Done');
			searchParams.push(searchInfonew2);
			var assigned_to = $('[name="assigned_user_id"]').val();
			if(assigned_to != '1')
			{
				var branch = $('input[name="cf_nrl_plantmaster164_id_display"]').val();
				var searchInfonew1 = new Array();
				searchInfonew1.push('cf_nrl_plantmaster580_id');
				searchInfonew1.push('e');
				searchInfonew1.push(branch);
				searchParams.push(searchInfonew1); 
			}
		}
		if(referenceModule == "Invoice" && sourcemodule == "SalesReturn")
		{
			var searchInfonew = new Array();
			searchInfonew.push('invoicestatus');
			searchInfonew.push('e');
			searchInfonew.push('Approved');
			searchParams.push(searchInfonew);
			var searchInfonew1 = new Array();
			searchInfonew1.push('cf_3288');
			searchInfonew1.push('e');
			searchInfonew1.push('Sales Invoice,Direct Sales');
			searchParams.push(searchInfonew1);
		}
		if(referenceModule == "PurchaseOrder" && sourcemodule == "InboundDelivery")
		{
			var searchInfonew = new Array();
			searchInfonew.push('cf_2709');
			searchInfonew.push('e');
			searchInfonew.push('Stock Item,Non Stock Item,Service Order');
			searchParams.push(searchInfonew);
			var ibdreference = $('select[name="cf_3193"]').val();
			if(ibdreference == "With respect to PO")
			{
				var searchInfonew1 = new Array();
				searchInfonew1.push('cf_2712');
				searchInfonew1.push('e');
				searchInfonew1.push('Without Reference,Reference to PR');
				searchParams.push(searchInfonew1);
				var searchInfonew2 = new Array();
				searchInfonew2.push('postatus');
				searchInfonew2.push('e');
				searchInfonew2.push('Approved');
				searchParams.push(searchInfonew2);
			}
			if(ibdreference == "With respect to STPO")
			{
				var searchInfonew1 = new Array();
				searchInfonew1.push('cf_2712');
				searchInfonew1.push('e');
				searchInfonew1.push('Reference to STR');
				searchParams.push(searchInfonew1);
				var searchInfonew2 = new Array();
				searchInfonew2.push('postatus');
				searchInfonew2.push('e');
				searchInfonew2.push('Approved');
				searchParams.push(searchInfonew2);
			}
		}
		if(referenceModule == "Invoice" && sourcemodule == "PurchaseReturnOrder"){
			
				var vendor = $('[name="cf_nrl_vendors780_id_display"]').val();
			    var searchInfonew1 = new Array();
				searchInfonew1.push('cf_nrl_vendors752_id');
				searchInfonew1.push('e');
				searchInfonew1.push(vendor);
				searchParams.push(searchInfonew1); 
				var searchInfonew2 = new Array();
				searchInfonew2.push('invoicestatus');
				searchInfonew2.push('e');
				searchInfonew2.push('Approved,Credit Invoice,Sent,AutoCreated,Cancelled,Pending For Approval,Created');
				searchParams.push(searchInfonew2);
				var searchInfonew3 = new Array();
				searchInfonew3.push('cf_3288');
				searchInfonew3.push('e');
				searchInfonew3.push('Purchase Invoice');
				searchParams.push(searchInfonew3);
			
		}
		if(referenceModule == "SalesOrder" && sourcemodule == "PurchaseOrder")
		{
			var searchInfonew = new Array();
			searchInfonew.push('sostatus');
			searchInfonew.push('e');
			searchInfonew.push('Approved');
			searchParams.push(searchInfonew);
		}
		if(referenceModule == "Products" && sourcemodule == "SalesOrder")
		{
			var category = $('select[name="productcategory"]').val();
			var searchInfonew = new Array();
			searchInfonew.push('productcategory');
			searchInfonew.push('e');
			searchInfonew.push(category);
			searchParams.push(searchInfonew);
		}
		if(referenceModule == "Products" && sourcemodule == "Invoice")
		{
			var category = $('select[name="productcategory"]').val();
			var searchInfonew = new Array();
			searchInfonew.push('productcategory');
			searchInfonew.push('e');
			searchInfonew.push(category);
			searchParams.push(searchInfonew);
		}
		/*if(referenceModule == "Accounts" && sourcemodule == "HelpDesk")
		{
			var custtype = $('[name="accounttype"]').select2('data').text;
			var searchInfonew = new Array();
			searchInfonew.push('accounttype');
			searchInfonew.push('e');
			searchInfonew.push(custtype);
			searchParams.push(searchInfonew);
		}
		if(referenceModule == "ServiceContracts" && sourcemodule == "HelpDesk")
		{
			var searchInfonew = new Array();
			searchInfonew.push('contract_status');
			searchInfonew.push('e');
			searchInfonew.push('Active');
			searchParams.push(searchInfonew);
			var searchInfonew1 = new Array();
			searchInfonew1.push('cf_2969');
			searchInfonew1.push('c');
			searchInfonew1.push(localStorage.getItem("temporg"));
			searchParams.push(searchInfonew1);
			localStorage.removeItem("temporg");
		}*/

		for(var i in currentSearchParams) {
            var fieldName = currentSearchParams[i]['fieldName'];
            var searchValue = currentSearchParams[i]['searchValue'];
            var searchOperator = currentSearchParams[i]['comparator'];
            if(fieldName== null || fieldName.length <=0 ){
                continue;
            }
            var searchInfo = new Array();
            searchInfo.push(fieldName);
            searchInfo.push(searchOperator);
            searchInfo.push(searchValue);
            searchParams.push(searchInfo);
        }


        return new Array(searchParams);
    },

    /**
	 * Function to get Page Records
	 */
	getPageRecords : function(params){
		var aDeferred = jQuery.Deferred();
                app.helper.showProgress();
		arocrm_BaseList_Js.getPageRecords(params).then(
            function(data){

                jQuery('#popupContents').html(data);
                vtUtils.applyFieldElementsView(jQuery('#popupContents'));
                aDeferred.resolve(data);
            }
        );
		return aDeferred.promise();
	},

    /**
	 * Function to handle next page navigation
	 */
	nextPageHandler : function(){
		var aDeferred = jQuery.Deferred();
        var popupContainer = this.getPopupPageContainer();
		var pageLimit = jQuery('#pageLimit',popupContainer).val();
		var noOfEntries = jQuery('#noOfEntries',popupContainer).val();
		if(noOfEntries == pageLimit){
			var pageNumber = jQuery('#pageNumber',popupContainer).val();
			var nextPageNumber = parseInt(pageNumber) + 1;
			var pagingParams = {
					"page": nextPageNumber
				}
			var completeParams = this.getCompleteParams();
			jQuery.extend(completeParams,pagingParams);
			this.getPageRecords(completeParams).then(
				function(data){
					jQuery('#pageNumber',popupContainer).val(nextPageNumber);
					aDeferred.resolve(data);
				}
			);
		}
		return aDeferred.promise();
	},

    /**
	 * Function to handle Previous page navigation
	**/
	previousPageHandler : function(){
		var aDeferred = jQuery.Deferred();
        var popupContainer = this.getPopupPageContainer();
		var pageNumber = jQuery('#pageNumber',popupContainer).val();
		var previousPageNumber = parseInt(pageNumber) - 1;
		if(pageNumber > 1){
			var pagingParams = {
				"page": previousPageNumber
			}
			var completeParams = this.getCompleteParams();
			jQuery.extend(completeParams,pagingParams);
			this.getPageRecords(completeParams).then(
				function(data){
					jQuery('#pageNumber',popupContainer).val(previousPageNumber);
					aDeferred.resolve(data);
				}
			);
		}
		return aDeferred.promise();
	},

    /**
	 * Function to handle search event
	 */
	searchHandler : function(){
		var aDeferred = jQuery.Deferred();
		var completeParams = this.getCompleteParams();
		completeParams['page'] = 1;
		this.getPageRecords(completeParams).then(
			function(data){
				aDeferred.resolve(data);
		});
		return aDeferred.promise();
	},

  getUrlVars : function()
    {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
    },


    /**
	 * Function to update Pagining status
	 */
	updatePagination : function(){
        var popupContainer = this.getPopupPageContainer();
        app.helper.hideProgress();
		var previousPageExist = jQuery('#previousPageExist',popupContainer).val();
		var nextPageExist = jQuery('#nextPageExist',popupContainer).val();
		var previousPageButton = jQuery('#PreviousPageButton',popupContainer);
		var nextPageButton = jQuery('#NextPageButton',popupContainer);
		var listViewEntriesCount = jQuery('#noOfEntries',popupContainer).val();
		var pageStartRange = jQuery('#pageStartRange',popupContainer).val();
		var pageEndRange = jQuery('#pageEndRange',popupContainer).val();
		var totalNumberOfRecords = jQuery('.totalNumberOfRecords',popupContainer);
		var pageNumbersTextElem = jQuery('.pageNumbersText',popupContainer);

        if(previousPageExist !== ""){
			previousPageButton.removeClass('disabled');
		} else if(previousPageExist === "") {
			previousPageButton.addClass('disabled');
		}

		if((nextPageExist !== "")){
			nextPageButton.removeClass('disabled');
		} else if((nextPageExist === "")) {
			nextPageButton.addClass('disabled');
		}

		if(listViewEntriesCount !== 0){
			var pageNumberText = pageStartRange+" "+app.vtranslate('to')+" "+pageEndRange;
			pageNumbersTextElem.html(pageNumberText);
			totalNumberOfRecords.removeClass('hide');
		} else {
			pageNumbersTextElem.html("<span>&nbsp;</span>");
			if(!totalNumberOfRecords.hasClass('hide')){
				totalNumberOfRecords.addClass('hide');
			}
		}

        this.registerPostPopupLoadEvents();
	},

	done : function(result, eventToTrigger){

    var recordid = this.getUrlVars()["record"];
    var sourceModule = this.getUrlVars()["module"];
	var view = this.getUrlVars()["view"];
    var plantid = this.getUrlVars()["plantid"];
    var referenceid = this.getUrlVars()["referenceid"];
	var referenceModule = jQuery('#popupPageContainer').find('#module').val();
	var sourcemodule = this.getSourceModule();
	var moduleapp = this.getUrlVars()["app"];

	     var event = "post.popupSelection.click";
		 var tagid = localStorage.getItem('tagmoduleid');
         var tagdisplay = localStorage.getItem('tagmoduledisplay');


        var event = "post.popupSelection.click";
        if(typeof eventToTrigger !== 'undefined'){
            event = eventToTrigger;
        }
        if(typeof event == 'function') {
            event(JSON.stringify(result));
        } else {
            app.event.trigger(event, JSON.stringify(result));
			if(moduleapp!='INVENTORY')
			{
				if(tagid!=undefined && tagdisplay!=undefined){
				var tagmid = Object.keys(result)[0];
				$('#'+tagid).val(tagmid);
				$('#'+tagdisplay).val(result[tagmid].name);
				}
			}


			if(referenceModule=='Products' && sourcemodule=='SalesPlan'){
      var id = Object.keys(result)[0];
        $.ajax(
        {
        type:"post",
        url: "arocrmAjax.php",
        data: {id: id, action: 'getProductCodeUnit'},
        dataType: 'json',
        success:function(response)
        {
          var tagid = localStorage.getItem('tagmoduleid');
          var seq = '';
          var tid = tagid.split('_');
          if(tid.length==2){
            seq = '';
          }else if(tid.length==3){
            seq = '_'+tid[tid.length - 1];
          }



          if(tagid.indexOf('cf_3512') > -1)
          {
            $("[name='cf_3514"+seq+"']").val(response.productcode);
            $("[name='cf_3528"+seq+"']").val(response.unit);
            $("[name='cf_3514"+seq+"']").prop("readonly",true);
            $("[name='cf_3528"+seq+"']").prop("readonly",true);
          }else if(tagid.indexOf('cf_3530') > -1)
          {
            $("[name='cf_3532"+seq+"']").val(response.productcode);
            $("[name='cf_3534"+seq+"']").val(response.unit);
            $("[name='cf_3532"+seq+"']").prop("readonly",true);
            $("[name='cf_3534"+seq+"']").prop("readonly",true);
          }else if(tagid.indexOf('cf_3568') > -1)
          {
            $("[name='cf_3570"+seq+"']").val(response.productcode);
            $("[name='cf_3572"+seq+"']").val(response.unit);
            $("[name='cf_3570"+seq+"']").prop("readonly",true);
            $("[name='cf_3572"+seq+"']").prop("readonly",true);
          }else{
            $("[name='cf_3550"+seq+"']").val(response.productcode);
            $("[name='cf_3552"+seq+"']").val(response.unit);
            $("[name='cf_3550"+seq+"']").prop("readonly",true);
            $("[name='cf_3552"+seq+"']").prop("readonly",true);
          }

        }
      });
    }
	if(referenceModule=='PriceBooks' && sourceModule=='Invoice'){
		var tablelen = $('table#lineItemTab > tbody > tr').length - 2;
			var accountid = $('input[name="account_id"]').val();
				var itemno = tablelen;
				var totalqty = 0;
				for(i=1;i<=itemno;i++)
				{
					var qty = $('#qty'+i).val();
					totalqty = parseInt(totalqty) + parseInt(qty);
				}
				var postingdate = $('input[name="cf_4627"]').val();
				var nettotalprice = $('#netTotal').text();
				var category = $('select[name="productcategory"]').val();
				var branch = $('[name="cf_nrl_plantmaster164_id"]').val();
				var discountallow = $('[name="cf_5197"]').val();
				var advdiscountallow = $('[name="cf_5209"]').val();
				if(branch == '')
				{
					alert("Please Select Branch First");
				}
				if(category == '')
				{
					alert("Please Select Category First");
				}
				if(postingdate == '')
				{
					alert("Please Select Posting Date First");
				}
				if(accountid == '')
				{
					alert("Please Select Customer First");
				}
				if(discountallow == '')
				{
					alert("Please Select Discount Allow or Not");
				}
				if(advdiscountallow == '')
				{
					alert("Please Select Cash Discount on Advance Payment Allow or Not");
				}
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {branch: branch, category: category, totalqty: totalqty, nettotalprice: nettotalprice, date: postingdate, accountid: accountid, discountallow: discountallow, advdiscountallow: advdiscountallow, action: 'checkTotalDiscount'},
				dataType: 'json',
				success:function(response)
				{
					if(response.discountapply == 'Yes')
					{
						$('#dynamicDiscount').addClass('hide');
						$('#staticDiscount').removeClass('hide');
					
					if(response.totalamount == null)
					{
						response.totalamount = nettotalprice;
					}
					if(response.totaldeductamount == null)
					{
						response.totaldeductamount = 0.00;
					}
					$('#preTaxTotal').text(response.totalamount);
					var taxtotal = $('#tax_final').text();
					var grandtotal = (parseFloat(response.totalamount) + parseFloat(taxtotal)).toFixed(2);
					$('#grandTotal').text(grandtotal);
					$('#overallDiscount').text("("+response.totaldeductamount+")");
					$('#discountTotal_final').text(response.totaldeductamount);
					if(response.advpercent != null || response.paypercent != null || response.paypercentcash !=null || response.pay7percent != null || response.pay15percent != null || response.pay30percent != null)
					{
						$('.popupPaymentTable').show();
						$('#overalladvancepercent').val(response.advpercent);
						$('#overalladvancepercentval').val(response.advpercentamount);
						$('#overallsamedaypercent').val(response.paypercent);
						$('#overallsamedaypercentval').val(response.paypercentamount);
						$('#samedayInvoiceId').val(response.involdsame);
						$('#overallsamedaycashpercent').val(response.paypercentcash);
						$('#overallsamedaycashpercentval').val(response.paypercentcashamount);
						$('#samedaycashInvoiceId').val(response.involdsamecash);
						$('#overall7dayspercent').val(response.pay7percent);
						$('#overall7dayspercentval').val(response.pay7percentamount);
						$('#within7daysInvoiceId').val(response.invold7);
						$('#overall15dayspercent').val(response.pay15percent);
						$('#overall15dayspercentval').val(response.pay15percentamount);
						$('#within15daysInvoiceId').val(response.invold15);
						$('#overall30dayspercent').val(response.pay30percent);
						$('#overall30dayspercentval').val(response.pay30percentamount);
						$('#within30daysInvoiceId').val(response.invold30);
					}
					else
					{
						$('.popupPaymentTable').hide();
						$('#overalladvancepercent').val(0.00);
						$('#overalladvancepercentval').val(0.00);
						$('#overallsamedaypercent').val(0.00);
						$('#overallsamedaypercentval').val(0.00);
						$('#samedayInvoiceId').val('');
						$('#overallsamedaycashpercent').val(0.00);
						$('#overallsamedaycashpercentval').val(0.00);
						$('#samedaycashInvoiceId').val('');
						$('#overall7dayspercent').val(0.00);
						$('#overall7dayspercentval').val(0.00);
						$('#within7daysInvoiceId').val('');
						$('#overall15dayspercent').val(0.00);
						$('#overall15dayspercentval').val(0.00);
						$('#within15daysInvoiceId').val('');
						$('#overall30dayspercent').val(0.00);
						$('#overall30dayspercentval').val(0.00);
						$('#within30daysInvoiceId').val('');
					}
					if(response.monthlydiscountstatus == 'Active')
					{
						$('.popupMonthlyTable').show();
						$('#overallmonthlycashamount').val(response.monthunitamount);
						$('#totaloverallmonthlycashamount').val(response.monthtotaldeduct);
						$('#overallmonthlycashpercent').val(response.monthcashpercent);
						$('#overallmonthlycashpercentval').val(response.monthcashpercentval);
						$('#overallmonthlytargetpercent').val(response.monthtargetpercent);
						$('#overallmonthlytargetpercentval').val(response.monthtargetpercentval);
						$('#overallmonthlyretailerpercent').val(response.monthretailerpercent);
						$('#overallmonthlyretailerpercentval').val(response.monthretailerpercentval);
					}
					else
					{
						$('.popupMonthlyTable').hide();
						$('#overallmonthlycashamount').val(0.00);
						$('#totaloverallmonthlycashamount').val(0.00);
						$('#overallmonthlycashpercent').val(0.00);
						$('#overallmonthlycashpercentval').val(0.00);
						$('#overallmonthlytargetpercent').val(0.00);
						$('#overallmonthlytargetpercentval').val(0.00);
						$('#overallmonthlyretailerpercent').val(0.00);
						$('#overallmonthlyretailerpercentval').val(0.00);
					}
					if(response.quarterlydiscountstatus == 'Active')
					{
						$('.popupQuarterlyTable').show();
						$('#overallquarterlycashamount').val(response.quarterunitamount);
						$('#totaloverallquarterlycashamount').val(response.quartertotaldeduct);
						$('#overallquarterlycashpercent').val(response.quartercashpercent);
						$('#overallquarterlycashpercentval').val(response.quartercashpercentval);
						$('#overallquarterlytargetpercent').val(response.quartertargetpercent);
						$('#overallquarterlytargetpercentval').val(response.quartertargetpercentval);
						$('#overallquarterlyretailerpercent').val(response.quarterretailerpercent);
						$('#overallquarterlyretailerpercentval').val(response.quarterretailerpercentval);
					}
					else
					{
						$('.popupQuarterlyTable').hide();
						$('#overallquarterlycashamount').val(0.00);
						$('#totaloverallquarterlycashamount').val(0.00);
						$('#overallquarterlycashpercent').val(0.00);
						$('#overallquarterlycashpercentval').val(0.00);
						$('#overallquarterlytargetpercent').val(0.00);
						$('#overallquarterlytargetpercentval').val(0.00);
						$('#overallquarterlyretailerpercent').val(0.00);
						$('#overallquarterlyretailerpercentval').val(0.00);
					}
					if(response.halfyearlydiscountstatus == 'Active')
					{
						$('.popupHalfYearlyTable').show();
						$('#overallhalfyearlycashamount').val(response.halfyearunitamount);
						$('#totaloverallhalfyearlycashamount').val(response.halfyeartotaldeduct);
						$('#overallhalfyearlycashpercent').val(response.halfyearcashpercent);
						$('#overallhalfyearlycashpercentval').val(response.halfyearcashpercentval);
						$('#overallhalfyearlytargetpercent').val(response.halfyeartargetpercent);
						$('#overallhalfyearlytargetpercentval').val(response.halfyeartargetpercentval);
						$('#overallhalfyearlyretailerpercent').val(response.halfyearretailerpercent);
						$('#overallhalfyearlyretailerpercentval').val(response.halfyearretailerpercentval);
					}
					else
					{
						$('.popupHalfYearlyTable').hide();
						$('#overallhalfyearlycashamount').val(0.00);
						$('#totaloverallhalfyearlycashamount').val(0.00);
						$('#overallhalfyearlycashpercent').val(0.00);
						$('#overallhalfyearlycashpercentval').val(0.00);
						$('#overallhalfyearlytargetpercent').val(0.00);
						$('#overallhalfyearlytargetpercentval').val(0.00);
						$('#overallhalfyearlyretailerpercent').val(0.00);
						$('#overallhalfyearlyretailerpercentval').val(0.00);
					}
					if(response.annuallydiscountstatus == 'Active')
					{
						$('.popupAnnuallyTable').show();
						$('#overallannuallycashamount').val(response.annualunitamount);
						$('#totaloverallannuallycashamount').val(response.annnualtotaldeduct);
						$('#overallannuallycashpercent').val(response.annualcashpercent);
						$('#overallannuallycashpercentval').val(response.annualcashpercentval);
						$('#overallannuallytargetpercent').val(response.annualtargetpercent);
						$('#overallannuallytargetpercentval').val(response.annualtargetpercentval);
						$('#overallannuallyretailerpercent').val(response.annualretailerpercent);
						$('#overallannuallyretailerpercentval').val(response.annualretailerpercentval);
					}
					else
					{
						$('.popupAnnuallyTable').hide();
						$('#overallannuallycashamount').val(0.00);
						$('#totaloverallannuallycashamount').val(0.00);
						$('#overallannuallycashpercent').val(0.00);
						$('#overallannuallycashpercentval').val(0.00);
						$('#overallannuallytargetpercent').val(0.00);
						$('#overallannuallytargetpercentval').val(0.00);
						$('#overallannuallyretailerpercent').val(0.00);
						$('#overallannuallyretailerpercentval').val(0.00);
					}
					$('.popoverButton').click();
					$('#overallmonthlycashamount').prop('readonly','true');
					$('#totaloverallmonthlycashamount').prop('readonly','true');
					$('#overallmonthlycashpercent').prop('readonly','true');
					$('#overallmonthlycashpercentval').prop('readonly','true');
					$('#overallmonthlytargetpercent').prop('readonly','true');
					$('#overallmonthlytargetpercentval').prop('readonly','true');
					$('#overallmonthlyretailerpercent').prop('readonly','true');
					$('#overallmonthlyretailerpercentval').prop('readonly','true');
					$('#overallquarterlycashamount').prop('readonly','true');
					$('#totaloverallquarterlycashamount').prop('readonly','true');
					$('#overallquarterlycashpercent').prop('readonly','true');
					$('#overallquarterlycashpercentval').prop('readonly','true');
					$('#overallquarterlytargetpercent').prop('readonly','true');
					$('#overallquarterlytargetpercentval').prop('readonly','true');
					$('#overallquarterlyretailerpercent').prop('readonly','true');
					$('#overallquarterlyretailerpercentval').prop('readonly','true');
					$('#overallhalfyearlycashamount').prop('readonly','true');
					$('#totaloverallhalfyearlycashamount').prop('readonly','true');
					$('#overallhalfyearlycashpercent').prop('readonly','true');
					$('#overallhalfyearlycashpercentval').prop('readonly','true');
					$('#overallhalfyearlytargetpercent').prop('readonly','true');
					$('#overallhalfyearlytargetpercentval').prop('readonly','true');
					$('#overallhalfyearlyretailerpercent').prop('readonly','true');
					$('#overallhalfyearlyretailerpercentval').prop('readonly','true');
					$('#overallannuallycashamount').prop('readonly','true');
					$('#totaloverallannuallycashamount').prop('readonly','true');
					$('#overallannuallycashpercent').prop('readonly','true');
					$('#overallannuallycashpercentval').prop('readonly','true');
					$('#overallannuallytargetpercent').prop('readonly','true');
					$('#overallannuallyretailerpercent').prop('readonly','true');
					$('#overallannuallyretailerpercentval').prop('readonly','true');
					$('#overalladvancepercent').prop('readonly','true');
					$('#overalladvancepercentval').prop('readonly','true');
					$('#overallsamedaypercent').prop('readonly','true');
					$('#overallsamedaypercentval').prop('readonly','true');
					$('#overallsamedaycashpercent').prop('readonly','true');
					$('#overallsamedaycashpercentval').prop('readonly','true');
					$('#overall7dayspercent').prop('readonly','true');
					$('#overall7dayspercentval').prop('readonly','true');
					$('#overall15dayspercent').prop('readonly','true');
					$('#overall15dayspercentval').prop('readonly','true');
					$('#overall30dayspercent').prop('readonly','true');
					$('#overall30dayspercentval').prop('readonly','true');
				}
				else
				{
					$('#dynamicDiscount').removeClass('hide');
					$('#staticDiscount').addClass('hide');
					if($('#staticDiscount').hasClass('hide') == true){
					$('.lineItemPopupModalFooter').html('<center><button class="btn btn-success popoverButton" type="button"><strong>'+app.vtranslate('JS_LBL_SAVE')+'</strong></button><a href="#" class="popoverCancel" type="reset">'+app.vtranslate('JS_LBL_CANCEL')+'</a></center>');
					}
				}

				}
				});
	}
	if(referenceModule=='PriceBooks' && sourceModule=='SalesOrder'){
		var reference = $('select[name="cf_3286"]').val();
		if(reference == 'Against Warranty')
		{
			$('#preTaxTotal').text('0.00');
			$('#grandTotal').text('0.00');
			var nettotal = $('#netTotal').text();
			$('#discountTotal_final').text(nettotal);
			$('#overallDiscount').text('(100%)');
			$('.groupTaxTotal').val('0.00');
			$('#tax_final').text('0.00');
		}else{
			var tablelen = $('table#lineItemTab > tbody > tr').length - 2;
			var accountid = $('input[name="account_id"]').val();
				var itemno = tablelen;
				var totalqty = 0;
				for(i=1;i<=itemno;i++)
				{
					var qty = $('#qty'+i).val();
					totalqty = parseInt(totalqty) + parseInt(qty);
				}
				var postingdate = $('input[name="cf_4306"]').val();
				var nettotalprice = $('#netTotal').text();
				var category = $('select[name="productcategory"]').val();
				var branch = $('[name="cf_nrl_plantmaster580_id"]').val();
				var discountallow = $('[name="cf_5195"]').val();
				var advdiscountallow = $('[name="cf_5207"]').val();
				if(branch == '')
				{
					alert("Please Select Branch First");
				}
				if(category == '')
				{
					alert("Please Select Category First");
				}
				if(postingdate == '')
				{
					alert("Please Select Posting Date First");
				}
				if(accountid == '')
				{
					alert("Please Select Customer First");
				}
				if(discountallow == '')
				{
					alert("Please Select Discount Allow or Not");
				}
				if(advdiscountallow == '')
				{
					alert("Please Select Cash Discount on Advance Payment Allow or Not");
				}
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {branch: branch, category: category, totalqty: totalqty, nettotalprice: nettotalprice, date: postingdate, accountid: accountid, discountallow: discountallow, advdiscountallow: advdiscountallow, action: 'checkTotalDiscount'},
				dataType: 'json',
				success:function(response)
				{
					if(response.discountapply == 'Yes')
					{
						$('#dynamicDiscount').addClass('hide');
						$('#staticDiscount').removeClass('hide');
					if(response.totalamount == null)
					{
						response.totalamount = nettotalprice;
					}
					if(response.totaldeductamount == null)
					{
						response.totaldeductamount = 0.00;
					}
					$('#preTaxTotal').text(response.totalamount);
					var taxtotal = $('#tax_final').text();
					var grandtotal = (parseFloat(response.totalamount) + parseFloat(taxtotal)).toFixed(2);
					$('#grandTotal').text(grandtotal);
					$('#overallDiscount').text("("+response.totaldeductamount+")");
					$('#discountTotal_final').text(response.totaldeductamount);
					if(response.advpercent != null || response.paypercent != null || response.paypercentcash !=null || response.pay7percent != null || response.pay15percent != null || response.pay30percent != null)
					{
						$('.popupPaymentTable').show();
						$('#overalladvancepercent').val(response.advpercent);
						$('#overalladvancepercentval').val(response.advpercentamount);
						$('#overallsamedaypercent').val(response.paypercent);
						$('#overallsamedaypercentval').val(response.paypercentamount);
						$('#samedayInvoiceId').val(response.involdsame);
						$('#overallsamedaycashpercent').val(response.paypercentcash);
						$('#overallsamedaycashpercentval').val(response.paypercentcashamount);
						$('#samedaycashInvoiceId').val(response.involdsamecash);
						$('#overall7dayspercent').val(response.pay7percent);
						$('#overall7dayspercentval').val(response.pay7percentamount);
						$('#within7daysInvoiceId').val(response.invold7);
						$('#overall15dayspercent').val(response.pay15percent);
						$('#overall15dayspercentval').val(response.pay15percentamount);
						$('#within15daysInvoiceId').val(response.invold15);
						$('#overall30dayspercent').val(response.pay30percent);
						$('#overall30dayspercentval').val(response.pay30percentamount);
						$('#within30daysInvoiceId').val(response.invold30);
					}
					else
					{
						$('.popupPaymentTable').hide();
						$('#overalladvancepercent').val(0.00);
						$('#overalladvancepercentval').val(0.00);
						$('#overallsamedaypercent').val(0.00);
						$('#overallsamedaypercentval').val(0.00);
						$('#samedayInvoiceId').val('');
						$('#overallsamedaycashpercent').val(0.00);
						$('#overallsamedaycashpercentval').val(0.00);
						$('#samedaycashInvoiceId').val('');
						$('#overall7dayspercent').val(0.00);
						$('#overall7dayspercentval').val(0.00);
						$('#within7daysInvoiceId').val('');
						$('#overall15dayspercent').val(0.00);
						$('#overall15dayspercentval').val(0.00);
						$('#within15daysInvoiceId').val('');
						$('#overall30dayspercent').val(0.00);
						$('#overall30dayspercentval').val(0.00);
						$('#within30daysInvoiceId').val('');
					}
					if(response.monthlydiscountstatus == 'Active')
					{
						$('.popupMonthlyTable').show();
						$('#overallmonthlycashamount').val(response.monthunitamount);
						$('#totaloverallmonthlycashamount').val(response.monthtotaldeduct);
						$('#overallmonthlycashpercent').val(response.monthcashpercent);
						$('#overallmonthlycashpercentval').val(response.monthcashpercentval);
						$('#overallmonthlytargetpercent').val(response.monthtargetpercent);
						$('#overallmonthlytargetpercentval').val(response.monthtargetpercentval);
						$('#overallmonthlyretailerpercent').val(response.monthretailerpercent);
						$('#overallmonthlyretailerpercentval').val(response.monthretailerpercentval);
					}
					else
					{
						$('.popupMonthlyTable').hide();
						$('#overallmonthlycashamount').val(0.00);
						$('#totaloverallmonthlycashamount').val(0.00);
						$('#overallmonthlycashpercent').val(0.00);
						$('#overallmonthlycashpercentval').val(0.00);
						$('#overallmonthlytargetpercent').val(0.00);
						$('#overallmonthlytargetpercentval').val(0.00);
						$('#overallmonthlyretailerpercent').val(0.00);
						$('#overallmonthlyretailerpercentval').val(0.00);
					}
					if(response.quarterlydiscountstatus == 'Active')
					{
						$('.popupQuarterlyTable').show();
						$('#overallquarterlycashamount').val(response.quarterunitamount);
						$('#totaloverallquarterlycashamount').val(response.quartertotaldeduct);
						$('#overallquarterlycashpercent').val(response.quartercashpercent);
						$('#overallquarterlycashpercentval').val(response.quartercashpercentval);
						$('#overallquarterlytargetpercent').val(response.quartertargetpercent);
						$('#overallquarterlytargetpercentval').val(response.quartertargetpercentval);
						$('#overallquarterlyretailerpercent').val(response.quarterretailerpercent);
						$('#overallquarterlyretailerpercentval').val(response.quarterretailerpercentval);
					}
					else
					{
						$('.popupQuarterlyTable').hide();
						$('#overallquarterlycashamount').val(0.00);
						$('#totaloverallquarterlycashamount').val(0.00);
						$('#overallquarterlycashpercent').val(0.00);
						$('#overallquarterlycashpercentval').val(0.00);
						$('#overallquarterlytargetpercent').val(0.00);
						$('#overallquarterlytargetpercentval').val(0.00);
						$('#overallquarterlyretailerpercent').val(0.00);
						$('#overallquarterlyretailerpercentval').val(0.00);
					}
					if(response.halfyearlydiscountstatus == 'Active')
					{
						$('.popupHalfYearlyTable').show();
						$('#overallhalfyearlycashamount').val(response.halfyearunitamount);
						$('#totaloverallhalfyearlycashamount').val(response.halfyeartotaldeduct);
						$('#overallhalfyearlycashpercent').val(response.halfyearcashpercent);
						$('#overallhalfyearlycashpercentval').val(response.halfyearcashpercentval);
						$('#overallhalfyearlytargetpercent').val(response.halfyeartargetpercent);
						$('#overallhalfyearlytargetpercentval').val(response.halfyeartargetpercentval);
						$('#overallhalfyearlyretailerpercent').val(response.halfyearretailerpercent);
						$('#overallhalfyearlyretailerpercentval').val(response.halfyearretailerpercentval);
					}
					else
					{
						$('.popupHalfYearlyTable').hide();
						$('#overallhalfyearlycashamount').val(0.00);
						$('#totaloverallhalfyearlycashamount').val(0.00);
						$('#overallhalfyearlycashpercent').val(0.00);
						$('#overallhalfyearlycashpercentval').val(0.00);
						$('#overallhalfyearlytargetpercent').val(0.00);
						$('#overallhalfyearlytargetpercentval').val(0.00);
						$('#overallhalfyearlyretailerpercent').val(0.00);
						$('#overallhalfyearlyretailerpercentval').val(0.00);
					}
					if(response.annuallydiscountstatus == 'Active')
					{
						$('.popupAnnuallyTable').show();
						$('#overallannuallycashamount').val(response.annualunitamount);
						$('#totaloverallannuallycashamount').val(response.annnualtotaldeduct);
						$('#overallannuallycashpercent').val(response.annualcashpercent);
						$('#overallannuallycashpercentval').val(response.annualcashpercentval);
						$('#overallannuallytargetpercent').val(response.annualtargetpercent);
						$('#overallannuallytargetpercentval').val(response.annualtargetpercentval);
						$('#overallannuallyretailerpercent').val(response.annualretailerpercent);
						$('#overallannuallyretailerpercentval').val(response.annualretailerpercentval);
					}
					else
					{
						$('.popupAnnuallyTable').hide();
						$('#overallannuallycashamount').val(0.00);
						$('#totaloverallannuallycashamount').val(0.00);
						$('#overallannuallycashpercent').val(0.00);
						$('#overallannuallycashpercentval').val(0.00);
						$('#overallannuallytargetpercent').val(0.00);
						$('#overallannuallytargetpercentval').val(0.00);
						$('#overallannuallyretailerpercent').val(0.00);
						$('#overallannuallyretailerpercentval').val(0.00);
					}
					$('.popoverButton').click();
					$('#overallmonthlycashamount').prop('readonly','true');
					$('#totaloverallmonthlycashamount').prop('readonly','true');
					$('#overallmonthlycashpercent').prop('readonly','true');
					$('#overallmonthlycashpercentval').prop('readonly','true');
					$('#overallmonthlytargetpercent').prop('readonly','true');
					$('#overallmonthlytargetpercentval').prop('readonly','true');
					$('#overallmonthlyretailerpercent').prop('readonly','true');
					$('#overallmonthlyretailerpercentval').prop('readonly','true');
					$('#overallquarterlycashamount').prop('readonly','true');
					$('#totaloverallquarterlycashamount').prop('readonly','true');
					$('#overallquarterlycashpercent').prop('readonly','true');
					$('#overallquarterlycashpercentval').prop('readonly','true');
					$('#overallquarterlytargetpercent').prop('readonly','true');
					$('#overallquarterlytargetpercentval').prop('readonly','true');
					$('#overallquarterlyretailerpercent').prop('readonly','true');
					$('#overallquarterlyretailerpercentval').prop('readonly','true');
					$('#overallhalfyearlycashamount').prop('readonly','true');
					$('#totaloverallhalfyearlycashamount').prop('readonly','true');
					$('#overallhalfyearlycashpercent').prop('readonly','true');
					$('#overallhalfyearlycashpercentval').prop('readonly','true');
					$('#overallhalfyearlytargetpercent').prop('readonly','true');
					$('#overallhalfyearlytargetpercentval').prop('readonly','true');
					$('#overallhalfyearlyretailerpercent').prop('readonly','true');
					$('#overallhalfyearlyretailerpercentval').prop('readonly','true');
					$('#overallannuallycashamount').prop('readonly','true');
					$('#totaloverallannuallycashamount').prop('readonly','true');
					$('#overallannuallycashpercent').prop('readonly','true');
					$('#overallannuallycashpercentval').prop('readonly','true');
					$('#overallannuallytargetpercent').prop('readonly','true');
					$('#overallannuallyretailerpercent').prop('readonly','true');
					$('#overallannuallyretailerpercentval').prop('readonly','true');
					$('#overalladvancepercent').prop('readonly','true');
					$('#overalladvancepercentval').prop('readonly','true');
					$('#overallsamedaypercent').prop('readonly','true');
					$('#overallsamedaypercentval').prop('readonly','true');
					$('#overallsamedaycashpercent').prop('readonly','true');
					$('#overallsamedaycashpercentval').prop('readonly','true');
					$('#overall7dayspercent').prop('readonly','true');
					$('#overall7dayspercentval').prop('readonly','true');
					$('#overall15dayspercent').prop('readonly','true');
					$('#overall15dayspercentval').prop('readonly','true');
					$('#overall30dayspercent').prop('readonly','true');
					$('#overall30dayspercentval').prop('readonly','true');
				}
				else
				{
					$('#dynamicDiscount').removeClass('hide');
					$('#staticDiscount').addClass('hide');
					if($('#staticDiscount').hasClass('hide') == true){
					$('.lineItemPopupModalFooter').html('<center><button class="btn btn-success popoverButton" type="button"><strong>'+app.vtranslate('JS_LBL_SAVE')+'</strong></button><a href="#" class="popoverCancel" type="reset">'+app.vtranslate('JS_LBL_CANCEL')+'</a></center>');
					}
				}

				}
				});
		}
	}
	if(referenceModule=='Products' && sourcemodule=='SalesOrder'){
		var id = JSON.parse(Object.keys(result[0]));
		var productid = id;
		$.ajax({
					type:"post",
					url: "shirshaAjax.php",
					data: {productid: productid, action: 'getProductAllDetails'},
					dataType: 'json',
					success:function(response)
					{
						var trid = localStorage.getItem('selectedtrid');
						var trlen = trid.length;
						var dataselid = trid.substr('3',trlen);
						$("input[name='productcode"+dataselid+"']").val(response.productcode);
						$("input[name='itemunit"+dataselid+"']").val(response.productunit);
						$("input[name='productcode"+dataselid+"']").attr('readonly',true);
						$("input[name='itemunit"+dataselid+"']").attr('readonly',true);
					}
				});
	}
	if(referenceModule == "Products" && sourcemodule == "Invoice")
			{
				var id = JSON.parse(Object.keys(result[0]));
				var productid = id;
				$.ajax(
				{
					type:"post",
					url: "shirshaAjax.php",
					data: {productid: productid, action: 'getProductAllDetails'},
					dataType: 'json',
					success:function(response)
					{
						var trid = localStorage.getItem('selectedtrid');
						var trlen = trid.length;
						var dataselid = trid.substr('3',trlen);
						$("input[name='productcode"+dataselid+"']").val(response.productcode);
						$("input[name='itemunit"+dataselid+"']").val(response.productunit);
						$("input[name='productcode"+dataselid+"']").attr('readonly',true);
						$("input[name='itemunit"+dataselid+"']").attr('readonly',true);
					}
				});
			}
		    if(referenceModule=='Products' && sourcemodule=='PurchaseOrder'){
			   var id = JSON.parse(Object.keys(result[0]));
         var vendorid = $('[name="vendor_id"]').val();
         var plantid = $('[name="cf_nrl_plantmaster950_id"]').val();
	    	 var currencyid = $('[name="currency_id"]').val();

				$.ajax(
				{
				type:"post",
				url: "arocrmAjax.php",
				data: {id: id, vendorid:vendorid,currencyid:currencyid,plantid:plantid, action: 'getProductCodeUnit'},
				dataType: 'json',
				success:function(response)
				{
          var listp = 0.00;
          var listinrp = 0.00;
					var trid = localStorage.getItem('selectedtrid');
					var trlen = trid.length;
					var dataselid = trid.substr('3',trlen);
					$("input[name='productcode"+dataselid+"']").val(response.productcode);
					$("input[name='itemunit"+dataselid+"']").val(response.unit);
					$("input[name='no_warranty_card"+dataselid+"']").val(response.warranty);
          if(response.listprice!=undefined || response.listprice!=''){
          listp = response.listprice;
          }
          if(response.listinrprice!=undefined || response.listinrprice!=''){
          listinrp = response.listinrprice;
          }
          $("input[name='listPrice"+dataselid+"']").val(listp);
          $("input[name='inr_rate"+dataselid+"']").val(listinrp);
          $("input[name='listPrice"+dataselid+"']").focus();
				}
				});
		    }

            if(referenceModule=='Services' && sourcemodule=='PurchaseOrder'){

            var id = JSON.parse(Object.keys(result[0]));
            $.ajax(
            {
            type:"post",
            url: "arocrmAjax.php",
            data: {id: id, action: 'getServiceCodeUnit'},
            dataType: 'json',
            success:function(response)
            {

              var trid = localStorage.getItem('selectedtrid');
              var trlen = trid.length;

              var dataselid = trid.substr('3',trlen);
              $("input[name='productcode"+dataselid+"']").val(response.service_no);
              $("input[name='productcode"+dataselid+"']").prop("readonly",true);
              $("input[name='itemunit"+dataselid+"']").val(response.service_usageunit);
              $("input[name='itemunit"+dataselid+"']").prop("readonly",true);
            }
            });


            }

			if(referenceModule=='Products' && sourcemodule=='PurchaseReq'){
			  var id = Object.keys(result)[0];
			  
			  $.ajax(
				{
				type:"post",
				url: "arocrmAjax.php",
				data: {id: id, action: 'getProductCodeUnit'},
				dataType: 'json',
				success:function(response)
				{
					var trid = localStorage.getItem('tagmoduleid');
					var trct = trid.split("_");
					var trctt = trct.length;
					if(trctt==2){
					$("[name='cf_2836']").val(response.productcode);
					$("[name='cf_2836']").prop('readonly','true');
					$("[name='cf_1742']").val(response.unit);
					$("[name='cf_1742']").prop('readonly','true');
					$("[name='cf_4870']").val(response.ah);
					$("[name='cf_4870']").prop('readonly','true');
					$("[name='cf_4868']").val(response.category);
					$("[name='cf_4868']").prop('readonly','true');
					$("[name='cf_5029']").val(response.warranty);
					$("[name='cf_5029']").prop('readonly','true');
					}else if(trctt==3){
					var dataselid = trct[trctt-1];
					$("[name='cf_2836_"+dataselid+"']").val(response.productcode);
					$("[name='cf_2836_"+dataselid+"']").prop('readonly','true');
					$("[name='cf_1742_"+dataselid+"']").val(response.unit);
					$("[name='cf_1742_"+dataselid+"']").prop('readonly','true');
					$("[name='cf_4870_"+dataselid+"']").val(response.ah);
					$("[name='cf_4870_"+dataselid+"']").prop('readonly','true');
					$("[name='cf_4868_"+dataselid+"']").val(response.category);
					$("[name='cf_4868_"+dataselid+"']").prop('readonly','true');
					$("[name='cf_5029_"+dataselid+"']").val(response.warranty);
					$("[name='cf_5029_"+dataselid+"']").prop('readonly','true');
					}
				}
				});
			  
		    }
			
			
			
        if(referenceModule=='Products' && sourcemodule=='StoretoStoreTransfer'){
          var id = Object.keys(result)[0];
            $.ajax(
          {
          type:"post",
          url: "arocrmAjax.php",
          data: {id: id, action: 'getProductCodeUnit'},
          dataType: 'json',
          success:function(response)
          {
            $("[name='cf_4752']").val(response.productcode);
            $("[name='cf_4752']").prop('readonly','true');
            $("[name='cf_4754']").val(response.unit);
            $("[name='cf_4754']").prop('readonly','true');
          }
          });
          }
		
        if(referenceModule=='Products' && sourcemodule=='StockUpload'){
  			  var id = Object.keys(result)[0];
  			  	$.ajax(
  				{
  				type:"post",
  				url: "arocrmAjax.php",
  				data: {id: id, action: 'getProductCodeUnit'},
  				dataType: 'json',
  				success:function(response)
  				{
  					var trid = localStorage.getItem('tagmoduleid');
  					var trct = trid.split("_");
  					var trctt = trct.length;
  					if(trctt==2){
  					$("[name='cf_4711']").val(response.productcode);
  					$("[name='cf_4711']").prop('readonly','true');
  					$("[name='cf_4713']").val(response.unit);
  					$("[name='cf_4713']").prop('readonly','true');
  					}else if(trctt==3){
  					var dataselid = trct[trctt-1];
  					$("[name='cf_4711_"+dataselid+"']").val(response.productcode);
  					$("[name='cf_4711_"+dataselid+"']").prop('readonly','true');
  					$("[name='cf_4713_"+dataselid+"']").val(response.unit);
  					$("[name='cf_4713_"+dataselid+"']").prop('readonly','true');
  					}
  				}
  				});
  		    }



        }
        app.helper.hidePopup();
    },

    showPopup : function(params,eventToTrigger,callback) {
        app.helper.hidePopup();
        app.helper.showProgress();
        app.request.post({"data":params}).then(function(err,data) {
            app.helper.hideProgress();
            if(err === null) {
                var options = {};
                if(typeof callback != 'undefined') {
                    options.cb = callback;
                }
                app.helper.showPopup(data,options);
                app.event.trigger("post.Popup.Load",{"eventToTrigger":eventToTrigger, 'module':params.module});
            }
        });
    },
    getUrlVars : function()
    {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
    },



    getListViewEntries: function(e){
		e.preventDefault();
        var preEvent = jQuery.Event('pre.popupSelect.click');
		app.event.trigger(preEvent);
        if(preEvent.isDefaultPrevented()){
            return;
        }
		var thisInstance = this;
		var row  = jQuery(e.currentTarget);

		var dataUrl = row.data('url');
		if(typeof dataUrl != 'undefined'){
			dataUrl = dataUrl+'&currency_id='+jQuery('#currencyId').val();
		    app.request.post({"url":dataUrl}).then(
			function(err,data){
        for(var id in data){

				    if(typeof data[id] == "object"){
					var recordData = data[id];
				    }
				}

                thisInstance.done(data,thisInstance.getEventName());

			});
         e.preventDefault();
		} else {
		  var id = row.data('id');
			var recordName = row.attr('data-name');
			var recordInfo = row.data('info');
			var referenceModule = jQuery('#popupPageContainer').find('#module').val();
			var sourcemodule = this.getSourceModule();
			var recordid = this.getSourceRecord();
			var response ={};

		     response[id] = {'name' : recordName,'info' : recordInfo, 'module' : referenceModule};
			   thisInstance.done(response,thisInstance.getEventName());
			   
			   		if(referenceModule=='PlantMaster' && sourcemodule=='StockUpload'){
			var yr = $('select[name="cf_4633"]').val();
					var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4635"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4979"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4979"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4979"]').datepicker('setStartDate', minDate);
												
												var maxDate = new Date(today);
												$('input[name="cf_4979"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
		}
			   
			   if(referenceModule=='PlantMaster' && sourcemodule=='PurchaseReq'){
				var yr = $('select[name="cf_4601"]').val();
				var month = $('select[name="cf_4603"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var years = lstyr;
					}
					else
					{
						var years = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: years, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && years == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = years + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && years == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = years + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: years, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = years + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = years + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3202"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3202"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3202"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3202"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}



		if(referenceModule=='PlantMaster' && sourcemodule=='StoretoStoreTransfer'){
			var yr = $('select[name="cf_4633"]').val();
		var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4635"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4973"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4973"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4973"]').datepicker('setStartDate', minDate);
												
												var maxDate = new Date(today);
												$('input[name="cf_4973"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
		}
			
			if(referenceModule=='PlantMaster' && sourcemodule=='SalesPlan'){
				var month = $('[name="cf_3502"]').val();
				var years = $('[name="cf_3506"]').val();
				var assignedto = $('[name="assigned_user_id"]').val();
				var plantid = id;
				  $.ajax(
					  {
					  type:"post",
					  url: "arocrmAjax.php",
					  data: { plantid:plantid, years:years, month:month, assignedto:assignedto, action: 'getallsalesbudgetqty'},
					  dataType: 'json',
					  success:function(response)
					  {
					  localStorage.setItem('salesplanvalues',JSON.stringify(response));
					  }
					});
					var year = $('select[name="cf_3506"]').val();
							var quartermonth = $('select[name="cf_3502"]').val();
							var d = new Date();
							var curmonth = d.getMonth()+1;
							var curday = d.getDate();
							var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
							var curyear = d.getFullYear();
							var y = year.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var qmonth = quartermonth.split(" - ");
					var months = qmonth[0];
					if(months == 'January' || months == 'February' || months == 'March')
					{
						var yearval = lstyr;
					}
					else
					{
						var yearval = fstyr;
					}
					var plant = plantid;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: yearval, month: months, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4850"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4850"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4850"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_4850"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			
			if(referenceModule=='Accounts' && sourcemodule=='SalesOrder'){
				$.ajax(
				{
					type:"post",
					url: "shirshaAjax.php",
					data: {id: id, action: 'getContact'},
					dataType: 'json',
					success:function(response)
					{
						$('input[name="customerno"]').val(response.custno);
						$('input[name="customerno"]').attr('readonly',true);
						if(response.contactid != "" && response.contactid != null)
						{
							$('input[name="contact_id"]').val(response.contactid);
							$('input[name="contact_id_display"]').val(response.contactname);
							$('input[name="contact_id_display"]').attr('readonly',true);
							$('#SalesOrder_editView_fieldName_contact_id_select').parent().hide();
						}
						else
						{
							$('input[name="contact_id"]').val('');
							$('input[name="contact_id_display"]').val('');
							$('input[name="contact_id_display"]').attr('readonly',false);
							$('#SalesOrder_editView_fieldName_contact_id_select').parent().show();
						}
						//$('#SalesOrder_editView_fieldName_contact_id_select').parent().remove();
						//$('#SalesOrder_editView_fieldName_contact_id_create').parent().remove();
						$('[name="cf_5073"]').val(response.custgst);
						$('[name="cf_5075"]').val(response.custpan);
						$('[name="cf_5073"]').attr('readonly',true);
						$('[name="cf_5075"]').attr('readonly',true);
					}
				});
			}
			if(referenceModule=='Accounts' && sourcemodule=='Invoice'){
				$.ajax(
				{
					type:"post",
					url: "shirshaAjax.php",
					data: {id: id, action: 'getContact'},
					dataType: 'json',
					success:function(response)
					{
						$('input[name="customerno"]').val(response.custno);
						$('input[name="customerno"]').attr('readonly',true);
						if(response.contactid != "" && response.contactid != null)
						{
							$('input[name="contact_id"]').val(response.contactid);
							$('input[name="contact_id_display"]').val(response.contactname);
							$('input[name="contact_id_display"]').attr('readonly',true);
							$('#Invoice_editView_fieldName_contact_id_select').parent().hide();
						}
						else
						{
							$('input[name="contact_id"]').val('');
							$('input[name="contact_id_display"]').val('');
							$('input[name="contact_id_display"]').attr('readonly',false);
							$('#Invoice_editView_fieldName_contact_id_select').parent().show();
						}
						$('[name="cf_5115"]').val(response.custgst);
						$('[name="cf_5117"]').val(response.custpan);
						$('[name="cf_5115"]').attr('readonly',true);
						$('[name="cf_5117"]').attr('readonly',true);
					}
				});
			}
			if(referenceModule=='Vendors' && sourcemodule=='RFQMaintain'){
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {id: id, action: 'getVendorCurreny'},
				dataType: 'json',
				success:function(response)
				{
					$('[name="cf_5123"]').val(response.code);
					$('[name="cf_5123"]').attr('readonly',true);
				}
				});
			}
			if(referenceModule=='SalesOrder' && sourcemodule=='Invoice'){
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {id: id, action: 'getProductDetailsfromSO'},
				dataType: 'json',
				success:function(response)
				{

					if(response.message==""){
						if(response.reference == 'Against Warranty')
						{
							
							if(response.savestatestatus==0){
            localStorage.setItem('savestatestatus',0);
            $('.saveButton').prop("disabled",true);
            }else{
            localStorage.setItem('savestatestatus',1);
            $('.saveButton').prop("disabled",false);
            }
			$('[name="region_id"]').select2('data', { id: response.taxregion, text: response.taxregion});
			$('[name="region_id"]').select2().select2('readonly',true);
			$('[name="currency_id"]').select2('data', { id: response.currency, text: response.currency});
			$('[name="currency_id"]').select2().select2('readonly',true);
			$('[name="taxtype"]').select2('data', { id: response.taxtype, text: response.taxtype});
			$('[name="taxtype"]').select2().select2('readonly',true);
			$('input[name="cf_5115"]').val(response.gst);
			$('input[name="cf_5117"]').val(response.pan);
			$('input[name="cf_5115"]').attr('readonly',true);
			$('input[name="cf_5117"]').attr('readonly',true);
			$('input[name="cf_nrl_plantmaster164_id"]').val(response.plantid);
			$('input[name="cf_nrl_plantmaster164_id_display"]').val(response.plantname);
			$('input[name="cf_nrl_plantmaster164_id_display"]').attr('readonly','true');
			$('#Invoice_editView_fieldName_cf_nrl_plantmaster164_id_create').parent().remove();
			$('#Invoice_editView_fieldName_cf_nrl_plantmaster164_id_select').parent().remove();
			$('#Invoice_editView_fieldName_arocrm_purchaseorder_select').parent().remove();
			$('select[name="cf_5197"]').select2('data', { id: response.discountallow, text: response.discountallow});
			$('select[name="cf_5209"]').select2('data', { id: response.discountapply, text: response.discountapply});
			$("table#lineItemTab > tbody").empty();
						$("table#lineItemTab > tbody").html(response.html);
						$('#directMode_lineItemTab').val('1');
						$("[name=totalProductCount]").val(response.totalcount);
						$("#netTotal").html(response.subtotal);
						$("#preTaxTotal").html(response.subtotal);
						$("#grandTotal").html(response.alltotal);
						$('input[name="customerno"]').val(response.custno);
						$('input[name="customerno"]').attr('readonly',true);
						$('input[name="account_id"]').val(response.customer);
						$('input[name="account_id_display"]').val(response.accname);
						$('input[name="account_id_display"]').attr('readonly',true);
						$('#Invoice_editView_fieldName_account_id_select').parent().remove();
						$('#Invoice_editView_fieldName_account_id_create').parent().remove();
						$('input[name="contact_id"]').val(response.contactid);
						$('input[name="contact_id_display"]').val(response.contactname);
						$('input[name="contact_id_display"]').attr('readonly',true);
						$('#Invoice_editView_fieldName_contact_id_select').parent().remove();
						$('#Invoice_editView_fieldName_contact_id_create').parent().remove();
						$('select[name="productcategory"]').select2('data', { id: response.category, text: response.category});
						$('select[name="productcategory"]').select2().select2('readonly',true);
						
						$('#overallmonthlytargetpercent').val('0.00');
							$('#overallmonthlytargetpercentval').val('0.00');
							$('#overallquarterlytargetpercent').val('0.00');
							$('#overallquarterlytargetpercentval').val('0.00');
							$('#overallhalfyearlytargetpercent').val('0.00');
							$('#overallhalfyearlytargetpercentval').val('0.00');
							$('#overallannuallytargetpercent').val('0.00');
							$('#overallannuallytargetpercentval').val('0.00');
							$('#overalladvancepercent').val('0.00');
							$('#overalladvancepercentval').val('0.00');
							$('#overallsamedaypercent').val('0.00');
							$('#overallsamedaypercentval').val('0.00');
							$('#overallsamedaycashpercent').val('0.00');
							$('#overallsamedaycashpercentval').val('0.00');
							$('#overall7dayspercent').val('0.00');
							$('#overall7dayspercentval').val('0.00');
							$('#overall15dayspercent').val('0.00');
							$('#overall15dayspercentval').val('0.00');
							$('#overall30dayspercent').val('0.00');
							$('#overall30dayspercentval').val('0.00');
							var nettotal = $('#netTotal').text();
							$('#discountTotal_final').text(nettotal);
							$('#overallDiscount').text("(100%)");
							$('#preTaxTotal').text('0.00');
							$('#grandTotal').text('0.00');
						}
						else
						{


            if(response.savestatestatus==0){
            localStorage.setItem('savestatestatus',0);
            $('.saveButton').prop("disabled",true);
            }else{
            localStorage.setItem('savestatestatus',1);
            $('.saveButton').prop("disabled",false);
            }
			$('[name="region_id"]').select2('data', { id: response.taxregion, text: response.taxregion});
			$('[name="region_id"]').select2().select2('readonly',true);
			$('[name="currency_id"]').select2('data', { id: response.currency, text: response.currency});
			$('[name="currency_id"]').select2().select2('readonly',true);
			$('[name="taxtype"]').select2('data', { id: response.taxtype, text: response.taxtype});
			$('[name="taxtype"]').select2().select2('readonly',true);
			$('input[name="cf_5115"]').val(response.gst);
			$('input[name="cf_5117"]').val(response.pan);
			$('input[name="cf_5115"]').attr('readonly',true);
			$('input[name="cf_5117"]').attr('readonly',true);
			$('input[name="cf_nrl_plantmaster164_id"]').val(response.plantid);
			$('input[name="cf_nrl_plantmaster164_id_display"]').val(response.plantname);
			$('input[name="cf_nrl_plantmaster164_id_display"]').attr('readonly','true');
			$('#Invoice_editView_fieldName_cf_nrl_plantmaster164_id_create').parent().remove();
			$('#Invoice_editView_fieldName_cf_nrl_plantmaster164_id_select').parent().remove();
			$('#Invoice_editView_fieldName_arocrm_purchaseorder_select').parent().remove();
			$('select[name="cf_5197"]').select2('data', { id: response.discountallow, text: response.discountallow});
			$('select[name="cf_5209"]').select2('data', { id: response.discountapply, text: response.discountapply});
			
            $("table#lineItemTab > tbody").empty();
						$("table#lineItemTab > tbody").html(response.html);
						$('#directMode_lineItemTab').val('1');
						$("[name=totalProductCount]").val(response.totalcount);
						$("#netTotal").html(response.subtotal);
						$("#preTaxTotal").html(response.subtotal);
						$("#grandTotal").html(response.alltotal);
						$('input[name="customerno"]').val(response.custno);
						$('input[name="customerno"]').attr('readonly',true);
						$('input[name="account_id"]').val(response.customer);
						$('input[name="account_id_display"]').val(response.accname);
						$('input[name="account_id_display"]').attr('readonly',true);
						$('#Invoice_editView_fieldName_account_id_select').parent().remove();
						$('#Invoice_editView_fieldName_account_id_create').parent().remove();
						$('input[name="contact_id"]').val(response.contactid);
						$('input[name="contact_id_display"]').val(response.contactname);
						$('input[name="contact_id_display"]').attr('readonly',true);
						$('#Invoice_editView_fieldName_contact_id_select').parent().remove();
						$('#Invoice_editView_fieldName_contact_id_create').parent().remove();
						$('select[name="productcategory"]').select2('data', { id: response.category, text: response.category});
						$('select[name="productcategory"]').select2().select2('readonly',true);
						$('#group_tax_percentage1').val(response.tax1);
						$('#group_tax_percentage2').val(response.tax2);
						$('#group_tax_percentage3').val(response.tax3);
						//$('#tax_final').text(response.totaltaxval);
						$("#adjustment").val(response.adjustval);
						$("input[name=adjustmentType][value='"+response.adjusticon+"']").attr("checked",true);
						$('select[name="cf_5197"]').select2('data', { id: response.discountallow, text: response.discountallow});
						//$('[name="cf_5197"]').select2().select2('readonly',true);
						$("#addProduct").attr("disabled","true");
						$("#addService").attr("disabled","true");
						$(".dropdown-toggle").attr("disabled","true");
						var adjusticon = response.adjusticon;
						var adjustval = response.adjustval;
						var soadvance = response.advance;
						var sodebit = response.debit;
						var socredit = response.credit;
						var soschemediscount = response.schemediscount;
						var tax1 = response.tax1;
				var tax2 = response.tax2;
				var tax3 = response.tax3;
				var accountid = $('input[name="account_id"]').val();
				var itemno = response.totalrow;
				var totalqty = 0;
				for(i=1;i<=itemno;i++)
				{
					var qty = $('#qty'+i).val();
					totalqty = parseInt(totalqty) + parseInt(qty);
				}
				var postingdate = $('input[name="cf_4627"]').val();
				var nettotalprice = $('#netTotal').text();
				var category = $('select[name="productcategory"]').val();
				var branch = $('[name="cf_nrl_plantmaster164_id"]').val();
				var discountallow = $('[name="cf_5197"]').val();
				var advdiscountallow = $('[name="cf_5209"]').val();
				if(branch == '')
				{
					alert("Please Select Branch First");
				}
				if(category == '')
				{
					alert("Please Select Category First");
				}
				if(postingdate == '')
				{
					alert("Please Select Posting Date First");
				}
				if(accountid == '')
				{
					alert("Please Select Customer First");
				}
				if(discountallow == '')
				{
					alert("Please Select Discount Allow or Not");
				}
				if(advdiscountallow == '')
				{
					alert("Please Select Cash Discount on Advance Payment Allow or Not");
				}
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {branch: branch, category: category, totalqty: totalqty, nettotalprice: nettotalprice, date: postingdate, accountid: accountid, discountallow: discountallow, advdiscountallow: advdiscountallow, action: 'checkTotalDiscount'},
				dataType: 'json',
				success:function(response)
				{
					if(response.discountapply == 'Yes')
					{
						$('#dynamicDiscount').addClass('hide');
						$('#staticDiscount').removeClass('hide');
					
					if(response.totalamount == null)
					{
						response.totalamount = nettotalprice;
					}
					if(response.totaldeductamount == null)
					{
						response.totaldeductamount = 0.00;
					}
					$('#preTaxTotal').text(response.totalamount);
					var taxtotal = $('#tax_final').text();
					var grandtotal = (parseFloat(response.totalamount) + parseFloat(taxtotal)).toFixed(2);
					$('#grandTotal').text(grandtotal);
					$('#overallDiscount').text("("+response.totaldeductamount+")");
					$('#discountTotal_final').text(response.totaldeductamount);
					if(response.advpercent != null || response.paypercent != null || response.paypercentcash !=null || response.pay7percent != null || response.pay15percent != null || response.pay30percent != null)
					{
						$('.popupPaymentTable').show();
						$('#overalladvancepercent').val(response.advpercent);
						$('#overalladvancepercentval').val(response.advpercentamount);
						$('#overallsamedaypercent').val(response.paypercent);
						$('#overallsamedaypercentval').val(response.paypercentamount);
						$('#samedayInvoiceId').val(response.involdsame);
						$('#overallsamedaycashpercent').val(response.paypercentcash);
						$('#overallsamedaycashpercentval').val(response.paypercentcashamount);
						$('#samedaycashInvoiceId').val(response.involdsamecash);
						$('#overall7dayspercent').val(response.pay7percent);
						$('#overall7dayspercentval').val(response.pay7percentamount);
						$('#within7daysInvoiceId').val(response.invold7);
						$('#overall15dayspercent').val(response.pay15percent);
						$('#overall15dayspercentval').val(response.pay15percentamount);
						$('#within15daysInvoiceId').val(response.invold15);
						$('#overall30dayspercent').val(response.pay30percent);
						$('#overall30dayspercentval').val(response.pay30percentamount);
						$('#within30daysInvoiceId').val(response.invold30);
					}
					else
					{
						$('.popupPaymentTable').hide();
						$('#overalladvancepercent').val(0.00);
						$('#overalladvancepercentval').val(0.00);
						$('#overallsamedaypercent').val(0.00);
						$('#overallsamedaypercentval').val(0.00);
						$('#samedayInvoiceId').val('');
						$('#overallsamedaycashpercent').val(0.00);
						$('#overallsamedaycashpercentval').val(0.00);
						$('#samedaycashInvoiceId').val('');
						$('#overall7dayspercent').val(0.00);
						$('#overall7dayspercentval').val(0.00);
						$('#within7daysInvoiceId').val('');
						$('#overall15dayspercent').val(0.00);
						$('#overall15dayspercentval').val(0.00);
						$('#within15daysInvoiceId').val('');
						$('#overall30dayspercent').val(0.00);
						$('#overall30dayspercentval').val(0.00);
						$('#within30daysInvoiceId').val('');
					}
					if(response.monthlydiscountstatus == 'Active')
					{
						$('.popupMonthlyTable').show();
						$('#overallmonthlycashamount').val(response.monthunitamount);
						$('#totaloverallmonthlycashamount').val(response.monthtotaldeduct);
						$('#overallmonthlycashpercent').val(response.monthcashpercent);
						$('#overallmonthlycashpercentval').val(response.monthcashpercentval);
						$('#overallmonthlytargetpercent').val(response.monthtargetpercent);
						$('#overallmonthlytargetpercentval').val(response.monthtargetpercentval);
						$('#overallmonthlyretailerpercent').val(response.monthretailerpercent);
						$('#overallmonthlyretailerpercentval').val(response.monthretailerpercentval);
					}
					else
					{
						$('.popupMonthlyTable').hide();
						$('#overallmonthlycashamount').val(0.00);
						$('#totaloverallmonthlycashamount').val(0.00);
						$('#overallmonthlycashpercent').val(0.00);
						$('#overallmonthlycashpercentval').val(0.00);
						$('#overallmonthlytargetpercent').val(0.00);
						$('#overallmonthlytargetpercentval').val(0.00);
						$('#overallmonthlyretailerpercent').val(0.00);
						$('#overallmonthlyretailerpercentval').val(0.00);
					}
					if(response.quarterlydiscountstatus == 'Active')
					{
						$('.popupQuarterlyTable').show();
						$('#overallquarterlycashamount').val(response.quarterunitamount);
						$('#totaloverallquarterlycashamount').val(response.quartertotaldeduct);
						$('#overallquarterlycashpercent').val(response.quartercashpercent);
						$('#overallquarterlycashpercentval').val(response.quartercashpercentval);
						$('#overallquarterlytargetpercent').val(response.quartertargetpercent);
						$('#overallquarterlytargetpercentval').val(response.quartertargetpercentval);
						$('#overallquarterlyretailerpercent').val(response.quarterretailerpercent);
						$('#overallquarterlyretailerpercentval').val(response.quarterretailerpercentval);
					}
					else
					{
						$('.popupQuarterlyTable').hide();
						$('#overallquarterlycashamount').val(0.00);
						$('#totaloverallquarterlycashamount').val(0.00);
						$('#overallquarterlycashpercent').val(0.00);
						$('#overallquarterlycashpercentval').val(0.00);
						$('#overallquarterlytargetpercent').val(0.00);
						$('#overallquarterlytargetpercentval').val(0.00);
						$('#overallquarterlyretailerpercent').val(0.00);
						$('#overallquarterlyretailerpercentval').val(0.00);
					}
					if(response.halfyearlydiscountstatus == 'Active')
					{
						$('.popupHalfYearlyTable').show();
						$('#overallhalfyearlycashamount').val(response.halfyearunitamount);
						$('#totaloverallhalfyearlycashamount').val(response.halfyeartotaldeduct);
						$('#overallhalfyearlycashpercent').val(response.halfyearcashpercent);
						$('#overallhalfyearlycashpercentval').val(response.halfyearcashpercentval);
						$('#overallhalfyearlytargetpercent').val(response.halfyeartargetpercent);
						$('#overallhalfyearlytargetpercentval').val(response.halfyeartargetpercentval);
						$('#overallhalfyearlyretailerpercent').val(response.halfyearretailerpercent);
						$('#overallhalfyearlyretailerpercentval').val(response.halfyearretailerpercentval);
					}
					else
					{
						$('.popupHalfYearlyTable').hide();
						$('#overallhalfyearlycashamount').val(0.00);
						$('#totaloverallhalfyearlycashamount').val(0.00);
						$('#overallhalfyearlycashpercent').val(0.00);
						$('#overallhalfyearlycashpercentval').val(0.00);
						$('#overallhalfyearlytargetpercent').val(0.00);
						$('#overallhalfyearlytargetpercentval').val(0.00);
						$('#overallhalfyearlyretailerpercent').val(0.00);
						$('#overallhalfyearlyretailerpercentval').val(0.00);
					}
					if(response.annuallydiscountstatus == 'Active')
					{
						$('.popupAnnuallyTable').show();
						$('#overallannuallycashamount').val(response.annualunitamount);
						$('#totaloverallannuallycashamount').val(response.annnualtotaldeduct);
						$('#overallannuallycashpercent').val(response.annualcashpercent);
						$('#overallannuallycashpercentval').val(response.annualcashpercentval);
						$('#overallannuallytargetpercent').val(response.annualtargetpercent);
						$('#overallannuallytargetpercentval').val(response.annualtargetpercentval);
						$('#overallannuallyretailerpercent').val(response.annualretailerpercent);
						$('#overallannuallyretailerpercentval').val(response.annualretailerpercentval);
					}
					else
					{
						$('.popupAnnuallyTable').hide();
						$('#overallannuallycashamount').val(0.00);
						$('#totaloverallannuallycashamount').val(0.00);
						$('#overallannuallycashpercent').val(0.00);
						$('#overallannuallycashpercentval').val(0.00);
						$('#overallannuallytargetpercent').val(0.00);
						$('#overallannuallytargetpercentval').val(0.00);
						$('#overallannuallyretailerpercent').val(0.00);
						$('#overallannuallyretailerpercentval').val(0.00);
					}
					$('.popoverButton').click();
					$('#overallmonthlycashamount').prop('readonly','true');
					$('#totaloverallmonthlycashamount').prop('readonly','true');
					$('#overallmonthlycashpercent').prop('readonly','true');
					$('#overallmonthlycashpercentval').prop('readonly','true');
					$('#overallmonthlytargetpercent').prop('readonly','true');
					$('#overallmonthlytargetpercentval').prop('readonly','true');
					$('#overallmonthlyretailerpercent').prop('readonly','true');
					$('#overallmonthlyretailerpercentval').prop('readonly','true');
					$('#overallquarterlycashamount').prop('readonly','true');
					$('#totaloverallquarterlycashamount').prop('readonly','true');
					$('#overallquarterlycashpercent').prop('readonly','true');
					$('#overallquarterlycashpercentval').prop('readonly','true');
					$('#overallquarterlytargetpercent').prop('readonly','true');
					$('#overallquarterlytargetpercentval').prop('readonly','true');
					$('#overallquarterlyretailerpercent').prop('readonly','true');
					$('#overallquarterlyretailerpercentval').prop('readonly','true');
					$('#overallhalfyearlycashamount').prop('readonly','true');
					$('#totaloverallhalfyearlycashamount').prop('readonly','true');
					$('#overallhalfyearlycashpercent').prop('readonly','true');
					$('#overallhalfyearlycashpercentval').prop('readonly','true');
					$('#overallhalfyearlytargetpercent').prop('readonly','true');
					$('#overallhalfyearlytargetpercentval').prop('readonly','true');
					$('#overallhalfyearlyretailerpercent').prop('readonly','true');
					$('#overallhalfyearlyretailerpercentval').prop('readonly','true');
					$('#overallannuallycashamount').prop('readonly','true');
					$('#totaloverallannuallycashamount').prop('readonly','true');
					$('#overallannuallycashpercent').prop('readonly','true');
					$('#overallannuallycashpercentval').prop('readonly','true');
					$('#overallannuallytargetpercent').prop('readonly','true');
					$('#overallannuallyretailerpercent').prop('readonly','true');
					$('#overallannuallyretailerpercentval').prop('readonly','true');
					$('#overalladvancepercent').prop('readonly','true');
					$('#overalladvancepercentval').prop('readonly','true');
					$('#overallsamedaypercent').prop('readonly','true');
					$('#overallsamedaypercentval').prop('readonly','true');
					$('#overallsamedaycashpercent').prop('readonly','true');
					$('#overallsamedaycashpercentval').prop('readonly','true');
					$('#overall7dayspercent').prop('readonly','true');
					$('#overall7dayspercentval').prop('readonly','true');
					$('#overall15dayspercent').prop('readonly','true');
					$('#overall15dayspercentval').prop('readonly','true');
					$('#overall30dayspercent').prop('readonly','true');
					$('#overall30dayspercentval').prop('readonly','true');
				}
				else
				{
					$('#dynamicDiscount').removeClass('hide');
					$('#staticDiscount').addClass('hide');
					if($('#staticDiscount').hasClass('hide') == true){
					$('.lineItemPopupModalFooter').html('<center><button class="btn btn-success popoverButton" type="button"><strong>'+app.vtranslate('JS_LBL_SAVE')+'</strong></button><a href="#" class="popoverCancel" type="reset">'+app.vtranslate('JS_LBL_CANCEL')+'</a></center>');
					}
				}

				}
				});
				
						var len = response.paymentlength;
						if(len>0)
						{
							var paymentname = new Array();
							var paymentval = new Array();
							var paymentid = new Array();
							paymentname = response.paymentname;
							paymentval = response.paymentval;
							paymentid = response.paymentid;
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong>&nbsp;&nbsp;<select name="advpay[]" class="optselect" multiple style="width:200px;">';
							for(var i = 0; i<len; i++)
							{
							htmls = htmls + '<option value="'+paymentid[i]+'">'+paymentname[i]+' - '+paymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
							$(".optselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
						}
						/*var debitlen = response.debitpaymentlength;
						if(debitlen>0)
						{
							var debitpaymentname = new Array();
							var debitpaymentval = new Array();
							var debitpaymentid = new Array();
							debitpaymentname = response.debitpaymentname;
							debitpaymentval = response.debitpaymentval;
							debitpaymentid = response.debitpaymentid;
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong>&nbsp;&nbsp;<select name="advdebitpay[]" class="optdebitselect" multiple style="width:200px;">';
							for(var i = 0; i<debitlen; i++)
							{
							htmls = htmls + '<option value="'+debitpaymentid[i]+'">'+debitpaymentname[i]+' - '+debitpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optdebitselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}*/
						var creditlen = response.creditpaymentlength;
						if(creditlen>0)
						{
							var creditpaymentname = new Array();
							var creditpaymentval = new Array();
							var creditpaymentid = new Array();
							creditpaymentname = response.creditpaymentname;
							creditpaymentval = response.creditpaymentval;
							creditpaymentid = response.creditpaymentid;
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong>&nbsp;&nbsp;<select name="advcreditpay[]" class="optcreditselect" multiple style="width:200px;">';
							for(var i = 0; i<creditlen; i++)
							{
							htmls = htmls + '<option value="'+creditpaymentid[i]+'">'+creditpaymentname[i]+' - '+creditpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optcreditselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}
						$('#advancePayment').val(response.advance);
						$('#ap').val(response.advance);
						$('#debit').val(response.debit);
						$('#dn').val(response.debit);
						$('#credit').val(response.credit);
						$('#cn').val(response.credit);
						$('#schemediscount').val(response.schemediscount);
						}
					}
					else
					{
						app.helper.showAlertNotification({'message': response.message});
					}
				}
				});

			}
			if(referenceModule=='PlantMaster' && sourcemodule=='Invoice'){
				var yr = $('select[name="cf_4623"]').val();
	var month = $('select[name="cf_4625"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4627"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4627"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4627"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_4627"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			
			if(referenceModule=='HelpDesk' && sourcemodule=='SalesOrder'){
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {id: id, action: 'getClaimforSO'},
				dataType: 'json',
				success:function(response)
				{
					$('#hdnProductId1').val(response.productid);
					$('#productName1').val(response.productname);
					$('#productcode1').val(response.productcode);
					$('#itemunit1').val(response.productunit);
					$('#productName1').attr('readonly',true);
					$('#productcode1').attr('readonly',true);
					$('#itemunit1').attr('readonly',true);
					$('#qty1').attr('readonly',true);
					$('.clearLineItem').hide();
					$('#addProduct').hide();
					$('.vicon-products').parent().remove();
					$('select[name="productcategory"]').select2('data', { id: response.productcategory, text: response.productcategory});
					$('select[name="productcategory"]').select2().select2('readonly',true);
					$('input[name="account_id"]').val(response.customerid);
					$('input[name="account_id_display"]').val(response.custname);
					$('input[name="account_id_display"]').attr('readonly',true);
					$('#SalesOrder_editView_fieldName_account_id_select').parent().remove();
					$('#SalesOrder_editView_fieldName_account_id_select').parent().remove();
					$('#SalesOrder_editView_fieldName_account_id_create').parent().remove();
					$('input[name="contact_id"]').val(response.contactid);
					$('input[name="contact_id_display"]').val(response.contactname);
					$('input[name="cf_nrl_plantmaster580_id"]').val(response.plantid);
					$('input[name="cf_nrl_plantmaster580_id_display"]').val(response.plantname);
					$('input[name="cf_nrl_plantmaster580_id_display"]').attr('readonly',true);
					$('#SalesOrder_editView_fieldName_cf_nrl_plantmaster580_id_select').parent().remove();
					$('#SalesOrder_editView_fieldName_cf_nrl_plantmaster580_id_create').parent().remove();
				}
				});
			}
			if(referenceModule=='GoodsReceipt' && sourcemodule=='Invoice'){
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {id: id, action: 'getProductDetailsfromGR'},
				dataType: 'json',
				success:function(response)
				{
						$("table#lineItemTab > tbody").empty();
						$("table#lineItemTab > tbody").html(response.html);
						$('#directMode_lineItemTab').val('1');
						$("[name=totalProductCount]").val(response.totalcount);
						$("#netTotal").html(response.subtotal);
						$("#preTaxTotal").text(response.subtotal);
						$("#grandTotal").text(response.grandtotal);
						$('input[name="cf_nrl_vendors752_id"]').val(response.vendor);
						$('input[name="cf_nrl_vendors752_id_display"]').val(response.vendorname);
						$('#Invoice_editView_fieldName_cf_nrl_vendors752_id_select').parent().remove();
						$('#Invoice_editView_fieldName_cf_nrl_vendors752_id_create').parent().remove();
						$('input[name="cf_nrl_vendors752_id_display"]').attr('readonly', 'true');
						$('[name="region_id"]').select2('data', { id: response.taxregion, text: response.taxregion});
						$('[name="region_id"]').select2().select2('readonly',true);
						$('[name="currency_id"]').select2('data', { id: response.currency, text: response.currency});
						$('[name="currency_id"]').select2().select2('readonly',true);
						$('[name="taxtype"]').select2('data', { id: response.taxtype, text: response.taxtype});
						$('[name="taxtype"]').select2().select2('readonly',true);
						$("#adjustment").val(response.adjustval);
						$("input[name=adjustmentType][value='"+response.adjusticon+"']").attr("checked",true);
						$('[name="bill_street"]').val(response.vendorstreet);
						$('[name="bill_street"]').attr('readonly',true);
						$('[name="ship_street"]').val(response.vendorstreet);
						$('[name="ship_street"]').attr('readonly',true);
						$('[name="bill_pobox"]').val(response.vendorpobox);
						$('[name="bill_pobox"]').attr('readonly',true);
						$('[name="ship_pobox"]').val(response.vendorpobox);
						$('[name="ship_pobox"]').attr('readonly',true);
						$('[name="bill_city"]').val(response.vendorcity);
						$('[name="bill_city"]').attr('readonly',true);
						$('[name="ship_city"]').val(response.vendorcity);
						$('[name="ship_city"]').attr('readonly',true);
						$('[name="bill_state"]').val(response.vendorstate);
						$('[name="bill_state"]').attr('readonly',true);
						$('[name="ship_state"]').val(response.vendorstate);
						$('[name="ship_state"]').attr('readonly',true);
						$('[name="bill_code"]').val(response.vendorpostalcode);
						$('[name="bill_code"]').attr('readonly',true);
						$('[name="ship_code"]').val(response.vendorpostalcode);
						$('[name="ship_code"]').attr('readonly',true);
						$('[name="bill_country"]').val(response.vendorcountry);
						$('[name="bill_country"]').attr('readonly',true);
						$('[name="ship_country"]').val(response.vendorcountry);
						$('[name="ship_country"]').attr('readonly',true);
						$('[name="copyAddressFromRight"]').attr('disabled',true);
						$('[name="copyAddressFromLeft"]').attr('disabled',true);
						$('input[name="arocrm_purchaseorder"]').val(response.po);
						$('input[name="arocrm_purchaseorder_display"]').val(response.poname);
						$('#Invoice_editView_fieldName_arocrm_purchaseorder_select').parent().remove();
						$('input[name="arocrm_purchaseorder_display"]').attr('readonly', 'true');
						$('select[name="productcategory"]').select2('data', { id: response.category, text: response.category});
						$('select[name="productcategory"]').select2().select2('readonly',true);
						$('input[name="cf_nrl_plantmaster164_id"]').val(response.plant);
						$('input[name="cf_nrl_plantmaster164_id_display"]').val(response.plantname);
						$('input[name="cf_nrl_plantmaster164_id_display"]').attr('readonly', 'true');
						$('#Invoice_editView_fieldName_cf_nrl_plantmaster164_id_select').parent().remove();
						$('#Invoice_editView_fieldName_cf_nrl_plantmaster164_id_create').parent().remove();
						$("#addProduct").attr("disabled","true");
						$("#addService").attr("disabled","true");
						$('.dropdown-toggle').attr("disabled","true");
						
						var adjusticon = response.adjusticon;
						var adjustval = response.adjustval;
						var poadvance = response.advance;
						var podebit = response.debit;
						var pocredit = response.credit;
						var tax1 = response.tax1;
						var tax2 = response.tax2;
						var tax3 = response.tax3;
						
				/*var accountid = $('input[name="account_id"]').val();
				var itemno = response.totalrow;
				var totalqty = 0;
				for(i=1;i<=itemno;i++)
				{
					var qty = $('#qty'+i).val();
					totalqty = parseInt(totalqty) + parseInt(qty);
				}
				var postingdate = $('input[name="cf_4627"]').val();
				var nettotalprice = $('#netTotal').text();
				var category = $('select[name="productcategory"]').val();
				var branch = $('[name="cf_nrl_plantmaster164_id"]').val();
				var discountallow = $('[name="cf_5197"]').val();
				if(branch == '')
				{
					alert("Please Select Branch First");
				}
				if(category == '')
				{
					alert("Please Select Category First");
				}
				if(postingdate == '')
				{
					alert("Please Select Posting Date First");
				}
				if(accountid == '')
				{
					alert("Please Select Customer First");
				}
				if(discountallow == '')
				{
					alert("Please Select Discount Allow or Not");
				}
				$.ajax(
				{
				type:"post",
				url: "shirshaAjax.php",
				data: {branch: branch, category: category, totalqty: totalqty, nettotalprice: nettotalprice, date: postingdate, accountid: accountid, discountallow: discountallow, action: 'checkTotalDiscountforInvoice'},
				dataType: 'json',
				success:function(response)
				{
					if(response.totalamount == null)
					{
						response.totalamount = nettotalprice;
					}
					if(response.totaldeductamount == null)
					{
						response.totaldeductamount = 0.00;
					}
					$('#preTaxTotal').text(response.totalamount);
					var taxtotal = $('#tax_final').text();
					var grandtotal = parseFloat(response.totalamount) + parseFloat(taxtotal);
					$('#grandTotal').text(grandtotal);
					$('#overallDiscount').text("("+response.totaldeductamount+")");
					$('#discountTotal_final').text(response.totaldeductamount);
					if(response.advpercent != null || response.paypercent != null || response.pay7percent != null || response.pay15percent != null || response.pay30percent != null)
					{
						$('.popupPaymentTable').show();
						$('#overalladvancepercent').val(response.advpercent);
						$('#overalladvancepercentval').val(response.advpercentamount);
						$('#overallsamedaypercent').val(response.paypercent);
						$('#overallsamedaypercentval').val(response.paypercentamount);
						$('#overall7dayspercent').val(response.pay7percent);
						$('#overall7dayspercentval').val(response.pay7percentamount);
						$('#overall15dayspercent').val(response.pay15percent);
						$('#overall15dayspercentval').val(response.pay15percentamount);
						$('#overall30dayspercent').val(response.pay30percent);
						$('#overall30dayspercentval').val(response.pay30percentamount);
					}
					else
					{
						$('.popupPaymentTable').hide();
						$('#overalladvancepercent').val(0.00);
						$('#overalladvancepercentval').val(0.00);
						$('#overallsamedaypercent').val(0.00);
						$('#overallsamedaypercentval').val(0.00);
						$('#overall7dayspercent').val(0.00);
						$('#overall7dayspercentval').val(0.00);
						$('#overall15dayspercent').val(0.00);
						$('#overall15dayspercentval').val(0.00);
						$('#overall30dayspercent').val(0.00);
						$('#overall30dayspercentval').val(0.00);
					}
					if(response.monthlydiscountstatus == 'Active')
					{
						$('.popupMonthlyTable').show();
						$('#overallmonthlycashamount').val(response.monthunitamount);
						$('#totaloverallmonthlycashamount').val(response.monthtotaldeduct);
						$('#overallmonthlycashpercent').val(response.monthcashpercent);
						$('#overallmonthlycashpercentval').val(response.monthcashpercentval);
						$('#overallmonthlytargetpercent').val(response.monthtargetpercent);
						$('#overallmonthlytargetpercentval').val(response.monthtargetpercentval);
						$('#overallmonthlyretailerpercent').val(response.monthretailerpercent);
						$('#overallmonthlyretailerpercentval').val(response.monthretailerpercentval);
					}
					else
					{
						$('.popupMonthlyTable').hide();
						$('#overallmonthlycashamount').val(0.00);
						$('#totaloverallmonthlycashamount').val(0.00);
						$('#overallmonthlycashpercent').val(0.00);
						$('#overallmonthlycashpercentval').val(0.00);
						$('#overallmonthlytargetpercent').val(0.00);
						$('#overallmonthlytargetpercentval').val(0.00);
						$('#overallmonthlyretailerpercent').val(0.00);
						$('#overallmonthlyretailerpercentval').val(0.00);
					}
					if(response.quarterlydiscountstatus == 'Active')
					{
						$('.popupQuarterlyTable').show();
						$('#overallquarterlycashamount').val(response.quarterunitamount);
						$('#totaloverallquarterlycashamount').val(response.quartertotaldeduct);
						$('#overallquarterlycashpercent').val(response.quartercashpercent);
						$('#overallquarterlycashpercentval').val(response.quartercashpercentval);
						$('#overallquarterlytargetpercent').val(response.quartertargetpercent);
						$('#overallquarterlytargetpercentval').val(response.quartertargetpercentval);
						$('#overallquarterlyretailerpercent').val(response.quarterretailerpercent);
						$('#overallquarterlyretailerpercentval').val(response.quarterretailerpercentval);
					}
					else
					{
						$('.popupQuarterlyTable').hide();
						$('#overallquarterlycashamount').val(0.00);
						$('#totaloverallquarterlycashamount').val(0.00);
						$('#overallquarterlycashpercent').val(0.00);
						$('#overallquarterlycashpercentval').val(0.00);
						$('#overallquarterlytargetpercent').val(0.00);
						$('#overallquarterlytargetpercentval').val(0.00);
						$('#overallquarterlyretailerpercent').val(0.00);
						$('#overallquarterlyretailerpercentval').val(0.00);
					}
					if(response.halfyearlydiscountstatus == 'Active')
					{
						$('.popupHalfYearlyTable').show();
						$('#overallhalfyearlycashamount').val(response.halfyearunitamount);
						$('#totaloverallhalfyearlycashamount').val(response.halfyeartotaldeduct);
						$('#overallhalfyearlycashpercent').val(response.halfyearcashpercent);
						$('#overallhalfyearlycashpercentval').val(response.halfyearcashpercentval);
						$('#overallhalfyearlytargetpercent').val(response.halfyeartargetpercent);
						$('#overallhalfyearlytargetpercentval').val(response.halfyeartargetpercentval);
						$('#overallhalfyearlyretailerpercent').val(response.halfyearretailerpercent);
						$('#overallhalfyearlyretailerpercentval').val(response.halfyearretailerpercentval);
					}
					else
					{
						$('.popupHalfYearlyTable').hide();
						$('#overallhalfyearlycashamount').val(0.00);
						$('#totaloverallhalfyearlycashamount').val(0.00);
						$('#overallhalfyearlycashpercent').val(0.00);
						$('#overallhalfyearlycashpercentval').val(0.00);
						$('#overallhalfyearlytargetpercent').val(0.00);
						$('#overallhalfyearlytargetpercentval').val(0.00);
						$('#overallhalfyearlyretailerpercent').val(0.00);
						$('#overallhalfyearlyretailerpercentval').val(0.00);
					}
					if(response.annuallydiscountstatus == 'Active')
					{
						$('.popupAnnuallyTable').show();
						$('#overallannuallycashamount').val(response.annualunitamount);
						$('#totaloverallannuallycashamount').val(response.annnualtotaldeduct);
						$('#overallannuallycashpercent').val(response.annualcashpercent);
						$('#overallannuallycashpercentval').val(response.annualcashpercentval);
						$('#overallannuallytargetpercent').val(response.annualtargetpercent);
						$('#overallannuallytargetpercentval').val(response.annualtargetpercentval);
						$('#overallannuallyretailerpercent').val(response.annualretailerpercent);
						$('#overallannuallyretailerpercentval').val(response.annualretailerpercentval);
					}
					else
					{
						$('.popupAnnuallyTable').hide();
						$('#overallannuallycashamount').val(0.00);
						$('#totaloverallannuallycashamount').val(0.00);
						$('#overallannuallycashpercent').val(0.00);
						$('#overallannuallycashpercentval').val(0.00);
						$('#overallannuallytargetpercent').val(0.00);
						$('#overallannuallytargetpercentval').val(0.00);
						$('#overallannuallyretailerpercent').val(0.00);
						$('#overallannuallyretailerpercentval').val(0.00);
					}
					//$('.popoverButton').css('display','none');
					$('#overallmonthlycashamount').prop('readonly','true');
					$('#totaloverallmonthlycashamount').prop('readonly','true');
					$('#overallmonthlycashpercent').prop('readonly','true');
					$('#overallmonthlycashpercentval').prop('readonly','true');
					$('#overallmonthlytargetpercent').prop('readonly','true');
					$('#overallmonthlytargetpercentval').prop('readonly','true');
					$('#overallmonthlyretailerpercent').prop('readonly','true');
					$('#overallmonthlyretailerpercentval').prop('readonly','true');
					$('#overallquarterlycashamount').prop('readonly','true');
					$('#totaloverallquarterlycashamount').prop('readonly','true');
					$('#overallquarterlycashpercent').prop('readonly','true');
					$('#overallquarterlycashpercentval').prop('readonly','true');
					$('#overallquarterlytargetpercent').prop('readonly','true');
					$('#overallquarterlytargetpercentval').prop('readonly','true');
					$('#overallquarterlyretailerpercent').prop('readonly','true');
					$('#overallquarterlyretailerpercentval').prop('readonly','true');
					$('#overallhalfyearlycashamount').prop('readonly','true');
					$('#totaloverallhalfyearlycashamount').prop('readonly','true');
					$('#overallhalfyearlycashpercent').prop('readonly','true');
					$('#overallhalfyearlycashpercentval').prop('readonly','true');
					$('#overallhalfyearlytargetpercent').prop('readonly','true');
					$('#overallhalfyearlytargetpercentval').prop('readonly','true');
					$('#overallhalfyearlyretailerpercent').prop('readonly','true');
					$('#overallhalfyearlyretailerpercentval').prop('readonly','true');
					$('#overallannuallycashamount').prop('readonly','true');
					$('#totaloverallannuallycashamount').prop('readonly','true');
					$('#overallannuallycashpercent').prop('readonly','true');
					$('#overallannuallycashpercentval').prop('readonly','true');
					$('#overallannuallytargetpercent').prop('readonly','true');
					$('#overallannuallyretailerpercent').prop('readonly','true');
					$('#overallannuallyretailerpercentval').prop('readonly','true');
					$('#overalladvancepercent').prop('readonly','true');
					$('#overalladvancepercentval').prop('readonly','true');
					$('#overallsamedaypercent').prop('readonly','true');
					$('#overallsamedaypercentval').prop('readonly','true');
					$('#overall7dayspercent').prop('readonly','true');
					$('#overall7dayspercentval').prop('readonly','true');
					$('#overall15dayspercent').prop('readonly','true');
					$('#overall15dayspercentval').prop('readonly','true');
					$('#overall30dayspercent').prop('readonly','true');
					$('#overall30dayspercentval').prop('readonly','true');

				}
				});*/
				
						var len = response.paymentlength;
						if(len>0)
						{
							var paymentname = new Array();
							var paymentval = new Array();
							var paymentid = new Array();
							paymentname = response.paymentname;
							paymentval = response.paymentval;
							paymentid = response.paymentid;
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong>&nbsp;&nbsp;<select name="advpay[]" class="optselect" multiple style="width:200px;">';
							for(var i = 0; i<len; i++)
							{
							htmls = htmls + '<option value="'+paymentid[i]+'">'+paymentname[i]+' - '+paymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
							$(".optselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
						}
						/*var debitlen = response.debitpaymentlength;
						if(debitlen>0)
						{
							var debitpaymentname = new Array();
							var debitpaymentval = new Array();
							var debitpaymentid = new Array();
							debitpaymentname = response.debitpaymentname;
							debitpaymentval = response.debitpaymentval;
							debitpaymentid = response.debitpaymentid;
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong>&nbsp;&nbsp;<select name="advdebitpay[]" class="optdebitselect" multiple style="width:200px;">';
							for(var i = 0; i<debitlen; i++)
							{
							htmls = htmls + '<option value="'+debitpaymentid[i]+'">'+debitpaymentname[i]+' - '+debitpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optdebitselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}*/
						var creditlen = response.creditpaymentlength;
						if(creditlen>0)
						{
							var creditpaymentname = new Array();
							var creditpaymentval = new Array();
							var creditpaymentid = new Array();
							creditpaymentname = response.creditpaymentname;
							creditpaymentval = response.creditpaymentval;
							creditpaymentid = response.creditpaymentid;
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong>&nbsp;&nbsp;<select name="advcreditpay[]" class="optcreditselect" multiple style="width:200px;">';
							for(var i = 0; i<creditlen; i++)
							{
							htmls = htmls + '<option value="'+creditpaymentid[i]+'">'+creditpaymentname[i]+' - '+creditpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optcreditselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}
						$('#advancePayment').val(response.advance);
						$('#ap').val(response.advance);
						$('#debit').val(response.debit);
						$('#dn').val(response.debit);
						$('#credit').val(response.credit);
						$('#cn').val(response.credit);
var t1val = parseFloat(tax1) * parseFloat(response.totalamount);
					var tax1val = (parseFloat(t1val)/parseFloat(100)).toFixed(2); 
					if(tax1val == 'NaN')
					{
						tax1val = 0.00;
					}
					var t2val = parseFloat(tax2) * parseFloat(response.totalamount);
					var tax2val = (parseFloat(t2val)/parseFloat(100)).toFixed(2);
					if(tax2val == 'NaN')
					{
						tax2val = 0.00;
					}
					var t3val = parseFloat(tax3) * parseFloat(response.totalamount);
					var tax3val = (parseFloat(t3val)/parseFloat(100)).toFixed(2);
					if(tax3val == 'NaN')
					{
						tax3val = 0.00;
					}
					var totaltaxval = parseFloat(tax1val) + parseFloat(tax2val) + parseFloat(tax3val);
					$('#tax_final').text(totaltaxval);
					$('#group_tax_amount1').val(tax1val);
					$('#group_tax_amount2').val(tax2val);
					$('#group_tax_amount3').val(tax3val);
				
					var taxtotal = $('#tax_final').text();
					if(adjusticon == '+')
					{
						var grandtotal = ((((((parseFloat(response.totalamount) + parseFloat(taxtotal)) - parseFloat(poadvance)) + parseFloat(podebit)) - parseFloat(pocredit))) + parseFloat(adjustval)).toFixed(2);
					}
					else
					{
						var grandtotal = ((((((parseFloat(response.totalamount) + parseFloat(taxtotal)) - parseFloat(poadvance)) + parseFloat(podebit)) - parseFloat(pocredit))) - parseFloat(adjustval)).toFixed(2);
					}
					$('#grandTotal').text(grandtotal);
				}
				});

			}
		
      if(referenceModule=='SchemeMaster' && sourcemodule=='SchemeMaster'){
        $.ajax(
        {
        type:"post",
        url: "arocrmAjax.php",
        data: {id: id, action: 'getSchemeMasterDetailsRev'},
        dataType: 'json',
        success:function(response)
        {
          console.log(response);

          $('[name="name"]').val(response.name);
          $('[name="name"]').prop('readonly',true);
          $('[name="cf_2066"]').val(response.startdate);
          $('[name="cf_2068"]').val(response.enddate);
          $('[name="cf_2066"]').prop('readonly',true);
          $('[name="cf_2068"]').prop('readonly',true);
          $('[name="cf_2066"]').datepicker("remove");
          $('[name="cf_2068"]').datepicker("remove");
          $('[name="cf_3663"]').select2().select2("val", response.schemetype);
          $('[name="cf_3663"]').select2().select2('readonly','true');
          $('[name="accounttype"]').select2().select2("val", response.schemefor);
          $('[name="accounttype"]').select2().select2('readonly','true');

          if(response.productschemerowcount > 0){
            $('table#Product_Category > tbody').html(response.productschemecounthtml);
            $('#totalRowCount_Product_Category').val(response.productschemecount);
            $('#directMode_Product_Category').val(1);
          }


          if(response.productsubrowcount > 0){
            $('table#Product_Subcategory > tbody').html(response.productschemecounthtml);
            $('#totalRowCount_Product_Subcategory').val(response.productschemecount);
            $('#directMode_Product_Subcategory').val(1);
          }


          if(response.prorowcount > 0){
            $('table#Product_Scheme > tbody').html(response.procounthtml);
            $('#totalRowCount_Product_Scheme').val(response.procount);
            $('#directMode_Product_Scheme').val(1);
          }

          if(response.giftrowcount > 0){
            $('table#Gift_Details > tbody').html(response.giftcounthtml);
            $('#totalRowCount_Gift_Details').val(response.giftcount);
            $('#directMode_Gift_Details').val(1);
          }

          $('.optionselect2').select2();

          if(response.schemefor=='Product'){

          $('div#Product_Scheme_divblock').removeClass('hide');
          $('div#Product_Category_divblock').addClass('hide');
          $('div#Product_Subcategory_divblock').addClass('hide');

          }else if(response.schemefor=='Product Category'){

            $('div#Product_Scheme_divblock').addClass('hide');
            $('div#Product_Category_divblock').removeClass('hide');
            $('div#Product_Subcategory_divblock').addClass('hide');

          }else if(response.schemefor=='Product Sub-Category'){

            $('div#Product_Scheme_divblock').addClass('hide');
            $('div#Product_Category_divblock').addClass('hide');
            $('div#Product_Subcategory_divblock').removeClass('hide');

          }else{

            $('div#Product_Scheme_divblock').addClass('hide');
            $('div#Product_Category_divblock').addClass('hide');
            $('div#Product_Subcategory_divblock').addClass('hide');

          }
        }
        });

      }




			if(referenceModule=='PlantMaster' && sourcemodule=='PurchaseReq'){

$.ajax(
{
type:"post",
url: "arocrmAjax.php",
data: {plantid : id, action: 'loadAllStockReq'},
dataType: 'json',
success:function(responses)
{

$('#PurchaseReq_Edit_fieldName_cf_2765').html('');
$('#PurchaseReq_Edit_fieldName_cf_2765').html(responses.message);
$('#PurchaseReq_Edit_fieldName_cf_2765').select2();
var d = new Date();
var month = d.getMonth()+1;
var day = d.getDate();
var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
var year = d.getFullYear();
var plant = id;
$.ajax(
{
type:"post",
url: "shirshaAjax.php",
data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
dataType: 'json',
success:function(response)
{
var graceday = response.days;
var chkval = response.fiscalval;
if(chkval == '1')
{
var gday = parseInt(graceday) - parseInt(1);
var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
var pmonth = pd.getMonth()+1;
var pday = pd.getDate();
var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;

var minDate = new Date(prevday);
$('#PurchaseReq_editView_fieldName_cf_3202').datepicker('setStartDate', minDate);

var maxDate = new Date(today);
$('#PurchaseReq_editView_fieldName_cf_3202').datepicker('setEndDate', maxDate);

}
else
{
var minDate = new Date(today);
$('#PurchaseReq_editView_fieldName_cf_3202').datepicker('setStartDate', minDate);

var maxDate = new Date(today);
$('#PurchaseReq_editView_fieldName_cf_3202').datepicker('setEndDate', maxDate);
}
}
});

}
});

			}




			if(referenceModule=='PlantMaster' && sourcemodule=='InboundDelivery'){
				var yr = $('select[name="cf_4319"]').val();
	var month = $('select[name="cf_4321"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3200"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3200"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3200"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3200"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule=='PlantMaster' && sourcemodule=='GoodsReceipt'){
				var d = new Date();
							var curmonth = d.getMonth()+1;
							var curday = d.getDate();
							var curyear = d.getFullYear();
							var yr = $('select[name="cf_4613"]').val();
							var month = $('select[name="cf_4615"]').val();
							var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3223"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3223"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3223"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3223"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule=='PlantMaster' && sourcemodule=='OutboundDelivery'){
				var yr = $('select[name="cf_4629"]').val();
	var month = $('select[name="cf_4631"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3225"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3225"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}

    	if(referenceModule=='PlantMaster' && sourcemodule=='QualityInspection'){
				var yr = $('select[name="cf_4609"]').val();
	var month = $('select[name="cf_4611"]').val();
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3227"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3227"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3227"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3227"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});	
			}
			if(referenceModule=='PlantMaster' && sourcemodule=='SalesOrder'){
				var yr = $('select[name="cf_4618"]').val();
	var month = $('select[name="cf_4620"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4306"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4306"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4306"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_4306"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule=='Vendors' && sourcemodule=='PurchaseOrder'){
				    $.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getVendorDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						$('textarea[name="cf_4788"]').val(response.message);
					}
					});
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getVendorAdvancePayments'},
				    dataType: 'json',
                    success:function(response)
                    {
						var len = response.paymentlength;
						if(len>0)
						{
							var paymentname = new Array();
							var paymentval = new Array();
							var paymentid = new Array();
							paymentname = response.paymentname;
							paymentval = response.paymentval;
							paymentid = response.paymentid;
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong>&nbsp;&nbsp;<select name="advpay[]" class="optselect" multiple style="width:200px;">';
							for(var i = 0; i<len; i++)
							{
							htmls = htmls + '<option value="'+paymentid[i]+'">'+paymentname[i]+' - '+paymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
							$(".optselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
						}
						/*var debitlen = response.debitpaymentlength;
						if(debitlen>0)
						{
							var debitpaymentname = new Array();
							var debitpaymentval = new Array();
							var debitpaymentid = new Array();
							debitpaymentname = response.debitpaymentname;
							debitpaymentval = response.debitpaymentval;
							debitpaymentid = response.debitpaymentid;
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong>&nbsp;&nbsp;<select name="advdebitpay[]" class="optdebitselect" multiple style="width:200px;">';
							for(var i = 0; i<debitlen; i++)
							{
							htmls = htmls + '<option value="'+debitpaymentid[i]+'">'+debitpaymentname[i]+' - '+debitpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optdebitselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}*/
						var creditlen = response.creditpaymentlength;
						if(creditlen>0)
						{
							var creditpaymentname = new Array();
							var creditpaymentval = new Array();
							var creditpaymentid = new Array();
							creditpaymentname = response.creditpaymentname;
							creditpaymentval = response.creditpaymentval;
							creditpaymentid = response.creditpaymentid;
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong>&nbsp;&nbsp;<select name="advcreditpay[]" class="optcreditselect" multiple style="width:200px;">';
							for(var i = 0; i<creditlen; i++)
							{
							htmls = htmls + '<option value="'+creditpaymentid[i]+'">'+creditpaymentname[i]+' - '+creditpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optcreditselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}
					}
					});
			}
			if(referenceModule=='Accounts' && sourcemodule=='SalesOrder'){
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getAdvancePayments'},
				    dataType: 'json',
                    success:function(response)
                    {
						var len = response.paymentlength;
						if(len>0)
						{
							var paymentname = new Array();
							var paymentval = new Array();
							var paymentid = new Array();
							paymentname = response.paymentname;
							paymentval = response.paymentval;
							paymentid = response.paymentid;
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong>&nbsp;&nbsp;<select name="advpay[]" class="optselect" multiple style="width:200px;">';
							for(var i = 0; i<len; i++)
							{
							htmls = htmls + '<option value="'+paymentid[i]+'">'+paymentname[i]+' - '+paymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
							$(".optselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
						}
						/*var debitlen = response.debitpaymentlength;
						if(debitlen>0)
						{
							var debitpaymentname = new Array();
							var debitpaymentval = new Array();
							var debitpaymentid = new Array();
							debitpaymentname = response.debitpaymentname;
							debitpaymentval = response.debitpaymentval;
							debitpaymentid = response.debitpaymentid;
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong>&nbsp;&nbsp;<select name="advdebitpay[]" class="optdebitselect" multiple style="width:200px;">';
							for(var i = 0; i<debitlen; i++)
							{
							htmls = htmls + '<option value="'+debitpaymentid[i]+'">'+debitpaymentname[i]+' - '+debitpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optdebitselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}*/
						var creditlen = response.creditpaymentlength;
						if(creditlen>0)
						{
							var creditpaymentname = new Array();
							var creditpaymentval = new Array();
							var creditpaymentid = new Array();
							creditpaymentname = response.creditpaymentname;
							creditpaymentval = response.creditpaymentval;
							creditpaymentid = response.creditpaymentid;
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong>&nbsp;&nbsp;<select name="advcreditpay[]" class="optcreditselect" multiple style="width:200px;">';
							for(var i = 0; i<creditlen; i++)
							{
							htmls = htmls + '<option value="'+creditpaymentid[i]+'">'+creditpaymentname[i]+' - '+creditpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optcreditselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}
					}
					});
			}
			
			if(referenceModule=='Accounts' && sourcemodule=='Invoice'){
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getAdvancePayments'},
				    dataType: 'json',
                    success:function(response)
                    {
						var len = response.paymentlength;
						if(len>0)
						{
							var paymentname = new Array();
							var paymentval = new Array();
							var paymentid = new Array();
							paymentname = response.paymentname;
							paymentval = response.paymentval;
							paymentid = response.paymentid;
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong>&nbsp;&nbsp;<select name="advpay[]" class="optselect" multiple style="width:200px;">';
							for(var i = 0; i<len; i++)
							{
							htmls = htmls + '<option value="'+paymentid[i]+'">'+paymentname[i]+' - '+paymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
							$(".optselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="adv"></span><span class="pull-right"><strong>Advance Payment</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(10) > td:first").html(htmls);
						}
						/*var debitlen = response.debitpaymentlength;
						if(debitlen>0)
						{
							var debitpaymentname = new Array();
							var debitpaymentval = new Array();
							var debitpaymentid = new Array();
							debitpaymentname = response.debitpaymentname;
							debitpaymentval = response.debitpaymentval;
							debitpaymentid = response.debitpaymentid;
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong>&nbsp;&nbsp;<select name="advdebitpay[]" class="optdebitselect" multiple style="width:200px;">';
							for(var i = 0; i<debitlen; i++)
							{
							htmls = htmls + '<option value="'+debitpaymentid[i]+'">'+debitpaymentname[i]+' - '+debitpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optdebitselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="dnote"></span><span class="pull-right"><strong>Debit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}*/
						var creditlen = response.creditpaymentlength;
						if(creditlen>0)
						{
							var creditpaymentname = new Array();
							var creditpaymentval = new Array();
							var creditpaymentid = new Array();
							creditpaymentname = response.creditpaymentname;
							creditpaymentval = response.creditpaymentval;
							creditpaymentid = response.creditpaymentid;
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong>&nbsp;&nbsp;<select name="advcreditpay[]" class="optcreditselect" multiple style="width:200px;">';
							for(var i = 0; i<creditlen; i++)
							{
							htmls = htmls + '<option value="'+creditpaymentid[i]+'">'+creditpaymentname[i]+' - '+creditpaymentval[i]+'</option>';
							}
							htmls = htmls + '</select></span>&nbsp;&nbsp;';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
							$(".optcreditselect").select2();
						}
						else
						{
							var htmls = '<span class="pull-left" id="cnote"></span><span class="pull-right"><strong>Credit Note</strong></span>';
							$("table#lineItemResult > tbody > tr:nth-child(11) > td:first").html(htmls);
						}
					}
					});
			}
			if(referenceModule == "PlantMaster" && sourcemodule == "CustomerPayment")
			{
				var yr = $('select[name="cf_4633"]').val();
					var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4635"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4967"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4967"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4967"]').datepicker('setStartDate', minDate);
												
												var maxDate = new Date(today);
												$('input[name="cf_4967"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			
			
			if(referenceModule == "Accounts" && sourcemodule == "CustomerPayment")
			{
				var type = $('[name="cf_3335"]').val();
				if(type == 'Sales Invoice Payment')
				{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'salesInvoiceDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						if(response.total == '0' || response.rowcount == '0')
						{
							app.helper.showAlertNotification({'message': 'Already paid total amount of all invoices against this Customer, select other customer'});
							$('#Payment_Details_divblock').hide();
						}
						else
						{
							$('#Payment_Details_divblock').show();
							$('table#Payment_Details > tbody').html('');
							$('table#Payment_Details > tbody').html(response.tbody);
							$('#totalRowCount_Payment_Details').val(response.rowcount);
							$('input[name="cf_3338"]').val(response.total);
							$('input[name="cf_3338"]').attr('readonly','true');
							$('input[name="cf_3340"]').val(response.sumdueamount);
							$('input[name="cf_3340"]').attr('readonly','true');
							$('input[name="cf_3342"]').val('0');
							//$('input[name="cf_3342"]').attr('readonly','true');
							$('input[name="cf_3344"]').val(response.sumdueamount);
							$('#directMode_Payment_Details').val(response.directmode);
							$('input[name="cf_3344"]').attr('readonly','true');
							$('input[name="cf_nrl_plantmaster1000_id"]').val(response.plantid);
							$('input[name="cf_nrl_plantmaster1000_id_display"]').val(response.plantname);
							$('input[name="cf_nrl_plantmaster1000_id_display"]').attr('readonly',true);
							$('#CustomerPayment_editView_fieldName_cf_nrl_plantmaster1000_id_select').parent().remove();
							$('#CustomerPayment_editView_fieldName_cf_nrl_plantmaster1000_id_create').parent().remove();
						}
					}
					});
				}
			}
			
			
			if(referenceModule == "PlantMaster" && sourcemodule == "VendorPayment")
			{
				var yr = $('select[name="cf_4633"]').val();
					var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4635"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4960"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4960"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4960"]').datepicker('setStartDate', minDate);
												
												var maxDate = new Date(today);
												$('input[name="cf_4960"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule == "Vendors" && sourcemodule == "VendorPayment")
			{
				var type = $('[name="cf_4701"]').val();
				if(type == 'Purchase Invoice')
				{
					$.ajax(
						{
						type:"post",
						url: "shirshaAjax.php",
						data: {id: id, action: 'purchaseInvoiceDetails'},
						dataType: 'json',
						success:function(response)
						{
							if(response.total == '0')
							{
								app.helper.showAlertNotification({'message': response.message});
								$('#Payment_Details_divblock').hide();
							}
							else
							{
								$('table#Payment_Details > tbody').html('');
								$('table#Payment_Details > tbody').html(response.tbody);
								$('#totalRowCount_Payment_Details').val(response.rowcount);
								$('input[name="cf_3331"]').val(response.alltotal);
								$('input[name="cf_3331"]').attr('readonly','true');
								$('input[name="cf_3300"]').val(response.total);
								$('input[name="cf_3300"]').attr('readonly','true');
								$('input[name="cf_3302"]').val('0');
								//$('input[name="cf_3302"]').attr('readonly','true');
								$('input[name="cf_3304"]').val(response.total);
								$('input[name="cf_3304"]').attr('readonly','true');
								$('input[name="cf_nrl_plantmaster425_id"]').val(response.plantid);
								$('input[name="cf_nrl_plantmaster425_id_display"]').val(response.plantname);
								$('input[name="cf_nrl_plantmaster425_id_display"]').attr('readonly',true);
								$('#VendorPayment_editView_fieldName_cf_nrl_plantmaster425_id_select').parent().remove();
								$('#VendorPayment_editView_fieldName_cf_nrl_plantmaster425_id_create').parent().remove();
							}
						}
						});
				}
			}
			if(referenceModule == "Products" && sourcemodule == "BOMMaster")
			{
				$.ajax(
                    {
                    type:"post",
                    url: "rahulAjax.php",
                    data: {id: id, action: 'getProductDetails'},
                    dataType: 'json',
                    success:function(response)
                    {
						/*var product = $('#cf_nrl_products1000_id_display').val();
						var productid = $("input[name=cf_nrl_products1000_id]").val();
						if(product != "" && product != undefined && productid == id)
						{
							$("input[name=cf_1254]").val(response.qtystock);
							$("input[name=cf_1254]").attr('readonly','true');
						}*/
						var bomrow = $('#totalRowCount_BOM_LineItem').val();
						if(bomrow== 1 && (recordid == undefined || recordid == ""))
						{
							var item = $("input[name=cf_3009_display]").val();
							var itemid = $("input[name=cf_3009]").val();
							if(item != "" && item != undefined && itemid == id)
							{
								$("input[name=cf_3011]").val(response.productcode);
								$("input[name=cf_3011]").attr('readonly','true');
								$("input[name=cf_3231]").val(response.unit);
								$("input[name=cf_3231]").attr('readonly','true');
							}
						}
						else
						{
							var bomrowArray = bomrow.split(",");
							var bomrowLength = bomrowArray.length;
							for(var i =1; i<=bomrowLength; i++)
							{
								var item = $("input[name=cf_3009_display_"+i+"]").val();
								var itemid = $("input[name^=cf_3009_"+i+"]").val();
								if(item != "" && item != undefined && itemid == id)
								{
									$("input[name=cf_3011_"+i+"]").val(response.productcode);
									$("input[name=cf_3011_"+i+"]").attr('readonly','true');
									$("input[name=cf_3231_"+i+"]").val(response.unit);
									$("input[name=cf_3231_"+i+"]").attr('readonly','true');
								}
							}
						}
					}
					});
			}


	  if(referenceModule == "PlantMaster" && sourcemodule == "SalesReturn")
	  {
		  	var yr = $('select[name="cf_4623"]').val();
			var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4625"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4817"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4817"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4817"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_4817"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
	  }
	  
	  
      if(referenceModule == "Invoice" && sourcemodule == "SalesReturn")
      {
		  
              $.ajax(
              {
              type:"post",
              url: "arocrmAjax.php",
              data: {id: id, action: 'getSODetailsforSOReturn'},
              dataType: 'json',
              success:function(response)
              {
				  if(response.msg == "")
				  {
				 // alert(response.assignedto);
				//  $('select[name="assigned_user_id"]').select2('data', { id:response.assignedto });
				 // $('select[name="assigned_user_id"]').select2().select2('readonly','true');
			
                  $('[name="cf_3266"]').val(response.grandtotal);
                  $('[name="cf_3266"]').prop("readonly",true);

                  $('[name="cf_nrl_accounts633_id"]').val(response.custid);
                  $('[name="cf_nrl_accounts633_id_display"]').val(response.custname);
                  $('[name="cf_nrl_accounts633_id_display"]').prop("readonly",true);
				  
				  $('[name="cf_nrl_plantmaster177_id"]').val(response.plantid);
                  $('[name="cf_nrl_plantmaster177_id_display"]').val(response.plantname);
                  $('[name="cf_nrl_plantmaster177_id_display"]').prop("readonly",true);
				  
				  $('[name="cf_nrl_salesorder922_id"]').val(response.soid);
                  $('[name="cf_nrl_salesorder922_id_display"]').val(response.soname);
                  $('[name="cf_nrl_salesorder922_id_display"]').prop("readonly",true);
				  
				  
                  $("#SalesReturn_editView_fieldName_cf_nrl_accounts633_id_select").parent().remove();
                  $("#SalesReturn_editView_fieldName_cf_nrl_accounts633_id_create").parent().remove();
				  
				  $("#SalesReturn_editView_fieldName_cf_nrl_plantmaster177_id_select").parent().remove();
                  $("#SalesReturn_editView_fieldName_cf_nrl_plantmaster177_id_create").parent().remove();
				  
				   $("#SalesReturn_editView_fieldName_cf_nrl_salesorder922_id_select").parent().remove();
                  $("#SalesReturn_editView_fieldName_cf_nrl_salesorder922_id_create").parent().remove();
				 
                  $('table#Line_Item > tbody').html('');
                  $('table#Line_Item > tbody').html(response.message);
                  $('#tr_clone_add__Line_Item').prop("disabled",true);
                  $('#totalRowCount_Line_Item').val(response.rowcount);
                  $('#directMode_Line_Item').val('1');
				  }
				  else
				  {
					  app.helper.showAlertNotification({'message': response.msg});
					  $('[name="cf_nrl_invoice621_id"]').val('');
					  $('[name="cf_nrl_invoice621_id_display"]').val('');
					  $('.clearReferenceSelection').hide();
				  }
              }
            });
      }



        if(referenceModule == "PlantMaster" && sourcemodule == "InboundDelivery")
        {
          $.ajax(
          {
          type:"post",
          url: "arocrmAjax.php",
          data: {id: id, action: 'getPlantCodeforPlantID'},
          dataType: 'json',
          success:function(response)
          {
          $("[name=cf_2860]").val(response.plantcode);
          $("[name=cf_2860]").attr("readonly","true");
          }

          });
        }



        if(referenceModule == "SalesOrder" && sourcemodule == "PurchaseOrder")
        {
        $.ajax(
          {
          type:"post",
          url: "arocrmAjax.php",
          data: {id: id, action: 'getDetailsforPOwrtSO'},
          dataType: 'json',
          success:function(response)
          {
            var amt  = response.amount;
            $('table#lineItemTab > tbody').html();
            $('table#lineItemTab > tbody').html(response.message);
            $('[name="cf_3366"]').html(response.invoice);
            $('[name="cf_3366"]').prop("readonly",true);
            $('#addProduct').prop("disabled",true);
            $('#addService').prop("disabled",true);
            $('#directMode_lineItemTab').val('1');
            $("[name=totalProductCount]").val(response.totalcount);
            $("#netTotal").html(amt.toFixed(2));
            $("#preTaxTotal").html(amt.toFixed(2));
            $("#grandTotal").html(amt.toFixed(2));
            $("#balance").val(amt.toFixed(2));

            $("#region_id").select2("readonly",true);
            $("#taxtype").select2("readonly",true);
            $("table#lineItemResult > tbody > tr:nth-child(2) > td:first").html('<div class="pull-right"><strong>Overall Discount</strong></div>');
            $("table#lineItemResult > tbody > tr:nth-child(5) > td:first").html('<div class="pull-right"><strong>Tax</strong></div>');
            $("table#lineItemResult > tbody > tr:nth-child(6) > td:first").html('<div class="pull-right"><strong>Taxes On Charges</strong></div>');
            $("table#lineItemResult > tbody > tr:nth-child(7) > td:first").html('<div class="pull-right"><strong>Deducted Taxes</strong></div>');
            $("#adjustment").prop("readonly",true);


          }
        });
        }



       if(referenceModule == "SalesReturn" && sourcemodule == "InboundDelivery")
       {
        var ref = $('[name="cf_3193"]').select2('data').id;
        if(ref=="" || ref==undefined){
          var message = "Please select Reference !!";
          app.helper.showAlertNotification({'message': message});
          setTimeout(function(){ 	window.location.reload(); }, 1500);
        }else{

            $.ajax(
            {
            type:"post",
            url: "arocrmAjax.php",
            data: {id: id, action: 'getSOReturnDetailsforIBD'},
            dataType: 'json',
            success:function(response)
            {
            if(response.message!=""){
            $("table#Line_Item > tbody").empty();
            $("table#Line_Item > tbody").html(response.message);
            $('#directMode_Line_Item').val('1');
            $("#totalRowCount_Line_Item").val(response.rowcount);
            $("#tr_clone_add__Line_Item").attr("disabled","true");
			
			$('[name="cf_2124"]').val(response.invoiceno);
			$('[name="cf_2124"]').prop("readonly",true);
			
			$('[name="cf_1651"]').val(response.invoicedate);
			$('[name="cf_1651"]').datepicker("remove");
			$('[name="cf_1651"]').prop("readonly",true);
		

            $("[name=cf_nrl_accounts181_id]").val(response.custid);
            $("[name=cf_nrl_accounts181_id_display]").val(response.custname);
            $("[name=cf_nrl_accounts181_id_display]").attr("readonly","true");
            $("#InboundDelivery_editView_fieldName_cf_nrl_accounts181_id_select").parent().remove();
            $("#InboundDelivery_editView_fieldName_cf_nrl_accounts181_id_create").parent().remove();
			
			$("[name=cf_nrl_plantmaster269_id]").val(response.plantid);
            $("[name=cf_nrl_plantmaster269_id_display]").val(response.plantname);
            $("[name=cf_nrl_plantmaster269_id_display]").attr("readonly","true");
            $("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_select").parent().remove();
            $("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_create").parent().remove();
			$('input[name="cf_2860"]').val(response.plantid);
			$('input[name="cf_2860"]').attr("readonly","true");


            localStorage.setItem("returnOrderSerial",JSON.stringify(response.serialnos));

            }else{
            var message = "IBD has been created from the Return Sales Order!!";
            app.helper.showAlertNotification({'message': message});
            setTimeout(function(){ 	window.location.reload(); }, 1500);
            }
            }

            });
            }
      }
	  if(referenceModule=='SalesBudget' && sourcemodule=='SalesBudget')
	  {
		$.ajax(
              {
				  type:"post",
				  url: "shirshaAjax.php",
				  data: {id: id, action: 'getSalesBudget'},
				  dataType: 'json',
				  success:function(response)
				  {
					$('input[name="cf_nrl_plantmaster615_id"]').val(response.cpid);
					$('input[name="cf_nrl_plantmaster615_id_display"]').val(response.cpname);
					$('input[name="cf_nrl_plantmaster615_id_display"]').attr('readonly','true');
					$('#SalesBudget_editView_fieldName_cf_nrl_plantmaster615_id_select').closest('span').css('display','none');
					$('#SalesBudget_editView_fieldName_cf_nrl_plantmaster615_id_create').closest('span').css('display','none');
					
					$('input[name="cf_nrl_accounts462_id"]').val(response.custid);
					$('input[name="cf_nrl_accounts462_id_display"]').val(response.customer);
					$('input[name="cf_nrl_accounts462_id_display"]').attr('readonly','true');
					$('#SalesBudget_editView_fieldName_cf_nrl_accounts462_id_select').closest('span').css('display','none');
					$('#SalesBudget_editView_fieldName_cf_nrl_accounts462_id_create').closest('span').css('display','none');
					
					$('input[name="cf_2821"]').val(response.state);
					$('input[name="cf_2819"]').val(response.district);
					$('input[name="cf_3473"]').val(response.place);
					$('input[name="cf_2823"]').val(response.nature);
					$('input[name="cf_2825"]').val(response.grade);
					$('input[name="cf_2821"]').prop('readonly',true);
					$('input[name="cf_2819"]').prop('readonly',true);
					$('input[name="cf_3473"]').prop('readonly',true);
					$('input[name="cf_2823"]').prop('readonly',true);
					$('input[name="cf_2825"]').prop('readonly',true);
					$('select[name="cf_3424"]').select2('data', { id: response.year, text: response.year});
					$('select[name="cf_3424"]').select2().select2('readonly','true');
					//$('select[name="cf_2821"]').val();
					$("table.table#Category_Wise > tbody").empty();
					$("table.table#Category_Wise > tbody").html(response.tbodycat);
					$('#totalRowCount_Category_Wise').val(response.rowcountcat);
					/*$("table.table#4W > tbody").empty();
					$("table.table#4W > tbody").html(response.tbody4W);
					$('#totalRowCount_4W').val(response.rowcount4W);
					$("table.table#2W > tbody").empty();
					$("table.table#2W > tbody").html(response.tbody2W);
					$('#totalRowCount_2W').val(response.rowcount2W);
					$("table.table#IB > tbody").empty();
					$("table.table#IB > tbody").html(response.tbodyIB);
					$('#totalRowCount_IB').val(response.rowcountIB);
					$("table.table#ER > tbody").empty();
					$("table.table#ER > tbody").html(response.tbodyER);
					$('#totalRowCount_ER').val(response.rowcountER);*/
				  }
			  });
	  }
	  if(referenceModule=='JourneyPlan' && sourcemodule=='JourneyPlan')
	  {
		  $.ajax(
              {
				  type:"post",
				  url: "shirshaAjax.php",
				  data: {id: id, action: 'getPJP'},
				  dataType: 'json',
				  success:function(response)
				  {
					$('select[name="cf_1491"]').select2('data', { id: response.year, text: response.year});
					$('select[name="cf_1491').select2().select2('readonly',true);
								
					$('select[name="cf_1493"]').select2('data', { id: response.month, text: response.month});
					$('select[name="cf_1493').select2().select2('readonly',true);
					
					$('input[name="cf_3592"]').val(response.normal);
					$('input[name="cf_3594"]').val(response.calamity);
					$('input[name="cf_3592"]').prop("readonly",true);
					$('input[name="cf_3594"]').prop("readonly",true);
					$("table.table#Basic_Details > tbody").empty();
					$("table.table#Basic_Details > tbody").html(response.tbodyBasic);
					$('#totalRowCount_Basic_Details').val(response.rowcountBasic);
					$('#directMode_Basic_Details').val('1');
					$("table.table#Actual_Working_Details > tbody").empty();
					$("table.table#Actual_Working_Details > tbody").html(response.tbodyWorking);
					$('#totalRowCount_Actual_Working_Details').val(response.rowcountWorking);
					$('#directMode_Actual_Working_Details').val('1');
					$("table.table#Actual_Bill_Details > tbody").empty();
					$("table.table#Actual_Bill_Details > tbody").html(response.tbodyBill);
					$('#totalRowCount_Actual_Bill_Details').val(response.rowcountBill);
					$('#directMode_Actual_Bill_Details').val('1');
					var type = $('select[name="cf_3588"]').val();
					if(type == 'Revised')
					{
						var basictotalrow = $('#totalRowCount_Basic_Details').val();
						var basicarr = basictotalrow.split(",");
						var basicrowcount = basicarr.length;
						for(var i=1;i<=basicrowcount;i++)
						{
							var date = $('input[name="cf_1962_'+i+'"]').val();
							var date = date.split('-')[2]+"-"+date.split('-')[1]+"-"+date.split('-')[0];
							var customdate = new Date(date.split('-')[2],date.split('-')[1]-1,date.split('-')[0]);
							var fullDate = new Date();
							if(fullDate>customdate)
							{
								//$('input[name="cf_1962_'+i+'"]').prop('readonly',true);
								$('.clearReferenceSelection').css('display','none');
								//$('#JourneyPlan_editView_fieldName_cf_nrl_routemaster499_id_select').closest('span').css('display','none');
								//$('#JourneyPlan_editView_fieldName_cf_nrl_routemaster499_id_create').closest('span').css('display','none');
								$('input[name="cf_1988_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2000_'+i+'"]').prop('readonly',true);
								$('select[name="cf_2016_'+i+'"]').select2().select2('readonly',true);
								$('select[name="cf_2024_'+i+'"]').select2().select2('readonly',true);
								//$('input[name="cf_2034_'+i+'"]').prop('readonly',true);
								//$('input[name="cf_2036_'+i+'"]').prop('readonly',true);
								//$('input[name="cf_2038_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2040_'+i+'"]').prop('readonly',true);
								$('select[name="cf_2070_'+i+'[]"]').select2().select2('readonly',true);
								//$('input[name="cf_2072_'+i+'"]').prop('readonly',true);
								$('select[name="cf_3130_'+i+'[]"]').select2().select2('readonly',true);
							}
						}
						var actualtotalrow = $('#totalRowCount_Actual_Working_Details').val();
						var actualarr = actualtotalrow.split(",");
						var actualrowcount = actualarr.length;
						for(var j=1;j<=actualrowcount;j++)
						{
							var date = $('input[name="cf_2086_'+j+'"]').val();
							var date = date.split('-')[2]+"-"+date.split('-')[1]+"-"+date.split('-')[0];
							var customdate = new Date(date.split('-')[2],date.split('-')[1]-1,date.split('-')[0]);
							if(fullDate<customdate)
							{
								$('select[name="cf_2106_'+j+'"]').select2().select2('readonly',true);
								$('select[name="cf_2108_'+j+'"]').select2().select2('readonly',true);
								$('input[name="cf_2110_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2112_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2114_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2116_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2118_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2120_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2122_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2126_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2128_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2130_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2132_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2134_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2136_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2138_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2140_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2142_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2144_'+j+'"]').prop('readonly',true);
								$('select[name="cf_2146_'+j+'"]').select2().select2('readonly',true);
								$('input[name="cf_2148_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2150_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2152_'+j+'"]').prop('readonly',true);
								$('textarea[name="cf_2154_'+j+'"]').prop('readonly',true);
							}
							if(fullDate>customdate)
							{
								$('input[name="cf_2086_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2102_'+j+'"]').prop('readonly',true);
								$('select[name="cf_2104_'+j+'[]"]').select2().select2('readonly',true);
								$('textarea[name="cf_3657_'+j+'"]').prop('readonly',true);
								$('select[name="cf_2106_'+j+'"]').select2().select2('readonly',true);
								$('select[name="cf_2108_'+j+'"]').select2().select2('readonly',true);
								$('input[name="cf_2110_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2112_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2114_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2116_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2118_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2120_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2122_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2126_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2128_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2130_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2132_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2134_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2136_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2138_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2140_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2142_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2144_'+j+'"]').prop('readonly',true);
								$('select[name="cf_2146_'+j+'"]').select2().select2('readonly',true);
								$('input[name="cf_2148_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2150_'+j+'"]').prop('readonly',true);
								$('input[name="cf_2152_'+j+'"]').prop('readonly',true);
								$('textarea[name="cf_2154_'+j+'"]').prop('readonly',true);
							}
						}
						var billtotalrow = $('#totalRowCount_Actual_Bill_Details').val();
						var billarr = billtotalrow.split(",");
						var billrowcount = billarr.length;
						for(var i=1;i<=billrowcount;i++)
						{
							var date = $('input[name="cf_3597_'+i+'"]').val();
							var date = date.split('-')[2]+"-"+date.split('-')[1]+"-"+date.split('-')[0];
							var customdate = new Date(date.split('-')[2],date.split('-')[1]-1,date.split('-')[0]);
							if(fullDate>customdate)
							{
								$('input[name="cf_2046_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2048_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2050_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2052_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2054_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2058_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2064_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3597_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3599_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3601_'+i+'"]').prop('readonly',true);
								$('textarea[name="cf_3603_'+i+'"]').prop('readonly',true);
								$('textarea[name="cf_3605_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3607_'+i+'"]').prop('readonly',true);
							}
							if(fullDate<customdate)
							{
								$('input[name="cf_2046_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2048_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2050_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2052_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2054_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2058_'+i+'"]').prop('readonly',true);
								$('input[name="cf_2064_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3597_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3599_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3601_'+i+'"]').prop('readonly',true);
								$('textarea[name="cf_3603_'+i+'"]').prop('readonly',true);
								$('textarea[name="cf_3605_'+i+'"]').prop('readonly',true);
								$('input[name="cf_3607_'+i+'"]').prop('readonly',true);
							}
						}
						
					}
				  }
			  });
	  }
	  if(referenceModule == "PlantMaster" && sourcemodule == "GoodsIssue")
      {
		  var yr = $('select[name="cf_4633"]').val();
	var month = $('select[name="cf_4635"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3229"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3229"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3229"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3229"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});	
	  }
      if(referenceModule == "OutboundDelivery" && sourcemodule == "GoodsIssue")
      {
              $.ajax(
              {
              type:"post",
              url: "arocrmAjax.php",
              data: {id: id, action: 'getDetailsOBDforGI'},
              dataType: 'json',
              success:function(response)
              {
                  $('[name="cf_1869"]').val(response.modeoftransfer);
                  $('[name="cf_1869"]').prop("readonly",true);

                  $('[name="cf_3137"]').val(response.vehicleno);
                  $('[name="cf_3137"]').prop("readonly",true);

                  $('[name="cf_1873"]').val(response.curdate);
                  $('[name="cf_1873"]').prop("readonly",true);

                  $('[name="cf_3185"]').val(response.obddate);
                  $('[name="cf_3185"]').removeClass('dateField');
                  $('[name="cf_3185"]').prop("readonly",true);

                  $('[name="cf_nrl_accounts901_id"]').val(response.custid);
                  $('[name="cf_nrl_accounts901_id_display"]').val(response.custname);
                  $('[name="cf_nrl_accounts901_id_display"]').prop("readonly",true);
                  $("#GoodsIssue_editView_fieldName_cf_nrl_accounts901_id_select").parent().remove();
                  $("#GoodsIssue_editView_fieldName_cf_nrl_accounts901_id_create").parent().remove();


                  $('[name="cf_nrl_plantmaster280_id"]').val(response.plantid);
                  $('[name="cf_nrl_plantmaster280_id_display"]').val(response.plantname);
                  $('[name="cf_nrl_plantmaster280_id_display"]').prop("readonly",true);
				 $("#GoodsIssue_editView_fieldName_cf_nrl_plantmaster280_id_select").parent().remove();
				 $("#GoodsIssue_editView_fieldName_cf_nrl_plantmaster280_id_create").parent().remove();

				 
                  $('table#Line_Item > tbody').html('');
                  $('table#Line_Item > tbody').html(response.message);
                  $('#tr_clone_add__Line_Item').prop("disabled",true);
                  $('#totalRowCount_Line_Item').val(response.rowcount);
                  $('#directMode_Line_Item').val('1');

                  if(response.savestatestatus==0){
                  localStorage.setItem('savestatestatus',0);
                  $('.saveButton').prop("disabled",true);
				  
				  var message = "Customer In-sufficient Credit Limit !!";
				  app.helper.showAlertNotification({'message': message});
				  
                  }else{
                  localStorage.setItem('savestatestatus',1);
                  $('.saveButton').prop("disabled",false);
                  }

				var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
				var year = d.getFullYear();
				var plant = response.plantid;
				$.ajax(
					{
					type:"post",
					url: "shirshaAjax.php",
					data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
					dataType: 'json',
					success:function(response)
					{
						var graceday = response.days;
						var chkval = response.fiscalval;
						if(chkval == '1')
						{
							var gday = parseInt(graceday) - parseInt(1);
							var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
							var pmonth = pd.getMonth()+1;
							var pday = pd.getDate();
							var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
							var minDate = new Date(prevday);
							$('input[name="cf_3229"]').datepicker('setStartDate', minDate);
							var maxDate = new Date(today);
							$('input[name="cf_3229"]').datepicker('setEndDate', maxDate);
						}
						else
						{
							var minDate = new Date(today);
							$('input[name="cf_3229"]').datepicker('setStartDate', minDate);

							var maxDate = new Date(today);
							$('input[name="cf_3229"]').datepicker('setEndDate', maxDate);
						}
					}
					});
              }
            });
      }


      if(referenceModule == "District" && sourcemodule == "RouteMaster")
      {
        $.ajax(
              {
              type:"post",
              url: "rahulAjax.php",
              data: {id: id, action: 'getState'},
              dataType: 'json',
              success:function(response)
              {
             var tmpid = localStorage.getItem("tagmoduleid");
             var tp = tmpid.split("_");
             var id = tp[2];
             if(id=="" || id==undefined){
               $('[name="cf_1465"]').val(response.state);
                $('[name="cf_3499"').val(response.country);
                $('[name="cf_1465"]').prop("readonly",true);
                 $('[name="cf_3499"').prop("readonly",true);
             }else{
               $('[name="cf_1465_'+id+'"]').val(response.state);
                $('[name="cf_3499_'+id+'"]').val(response.country);
                $('[name="cf_1465_'+id+'"]').prop("readonly",true);
                 $('[name="cf_3499_'+id+'"]').prop("readonly",true);
             }
              }
            });
        }

      if(referenceModule == "District" && sourcemodule == "MarketAnalysis")
			{
				$.ajax(
              {
              type:"post",
              url: "rahulAjax.php",
              data: {id: id, action: 'getState'},
              dataType: 'json',
              success:function(response)
              {
						var total4wrow = $('#totalRowCount_4W').val();
						if(total4wrow == 1 && (recordid == "" || recordid == undefined))
						{
							if($("input[name=cf_2188]").val() == id)
							{
								$('input[name="cf_3469"]').val(response.state);
								$('input[name="cf_3469"]').attr('readonly','true');
							}
						}
						else
						{
							var total4w = total4wrow.split(",");
							var len4w = total4w.length;
							for(var i=1;i<=len4w;i++)
							{
								if($("input[name=cf_2188_"+i+"]").val() == id)
								{
									$('input[name=cf_3469_'+i+']').val(response.state);
									$('input[name="cf_3469_'+i+']').attr('readonly','true');
								}
							}
						}
						var total2wrow = $('#totalRowCount_2W').val();
						if(total2wrow == 1 && (recordid == "" || recordid == undefined))
						{
							if($("input[name=cf_2215]").val() == id)
							{
								$('input[name="cf_3467"]').val(response.state);
								$('input[name="cf_3467"]').attr('readonly','true');
							}
						}
						else
						{
							var total2w = total2wrow.split(",");
							var len2w = total2w.length;
							for(var i=1;i<=len2w;i++)
							{
								if($("input[name=cf_2215_"+i+"]").val() == id)
								{
									$('input[name=cf_3467_'+i+']').val(response.state);
									$('input[name=cf_3467_'+i+']').attr('readonly','true');
								}
							}
						}
						var totalibrow = $('#totalRowCount_IB').val();
						if(totalibrow == 1 && (recordid == "" || recordid == undefined))
						{
							if($("input[name=cf_2254]").val() == id)
							{
								$('input[name="cf_3465"]').val(response.state);
								$('input[name="cf_3465"]').attr('readonly','true');
							}
						}
						else
						{
							var totalib = totalibrow.split(",");
							var lenib = totalib.length;
							for(var i=1;i<=lenib;i++)
							{
								if($("input[name=cf_2254_"+i+"]").val() == id)
								{
									$('input[name=cf_3465_'+i+']').val(response.state);
									$('input[name=cf_3465_'+i+']').attr('readonly','true');
								}
							}
						}
						var totalerrow = $('#totalRowCount_ER').val();
						if(totalerrow == 1 && (recordid == "" || recordid == undefined))
						{
							if($("input[name=cf_2285]").val() == id)
							{
								$('input[name="cf_3463"]').val(response.state);
								$('input[name="cf_3463"]').attr('readonly','true');
							}
						}
						else
						{
							var totaler = totalerrow.split(",");
							var lener = totaler.length;
							for(var i=1;i<=lener;i++)
							{
								if($("input[name=cf_2285_"+i+"]").val() == id)
								{
									$('input[name=cf_3463_'+i+']').val(response.state);
									$('input[name=cf_3463_'+i+']').attr('readonly','true');
								}
							}
						}
						var total4wrow = $('#totalRowCount_4W').val();
						if(total4wrow == 1 && recordid != "" && recordid != undefined)
						{
							if($("input[name=cf_2188]").val() == id)
							{
								$('input[name="cf_3469"]').val(response.state);
								$('input[name="cf_3469"]').attr('readonly','true');
							}
						}
						else
						{
							var total4w = total4wrow.split(",");
							var len4w = total4w.length;
							for(var i=1;i<=len4w;i++)
							{
								if($("input[name=cf_2188_"+i+"]").val() == id)
								{
									$('input[name=cf_3469_'+i+']').val(response.state);
									$('input[name=cf_3469_'+i+']').attr('readonly','true');
								}
							}
						}
						var total2wrow = $('#totalRowCount_2W').val();
						if(total2wrow == 1 && recordid != "" && recordid != undefined)
						{
							if($("input[name=cf_2215]").val() == id)
							{
								$('input[name="cf_3467"]').val(response.state);
								$('input[name="cf_3467"]').attr('readonly','true');
							}
						}
						else
						{
							var total2w = total2wrow.split(",");
							var len2w = total2w.length;
							for(var i=1;i<=len2w;i++)
							{
								if($("input[name=cf_2215_"+i+"]").val() == id)
								{
									$('input[name=cf_3467_'+i+']').val(response.state);
									$('input[name=cf_3467_'+i+']').attr('readonly','true');
								}
							}
						}
						var totalibrow = $('#totalRowCount_IB').val();
						if(totalibrow == 1 && recordid != "" && recordid != undefined)
						{
							if($("input[name=cf_2254]").val() == id)
							{
								$('input[name="cf_3465"]').val(response.state);
								$('input[name="cf_3465"]').attr('readonly','true');
							}
						}
						else
						{
							var totalib = totalibrow.split(",");
							var lenib = totalib.length;
							for(var i=1;i<=lenib;i++)
							{
								if($("input[name=cf_2254_"+i+"]").val() == id)
								{
									$('input[name=cf_3465_'+i+']').val(response.state);
									$('input[name=cf_3465_'+i+']').attr('readonly','true');
								}
							}
						}
						var totalerrow = $('#totalRowCount_ER').val();
						if(totalerrow == 1 && recordid != "" && recordid != undefined)
						{
							if($("input[name=cf_2285]").val() == id)
							{
								$('input[name="cf_3463"]').val(response.state);
								$('input[name="cf_3463"]').attr('readonly','true');
							}
						}
						else
						{
							var totaler = totalerrow.split(",");
							var lener = totaler.length;
							for(var i=1;i<=lener;i++)
							{
								if($("input[name=cf_2285_"+i+"]").val() == id)
								{
									$('input[name=cf_3463_'+i+']').val(response.state);
									$('input[name=cf_3463_'+i+']').attr('readonly','true');
								}
							}
						}
					}
					});
			}
			if(referenceModule == "HelpDesk" && sourcemodule == "InitialJobReport")
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getTicketDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						$('input[name="cf_4031"]').val(response.regdate);
						$('input[name="cf_4031"]').attr('readonly',true);
						$('input[name="cf_3973"]').val(response.serialno);
						$('input[name="cf_3977"]').val(response.selldate);
						$('input[name="cf_3999"]').val(response.cp);
						$('input[name="cf_3975"]').val(response.productname);
						$('input[name="cf_4001"]').val(response.productcategory);
						$('input[name="cf_3983"]').val(response.consumer);
						$('input[name="cf_3997"]').val(response.mobile);
						$('input[name="cf_3985"]').val(response.street);
						$('input[name="cf_3989"]').val(response.city);
						$('input[name="cf_3987"]').val(response.po);
						$('input[name="cf_3991"]').val(response.state);
						$('input[name="cf_3993"]').val(response.country);
						$('input[name="cf_3995"]').val(response.zip);
						$('input[name="cf_3979"]').val(response.ppoint);
						$('input[name="cf_3981"]').val(response.pplace);
						$('input[name="cf_3973"]').prop('readonly','true');
						$('input[name="cf_3977"]').prop('readonly','true');
						$('input[name="cf_3999"]').prop('readonly','true');
						$('input[name="cf_3975"]').prop('readonly','true');
						$('input[name="cf_4001"]').prop('readonly','true');
						$('input[name="cf_3983"]').prop('readonly','true');
						$('input[name="cf_3997"]').prop('readonly','true');
						$('input[name="cf_3985"]').prop('readonly','true');
						$('input[name="cf_3989"]').prop('readonly','true');
						$('input[name="cf_3987"]').prop('readonly','true');
						$('input[name="cf_3991"]').prop('readonly','true');
						$('input[name="cf_3993"]').prop('readonly','true');
						$('input[name="cf_3995"]').prop('readonly','true');
						$('input[name="cf_3979"]').prop('readonly','true');
						$('input[name="cf_3981"]').prop('readonly','true');
					}
					});
			}
			if(referenceModule == "HelpDesk" && sourcemodule == "FinalJobProcessingReport")
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getTicketDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						$('input[name="cf_3854"]').val(response.regdate);
						$('input[name="cf_3854"]').attr('readonly',true);
						$('input[name="cf_3794"]').val(response.serialno);
						$('input[name="cf_3814"]').val(response.selldate);
						$('input[name="cf_3870"]').val(response.cp);
						$('input[name="cf_3812"]').val(response.productname);
						$('input[name="cf_3834"]').val(response.productcategory);
						if(response.productcategory == '4W' || response.productcategory == '2W')
						{
							$('#Resistive_Load_Test_Report_of_4W_or_2W_divblock').show();
							$('#Inverter_or_E_Rickshaw_Battery_Load_or_Capacity_Test_divblock').hide();
						}
						if(response.productcategory == 'IB' || response.productcategory == 'ER')
						{
							$('#Resistive_Load_Test_Report_of_4W_or_2W_divblock').hide();
							$('#Inverter_or_E_Rickshaw_Battery_Load_or_Capacity_Test_divblock').show();
							if(response.productcategory == 'IB')
							{
								$('input[name="cf_3954"]').closest('td').show();
								$('input[name="cf_3954"]').closest('td').prev('td').show();
								$('input[name="cf_5063"]').closest('td').show();
								$('input[name="cf_5063"]').closest('td').prev('td').show();
								$('input[name="cf_5065"]').closest('td').show();
								$('input[name="cf_5065"]').closest('td').prev('td').show();
								$('input[name="cf_5067"]').closest('td').hide();
								$('input[name="cf_5067"]').closest('td').prev('td').hide();
								$('input[name="cf_5069"]').closest('td').hide();
								$('input[name="cf_5069"]').closest('td').prev('td').hide();
								$('input[name="cf_5071"]').closest('td').hide();
								$('input[name="cf_5071"]').closest('td').prev('td').hide();
							}
							if(response.productcategory == 'ER')
							{
								$('input[name="cf_3954"]').closest('td').hide();
								$('input[name="cf_3954"]').closest('td').prev('td').hide();
								$('input[name="cf_5063"]').closest('td').hide();
								$('input[name="cf_5063"]').closest('td').prev('td').hide();
								$('input[name="cf_5065"]').closest('td').hide();
								$('input[name="cf_5065"]').closest('td').prev('td').hide();
								$('input[name="cf_5067"]').closest('td').show();
								$('input[name="cf_5067"]').closest('td').prev('td').show();
								$('input[name="cf_5069"]').closest('td').show();
								$('input[name="cf_5069"]').closest('td').prev('td').show();
								$('input[name="cf_5071"]').closest('td').show();
								$('input[name="cf_5071"]').closest('td').prev('td').show();
							}
						}
						$('input[name="cf_3820"]').val(response.consumer);
						$('input[name="cf_3842"]').val(response.mobile);
						$('input[name="cf_3822"]').val(response.street);
						$('input[name="cf_3826"]').val(response.city);
						$('input[name="cf_3824"]').val(response.po);
						$('input[name="cf_3828"]').val(response.state);
						$('input[name="cf_3830"]').val(response.country);
						$('input[name="cf_3832"]').val(response.zip);
						$('input[name="cf_3816"]').val(response.ppoint);
						$('input[name="cf_3818"]').val(response.pplace);
						$('input[name="cf_3838"]').val(response.vrno);
						$('input[name="cf_3836"]').val(response.vmodel);
						$('input[name="cf_3838"]').attr('readonly',true);
						$('input[name="cf_3836"]').attr('readonly',true);
						$('input[name="cf_nrl_plantmaster472_id"]').val(response.plantid);
						$('input[name="cf_nrl_plantmaster472_id_display"]').val(response.plantname);
						$('input[name="cf_nrl_plantmaster472_id_display"]').attr('readonly',true);
						$('#FinalJobProcessingReport_editView_fieldName_cf_nrl_plantmaster472_id_select').parent().remove();
						$('#FinalJobProcessingReport_editView_fieldName_cf_nrl_plantmaster472_id_create').parent().remove();
						$('input[name="cf_3794"]').prop('readonly','true');
						$('input[name="cf_3814"]').prop('readonly','true');
						$('input[name="cf_3870"]').prop('readonly','true');
						$('input[name="cf_3812"]').prop('readonly','true');
						$('input[name="cf_3834"]').prop('readonly','true');
						$('input[name="cf_3820"]').prop('readonly','true');
						$('input[name="cf_3842"]').prop('readonly','true');
						$('input[name="cf_3822"]').prop('readonly','true');
						$('input[name="cf_3826"]').prop('readonly','true');
						$('input[name="cf_3824"]').prop('readonly','true');
						$('input[name="cf_3828"]').prop('readonly','true');
						$('input[name="cf_3830"]').prop('readonly','true');
						$('input[name="cf_3832"]').prop('readonly','true');
						$('input[name="cf_3816"]').prop('readonly','true');
						$('input[name="cf_3818"]').prop('readonly','true');
					}
					});
			}
			if(referenceModule == "ServiceContracts" && sourcemodule == "ServiceContracts")
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
					data: {id: id, action: 'getWarrantyDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						$('input[name="cf_4531"]').val(response.serialno);
						$('input[name="cf_2989"]').val(response.mfgdate);
						$('input[name="cf_2971"]').val(response.selldate);
						$('input[name="cf_3615"]').val(response.cp);
						$('input[name="cf_2973"]').val(response.productcode);
						$('input[name="cf_nrl_products997_id"]').val(response.productid);
						$('input[name="cf_nrl_products997_id_display"]').val(response.productname);
						$('#ServiceContracts_editView_fieldName_cf_nrl_products997_id_select').closest('span').css('display','none');
						$('#ServiceContracts_editView_fieldName_cf_nrl_products997_id_create').closest('span').css('display','none');
						$('input[name="cf_3709"]').val(response.productgroup);
						$('select[name="productcategory"]').select2('data', { id: response.productcategory, text: response.productcategory});
						if(response.productcategory == '4W' || response.productcategory == '2W')
						{
							$('#Electrical_Performance_of_the_Vehicle_at_the_time_of_Fitment_divblock').show();
							$('#Electrical_Performance_of_the_E_Rickshaw_at_the_Time_of_Fitment_divblock').hide();
							$('#Inverter_Details_divblock').hide();
						}
						if(response.productcategory == 'IB')
						{
							$('#Electrical_Performance_of_the_Vehicle_at_the_time_of_Fitment_divblock').hide();
							$('#Electrical_Performance_of_the_E_Rickshaw_at_the_Time_of_Fitment_divblock').hide();
							$('#Inverter_Details_divblock').show();
						}
						if(response.productcategory == 'ER')
						{
							$('#Inverter_Details_divblock').hide();
							$('#Electrical_Performance_of_the_Vehicle_at_the_time_of_Fitment_divblock').hide();
							$('#Electrical_Performance_of_the_E_Rickshaw_at_the_Time_of_Fitment_divblock').show();
						}
						$('select[name="productcategory"]').select2().select2('readonly','true');
						$('input[name="cf_3124"]').val(response.sellperiod);
						$('input[name="cf_3420"]').val(response.wfreeperiod);
						$('input[name="cf_3126"]').val(response.wprorataperiod);
						$('input[name="cf_3638"]').val(response.cfreedate);
						$('input[name="cf_2975"]').val(response.cproratadate);
						$('input[name="cf_3640"]').val(response.afreedate);
						$('input[name="cf_2977"]').val(response.aproratadate);
						$('input[name="cf_2969"]').val(response.consumer);
						$('input[name="cf_3623"]').val(response.mail);
						$('input[name="cf_3621"]').val(response.mobile);
						$('input[name="cf_3689"]').val(response.street);
						$('input[name="cf_3693"]').val(response.city);
						$('input[name="cf_3691"]').val(response.po);
						$('input[name="cf_3695"]').val(response.state);
						$('input[name="cf_3699"]').val(response.country);
						$('input[name="cf_3697"]').val(response.zip);
						$('input[name="cf_3617"]').val(response.ppoint);
						$('input[name="cf_3701"]').val(response.pplace);
						$('input[name="cf_3703"]').val(response.pdis);
						$('input[name="cf_3705"]').val(response.pstate);
						$('input[name="cf_3725"]').val(response.make);
						$('input[name="cf_3727"]').val(response.mode);
						$('input[name="cf_3729"]').val(response.pmy);
						$('input[name="cf_3731"]').val(response.life);
						$('input[name="cf_3619"]').val(response.purchasedate);
						$('select[name="contract_status"]').select2('data', { id: response.stage, text: response.stage});
						$('select[name="contract_status"]').select2().select2('readonly','true');
						$('select[name="cf_3661"]').select2('data', { id: response.status, text: response.status});
						$('select[name="cf_3661"]').select2().select2('readonly','true');
						$('input[name="cf_4531"]').prop('readonly','true');
						$('input[name="cf_2989"]').prop('readonly','true');
						$('input[name="cf_2971"]').prop('readonly','true');
						$('input[name="cf_3615"]').prop('readonly','true');
						$('input[name="cf_2973"]').prop('readonly','true');
						$('input[name="cf_3709"]').prop('readonly','true');
						$('input[name="cf_3124"]').prop('readonly','true');
						$('input[name="cf_3126"]').prop('readonly','true');
						$('input[name="cf_3638"]').prop('readonly','true');
						$('input[name="cf_2975"]').prop('readonly','true');
						$('input[name="cf_3640"]').prop('readonly','true');
						$('input[name="cf_2977"]').prop('readonly','true');
						$('input[name="cf_3619"]').prop('readonly','true');
						$('input[name="cf_3619"]').prop('readonly','true');
						$('input[name="cf_2969"]').prop('readonly','true');
						$('input[name="cf_3623"]').prop('readonly','true');
						$('input[name="cf_3621"]').prop('readonly','true');
						$('input[name="cf_3689"]').prop('readonly','true');
						$('input[name="cf_3693"]').prop('readonly','true');
						$('input[name="cf_3691"]').prop('readonly','true');
						$('input[name="cf_3695"]').prop('readonly','true');
						$('input[name="cf_3699"]').prop('readonly','true');
						$('input[name="cf_3697"]').prop('readonly','true');
						$('input[name="cf_3725"]').prop('readonly','true');
						$('input[name="cf_3727"]').prop('readonly','true');
						$('input[name="cf_3729"]').prop('readonly','true');
						$('input[name="cf_3617"]').prop('readonly','true');
						$('input[name="cf_3701"]').prop('readonly','true');
						$('input[name="cf_3703"]').prop('readonly','true');
						$('input[name="cf_3705"]').prop('readonly','true');
						$('input[name="cf_nrl_products997_id_display"]').prop('readonly','true');
						$('input[name="cf_nrl_products997_id_display"]').prop('readonly','true');
					}
					});
			}

			if(referenceModule == "ServiceContracts" && sourcemodule == "HelpDesk")
			{
					$.ajax(
                    {
                    type:"post",
                    url: "rahulAjax.php",
					data: {id: id, action: 'getWarrantyDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						$("input[name=cf_2991]").val(response.serialno);
						$("input[name=cf_2991]").attr("readonly","true");
						$("input[name=product_id]").val(response.productid);
						$("input[name=product_id_display]").val(response.productname);
						$("input[name=product_id_display]").attr("readonly","true");
					}
					});
			}
			if(referenceModule=='PlantMaster' && sourcemodule=='RFQMaintain')
			{
				var yr = $('select[name="cf_4623"]').val();
		var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4625"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4800"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4800"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4800"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_4800"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule=='Products' && sourcemodule=='RFQMaintain')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
                    data: {productid: id, action: 'getProductAllDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						var trid = localStorage.getItem('tagmoduleid');
						var trct = trid.split("_");
						var trctt = trct.length;
						if(trctt==2){
						$("[name='cf_1965']").val(response.productcode);
						$("[name='cf_1965']").prop('readonly','true');
						$("[name='cf_1969']").val(response.productunit);
						$("[name='cf_1969']").prop('readonly','true');
						$("[name='cf_4872']").val(response.ah);
						$("[name='cf_4872']").prop('readonly','true');
						$("[name='cf_4874']").val(response.category);
						$("[name='cf_4874']").prop('readonly','true');
						}else if(trctt==3){
						var dataselid = trct[trctt-1];
						$("[name='cf_1965_"+dataselid+"']").val(response.productcode);
						$("[name='cf_1965_"+dataselid+"']").prop('readonly','true');
						$("[name='cf_1969_"+dataselid+"']").val(response.productunit);
						$("[name='cf_1969_"+dataselid+"']").prop('readonly','true');
						$("[name='cf_4872_"+dataselid+"']").val(response.ah);
						$("[name='cf_4872_"+dataselid+"']").prop('readonly','true');
						$("[name='cf_4874_"+dataselid+"']").val(response.category);
						$("[name='cf_4874_"+dataselid+"']").prop('readonly','true');
					}
					}
					});
			}
			if(referenceModule=='PurchaseReq' && sourcemodule=='RFQMaintain')
			{
			      $.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getRfqLineItem'},
				    dataType: 'json',
                    success:function(response)
                    {
						if(response.message==""){
							var message = "No Details Found for the Purchase Requisition";
							app.helper.showAlertNotification({'message': message});
							setTimeout(function(){ 	window.location.reload(); }, 1500);							
						}else{
						 $("#RFQMaintain_editView_fieldName_cf_1953").val(response.requisition_date);
						 $('input[name="cf_nrl_plantmaster353_id"]').val(response.plantid);
						 $('input[name="cf_nrl_plantmaster353_id_display"]').val(response.plantname);
						 $('input[name="cf_nrl_plantmaster353_id_display"]').attr("readonly","true");
						 $("#RFQMaintain_editView_fieldName_cf_nrl_plantmaster353_id_select").parent().remove();
						 $("#RFQMaintain_editView_fieldName_cf_nrl_plantmaster353_id_create").parent().remove();
						 $("#RFQ_Lineitem > tbody").empty();
						 $("#RFQ_Lineitem > tbody").html(response.message);
						 $("#totalRowCount_RFQ_Lineitem").val(response.rowcount);
						 $(".customPicklistSelect2").select2();
						 $("#tr_clone_add__RFQ_Lineitem").prop("disabled","true");
						 $('#directMode_RFQ_Lineitem').val('1');
						}
					}
				    });
			}


			if(referenceModule=='PurchaseReq' && sourcemodule=='PurchaseOrder')
			{
			 var vendorid = $('[name="vendor_id"]').val();
             var month = $('[name="cf_4300"]').val();
				 $.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, month:month, vendorid: vendorid, action: 'getPRLineItemforPO'},
				             dataType: 'json',
                    success:function(response)
                    {
						if(response.status!=0){
					     $("table#lineItemTab > tbody").empty();
						 $("table#lineItemTab > tbody").html(response.message);
						  $('#directMode_lineItemTab').val('1');
              $("[name=totalProductCount]").val(response.totalcount);
               $("#netTotal").html(response.amount);
             $("#preTaxTotal").html(response.amount);
             $("#grandTotal").html(response.amount);
			 $("#balance").val(response.amount);
						 $("#PurchaseOrder_editView_fieldName_cf_2756").val(response.requisition_date);
						 $("#PurchaseOrder_editView_fieldName_cf_2756").attr("readonly","true");
						 $("#PurchaseOrder_editView_fieldName_cf_2761").val(response.req_no);
						 $("#PurchaseOrder_editView_fieldName_cf_2761").attr("readonly","true");

						  $("#addProduct").attr("disabled","true");
						   $("#addService").attr("disabled","true");
						}else{

					var message = "PO has been created from the PR! Please select another one";
					app.helper.showAlertNotification({'message': message});
					setTimeout(function(){ 	window.location.reload(); }, 1500);


						}
					}
				    });
			}


			if(referenceModule=='StockRequisition' && sourcemodule=='PurchaseOrder')
			{
				 $.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getSTPRLineItemforPO'},
				    dataType: 'json',
                    success:function(response)
                    {
						
						if(response.message!=""){
					     $("table#lineItemTab > tbody").empty();
						 $("table#lineItemTab > tbody").html(response.message);
						 $('#directMode_lineItemTab').val('1');
						 $("#PurchaseOrder_editView_fieldName_cf_2758").val(response.str_date);
						 $("#PurchaseOrder_editView_fieldName_cf_2758").attr("readonly","true");
						 $("#PurchaseOrder_editView_fieldName_cf_2838").val(response.str_no);
						 $("#PurchaseOrder_editView_fieldName_cf_2838").attr("readonly","true");
						 
						 $("[name=cf_nrl_plantmaster950_id]").val(response.plant_id);
						 $("[name=cf_nrl_plantmaster950_id_display]").val(response.plant_display_name);
						 $("[name=cf_nrl_plantmaster950_id_display]").attr("readonly","true");
						 $("#PurchaseOrder_editView_fieldName_cf_nrl_plantmaster950_id_select").parent().remove();
						 $("#PurchaseOrder_editView_fieldName_cf_nrl_plantmaster950_id_create").parent().remove();
						 
						 $("[name=cf_nrl_plantmaster953_id]").val(response.from_plant_id);
						 $("[name=cf_nrl_plantmaster953_id_display]").val(response.from_plant_display_name);
						 $("[name=cf_nrl_plantmaster953_id_display]").attr("readonly","true");
						 $("#PurchaseOrder_editView_fieldName_cf_nrl_plantmaster953_id_select").parent().remove();
						 $("#PurchaseOrder_editView_fieldName_cf_nrl_plantmaster953_id_create").parent().remove();
						 
						 $("#addProduct").attr("disabled","true");
						 $("#addService").attr("disabled","true");
						 $("[name=totalProductCount]").val(response.totalcount);
						 $("#netTotal").html(response.amount);
						 $("#preTaxTotal").html(response.amount);
						 $("#grandTotal").html(response.amount);
						// $("#region_id").select2().select2("readonly",true);
						 $("#taxtype").select2().select2("readonly",true);
						 $("table#lineItemResult > tbody > tr:nth-child(2) > td:first").html('<div class="pull-right"><strong>Overall Discount</strong></div>');
						 //$("table#lineItemResult > tbody > tr:nth-child(5) > td:first").html('<div class="pull-right"><strong>Tax</strong></div>');
						 $("table#lineItemResult > tbody > tr:nth-child(6) > td:first").html('<div class="pull-right"><strong>Taxes On Charges</strong></div>');
						 $("table#lineItemResult > tbody > tr:nth-child(7) > td:first").html('<div class="pull-right"><strong>Deducted Taxes</strong></div>');
						 $("#adjustment").prop("readonly","true");
						}else{
							var message = "PO has been created from the STPR! Please select another one";
							app.helper.showAlertNotification({'message': message});
							setTimeout(function(){ 	window.location.reload(); }, 1500);
						}
					}
				    });
			}

			if(referenceModule=='AssemblyOrder' && sourcemodule=='InboundDelivery')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
                    data: {
					id: id, 
					action: 'getAssemblyLineItemforIBD'},
				    dataType: 'json',
                    success:function(response)
                    {
						$("table#Line_Item > tbody").empty();
						$("table#Line_Item > tbody").html(response.tbody);
						$('#directMode_Line_Item').val('1');
						$("#totalRowCount_Line_Item").val(response.totalcount);
						$("[name=cf_nrl_plantmaster269_id]").val(response.plantid);
						$("[name=cf_nrl_plantmaster269_id_display]").val(response.plantname);
						$("[name=cf_nrl_plantmaster269_id_display]").attr("readonly","true");
						$("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_select").parent().remove();
						$("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_create").parent().remove();
						$("[name=cf_2860]").val(response.plantcode);
						$("[name=cf_2860]").attr("readonly","true");
					  $("#tr_clone_add__Line_Item").attr("disabled","true");
					  var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();
					var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
					var year = d.getFullYear();
					var plant = response.plantid;
					$.ajax(
						{
						type:"post",
						url: "shirshaAjax.php",
						data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
						dataType: 'json',
						success:function(response)
						{
							var graceday = response.days;
							var chkval = response.fiscalval;
							if(chkval == '1')
							{
								var gday = parseInt(graceday) - parseInt(1);
								var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
								var pmonth = pd.getMonth()+1;
								var pday = pd.getDate();
								var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
								var minDate = new Date(prevday);
								$('input[name="cf_3200"]').datepicker('setStartDate', minDate);
								var maxDate = new Date(today);
								$('input[name="cf_3200"]').datepicker('setEndDate', maxDate);

							}
							else
							{
								var minDate = new Date(today);
								$('input[name="cf_3200"]').datepicker('setStartDate', minDate);

								var maxDate = new Date(today);
								$('input[name="cf_3200"]').datepicker('setEndDate', maxDate);
							}
						}
						});
					}
					});
			}

			if(referenceModule=='PurchaseOrder' && sourcemodule=='InboundDelivery')
			{
        var ref = $('[name="cf_3193"]').select2('data').id;
        if(ref=="" || ref==undefined){
          var message = "Please select Reference !!";
          app.helper.showAlertNotification({'message': message});
          setTimeout(function(){ 	window.location.reload(); }, 1500);
        }else{

          if(ref=='With respect to PO'){

				 $.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getPOLineItemforIBD'},
				    dataType: 'json',
                    success:function(response)
                    {
						if(response.message!=""){
						$("table#Line_Item > tbody").empty();
						$("table#Line_Item > tbody").html(response.message);
						$('#directMode_Line_Item').val('1');
						$("#totalRowCount_Line_Item").val(response.rowcount);
						$("[name=cf_2848]").val(response.pono);
						$("[name=cf_2848]").attr("readonly","true");
						$("[name=cf_2850]").val(response.podate);
						$("[name=cf_2850]").attr("readonly","true");
                        $("[name=cf_nrl_vendors866_id]").val(response.vendorid);
						$("[name=cf_nrl_vendors866_id_display]").val(response.vendorname);
						$("[name=cf_nrl_vendors866_id_display]").attr("readonly","true");
						$("[name=cf_2845]").val(response.vendorcode);
						$("[name=cf_2845]").attr("readonly","true");
						$("[name=cf_2862]").val(response.reference);
						$("[name=cf_2862]").attr("readonly","true");
						$("[name=cf_nrl_plantmaster269_id]").val(response.plantid);
						$("[name=cf_nrl_plantmaster269_id_display]").val(response.plantname);
						$("[name=cf_nrl_plantmaster269_id_display]").attr("readonly","true");
						$("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_select").parent().remove();
						$("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_create").parent().remove();
						$("#InboundDelivery_editView_fieldName_cf_nrl_vendors866_id_select").parent().remove();
						$("#InboundDelivery_editView_fieldName_cf_nrl_vendors866_id_create").parent().remove();
						$("[name=cf_2860]").val(response.plantcode);
						$("[name=cf_2860]").attr("readonly","true");
						$("#tr_clone_add__Line_Item").attr("disabled","true");
					  
						}else{
							var message = "IBD has been created from the PO! Please select another one";
							app.helper.showAlertNotification({'message': message});
							setTimeout(function(){ 	window.location.reload(); }, 1500);

						}
					}


				    });
          }else{

            $.ajax(
                  {
                  type:"post",
                  url: "arocrmAjax.php",
                  data: {id: id, action: 'getSTPOLineItemforIBD'},
                  dataType: 'json',
                  success:function(response)
                  {
               if(response.message!=""){
               $("table#Line_Item > tbody").empty();
               $("table#Line_Item > tbody").html(response.message);
               $('#directMode_Line_Item').val('1');
               $("#totalRowCount_Line_Item").val(response.rowcount);
               $("[name=cf_2848]").val(response.pono);
               $("[name=cf_2848]").attr("readonly","true");
               $("[name=cf_2850]").val(response.podate);
               $("[name=cf_2850]").attr("readonly","true");
			   $("[name=cf_nrl_vendors866_id]").val(response.vendorid);
               $("[name=cf_nrl_vendors866_id_display]").val(response.vendorname);
			   $("[name=cf_nrl_vendors866_id_display]").attr("readonly","true");
               $("#InboundDelivery_editView_fieldName_cf_nrl_vendors866_id_select").parent().remove();
               $("#InboundDelivery_editView_fieldName_cf_nrl_vendors866_id_create").parent().remove();
               $("[name=cf_2843]").attr("readonly","true");
               $("[name=cf_2845]").val(response.vendorcode);
               $("[name=cf_2845]").attr("readonly","true");
               $("[name=cf_2862]").val(response.reference);
               $("[name=cf_2862]").attr("readonly","true");
               $("[name=cf_nrl_plantmaster269_id]").val(response.plantid);
               $("[name=cf_nrl_plantmaster269_id_display]").val(response.plantname);
               $("[name=cf_nrl_plantmaster269_id_display]").attr("readonly","true");
               $("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_select").parent().remove();
               $("#InboundDelivery_editView_fieldName_cf_nrl_plantmaster269_id_create").parent().remove();
               $("[name=cf_2860]").val(response.plantcode);
               $("[name=cf_2860]").attr("readonly","true");
               $("#tr_clone_add__Line_Item").attr("disabled","true");
               }else{
                 var message = "No Line Item Found! Please select another one";
                 app.helper.showAlertNotification({'message': message});
                 setTimeout(function(){ 	window.location.reload(); }, 1500);
               }

             }




          });

          }
			}
    }


			if(referenceModule=='InboundDelivery' && sourcemodule=='QualityInspection')
			{
				 $.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getIBDItemforQI'},
				            dataType: 'json',
                    success:function(response)
                    {

          $("[name=cf_3650]").val(response.ibdno);
					$("[name=cf_3650]").attr("readonly","true");
					$("[name=cf_nrl_vendors825_id]").val(response.vendorid);
					$("[name=cf_nrl_vendors825_id_display]").val(response.vendorname);
					$("[name=cf_nrl_vendors825_id_display]").attr("readonly","true");
					$("[name=cf_2985]").val(response.vendorcode);
					$("[name=cf_2985]").attr("readonly","true");
					$("#QualityInspection_editView_fieldName_cf_nrl_vendors825_id_select").parent().remove();
					$("#QualityInspection_editView_fieldName_cf_nrl_vendors825_id_create").parent().remove();

          $("[name=cf_nrl_plantmaster114_id]").val(response.plantid);
					$("[name=cf_nrl_plantmaster114_id_display]").val(response.plantname);
					$("[name=cf_nrl_plantmaster114_id_display]").attr("readonly","true");
					$("[name=cf_2987]").val(response.plantcode);
					$("[name=cf_2987]").attr("readonly","true");
					$("#QualityInspection_editView_fieldName_cf_nrl_plantmaster114_id_select").parent().remove();
					$("#QualityInspection_editView_fieldName_cf_nrl_plantmaster114_id_create").parent().remove();
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();
					var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
					var year = d.getFullYear();
					var plant = response.plantid;
					$.ajax(
						{
						type:"post",
						url: "shirshaAjax.php",
						data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
						dataType: 'json',
						success:function(response)
						{
							var graceday = response.days;
							var chkval = response.fiscalval;
							if(chkval == '1')
							{
								var gday = parseInt(graceday) - parseInt(1);
								var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
								var pmonth = pd.getMonth()+1;
								var pday = pd.getDate();
								var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
								var minDate = new Date(prevday);
								$('input[name="cf_3227"]').datepicker('setStartDate', minDate);
								var maxDate = new Date(today);
								$('input[name="cf_3227"]').datepicker('setEndDate', maxDate);
							}
							else
							{
								var minDate = new Date(today);
								$('input[name="cf_3227"]').datepicker('setStartDate', minDate);

								var maxDate = new Date(today);
								$('input[name="cf_3227"]').datepicker('setEndDate', maxDate);
							}
						}
						});
					}
				    });
			}




      if(referenceModule=='OutboundDelivery' && sourcemodule=='QualityInspection')
      {
      $.ajax(
            {
            type:"post",
            url: "arocrmAjax.php",
            data: {id: id, action: 'getOBDItemforQI'},
            dataType: 'json',
            success:function(response)
            {
          $("[name=cf_nrl_vendors825_id]").val(response.vendorid);
          $("[name=cf_nrl_vendors825_id_display]").val(response.vendorname);
          $("[name=cf_nrl_vendors825_id_display]").attr("readonly","true");
          $("[name=cf_2985]").val(response.vendorcode);
          $("[name=cf_2985]").attr("readonly","true");
          $("#QualityInspection_editView_fieldName_cf_nrl_vendors825_id_select").parent().remove();
          $("#QualityInspection_editView_fieldName_cf_nrl_vendors825_id_create").parent().remove();

          $("[name=cf_nrl_plantmaster114_id]").val(response.plantid);
          $("[name=cf_nrl_plantmaster114_id_display]").val(response.plantname);
          $("[name=cf_nrl_plantmaster114_id_display]").attr("readonly","true");
          $("[name=cf_2987]").val(response.plantcode);
          $("[name=cf_2987]").attr("readonly","true");
          $("#QualityInspection_editView_fieldName_cf_nrl_plantmaster114_id_select").parent().remove();
          $("#QualityInspection_editView_fieldName_cf_nrl_plantmaster114_id_create").parent().remove();

          $('[name="cf_nrl_salesorder543_id"]').val(response.soid);
          $('[name="cf_nrl_salesorder543_id_display"]').val(response.soname);
          $('[name="cf_nrl_salesorder543_id_display"]').prop("readonly",true);
          $("#QualityInspection_editView_fieldName_cf_nrl_salesorder543_id_select").parent().remove();
          $("#QualityInspection_editView_fieldName_cf_nrl_salesorder543_id_create").parent().remove();

          $('[name="cf_nrl_accounts685_id"]').val(response.customerid);
          $('[name="cf_nrl_accounts685_id_display"]').val(response.customername);
          $('[name="cf_nrl_accounts685_id_display"]').prop("readonly",true);
          $("#QualityInspection_editView_fieldName_cf_nrl_accounts685_id_select").parent().remove();
          $("#QualityInspection_editView_fieldName_cf_nrl_accounts685_id_create").parent().remove();
		  var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();
					var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
					var year = d.getFullYear();
					var plant = response.plantid;
					$.ajax(
						{
						type:"post",
						url: "shirshaAjax.php",
						data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
						dataType: 'json',
						success:function(response)
						{
							var graceday = response.days;
							var chkval = response.fiscalval;
							if(chkval == '1')
							{
								var gday = parseInt(graceday) - parseInt(1);
								var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
								var pmonth = pd.getMonth()+1;
								var pday = pd.getDate();
								var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
								var minDate = new Date(prevday);
								$('input[name="cf_3227"]').datepicker('setStartDate', minDate);
								var maxDate = new Date(today);
								$('input[name="cf_3227"]').datepicker('setEndDate', maxDate);
							}
							else
							{
								var minDate = new Date(today);
								$('input[name="cf_3227"]').datepicker('setStartDate', minDate);

								var maxDate = new Date(today);
								$('input[name="cf_3227"]').datepicker('setEndDate', maxDate);
							}
						}
						});
          }
            });
      }

			if(referenceModule=='AssemblyOrder' && sourcemodule=='Assembly')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
                    data: {id: id, action: 'getAOItem'},
				    dataType: 'json',
                    success:function(response)
                    {
						$("table#Line_Item > tbody").empty();
						$("table#Line_Item > tbody").html(response.tbody);
						$("#totalRowCount_Line_Item").val(response.totalcount);
						$("input[name='cf_nrl_plantmaster837_id']").val(response.plantid);
						$("#cf_nrl_plantmaster837_id_display").val(response.plant);
						$("#directMode_Line_Item").val('1');
						$("#tr_clone_add__Line_Item").prop("disabled","true");
					}
					});
			}
						if(referenceModule=='PlantMaster' && sourcemodule=='Assembly')
			{
				var yr = $('select[name="cf_4633"]').val();
		var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4635"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_5144"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_5144"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_5144"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_5144"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule=='PlantMaster' && sourcemodule=='AssemblyOrder')
			{
				var yr = $('select[name="cf_4633"]').val();
		var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4635"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_5112"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_5112"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_5112"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_5112"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
			}
			if(referenceModule=='AssemblyOrder' && sourcemodule=='OutboundDelivery')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "shirshaAjax.php",
                    data: {id: id, action: 'getAOLineItemforOBD'},
				            dataType: 'json',
                    success:function(response)
                    {
						$("table#Line_Item > tbody").empty();
						$("table#Line_Item > tbody").html(response.tbody);
						$("#totalRowCount_Line_Item").val(response.totalcount);
						$("input[name='cf_nrl_plantmaster625_id']").val(response.plantid);
						$("#cf_nrl_plantmaster625_id_display").val(response.plant);
						$("#directMode_Line_Item").val('1');
						$("#tr_clone_add__Line_Item").prop("disabled","true");
						var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
				var year = d.getFullYear();
				var plant = response.plantid;
				$.ajax(
					{
					type:"post",
					url: "shirshaAjax.php",
					data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
					dataType: 'json',
					success:function(response)
					{
						var graceday = response.days;
						var chkval = response.fiscalval;
						if(chkval == '1')
						{
							var gday = parseInt(graceday) - parseInt(1);
							var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
							var pmonth = pd.getMonth()+1;
							var pday = pd.getDate();
							var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;

							var minDate = new Date(prevday);
							var maxDate = new Date(today);

							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
						else
						{
							var minDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);

							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
					}
					});

					}
					});
			}

			if(referenceModule=='SalesOrder' && sourcemodule=='OutboundDelivery')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getSOLineItemforOBD'},
				            dataType: 'json',
                    success:function(response)
                    {
                if(response.savestatestatus==0){
                localStorage.setItem('savestatestatus',0);
                $('.saveButton').prop("disabled",true);
                }else{
                localStorage.setItem('savestatestatus',1);
                $('.saveButton').prop("disabled",false);
                }

					if(response.srvresponse==0){
					var message = "OBD already done for the SO !";
					app.helper.showAlertNotification({'message': message});
					setTimeout(function(){ 	window.location.reload(); }, 1500);
					}else{
					
					$('input[name="cf_nrl_plantmaster625_id"]').val(response.plantid);
					$('input[name="cf_nrl_plantmaster625_id_display"]').val(response.plantname);
					$('input[name="cf_nrl_plantmaster625_id_display"]').attr('readonly','true');
					$('#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster625_id_select').parent().remove();
					$('#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster625_id_create').parent().remove();
					
					$('input[name="cf_nrl_accounts599_id"]').val(response.customerid);
					$('input[name="cf_nrl_accounts599_id_display"]').val(response.customername);
					$('input[name="cf_nrl_accounts599_id_display"]').attr('readonly','true');
					$('#OutboundDelivery_editView_fieldName_cf_nrl_accounts599_id_select').parent().remove();
					$('#OutboundDelivery_editView_fieldName_cf_nrl_accounts599_id_create').parent().remove();
					
					
					$("table#Line_Item > tbody").empty();
					$("table#Line_Item > tbody").html(response.message);
					$("#totalRowCount_Line_Item").val(response.rowcount);
					$("#directMode_Line_Item").val('1');
					$("#tr_clone_add__Line_Item").prop("disabled","true");
					}
					var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
				var year = d.getFullYear();
				var plant = response.delplantid;
				$.ajax(
					{
					type:"post",
					url: "shirshaAjax.php",
					data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
					dataType: 'json',
					success:function(response)
					{
						var graceday = response.days;
						var chkval = response.fiscalval;
						if(chkval == '1')
						{
							var gday = parseInt(graceday) - parseInt(1);
							var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
							var pmonth = pd.getMonth()+1;
							var pday = pd.getDate();
							var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
							var minDate = new Date(prevday);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);
							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
						else
						{
							var minDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);

							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
					}
					});

					}
				    });
			}


      if(referenceModule=='PurchaseOrder' && sourcemodule=='OutboundDelivery')
      {
        $.ajax(
                  {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getPOLineItemforOBDWSTPO'},
                    dataType: 'json',
                    success:function(response)
                    {
         if(response.srvresponse==0){
         var message = "OBD already done for the SO !";
          app.helper.showAlertNotification({'message': message});
          setTimeout(function(){ 	window.location.reload(); }, 1500);
        }else{
          $("table#Line_Item > tbody").empty();
          $("table#Line_Item > tbody").html(response.message);
          $("#totalRowCount_Line_Item").val(response.rowcount);
          $("#directMode_Line_Item").val('1');
          $("#tr_clone_add__Line_Item").prop("disabled","true");

          $('[name="cf_nrl_plantmaster625_id"]').val(response.delfromplantid);
          $('[name="cf_nrl_plantmaster625_id_display"]').val(response.delfromplantname);
		  
		   $('[name="cf_nrl_plantmaster574_id"]').val(response.delplantid);
          $('[name="cf_nrl_plantmaster574_id_display"]').val(response.delplantname);
		  
          $('[name="cf_nrl_plantmaster625_id_display"]').prop("readonly",true);
          $("#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster625_id_select").parent().remove();
          $("#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster625_id_create").parent().remove();
		  
		  $('[name="cf_nrl_plantmaster574_id_display"]').prop("readonly",true);
          $("#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster574_id_select").parent().remove();
          $("#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster574_id_create").parent().remove();
        }
		var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
				var year = d.getFullYear();
				var plant = response.delplantid;
				$.ajax(
					{
					type:"post",
					url: "shirshaAjax.php",
					data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
					dataType: 'json',
					success:function(response)
					{
						var graceday = response.days;
						var chkval = response.fiscalval;
						if(chkval == '1')
						{
							var gday = parseInt(graceday) - parseInt(1);
							var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
							var pmonth = pd.getMonth()+1;
							var pday = pd.getDate();
							var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
							var minDate = new Date(prevday);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);
							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
						else
						{
							var minDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);

							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
					}
					});
          }
            });
      }

			if(referenceModule=='Products' && sourcemodule=='QualityInspection')
			{
				var ref = $('[name="cf_3071"]').select2('data').id;
				if(ref=='With respect to Outbound Delivery'){
				var obdno = $('[name="cf_nrl_outbounddelivery220_id"]').val();
				if(obdno!=""){
				app.helper.showProgress();
				$.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, obdno:obdno, action: 'getLineItemforQIWOBD'},
				            dataType: 'json',
                    success:function(response)
                    {
				  var maxrct = response.maxrowcount;
				if(maxrct==undefined ||  maxrct==null ||  maxrct=='' || maxrct==0){
				maxrct = 0;
				}
					if(maxrct!=0){


					$("[name=cf_2981]").val(response.productcode);
					$("[name=cf_2981]").attr("readonly","true");

					$("[name='cf_1675']").prop("max",parseInt(maxrct));
					$("[name='cf_1675']").val(parseInt(maxrct));
					$("[name='cf_1675']").prop("min",1);
					
					
				
					$("[name='cf_4919']").val(parseInt(maxrct));
					$("[name='cf_4919']").prop("readonly","true");

					$("[name='cf_1677']").prop("max",parseInt(maxrct));
					$("[name='cf_1677']").val(parseInt(maxrct));
					$("[name='cf_1677']").prop("readonly","true");

					$("table#Quality_Inspection_Lineitem > tbody").html('');
					$("table#Quality_Inspection_Lineitem > tbody").html(response.message);
					$("#totalRowCount_Quality_Inspection_Lineitem").val(response.rowcount);
					$('#directMode_Quality_Inspection_Lineitem').val('1');
					$('#tr_clone_add__Quality_Inspection_Lineitem').prop("disabled",true);
					$('.optionselect2').select2();

					}else{
					var message = "Quality Inspection already done for the following OBD and Product";
					app.helper.showAlertNotification({'message': message});
					setTimeout(function(){ 	window.location.reload(); }, 1500);
					}
					app.helper.hideProgress();
					}
				    });
				}else{
				    var message = "Please select OBD !!";
					app.helper.showAlertNotification({'message': message});
				    setTimeout(function(){ 	window.location.reload(); }, 1500);
				}
				}else{
				var ibdno = $('[name="cf_nrl_inbounddelivery39_id"]').val();
				if(ibdno!=""){
				app.helper.showProgress();
				$.ajax(
                    {
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, ibdno:ibdno, action: 'getProductCodeforQI'},
				            dataType: 'json',
                    success:function(response)
                    {
						
					var rwc = parseInt(response.maxrowcount);
					 if(rwc!=0){ 
					$("[name=cf_2981]").val(response.productcode);
					$("[name=cf_2981]").attr("readonly","true");

					$("[name='cf_1675']").prop("max",parseInt(response.maxrowcount));
					$("[name='cf_1675']").prop("min",1);
					$("[name='cf_1675']").val(parseInt(response.maxrowcount));
					
					$("[name='cf_4919']").val(parseInt(rwc));
					$("[name='cf_4919']").prop("readonly","true");

					$("[name='cf_1677']").prop("max",parseInt(response.maxrowcount));
					$("[name='cf_1677']").val(parseInt(response.maxrowcount));
					$("[name='cf_1677']").prop("readonly","true");

					$("table#Quality_Inspection_Lineitem > tbody").html('');
					$("table#Quality_Inspection_Lineitem > tbody").html(response.message);
					$("#totalRowCount_Quality_Inspection_Lineitem").val(response.rowcount);
					$('#directMode_Quality_Inspection_Lineitem').val('1');
					$('#tr_clone_add__Quality_Inspection_Lineitem').prop("disabled",true);
					$('.optionselect2').select2();
					}else{
					var message = "Quality Inspection already done for the following IBD and Product";
					app.helper.showAlertNotification({'message': message});
					setTimeout(function(){ 	window.location.reload(); }, 1500);
                     } 
					 app.helper.hideProgress();
					}
				    });
				}else{

					var message = "Please select IBD !!";
					app.helper.showAlertNotification({'message': message});
				    setTimeout(function(){ 	window.location.reload(); }, 1500);

				}
				}
			}

			if(referenceModule=='InboundDelivery' && sourcemodule=='GoodsReceipt')
			{
				
			        $.ajax({
                    type:"post",
                    url: "arocrmAjax.php",
                    data: { id: id, action: 'getDetailsIBDforGR'},
				    dataType: 'json',
                    success:function(response)
                       {
					
					if(response.message!=""){
					$("[name=cf_nrl_purchaseorder383_id]").val(response.poid);
					$("[name=cf_nrl_purchaseorder383_id_display]").val(response.poname);
					$("[name=cf_nrl_purchaseorder383_id_display]").attr("readonly","true");
					$("#GoodsReceipt_editView_fieldName_cf_nrl_purchaseorder383_id_select").parent().remove();
	                
					$("[name=cf_1882]").val(response.vehicleno);
					$("[name=cf_1882]").attr("readonly",true);
					
					$("[name=cf_1841]").val(response.modeoftransfer);
					$("[name=cf_1841]").attr("readonly",true);
					
					$("[name=cf_1845]").val(response.invoiceno);
					$("[name=cf_1845]").attr("readonly",true);
					
					$("[name=cf_1880]").val(response.waybillno);
					$("[name=cf_1880]").attr("readonly",true);
					
					$("[name=cf_1886]").val(response.awbno);
					$("[name=cf_1886]").attr("readonly",true);
					
					$("[name=cf_1890]").val(response.billofentry);
					$("[name=cf_1890]").attr("readonly",true);
					
					$("[name=cf_1888]").val(response.cnnumber);
					$("[name=cf_1888]").attr("readonly",true);
					
					if(response.invoicedate=="--"){
						response.invoicedate = "";
					}
					$("[name=cf_1843]").val(response.invoicedate);
					$("[name=cf_1843]").datepicker('remove');
					$("[name=cf_1843]").attr('readonly',true);
					
					$("[name=cf_nrl_plantmaster388_id]").val(response.plantid);
					$("[name=cf_nrl_plantmaster388_id_display]").val(response.plantname);
					$("[name=cf_nrl_plantmaster388_id_display]").attr("readonly","true");
					$("#GoodsReceipt_editView_fieldName_cf_nrl_plantmaster388_id_select").parent().remove();
					$("#GoodsReceipt_editView_fieldName_cf_nrl_plantmaster388_id_create").parent().remove();

					$("[name=cf_nrl_vendors538_id]").val(response.vendorid);
					$("[name=cf_nrl_vendors538_id_display]").val(response.vendorname);
					$("[name=cf_nrl_vendors538_id_display]").attr("readonly","true");
					$("#GoodsReceipt_editView_fieldName_cf_nrl_vendors538_id_select").parent().remove();
					$("#GoodsReceipt_editView_fieldName_cf_nrl_vendors538_id_create").parent().remove();

					$('table#Line_Item_Details > tbody').html('');
					$('table#Line_Item_Details > tbody').html(response.message);
					$('#tr_clone_add__Line_Item_Details').prop("disabled","true");
					$('#totalRowCount_Line_Item_Details').val(response.rowcount);
					$('#directMode_Line_Item_Details').val('1');
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();
					var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
					var year = d.getFullYear();
					var plant = response.plantid;
					$.ajax(
						{
						type:"post",
						url: "shirshaAjax.php",
						data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
						dataType: 'json',
						success:function(response)
						{
							var graceday = response.days;
							var chkval = response.fiscalval;
							if(chkval == '1')
							{
								var gday = parseInt(graceday) - parseInt(1);
								var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
								var pmonth = pd.getMonth()+1;
								var pday = pd.getDate();
								var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
								var minDate = new Date(prevday);
								$('input[name="cf_3223"]').datepicker('setStartDate', minDate);
								var maxDate = new Date(today);
								$('input[name="cf_3223"]').datepicker('setEndDate', maxDate);
							}
							else
							{
								var minDate = new Date(today);
								$('input[name="cf_3223"]').datepicker('setStartDate', minDate);

								var maxDate = new Date(today);
								$('input[name="cf_3223"]').datepicker('setEndDate', maxDate);
							}
						}
						});
					   }else{
						  var mess = "Quality Inspection Not Done for the IBD !!";
                          app.helper.showAlertNotification({'message': mess});
                          setTimeout(function(){ 	window.location.reload(); }, 1500);						  
					   }
					   
					   }
				        });
			}




      if(referenceModule=='PurchaseOrder' && sourcemodule=='GoodsReceipt')
      {
              $.ajax({
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getDetailsPOforGR'},
                    dataType: 'json',
                    success:function(response)
                       {

          $("[name=cf_nrl_plantmaster388_id]").val(response.plantid);
          $("[name=cf_nrl_plantmaster388_id_display]").val(response.plantname);
          $("[name=cf_nrl_plantmaster388_id_display]").attr("readonly","true");
          $("#GoodsReceipt_editView_fieldName_cf_nrl_plantmaster388_id_select").parent().remove();
          $("#GoodsReceipt_editView_fieldName_cf_nrl_plantmaster388_id_create").parent().remove();

          $("[name=cf_nrl_vendors538_id]").val(response.vendorid);
          $("[name=cf_nrl_vendors538_id_display]").val(response.vendorname);
          $("[name=cf_nrl_vendors538_id_display]").attr("readonly","true");
          $("#GoodsReceipt_editView_fieldName_cf_nrl_vendors538_id_select").parent().remove();
          $("#GoodsReceipt_editView_fieldName_cf_nrl_vendors538_id_create").parent().remove();

          $('table#Line_Item_Details > tbody').html('');
          $('table#Line_Item_Details > tbody').html(response.message);
          $('#tr_clone_add__Line_Item_Details').prop("disabled","true");
          $('#totalRowCount_Line_Item_Details').val(response.rowcount);
          $('#directMode_Line_Item_Details').val('1');
          var d = new Date();
          var month = d.getMonth()+1;
          var day = d.getDate();
          var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
          var year = d.getFullYear();
          var plant = response.plantid;
          $.ajax(
            {
            type:"post",
            url: "shirshaAjax.php",
            data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
            dataType: 'json',
            success:function(response)
            {
              var graceday = response.days;
              var chkval = response.fiscalval;
              if(chkval == '1')
              {
                var gday = parseInt(graceday) - parseInt(1);
                var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
                var pmonth = pd.getMonth()+1;
                var pday = pd.getDate();
                var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
                var minDate = new Date(prevday);
                $('input[name="cf_3223"]').datepicker('setStartDate', minDate);
                var maxDate = new Date(today);
                $('input[name="cf_3223"]').datepicker('setEndDate', maxDate);
              }
              else
              {
                var minDate = new Date(today);
                $('input[name="cf_3223"]').datepicker('setStartDate', minDate);

                var maxDate = new Date(today);
                $('input[name="cf_3223"]').datepicker('setEndDate', maxDate);
              }
            }
            });
             }
                });
      }



      if(referenceModule=='PurchaseReturnOrder' && sourcemodule=='OutboundDelivery')
      {
                      $.ajax({
                      type:"post",
                      url: "arocrmAjax.php",
                      data: {id: id, action: 'getRPODetailsforOBD'},
                      dataType: 'json',
                      success:function(response)
                         {

                           $("[name=cf_nrl_plantmaster625_id]").val(response.plantid);
                           $("[name=cf_nrl_plantmaster625_id_display]").val(response.plantname);
                           $("[name=cf_nrl_plantmaster625_id_display]").attr("readonly",true);
                           $("#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster625_id_select").parent().remove();
                           $("#OutboundDelivery_editView_fieldName_cf_nrl_plantmaster625_id_create").parent().remove();

                           $("[name=cf_nrl_vendors417_id]").val(response.vendorid);
                           $("[name=cf_nrl_vendors417_id_display]").val(response.vendorname);
                           $("[name=cf_nrl_vendors417_id_display]").attr("readonly",true);
                           $("#OutboundDelivery_editView_fieldName_cf_nrl_vendors417_id_select").parent().remove();
                           $("#OutboundDelivery_editView_fieldName_cf_nrl_vendors417_id_create").parent().remove();

                           $('table#Line_Item > tbody').html('');
                           $('table#Line_Item > tbody').html(response.message);
                           $('#tr_clone_add__Line_Item').prop("disabled",true);
                           $('#totalRowCount_Line_Item').val(response.rowcount);
                           $('#directMode_Line_Item').val('1');
						   var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
				var year = d.getFullYear();
				var plant = response.plantid;
				$.ajax(
					{
					type:"post",
					url: "shirshaAjax.php",
					data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
					dataType: 'json',
					success:function(response)
					{
						var graceday = response.days;
						var chkval = response.fiscalval;
						if(chkval == '1')
						{
							var gday = parseInt(graceday) - parseInt(1);
							var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
							var pmonth = pd.getMonth()+1;
							var pday = pd.getDate();
							var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
							var minDate = new Date(prevday);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);
							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
						else
						{
							var minDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setStartDate', minDate);

							var maxDate = new Date(today);
							$('input[name="cf_3225"]').datepicker('setEndDate', maxDate);
						}
					}
					});

                         }
                           });
      }
	  if(referenceModule=='PlantMaster' && sourcemodule=='PurchaseOrder')
      {
		  var yr = $('select[name="cf_4605"]').val();
							var month = $('select[name="cf_4607"]').val();
	var d = new Date();
	var curmonth = d.getMonth()+1;
	var curday = d.getDate();
	var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
	var curyear = d.getFullYear();
	var y = yr.split(" - ");
	var fstyr = y[0];
	var lstyr = y[1];
	if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3653"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3653"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3653"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3653"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
	  }
	  if(referenceModule=='PlantMaster' && sourcemodule=='PurchaseReturnOrder')
      {
		  var yr = $('select[name="cf_4623"]').val();
		var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					var month = $('select[name="cf_4625"]').val();
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_3372"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_3372"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_3372"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_3372"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});
	  }
     

	 if(referenceModule=='Invoice' && sourcemodule=='PurchaseReturnOrder')
      {
              $.ajax({
                    type:"post",
                    url: "arocrmAjax.php",
                    data: {id: id, action: 'getINVDetailsforPReturn'},
                    dataType: 'json',
                    success:function(response)
                       {
	
		
          $("[name=cf_nrl_plantmaster447_id]").val(response.plantid);
          $("[name=cf_nrl_plantmaster447_id_display]").val(response.plantname);
          $("[name=cf_nrl_plantmaster447_id_display]").attr("readonly",true);
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_plantmaster447_id_select").parent().remove();
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_plantmaster447_id_create").parent().remove();

          $("[name=cf_nrl_vendors780_id]").val(response.vendorid);
          $("[name=cf_nrl_vendors780_id_display]").val(response.vendorname);
          $("[name=cf_nrl_vendors780_id_display]").attr("readonly",true);
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_vendors780_id_select").parent().remove();
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_vendors780_id_create").parent().remove();


          $("[name=cf_nrl_goodsreceipt248_id]").val(response.grid);
          $("[name=cf_nrl_goodsreceipt248_id_display]").val(response.grname);
          $("[name=cf_nrl_goodsreceipt248_id_display]").attr("readonly",true);
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_goodsreceipt248_id_select").parent().remove();
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_goodsreceipt248_id_create").parent().remove();
		  
		  $("[name=cf_nrl_purchaseorder809_id]").val(response.poid);
          $("[name=cf_nrl_purchaseorder809_id_display]").val(response.poname);
          $("[name=cf_nrl_purchaseorder809_id_display]").attr("readonly",true);
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_purchaseorder809_id_select").parent().remove();
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_purchaseorder809_id_create").parent().remove();

          $("[name=cf_nrl_inbounddelivery180_id]").val(response.delvid);
          $("[name=cf_nrl_inbounddelivery180_id_display]").val(response.delvname);
          $("[name=cf_nrl_inbounddelivery180_id_display]").attr("readonly",true);
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_inbounddelivery180_id_select").parent().remove();
          $("#PurchaseReturnOrder_editView_fieldName_cf_nrl_inbounddelivery180_id_create").parent().remove();
		  
		  $('[name="cf_1797"]').val(response.receiptdate);
		  $('[name="cf_1797"]').datepicker('remove');
		  $('[name="cf_1797"]').attr('readonly',true);
			
          $('table#Line_Item > tbody').html('');
          $('table#Line_Item > tbody').html(response.message);
          $('#tr_clone_add__Line_Item').prop("disabled",true);
          $('#totalRowCount_Line_Item').val(response.rowcount);
          $('#directMode_Line_Item').val('1');
		  	   var d = new Date();
				var month = d.getMonth()+1;
				var day = d.getDate();
				var today = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
				var year = d.getFullYear();
				var plant = response.plantid;
				$.ajax(
					{
					type:"post",
					url: "shirshaAjax.php",
					data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
					dataType: 'json',
					success:function(response)
					{
						var graceday = response.days;
						var chkval = response.fiscalval;
						if(chkval == '1')
						{
							var gday = parseInt(graceday) - parseInt(1);
							var pd = new Date(d.setDate(d.getDate()-parseInt(gday)));
							var pmonth = pd.getMonth()+1;
							var pday = pd.getDate();
							var prevday = pd.getFullYear() + '-' + ((''+pmonth).length<2 ? '0' : '') + pmonth + '-' + ((''+pday).length < 2 ? '0' : '') + pday;
							var minDate = new Date(prevday);
							$('input[name="cf_3372"]').datepicker('setStartDate', minDate);
							var maxDate = new Date(today);
							$('input[name="cf_3372"]').datepicker('setEndDate', maxDate);
						}
						else
						{
							var minDate = new Date(today);
							$('input[name="cf_3372"]').datepicker('setStartDate', minDate);

							var maxDate = new Date(today);
							$('input[name="cf_3372"]').datepicker('setEndDate', maxDate);
						}
					}
					});
             }
                });
      }

			/*if(referenceModule=='InboundDelivery' && sourcemodule=='PurchasePayment'){
			$.ajax(
				{
				type:"post",
				url: "rahulAjax.php",
				data: {id: id, action: 'getInBoundDetails'},
				dataType: 'json',
				success:function(response)
				{
					$("input[name='cf_3032']").val(response.invdate);
					$("input[name='cf_3032']").attr('readonly','true');
					$("input[name='cf_3034']").val(response.invno);
					$("input[name='cf_3034']").attr('readonly','true');
				}
				});
			}
			if(referenceModule=='GoodsReceipt' && sourcemodule=='PurchasePayment'){
			$.ajax(
				{
				type:"post",
				url: "rahulAjax.php",
				data: {id: id, action: 'getGRDetails'},
				dataType: 'json',
				success:function(response)
				{
					$("input[name='cf_3028']").val(response.grdate);
					$("input[name='cf_3028']").attr('readonly','true');
				}
				});
			}
			if(referenceModule=='PurchaseInvoice' && sourcemodule=='PurchasePayment'){
			$.ajax(
				{
				type:"post",
				url: "rahulAjax.php",
				data: {id: id, action: 'getInvoiceDetails'},
				dataType: 'json',
				success:function(response)
				{
					$("input[name='cf_3036']").val(response.grdate);
					$("input[name='cf_3036']").attr('readonly','true');
				}
				});
			}*/

			if(referenceModule=='RouteMaster' && sourcemodule=='JourneyPlan')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "rahulAjax.php",
                    data: {id: id, action: 'getRouteDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						var directmode = $('#directMode_Basic_Details').val();
						var lineitemrow = $('#totalRowCount_Basic_Details').val();
						var count = lineitemrow.split(",");
						var rownum = count.length;
						if(rownum == 1 && (recordid == undefined || recordid == "") && directmode == 0)
						{
							var date = $('input[name="cf_1962"]').val();
							var route = $('input[name="cf_nrl_routemaster499_id_display"]').val();

							if($('input[name="cf_nrl_routemaster499_id"]').val() == id)
							{
								$('input[name="cf_1988"]').val(response.type);
								$('input[name="cf_2000"]').val(response.distance);
								$('input[name="cf_1988"]').prop('readonly','true');
								$('input[name="cf_2000"]').prop('readonly','true');
								$('input[name="cf_3597"]').val(date);
								$('input[name="cf_3599"]').val(route);
								$('input[name="cf_3601"]').val(response.type);
								$('input[name="cf_3597"]').prop('readonly','true');
								$('input[name="cf_3599"]').prop('readonly','true');
								$('input[name="cf_3601"]').prop('readonly','true');

							}
						}
						else
						{
						for(var i=0;i<rownum;i++)
						{
							var date = $('input[name="cf_1962_'+count[i]+'"]').val();
							var route = $('input[name="cf_nrl_routemaster499_id_display_'+count[i]+'"]').val();

							if($('#cf_nrl_routemaster499_id_'+count[i]).val() == id)
							{
								$('input[name="cf_1988_'+count[i]+'"]').val(response.type);
								$('input[name="cf_2000_'+count[i]+'"]').val(response.distance);
								$('input[name="cf_1988_'+count[i]+'"]').prop('readonly','true');
								$('input[name="cf_2000_'+count[i]+'"]').prop('readonly','true');
								$('input[name="cf_3597_'+count[i]+'"]').val(date);
								$('input[name="cf_3599_'+count[i]+'"]').val(route);
								$('input[name="cf_3601_'+count[i]+'"]').val(response.type);
								$('input[name="cf_3597_'+count[i]+'"]').prop('readonly','true');
								$('input[name="cf_3599_'+count[i]+'"]').prop('readonly','true');
								$('input[name="cf_3601_'+count[i]+'"]').prop('readonly','true');
							}
						}
						}
					}
					});
			}
			if(referenceModule=='SerialNumber' && sourcemodule=='ServiceContracts')
			{
				$.ajax(
                    {
                    type:"post",
                    url: "rahulAjax.php",
                    data: {id: id, action: 'getAllDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						$('input[name="cf_2989"]').val(response.manufacturingdate);
						$('input[name="cf_2971"]').val(response.sellingdate);
						$('input[name="cf_2969"]').val(response.customer);
						$('input[name="cf_2973"]').val(response.product);
						$('input[name="cf_3124"]').val(response.sellingtime);
						$('input[name="cf_3420"]').val(response.guaranteetime);
						$('input[name="cf_3126"]').val(response.warrantytime);
						$('input[name="cf_2989"]').prop('readonly','true');
						$('input[name="cf_2971"]').prop('readonly','true');
						$('input[name="cf_2969"]').prop('readonly','true');
						$('input[name="cf_2973"]').prop('readonly','true');
						$('input[name="cf_3124"]').prop('readonly','true');
						$('input[name="cf_3126"]').prop('readonly','true');
						var sellperiod = response.sellingtime;
						var warrantyperiod = response.guaranteetime;
						var warranty = response.warrantytime;
						var manufacturingdate = response.manufacturingdate;
						var makedate = new Date(manufacturingdate.split('-')[2],manufacturingdate.split('-')[1]-1,manufacturingdate.split('-')[0]);
						var totalmonth = parseInt(sellperiod) + parseInt(warrantyperiod) + parseInt(warranty);
						makedate.setMonth(makedate.getMonth() + totalmonth);
						var dd = makedate.getDate();
						 if(dd<10)
						 {
							dd = '0'+dd;
						 }
						 var mm = makedate.getMonth() + 1;
						 if(mm<10)
						 {
							mm = '0'+mm;
						 }
						 var y = makedate.getFullYear();
						 var actual = dd + '-' + mm + '-' + y;
						 $('input[name="cf_2977"]').val(actual);
						 $('input[name="cf_2977"]').prop('readonly',true);
						 var sellingdate = response.sellingdate;
						 var selldate = new Date(sellingdate.split('-')[2],sellingdate.split('-')[1]-1,sellingdate.split('-')[0]);
						 selldate.setMonth(selldate.getMonth() + parseInt(warrantyperiod) + parseInt(warranty));
						var dd = selldate.getDate();
						if(dd<10)
						{
							dd = '0'+dd;
						}
						var mm = selldate.getMonth() + 1;
						if(mm<10)
						{
							mm = '0'+mm;
						}
						var y = selldate.getFullYear();
						var customerexpirydate = dd + '-' + mm + '-' + y;
						$('input[name="cf_2975"]').val(customerexpirydate);
						$('input[name="cf_2975"]').prop('readonly',true);

						 var extenddate = new Date(sellingdate.split('-')[2],sellingdate.split('-')[1]-1,sellingdate.split('-')[0]);
						extenddate.setMonth(extenddate.getMonth() + parseInt(warrantyperiod));
						var dd = extenddate.getDate();
						if(dd<10)
						{
							dd = '0'+dd;
						}
						var mm = extenddate.getMonth() + 1;
						if(mm<10)
						{
							mm = '0'+mm;
						}
						var y = extenddate.getFullYear();
						var customerexpire = dd + '-' + mm + '-' + y;

						 var fullDate = new Date();
						var twoDigitMonth = (fullDate.getMonth()<10)? '0' + (fullDate.getMonth()+1):(fullDate.getMonth()+1);
						var currentDate = fullDate.getDate() + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
						var customerexpiry = new Date(customerexpirydate.split('-')[2],customerexpirydate.split('-')[1]-1,customerexpirydate.split('-')[0]);
						var actualexpiry = new Date(actual.split('-')[2],actual.split('-')[1]-1,actual.split('-')[0]);
						var customerextend = new Date(customerexpire.split('-')[2],customerexpire.split('-')[1]-1,customerexpire.split('-')[0]);
						if(fullDate>customerexpiry)
						{
							var warrantystatus = "Expired";
						}
						else if(fullDate>customerextend && customerexpiry>fulldate)
						{
							var warrantystatus = "Extended";
						}
						else
						{
							if(customerexpiry>actualexpiry)
							{
								var warrantystatus = "Inactive";
							}
							else
							{
								var warrantystatus = "Active";
							}
						}
						var selectedValue = warrantystatus;
						$('select[name="contract_status"]').select2('data', { id: selectedValue, text: selectedValue});
					}
				});
			}
			if(referenceModule=='PlantMaster' && sourcemodule=='SalesBudget')
			{
					var yr = $('select[name="cf_3424"]').val();
					var month = $('select[name="cf_1493"]').val();
					var d = new Date();
					var curmonth = d.getMonth()+1;
					var curday = d.getDate();
					var today = d.getFullYear() + '-' + ((''+curmonth).length<2 ? '0' : '') + curmonth + '-' + ((''+curday).length<2 ? '0' : '') + curday;
					var curyear = d.getFullYear();
					var y = yr.split(" - ");
					var fstyr = y[0];
					var lstyr = y[1];
					if(month == 'January' || month == 'February' || month == 'March')
					{
						var year = lstyr;
					}
					else
					{
						var year = fstyr;
					}
					var plant = id;
					$.ajax(
							{
								type:"post",
								url: "shirshaAjax.php",
								data: {year: year, month: month, action: 'getAllDays'},
								dataType: 'json',
								success:function(response)
								{
									var day = response.days;
									var m = response.month;
									var fchar = m.substring(0,1);
									if(fchar == '0')
									{
										var lastChar = m[m.length -1];
										if(lastChar == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									else
									{
										if(m == curmonth && year == curyear)
										{
											maxday = today;
											var day = ((''+curday).length<2 ? '0' : '') + curday;
										}
										else
										{
											var maxday = year + '-' + m + '-' + day;
										}
									}
									$.ajax(
										{
										type:"post",
										url: "shirshaAjax.php",
										data: {plant : plant, year: year, month: month, module: sourcemodule, action: 'getFiscalDetails'},
										dataType: 'json',
										success:function(response)
										{
											var graceday = response.days;
											var chkval = response.fiscalval;
											if(chkval == '1')
											{
													var gday = parseInt(graceday) - parseInt(1);
													var pday = parseInt(day) - parseInt(gday);
													var minusday = pday.toString().substr(0,1);
													if(minusday == '-' || minusday == '0')
													{
														var minday = year + '-' + m + '-01';
													}
													else
													{
														pday = ((''+pday).length<2 ? '0' : '') + pday;
														var minday = year + '-' + m + '-' + pday;
													}
													var minDate = new Date(minday);
													$('input[name="cf_4782"]').datepicker('setStartDate', minDate);
													var maxDate = new Date(maxday);
													$('input[name="cf_4782"]').datepicker('setEndDate', maxDate);

											}
											else
											{
												var minDate = new Date(today);
												$('input[name="cf_4782"]').datepicker('setStartDate', minDate);

												var maxDate = new Date(today);
												$('input[name="cf_4782"]').datepicker('setEndDate', maxDate);
											}
										}
										});

						}
						});	
						}
					
			
			if(referenceModule=='Accounts' && sourcemodule=='SalesBudget')
			{
				var branch = $('[name="cf_nrl_plantmaster615_id"]').val();
				var fiscalyear = $('[name="cf_3424"]').val();
				$.ajax(
                    {
                    type:"post",
                    url: "rahulAjax.php",
                    data: {id: id, branch:branch, fiscalyear:fiscalyear, action: 'getCustomerAllDetails'},
				            dataType: 'json',
                    success:function(response)
                    {
						$('[name="cf_3473"]').val(response.place);
						$('[name="cf_2819"]').val(response.city);
						$('[name="cf_2821"]').val(response.state);
						$('[name="cf_2823"]').val(response.nature);
						$('[name="cf_2825"]').val(response.grade);
            $('[name^="cf_4411"]').val(response.nature);
            $('[name="cf_4409_1"]').val(response.fwgrade);
            $('[name="cf_4409_2"]').val(response.twgrade);
            $('[name="cf_4409_3"]').val(response.ibgrade);
            $('[name="cf_4409_4"]').val(response.ergrade);
			
			$('[name="cf_4421_1"]').val(response.fwaprilactual);
            $('[name="cf_4421_2"]').val(response.twaprilactual);
            $('[name="cf_4421_3"]').val(response.ibaprilactual);
            $('[name="cf_4421_4"]').val(response.eraprilactual);
			
			$('[name="cf_4427_1"]').val(response.fwmayactual);
            $('[name="cf_4427_2"]').val(response.twmayactual);
            $('[name="cf_4427_3"]').val(response.ibmayactual);
            $('[name="cf_4427_4"]').val(response.ermayactual);
			
			$('[name="cf_4449_1"]').val(response.fwjuneactual);
            $('[name="cf_4449_2"]').val(response.twjuneactual);
            $('[name="cf_4449_3"]').val(response.ibjuneactual);
            $('[name="cf_4449_4"]').val(response.erjuneactual);
			
			$('[name="cf_4463_1"]').val(response.fwjulyactual);
            $('[name="cf_4463_2"]').val(response.twjulyactual);
            $('[name="cf_4463_3"]').val(response.ibjulyactual);
            $('[name="cf_4463_4"]').val(response.erjulyactual);
			
			$('[name="cf_4469_1"]').val(response.fwaugactual);
            $('[name="cf_4469_2"]').val(response.twaugactual);
            $('[name="cf_4469_3"]').val(response.ibaugactual);
            $('[name="cf_4469_4"]').val(response.eraugactual);
			
			$('[name="cf_4475_1"]').val(response.fwsepactual);
            $('[name="cf_4475_2"]').val(response.twsepactual);
            $('[name="cf_4475_3"]').val(response.ibsepactual);
            $('[name="cf_4475_4"]').val(response.ersepactual);
			
			$('[name="cf_4481_1"]').val(response.fwoctactual);
            $('[name="cf_4481_2"]').val(response.twoctactual);
            $('[name="cf_4481_3"]').val(response.iboctactual);
            $('[name="cf_4481_4"]').val(response.eroctactual);
			
			$('[name="cf_4487_1"]').val(response.fwnovactual);
            $('[name="cf_4487_2"]').val(response.twnovactual);
            $('[name="cf_4487_3"]').val(response.ibnovactual);
            $('[name="cf_4487_4"]').val(response.ernovactual);
			
			$('[name="cf_4493_1"]').val(response.fwdecactual);
            $('[name="cf_4493_2"]').val(response.twdecactual);
            $('[name="cf_4493_3"]').val(response.ibdecactual);
            $('[name="cf_4493_4"]').val(response.erdecactual);
			
			$('[name="cf_4499_1"]').val(response.fwjanactual);
            $('[name="cf_4499_2"]').val(response.twjanactual);
            $('[name="cf_4499_3"]').val(response.ibjanactual);
            $('[name="cf_4499_4"]').val(response.erjanactual);
			
			$('[name="cf_4505_1"]').val(response.fwfebactual);
            $('[name="cf_4505_2"]').val(response.twfebactual);
            $('[name="cf_4505_3"]').val(response.ibfebactual);
            $('[name="cf_4505_4"]').val(response.erfebactual);
			
			$('[name="cf_4511_1"]').val(response.fwmaractual);
            $('[name="cf_4511_2"]').val(response.twmaractual);
            $('[name="cf_4511_3"]').val(response.ibmaractual);
            $('[name="cf_4511_4"]').val(response.ermaractual);
			
			$('[name="cf_3473"]').prop('readonly','true');
						/*var lineitemrow4W = $('#totalRowCount_4W').val();
						var count4W = lineitemrow4W.split(",");
						var rownum4W = count4W.length;
						if(rownum4W == 1 && (recordid == undefined || recordid == ""))
						{
							$('input[name="cf_2337"]').val(response.fourwcenturion);
							$('input[name="cf_2341"]').val(response.fourw);
							var four = response.fourw;
							var fourmnth = (parseFloat(four) / 12).toFixed(2);
							$('input[name="cf_2343"]').val(fourmnth);
						}
						else
						{
							$('input[name="cf_2337_1"]').val(response.fourwcenturion);
							$('input[name="cf_2341_1"]').val(response.fourw);
							var four = response.fourw;
							var fourmnth = (parseFloat(four) / 12).toFixed(2);
							$('input[name="cf_2343_1"]').val(fourmnth);
							for(var i =2; i<=rownum4W; i++)
							{
								$('input[name="cf_2337_'+i+'"]').val('0.00');
								$('input[name="cf_2341_'+i+'"]').val('0.00');
								$('input[name="cf_2343_'+i+'"]').val('0.00');
							}
						}
						var lineitemrow2W = $('#totalRowCount_2W').val();
						var count2W = lineitemrow2W.split(",");
						var rownum2W = count2W.length;
						if(rownum2W == 1 && (recordid == undefined || recordid == ""))
						{
							$('input[name="cf_2444"]').val(response.twowcenturion);
							$('input[name="cf_2446"]').val(response.twow);
							var two = response.twow;
							var twomnth = (parseFloat(two) / 12).toFixed(2);
							$('input[name="cf_2448"]').val(twomnth);
						}
						else
						{
							$('input[name="cf_2444_1"]').val(response.twowcenturion);
							$('input[name="cf_2446_1"]').val(response.twow);
							var two = response.twow;
							var twomnth = (parseFloat(two) / 12).toFixed(2);
							$('input[name="cf_2448_1"]').val(twomnth);
							for(var i =2; i<=rownum2W; i++)
							{
								$('input[name="cf_2444_'+i+'"]').val('0.00');
								$('input[name="cf_2446_'+i+'"]').val('0.00');
								$('input[name="cf_2448_'+i+'"]').val('0.00');
							}
						}
						var lineitemrowIB = $('#totalRowCount_IB').val();
						var countIB = lineitemrowIB.split(",");
						var rownumIB = countIB.length;
						if(rownumIB == 1 && (recordid == undefined || recordid == ""))
						{
							$('input[name="cf_2468"]').val(response.ibcenturion);
							$('input[name="cf_2474"]').val(response.ib);
							var ib = response.ib;
							var ibmnth = (parseFloat(ib) / 12).toFixed(2);
							$('input[name="cf_2480"]').val(ibmnth);
						}
						else
						{
							$('input[name="cf_2468_1"]').val(response.ibcenturion);
							$('input[name="cf_2474_1"]').val(response.ib);
							var ib = response.ib;
							var ibmnth = (parseFloat(ib) / 12).toFixed(2);
							$('input[name="cf_2480_1"]').val(ibmnth);
							for(var i =2; i<=rownumIB; i++)
							{
								$('input[name="cf_2468_'+i+'"]').val('0.00');
								$('input[name="cf_2474_'+i+'"]').val('0.00');
								$('input[name="cf_2480_'+i+'"]').val('0.00');
							}
						}
						var lineitemrowER = $('#totalRowCount_ER').val();
						var countER = lineitemrowER.split(",");
						var rownumER = countER.length;
						if(rownumER == 1 && (recordid == undefined || recordid == ""))
						{
							$('input[name="cf_2594"]').val(response.ercenturion);
							$('input[name="cf_2600"]').val(response.er);
							var er = response.er;
							var ermnth = (parseFloat(er) / 12).toFixed(2);
							$('input[name="cf_2604"]').val(ermnth);
						}
						else
						{
							$('input[name="cf_2594_1"]').val(response.ercenturion);
							$('input[name="cf_2600_1"]').val(response.er);
							var er = response.er;
							var ermnth = (parseFloat(er) / 12).toFixed(2);
							$('input[name="cf_2604_1"]').val(ermnth);
							for(var i =2; i<=rownumER; i++)
							{
								$('input[name="cf_2594_'+i+'"]').val('0.00');
								$('input[name="cf_2600_'+i+'"]').val('0.00');
								$('input[name="cf_2604_'+i+'"]').val('0.00');
							}
						}*/
						$('input[name="cf_2801"]').prop('readonly','true');
						$('input[name="cf_2819"]').prop('readonly','true');
						$('input[name="cf_2821"]').prop('readonly','true');
						$('input[name="cf_2823"]').prop('readonly','true');
						$('input[name="cf_2825"]').prop('readonly','true');
					}
					});
			}
			if(referenceModule=='Accounts' && sourcemodule=='MarketAnalysis')
			{
				var trid = localStorage.getItem('tagmoduleid');
				 var rowid = "";
				 var tdid = trid.split("_");
				 var trct = tdid.length;
				 if(trct==4){
				 rowid = 0;
				 }else if(trct==5){
				 rowid = tdid[trct-1];
				 }

				    $.ajax(
                    {
                    type:"post",
                    url: "rahulAjax.php",
                    data: {id: id, action: 'getCustomerDetails'},
				    dataType: 'json',
                    success:function(response)
                    {
						var challen_4w = $('input[name="cf_nrl_accounts388_id"]').val();
						var challen_2w = $('input[name="cf_nrl_accounts431_id"]').val();
						var challen_IB = $('input[name="cf_nrl_accounts168_id"]').val();
						var challen_ER = $('input[name="cf_nrl_accounts569_id"]').val();

					    var tablabel = $('.related-tabs').find('li.active').find('span.tab-label').text();

						if(tablabel=='4W')
						{
						if(rowid>=1)
						{
							if(challen_4w!=''){
							$('input[name="cf_2188_'+rowid+'"]').val(response.city);
							$('input[name="cf_2190_'+rowid+'"]').val(response.state);
							}
						}else{
							if(challen_4w!=''){
							$('input[name="cf_2188"]').val(response.city);
							$('input[name="cf_2190"]').val(response.state);
							}
						}
						}


						if(tablabel=='2W')
						{
						if(rowid>1)
						{
							if(challen_2w!=''){
							$('input[name="cf_2215_'+rowid+'"]').val(response.city);
							$('input[name="cf_2217_'+rowid+'"]').val(response.state);
							 }
						}else{
							if(challen_2w!=''){
							$('input[name="cf_2215"]').val(response.city);
							$('input[name="cf_2217"]').val(response.state);
							}
						}
						}


						if(tablabel=='IB')
						{
						if(rowid>1)
						{
							if(challen_IB!=''){
							$('input[name="cf_2254_'+rowid+'"]').val(response.city);
							$('input[name="cf_2256_'+rowid+'"]').val(response.state);
							}
						}else{
							if(challen_IB!=''){
							$('input[name="cf_2254"]').val(response.city);
							$('input[name="cf_2256"]').val(response.state);
							}
						}
						}


						if(tablabel=='ER')
						{
						if(rowid>1)
						{
							if(challen_ER!=''){
							$('input[name="cf_2285_'+rowid+'"]').val(response.city);
							$('input[name="cf_2287_'+rowid+'"]').val(response.state);
							}
						}else{
							if(challen_ER!=''){
							$('input[name="cf_2285"]').val(response.city);
							$('input[name="cf_2287"]').val(response.state);
							}
						}
						}
					}


				    });
			}


            e.preventDefault();
		}
	},


	registerEventForListViewEntryClick : function(){

		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.off('click', '.listViewEntries');
		popupPageContentsContainer.on('click','.listViewEntries',function(e){
	    thisInstance.getListViewEntries(e);
		});
	},

    /**
     * Function to register event for Search
     */
    registerEventForSearch : function(){
        var thisInstance = this;
        var popupContainer = this.getPopupPageContainer();
		popupContainer.on('click','#popupSearchButton',function(e){
            jQuery('#totalPageCount',popupContainer).text("");
            thisInstance.searchHandler().then(function(data){
                jQuery('#pageNumber',popupContainer).val(1);
                jQuery('#pageToJump',popupContainer).val(1);
                thisInstance.updatePagination();
            });
        });
    },

    /**
	 * Function to handle Sort
	 */
	sortHandler : function(headerElement){
		var aDeferred = jQuery.Deferred();
		//Listprice column should not be sorted so checking for class noSorting
		if(headerElement.hasClass('noSorting')){
			return;
		}
		var fieldName = headerElement.data('columnname');
		var sortOrderVal = headerElement.data('nextsortorderval');
		var sortingParams = {
			"orderby" : fieldName,
			"sortorder" : sortOrderVal
		}
		var completeParams = this.getCompleteParams();
		jQuery.extend(completeParams,sortingParams);
		this.getPageRecords(completeParams).then(
			function(data){
				aDeferred.resolve(data);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},

    /**
     * Function to register event for Sorting
     * @returns {undefined}
     */
    registerEventForSort : function(){
        var thisInstance = this;
        var popupPageContentsContainer = this.getPopupPageContainer();
        popupPageContentsContainer.on('click','.listViewHeaderValues',function(e){
                var element = jQuery(e.currentTarget);
                thisInstance.sortHandler(element).then(function(data){
                        thisInstance.updatePagination();
                });
        });
    },

    /**
	 * Function to register event for popup list Search
	 */
	registerEventForPopupListSearch : function(){
		var thisInstance = this;
        var popupPageContainer = this.getPopupPageContainer();
        popupPageContainer.on('click','[data-trigger="PopupListSearch"]',function(e){
            jQuery('#searchvalue').val("");
            jQuery('#totalPageCount').text("");
			thisInstance.searchHandler().then(function(data){
				jQuery('#pageNumber').val(1);
				jQuery('#pageToJump').val(1);
				thisInstance.updatePagination();
			});
        }).on('keypress',function(e){
			var code = e.keyCode || e.which;
			if(code == 13){
				var element = popupPageContainer.find('[data-trigger="PopupListSearch"]');
				jQuery(element).trigger('click');
			}
		});

		$('[data-trigger="PopupListSearch"]').click();
	},

	pageJump : function() {
		var thisInstance = this;
		var popupContainer = thisInstance.getPopupPageContainer();
		var element = popupContainer.find('#totalPageCount');
		var totalPageNumber = element.text();
		var pageCount;

		if(totalPageNumber === ""){
			var totalCountElem = popupContainer.find('#totalCount');
			var totalRecordCount = totalCountElem.val();
			if(totalRecordCount !== '') {
				var recordPerPage = popupContainer.find('#pageLimit').val();
				if(recordPerPage === '0') recordPerPage = 1;
				pageCount = Math.ceil(totalRecordCount/recordPerPage);
				if(pageCount === 0){
					pageCount = 1;
				}
				element.text(pageCount);
				return;
			}

			thisInstance.getPageCount().then(function(data){
				var pageCount = data.page;
				totalCountElem.val(data.numberOfRecords);
				if(pageCount === 0){
					pageCount = 1;
				}
				element.text(pageCount);
			});
		}
	},

	pageJumpOnSubmit : function(element) {
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var popupContainer = this.getPopupPageContainer();
		var currentPageElement = jQuery('#pageNumber', popupContainer);
		var currentPageNumber = parseInt(currentPageElement.val());
		var newPageNumber = parseInt(jQuery('#pageToJump',popupContainer).val());
		var totalPages = parseInt(jQuery('#totalPageCount', popupContainer).text());

		if(newPageNumber > totalPages){
			var message = app.vtranslate('JS_PAGE_NOT_EXIST');
			app.helper.showErrorNotification({'message':message});
			return aDeferred.reject();
		}

		if(newPageNumber === currentPageNumber){
			var message = app.vtranslate('JS_YOU_ARE_IN_PAGE_NUMBER')+" "+newPageNumber;
			app.helper.showAlertNotification({'message': message});
			return aDeferred.reject();
		}

		var urlParams = thisInstance.getCompleteParams();
		urlParams['page'] = newPageNumber;
		this.getPageRecords(urlParams).then(
			function(data){
				jQuery('.btn-group', popupContainer).removeClass('open');
				jQuery('#pageNumber',popupContainer).val(newPageNumber);
				aDeferred.resolve(data);
			}
		);
		return aDeferred.promise();
	},

    /**
	 * Function to get Page Jump Params
	 */
	getPageJumpParams : function(){
		var params = this.getCompleteParams();
		params['view'] = 'PopupAjax';
		params['mode'] = 'getPageCount';
		return params;
	},

	/**
	 * Function to get page count and total number of records in list
	 */
	getPageCount : function(){
		var aDeferred = jQuery.Deferred();
		var pageCountParams = this.getPageJumpParams();
		var params = {
			"type" : "GET",
			"data" : pageCountParams
		}

		app.request.get(params).then(
			function(err, data) {
				var response;
				if(typeof data !== "object"){
					response = JSON.parse(data);
				} else{
					response = data;
				}
				aDeferred.resolve(response);
			}
		);
		return aDeferred.promise();
	},

	totalNumOfRecords : function (currentEle) {
		var thisInstance = this;
		var popupContainer = thisInstance.getPopupPageContainer();
		var totalRecordsElement = popupContainer.find('#totalCount');
		var totalNumberOfRecords = totalRecordsElement.val();
		currentEle.addClass('hide');

		if(totalNumberOfRecords === '') {
			thisInstance.getPageCount().then(function(data){
				totalNumberOfRecords = data.numberOfRecords;
				totalRecordsElement.val(totalNumberOfRecords);
				popupContainer.find('ul#listViewPageJumpDropDown #totalPageCount').text(data.page);
				thisInstance.showPagingInfo();
			});
		}else{
			thisInstance.showPagingInfo();
		}
	},

	showPagingInfo : function(){
		var thisInstance = this;
		var popupContainer = thisInstance.getPopupPageContainer();
		var totalNumberOfRecords = jQuery('#totalCount', popupContainer).val();
		var pageNumberElement = jQuery('.pageNumbersText', popupContainer);
		var pageRange = pageNumberElement.text();
		var newPagingInfo = pageRange.trim()+" "+app.vtranslate('of')+" "+totalNumberOfRecords;
		var listViewEntriesCount = parseInt(jQuery('#noOfEntries', popupContainer).val());

		if(listViewEntriesCount !== 0){
			jQuery('.pageNumbersText', popupContainer).html(newPagingInfo);
		} else {
			jQuery('.pageNumbersText', popupContainer).html("");
		}
	},

	initializePaginationEvents : function() {
		var thisInstance = this;
		var paginationObj = new arocrm_Pagination_Js;
		var popupContainer = thisInstance.getPopupPageContainer();
		paginationObj.initialize(popupContainer);

		app.event.on(paginationObj.nextPageButtonClickEventName, function(){
			thisInstance.nextPageHandler().then(function(data){
				var pageNumber = popupContainer.find('#pageNumber').val();
				popupContainer.find('#pageToJump').val(pageNumber);
				thisInstance.updatePagination();
                thisInstance.handleCheckBoxSelection();
				thisInstance.registerToRemoveEmailFieldClickAttr();
                                thisInstance.registerPostSelectionActions();
			});
		});

		app.event.on(paginationObj.previousPageButtonClickEventName, function(){
			thisInstance.previousPageHandler().then(function(data){
				var pageNumber = popupContainer.find('#pageNumber').val();
				popupContainer.find('#pageToJump').val(pageNumber);
				thisInstance.updatePagination();
                thisInstance.handleCheckBoxSelection();
				thisInstance.registerToRemoveEmailFieldClickAttr();
                                thisInstance.registerPostSelectionActions();
			});
		});

		app.event.on(paginationObj.pageJumpButtonClickEventName, function(event, currentEle){
			thisInstance.pageJump();
                        thisInstance.registerPostSelectionActions();
		});

		app.event.on(paginationObj.totalNumOfRecordsButtonClickEventName, function(event, currentEle){
			thisInstance.totalNumOfRecords(currentEle);
		});

		app.event.on(paginationObj.pageJumpSubmitButtonClickEvent, function(event, currentEle){
			thisInstance.pageJumpOnSubmit().then(function(data){
				thisInstance.updatePagination();
                thisInstance.handleCheckBoxSelection();
				thisInstance.registerToRemoveEmailFieldClickAttr();
                thisInstance.registerPostSelectionActions();
			});
		});
	},


   /**
	* Function to read selection
	*/
	readSelectedIds : function(decode){
		var selectedIdsElement = jQuery('#selectedIds');
		var selectedIdsDataAttr = 'SelectedIdsData';
		var selectedIdsElementDataAttributes = selectedIdsElement.data();
		if (!(selectedIdsDataAttr in selectedIdsElementDataAttributes) ) {
			var selectedIds = new Array();
			this.writeSelectedIds(selectedIds);
		} else {
			selectedIds = selectedIdsElementDataAttributes[selectedIdsDataAttr];
		}
		if(decode == true){
			if(typeof selectedIds == 'object'){
				return JSON.stringify(selectedIds);
			}
		}
		return selectedIds;
	},

    /**
	 * Function to get selected recordIds from selection.
     */
	getSelectedRecordIds : function(){
			var thisInstance = this;
			var recordIds = new Array();
			var selectedData = thisInstance.readSelectedIds();
			for(var data in selectedData){
				if(typeof selectedData[data] == "object"){
					var id = selectedData[data]['id'];
					recordIds.push(id);
				}
			}
			return recordIds;
	},

    /**
     * Function to write selection
	 */
	writeSelectedIds : function(selectedIds){
		jQuery('#selectedIds').data('SelectedIdsData',selectedIds);
	},

    registerSelectButton : function(){
		var popupPageContentsContainer = this.getPopupPageContainer();
		var thisInstance = this;
		popupPageContentsContainer.on('click','button.select', function(e){
			var selectedRecordDetails = {};
			var recordIds = new Array();
			var dataUrl;
			var selectedData = thisInstance.readSelectedIds();
			for(var data in selectedData){
				if(typeof selectedData[data] == "object"){
					var id = selectedData[data]['id'];
					recordIds.push(id);
					var name = selectedData[data]['name'];
					dataUrl = selectedData[data]['url'];
					selectedRecordDetails[id] = {'name' : name};
				}
			}
			var jsonRecorIds = JSON.stringify(recordIds);
			var datafieldid = "";
			if(Object.keys(selectedRecordDetails).length <= 0) {
				alert(app.vtranslate('JS_PLEASE_SELECT_ONE_RECORD'));
			}else{
				if(typeof dataUrl != 'undefined'){
				    dataUrl = dataUrl+'&idlist='+jsonRecorIds+'&currency_id='+jQuery('#currencyId').val();
				    app.request.get({'url':dataUrl}).then(
					function(error , data){
                        var recordData = data;
                        var recordDataLength = Object.keys(recordData).length;
                        if(recordDataLength == 1){
							recordData = recordData[0];
						}
						thisInstance.done(recordData, thisInstance.getEventName());
						e.preventDefault();
					},
					function(error,err){

					}
				);
				}else{
				    thisInstance.done(selectedRecordDetails, thisInstance.getEventName());
				}
			}
		});
	},

	selectAllHandler : function(e){
		var thisInstance = this;
		var currentElement = jQuery(e.currentTarget);
		var isMainCheckBoxChecked = currentElement.is(':checked');
		var tableElement = currentElement.closest('table');
		if(isMainCheckBoxChecked) {
			jQuery('input.entryCheckBox', tableElement).prop('checked',true);
			var selectedId = thisInstance.readSelectedIds();
			var recordIds = thisInstance.getSelectedRecordIds();
			jQuery('input.entryCheckBox').each(function(index, checkBoxElement){
				var checkBoxJqueryObject = jQuery(checkBoxElement);
				var row = checkBoxJqueryObject.closest('tr');
				var data = row.data();
                if(thisInstance.getView() == 'EmailsRelatedModulePopup' || thisInstance.getView() == 'EmailsRelatedModulePopupAjax'){
					var emailFields = jQuery(row).find('.emailField');
					data.email = emailFields;
				}
				if(!(jQuery.inArray(row.data('id'), recordIds) !== -1)){
					selectedId.push(data);
				}
			});
			thisInstance.writeSelectedIds(selectedId);
		}else {
			jQuery('input.entryCheckBox', tableElement).removeAttr('checked').closest('tr').removeClass('highlightBackgroundColor');
			jQuery('input.entryCheckBox').each(function(index, checkBoxElement){
				var selectedId = thisInstance.readSelectedIds();
				var recordIds = thisInstance.getSelectedRecordIds();
				var checkBoxJqueryObject = jQuery(checkBoxElement);
				var row = checkBoxJqueryObject.closest('tr');
				selectedId.splice(jQuery.inArray(row.data('id'), recordIds), 1);
				thisInstance.writeSelectedIds(selectedId);
			});

		}
	},

	registerEventForSelectAllInCurrentPage : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('change','input.selectAllInCurrentPage',function(e){
			thisInstance.selectAllHandler(e);
                        thisInstance.registerPostSelectionActions();
		});
	},

    checkBoxChangeHandler : function(e){
		var elem = jQuery(e.currentTarget);
		var parentElem = elem.closest('tr');
		if(elem.is(':checked')){
			parentElem.addClass('highlightBackgroundColor');

		}else{
			parentElem.removeClass('highlightBackgroundColor');
		}
	},

	/**
	 * Function to register event for entry checkbox change
	 */
	registerEventForCheckboxChange : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','input.entryCheckBox',function(e){
			e.stopPropagation();
			var checkBoxJqueryObject = jQuery(e.currentTarget);
			var row = checkBoxJqueryObject.closest('tr');
			var data = row.data();
            if(thisInstance.getView() == 'EmailsRelatedModulePopup' || thisInstance.getView() == 'EmailsRelatedModulePopupAjax'){
				var emailFields = jQuery(row).find('.emailField');
				data.email = emailFields;
			}
			var selectedId = thisInstance.readSelectedIds();
			if(checkBoxJqueryObject.is(':checked')){
				selectedId.push(data);
				thisInstance.writeSelectedIds(selectedId);
                                thisInstance.registerPostSelectionActions();
			}else{
				var recordIds= thisInstance.getSelectedRecordIds();
				selectedId.splice(jQuery.inArray(row.data('id'),recordIds), 1);
				thisInstance.writeSelectedIds(selectedId);
                                thisInstance.registerPostSelectionActions();
			}
            thisInstance.checkBoxChangeHandler(e);
		});
	},

        registerPostSelectionActions : function(){
            var selectedIds = this.getSelectedRecordIds();
            var selectionButton = jQuery('#popupContents').find('.select');
            if(selectedIds.length > 0){
                selectionButton.removeAttr("disabled");
            }else if(selectedIds.length == 0){
                selectionButton.attr("disabled", "disabled");
            }
        },

    /**
	 * Function to handle CheckBoxSelection after navigation
	 */
	handleCheckBoxSelection : function(){
		var thisInstance=this;
		var recordIds= thisInstance.getSelectedRecordIds();
		var selectedAll = true;
		jQuery('input.entryCheckBox').each(function(index, checkBoxElement){
			var checkBoxJqueryObject = jQuery(checkBoxElement);
			var parentElem = checkBoxJqueryObject.closest('tr');
			var row = checkBoxJqueryObject.closest('tr');
			var id = row.data('id');
			if((jQuery.inArray(id,recordIds))!== -1){
				checkBoxJqueryObject.prop('checked',true);
			}else{
				selectedAll = false;
			}
		});
		if(selectedAll === true){
            jQuery('.selectAllInCurrentPage').prop('checked',true);
		}
	},

    registerPostPopupLoadEvents : function(){
        var popupContainer = jQuery('#popupModal');
        var Options= {
            axis:"yx",
            setHeight:"400px", // Without height, it will not know where to start
            scrollInertia: 200
        };
        app.helper.showVerticalScroll(popupContainer.find('.popupEntriesDiv'), Options);

        // For Email Templates popup
        var popupContainer = jQuery('.popupModal');
        if(popupContainer.length != 0) {
            var Options= {
                axis:"yx",
                scrollInertia: 200
            };
            app.helper.showVerticalScroll(popupContainer.find('.popupEntriesDiv'), Options);
        }
    },

	registerToRemoveEmailFieldClickAttr : function() {
		jQuery('#popupContents').find('a.emailField').removeAttr('onclick');
	},

	registerEvents: function(){
		this.registerEventForListViewEntryClick();
		this.registerEventForSearch();
		this.registerEventForSort();
		this.registerEventForPopupListSearch();

		//For Pagination
		this.initializePaginationEvents();
		//END

		this.registerToRemoveEmailFieldClickAttr();
		//for record selection
		this.registerEventForSelectAllInCurrentPage();
		this.registerSelectButton();
		this.registerEventForCheckboxChange();
	}
});

jQuery(document).ready(function() {
	app.event.on("post.Popup.Load",function(event,params){
        vtUtils.applyFieldElementsView(jQuery('.myModal'));

		var popupInstance = arocrm_Popup_Js.getInstance(params.module);
        var eventToTrigger = params.eventToTrigger;
        if(typeof eventToTrigger != "undefined"){
            popupInstance.setEventName(params.eventToTrigger);
        }
        popupInstance.registerEvents();
        popupInstance.registerPostPopupLoadEvents();
    });
});

function UniteSettingsUC(){
	
	var arrControls = {};
	var g_IDPrefix = "#unite_setting_";
	var g_colorPicker, g_iconsHash={}, g_objFontsPanel;
	var g_objParent = null, g_objWrapper = null, g_objSapTabs = null;
	var g_objProvider = new UniteProviderAdminUC();
	
	var g_events = {
			CHANGE: "settings_change"
	};
	
	var g_options = {
			show_saps:false,
			saps_type:""
	}
	
	if(!g_ucAdmin)
		var g_ucAdmin = new UniteAdminUC();
	
	var t=this;
	
	
	/**
	 * validate that the parent exists
	 */
	function validateInited(){
		
		if(!g_objParent || g_objParent.length == 0)
			throw new Error("The parent not given, settings not inited");
		
	}
	
	
	/**
	 * compare control values
	 */
	function iscValEQ(controlValue, value){
		
		if(typeof value != "string"){
			
			return jQuery.inArray( controlValue, value) != -1;
		}else{
			return (value.toLowerCase() == controlValue);
		}

	}
	

	
	/**
	 * close all accordion items
	 */
	function closeAllAccordionItems(formID){
		jQuery("#"+formID+" .unite-postbox .inside").slideUp("fast");
		jQuery("#"+formID+" .unite-postbox .unite-postbox-title").addClass("box_closed");
	}
	
	this.__________FONTS_PANEL__________ = function(){}
	
	
	/**
	 * init fonts panel
	 */
	this.initFontsPanel = function(objWrapper){
		
		g_objFontsPanel = objWrapper.find(".uc-fontspanel");
		if(g_objFontsPanel.length == 0){
			g_objFontsPanel = null;
			return(null);
		}
		
		//checkbox event
		g_objFontsPanel.find(".uc-fontspanel-toggle").click(function(){
			
			var objCheck = jQuery(this);
			var sectionID = objCheck.data("target");
			var objSection = jQuery("#" + sectionID);
			g_ucAdmin.validateDomElement(objSection, "fonts panel section");
	
			if(objCheck.is(":checked")){
				
				objSection.show();
				
			}else{
				
				objSection.hide();
				
			}
		});
		
		this.initColorPicker(g_objFontsPanel);
		
		return(g_objFontsPanel);
	}
	
	
	/**
	 * get fonts panel data
	 */
	this.getFontsPanelData = function(){
		
		if(!g_objFontsPanel)
			return(null);
		
		var objData = {};
		var objCheckboxes = g_objFontsPanel.find(".uc-fontspanel-toggle");
		jQuery.each(objCheckboxes, function(index, checkbox){
			
			var objCheckbox = jQuery(checkbox);
			
			if(objCheckbox.is(":checked") == false)
				return(true);
			
			var sectionID = objCheckbox.data("target");
			var sectionName = objCheckbox.data("sectionname");
			
			var objSection = jQuery("#" + sectionID);
			g_ucAdmin.validateDomElement(objSection, "fonts panel section "+sectionID);
			
			//get fields values
			var objFields = objSection.find(".uc-fontspanel-field");
			
			var fieldsValues = {};
			jQuery.each(objFields, function(index, field){
			
				var objField = jQuery(field);
				
				var fieldName = objField.data("fieldname");
				var value = objField.val();
								
				if(jQuery.trim(value) == "")
					return(true);
				
				fieldsValues[fieldName] = value;
				
			});
						
			if(jQuery.isEmptyObject(fieldsValues) == false)
				objData[sectionName] = fieldsValues;
			
		});
		
		return(objData);
	}
	
	
	/**
	 * destroy fonts panel
	 */
	this.destroyFontsPanel = function(){
		
		if(!g_objFontsPanel)
			return(false);
		
		g_objFontsPanel.find(".uc-fontspanel-toggle").off("click");
	}
	
	
	this.__________OTHER_EXTERNAL__________ = function(){}
	
	/**
	 * init side settings accordion - started from php
	 */
	this.initAccordion = function(formID){
		var classClosed = "box_closed";
		jQuery("#"+formID+" .unite-postbox .unite-postbox-title").click(function(){
			var handle = jQuery(this);
			
			//open
			if(handle.hasClass(classClosed)){
				closeAllAccordionItems(formID);
				handle.removeClass(classClosed).siblings(".inside").slideDown("fast");
			}else{	//close
				handle.addClass(classClosed).siblings(".inside").slideUp("fast");
			}
			
		});
	};
	
	
	/**
	 * init tipsy
	 */
	function initTipsy(gravity){
		
		if(typeof jQuery("body").tipsy != "function")
			return(false);
		
		if(!gravity)
			var gravity = "e";
		
		//init tipsy
		g_objParent.find(".setting_uctext").tipsy({
			html:true,
			gravity:gravity,
	        delayIn: 70
		});
		
	}
	
	
	/**
	 * get all settings inputs
	 */
	function getObjInputs(objParent){
		validateInited();
		
		if(!objParent)
			var objParent = g_objParent;
		
		var objInputs = objParent.find("input, textarea, select").not("input[type='button']");
		return(objInputs);
	}
	
	
	/**
	 * get input type
	 */
	function getInputType(objInput){
		var type = objInput[0].type;
		
		switch(type){
			case "select-one":
			case "select-multiple":
				type = "select";
			break;
			case "text":
				if(objInput.hasClass("unite-color-picker"))
					type = "color";
				else if(objInput.hasClass("unite-setting-image-input"))
					type = "image";
				else if(objInput.hasClass("unite-setting-mp3-input"))
					type = "mp3";
				else if(objInput.hasClass("unite-postpicker-input"))
					type="post";
			break;
			case "textarea":
				if(objInput.hasClass("mce_editable") || objInput.hasClass("wp-editor-area"))
					type = "editor_tinymce";
			break;
		}
		
		return(type);
	}
	
		
	
	/**
	 * get settings values object by the parent
	 */
	this.getSettingsValues = function(objParent){
		
		validateInited();
		
		var obj = new Object();
		
		var name,value,type,flagUpdate,inputID;
		
		var objInputs = getObjInputs(objParent);
		
		jQuery.each(objInputs, function(index, input){
			
			var objInput = jQuery(input);
			name = objInput.attr("name");
			type = getInputType(objInput);
			value = objInput.val();
			inputID = objInput.prop("id");
			
			flagUpdate = true;

			switch(type){
				case "checkbox":
					value = objInput.is(":checked");
				break;
				case "radio":
					if(objInput.is(":checked") == false) 
						flagUpdate = false;				
				break;
				case "button":
					flagUpdate = false;
				break;
				case "editor_tinymce":
					
					if(typeof tinyMCE != "undefined"){
						
						var objEditor = tinyMCE.EditorManager.get(inputID);
						if(objEditor)						
							value = objEditor.getContent();
					}
					
				break;
				case "image":
					var imageID = objInput.data("imageid");
					if(imageID && jQuery.isNumeric(imageID))
						value = imageID;
				case "mp3":
					var source = objInput.data("source");
					
					//convert to relative url if not addon
					if(source != "addon" && jQuery.isNumeric(value) == false)
						value = g_ucAdmin.urlToRelative(value);
				break;
				case "post":
					value = objInput.data("postid");
					obj[name+"_post_title"] = objInput.val();
				break;
			}
			
			if(flagUpdate == true && name != undefined) 
				obj[name] = value;
			
		});
		
		return(obj);
	};
	
	
	/**
	 * clear input
	 */
	function clearInput(objInput, dataname, checkboxDataName){
		
		var name = objInput.attr("name");
		var type = getInputType(objInput);
		var inputID = objInput.prop("id");
		var defaultValue;
		
		if(!dataname)
			var dataname = "default";
		
		if(!checkboxDataName)
			var checkboxDataName = "defaultchecked";
		
		switch(type){
			case "select":
			case "textarea":
			case "text":
				defaultValue = objInput.data(dataname);
				if(type == "select"){
					if(defaultValue === true)
						defaultValue = "true";
					if(defaultValue === false)
						defaultValue = "false";
				}
				objInput.val(defaultValue);
			break;
			case "color":
				defaultValue = objInput.data(dataname);
				objInput.val(defaultValue);
				g_colorPicker.linkTo(objInput);							
			break;
			case "checkbox":
				defaultValue = objInput.data(checkboxDataName);
				defaultValue = g_ucAdmin.strToBool(defaultValue);
				
				if(defaultValue == true)
					objInput.attr("checked", true);
				else
					objInput.attr("checked", false);
			break;
			case "radio":
				defaultValue = objInput.data(checkboxDataName);
				defaultValue = g_ucAdmin.strToBool(defaultValue);
				
				if(defaultValue == true)
					objInput.attr("checked", "checked");
			break;
			case "editor_tinymce":
				
				var objEditorWrapper = objInput.parents(".unite-editor-setting-wrapper");
				defaultValue = objEditorWrapper.data(dataname);
								
				if(typeof tinyMCE == "undefined")	//skip the init, if no editor yet
					break;
				
				var objEditor = tinyMCE.EditorManager.get(inputID);
												
				if(objEditor){
					objEditor.setContent(defaultValue);
				}else{
					objInput.val(defaultValue);
				}
			
			break;
			case "image":
			case "mp3":
				defaultValue = objInput.data(dataname);
				objInput.val(defaultValue);
				objInput.trigger("change");
			break;
			case "post":
				defaultValue = objInput.data(dataname);
				objInput.data("postid", defaultValue);
				var defaultTitle = objInput.data(dataname+"-title");
				objInput.val(defaultTitle);
			break;
			default:
				trace("for clear - wrong type: " + type);
			break;
		}
		
	}

	
	/**
	 * set input value
	 */
	function setInputValue(objInput, value, value2){
		
		var type = getInputType(objInput);
		var inputID = objInput.prop("id");
		
		switch(type){
			case "select":
			case "textarea":
			case "text":
				objInput.val(value);
			break;
			case "color":
				objInput.val(value);
				g_colorPicker.linkTo(objInput);							
			break;
			case "checkbox":
				value = g_ucAdmin.strToBool(value);
				
				if(value == true)
					objInput.attr("checked", true);
				else
					objInput.attr("checked", false);
			break;
			case "radio":
				valueBool = g_ucAdmin.strToBool(value);
				
				if(valueBool == true)
					objInput.attr("checked", "checked");	//set by radio boolean
				else{
					var radioValue = objInput.val();		//set by radio text
					if(radioValue === value)
						objInput.attr("checked", "checked");
				}
			break;
			case "editor_tinymce":
				
				if(typeof tinyMCE == "undefined"){	//set textarea content
					
					objInput.val(value);
					
				}else{
					var objEditor = tinyMCE.EditorManager.get(inputID);
					if(objEditor){
						var objEditor = tinyMCE.EditorManager.get(inputID);
						objEditor.setContent(value);
					}else{
						objInput.val(value);
					}
				}
				
			break;
			case "image":
				if(value2)
					objInput.data("imageid",value2);	//set image id
			case "mp3":
				objInput.val(value);
				objInput.trigger("change");
			break;
			case "post":
				objInput.data("postid", value);
				var showVal = value2;
				if(!showVal)
					showVal = value;
				objInput.val(showVal);
			break;
			default:
				trace("for setvalue - wrong type: " + type);
			break;
		}
		
		
	}
	
	
	/**
	 * clear settings
	 */
	this.clearSettings = function(dataname, checkboxDataName){
		
		validateInited();
		
		var objInputs = getObjInputs();
		
		jQuery.each(objInputs, function(index, input){
			var objInput = jQuery(input);
			clearInput(objInput, dataname, checkboxDataName);
		});
	}
	
	
	/**
	 * get field names by type
	 */
	this.getFieldNamesByType = function(type){
		
		validateInited();
		
		var objInputs = getObjInputs();
		var arrFieldsNames = [];
		
		jQuery.each(objInputs, function(index, input){
			var objInput = jQuery(input);
			var name = objInput.attr("name");
			
			var inputType = getInputType(objInput);
			if(inputType == type)
				arrFieldsNames.push(name);
		});
		
		return(arrFieldsNames);
	}

	
	/**
	 * clear settings
	 */
	function clearSettingsInit(){
		
		t.clearSettings("initval","initchecked");
		
	}
	
	
	/**
	 * set values, clear first
	 */
	this.setValues = function(objValues){
		
		validateInited();
				
		//if empty values - exit
		if(typeof objValues != "object"){
			this.clearSettings();
			return(false);
		}
		
		var objInputs = getObjInputs();
		
		jQuery.each(objInputs, function(index, input){
			var objInput = jQuery(input);
			clearInput(objInput);
			var name = objInput.attr("name");
			
			if(typeof name == "undefined")
				return(true);
							
			var type = getInputType(objInput);
			if(objValues.hasOwnProperty(name)){
				var value = objValues[name];
				var value2 = null;
				
				switch(type){
					case "post":
						var postTitle = g_ucAdmin.getVal(objValues, name+"_post_title");
						if(postTitle)
							value2 = postTitle;
					break;
				}
				setInputValue(objInput, value, value2);
			}
			
		});
		
	}

	function _______COLOR_PICKER_____(){}
		
	
	/**
	 * init color picker
	 */
	this.initColorPicker = function(objParent){
		
		if(!objParent)
			var objParent = g_objParent;
		
		var colorPickerWrapper = jQuery('#divColorPicker');
		if(colorPickerWrapper.length == 0){
			jQuery("body").append('<div id="divColorPicker" style="display:none;"></div>');
			colorPickerWrapper = jQuery('#divColorPicker');
		}
		
		//init the wrapper itself
		var isInited = colorPickerWrapper.data("inited");
		
		if(isInited !== true){
						
			colorPickerWrapper.click(function(){
				
				return(false);	//prevent body click
			});
			
			jQuery("body").click(function(){
				colorPickerWrapper.hide();
			});
			
			colorPickerWrapper.data("inited", true);
		}
		
		g_colorPicker = jQuery.unite_farbtastic('#divColorPicker');
		
		
		//link the color picket to the inputs
		objParent.find(".unite-color-picker").each(function(index, input){

			g_colorPicker.linkTo(input);
			
		});
		
		objParent.find(".unite-color-picker").focus(function(){
			
			g_colorPicker.linkTo(this);
			
			var bodyWidth = jQuery("body").width();
			
			colorPickerWrapper.show();
			var input = jQuery(this);
			var offset = input.offset();
			
			var wrapperWidth = colorPickerWrapper.width();
			var inputWidth = input.width();
			var inputHeight = input.height();
			
			var posLeft = offset.left - wrapperWidth / 2 + inputWidth/2;
			
			var posRight = posLeft + wrapperWidth;
			if(posRight > bodyWidth)
				posLeft = bodyWidth - wrapperWidth;
			
			var posTop = offset.top - colorPickerWrapper.height() - inputHeight + 10;
			
			colorPickerWrapper.css({
				"left":posLeft,
				"top":posTop
			});

			
		}).click(function(){			
			return(false);	//prevent body click
		});
		
	}

	
	function _______MP3_SETTING_____(){}
	
	
	/**
	 * update image url base
	 */
	this.updateMp3FieldState = function(objInput, isEnable){
		
		var objButton = objInput.siblings(".unite-button-choose");
		var objError = objInput.siblings(".unite-setting-mp3-error");
		
		objInput.trigger("change");
		
		if(!isEnable){				//set disabled mode
			
			if(objError.length)
				objError.show();
			
			g_ucAdmin.disableInput(objInput);
			g_ucAdmin.disableButton(objButton);
			
		}else{						//set enabled mode
			
			if(objError.length)
				objError.hide();
			
			g_ucAdmin.enableInput(objInput);
			g_ucAdmin.enableButton(objButton);
		}
		
		
	}
	
	
	/**
	 * on change image click - change the image
	 */
	function onChooseMp3Click(){
		var objButton = jQuery(this);
		
		if(g_ucAdmin.isButtonEnabled(objButton) == false)
			return(true);
		
		var objInput = objButton.siblings(".unite-setting-mp3-input");
		var source = objInput.data("source");
		
		g_ucAdmin.openAddMp3Dialog(g_uctext.choose_audio,function(urlMp3){
			
			if(source == "addon"){		//in that case the url is an object
				var inputValue = urlMp3.url_assets_relative;
				var fullUrl = urlMp3.full_url;
				objInput.data("urlfull", fullUrl);
				
				setInputValue(objInput, inputValue);
			}else{
				setInputValue(objInput, urlMp3);
			}
			
			objInput.trigger("change");
			
		},false, source);
		
	}
	
	function _______IMAGE_SETTING_____(){}
	
	
	/**
	 * set image preview
	 */
	function setImagePreview(){
		
		var objInput = jQuery(this);
		
		if(objInput.length == 0)
			throw new Error("wrong image input given");
		
		var source = objInput.data("source");			
		
		var url = objInput.val();
		
		if(source == "addon"){
			var urlFull = objInput.data("urlfull");
			if(!urlFull){
				urlFull = g_ucAdmin.urlToFull(url);
				objInput.data("urlfull", urlFull);
			}
			
			url = urlFull;
		}else{
			url = g_ucAdmin.urlToFull(url);
		}
				
		var objPreview = objInput.siblings(".unite-setting-image-preview");
		
		url = jQuery.trim(url);
		
		if(url == ""){
			objPreview.hide();
		}else{
			objPreview.css("background-image","url('"+url+"')");
			objPreview.show();
		}
		
	}
	
	
	/**
	 * on change image click - change the image
	 */
	function onChooseImageClick(){
		var objButton = jQuery(this);
		
		if(g_ucAdmin.isButtonEnabled(objButton) == false)
			return(true);
		
		var objInput = objButton.siblings(".unite-setting-image-input");
		var source = objInput.data("source");
		
		g_ucAdmin.openAddImageDialog(g_uctext.choose_image,function(urlImage, imageID){
			
			if(source == "addon"){		//in that case the url is an object
				var inputValue = urlImage.url_assets_relative;
				var fullUrl = urlImage.full_url;				
				objInput.data("urlfull", fullUrl);
				
				setInputValue(objInput, inputValue);
			}else
				setInputValue(objInput, urlImage, imageID);
			
			objInput.trigger("change");
			
		},false, source);
		
	}
	
	
	/**
	 * on clear image click
	 */
	function onClearImageClick(){
		
		var objButton = jQuery(this);
		
		if(g_ucAdmin.isButtonEnabled(objButton) == false)
			return(true);
		
		var objInput = objButton.siblings(".unite-setting-image-input");
		
		objInput.val("");
		objInput.data("urlfull","");
		
		objInput.trigger("change");
		
	}
	
	
	/**
	 * update image url base
	 */
	this.updateImageFieldState = function(objInput, isEnable){
		
		var objError = objInput.siblings(".unite-setting-image-error");
		var objButton = objInput.siblings(".unite-button-choose");
		var objButtonClear = objInput.siblings(".unite-button-clear");
		var objPreview = objInput.siblings(".unite-setting-image-preview");
		
		objInput.trigger("change");
		
		if(!isEnable){				//set disabled mode
			
			if(objError.length)
				objError.show();
			
			g_ucAdmin.disableInput(objInput);
			g_ucAdmin.disableButton(objButton);
			g_ucAdmin.disableButton(objButtonClear);
			objPreview.hide();
			
		}else{						//activate image input
			if(objError.length)
				objError.hide();
			
			g_ucAdmin.enableInput(objInput);
			g_ucAdmin.enableButton(objButton);
			g_ucAdmin.enableButton(objButtonClear);
			
			var backgroundImage = objPreview.css("background-image");
						
			if(backgroundImage && backgroundImage != "none")
				objPreview.show();
		}
		
		
	}
	
	
	/**
	 * on update assets path
	 * update all image addon inputs url base
	 */
	function onUpdateAssetsPath(event, urlBase){
		
		validateInited();
		
		var objInputs = getObjInputs();

		objInputs.each(function(index, input){
			
			var objInput = jQuery(input);
			var type = getInputType(objInput);
			if(type != "image" || type != "mp3")
				return(true);
			
			var source = objInput.data("source");
			
			if(source == "addon"){
				var isEnable = true;
				if(!urlBase)
					isEnable = false;
				
				t.updateImageFieldState(objInput, isEnable);
			}
			
		});
		
	}
	
	function _______SAPS_____(){}
	
	/**
	 * get all sap tabs
	 */
	function getAllSapTabs(){
		
		var objTabs = g_objSapTabs.children("a");
		
		return(objTabs);
	}
	
	
	/**
	 * show sap elmeents
	 */
	function showSapInlineElements(numSap){

		var elementClass = ".unite-sap-" + numSap;
		var objElements = g_objParent.find(".unite-sap-element");
		
		if(objElements.length == 0)
			return(false);
		
		var objSapElements = g_objParent.find(elementClass);
		
		objElements.not(objSapElements).hide();
		
		objSapElements.show();
	}
	
	
	/**
	 * on sap tab click
	 */
	function onSapTabClick(){
		
		var classSelected = "unite-tab-selected";
		
		var objTab = jQuery(this);
		
		if(objTab.hasClass(classSelected))
			return(false);
		
		var allTabs = getAllSapTabs();
		
		allTabs.not(objTab).removeClass(classSelected);
		
		objTab.addClass(classSelected);
		
		var sapNum = objTab.data("sapnum");
		
		showSapInlineElements(sapNum);
			
	}
	
	
	/**
	 * init saps
	 */
	function initSaps(){
		
		if(g_options.show_saps == false)
			return(false);
		
		if(!g_objWrapper)
			return(false);
		
		g_objSapTabs = g_objWrapper.find(".unite-settings-tabs");
		
		if(g_objSapTabs.length == 0){
			
			g_objSapTabs = null;
			return(false);
		}
		
		g_objSapTabs.children("a").click(onSapTabClick);
		
		
	}
	
	function ______ICON_PICKER____(){}
	
	/**
	 * init the dialog
	 */
	function iconPicker_initDialog(){
		
		var htmlDialog = '<div id="unite_icon_picker_dialog" class="unite-icon-picker-dialog">';
		htmlDialog += '<div class="unite-iconpicker-dialog-top">';
		htmlDialog += '<input class="unite-iconpicker-dialog-input-filter" type="text" placeholder="Type to filter" value="">';
		htmlDialog += '<span class="unite-iconpicker-dialog-icon-name"></span></div>';
		htmlDialog += '<div class="unite-iconpicker-dialog-icons-container"></div></div>';
	
		jQuery("body").append(htmlDialog);
		
		var objDialogWrapper = jQuery('#unite_icon_picker_dialog');
		
		var objContainer = objDialogWrapper.find('.unite-iconpicker-dialog-icons-container');
		var objFilter = objDialogWrapper.find('.unite-iconpicker-dialog-input-filter');
		var objIconName = objDialogWrapper.find(".unite-iconpicker-dialog-icon-name");
		
		jQuery(g_ucFaIcons).each(function(index, className) {
			
			var objIcon = jQuery('<span class="unite-iconpicker-icon"><i class="fa fa-' + className + ' fa-lg"></i></span>');
			
			//avoid doubles
			if(g_iconsHash.hasOwnProperty(className) == false){
				objIcon.data('name', className);
				objContainer.append(objIcon);
				g_iconsHash[className] = objIcon;
			}
			
		});
		
		//trace(objDialogWrapper);
		
		objDialogWrapper.dialog({
			autoOpen: false,
			height: 500,
			width: 800,
			dialogClass:"unite-ui unite-ui2",
			title: "Choose Icon",
			open: function( event, ui ) {
			  
			  objContainer.scrollTop(0);
			  
			  var objSelectedIcon = objContainer.find('.icon-selected');
			  if (!objSelectedIcon.length) 
				  return(false);
			  
			  if(objSelectedIcon.is(":hidden") == true)
				  return(false);
			  
			  //scroll to icon
			  var containerHeight = objContainer.height();
			  var iconPos = objSelectedIcon.position().top;
			  
			  if(iconPos > containerHeight)
				  objContainer.scrollTop(iconPos - (containerHeight / 2 - 50) );
			}
		
		  });
		
		//init events
		objContainer.on('click', '.unite-iconpicker-icon', function (event) {
				
				objContainer.find('.icon-selected').removeClass('icon-selected');
				var objIcon = jQuery(event.target).closest('.unite-iconpicker-icon');
				objIcon.addClass('icon-selected');
				
				var iconNameStr = objIcon.data('name');
				var iconClass = 'fa fa-' + iconNameStr;
				
				//update picker object
				var objPicker = objDialogWrapper.data("objpicker");
				var objPickerInput = objPicker.find(".unite-iconpicker-input");
				var objPickerButton = objPicker.find(".unite-iconpicker-button");
				
				objPickerInput.val(iconClass);
				objPickerButton.html('<i class="fa fa-' + iconNameStr + ' fa-lg"></i>');
				
				//close dialog
				objDialogWrapper.dialog("close");
		});
		
		//on icon mouseover
		objContainer.on('mouseenter', '.unite-iconpicker-icon', function (event) {
			
			var objIcon = jQuery(event.target).closest('.unite-iconpicker-icon');
			var iconNameStr = objIcon.data('name');
			var iconClass = 'fa-' + iconNameStr;
			objIconName.text(iconClass);
		});
		
		//on icon mouseover
		objContainer.on('mouseleave', '.unite-iconpicker-icon', function (event) {
			objIconName.text("");
		});
		
			
		//filter functionality
		objFilter.on('keyup', function () {
						
			var strFilter = objFilter.val();
			strFilter = jQuery.trim(strFilter);
			
			jQuery(g_ucFaIcons).each(function(index, name){
			  
			  var isVisible = false;
			  if(strFilter == "" || name.indexOf(strFilter) === 0)
				  isVisible = true;
			  
			  if(isVisible == true)
				  g_iconsHash[name].show();
			   else 
				   g_iconsHash[name].hide();
			  
			});
		  })
		  
		
	}
	
	
	/**
	 * init icon picker raw function
	 */
	function initIconPicker(){
		
		var objPickers = g_objParent.find(".unite-settings-iconpicker");
		if(objPickers.length == 0)
			return(false);
				
		//add dialog to the body
		var objDialogWrapper = jQuery('#unite_icon_picker_dialog');
		if(objDialogWrapper.length == 0){
			
			iconPicker_initDialog();
			
			objDialogWrapper = jQuery('#unite_icon_picker_dialog');
		}
		
		//init picker wrappers events
		objPickers.each(function(){
			
			var objPickerWrapper = jQuery(this);
			var objInput = objPickerWrapper.find('input.unite-iconpicker-input');
			var objButton = objPickerWrapper.find('.unite-iconpicker-button');
			
			//on button click
			objButton.click(function () {
				
					if (objDialogWrapper.dialog('isOpen')) {
						objDialogWrapper.dialog('close');
					} else {
						objDialogWrapper.data("objpicker", objPickerWrapper);
						objDialogWrapper.dialog('open');
					}
			});
			
			//on input blur
			
			objInput.on('blur', function () {
				
				var val = jQuery(this).val().substr(6);
				val = jQuery.trim(val);
				
				if (!g_iconsHash[val]){
					objButton.html("choose");
				  return(false);
				}
				
				objButton.html('<i class="fa fa-' + val + ' fa-lg"></i>');
				
				//set selected icon in dialog
				var objContainer = objDialogWrapper.find('.unite-iconpicker-dialog-icons-container');
				
				objContainer.find('.icon-selected').removeClass('icon-selected');
				var objIcon = g_iconsHash[val];
				objIcon.addClass('icon-selected');
				
			});
			
			objInput.trigger("blur");
			
		});
		
	}
	
	function ______POST_PICKER____(){}
	/**
	 * init post picker
	 */
	this.initPostPicker = function(objPickerWrapper){
		var objButtonSelect = objPickerWrapper.find(".uc-button-select-post");
		objButtonSelect.click(function(){
			var objButton = jQuery(this);
			var dialogTitle = objButton.data("dialogtitle");
			g_ucAdmin.validateNotEmpty(dialogTitle, "dialog title");
			g_ucAdmin.openAddPostDialog(dialogTitle, function(response){
				var postID = response.id;
				var postTitle = response.title;
				var objInput = objButton.siblings(".unite-postpicker-input");
				setInputValue(objInput, postID, postTitle);
			});
		});
	}
	/**
	 * init the post picker
	 */
	function initPostPickers(){
		var objPickers = g_objParent.find(".unite-settings-postpicker");
		if(objPickers.length == 0)
			return(false);
		//init picker wrappers events
		objPickers.each(function(){
			var objPickerWrapper = jQuery(this);
			t.initPostPicker(objPickerWrapper);
		});
	}
	
	function _______EVENTS_____(){}
	
	
	/**
	 * update events (in case of ajax set)
	 */
	this.updateEvents = function(){
		
		initSettingsEvents();
		
		initTipsy("s");
		
		if(typeof g_objProvider.onSettingsUpdateEvents == "function")
			g_objProvider.onSettingsUpdateEvents(g_objParent);
		
	};

	
	/**
	 * set on change event, this function should run before init
	 */
	this.setEventOnChange = function(func){
		onEvent(g_events.CHANGE, func);
	}

	
	/**
	 * run on setting change
	 */
	function onSettingChange(){
		
		triggerEvent(g_events.CHANGE);
		
	}

	
	/**
	 * on selects change - impiment the hide/show, enabled/disables functionality
	 */
	function onControlSettingChange(){
		
		var controlValue = this.value.toLowerCase();
		var controlID = this.name;
		
		if(!arrControls[controlID]) 
			return(false);
		
		var arrChildControls = arrControls[controlID];
				
		jQuery(arrChildControls).each(function(){
			var childInputID = this.name;
			
			var objChildInput = jQuery(g_IDPrefix + childInputID);
			
			var objChildRow = jQuery(g_IDPrefix + childInputID + "_row");
			
			if(objChildRow.length == 0)
				return(true);
			
			var value = this.value;
			
			var inputTagName = "";
			if(objChildInput.length)
				inputTagName = objChildInput.get(0).tagName;
			
			var isChildRadio = (inputTagName == "SPAN" && objChildInput.length && objChildInput.hasClass("radio_wrapper"));
			
			switch(this.type){
				case "enable":
				case "disable":
					
					if(objChildInput.length > 0){
						
						//disable
						if(this.type == "enable" && iscValEQ(controlValue,value) == false || this.type == "disable" && iscValEQ(controlValue,value) == true){
							objChildRow.addClass("setting-disabled");
							
							if(objChildInput.length)
								objChildInput.prop("disabled","disabled").css("color","");
							
							if(isChildRadio)
								objChildInput.children("input").prop("disabled","disabled").addClass("disabled");
						}//enable						
						else{	
							
							objChildRow.removeClass("setting-disabled");
							
							if(objChildInput.length)
								objChildInput.prop("disabled","");
							
							if(isChildRadio)
								objChildInput.children("input").prop("disabled","").removeClass("disabled");
							
							//color the input again
							if(objChildInput.length && objChildInput.hasClass("unite-color-picker")) 
								g_colorPicker.linkTo(objChildInput);							
		 				}
						
					}
				break;
				case "show":
					if(iscValEQ(controlValue,value) == true) 
						objChildRow.show();									
					else 
						objChildRow.hide();					
				break;
				case "hide":
					if(iscValEQ(controlValue,value) == true) 
						objChildRow.hide();
					else 
						objChildRow.show();
				break;
			}
			
		});
	}
	
	
	/**
	 * trigger event
	 */
	function triggerEvent(eventName, params){
		if(!params)
			var params = null;
		
		g_objParent.trigger(eventName, params);
	}
	
	
	/**
	 * on event name
	 */
	function onEvent(eventName, func){
		validateInited();				
		g_objParent.on(eventName,func);
	}

	
	/**
	 * combine controls to one object, and init control events.
	 */
	function initControlsEvents(){
		
		//combine controls
		for(key in g_settingsObjUC){
			var obj = g_settingsObjUC[key];
			
			for(controlKey in obj.controls){
				arrControls[controlKey] = obj.controls[controlKey];
			}
		}
		
		//init events
		g_objParent.find("select").change(onControlSettingChange);
		g_objParent.find("input[type='radio']").change(onControlSettingChange);
				
	}
	
	
	/**
	 * init image chooser
	 */
	this.initImageChooser = function(objImageSettings){
		
		if(objImageSettings.length == 0)
			return(false);
				
		objImageSettings.find(".unite-button-choose").click(onChooseImageClick);
		objImageSettings.find(".unite-button-clear").click(onClearImageClick);
		
		var objInput = objImageSettings.find("input");
		
		objInput.change(setImagePreview);
	}
	
	
	/**
	 * init mp3 chooser
	 */
	this.initMp3Chooser = function(objMp3Setting){

		if(objMp3Setting.length == 0)
			return(false);
		
		
		objMp3Setting.find(".unite-button-choose").click(onChooseMp3Click);
	}
	
	
	/**
	 * init settings events
	 */
	function initSettingsEvents(){
		
		var objInputs = g_objParent.find("input,textarea,select").not("input[type='radio']");
		
		objInputs.change(onSettingChange);
		
		var objInputsClick = g_objParent.find("input[type='radio']");
		objInputsClick.click(onSettingChange);
		
		//init image input events
		var objImageSettings = g_objParent.find(".unite-setting-image");
		t.initImageChooser(objImageSettings);
		
		
		//init mp3 input events
		var objMp3Settings = g_objParent.find(".unite-setting-mp3");
		t.initMp3Chooser(objMp3Settings);
		
		initControlsEvents();
	}
	
	
	/**
	 * init global events - not repeating
	 */
	function initGlobalEvents(){
		
		g_ucAdmin.onEvent("update_assets_path", onUpdateAssetsPath);
		
	}
	
	
	/**
	 * init options
	 */
	function initOptions(){
				
		if(!g_objWrapper)
			return(false);
		
		var objOptions = g_objWrapper.data("options");
		
		if(typeof objOptions != "object")
			throw new Error("The options should be an object");
		
		var arrOptions = ["show_saps","saps_type","id_prefix"];
		
		jQuery.each(arrOptions, function(index, optionKey){
			g_options[optionKey] = g_ucAdmin.getVal(objOptions, optionKey, g_options[optionKey]);
		});
		
		if(g_options["id_prefix"])
			g_IDPrefix = "#"+g_options["id_prefix"];
		
	}
	
	
	/**
	 * destroy settings object
	 */
	this.destroy = function(){
				
		g_ucAdmin.offEvent("update_assets_path");

		var objInputs = g_objParent.find("input,textarea,select").not("input[type='radio']");
		
		objInputs.off("change");
		
		var objInputsClick = g_objParent.find("input[type='radio']");
		objInputsClick.off("click");
				
		var objImageSettings = g_objParent.find(".unite-setting-image");
		
		//destroy image events:
		if(objImageSettings.length){
			
			objImageSettings.find(".unite-button-choose").off("click");
			objImageSettings.find(".unite-button-clear").off("click");
			objImageSettings.find("input").off("change");
		}
				
		//destroy control events
		g_objParent.find("select").off("change");
		g_objParent.find("input[type='radio']").off("change");
		
		//destroy loaded events
		g_objParent.off(g_events.CHANGE);
		
		//destroy tabs events
		if(g_objSapTabs)
			g_objSapTabs.children("a").off("click");
		
		g_objProvider.destroyEditors(t);
		
		//null parent object so it won't pass the validation
		if(g_objParent.length){
			g_objParent.html("");
		}
		
		g_objParent = null;
	}
	
	
	/**
	 * set id prefix
	 */
	this.setIDPrefix = function(idPrefix){
		g_IDPrefix = "#"+idPrefix;
	}
	
	
	/**
	 * get id prefix
	 */
	this.getIDPrefix = function(){
		
		return(g_IDPrefix);
	}
	
	
	/**
	 * init the settings function, set the tootips on sidebars.
	 */
	this.init = function(objParent){
				
		if(!g_ucAdmin)
			g_ucAdmin = new UniteAdminUC();
		
		g_objParent = objParent;
		
		//init settings wrapper
		if(g_objParent.hasClass("unite_settings_wrapper") == true)
			g_objWrapper = g_objParent;
		else{
			g_objWrapper = g_objParent.parents(".unite_settings_wrapper");
		}
		
		if(g_objWrapper.length == 0)
			g_objWrapper = g_objParent.children(".unite_settings_wrapper");
		
		if(g_objWrapper.length == 0)
			g_objWrapper = null;
		
		validateInited();
		
		initOptions();
		
		t.initColorPicker();	//put the color picker automatically
		
		initIconPicker();
		
		initPostPickers();
		initGlobalEvents();
		
		t.updateEvents();
		
		initSaps();
				
		clearSettingsInit();
		
		g_objProvider.initEditors(t);
		
	};


} // UniteSettings class end


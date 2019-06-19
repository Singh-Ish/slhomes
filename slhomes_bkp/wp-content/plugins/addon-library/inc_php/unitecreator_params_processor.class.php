<?php
/**
 * @package Addon Library
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ADDON_LIBRARY_INC') or die('Restricted access');

class UniteCreatorParamsProcessorWork{
	
	private $addon;
	const PROCESS_TYPE_CONFIG = "config";	//process for output the config
	const PROCESS_TYPE_OUTPUT = "output";	//process for output
	const PROCESS_TYPE_SAVE = "save";		//process for save
	
	
	/**
	 * validate that the processor inited
	 */
	private function validateInited(){
		
		if(empty($this->addon))
			UniteFunctionsUC::throwError("The params processor is not inited");
		
	}
	
	private function _____________GENERAL_____________(){}
	
	/**
	 * validate process type
	 */
	private function validateProcessType($type){
		UniteFunctionsUC::validateValueInArray($type, "process type",array(
				self::PROCESS_TYPE_CONFIG,
				self::PROCESS_TYPE_SAVE,
				self::PROCESS_TYPE_OUTPUT
		));
	}
	
	
	/**
	 * convert from url assets
	 */
	private function convertFromUrlAssets($value){
	
		$urlAssets = $this->addon->getUrlAssets();
	
		if(!empty($urlAssets))
			$value = HelperUC::convertFromUrlAssets($value, $urlAssets);
	
		return($value);
	}
	
	/**
	 * process param value, by param type
	 * if it's url, convert to full
	 */
	private function converValueByType($value, $type){
	
		if(empty($value))
			return($value);
	
		$value = $this->convertFromUrlAssets($value);
	
		switch($type){
			case "uc_image":
			case "uc_mp3":
				$value = HelperUC::URLtoFull($value);
				break;
		}
	
		return($value);
	}
	
	
	/**
	 * make sure the value is always taken from the options
	 */
	private function convertValueFromOptions($value, $options, $defaultValue){
	
		if(is_array($options) == false)
			return($value);
	
		if(empty($options))
			return($value);
	
		$key = array_search($value, $options, true);
		if($key !== false)
			return($value);
	
		//------- not found
		//in case of false / nothing
		if(empty($value)){
			$key = array_search("false", $options, true);
			if($key !== false)
				return("false");
		}
	
		//if still not found, return default value
		return($defaultValue);
	}
	
	
	/**
	 * construct the object
	 */
	public function init($addon){
	
		//for auto complete
		//$this->addon = new UniteCreatorAddon();
	
		$this->addon = $addon;
	}
	
	private function _____________FONTS______________(){}
	
	
	/**
	 * process the font
	 */
	private function processFont($value, $arrFont){
		
		$this->validateInited();
		
		if(empty($value) || empty($arrFont))
			return($value);
	
		$arrStyle = array();
		$spanClass = "";
		$counter = 0;
		$addStyles = "";
		$arrGoogleFonts = null;
	
		foreach($arrFont as $styleName => $styleValue){
	
			$styleValue = trim($styleValue);
	
			if(empty($styleValue))
				continue;
	
			switch($styleName){
				case "font-family":
					if(strpos($styleValue, " ") !== false && strpos($styleValue, ",") === false)
						$arrStyle[$styleName] = "'$styleValue'";
					else
						$arrStyle[$styleName] = "$styleValue";
					//check google fonts
					if(empty($arrGoogleFonts)){
						$arrFontsPanelData = HelperUC::getFontPanelData();
						$arrGoogleFonts = $arrFontsPanelData["arrGoogleFonts"];
					}
					if(isset($arrGoogleFonts[$styleValue])){
						$urlGoogleFont = "https://fonts.googleapis.com/css?family=".$arrGoogleFonts[$styleValue];
						$this->addon->addCssInclude($urlGoogleFont);
					}
				break;
				case "font-weight":
				case "font-size":
				case "line-height":
				case "text-decoration":
				case "color":
				case "font-style":
					$arrStyle[$styleName] = $styleValue;
				break;
				case "mobile-size":
					//generate id
					//generate id
					if(empty($spanID)){
						$counter++;
						$spanClass = "uc-style-".$counter.UniteFunctionsUC::getRandomString(10,true);
					}
					$css = "@media (max-width:480px){.{$spanClass}{font-size:{$styleValue}}}";
					$this->addon->addToCSS($css);
				break;
				case "custom":
					$addStyles = $styleValue;
				break;
				default:
					UniteFunctionsUC::throwError("Wrong font style: $styleName");
				break;
			}
		}
	
		$style = "";
		if(!empty($arrStyle) || !empty($addStyles))
			$style = UniteFunctionsUC::arrStyleToStrInlineCss($arrStyle, $addStyles);
	
		$htmlAdd = "";
		if(!empty($spanClass))
			$htmlAdd .= "class=\"{$spanClass}\"";
	
		$value = "<span {$htmlAdd} {$style}>$value</span>";
		return($value);
	}
	
	/**
	 * process fonts, type can be main or items
	 */
	private function processFonts($arrValues, $type){
	
		$arrFonts = $this->addon->getArrFonts();
		
		if(empty($arrValues))
			return($arrValues);
		
		switch($type){
			case "main":
				$prefix = "";
				break;
			case "items":
				$prefix = "uc_items_attribute_";
				break;
			default:
				UniteFunctionsUC::throwError("Wrong fonts type: $type");
			break;
		}
	
		foreach($arrValues as $key=>$value){
			
			if(empty($value))
				continue;
			
			$fontsKey = $prefix.$key;
			$arrFont = UniteFunctionsUC::getVal($arrFonts, $fontsKey);
			if(empty($arrFont))
				continue;
			$arrValues[$key] = $this->processFont($value, $arrFont);
		}
	
		return($arrValues);
	}
	
	
	
	private function _____________POST______________(){}
	
	
	/**
	 * get post data
	 */
	protected function getPostData($postID){
		dmp("getPostData: function for override");exit();
	}
	
	
	/**
	 * process image param value, add to data
	 * @param unknown_type $param
	 */
	private function getProcessedParamsValue_post($data, $value, $param, $processType){
		
		$this->validateProcessType($processType);
		
		$postID = $value;
		if(empty($postID))
			return($data);
		
		$name = UniteFunctionsUC::getVal($param, "name");
		
		switch($processType){
			case self::PROCESS_TYPE_CONFIG:		//get additional post title
				
				$postTitle = UniteProviderFunctionsUC::getPostTitleByID($postID);
				$data[$name] = $postID;
				
				if(!empty($postTitle))
					$data[$name."_post_title"] = $postTitle;
				
			break;
			case self::PROCESS_TYPE_SAVE:
				$data[$name] = $postID;
				unset($data[$name."_post_title"]);
			break;
			case self::PROCESS_TYPE_OUTPUT:
				$data[$name] = $this->getPostData($postID);
			break;
		}
				
		return($data);
	}

	
	private function _____________IMAGE______________(){}
	
	
	/**
	 * add other image thumbs based of the platform
	 */
	protected function addOtherImageThumbs($data, $name, $value){
	
		return($data);
	}
	
	/**
	 * get all image related fields to data, but value
	 * create param with full fields
	 */
	protected function getImageFields($data, $name, $value){
		
		if(empty($data))
			$data = array();
		
		//get by param
		$param = array();
		$param["name"] = $name;
		$param["value"] = $value;
		$param["add_thumb"] = true;
		$param["add_thumb_large"] = true;
		
		$data[$name] = $value;
		$data = $this->getProcessedParamsValue_image($data, $value, $param);
		
		return($data);
	}
	
	/**
	 * process image param value, add to data
	 * @param unknown_type $param
	 */
	private function getProcessedParamsValue_image($data, $value, $param){
	
		$name = $param["name"];
		
		$urlImage = $value;		//in case that the value is image id
		if(is_numeric($value)){
			$urlImage = UniteProviderFunctionsUC::getImageUrlFromImageID($value);
			$data[$name] = $urlImage;
		}
		$addThumb = UniteFunctionsUC::getVal($param, "add_thumb");
		$addThumb = UniteFunctionsUC::strToBool($addThumb);
	
		$addThumbLarge = UniteFunctionsUC::getVal($param, "add_thumb_large");
		$addThumbLarge = UniteFunctionsUC::strToBool($addThumbLarge);
	
		if($addThumb == true){
	
			$urlThumb = HelperUC::$operations->getThumbURLFromImageUrl($value, null, GlobalsUC::THUMB_SIZE_NORMAL);
			$data[$name."_thumb"] = $urlThumb;
		}
	
		if($addThumbLarge == true){
	
			$urlThumb = HelperUC::$operations->getThumbURLFromImageUrl($value, null, GlobalsUC::THUMB_SIZE_LARGE);
			$data[$name."_thumb_large"] = $urlThumb;
		}
	
		$data = $this->addOtherImageThumbs($data, $name, $value);
	
	
		return($data);
	}
	
	private function ____________VARIABLES______________(){}
	
	
	/**
	 * process items variables, based on variable type and item content
	 */
	private function getItemsVariablesProcessed($arrItem, $index, $numItems){
	
		$arrVars = $this->addon->getVariablesItem();
		$arrVarData = array();
	
		//get variables output object
		$arrParams = $this->getProcessedMainParamsValues(self::PROCESS_TYPE_SAVE);
		
		$objVarOutput = new UniteCreatorVariablesOutput();
		$objVarOutput->init($arrParams);
	
	
		foreach($arrVars as $var){
			$name = UniteFunctionsUC::getVal($var, "name");
			UniteFunctionsUC::validateNotEmpty($name, "variable name");
	
			$content = $objVarOutput->getItemVarContent($var, $arrItem, $index, $numItems);
	
			$arrVarData[$name] = $content;
		}
	
		return($arrVarData);
	}
	
	
	/**
	 * get main processed variables
	 */
	private function getMainVariablesProcessed($arrParams){
	
		//get variables
		$objVariablesOutput = new UniteCreatorVariablesOutput();
		$objVariablesOutput->init($arrParams);
	
		$arrVars = $this->addon->getVariablesMain();
	
		$arrOutput = array();
	
		foreach($arrVars as $var){
	
			$name = UniteFunctionsUC::getVal($var, "name");
			$content = $objVariablesOutput->getMainVarContent($var);
			$arrOutput[$name] = $content;
		}
	
		return($arrOutput);
	}
	
	private function ________PARAMS_OUTPUT__________(){}
	
	/**
	 * process params - add params by type (like image base)
	 */
	public function initProcessParams($arrParams){
	
		$this->validateInited();
	
		if(empty($arrParams))
			return(array());
	
		$arrParamsNew = array();
		foreach($arrParams as $param){
	
			$type = UniteFunctionsUC::getVal($param, "type");
			switch($type){
				case "uc_imagebase":
					$settings = new UniteCreatorSettings();
					$settings->addImageBaseSettings();
					$arrParamsAdd = $settings->getSettingsCreatorFormat();
					foreach($arrParamsAdd as $addParam)
						$arrParamsNew[] = $addParam;
					break;
				default:
					$arrParamsNew[] = $param;
				break;
			}
	
		}
	
		return($arrParamsNew);
	}
	
	
	/**
	 * process params for output it to settings html
	 * update params items for output
	 */
	public function processParamsForOutput($arrParams){
	
		$this->validateInited();
	
		if(is_array($arrParams) == false)
			UniteFunctionsUC::throwError("objParams should be array");
	
		foreach($arrParams as $key=>$param){
	
			$type = UniteFunctionsUC::getVal($param, "type");
	
			if(isset($param["value"]))
				$param["value"] = $this->converValueByType($param["value"], $type);
	
			if(isset($param["default_value"]))
				$param["default_value"] = $this->converValueByType($param["default_value"], $type);
	
			//make sure that the value is part of the options
			if(isset($param["value"]) && isset($param["default_value"]) && isset($param["options"]) && !empty($param["options"]) )
				$param["value"] = $this->convertValueFromOptions($param["value"], $param["options"], $param["default_value"]);
	
			$arrParams[$key] = $param;
		}
	
		return($arrParams);
	}
	
	
	
	private function _____________VALUES_OUTPUT_OUTPUT______________(){}
	
	
	
	/**
	 * get processed params
	 * @param $objParams
	 */
	public function getProcessedParamsValues($arrParams, $processType, $filterType = null){
		
		$this->validateProcessType($processType);
		
		$arrParams = $this->processParamsForOutput($arrParams);
		
		$data = array();
	
		foreach($arrParams as $param){
	
			$type = UniteFunctionsUC::getVal($param, "type");
	
			if(!empty($filterType)){
				if($type != $filterType)
					continue;
			}
			
			$name = UniteFunctionsUC::getVal($param, "name");
	
			$defaultValue = UniteFunctionsUC::getVal($param, "default_value");
			$value = $defaultValue;
			if(array_key_exists("value", $param))
				$value = UniteFunctionsUC::getVal($param, "value");
	
			$value = $this->converValueByType($value, $type);
	
			if(empty($name))
				continue;
	
			if(isset($data[$name]))
				continue;
	
			if($type != "imagebase_fields")
				$data[$name] = $value;
	
			//special params
			switch($type){
				case "uc_image":
					$data = $this->getProcessedParamsValue_image($data, $value, $param);
				break;
				case UniteCreatorDialogParam::PARAM_POST:
					$data = $this->getProcessedParamsValue_post($data, $value, $param, $processType);
				break;
			}
		}
	
	
		return($data);
	}
	
	
	/**
	 * get main params processed, for output
	 */
	public function getProcessedMainParamsValues($processType){
		
		$this->validateInited();
		
		$this->validateProcessType($processType);
		
		$objParams = $this->addon->getParams();
	
		$arrParams = $this->getProcessedParamsValues($objParams, $processType);
		
		$arrVars = $this->getMainVariablesProcessed($arrParams);
		
		if($processType == self::PROCESS_TYPE_OUTPUT)
			$arrParams = $this->processFonts($arrParams, "main");
		
		$arrParams = array_merge($arrParams, $arrVars);
		
		return($arrParams);
	}
	
	
	/**
	 * get item data
	 */
	public function getProcessedItemsData($arrItems, $processType, $forTemplate = true, $filterType = null){
	
		$this->validateInited();
		$this->validateProcessType($processType);
	
		if(empty($arrItems))
			return(array());
	
		$operations = new UCOperations();
	
		$arrItemsNew = array();
		$arrItemParams = $this->addon->getParamsItems();
		$arrItemParams = $this->initProcessParams($arrItemParams);
		
		$numItems = count($arrItems);
	
		foreach($arrItems as $index => $arrItemValues){
	
			$arrParamsNew = $this->addon->setParamsValuesItems($arrItemValues, $arrItemParams);
	
			$item = $this->getProcessedParamsValues($arrParamsNew, $processType, $filterType);
			
			if($processType == self::PROCESS_TYPE_OUTPUT)
				$item = $this->processFonts($item, "items");
			
			//in case of filter it's enought
			if(!empty($filterType)){
	
				$arrItemsNew[] = $item;
				continue;
			}
	
			//add constants
			$indexOutput = ($index+1);
			$itemID = $indexOutput;
	
			$addData = array(
					"item_index" => $indexOutput,
					"item_id" => $itemID
			);
	
			$item = array_merge($addData, $item);
	
			//add values by items type
	
			$itemsType = $this->addon->getItemsType();
	
			switch($itemsType){
				case UniteCreatorAddon::ITEMS_TYPE_IMAGE:
					//add thumb
					$urlImage = UniteFunctionsUC::getVal($item, "image");
					$urlThumb = $operations->createThumbs($urlImage);
					$urlThumb = HelperUC::URLtoFull($urlThumb);
					$item["thumb"] = $urlThumb;
					break;
			}
	
			//add item variables
			$arrVarsData = $this->getItemsVariablesProcessed($item, $index, $numItems);
			$item = array_merge($item, $arrVarsData);
	
			if($forTemplate == true)
				$arrItemsNew[] = array("item"=>$item);
			else
				$arrItemsNew[] = $item;
		}

		
		return($arrItemsNew);
	}
	
		
}
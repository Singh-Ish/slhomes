<?php

/**
 * 
 * outputs all related to table with paging
 *
 */
class UniteTableUC{
	
	const GET_FIELD_PAGE = "table_page";
	const GET_FIELD_INPAGE = "table_inpage";
	const GET_FIELD_SEARCH = "table_search";
	const GET_FIELD_CATEGORY = "table_category";
	
	const GET_FIELD_OPTION = "option";
	const GET_FIELD_VIEW = "view";
	
	
	private $isPaging = false;
	private $isInsideTopActions = false;
	
	private $page;
	private $inPage;
	private $total;
	private $numPages;
	private $baseUrl;
	
	private $defaultInPage = 10;
		
	
	/**
	 * validate that the paging is inited
	 */
	private function validatePaging(){
		
		if($this->isPaging == false)
			UniteFunctionsUC::throwError("The paging should be available");
		
	}

	/**
	 * validate that it's inside top actions
	 */
	private function validateTopActions(){
		
		if($this->isInsideTopActions == false)
			UniteFunctionsUC::throwError("The top actions form should be started");
		
	}
	
	/**
	 * get page from get
	 */
	private function getPageFromGet(){
		
		$page = UniteFunctionsUC::getGetVar(self::GET_FIELD_PAGE,1,UniteFunctionsUC::SANITIZE_ID);
		$page = (int)$page;
		
		return($page);
	}
	
	/**
	 * get inpage from get
	 */
	private function getInPageFromGet(){
		$inpage = UniteFunctionsUC::getGetVar(self::GET_FIELD_INPAGE, $this->defaultInPage, UniteFunctionsUC::SANITIZE_ID);
		$inpage = (int)$inpage;
		
		return($inpage);
	}
	
		
	
	
	
	/**
	 * get all available get fields
	 */
	private function getGetFieldsNames($includeBaseFields = false){
		
		$fields = array(
				self::GET_FIELD_PAGE,
				self::GET_FIELD_INPAGE,
				self::GET_FIELD_SEARCH,
				self::GET_FIELD_CATEGORY				
		);
		
		if($includeBaseFields == true){
			$fields[] = self::GET_FIELD_OPTION;
			$fields[] = self::GET_FIELD_VIEW;
		}
		
		return($fields);
	}
	
	
	/**
	 * get array of fields from get
	 */
	private function getArrGetFields($includeBaseFields = false){
		
		$arrFields = array();
		$fieldNames = $this->getGetFieldsNames($includeBaseFields);
		
		foreach($fieldNames as $name){
			
			$fieldValue = UniteFunctionsUC::getGetVar($name, "", UniteFunctionsUC::SANITIZE_TEXT_FIELD);
			if(!empty($fieldValue))
				$arrFields[$name] = $fieldValue;
		}
		
		return($arrFields);
	}
	
	
	/**
	 * get page url
	 */
	private function getUrlPage($page){
		
		$arrGetFields = $this->getArrGetFields();
		
		$urlPage = UniteFunctionsUC::addUrlParams($this->baseUrl, $arrGetFields);
				
		return($urlPage);
	}
	
	/**
	 * get all hidden fields html
	 */
	private function getHtmlHiddenFields(){
	
		$arrGetFields = $this->getArrGetFields(true);
		$html = "";
	
		foreach($arrGetFields as $name=>$value)
			$html .= HelperHtmlUC::getHiddenInputField($name, $value);
	
		return($html);
	}
	
	
	/**
	 * get input with count content, about 10,25,50,100
	 */
	public function getHTMLInpageSelect(){
	
		$inpage = $this->getInPageFromGet();
	
		$arrNumbers = array(
				"10","25","50","100"
		);
	
		$fieldInpage = self::GET_FIELD_INPAGE;
	
		$htmlSelect = HelperHtmlUC::getHTMLSelect($arrNumbers, $inpage, "name='{$fieldInpage}' class='unite-tableitems-selectrecords' onchange='this.form.submit()'");
		$htmlGetFields = $this->getHtmlHiddenFields();
	
		$html = "";
		$html .= "<form method='get'>";
		$html .= $htmlSelect;
		$html .= $htmlGetFields;
		$html .= '</form>';
	
		return $html;
	}
	
	/**
	 * get pagination html
	 */
	public function getPaginationHtml(){
	
		$this->validatePaging();
	
		$item_per_page = $this->inPage;
		$current_page = $this->page;
		$total_records = $this->total;
		$total_pages = $this->numPages;
		$page_url = $this->baseUrl;
	
		$isShowExtras = true;
	
		$pagination = '';
		if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
			$pagination .= '<ul class="unite-pagination class-for-pagination">';
	
			$right_links    = $current_page + 8;
			$previous       = $current_page - 1; //previous link
			$next           = $current_page + 1; //next link
			$first_link     = true; //boolean var to decide our first link
	
			//put first and previous
			if($current_page > 1 && $isShowExtras == true){
				$previous_link = ($previous==0)?1:$previous;
	
				$urlFirst = $this->getUrlPage(1);
				$urlPrev = $this->getUrlPage($previous_link);
	
				$titleFirst = __("First", ADDONLIBRARY_TEXTDOMAIN);
				$titlePrev = __("Previous", ADDONLIBRARY_TEXTDOMAIN);
	
				$textFirst = "";
				$textPrev = "";
	
				$pagination .= '<li class="unite-first"><a href="'.$urlFirst.$search.'" title="'.$titleFirst.'" > &laquo; '.$textFirst.'</a></li>'; //first link
				$pagination .= '<li><a href="'.$urlPrev.$search.'" title="'.$titlePrev.'">&lt; '.$textPrev.'</a></li>'; //previous link
	
				for($i = ($current_page-3); $i < $current_page; $i++){ //Create left-hand side links
					if($i > 0){
						$urlPage = $this->getUrlPage($i);
	
						$pagination .= '<li><a href="'.$urlPage.$search.'">'.$i.'</a></li>';
					}
				}
				$first_link = false; //set first link to false
			}
	
			if($first_link){ //if current active page is first link
				$pagination .= '<li class="unite-first unite-active"><a href="javascript:void(0)">'.$current_page.'</a></li>';
			}elseif($current_page == $total_pages){ //if it's the last active link
				$pagination .= '<li class="unite-last unite-active"><a href="javascript:void(0)">'.$current_page.'</a></li>';
			}else{ //regular current link
				$pagination .= '<li class="unite-active"><a href="javascript:void(0)">'.$current_page.'</a></li>';
			}
	
			for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
				if($i<=$total_pages){
	
					$urlPage = UniteFunctionsUC::addUrlParams($page_url, "table_page={$i}");
	
					$pagination .= '<li><a href="'.$urlPage.$search.'">'.$i.'</a></li>';
				}
			}
	
			//show first / last
			if($current_page < $total_pages && $isShowExtras == true){
	
				//next and last pages
				$next_link = ($i > $total_pages)? $total_pages : $i;
	
				$urlNext = $this->getUrlPage($next_link.$search);
				$urlLast = $this->getUrlPage($total_pages.$search);
	
				$titleNext = __("Next Page", ADDONLIBRARY_TEXTDOMAIN);
				$titleLast = __("Last Page", ADDONLIBRARY_TEXTDOMAIN);
	
				$textNext = "";
				$textLast = "";
	
				$pagination .= "<li><a href=\"{$urlNext}\" title=\"$titleNext\" >{$textNext} &gt;</a></li>";
				$pagination .= "<li class=\"unite-last\"><a href=\"{$urlLast}\" title=\"$titleLast\" >{$textLast} &raquo; </a></li>";
			}
	
			$pagination .= '</ul>';
		}
	
		return($pagination);
	}
	
	/**
	 * draw table pagination
	 */
	public function putPaginationHtml(){
		$this->validatePaging();
	
		$html = $this->getPaginationHtml();
	
		echo $html;
	}
	
	/**
	 * put inpage select
	 */
	public function putInpageSelect(){
		$this->validatePaging();
	
		$html = $this->getHTMLInpageSelect();
	
		echo $html;
	}
	
	/**
	 * get paging options from get and default
	 */
	public function getPagingOptions(){
	
		$output = array();
		$output["page"] = $this->getPageFromGet();
		$output["inpage"] = $this->getInPageFromGet();
	
		return($output);
	}

	
	/**
	 * set paging data
	 */
	public function setPagingData($baseURl, $data){
	
		$this->baseUrl = $baseURl;
	
		$this->total = UniteFunctionsUC::getVal($data, "total");
		$this->page = UniteFunctionsUC::getVal($data, "page");
		$this->inPage = UniteFunctionsUC::getVal($data, "inpage");
		$this->numPages = UniteFunctionsUC::getVal($data, "num_pages");
	
		UniteFunctionsUC::validateNotEmpty($this->inPage, "in page");
		if($this->total > 0){
			UniteFunctionsUC::validateNotEmpty($this->page, "page");
			UniteFunctionsUC::validateNotEmpty($this->numPages, "num pages");
		}
	
		$this->isPaging = true;
	}

	
	/**
	 * put actions form end
	 */
	public function putActionsFormStart(){
		$this->validatePaging();
				
		$url = $this->baseUrl;
		$url = htmlspecialchars($url);
		
		$html = "";
		$html .= "<form method='get' name='unite-table-actions' action='{$url}'>";
		
		echo $html;
		
		$this->isInsideTopActions = true;
		
	}
	
	
	/**
	 * put actions form start
	 */
	public function putActionsFormEnd(){
		
		$this->validateTopActions();
		
		$html = "</form>";
		
		$this->isInsideTopActions = false;
		
		echo $html;
	}
	
	
	/**
	 * function for search content and sorting
	 */
	public function putSearchButton($buttonText = "", $clearText = ""){
		
		//the button must be inside top actions
		$this->validateTopActions();
		
		$html = "";
		
		$searchValue = "";
		
		if(empty($buttonText))
			$buttonText = __("Search", ADDONLIBRARY_TEXTDOMAIN);

		if(empty($clearText))
			$clearText = __("Clear input", ADDONLIBRARY_TEXTDOMAIN);
		
		$html .= "	<input name='search' type='text' class='unite-input-medium mbottom_0 unite-cursor-text' value=\"{$searchValue}\"/> ";
		$html .= "	<button id='unite_action_form_search' class='unite-button-primary' type='submit' value='1'>".$buttonText."</button>";
		
		if(!empty($searchValue))
			$html .= "	<a href=\"{$url}\" class=\"unite-button-secondary\">".$clearText."</a>";
		
		
		echo $html;
	}
	
	
	/**
	 * put filter category input
	 */
	public function putFilterCategoryInput(){
		
		$objCats = new UniteCreatorCategories;
		$arrCats = $objCats->getCatsShort("all_uncat_layouts", "layout");
		
		$html = "";
		
		$html .= HelperHtmlUC::getHTMLSelect($arrCats, $filterCategory,"name='category'", true);
		
		return($html);
	}
	
}
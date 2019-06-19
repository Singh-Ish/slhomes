<?php
/**
 * @package Addon Library
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ADDON_LIBRARY_INC') or die('Restricted access');

class UniteCreatorParamsProcessor extends UniteCreatorParamsProcessorWork{
	
	
	/**
	 * get post data
	 */
	protected function getPostData($postID){
		
		if(empty($postID))
			return(null);
		
		try{
		
			$output = array();
			$output["id"] = $postID;
			
		}catch(Exception $e){
			return(null);
		}
		
		return($output);
	}
	
	
}
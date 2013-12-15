<?php
/**
* @copyright		Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license				GNU/GPL, see LICENSE.php
* @package			PayPlans
* @subpackage	API
* @contact 				payplans@readybytes.in
*/
if(defined('_JEXEC')===false) die();


/**
 * These functions are listed for Group object
 * @author Puneet Singhal
 *
 */
interface PayplansIfaceApiGroup
{
	/** 
	 * Gets the title of the group
	 * 
	 * @return string Title of the group
	 */
	public function getTitle();
    
	/**
	 * Gets the css classes which will be applied on the current group while displaying it at frontend
	 * 
	 * @return string  css class applied on the group
	 */
	public function getCssClasses();
	
	/**
	 * Gets the teaser-text of the group
	 * 
	 * @return string  Teaser-text of the group
	 */
	public function getTeasertext();
	
	/**
	 * Gets the description of the group
	 * 
	 * @return string  Description of the group
	 */
    public function getDescription();
    
    /**
     * Gets the published status of the group
     * 
	 * @return integer  1 when group is published
	 */
    public function getPublished();

    /**
     * Gets the visibility status of the group
     * 
	 * @return integer 1 when group is visible else 0
	 */
    public function getVisible();
    
    /**
     * Gets the group identifier with which the currect group is attached as a child
     * 
	 * @return integer Parent group identifier of the current group
	 */
	public function getParent();
}

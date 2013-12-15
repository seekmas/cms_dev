<?php
/**
* @copyright	Copyright (C) 2009 - 2009 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
*/
if(defined('_JEXEC')===false) die();


/**
 * Access Control is centred around the resource concept.
 * Resource : Any enitity which will be accessed by someone
 *     e.g. Profile, Photo, Video, File, Listing, Article, Category
 * Accessor : The user who is trying to access the resource
 *     Mostly logged in user
 * Owner    : The resource was created by or bleongs to owner
 *     User who owns Profile, Photo, Video, File, Listing, Article, Category etc.
 *     
 * @author ssv445
 *
 */
interface PayplansIfaceAppAccess
{
	// Identify the resource and return id of it. 
	// if no resource then return false
	public function getResource();
	
	// who is trying to access to the resource
	public function getResourceAccessor();
	
	// Who own this resource
	public function getResourceOwner();
	
	// how many resource is owned by accessor 
	public function getResourceCount();
	
	// is accessor violating this rule
	public function isViolation();
	
	// user is trying to violate rule, lets stop 
	public function handleViolation();
}

<?php

/**
 * @package SL Developer Info
 * @version 1.3.3
 * @author Stephen Lewis (http://experienceinternet.co.uk/)
 * @copyright Copyright (c) 2009, Stephen Lewis
 * @license http://creativecommons.org/licenses/by-sa/3.0 Creative Commons Attribution-Share Alike 3.0 Unported
 * @link http://experienceinternet.co.uk/resources/details/sl-developer-info/
*/

if ( ! defined('EXT'))
{
	exit('Invalid file request');
}

class Sl_developer_info
{
	/**
	 * The data returned from the module.
	 * @var return_data
	 */
	var $return_data = '';
	
	
	/**
	 * PHP4 constructor.
	 * @see __construct
	 */
	function Sl_developer_info()
	{
		$this->__construct();
	}
	
	
	/**
	 * PHP5 constructor.
	 */
	function __construct()
	{
		$this->return_data = '';
	}	
}

?>
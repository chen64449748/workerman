<?php 

/**
* 
*/
class Admin
{
	protected $pwd;
	function __construct()
	{
		$this->pwd = md5('admin');
	}
}
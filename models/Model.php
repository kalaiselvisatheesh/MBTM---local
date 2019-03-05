<?php
class Model extends Database
{
    /*Table Name*/
    var $magazineTable              			=   "magazines";
	
	
    /*Table Name*/
	function __construct()
	{
		global $globalDbManager;
		$this->dbConnect = $globalDbManager->dbConnect;
	}
}?>
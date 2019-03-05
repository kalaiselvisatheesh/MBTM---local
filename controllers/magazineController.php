<?php
class MagazineController extends Controller
{
   function getTotalRecordCount()
	{
		if (!isset($this->MagazineModelObj))
			$this->loadModel('MagazineModel', 'MagazineModelObj');
		if ($this->MagazineModelObj)
			return $this->MagazineModelObj->getTotalRecordCount();
	}
	function getMagazinesList($fields,$condition,$limit)
	{
		if (!isset($this->MagazineModelObj))
			$this->loadModel('MagazineModel', 'MagazineModelObj');
		if ($this->MagazineModelObj)
			return $this->MagazineModelObj->getMagazinesList($fields,$condition,$limit);
	}
	function getMagazineYear($condition)
	{
		if (!isset($this->MagazineModelObj))
			$this->loadModel('MagazineModel', 'MagazineModelObj');
		if ($this->MagazineModelObj)
			return $this->MagazineModelObj->getMagazineYear($condition);
	}
	
}
?>
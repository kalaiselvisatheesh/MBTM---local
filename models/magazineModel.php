<?php
class MagazineModel extends Model
{
    function getMagazinesList($fields,$condition,$limit)
	{
		$limit_clause='';
		$limitval = 0;
		if($limit != 0){
			$limitval = $limit * 21;			
		}
		$limit_clause = ' limit '.$limitval.',21';
		$sorting_clause = ' publishDate desc';
		$sql = "select SQL_CALC_FOUND_ROWS ".$fields." from {$this->magazineTable} as m	
						WHERE 1".$condition." ORDER BY ".$sorting_clause." ".$limit_clause;
		$result	=	$this->sqlQueryArray($sql);
		if(count($result) == 0) return false;
		else return $result;
	}	
   
    function getTotalRecordCount()
	{
		$result = $this->sqlCalcFoundRows();
        return $result;
	}
	
	function getMagazineYear($condition)
	{
		$sorting_clause = ' publishDate desc';
		$sql = "select year(publishDate) as publishYear from {$this->magazineTable} 
						WHERE 1".$condition." group by publishDate ORDER BY ".$sorting_clause;						
		$result	=	$this->sqlQueryArray($sql);
		if(count($result) == 0) return false;
		else return $result;
	}
	
}
?>
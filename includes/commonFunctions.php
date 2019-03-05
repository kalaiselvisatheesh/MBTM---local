<?php
ini_set('default_encoding','utf-8');
ini_set('max_execution_time', 600); 

if (!ini_get('display_errors')) {
    ini_set('display_errors', '0');
}
if(SERVER)	{
	error_reporting(0);
}
else {
		ini_set('display_errors', '1');
		error_reporting(E_ALL);
}
//
function admin_login_check()
{
    //if(!isset($_SESSION['carfit_admin_user_name']) && !isset($_SESSION['carfit_sess_user_email']) ){
    if(!isset($_SESSION['carfit_admin_user_name']) && !isset($_SESSION['carfit_admin_user_email']) ){
		header('location:index.php');
		die();
    }
}

function encode($id) {
	return dechex(($id + 5) * 101);
}
function decode($id) {
	return hexdec($id)/101-5;
}
/* Function to convert seconds to (hours :mintutes :seconds) */
function secondsToTime($seconds) {
  // extract hours
  $hours = floor($seconds / (60 * 60));
  // extract minutes
  $divisorForMinutes = $seconds % (60 * 60);
  $minutes = floor($divisorForMinutes / 60);
  // extract the remaining seconds
  $divisorForSeconds = $divisorForMinutes % 60;
  $seconds = ceil($divisorForSeconds);
  // return the final array
  $timearr = array(
      "hours" => (int) $hours,
      "minutes" => (int) $minutes,
      "seconds" => (int) $seconds,
   );
  return $timearr;
}
//calculating score for trip
function calculatingScore($violation,$distance){
	$overSpeed 			=	$violation['MaxVechileSpeed'] - $violation['MaxRoadSpeed'];
	$reduceDistance		=   $distance - $violation['Violationcount'];
	$scores				=	(($reduceDistance / $distance)*100) - $overSpeed ; 
	//if scores is negative value
	if($scores < 0)  $scores = 0; 
	return $scores;	
}

//to get address from latitude and longtitude
function getAddress($latitude, $longitude){
	
   $url  = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".
            $latitude.",".$longitude."&sensor=false";
   $json = @file_get_contents($url);
   $data = json_decode($json);
   $status = $data->status;
   $address = '';
   if($status == "OK"){
      $address = $data->results[0]->formatted_address;
    }
	if(!empty($address))   {
		return $address;
	}
  }

function dateValidation($date){
	$result = 0;
	$date  = explode('/', $date);
	if (count($date) == 3) {
		if($date[0] != '' && $date[1] != '' && $date[2] != ''){
			if($date[2] >= '1983'){
			    if (checkdate($date[0], $date[1], $date[2]))
				    $result = 1;
			   	else 
			       $result = 0;
			}
			else 
		       $result = 0;
		}
		else {
		    $result = 0;
		}
	} 
	else {
	    $result = 0;
	}	
	return $result;
}
// To get page name 
function getCurrPage()
{
	$page = substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
	$page=explode('?',$page);
	if(is_array($page))
		$page=$page[0];
	return $page;
}
 
function displayText($text, $length) {
	if (strlen($text) > $length) return strip_tags(substr($text, 0, $length)).' ...'; else return $text;
}

/********************************************************
  * Function Name: escapeSpecialCharacters
  * Purpose: Escapes special characters in a string for use in an SQL statement
  * $data   - array or text
  *******************************************************/
function escapeSpecialCharacters($data)
{
	//$data = trim($data);
	if (get_magic_quotes_gpc())
		return $data; //No need to escape data if magic quotes is turned on
	$data = is_array($data) ? array_map('escapeSpecialCharacters', $data) : addslashes(trim($data));//mysql_real_escape_string
    return $data;
}
/********************************************************
  * Function Name: unEscapeSpecialCharacters
  * Purpose: UnEscapes special characters in a string for use in an SQL statement
  * $data   - array or text
  *******************************************************/
function unEscapeSpecialCharacters($data)
{
	if (get_magic_quotes_gpc())
		return $data; //No need to escape data if magic quotes is turned on
	//$data = is_array($data) ? array_map('unEscapeSpecialCharacters', $data) :stripslashes(utf8_encode(trim($data)));
	$data = is_array($data) ? array_map('unEscapeSpecialCharacters', $data) :stripslashes((trim($data)));
    return $data;
}
function sendMail($mailContentArray,$type)
{
	//error_reporting(0);
	if(is_array($mailContentArray))
	{
		$heardFrom		= 	'';
		$message		=	'';
		$from 	  		=   $mailContentArray['from'];
		$to   		    =   $mailContentArray['toemail'];
		$subject		= 	$mailContentArray['subject'];
		$sitelinkpath	=	SITE_PATH.'/admin/webresources/mail_content/';		
		$filename       = 	ABS_PATH.'/admin/webresources/mail_content/'.$mailContentArray['fileName'];
		$dravaPath		=	DRAVA_PATH;
		$termsPath		=	TERMS_OF_SERVICE_PATH;
		$privacyPath	=	PRIVACY_PATH;
		$contactPath	=	CONTACT_PATH;
		$mailData 		= 	file_get_contents($filename);
		$filearray 		= 	explode('/',$mailContentArray['fileName']);
		$typearray 		= 	end($filearray);
		$typeextn 		= 	explode('.',$typearray);
		$sitepath		=	SITE_PATH;
		$acceptlink		= 	$declinelink = '';
		if(isset($mailContentArray['acceptLink']))
			$acceptlink		=	SITE_PATH.'/'.$mailContentArray['acceptLink'];
		if(isset($mailContentArray['declineLink']))
			$declinelink	= 	SITE_PATH.'/'.$mailContentArray['declineLink'];		
		switch($type)
		{
			case 1:
				//Accept/Decline process
				$mailData 			=	str_replace('{NAME}', $mailContentArray['name'], $mailData);
				//$mailData 			=	str_replace('{LINK}',  $mailContentArray['link'], $mailData);
				$mailData 			=	str_replace('{SITEPATH}',  $sitepath, $mailData);
				$mailData 			=	str_replace('{SITE_MAIL_PATH}',  $sitelinkpath, $mailData);
				$mailData 			=	str_replace('{MAIL_SUBJECT}',  $mailContentArray['subject'], $mailData);
				$mailData 			=	str_replace('{ACCEPT_LINK}', $acceptlink, $mailData);
				$mailData 			=	str_replace('{DECLINE_LINK}', $declinelink, $mailData);
				break;
			case 2:
				//Invitation for new user
				$mailData 			=	str_replace('{NAME}', $mailContentArray['name'], $mailData);
				//$mailData 			=	str_replace('{USERNAME}', $mailContentArray['email'], $mailData);				
				$mailData 			=	str_replace('{SITE_MAIL_PATH}',  $sitelinkpath, $mailData);
				$mailData 			=	str_replace('{MAIL_SUBJECT}',  $mailContentArray['subject'], $mailData);
				$mailData 			=	str_replace('{REFERRAL_CODE}',  $mailContentArray['referralCode'], $mailData);
				$mailData 			=	str_replace('{APP_URL}',  APP_URL, $mailData);
				break;
			case 3:
				//User Registration
				$mailData 			=	str_replace('{NAME}', $mailContentArray['name'], $mailData);
				$mailData 			=	str_replace('{SITE_MAIL_PATH}',  $sitelinkpath, $mailData);
				$mailData 			=	str_replace('{MAIL_SUBJECT}',  $mailContentArray['subject'], $mailData);
				$mailData 			=	str_replace('{MENTEE_EMAIL}',  $mailContentArray['mentee_email'], $mailData);
				$mailData 			=	str_replace('{MENTEE_PHONE}',  $mailContentArray['mentee_phone'], $mailData);
				$mailData 			=	str_replace('{MENTOR_EMAIL}',  $mailContentArray['mentor_email'], $mailData);
				$mailData 			=	str_replace('{MENTOR_PHONE}',  $mailContentArray['mentor_phone'], $mailData);
				break;
		}
		$mail_image 	= 	SITE_PATH.'/webresources/mail_content/';
		$mailData 		=	str_replace('{DRAVA_BIZ}',  		$dravaPath, $mailData);
		$mailData 		=	str_replace('{TERMS_OF_SERVICE}',  	$termsPath, $mailData);
		$mailData 		=	str_replace('{PRIVACY}',  			$privacyPath, $mailData);
		$mailData 		=	str_replace('{CONTACT}', 			$contactPath, $mailData);
		$mailData 		=	str_replace('{SITE_PATH}', 			$sitelinkpath ,$mailData);
		$mailData		=   str_replace('{YEAR}', date('Y'), $mailData);
		$headers  		= 	"MIME-Version: 1.0\n";
		$headers 		.= 	"Content-Transfer-Encoding: 8bit\n";
		$headers        .= 	"From: $from\r\n";
		$headers 		.= 	"Content-type: text/html\r\n";
		if ($_SERVER['HTTP_HOST'] == '172.21.4.104'){
			if($_SERVER['REMOTE_ADDR'] == '172.21.4.104'){
				echo "=======sitelinkpath==========>".$sitelinkpath;
				echo "<pre>"; print_r($mailData); echo "</pre>";
				//die;
			}		
			//$sendmail = sendMailSes($from,$to,$subject,$mailData,'');
		}
		else {
			$toArray 		=   explode(',',$to);
			//mail($to,$subject,$mailData,$headers);
			//$sendmail = mailThroughAmazon($from,$to,$subject,$mailData,'','','','','','','','');
			$sendmail = sendMailSes($from,$toArray,$subject,$mailData,'');
		}
		
	}
}
/********************************************************
* Function : displayDate
********************************************************/
function displayConversationDateTimeForLog($postedDate,$time_zone='',$type='')
{
	//$endDate = '2012-07-24 14:00:01';
	if($postedDate != '')
	{
		$db_date	=	date('d',strtotime($postedDate));
		$db_month	=	date('m',strtotime($postedDate));
		$db_year	=	date('Y',strtotime($postedDate));
		$db_hour	=	date('h',strtotime($postedDate));
		$db_min		=	date('i',strtotime($postedDate));
		$db_sec		=	date('s',strtotime($postedDate));
		$meridian	=	date('a',strtotime($postedDate));
		if($time_zone != '') {
			if(substr($time_zone,0,1) == '-') {
				$multiplier = -1;
				$time_zone = substr($time_zone,1);
			}
			else
				$multiplier = 1;
			$offset_array 	= explode(':',$time_zone);
			$hour			= $offset_array[0];
			$minutes		= $offset_array[1];
			$offset 		= $multiplier*(($hour*60*60)+($minutes*60));
			$c_date 		= strtotime(date('Y-m-d H:i:s'))+$offset;
			$cur_date		= date('d',$c_date);
			$cur_month		= date('m',$c_date);
			$cur_year		= date('Y',$c_date);
		}
		else {
			$cur_date	=	date('d');
			$cur_month	=	date('m');
			$cur_year	=	date('Y');
		}
		if($type == 1)
			$time =	$db_month.'/'.$db_date.'/'.$db_year;
		else
			$time =	$db_month. '/'.$db_date.'/'.$db_year.'&nbsp;&nbsp;<br>'.$db_hour.':'.$db_min.':'.$db_sec.' '.$meridian;
	}
	else
		$time = 'Null';
	return $time;
}
//***convertIntocheckinGmtSite**//
function convertIntocheckinGmtSite($meet_time, $time_zone=''){
	 if($time_zone=='') {
	 	if(!isset($_SESSION['carfit_ses_from_timeZone']) || $_SESSION['carfit_ses_from_timeZone'] == ''){
			 $time_zone = getTimeZone();
			 $_SESSION['carfit_ses_from_timeZone'] = strval($time_zone);
		} else {
			$time_zone = $_SESSION['carfit_ses_from_timeZone'];
		}
	 }
	 if(substr($time_zone,0,1) == '-') {
	 	$multiplier = -1;
		$time_zone = substr($time_zone,1);
	 }
	 else
	 	$multiplier = 1;
	if($time_zone=='' || $time_zone=='0')
		$offset = 0;
	else{
		$offset_array = explode(':',$time_zone);
		$hour= $offset_array[0];
		$minutes= $offset_array[1];
		$offset = $multiplier*(($hour*60*60)+($minutes*60));
	}
	if($meet_time != '') {
		$date = strtotime(gmdate($meet_time))+$offset;
	}
	else{
		$date = strtotime(gmdate('Y-m-d H:i:s'))+$offset;
	}
	//echo '<pre>';print_r($date);echo '</pre>';
	return date('Y-m-d H:i:s',$date);
}
//**********************//

function sendMailSes($from,$to,$subject,$html_message,$text_msg=''){
	
	require_once('sdk.class.php');// Include the SDK
	
	$ses = new AmazonSES();//// Instantiate the  class
	//$region = $ses->set_region(REGION_US_W2);
	//echo '<pre>=to==>';print_r($to);echo '<===</pre>';
	//echo '<pre>==from=>';print_r($from);echo '<===</pre>';
	$result = $ses->send_email($from, array(
		        'ToAddresses' => $to,
		    	), 
				array(
				        // Subject is required
				        'Subject' => array(
				            // Data is required
				            'Data' => $subject,
				            'Charset' => 'utf8',
				        ),
				        // Body is required
				        'Body' => array(
				            'Html' => array(
				                // Data is required
				                'Data' => $html_message,
				                'Charset' => 'utf8',
				            ),
				        ),
		    	)
			);
	//echo'<pre>';print_r($result);echo'</pre>';
}

function mailThroughAmazon($from,$to,$subject,$html_message='',$text_msg='',$file_text_path='',$file_html_path='',$http_url='',$replyto='',$cc='',$bcc='',$return_path=''){

	//require_once('ses.php');

	$ses = new SimpleEmailService('', '');
		//$ses->verifyEmailAddress($to);

	$m = new SimpleEmailServiceMessage();
	$m->addTo($to);
	$m->setFrom($from);
	$m->setSubject($subject);
	if($text_msg != '' || $html_message != '') {
		$m->setMessageFromString($text_msg,$html_message);
	}
	else if($file_text_path != '' || $file_html_path != '') {
		$m->setMessageFromFile($file_text_path,$file_html_path);
	}
	else if($http_url != '') {
		$m->setMessageFromURL($http_url);
	}
	else {
		return 'Attempt to send mail with out message';
	}
	if($cc != '')
		$m->addCC($cc);
	if($bcc != '')
		$m->addBCC($bcc);
	if($return_path != '')
		$m->setReturnPath($return_path);
	if($replyto != '')
		$m->addReplyTo($replyto);
	//echo '<pre>';print_r($m);echo '</pre>';
	return $ses->sendEmail($m);
}

function destroyPagingControlsVariables() { //clear paging session variables
    unset($_SESSION['orderby']);
    unset($_SESSION['ordertype']);
    unset($_SESSION['curpage']);
    unset($_SESSION['perpage']);
    unset($_SESSION['paging']);
}
//set paging session variables
function setPagingControlValues($default_field,$per_page) {
	if(isset($_POST['per_page']))
		$_SESSION['perpage'] = $_POST['per_page'];
	elseif(!isset($_SESSION['perpage']))
		$_SESSION['perpage'] = $per_page;

	if(isset($_POST['cur_page']))
		$_SESSION['curpage'] = $_POST['cur_page'];
	elseif(!isset($_SESSION['curpage']))
		$_SESSION['curpage'] = 1;

	if(isset($_POST['order_by']))
		$_SESSION['orderby'] = $_POST['order_by'];
	elseif(!isset($_SESSION['orderby']))
		$_SESSION['orderby'] = $default_field;

	if(isset($_POST['order_type']))
		$_SESSION['ordertype'] = $_POST['order_type'];
	elseif(!isset($_SESSION['ordertype']))
		$_SESSION['ordertype'] = 'desc';
	if(isset($_POST['paging_change']) && $_POST['paging_change']!='')
         $_SESSION['curpage'] = $_POST['paging_change'];
}

// Displays the sort icons for the column headings
// Paramters => 	column : field in the database that is merged in the ORDER BY clause of the query
//					title  : column name to be displayed on the screen.
// Output	 =>		Returns as a Hyperlink with given column and field.
function SortColumn($column, $title)
{
	/*if($_SERVER['REMOTE_ADDR']=='172.21.4.95') {
		echo '===>'.__LINE__.'===>'.$column;
		echo '===>'.__LINE__.'===>'.$title;
		die();
	}*/
	$sort_type = 'ASC';
	$sort_image = 'fa fa-sort ';
	
	if (($_SESSION['orderby'] == $column) && ($_SESSION['ordertype'] == 'ASC')){  //asc
		$sort_type = 'DESC';
		$sort_image = 'fa fa-sort-alpha-asc ';
	}
	elseif (($_SESSION['orderby'] == $column) && ($_SESSION['ordertype'] == 'DESC')){ //desc
		$sort_type = 'ASC';
		$sort_image = 'fa fa-sort-alpha-desc ';
	}
	
	$alt_title = 'Sort by '.ucfirst(strtolower($title))." ".strtolower($sort_type);
	$sort_link = "<a href=\"#\" onclick=\"javascript:setPagingControlValues('".$column."','".$sort_type."',".$_SESSION['curpage'].");\" alt=\"".$alt_title."\" title=\"".$alt_title."\" >";
	//return $sort_link.''.$title.'</a>&nbsp;'.$sort_link.'</a>';//<img src="'.IMAGE_PATH . $sort_image.'" alt="" border="0">
	return $sort_link.''.$title.'</a>&nbsp;'.$sort_link.'&nbsp;<i class="'. $sort_image.'"></i></a>';
}
/* sort column for icon in title tag*/
function SortColumnIcon($column, $title,$titleName)
{
	/*if($_SERVER['REMOTE_ADDR']=='172.21.4.95') {
		echo '===>'.__LINE__.'===>'.$column;
		echo '===>'.__LINE__.'===>'.$title;
		die();
	}*/
	$sort_type = 'ASC';
	$sort_image = 'fa fa-sort fa-lg';
	
	if (($_SESSION['orderby'] == $column) && ($_SESSION['ordertype'] == 'ASC')){  //asc
		$sort_type = 'DESC';
		$sort_image = 'fa fa-sort-alpha-asc fa-lg';
	}
	elseif (($_SESSION['orderby'] == $column) && ($_SESSION['ordertype'] == 'DESC')){ //desc
		$sort_type = 'ASC';
		$sort_image = 'fa fa-sort-alpha-desc fa-lg';
	}
	
	$alt_title = 'Sort by '.ucfirst(strtolower($titleName))." ".strtolower($sort_type);
	$sort_link = "<a href=\"#\" onclick=\"javascript:setPagingControlValues('".$column."','".$sort_type."',".$_SESSION['curpage'].");\" alt=\"".$alt_title."\" title=\"".$alt_title."\" >";
	//return $sort_link.''.$title.'</a>&nbsp;'.$sort_link.'</a>';//<img src="'.IMAGE_PATH . $sort_image.'" alt="" border="0">
	return $sort_link.''.$title.'</a>&nbsp;'.$sort_link.'&nbsp;<i class="'. $sort_image.'"></i></a>';
}
function SortColumnAjax($column, $title,$functionName)
{
	$sort_type = 'ASC';
	$sort_image = 'no_sort.gif';
	if (($_SESSION['orderby'] == $column) && ($_SESSION['ordertype'] == 'ASC')){  //asc
		$sort_type = 'DESC';
		$sort_image = 'asc.gif';
	}
	elseif (($_SESSION['orderby'] == $column) && ($_SESSION['ordertype'] == 'DESC')){ //desc
		$sort_type = 'ASC';
		$sort_image = 'desc.gif';
	}
	$alt_title = 'Sort by '.ucfirst(strtolower($title))." ".strtolower($sort_type);
	$sort_link = "<a href=\"javascript:void(0);\" onclick=\"javascript:setPagingControlValuesAjax('".$column."','".$sort_type."',".$_SESSION['curpage']."); $functionName \" alt=\"".$alt_title."\" title=\"".$alt_title."\" >";
	//return $sort_link.''.$title.'</a>&nbsp;'.$sort_link.'</a>';//<img src="'.IMAGE_PATH . $sort_image.'" alt="" border="0">
	return $sort_link.''.$title.'</a>&nbsp;'.$sort_link.'<img src="'.ADMIN_IMAGE_PATH . $sort_image.'" alt="" border="0"></a>';
}
// Display paging control
//Input : no. of records and URL
function pagingControlLatest($total,$action='')
{
	$per_page 		= $_SESSION['perpage'];
	$page 			= $_SESSION['curpage'];
	$pagination 	= '<div class="menu-list">';
	if ($action == '')
		$action = $_SERVER['SCRIPT_NAME'];
	?>
	<form name="paging" id="paging" method="post" action="<?php echo($action);?>"  >
		<input type="Hidden" value="<?php echo($_SESSION['curpage']);?>" name="cur_page" id="cur_page">
		<input type="Hidden" value="<?php echo($_SESSION['orderby']);?>" name="order_by" id="order_by">
		<input type="Hidden" value="<?php echo($_SESSION['ordertype']);?>" name="order_type" id="order_type">
		<?php
		if ($total > $per_page)
		{
        $adjacents = "2";

    	$page = ($page == 0 ? 1 : $page);
    	$start = ($page - 1) * $per_page;

		$firstPage = 1;

		$prev = ($page == 1)?1:$page - 1;

    	$prev = $page - 1;
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;

    	if($lastpage > 1)
    	{
    		$pagination .= "<ul class='pagination'>";
                   // $pagination .= "<li class='details'>Page $page of $lastpage</li>";
			if ($page == 1)
			{
				$pagination.= "<li><i class='fa fa-angle-double-left fa-lg'></i></li>";
				$pagination.= "<li><i class='fa fa-angle-left fa-lg'></i></li>";
			}
			else
			{
				$pagination.= "<li><a class='' href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$firstPage);\" ><i class='fa fa-angle-double-left fa-lg'></i></a></li>";
				$pagination.= "<li><a class='' href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$prev);\" ><i class='fa fa-angle-left fa-lg'></i></a></li>";
			}

    		if ($lastpage < 7 + ($adjacents * 2))
    		{
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><i>$counter</i></li>";
    				else
    					$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter);\">$counter</a></li>";
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><i>$counter</i></li>";
    					else
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter);\">$counter</a></li>";
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lpm1);\">$lpm1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lastpage);\">$lastpage</a></li>";
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',1);\">1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',2);\">2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><i>$counter</i></li>";
    					else
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter);\">$counter</a></li>";
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lpm1);\" >$lpm1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lastpage);\" >$lastpage</a></li>";
    			}
    			else
    			{
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',1);\" >1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',2);\" >2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><i>$counter</i></li>";
    					else
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter);\" >$counter</a></li>";
    				}
    			}
    		}

    		if ($page < $counter - 1){
    			$pagination.= "<li><a class='' href='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$next);\" ><i class='fa fa-angle-right fa-lg'></i></a></li>";
                $pagination.= "<li><a class='' shref='javascript:void(0);' onclick=\"javascript:setPagingControlValues('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lastpage);\" ><i class='fa fa-angle-double-right fa-lg'></i></a></li>";
    		}else{
    			$pagination.= "<li><i class='fa fa-angle-right fa-lg'></i></li>";
                $pagination.= "<li><i class='fa fa-angle-double-right fa-lg'></i></li>";
            }
    		$pagination.= "</ul>";
    	}
	}
		 echo $pagination; ?>
		 
		</div>
		<div  class="per_page">
		<?php $per_page_array =  eval(ADMIN_PER_PAGE_ARRAY);
		if($total > $per_page_array[0]){ 
			if($action!='Receipts_Download'){?>
			
			<span class="fright">
				<select name="per_page" id="per_page" onchange="setPerPage(this.value);">
				<?php foreach($per_page_array as $value){ ?>
					<option value="<?php echo($value);?>" <?php if($per_page == $value) { echo "selected='selected'"; } ?>><?php echo($value);?></option>
				<?php } ?>
				</select>
			</span>
			<span class="recor_txt">Per page &nbsp;</span>
		<?php }
			else{
				$_SESSION['perpage']=10;
			}
		}
			
			?>
		</div>
	</form>
<?php }

// Display paging control
//Input : no. of records and URL
function pagingControlLatestAjax($total,$functionName='')
{
	$per_page 		= $_SESSION['perpage'];
	$page 			= $_SESSION['curpage'];
	$pagination 	= '<br><table cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
	<tr>
	<td align="center" width="90%" ><table cellspacing="0" cellpadding="0" border="0" align="center"><tr><td> ';
	?>
	<form name="pagingAjax" id="pagingAjax" method="post"   >
		<input type="Hidden" value="<?php echo($_SESSION['curpage']);?>" name="cur_page" id="cur_page">
		<input type="Hidden" value="<?php echo($_SESSION['orderby']);?>" name="order_by" id="order_by">
		<input type="Hidden" value="<?php echo($_SESSION['ordertype']);?>" name="order_type" id="order_type">
		<?php
		if ($total > $per_page)
		{
        $adjacents = "2";
    	$page = ($page == 0 ? 1 : $page);
    	$start = ($page - 1) * $per_page;

		$firstPage = 1;

		$prev = ($page == 1)?1:$page - 1;

    	$prev = $page - 1;
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;

    	if($lastpage > 1)
    	{
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage</li>";
			if ($page == 1)
			{
				$pagination.= "<li><a class='current'><i class='fa fa-angle-double-left fa-lg'></i></a></li>";
				$pagination.= "<li><a class='current'><i class='fa fa-angle-double-right fa-lg'></i></a></li>";
			}
			else
			{
				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$firstPage); $functionName \" ><<</a></li>";
				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$prev); $functionName \" ><</a></li>";
			}
    		if ($lastpage < 5 + ($adjacents * 2))
    		{
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter); $functionName \">$counter</a></li>";
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))
    			{
    				for ($counter = 1; $counter < 2 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter); $functionName \">$counter</a></li>";
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lpm1); $functionName \">$lpm1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lastpage); $functionName \">$lastpage</a></li>";
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',1); $functionName \">1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',2); $functionName \">2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter); $functionName \">$counter</a></li>";
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lpm1); $functionName \" >$lpm1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lastpage); $functionName \" >$lastpage</a></li>";
    			}
    			else
    			{
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',1); $functionName \" >1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',2); $functionName \" >2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$counter); $functionName \" >$counter</a></li>";
    				}
    			}
    		}
    		if ($page < $counter - 1){
    			$pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$next); $functionName \" >></a></a></li>";
                $pagination.= "<li><a href='javascript:void(0);' onclick=\"javascript:setPagingControlValuesAjax('".$_SESSION['orderby']."','".$_SESSION['ordertype']."',$lastpage); $functionName \" >>></a></a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>></a></a></li>";
                $pagination.= "<li><a class='current'>>></a></a></li>";
            }
    		$pagination.= "</ul>";
    	}
	}
		 echo $pagination; ?>
		 	</td></tr>
		 </table>
		</td>
		<?php  $per_page_array =  eval(ADMIN_PER_PAGE_ARRAY);
		if($total > $per_page_array[0]){ ?>
		<td  class="record">
		Per page 
		</td><td class="record" width="20%" style="padding-right:10px;">

			<select name="per_page" id="per_page" onchange="setPerPageAjax(this.value);<?php  echo $functionName; ?>" style="width:40px;">
			<?php foreach($per_page_array as $value){?>
				<option value="<?php echo($value);?>" <? if($per_page == $value) echo " selected='selected'"?>><?php echo($value);?></option>
			<?php }?>
			</select>
		</td>
		<?php }?>
		</tr>
		</table>
	</form>
<?php } 

function ipAddress(){
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
 		$ip_address=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
 		$ip_address=$_SERVER['REMOTE_ADDR'];
	}
	return $ip_address;
}
function sendingPNForAllUser($user_id,$message){
	if($_SERVER['REMOTE_ADDR']=='172.21.4.95') {
		echo '===>'.__LINE__.'===>'.$user_id.'<br />';
		echo '===>'.__LINE__.'===>'.$message.'<br />';
	}
}
function getTimeZone(){
	$key="9dcde915a1a065fbaf14165f00fcc0461b8d0a6b43889614e8acdb8343e2cf15";
	if ($_SERVER['HTTP_HOST'] == '172.21.4.104'){
		$ip = '27.124.58.84';
	}
	else{
		//$ip = $_SERVER['REMOTE_ADDR']; //
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		else
			$ip = $_SERVER['REMOTE_ADDR']; 
	}
	$url = "http://api.ipinfodb.com/v3/ip-city/?key=$key&ip=$ip&format=xml";

	$xml = simplexml_load_file($url);
	foreach($xml->children() as $child)
  	{
 	 //echo $child->getName() . ": " . $child . "<br />";
	 if($child->getName() == 'timeZone')
	 	return $child ;
 	}
}
// to split date and time 
function dateSplitter($date_variable){
	$date1 = substr($date_variable,0,strrpos($date_variable,'/')+5);
	$date2 = substr($date_variable,strpos($date_variable,':')-2);
	echo $date1."<br>".$date2;	
}

function html_entities ( $string )
{
     return str_replace ( array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' , '&mdash;', '&nbsp;'), array ( '&', '"', "'", '<', '>' ,'--', ' '), $string );
	 // return str_replace ( array ('&', '"', "'", '<', '>' ,'--'), array ('&amp;', '&quot;', '&apos;' , '&lt;' , '&gt;' , '&mdash;'), $string );
}
function logEntry($event,$txt_file){
	$log_dir	=	'C:/wamp/www/carfit/logs';
	if(!is_dir($log_dir)) {
		mkdir($log_dir,0777);
	}
	$filename = "C:/wamp/www/carfit/logs/".$txt_file;
	$f		  = fopen($filename, 'a' );			// open the log file for writing and put the pointer at the end of the file
	fwrite ($f, $event . chr(13) . chr(10) );
	fclose($f);
}
function getImageMimeType($imagedata)
{
  $imagemimetypes = array( 
    "jpeg" => "FFD8", 
    "png" => "89504E470D0A1A0A", 
    "gif" => "474946",
    "bmp" => "424D"
  );

  foreach ($imagemimetypes as $mime => $hexbytes)
  {
    $bytes = getBytesFromHexString($hexbytes);
    if (substr($imagedata, 0, strlen($bytes)) == $bytes)
      return $mime;
  }

  return NULL;
}
function getBytesFromHexString($hexdata)
{
  for($count = 0; $count < strlen($hexdata); $count+=2)
    $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

  return implode($bytes);
}
/* Create endpointARN for push notification */
function createEndpointARNAWS($PlatformApplicationArn,$Token,$CustomUserData){
	error_reporting(E_ALL);
	$endpoint = require("sns-create.php");
	return $endpoint;die();
}
// function sendNotificationAWS($message,$EndpointArn,$platform,$badge,$type,$processId,$userId,$sound,$merchantId,$merchantName,$notes,$orderAmount){
function sendNotificationAWS($message,$EndpointArn,$platform,$badge,$type,$processId,$userId){
	
	error_reporting(E_ALL);
	$endpoint = require("sns-send.php");
	return $endpoint;die();
}

function curlRequest($url, $method, $data = null, $access_token = '')
{
//echo '<pre>'; print_r($data); exit;
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, $url);
	if ($access_token != '') {
		# headers and data (this is API dependent, some uses XML)
		if ($method == 'PUT') {
		$headers = array(
						'Accept: application/json',
						'Content-Type: application/json',
						'Authorization: '.$access_token,
						);
		} else {
			$headers = array(
						'Authorization: '.$access_token
						);
		}
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	} 
	
	
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)"); 
	curl_setopt($handle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
	
	switch($method) {
		case 'GET':
		break;
		case 'POST':
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		break;
		case 'PUT':
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		break;
		case 'DELETE':
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		break;
	}
	$response = curl_exec($handle);
	if($_SERVER['REMOTE_ADDR'] == '172.21.4.215'){
		//echo'<div style="width:200px"><pre>';print_r($response);echo'</pre></div>';
	}
	
	$response = json_decode($response, true);
	return $response; 
}

function getLatLngFromAddress($address) {
	$address = str_replace(" ", "+", $address);
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");//&region=$region	
	$json = json_decode($json);
	if(!empty( $json->{'results'}))  {
		$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		if(isset($lat) && $lat !='' && isset($long) && $long !='') {
			return $lat.'###'.$long;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

function checkImage($files,$type){
	$flag = 0;
	if (isset($files['tmp_name']) && $files['tmp_name'] != '') {
		$ImageArray = array('image/png','image/jpg','image/jpeg','image/gif');
		$dimension = getImageSize($files['tmp_name']);
		if(!in_array($files['type'],$ImageArray)){
			$flag = 1;
		}
		else if(!empty($files['error'])){
			$flag = 2;
		}
		else if($files['size'] > 10485760) {//2097152 - changed on 19/11/2014
			// 5242880 - 5MB
			$flag = 3;
		}
		//else if($type ==1 && ($dimension[0] < '100' || $dimension[1] < '100')){
		//	$flag = 4;
		//}
		else{
			$flag = 5;//success
		}
   }
   return $flag;
}


function uploadImageToS3($image_path,$type,$image_name){
	error_reporting(0);
	$image_upload_path = $ext = '';
	$contentType = "image/png";
	if($type == 1){ //user thumb
		$image_upload_path = 'users/'.$image_name;
	}else if($type == 2){ //user thumb
		$image_upload_path = 'users/thumbnail/'.$image_name;
	}
	
	require_once('sdk.class.php');// Include the SDK
	
	$s3 = new AmazonS3();//// Instantiate the AmazonS3 class
	
	$bucket = BUCKET_NAME;
	// Create our new bucket in the US-West region.
	$exists = $s3->if_bucket_exists($bucket);
	if(!$exists){
		$create_bucket_response = $s3->create_bucket($bucket, AmazonS3::REGION_US_W2);
	}
	$filename = $image_path;
	$s3->batch()->create_object($bucket, $image_upload_path, array(
				'fileUpload' => $filename,
				'contentType' => $contentType,
				'acl' => AmazonS3::ACL_PUBLIC,
				 'headers' => array( // Custom $requestHeaders //meta headers
				           	 	"Cache-Control" => "max-age=315360000",
				            	"Expires" => gmdate("D, d M Y H:i:s T", strtotime("+5 years"))
							  )
				)
		);
		$file_upload_response = $s3->batch()->send();
		return $file_upload_response;
		//echo'<br>-------file---------<pre>';print_r($file_upload_response);echo'</pre>';
}

function image_exists($type,$image_name){
	return true;
	$info ='';
	if($type == 1){ //user 
		$filename = 'users/'.$image_name;
	}else if($type == 2){ //user 
		$filename = 'users/thumbnail/'.$image_name;
	}
	$bucket 	 = BUCKET_NAME;
	require_once('sdk.class.php');// Include the SDK
	$s3 	= 	new AmazonS3();// Instantiate the AmazonS3 class
	$response		= $s3->if_object_exists( $bucket, $filename );
	if($response ==  true)
	{
		$info	=	$s3->get_object_url($bucket, $filename);
	}
	if($info){
		return true;
	}
	else{
		return false;
	}
}
function deleteImages($type,$image_name){
	if($type == 1){ //user 
		$filename = 'users/'.$image_name;
	}else if($type == 2){ //user 
		$filename = 'users/thumbnail/'.$image_name;
	}
	
	$bucket 	 = BUCKET_NAME;
	require_once('sdk.class.php');// Include the SDK
	$s3 = new AmazonS3();// Instantiate the AmazonS3 class
	$info = $s3->delete_object($bucket, $filename);
	if ($info){
	 return true;
	}
	else{
		return false;
	}
}


?>
<?php 
ob_start();
require_once('../includes/ajaxCommonIncludes.php');
if(isset($_POST['action']) && $_POST['action'] == 'GET_YEAR'){
	if(isset($_POST['year']) && $_POST['year'] != ''){
		require_once('../controllers/magazineController.php');
		$magazineObj   	=   new MagazineController();
		$fields    		= "  m.* ";
		$condition		= " and year(m.publishDate) = '".$_POST['year']."'";
		$magazineResult = $magazineObj->getMagazinesList($fields,$condition,$_POST['page']);
		if($magazineResult > 0){
		?>
		<!--listing-->
			<div class="row mb-4 wow fadeIn">
				<!-- Start : listing -->
				<?php foreach($magazineResult as $key=>$value){ ?>
				<div class="col-lg-4 col-md-12 mb-4">
					<div class="box">
						<div class="box-img" style="background-image: url('<?php echo MAGAZINE_IMAGE_PATH.$value->file; ?>')"> 
							<div class="box-hover">
								<i class="fa fa-download"></i>
								<div class="hover-btn">
									<a href="#" title="Image">Download</a>										
								</div>
							</div>
						</div>
						<div class="box-body">
							<h3><?php echo $value->title; ?></h3>
							<p><?php echo date('d M Y',strtotime($value->publishDate));?> <i>|</i><?php echo $value->description; ?></p>
						</div>
					</div>
				</div>
				<?php } ?>
				<!-- End : listing -->					
			</div>
			
		<!--listing-->
		<?php } else if ($_POST['page'] == 0) { ?>
		<div class="no-data">
			No data found for this year.
		</div>
		<?php } 
	}else{
		echo '';
	}
}


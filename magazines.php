<?php 
require_once('includes/commonIncludes.php');
require_once('controllers/magazineController.php');
commonHead();
top_header();
$magazineObj   	=   new MagazineController();
$currYear 		= date('Y');
$prevYear		= $currYear-1;
$nextYear		= ($currYear)+1;
$fields    		= "  m.* ";
$condition		= " and year(m.publishDate) = '".$currYear."'";
$magazineResult = $magazineObj->getMagazinesList($fields,$condition,0);

?>
<!--Main layout-->
	<main>
		<!--Banner-->
		<div class="container-fluid">
			<section class="wow fadeIn" style="background-image: url(https://mdbootstrap.com/img/Photos/Others/gradient1.jpg);">
				<div class="text-center text-white py-5 px-5">
					<h1 class="mb-4">
						<strong>Magazines</strong>
					</h1>
					<p>
						 <strong>Best & free guide of responsive web design</strong>
					</p>
					<p class="mb-4">
							 <strong>The most comprehensive tutorial for the Bootstrap 4. Loved by over 500 000 users. Video and written
							versions available. Create your own, stunning website.</strong>
					</p>
				</div>
			</section>
		</div>
		<!--Banner-->

		<div class="container">
		<!-- year-nav-top-->
			<div class="year-nav-top">           
				<a href="javascript:void(0);" onclick="changeYear(1)" title="">
					<span class=" fa fa-angle-double-left"></span>
					<span class="prev-year-sel"><?php echo $prevYear; ?></span>
				</a>
				<a class="datepicker" href="javascript:void(0);">
					<span class=" fab fa-twitter"></span>
					<span data-toggle="tooltip" data-placement="left" title="Pick a Year" ><span class="glyphicon glyphicon-calendar"></span></span><span class="current-year-sel"><?php echo $currYear; ?></span></a> 					
				</a>
				<a href="javascript:void(0);" onclick="changeYear(2)" title="">
					<span class=" fa fa-angle-double-right"></span>
					<span class="next-year-sel"><?php echo $nextYear; ?></span> 
				</a>
			</div>         
		 <!-- year-nav-top-->

		<!--listing-->
			<input type="hidden" name="loadmore" id="loadmore" value="1" />
			<input type="hidden" name="nextloadmore" id="nextloadmore" value="1" />
			<section class="listing" >
				<div class="row mb-4 wow fadeIn" id="magazineList">
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
								<p><?php echo date('d M Y',strtotime($value->publishDate));?><i>|</i><?php echo $value->description; ?></p>
							</div>
						</div>
					</div>
					<?php } ?>
					<!-- End : listing -->					
				</div>
			</section>
		<!--listing-->

		<!-- year-nav-bottom-->
		<div class="year-nav-bottom">           
				<a href="javascript:void(0);" onclick="changeYear(1)" title="">
					<span class=" fa fa-angle-double-left"></span>
					<span class="prev-year-sel"><?php echo $prevYear; ?></span>
				</a>
			   
				<a href="javascript:void(0);" onclick="changeYear(2)" title="">
					<span class=" fa fa-angle-double-right"></span>
					<span class="next-year-sel"><?php echo $nextYear; ?></span> 
				</a>
			</div>         
		 <!-- year-nav-bottom-->
		</div>

	</main>
	<!--Main layout-->
	
	<!--popup-->
	<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header text-center">
			<h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body mx-3">
			<div class="md-form mb-5">
			  <!-- Default input -->
				<label for="exampleForm2">Default input</label>
				<input type="text" id="exampleForm2" class="form-control">
			</div>
			<div class="md-form mb-5">
			 <!-- Default input -->
				<label for="exampleForm2">Default input</label>
				<input type="text" id="exampleForm2" class="form-control">
			</div>

			<div class="md-form mb-4">
			  <!-- Default input -->
				<label for="exampleForm2">Default input</label>
				<input type="text" id="exampleForm2" class="form-control">
			</div>

		  </div>
		  <div class="modal-footer d-flex justify-content-center">
			<button class="btn btn-deep-orange">Sign up</button>
		  </div>
		</div>
	  </div>	
	</div>

	<div class="text-center">
	  <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalRegisterForm">Launch
		Modal Register Form</a>
	</div>
    <!--popup-->	
	
<?php
commonFooter();
?>
<script>
$(document).ready(function() {
	$('.datepicker').datepicker({
	format: "yyyy",
	viewMode: "years",
	minViewMode: "years",
	startDate: '1990',
	endDate: "thisYear"
	}).on('changeYear', changeDate);
	
	
});
$(window).scroll(function() {
    if($(window).scrollTop() == $(document).height() - $(window).height()) {
           // ajax call get data from server and append to the div
		   var selectedDate = $('.current-year-sel').html();
		   var pageval = $('#loadmore').val();
		   //alert(parseInt(pageval)+1)
		   if($('#nextloadmore').val() == 1){
			   $.ajax({
					type: "POST",
					url: "models/ajaxAction.php",
					data: 'action=GET_YEAR&year='+selectedDate+'&page='+pageval,
					success: function (result){
						//alert("--"+result);
						if(result != ''){
							//alert('This order already assigned for some other service');
							$("#magazineList").append(result);
							
							$('#loadmore').val(parseInt(pageval)+1)
							return false;
						}else{
							$('#nextloadmore').val(0);
						}
					}			
				});
		   }
    }
});


</script>
	//
</script>

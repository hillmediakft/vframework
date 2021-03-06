<div class="page-content">
	<!-- BEGIN PAGE HEADER-->
	<!-- BEGIN PAGE TITLE & BREADCRUMB-->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<a href="admin/home">Kezdőoldal</a> 
				<i class="fa fa-angle-right"></i>
			</li>
			<li><a href="#">Képgalériak</a></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<!-- echo out the system feedback (error and success messages) -->
			<div id="ajax_message"></div>
			<?php $this->renderFeedbackMessages(); ?>	

			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-picture-o"></i>Képgalériák kezelése</div>
					<div class="actions">
						<a href="admin/photo-gallery/create" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új kép feltöltése</a>
						<a href="admin/photo-gallery/category" class="btn default btn-sm"> Kategóriák</a>
					</div>
				</div>
				<div class="portlet-body">

					<!-- BEGIN PAGE CONTENT-->
					<div class="row">
						<div class="col-md-12">

							<!-- BEGIN FILTER -->
							<div id="mixitup_container" class="margin-top-10">
								<ul class="mix-filter">
									<li class="filter" data-filter="all">
										 Összes
									</li>
									<?php foreach ($categories as $category) { ?>
									<li class="filter" data-filter="category_<?php echo $category['id']; ?>">
										 <?php echo $category['category_name']; ?>
									</li>									
									<?php } ?>
								</ul>
								<div class="row mix-grid">
								
								<?php foreach($all_photos as $photo) { ?>						
											
									<div id="photo_<?php echo $photo['id'];?>" class="col-md-3 col-sm-4 mix category_<?php echo $photo['category_id'];?>">
										<div class="mix-inner">
											<div class="photo_slider_label">
												<?php echo ($photo['slider'] == 1) ? '<span class="label label-info">Kiemelt</span>' : '';?>
											</div>
											<img class="img-responsive" src="<?php echo $this->getConfig('photogallery.upload_path') . $this->url_helper->thumbPath($photo['filename']);?>" alt="">
											<div class="mix-details">
												<h4><?php echo $photo['caption'];?></h4>
												<a class="mix-delete" data-id="<?php echo $photo['id'];?>">
													<i class="fa fa-trash"></i>
												</a>
												<a class="mix-edit" href="admin/photo-gallery/edit/<?php echo $photo['id'];?>">
													<i class="fa fa-edit"></i>
												</a>
												<a class="mix-preview fancybox-button" href="<?php echo $photo['filename'];?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
												</a>
											</div>
										</div>
									</div>
												
								<?php } ?>										
												
								</div>
							</div>
							<!-- END FILTER -->

						</div>
					</div> <!-- END PAGE CONTENT-->
						
				</div> <!-- END PHOTO GALLERY PORTLET BODY-->
				
			</div> <!-- END PHOTO GALLERY PORTLET-->
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
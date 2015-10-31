<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						Képgaléria <small>kezelése</small>
					</h3>
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
		
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

						<!-- echo out the system feedback (error and success messages) -->
						<?php $this->renderFeedbackMessages(); ?>	

						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-picture-o"></i>Képgalériák kezelése</div>
																	<div class="actions">
										<a href="admin/photo_gallery/new_photo" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új kép feltöltése</a>
										
										
										

									</div>
							</div>
							<div class="portlet-body">

			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					

					
								<!-- BEGIN FILTER -->
								<div class="margin-top-10">
									<ul class="mix-filter">
										<li class="filter" data-filter="all">
											 Összes
										</li>
										<li class="filter" data-filter="category_1">
											 Vásárláskor
										</li>
										<li class="filter" data-filter="category_2">
											 Felújátás közben
										</li>
										<li class="filter" data-filter="category_3">
											 Felújítás után
										</li>
									</ul>
									<div class="row mix-grid">
									
									
					<?php foreach($all_photos as $value) { ?>						
									
									
										<div class="col-md-3 col-sm-4 mix category_<?php echo $value['photo_category'];?>">
											<div class="mix-inner">
												<div class="photo_slider_label"><?php echo ($value['photo_slider'] == 1) ? '<span class="label label-info">Kiemelt</span>' : '';?></div>
												<img class="img-responsive" src="<?php echo Util::thumb_path( $value['photo_filename']);?>" alt="">
												<div class="mix-details">
													<h4><?php echo $value['photo_caption'];?></h4>
													<a id="delete_photo<?php echo $value['photo_id'];?>"class="mix-delete" href="admin/photo-gallery/delete/<?php echo $value['photo_id'];?>">
													<i class="fa fa-trash"></i>
													</a>
													<a class="mix-edit" href="admin/photo-gallery/edit/<?php echo $value['photo_id'];?>">
													<i class="fa fa-edit"></i>
													</a>

													<a class="mix-preview fancybox-button" href="<?php echo $value['photo_filename'];?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										
										</div>
										
										
										
					<?php } ?>										
										
<!--										
										<div class="col-md-3 col-sm-4 mix category_2">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img2.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img2.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_3">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img3.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img3.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_1 category_2">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img4.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img4.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_2 category_1">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img5.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img5.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_1 category_2">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img6.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img6.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_2 category_3">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img1.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img1.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_1 category_2">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img2.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img2.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_3">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img4.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img4.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3 col-sm-4 mix category_1">
											<div class="mix-inner">
												<img class="img-responsive" src="<?php echo UPLOADS . 'img5.jpg';?>" alt="">
												<div class="mix-details">
													<h4>Cascusamus et iusto accusamus</h4>
													<a class="mix-link">
													<i class="fa fa-link"></i>
													</a>
													<a class="mix-preview fancybox-button" href="<?php echo UPLOADS . 'img5.jpg';?>" title="Project Name" data-rel="fancybox-button">
													<i class="fa fa-search"></i>
													</a>
												</div>
											</div>
										</div>
										
										
		-->								
										
										
									</div>
								</div>
								<!-- END FILTER -->

				</div>
			</div>
			<!-- END PAGE CONTENT-->




							
								
							</div> <!-- END PHOTO GALLERY PORTLET BODY-->
						</div> <!-- END PHOTO GALLERY PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->    
	</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
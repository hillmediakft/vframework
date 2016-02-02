<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<!-- 
					<h3 class="page-title">
						Blog <small>hozzáadása</small>
					</h3>
					-->

					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li>
								<a href="admin/blog">Blogok kezelése</a>
								<i class="fa fa-angle-right"></i>
							</li>
							<li><a href="admin/blog/insert">Blog hozzáadása</a></li>
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
								<div class="caption"><i class="fa fa-film"></i>Blog bejegyzés hozzáadása</div>
							</div>
			
							<div class="portlet-body">


							<div class="space10"></div>							
							<div class="row">	
								<div class="col-md-12">						
									<form action="" method="POST" id="new_blog" enctype="multipart/form-data">	
								
									<!-- bootstrap file upload -->
										<div class="form-group">
											<label class="control-label">Kép</label>
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?php echo ADMIN_IMAGE . 'no_user_image.jpg';?>" alt=""/></div>
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
												<div>
													<span class="btn default btn-file"><span class="fileupload-new">Kiválasztás</span><span class="fileupload-exists">Módosít</span><input id="uploadprofile" class="img" type="file" name="upload_blog_picture"></span>
													<a href="#" class="btn btn-warning fileupload-exists" data-dismiss="fileupload">Töröl</a>
												</div>
											</div>
							
																		
											<div class="space10"></div>
											<div class="clearfix"></div>
												<div class="controls">
													<span class="label label-danger">INFO</span>
													<span>Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!</span>
											</div>
											<div class="space10"></div>
											<div class="space10"></div>
										</div>
									<!-- bootstrap file upload END -->
									
										
										<div class="form-group">
											<label for="blog_title" class="control-label">Cím</label>
											<input type="text" name="blog_title" id="blog_title" placeholder="" class="form-control input-xlarge" />
										</div>
										<div class="form-group">
											<label for="blog_text" class="control-label">Szöveg</label>
											<textarea name="blog_body" id="blog_body" placeholder="" class="form-control input-xlarge"></textarea>
										</div>
										
										<div class="form-group">
											<label for="blog_category">Kategória</label>
											<select name="blog_category" class="form-control input-xlarge">
										<?php foreach($category_list as $value) { ?>
												<option value="<?php echo $value['category_id']?>"><?php echo $value['category_name']?></option>
										<?php } ?>
											</select>
										</div>
										
										<div class="form-group">
											<label for="blog_add_date" class="control-label">Hozzáadás dátuma</label>
											<input type="text" name="blog_add_date" id="blog_add_date" placeholder="" class="form-control input-xlarge" />
										</div>
	
										<div class="space10"></div>
										<button class="btn green submit" type="submit" value="submit" name="submit_add_blog">Blog hozzáadása <i class="fa fa-check"></i></button>
									</form>
								</div>
							</div>	


<div id="message"></div> 
									

							</div> <!-- END USER GROUPS PORTLET BODY-->
						</div> <!-- END USER GROUPS PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->    
	</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
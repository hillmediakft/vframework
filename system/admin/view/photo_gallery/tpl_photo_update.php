<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						Fotó <small>szerkesztése</small>
					</h3>
					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li><a href="#">Fotó szerkesztése</a></li>
						</ul>
					</div>
					<!-- END PAGE TITLE & BREADCRUMB-->
				<!-- END PAGE HEADER-->
		
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

						<div class="row">
							<div class="col-lg-12 margin-bottom-20">
								<a class ='btn btn-default' href='admin/photo-gallery'><i class='fa fa-arrow-left'></i>  Vissza a galériáhoza</a>
							</div>
						</div>

						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-film"></i>Fotó szerkesztése</div>
								<!--
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="#portlet-config" data-toggle="modal" class="config"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
								-->
							</div>
							<div class="portlet-body">
							<!-- croppic -->
							<div class="row">	
								<div class="col-md-12">						
									<div class="space10"></div>
									<div class="row">
										<form action="" method="POST" enctype="multipart/form-data" id="edit_photo">	
											
											
											<div class="col-md-6">
												<label class="control-label">Kép feltöltése</label>					


													<div class="fileupload fileupload-new" data-provides="fileupload">
														<div class="fileupload-new thumbnail" style="width: 400px; height: 300px;"><img src="<?php echo ADMIN_IMAGE . 'placeholder-400x300.jpg';?>" /></div>
														<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 400px; max-height: 300px; line-height: 20px;"></div>
														<div>
															<span class="btn btn-file green"><span class="fileupload-new">Kiválasztás</span><span class="fileupload-exists">Módosít</span><input id="uploadprofile" class="img" type="file" name="upload_gallery_photo"></span>
															<a href="#" class="btn btn-warning" data-dismiss="fileupload">Töröl</a>
														</div>
													</div>
													<div class="space10"></div>
													<div class="clearfix"></div>
													<div class="controls">
														<span class="label label-danger">INFO</span>
														<span>Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!</span>
													</div>
												</div>				


											<div class="col-md-6">						
							
												<div class="form-group">
													<label for="photo_caption" class="control-label">Fotó felirat</label>
													<input type="text" name="photo_caption" id="photo_caption" class="form-control input-xlarge" value="<?php echo $photo[0]['photo_caption'];?>"/>
												</div>
												<div class="form-group">
													<label for="photo_category" class="control-label">Fotó kategória</label>
													<select class="form-control input-xlarge" name="photo_category" aria-controls="category">
														<option value="">Válasszon kategóriát</option>
														<option value="1" <?php echo ($photo[0]['photo_category'] == 1) ? 'selected' : '';?>>Vásárlás után</option>
														<option value="2" <?php echo ($photo[0]['photo_category'] == 2) ? 'selected' : '';?>>Felújítás közben</option>
														<option value="3" <?php echo ($photo[0]['photo_category'] == 3) ? 'selected' : '';?>>Felújítás után</option>
													</select>
												</div>
												<div class="form-group">
													<label>Megjelenés galéria sliderben</label>
													<div class="checkbox">
														<label>
															
																<span><input type="checkbox" value="1" name="photo_slider" <?php echo ($photo[0]['photo_slider'] == 1) ? 'checked' : '';?>></span>
														
															Megjelenik
														</label>
													</div>
												</div>
												<input type="hidden" value="<?php echo $photo[0]['photo_filename'];?>" name="old_photo">												
																
												<div class="space10"></div>
												<button class="btn green submit" type="submit" value="Mentés" name="submit_update_photo">Mentés <i class="fa fa-check"></i></button>
											</div>
														
										</form>
									</div>
									
									
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
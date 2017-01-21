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
			<li>
				<a href="admin/photo-gallery">Képgaléria</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><span>Fotó feltöltése</span></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<!-- ÜZENETEK -->
			<div id="ajax_message"></div>
			<?php $this->renderFeedbackMessages(); ?>	

			<form action="" method="POST" enctype="multipart/form-data" id="photo_form">	
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

					<div class="portlet-title">
						<div class="caption"><i class="fa fa-film"></i>Fotó feltöltése</div>
						<div class="actions">
                            <button class="btn green btn-sm" type="submit" name="submit_insert_photo"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/photo-gallery"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
					</div>

					<div class="margin-bottom-20"></div>

					<div class="portlet-body">
						<div class="row">	
							<div class="col-md-12">						
								<div class="row">
									
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
										<div class="clearfix"></div>
										
										<div class="note note-info">
											Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!
										</div>
									
									</div>				

									<div class="col-md-6">						
										
										<div class="form-group">
											<label for="photo_caption" class="control-label">Fotó felirat</label>
											<input type="text" name="photo_caption" id="photo_caption" placeholder="A fotóhoz tartozó szöveges információ" class="form-control input-xlarge" />
										</div>
										
										<div class="form-group">
											<label for="photo_category" class="control-label">Fotó kategória</label>
											<select class="form-control input-xlarge" name="photo_category" aria-controls="category">
												<option value="0">Válasszon kategóriát</option>
												<?php foreach ($categorys as $value) {
													echo '<option value="' . $value['category_id'] . '">' . $value['category_name'] . '</option>' . "\r\n";
												} ?>	
											</select>
										</div>
										
										<div class="form-group">
											<label>Megjelenés galéria sliderben</label>
											<div class="checkbox">
												<label>
													<span><input type="checkbox" value="1" name="photo_slider"></span>
													Megjelenik
												</label>
											</div>
										</div>			
														
									</div>
								</div>
							</div>
						</div>	
					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
			</form>

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
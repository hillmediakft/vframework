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
			<li><a href="#">Fotó szerkesztése</a></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<form action="admin/photo-gallery/update/<?php echo $photo['id']; ?>" method="POST" enctype="multipart/form-data" id="photo_form">	

				<div class="portlet">
					
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-film"></i>Fotó szerkesztése</div>
						<div class="actions">
							<button class="btn green btn-sm" type="submit" name="submit_update_photo"><i class="fa fa-check"></i> Mentés</button>
							<a class="btn default btn-sm" href="admin/photo-gallery"><i class="fa fa-close"></i> Mégsem</a>
						</div>
					</div>
					
					<div class="margin-bottom-20"></div>

					<div class="portlet-body">
					
						<div class="row">	
							<div class="col-md-12">						
								<div class="row">
										
									<div class="col-md-6">
	                                <!-- bootstrap file upload -->
	                                <?php 
	                                	$placeholder = $this->getConfig('photogallery.upload_path') . $photo['filename'];
	                                    echo $this->html_admin_helper->photoUpload(array(
	                                        //'label' => 'Kép',
	                                        'width' => 400,
	                                        'height' => 300,
	                                        'placeholder' => $placeholder,
	                                        'input_name' => 'upload_gallery_photo',
	                                        // 'info_content' => 'Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a megjelenő módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!'
	                                        ));
	                                ?>
	                                <!-- bootstrap file upload END -->
									</div>				

									<div class="col-md-6">						
					

			                            <div class="portlet">
			                                <!--<div class="portlet-title"></div>-->
			                                <div class="portlet-body">
			                                    <ul class="nav nav-tabs">
			                                    <?php foreach ($langs as $key => $lang) { ?>
			                                        <li class="<?php echo ($key == 0) ? 'active' : ''; ?>">
			                                            <a href="#tab_1_<?php echo $key+1; ?>" data-toggle="tab"> <?php echo $lang; ?> </a>
			                                        </li>
			                                    <?php } ?>
			                                    </ul>
			                                    <div class="tab-content">
			                                        <?php foreach ($langs as $key => $lang) { ?>
			                                        <div class="tab-pane fade <?php echo ($key == 0) ? 'active in' : ''; ?>" id="tab_1_<?php echo $key+1; ?>">
														<div class="form-group">
															<label for="photo_caption_<?php echo $lang; ?>" class="control-label">Fotó felirat</label>
															<input type="text" name="photo_caption_<?php echo $lang; ?>" class="form-control input-xlarge" value="<?php echo (isset($photo['caption_' . $lang])) ? $photo['caption_' . $lang] : '';?>"/>
														</div>
			                                        </div>
			                                        <?php } ?>
			                                    </div>
			                                </div>
			                            </div>


										<div class="form-group">
											<label for="photo_category" class="control-label">Fotó kategória</label>
											<select class="form-control input-xlarge" name="photo_category" aria-controls="category">
												<option value="">Válasszon kategóriát</option>
												<?php foreach ($categories as $category) { ?>
													<option value="<?php echo $category['id']; ?>" <?php echo ($photo['category_id'] == $category['id']) ? 'selected' : '';?>><?php echo $category['category_name']; ?></option>
												<?php } ?>	
											</select>
										</div>											
										<div class="form-group">
											<label>Megjelenés galéria sliderben</label>
											<div class="checkbox">
												<label>
													<span><input type="checkbox" value="1" name="photo_slider" <?php echo ($photo['slider'] == 1) ? 'checked' : '';?>></span>
													Megjelenik
												</label>
											</div>
										</div>
										<input type="hidden" value="<?php echo $photo['filename'];?>" name="old_photo">												
									
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
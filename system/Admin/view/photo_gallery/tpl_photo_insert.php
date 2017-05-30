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

			<form action="admin/photo-gallery/store" method="POST" enctype="multipart/form-data" id="photo_form">	
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
										

	                                <!-- bootstrap file upload -->
	                                <?php 
	                                    echo $this->html_admin_helper->photoUpload(array(
	                                        //'label' => 'Kép',
	                                        'width' => 400,
	                                        'height' => 300,
	                                        'placeholder' => ADMIN_IMAGE . 'placeholder-400x300.jpg',
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
															<label for="photo_caption_<?php echo $lang; ?>" class="control-label">Fotó felirat / <?php echo $lang; ?></label>
															<input type="text" name="photo_caption_<?php echo $lang; ?>" id="photo_caption_<?php echo $lang; ?>" placeholder="A fotóhoz tartozó szöveges információ" class="form-control input-xlarge" />
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
												<?php foreach ($categorys as $value) {
													echo '<option value="' . $value['id'] . '">' . $value['category_name'] . '</option>' . "\r\n";
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
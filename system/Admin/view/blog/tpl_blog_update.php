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
				<a href="admin/blog">Blogok kezelése</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><span>Blogbejegyzés módosítása</span></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<!-- echo out the system feedback (error and success messages) -->
			<?php $this->renderFeedbackMessages(); ?>			

			<form action="admin/blog/update/<?php echo $blog['id']; ?>" method="POST" id="update_blog_form" enctype="multipart/form-data">	
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i> 
                            Blog bejegyzés módosítása
                        </div>
                        <div class="actions">
                            <button class="btn green btn-sm" type="submit" name="submit_update_blog"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/blog"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

					<div class="portlet-body">

						<div class="row">	
							<div class="col-md-12">						
							
                                <!-- bootstrap file upload -->
                                <?php 
                                	$placeholder = $this->getConfig('blogphoto.upload_path') . $blog['picture'];
                                    echo $this->html_admin_helper->photoUpload(array(
                                        //'label' => 'Kép',
                                        'width' => 200,
                                        'height' => 150,
                                        'placeholder' => $placeholder,
                                        'input_name' => 'upload_blog_picture',
                                        // 'info_content' => 'Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a megjelenő módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!'
                                        ));
                                ?>
                                <!-- bootstrap file upload END -->

<div class="margin-bottom-30"></div>

								<!-- STÁTUSZ -->
                                <div class="form-group">
                                    <label for="status">Státusz</label>
                                    <select name="status" class="form-control input-xlarge">
                                        <option value="0" <?php echo ($blog['status'] == 0) ? 'selected' : ''; ?>>Inaktív</option>
                                        <option value="1" <?php echo ($blog['status'] == 1) ? 'selected' : ''; ?>>Aktív</option>
                                    </select>
                                </div>

								<!-- KATEGÓRIA -->
								<div class="form-group">
									<label for="blog_category">Kategória</label>
									<select name="blog_category" class="form-control input-xlarge">
										<option value="">Válasszon kategóriát</option>
									<?php foreach($category_list as $category) { ?>
										<option value="<?php echo $category['id']?>" <?php echo ($blog['category_id'] == $category['id']) ? "selected" : "";?>><?php echo $category['category_name']?></option>
									<?php } ?>
									</select>
								</div>


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
													<label for="blog_title_<?php echo $lang; ?>" class="control-label">Cím / <?php echo $lang; ?></label>
													<input type="text" name="blog_title_<?php echo $lang; ?>" value="<?php echo (isset($blog['title_' . $lang])) ? $blog['title_' . $lang] : '';?>" class="form-control input-xlarge" />
												</div>
												<div class="form-group">
													<label for="blog_body_<?php echo $lang; ?>" class="control-label">Szöveg / <?php echo $lang; ?></label>
													<textarea name="blog_body_<?php echo $lang; ?>" class="form-control input-xlarge"><?php echo (isset($blog['body_' . $lang])) ? $blog['body_' . $lang] : '';?></textarea>
												</div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

								<!-- régi kép elérési útja-->
                            	<input type="hidden" name="old_img" id="old_img" value="<?php echo $blog['picture']; ?>">
							</div>
							
						</div>	

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->

			</form>

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
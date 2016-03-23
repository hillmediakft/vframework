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

			<form action="" method="POST" id="update_blog_form" enctype="multipart/form-data">	
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
							<div class="form-group">
								<label class="control-label">Kép</label>
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="<?php echo Config::get('blogphoto.upload_path') . $this->content[0]['blog_picture'];?>" alt=""/></div>
									<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
									<div>
										<span class="btn default btn-file"><span class="fileupload-new">Kiválasztás</span><span class="fileupload-exists">Módosít</span><input id="uploadprofile" class="img" type="file" name="upload_blog_picture"></span>
										<a href="#" class="btn btn-warning fileupload-exists" data-dismiss="fileupload">Töröl</a>
									</div>
								</div>
							</div>
							<!-- bootstrap file upload END -->
																
							<div class="clearfix"></div>

							<div class="note note-info">
								Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!
							</div>

							<?php foreach($this->content as $value) { ?>
								<div class="form-group">
									<label for="blog_title" class="control-label">Cím</label>
									<input type="text" name="blog_title" id="blog_title" value="<?php echo $value['blog_title'];?>" class="form-control input-xlarge" />
								</div>
								<div class="form-group">
									<label for="blog_text" class="control-label">Szöveg</label>
									<textarea name="blog_body" id="blog_body" class="form-control input-xlarge"><?php echo $value['blog_body'];?></textarea>
								</div>
								<div class="form-group">
									<label for="blog_category">Kategória</label>
									<select name="blog_category" class="form-control input-xlarge">
								<?php foreach($this->category_list as $v) { ?>
										<option value="<?php echo $v['category_id']?>" <?php echo ($value['category_name'] == $v['category_name']) ? "selected" : "";?>><?php echo $v['category_name']?></option>
								<?php } ?>
									</select>
								</div>
							<?php } ?>
								<!-- régi kép elérési útja-->
                            	<input type="hidden" name="old_img" id="old_img" value="<?php echo $this->content[0]['blog_picture']; ?>">
							</div>
							
						</div>	

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->

			</form>

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
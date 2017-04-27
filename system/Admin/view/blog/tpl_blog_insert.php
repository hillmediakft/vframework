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
			<li><a href="admin/blog/insert">Blog hozzáadása</a></li>
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
		
			<form action="" method="POST" id="new_blog_form" enctype="multipart/form-data">	

				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i> 
                            Blog bejegyzés hozzáadása
                        </div>
                        <div class="actions">
                            <button class="btn green btn-sm" type="submit" id="submit_add_blog" name="submit_add_blog"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/blog"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

					<div class="portlet-body">

						<div class="row">	
							<div class="col-md-12">						
							
								<!-- bootstrap file upload -->
                                <label for="upload_blog_picture" class="control-label">Kép</label>
                                <div class="form-group ">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="<?php echo ADMIN_IMAGE . 'no_user_image.jpg';?>" alt=""/>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new">Kiválasztás</span>
                                                <span class="fileinput-exists">Módosít</span>
                                                <input id="uploadprofile" class="img" type="file" name="upload_blog_picture">
                                            </span>
                                            <a href="javascript:;" class="btn btn-warning fileinput-exists" data-dismiss="fileinput">Töröl</a>
                                        </div>
                                    </div>
                                </div>
								<!-- bootstrap file upload END -->

								<div class="clearfix"></div>

								<div class="note note-info">
									Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!
								</div>

<div class="margin-bottom-30"></div>
	
								<!-- STÁTUSZ -->
                                <div class="form-group">
                                    <label for="status">Státusz</label>
                                    <select name="status" class="form-control input-xlarge">
                                        <option value="0">Inaktív</option>
                                        <option value="1">Aktív</option>
                                    </select>
                                </div>

                                <!-- KATEGÓRIA -->
								<div class="form-group">
									<label for="blog_category">Kategória</label>
									<select name="blog_category" class="form-control input-xlarge">
										<option value="0">Válasszon kategóriát</option>
									<?php foreach($category_list as $category) { ?>
										<option value="<?php echo $category['id']?>"><?php echo $category['category_name']?></option>
									<?php } ?>
									</select>
								</div>

								<div class="form-group">
									<label for="blog_title" class="control-label">Cím</label>
									<input type="text" name="blog_title" id="blog_title" placeholder="" class="form-control input-xlarge" />
								</div>
								<div class="form-group">
									<label for="blog_text" class="control-label">Szöveg</label>
									<textarea name="blog_body" id="blog_body" placeholder="" class="form-control input-xlarge"></textarea>
								</div>

							</div>
						</div>	

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
		
			</form>
		
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
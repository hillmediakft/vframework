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
				<a href="admin/translations">Fordítások listája</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><span>Fordítás elem hozzáadása</span></li>
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
		
			<form action="" method="POST" id="insert_translation_form" enctype="multipart/form-data">	

				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i> 
                            Fordítás elem hozzáadása
                        </div>
                        <div class="actions">
                            <button class="btn green btn-sm" type="submit" name="submit_insert_translation"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/translations"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

					<div class="portlet-body">

						<div class="row">	
							<div class="col-md-12">						
							

<div class="margin-bottom-10"></div>
	
								<!-- KÓD -->
								<div class="form-group">
									<label for="code" class="control-label">Kód *</label>
									<input type="text" name="code" class="form-control input-xlarge" required />
								</div>
								<!-- KATEGÓRIA -->
								<div class="form-group">
									<label for="category" class="control-label">Kategória *</label>
									<input type="text" name="category" class="form-control input-xlarge" required />
								</div>
								<!-- EDITOR -->
                                <div class="form-group">
                                    <label for="editor" class="control-label">Editor</label>
                                    <input type="checkbox" value="1" name="editor">			
                                </div>

<div class="margin-bottom-30"></div>

                                <!-- TEXT -->
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
													<label for="text_<?php echo $lang; ?>" class="control-label">Szöveg / <?php echo $lang; ?></label>
													<textarea rows="10" name="text_<?php echo $lang; ?>" class="form-control input-xlarge"></textarea>
												</div>
                                            </div>
                                            <?php } ?>
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
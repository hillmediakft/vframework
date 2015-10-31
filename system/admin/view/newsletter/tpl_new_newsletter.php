<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						Új hírlevél <small>létrehozása</small>
					</h3>
					

					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li><a href="#">Új hírlevél</a></li>
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
								<div class="caption"><i class="fa fa-film"></i>Új hírlevél</div>
							</div>
			
							<div class="portlet-body">


							<div class="space10"></div>							
							<div class="row">	
								<div class="col-md-12">						
									<form action="" method="POST" id="add_message">	
									
										<div class="form-group">
											<label for="newsletter_name" class="control-label">Név</label>
											<input type="text" name="newsletter_name" id="newsletter_name" placeholder="" class="form-control input-xlarge" />
										</div>
										<div class="form-group">
											<label for="newsletter_subject" class="control-label">Tárgy</label>
											<input type="text" name="newsletter_subject" id="newsletter_subject" placeholder="" class="form-control input-xlarge" />
										</div>
										<div class="form-group">
											<label for="newsletter_body" class="control-label">Tartalom</label>
											<textarea name="newsletter_body" id="newsletter_body" placeholder="" class="form-control input-xlarge"></textarea>
											<?php if(isset($ckeditor) && $ckeditor === true) { ?>
											<script>
												//CKEDITOR.replace( 'newsletter_body' );
												CKEDITOR.replace( 'newsletter_body', {customConfig: 'config_custom3.js'});
											</script>
											<?php } ?>
										</div>
		
										<div class="form-group">
											<label for="newsletter_status">Státusz</label>
											<select name='newsletter_status' class="form-control input-xlarge">
												<option value="1">Aktív</option>
												<option value="0">Inaktív</option>
											</select>
										</div>
<!--
	<div class="form-group">
		<label for="newsletter_template">Sablon</label>
		<select name='newsletter_template' class="form-control input-xlarge">
			<option value="0">template1</option>
			<option value="1">template2</option>
		</select>
	</div>
-->	
										<div class="space10"></div>
										<button class="btn green submit" type="submit" value="submit" name="submit_new_newsletter">Mentés <i class="fa fa-check"></i></button>
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
</div><!-- END CONTAINER -->
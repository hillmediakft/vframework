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
			<li><a href="#">Hírlevél szerkesztése</a></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

            <!-- echo out the system feedback (error and success messages) -->
            <div id="ajax_message"></div>
            <?php $this->renderFeedbackMessages(); ?>			

			<form action="" method="POST" id="newsletter_update_form" enctype="multipart/form-data">	

				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

			        <div class="portlet-title">
			            <div class="caption">
			                <i class="fa fa-film"></i> 
			                Hírlevél szerkesztése
			            </div>
			            <div class="actions">
			            	<button class="btn green submit" type="submit">Mentés <i class="fa fa-check"></i></button>
			                <a class="btn default btn-sm" href="admin/newsletter"><i class="fa fa-close"></i> Mégsem</a>
			            </div>
			        </div>

					<div class="portlet-body">

						<div class="row">	
							<div class="col-md-12">		
                                <div class="form-group">
                                    <label for="newsletter_name" class="control-label">Név</label>
                                    <input type="text" name="newsletter_name" id="newsletter_name" value="<?php echo $this->newsletter[0]['newsletter_name'];?>" placeholder="" class="form-control input-xlarge" />
                                </div>
                                <div class="form-group">
                                    <label for="newsletter_subject" class="control-label">Tárgy</label>
                                    <input type="text" name="newsletter_subject" id="newsletter_subject" value="<?php echo $this->newsletter[0]['newsletter_subject'];?>" placeholder="" class="form-control input-xlarge" />
                                </div>
                                <div class="form-group">
                                    <label for="newsletter_body" class="control-label">Tartalom</label>
                                    <textarea name="newsletter_body" id="newsletter_body" placeholder="" class="form-control input-xlarge"><?php echo $this->newsletter[0]['newsletter_body'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="newsletter_status">Státusz</label>
                                    <select name='newsletter_status' class="form-control input-xlarge">
                                        <option value="1" <?php echo ($this->newsletter[0]['newsletter_status'] == 1) ? "selected='selected'" : '';?> >Aktív</option>
                                        <option value="0" <?php echo ($this->newsletter[0]['newsletter_status'] == 0) ? "selected='selected'" : '';?> >Inaktív</option>
                                    </select>
                                </div>
							</div>
						</div>	

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
		
			</form>
		
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
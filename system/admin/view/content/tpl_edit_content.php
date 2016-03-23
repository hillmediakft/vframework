		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						Tartalom <small>szerkesztése</small>
					</h3>
					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li><a href="#">Tartalmi elem szerkesztése</a></li>
						</ul>
					</div>
					<!-- END PAGE TITLE & BREADCRUMB-->
				<!-- END PAGE HEADER-->

			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

						<div class="row">
							<div class="col-lg-12 margin-bottom-20">
								<a class ='btn btn-default' href='admin/content'><i class='fa fa-arrow-left'></i>  Vissza az tartalmakhoz</a>
							</div>
						</div>	

						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet">

							<div class="portlet-body">

								
								<h2 class='cim1'><?php echo $this->data_arr[0]['content_title'];?> szerkesztése</h2>
								<br />
								<form action='' name='update_content_form' id='update_content_form' method='POST'>
									
									<input type="hidden" name="content_id" id="content_id" value="<?php echo $this->data_arr[0]['content_id'] ?>">
									
									<div class="form-group">
										<label for="content_name">A tartalmi elem neve</label>	
										<input type="text" name="content_name" class="form-control input-large" disabled value="<?php echo $this->data_arr[0]['content_name'];?>"/>
									</div>
									
									<div class="form-group">
										<label for="content_title">A tartalmi elem megnevezése</label>	
										<input type='text' name='content_title' class='form-control input-large' value="<?php echo $this->data_arr[0]['content_title'];?>"/>
									</div>
									
									<div class="form-group">
										<label for="content_body">Tartalom</label>
										<textarea type='text' name='content_body' class='form-control'><?php echo $this->data_arr[0]['content_body'] ?></textarea>
									</div>
									

										<input class="btn green submit" type="submit" name="submit_update_content" value="Mentés">
										
								</form>									

							</div> <!-- END USER GROUPS PORTLET BODY-->
						</div> <!-- END USER GROUPS PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->
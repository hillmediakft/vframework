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

								
								<h2 class='cim1'><?php echo $content['title'];?> szerkesztése</h2>
								<br />
								<form action='' name='update_content_form' id='update_content_form' method='POST'>
									
									<input type="hidden" name="content_id" id="content_id" value="<?php echo $content['id'] ?>">
									
									<div class="form-group">
										<label for="name">A tartalmi elem neve</label>	
										<input type="text" name="name" class="form-control input-large" disabled value="<?php echo $content['name'];?>"/>
									</div>
									
									<div class="form-group">
										<label for="title">A tartalmi elem megnevezése</label>	
										<input type='text' name='title' class='form-control input-large' value="<?php echo $content['title'];?>"/>
									</div>
									
									<div class="form-group">
										<label for="body">Tartalom</label>
										<textarea type='text' name='body' class='form-control'><?php echo $content['body'] ?></textarea>
									</div>
									

										<input class="btn green submit" type="submit" name="submit_update_content" value="Mentés">
										
								</form>									

							</div> <!-- END USER GROUPS PORTLET BODY-->
						</div> <!-- END USER GROUPS PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->
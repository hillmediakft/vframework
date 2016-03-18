<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
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
					<li><a href="#">Vélemény hozzáadása</a></li>
				</ul>
			</div>
			<!-- END PAGE TITLE & BREADCRUMB-->
			<!-- END PAGE HEADER-->

			<div class="margin-bottom-20"></div>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

						<div class="row">
							<div class="col-lg-12 margin-bottom-20">
								<a class ='btn btn-default' href='admin/testimonials'><i class='fa fa-arrow-left'></i>  Vissza a rólunk mondták elemekhez</a>
							</div>
						</div>	

						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet">

							<div class="portlet-body">

								<form action='' name='new_testimonial_form' id='new_testimonial_form' method='POST'>
									
										<div class="form-group">
										<label for="testimonial_name">Név</label>	
										<input type="text" name="testimonial_name" class="form-control input-large" value=""/>
									</div>
									
									<div class="form-group">
										<label for="testimonial_title">Beosztás</label>	
										<input type='text' name='testimonial_title' class='form-control input-large' value=""/>
									</div>
									
									<div class="form-group">
										<label for="testimonial_text">Vélemény</label>
										<textarea type='text' name='testimonial_text' class='form-control'></textarea>
									</div>

										<input class="btn green submit" type="submit" name="submit_new_testimonial" value="Mentés">
										
								</form>									

							</div> <!-- END USER GROUPS PORTLET BODY-->
						</div> <!-- END USER GROUPS PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->    
	</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
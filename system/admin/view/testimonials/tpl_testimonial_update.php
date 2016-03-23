<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				Rólunk mondták <small>szerkesztése</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="admin/home">Kezdőoldal</a> 
						<i class="fa fa-angle-right"></i>
					</li>
					<li><a href="#">Róluk mondták elem szerkesztése</a></li>
				</ul>
			</div>
			<!-- END PAGE TITLE & BREADCRUMB-->
		<!-- END PAGE HEADER-->

	
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

						
						<h2 class='cim1'><?php echo $this->data_arr[0]['name'];?> véleményének szerkesztése</h2>
						<br />
						<form action='' name='update_testimonial_form' id='update_testimonial_form' method='POST'>

						<input type="hidden" name="testimonial_id" id="testimonial_id" value="<?php echo $this->data_arr[0]['id'] ?>">

						<div class="form-group">
						<label for="testimonial_name">Név</label>	
						<input type="text" name="testimonial_name" class="form-control input-large" value="<?php echo $this->data_arr[0]['name'];?>"/>
						</div>

						<div class="form-group">
						<label for="testimonial_title">Beosztás</label>	
						<input type='text' name='testimonial_title' class='form-control input-large' value="<?php echo $this->data_arr[0]['title'];?>"/>
						</div>

						<div class="form-group">
						<label for="testimonial_text">Vélemény</label>
						<textarea type='text' name='testimonial_text' class='form-control'><?php echo $this->data_arr[0]['text'] ?></textarea>
						</div>


						<input class="btn green submit" type="submit" name="submit_update_testimonial" value="Mentés">

						</form>									

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
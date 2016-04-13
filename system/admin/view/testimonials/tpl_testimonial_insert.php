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

			<form action="" id="testimonial_form" method="POST">

				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

	                <div class="portlet-title">
	                    <div class="caption"><i class="fa fa-file"></i>Vélemény hozzáadása</div>
	                    <div class="actions">
                            <button class="btn green btn-sm" type="submit" id="send_testimonial_form" name="send_testimonial_form"><i class="fa fa-check"></i> Mentés</button>
							<a class="btn default btn-sm" href="admin/testimonials"><i class="fa fa-close"></i> Mégsem</a>
	                    </div>
	                </div>

					<div class="portlet-body">
							
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

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->

			</form>									

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
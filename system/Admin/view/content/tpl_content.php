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
					<li><a href="#">Tartalom szerkesztése</a></li>
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
						<div class="caption"><i class="fa fa-file"></i>Szerkeszthető tartalmi elemek</div>
						<!--
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
							<a href="#portlet-config" data-toggle="modal" class="config"></a>
							<a href="javascript:;" class="reload"></a>
							<a href="javascript:;" class="remove"></a>
						</div>
						-->
					</div>
					<div class="portlet-body">

						<table class="table table-striped table-bordered table-hover" id="content">
							<thead>
								<tr class="heading">
									<th>#id</th>
									<th>Tartalom neve</th>
									<th>Tartalom leírása</th>
									<th style="width:110px"></th>
								</tr>
							</thead>
							<tbody>

					<?php foreach($all_content as $content) { ?>
								<tr class="odd gradeX">
									<td><?php echo $content['id'];?></td>
									<td><?php echo $content['name'];?></td>
									<td><?php echo $content['title'];?></td>
										
									<td>
										<a class="btn btn-sm green" href="admin/content/edit/<?php echo $content['id'];?>" ><i class="fa fa-pencil"></i>
										Szerkeszt</a>
									</td>
								</tr>

					<?php } ?>					

							</tbody>
						</table>
					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
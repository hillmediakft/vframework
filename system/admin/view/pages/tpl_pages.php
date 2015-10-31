<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<!--
					<h3 class="page-title">
						Oldalak <small>kezelése</small>
					</h3>
					-->
					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li>
							    <span>Oldalak</span>
				            </li>
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
								<div class="caption"><i class="fa fa-file"></i>Szerkeszthető oldalak</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="users">
									<thead>
										<tr class="heading">
											<th>#id</th>
											<th>Oldal</th>
											<th>Cím (meta title)</th>
											<th>Leírás (meta description)</th>
											<th style="width:0px"></th>
										</tr>
									</thead>
									<tbody>

									
<?php foreach($all_pages as $value) { ?>

			<tr class="odd gradeX">

				<td><?php echo $value['page_id'];?></td>
				<td><?php echo $value['page_title'];?></td>

				<td><?php echo $value['page_metatitle'];?></td>
				<td><?php echo $value['page_metadescription'];?></td>

				<td>									
					
					<a class="btn btn-sm grey-steel" href="<?php echo $this->registry->site_url . 'pages/edit/' . $value['page_id'];?>">
						<i class="fa fa-cogs"></i>
					</a>

				</td>
				
			</tr>

		<?php } ?>										
									
<!--									

										<tr class="odd gradeX">
											<td>1</td>
											<td>Kezdőoldal</td>
											<td>Gipsz Jakab Kft - melegburkolás</td>
											<td>A cég tevékenysége meleg- és hidegburkolási tevékenység</td>			
											<td>
												<a class="btn btn-sm green" href="#" ><i class="fa fa-pencil"></i>
												Szerkeszt</a>
											</td>
										</tr>

										<tr class="odd gradeX">
											<td>2</td>
											<td>Rólunk</td>
											<td>Bemutatkozik a cégünk, mit csinálunk</td>
											<td>Mit kell tudni a meleg- és hidegburkolási tevékenységről</td>	
											<td>
												<a class="btn btn-sm green" href="#" ><i class="fa fa-pencil"></i>
												Szerkeszt</a>
											</td>
										</tr>

										<tr class="odd gradeX">
											<td>2</td>
											<td>Mezei user</td>
											<td>Csekély jogkörrel rendelkezik</td>
											<td>11</td>				
											<td >
												<a class="btn btn-sm green" href="admin/users/edit_roles" ><i class="fa fa-pencil"></i> Szerkeszt</i></a>
											</td>
										</tr>				
-->
									</tbody>
								</table>
							</div> <!-- END USER GROUPS PORTLET BODY-->
						</div> <!-- END USER GROUPS PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->    
	</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
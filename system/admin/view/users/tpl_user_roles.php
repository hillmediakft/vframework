<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						Felhasználói <small>csoportok</small>
					</h3>
					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li><a href="#">Felhasználói csoportok</a></li>
						</ul>
					</div>
					<!-- END PAGE TITLE & BREADCRUMB-->
				<!-- END PAGE HEADER-->

			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

					

						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-users"></i>Felhasználói csoportok</div>
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
								<div class="table-toolbar">
									<div class="btn-group">
										<button id="sample_editable_1_new" class="btn blue">
										Új csoport hozzáadása <i class="fa fa-plus"></i>
										</button>
									</div>
									<div class="btn-group pull-right">
										<button class="btn dropdown-toggle" data-toggle="dropdown">Eszközök <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="#">Nyomtatás</a></li>
											<li><a href="#">Mentés PDF</a></li>
											<li><a href="#">Mentés Excel</a></li>
										</ul>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover" id="users">
									<thead>
										<tr>
											<th>Id</th>
											<th>Felhasználói csoport</th>
											<th>Leírás</th>
											<th>Felhasználók száma</th>
											<th style="width:115px"></th>
										</tr>
									</thead>
									<tbody>


										<tr class="odd gradeX">
											<td>1</td>
											<td>Szuper adminisztrátor</td>
											<td>Teljes jogkörrel rendelkezik</td>
											<td>1</td>			
											<td>
												<a class="btn btn-sm green" href="#" ><i class="fa fa-pencil"></i>
												Szerkeszt</a>
											</td>
										</tr>

										<tr class="odd gradeX">
											<td>2</td>
											<td>Adminisztrátor</td>
											<td>Korlátozott adminisztrációs jogkörrel rendelkezik</td>
											<td>3</td>				
											<td >
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

									</tbody>
								</table>
							</div> <!-- END USER GROUPS PORTLET BODY-->
						</div> <!-- END USER GROUPS PORTLET-->
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->    
	</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
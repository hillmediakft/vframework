<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
				<!-- BEGIN PAGE HEADER-->
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						Felhasználói jogosultságok <small>kezelése</small>
					</h3>
					<div class="page-bar">
						<ul class="page-breadcrumb">
							<li>
								<i class="fa fa-home"></i>
								<a href="admin/home">Kezdőoldal</a> 
								<i class="fa fa-angle-right"></i>
							</li>
							<li><a href="#">Felhasználói jogosultságok</a></li>
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
								<div class="caption"><i class="fa fa-cog"></i>Jogosultságok szerkesztése</div>
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
									Felhasználói csoport: <span class="label label-info">admin</span>
									
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
							
					
						<form action="admin/users/edit_roles" method="POST" id="edit_roles">				
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr class="info">
											<th>Jogosultság</th>
											<th>Leírás</th>
											<th>Engedélyezés</th>
										</tr>
									</thead>
									<tbody>
									
		
										<tr class="odd gradeX">
											<td colspan="3"><strong>Felhasználók kezelése</strong></td>
										</tr>
										
										<tr class="odd gradeX">
											<td>Új felhasználó hozzáadása</td>
											<td>Az új felhasználó menüpontban új felhasználót adhat a felhasználók listájához.</td>
											<td>
												<div class="checkbox">
												  <input type="checkbox" name="add_new_user">
												</div>
											</td>

										</tr>
										
										<tr class="odd gradeX">
											<td>Felhasználó törlése</td>
											<td>Az új felhasználó menüpontban új felhasználót adhat a felhasználók listájához.</td>
											<td>
												<div class="checkbox">
												  <input type="checkbox" name="delete_user">
												</div>
											</td>
										</tr>
										
										<tr class="odd gradeX">
											<td colspan="3"><strong>Menü hozzáférés</strong></td>
										</tr>
										
										<tr class="odd gradeX">
											<td>Felhasználó menü</td>
											<td>Az új felhasználó menüpontban új felhasználót adhat a felhasználók listájához.</td>
											<td>
												<div class="checkbox">
												  <input type="checkbox" name="user_menu">
												</div>
											</td>
										</tr>

									</tbody>
								</table>
								
								<button class="btn green submit" type="submit" value="Mentés" name="submit_edit_roles">Mentés <i class="fa fa-check"></i></button>
								
								</form>
								
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
			
			
			</div>
			<!-- END PAGE CONTAINER-->    
		</div>                                                            
		<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->

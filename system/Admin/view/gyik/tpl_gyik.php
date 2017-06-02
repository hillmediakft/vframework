<?php 
use System\Libs\Auth;
?>
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
				<li><span>GYIK</span></li>
			</ul>
		</div>
		<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->
	
	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<!-- ÜZENETEK -->
			<div id="ajax_message"></div>
			<?php $this->renderFeedbackMessages(); ?>

			<form class="horizontal-form" id="gyik_form" method="POST" action="">							
								
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-cogs"></i>Gyakori kérdések listája</div>
						
							<div class="actions">
								<a href="admin/gyik/create" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új GYIK</a>
								<button class="btn red btn-sm" id="delete_group" type="button"><i class="fa fa-trash"></i> Csoportos törlés</button>
							</div>
						
					</div>
					<div class="portlet-body">

						<table class="table table-striped table-bordered table-hover table-checkable dataTable" id="gyik">
							<thead>
								<tr>
									<th class="table-checkbox">
										<input type="checkbox" class="group-checkable" data-set="#gyik .checkboxes" />
									</th>
									<th>Kérdés</th>
									<th>Válasz</th>
									<th>Kategória</th>
									<th>Létrehozva</th>
									<th style="width:1px;">Státusz</th>
									<th style="width:1px;"></th>
								</tr>
							</thead>
							<tbody>

							<?php foreach($all_gyik as $gyik) { ?>
								<tr class="odd gradeX">
									<td>
										<?php if (Auth::hasAccess('gyik.delete')) { ?>
										<input type="checkbox" class="checkboxes" name="gyik_id_<?php echo $gyik['id'];?>" value="<?php echo $gyik['id'];?>"/>
										<?php } ?>
									</td>
									<td><?php echo $gyik['title'];?></td>
									<td><?php echo $gyik['description'];?></td>
									<td><?php echo $gyik['category_name'];?></td>
									<td><?php echo date('Y-m-d', $gyik['create_timestamp']);?></td>
									
                                    <?php if ($gyik['status'] == 1) { ?>
                                        <td><span class="label label-sm label-success">Aktív</span></td>
                                    <?php } ?>
                                    <?php if ($gyik['status'] == 0) { ?>
                                        <td><span class="label label-sm label-danger">Inaktív</span></td>
                                    <?php } ?>

									<td>									
										<div class="actions">
											<div class="btn-group">
												<a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
												<ul class="dropdown-menu pull-right">
													<?php if (Auth::hasAccess('gyik.edit')) { ?>	
														<li><a href="admin/gyik/edit/<?php echo $gyik['id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
													<?php } ?>
													<?php if (Auth::hasAccess('gyik.delete')) { ?>
														<li><a class="delete_item" data-id="<?php echo $gyik['id']; ?>"><i class="fa fa-trash"></i> Töröl</a></li>
													<?php } ?>
                                                    <?php if (Auth::hasAccess('gyik.change_status')) { ?>	
                                                        <?php if ($gyik['status'] == 1) { ?>
                                                            <li><a class="change_status" data-id="<?php echo $gyik['id']; ?>" data-action="make_inactive"><i class="fa fa-ban"></i> Blokkol</a></li>
                                                        <?php } ?>
                                                        <?php if ($gyik['status'] == 0) { ?>
                                                            <li><a class="change_status" data-id="<?php echo $gyik['id']; ?>" data-action="make_active"><i class="fa fa-check"></i> Aktivál</a></li>
                                                        <?php } ?>
                                                    <?php } ?>	

												</ul>
											</div>
										</div>
									</td>
								</tr>
							<?php } ?>	
							</tbody>
						</table>
						
					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
								
			</form>			
				
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->    
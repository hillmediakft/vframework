<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<!-- 
			<h3 class="page-title">
				Kezdőoldali slider <small>beállítások</small>
			</h3>
			-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="admin/home">Kezdőoldal</a> 
						<i class="fa fa-angle-right"></i>
					</li>
					<li><span>Slider lista</span></li>
				</ul>
			</div>
			<!-- END PAGE TITLE & BREADCRUMB-->
		<!-- END PAGE HEADER-->
		
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

					<div id="loadingDiv" style="display:none;"><img src="public/admin_assets/img/loader.gif"></div>
				<!-- ÜZENETEK -->	
					<div id="ajax_message">
						<div class="alert alert-success" style="display:none;"></div>
						<!-- <div class="alert alert-danger"></div>-->
					</div> 
					<?php $this->renderFeedbackMessages(); ?>	

					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-film"></i> 
								Kezdőoldali slider kezelése
							</div>
							<div class="actions">
								<a class="btn blue-steel btn-sm" href="admin/slider/new_slide"><i class="fa fa-plus"></i> Slide hozzáadása</a>
							</div>
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
								
							<table class="table table-hover slider_list">
								<thead>
									<tr>
										<th style="width: 250px">Kép</th>
										<th>Slide címe</th>
										<th>Slide szövege</th>
										<th>Státusz</th>
										<th style="width: 120px"></th>
									</tr>
								</thead>
								<tbody id="slider_list">						

									<?php foreach($slider as $value) { ?>
									<tr id="slider_<?php echo $value['id'];?>" class="odd gradeX">
														
										<td><img src="<?php echo Config::get('slider.upload_path') . Util::thumb_path($value['picture']);?>"></td>
										<td><?php echo $value['title'];?></td>
										<td><?php echo $value['text'];?></td>
										
										<?php if ($value['active'] == 1) {?>
										<td><span class="label label-sm label-success"><?php echo 'Aktív';?></span></td>
										<?php } ?>
										<?php if ($value['active'] == 0) {?>
										<td><span class="label label-sm label-danger"><?php echo 'Inaktív';?></span></td>
										<?php } ?>
										<td>									
											<div class="actions">
												<div class="btn-group">
													<a class="btn btn-sm grey-steel" title="Műveletek" href="#" data-toggle="dropdown">
														<i class="fa fa-cogs"></i>
													</a>
													<ul class="dropdown-menu pull-right">
														<li><a href="admin/slider/edit/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
														<li><a href="admin/slider/delete/<?php echo $value['id'];?>" id="delete_<?php echo $value['id'];?>"><i class="fa fa-trash"></i> Töröl</a></li>
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
						
				</div> <!-- END COL-MD-12 -->
			</div> <!-- END ROW -->	
		</div> <!-- END PAGE CONTENT-->    
	</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
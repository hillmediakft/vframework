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
						<li>
							<a href="admin/photo_gallery">Képgaléria</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li><a href="admin/photo_gallery/category">Kategóriák</a></li>
					</ul>
				</div>
				<!-- END PAGE TITLE & BREADCRUMB-->
			<!-- END PAGE HEADER-->
		
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">

					<!-- ÜZENETEK -->
					<div id="ajax_message"></div>
					<?php $this->renderFeedbackMessages(); ?>
						
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-cogs"></i>Fotó kategóriák</div>
								
									<div class="actions">
										<a href="admin/photo_gallery/category_insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új kategória</a>
										<div class="btn-group">
											<a data-toggle="dropdown" href="#" class="btn btn-sm default">
												<i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
											</a>
												<ul class="dropdown-menu pull-right">
													<li>
														<a href="#" id="print_photo_category"><i class="fa fa-print"></i> Nyomtat </a>
													</li>
													<li>
														<a href="#" id="export_photo_category"><i class="fa fa-file-excel-o"></i> Export CSV </a>
													</li>
												</ul>
										</div>
									</div>
								
							</div>
							<div class="portlet-body">

								<table class="table table-striped table-bordered table-hover" id="photo_category">
									<thead>
										<tr>
											<th>Név</th>
											<th>Képek száma</th>
											<th style="width:0px;"></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($all_category as $value) { ?>
										<tr class="odd gradeX">
											<td>
												<a class="xedit" id="<?php echo $value['category_name']; ?>" data-type="text" data-pk="<?php echo $value['category_id']; ?>" data-title="Írja be a szöveget"><?php echo $value['category_name']; ?></a>
												<?php //echo $value['category_name'];?>
											</td>
												<?php
												// megszámoljuk, hogy az éppen aktuális kategóriának mennyi eleme van a photo_gallery tábla photo_category oszlopában
												$counter = 0;
												foreach($category_counter as $v) {
													if($value['category_id'] == $v['photo_category']) {
														$counter++;
													}
												}
												?>
											<td><?php echo $counter;?></td>
											<td>
												<a class="btn btn-sm grey-steel delete_item" data-id="<?php echo $value['category_id'] ?>" title="törlés"><i class="fa fa-trash"></i></a>
											<!--									
												<div class="actions">
													<div class="btn-group">
														<a class="btn btn-sm grey-steel" data-toggle="dropdown" title="műveletek"><i class="fa fa-cogs"></i></a>
														<ul class="dropdown-menu pull-right">
														<?php //if (1) { ?>	
															<li><a href="admin/photo_gallery/category_update/<?php //echo $value['category_id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
														<?php //} ?>
														</ul>
													</div>
												</div>
											-->

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
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
				<a href="admin/photo-gallery">Képgaléria</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><a href="admin/photo-gallery/category">Kategóriák</a></li>
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
				
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-cogs"></i>
							Fotó kategóriák
						</div>
						<div class="actions">

							<a href="javascript:;" class="btn blue btn-sm" id="category_insert_button"><i class="fa fa-plus"></i> Új kategória</a>

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
									<?php foreach ($langs as $lang) { ?>
										<th>Név / <?php echo $lang; ?></th>
									<?php } ?>
									<th>Képek száma</th>
									<th style="max-width: 100px"></th>
									<th style="max-width: 100px"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($all_category as $category) { ?>
								<tr class="odd gradeX" data-id="<?php echo $category['id'];?>">
									
									<!-- kategóriák -->
									<?php foreach ($langs as $lang) { ?>
									<td data-lang="<?php echo $lang ?>"><?php echo $category['category_name_' . $lang];?></td>
									<?php } ?>
									<!-- képek száma -->
									<td><?php echo isset($category_counter[$category['id']]) ? $category_counter[$category['id']] : 0;?></td>

									<td>
	                                    <a class="edit" href="javascript:;"><i class="fa fa-edit"></i> Szerkeszt </a>
	                                </td>
	                                <td>
	                                    <a class="delete" href="javascript:;"><i class="fa fa-trash"></i> Töröl </a>
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
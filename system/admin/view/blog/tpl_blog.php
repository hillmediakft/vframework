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
				<li><a href="admin/blog">Blog</a></li>
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

			<form class="horizontal-form" id="blog_form" method="POST" action="">							
								
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-cogs"></i>Blogok kezelése</div>
						
							<div class="actions">
								<a href="admin/blog/insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új bejegyzés</a>
								<button class="btn red btn-sm" id="delete_group" type="button"><i class="fa fa-trash"></i> Csoportos törlés</button>
								<div class="btn-group">
									<a data-toggle="dropdown" href="#" class="btn btn-sm default">
										<i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
									</a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="#" id="print_blog"><i class="fa fa-print"></i> Nyomtat </a>
											</li>
											<li>
												<a href="#" id="export_blog"><i class="fa fa-file-excel-o"></i> Export CSV </a>
											</li>
										</ul>
								</div>
							</div>
						
					</div>
					<div class="portlet-body">

						<table class="table table-striped table-bordered table-hover table-checkable dataTable" id="blog">
							<thead>
								<tr>
									<th class="table-checkbox">
										<input type="checkbox" class="group-checkable" data-set="#blog .checkboxes" />
									</th>
									<th>Cím</th>
									<th>Dátum</th>
									<th>Kategória</th>
									<th style="width:0px;"></th>
								</tr>
							</thead>
							<tbody>

							<?php foreach($this->all_blog as $value) { ?>
								<tr class="odd gradeX">
									<td>
										<?php if (1) { ?>
										<input type="checkbox" class="checkboxes" name="blog_id_<?php echo $value['blog_id'];?>" value="<?php echo $value['blog_id'];?>"/>
										<?php } ?>	
									</td>
									<!-- <td><img src="<?php //echo Util::thumb_path($value['blog_picture']);?>" width="60" /></td>-->
									<td><?php echo $value['blog_title'];?></td>
									<td><?php echo $value['blog_add_date'];?></td>
									<td><?php echo $value['category_name'];?></td>
									<td>									
										<div class="actions">
											<div class="btn-group">
												<a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
												<ul class="dropdown-menu pull-right">
													<?php if (1) { ?>	
														<li><a href="admin/blog/update/<?php echo $value['blog_id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
													<?php } ?>
													<?php if (1) { ?>
														<li><a class="delete_item" data-id="<?php echo $value['blog_id']; ?>"><i class="fa fa-trash"></i> Töröl</a></li>
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
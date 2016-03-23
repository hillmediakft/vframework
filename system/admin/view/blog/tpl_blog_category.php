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
				<a href="admin/blog">Blog</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><a href="admin/blog/category">Kategóriák</a></li>
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
				
			<form class="horizontal-form" id="blog_category_form" method="POST" action="">							
								
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-cogs"></i>Blog kategóriák</div>
						
							<div class="actions">
								<!-- <a href="admin/blog/category_insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új kategória</a> -->
								
								<a href="javascript:;" class="btn blue btn-sm" id="category_insert_button"><i class="fa fa-plus"></i> Új kategória</a>


								<div class="btn-group">
									<a data-toggle="dropdown" href="#" class="btn btn-sm default">
										<i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
									</a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="#" id="print_blog_category"><i class="fa fa-print"></i> Nyomtat </a>
											</li>
											<li>
												<a href="#" id="export_blog_category"><i class="fa fa-file-excel-o"></i> Export CSV </a>
											</li>
										</ul>
								</div>
							</div>
						
					</div>
					<div class="portlet-body">

						<table class="table table-striped table-bordered table-hover" id="blog_category">
							<thead>
								<tr>
									<th>Név</th>
									<th>Bejegyzések száma</th>
									<th style="max-width: 100px"></th>
									<th style="max-width: 100px"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($this->all_blog_category as $value) { ?>
								<tr class="odd gradeX" data-id="<?php echo $value['category_id'];?>">
									<td><?php echo $value['category_name'];?></td>
										<?php
										// megszámoljuk, hogy az éppen aktuális kategóriának mennyi eleme van a blog tábla blog_category oszlopában
										$counter = 0;
										foreach($this->category_counter as $v) {
											if($value['category_id'] == $v['blog_category']) {
												$counter++;
											}
										}
										?>
									<td><?php echo $counter;?></td>

									<td>
                                        <a class="edit" href="javascript:;"><i class="fa fa-edit"></i> Szerkeszt </a>
                                    </td>
                                    <td>
                                        <a class="delete" href="javascript:;"><i class="fa fa-trash"></i> Töröl </a>
                                    </td>


<!--
									<td>									
										<div class="actions">
											<div class="btn-group">
												<a class="btn btn-sm grey-steel" data-toggle="dropdown" title="műveletek"><i class="fa fa-cogs"></i></a>
												<ul class="dropdown-menu pull-right">
												<?php //if (1) { ?>	
													<li><a href="admin/blog/category_update/<?php //echo $value['category_id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
												<?php //} ?>
												</ul>
											</div>
										</div>
									</td>
-->



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
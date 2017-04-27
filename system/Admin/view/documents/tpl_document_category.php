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
				<a href="admin/documents">Dokumentumok</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><a href="admin/documents/category">Dokumentum kategóriák</a></li>
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
				
			<form class="horizontal-form" id="document_category_form" method="POST" action="">							
								
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-cogs"></i>Dokumentum kategóriák</div>
						
							<div class="actions">
								<!-- <a href="admin/document/category_insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új kategória</a> -->
								
								<a href="javascript:;" class="btn blue btn-sm" id="category_insert_button"><i class="fa fa-plus"></i> Új kategória</a>


								<div class="btn-group">
									<a data-toggle="dropdown" href="#" class="btn btn-sm default">
										<i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
									</a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="#" id="print_document_category"><i class="fa fa-print"></i> Nyomtat </a>
											</li>
											<li>
												<a href="#" id="export_document_category"><i class="fa fa-file-excel-o"></i> Export CSV </a>
											</li>
										</ul>
								</div>
							</div>
						
					</div>
					<div class="portlet-body">

						<table class="table table-striped table-bordered table-hover" id="document_category">
							<thead>
								<tr>
									<th>Név</th>
									<th>Bejegyzések száma</th>
									<th style="max-width: 100px"></th>
									<th style="max-width: 100px"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($all_document_category as $value) { ?>
								<tr data-id="<?php echo $value['id'];?>">
									
									<td><?php echo $value['name'];?></td>
										<?php
										// megszámoljuk, hogy az éppen aktuális kategóriának mennyi eleme van a document tábla document_category oszlopában
										$counter = 0;
										
										foreach($category_counter as $v) {
											if($value['id'] == $v['category_id']) {
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
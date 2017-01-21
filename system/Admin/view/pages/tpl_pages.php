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
			    <span>Oldalak</span>
            </li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->
	
	<div class="margin-bottom-20"></div>
	
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
							<?php foreach($all_pages as $page) { ?>
							<tr class="odd gradeX">
								<td><?php echo $page['id'];?></td>
								<td><?php echo $page['title'];?></td>
								<td><?php echo $page['metatitle'];?></td>
								<td><?php echo $page['metadescription'];?></td>
								<td>									
									<a class="btn btn-sm grey-steel" href="admin/pages/update/<?php echo $page['id'];?>"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
							<?php } ?>										
						</tbody>
					</table>
				</div> <!-- END PORTLET BODY-->
			</div> <!-- END PORTLET-->
		
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
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
			<li><a href="admin/languages#">Nyelvek kezelése</a></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i>Nyelvi fájlok szerkesztése</div>
				</div>
				<div class="portlet-body">

					<div class="note note-info">
						<i class="fa fa-info-circle"></i> Szerkesztéshez kattintson a kék színű, szaggatott vonallal aláhúzott szövegekre!
					</div> 

					<div class="row">
						<div class="col-md-12">
							<table id="user" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>Kód</th>
								<th>Magyar</th>
								<th>Angol</th>
								<th>Német</th>
							<tbody>
								<?php foreach($this->languages as $value) { ?>			
								<tr>
									<td style="width:15%">
										 <?php echo $value['text_code'];?>
									</td>
									<td style="width:28%">
										<a href="admin/languages#" class="xedit" id="<?php echo $value['text_code'];?>_hu" data-type="text" data-pk="<?php echo $value['text_id'];?>" data-title="Írja be a szöveget"><?php echo $value['text_hu'];?></a>
									</td>
									<td style="width:27%">
										<a href="admin/languages#" class="xedit" id="<?php echo $value['text_code'];?>_en" data-type="text" data-pk="<?php echo $value['text_id'];?>" data-title="Írja be a szöveget"><?php echo $value['text_en'];?></a>
									</td>
									<td style="width:27%">
										<a href="admin/languages#" class="xedit" id="<?php echo $value['text_code'];?>_de" data-type="text" data-pk="<?php echo $value['text_id'];?>" data-title="Írja be a szöveget"><?php echo $value['text_de'];?></a>
									</td>
								</tr>
								<?php } ?>	
							</tbody>
							</table>
						</div>
					</div>

				</div> <!-- END USER GROUPS PORTLET BODY-->
			</div> <!-- END USER GROUPS PORTLET-->

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
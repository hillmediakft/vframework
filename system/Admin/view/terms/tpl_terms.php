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
				<span>Címkék</span>
			</li>
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
				
			<form class="horizontal-form" id="terms_form" method="POST" action="">							
								
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-cogs"></i>Címkék listája</div>
						<div class="actions">
							<a href="javascript:;" class="btn blue btn-sm" id="term_insert_button"><i class="fa fa-plus"></i> Új címke</a>
						</div>
					</div>
					<div class="portlet-body">

						<table class="table table-striped table-bordered table-hover" id="terms">
							<thead>
								<tr>
									<?php foreach ($langs as $lang) { ?>
										<th>Címke / <?php echo $lang; ?></th>
									<?php } ?>
									<th style="max-width: 100px"></th>
									<th style="max-width: 100px"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($terms as $key => $term) { ?>
																			
								<tr data-id="<?php echo $term['id'];?>">
									
									<!-- címkék -->
									<?php foreach ($langs as $lang) { ?>
										<td data-lang="<?php echo $lang ?>">
											<?php echo isset($term['text_' . $lang]) ? $term['text_' . $lang] : '';?>
										</td>
									<?php } ?>

									<!-- Szerkesztés -->
									<td>
                                        <a class="edit" href="javascript:;"><i class="fa fa-edit"></i> Szerkeszt </a>
                                    </td>

                                    <!-- Törlés -->
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
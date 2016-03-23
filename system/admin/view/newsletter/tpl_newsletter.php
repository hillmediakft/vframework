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
			<li><span>Hírlevél</span></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>
	
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div id="message-box">
			
				<!-- BEGIN PROGRESS BAR -->	
				<div id="message" style="display:none; border:1px solid #000; padding:10px; width:350px; height:150px; overflow:auto; background:#eee;"></div> 	
				<div id="message_done"></div>
				<br />	
				<progress id="progress_bar" value="0" max="100" style="display:none; width: 350px;"></progress>			
				<div id="progress_pc" style="text-align:right; display:block; margin-top:5px;"></div>
				<!-- END PROGRESS BAR -->	
				
				
				
				<!-- BEGIN PROGRESS BAR EVENTSOURCE -->	
<!-- 				
			<div id="results" style="border:1px solid #000; padding:10px; width:300px; height:250px; overflow:auto; background:#eee;"></div>
				<br />
				  
				<progress id="progressor" value="0" max="100" style=""></progress> 
				<span id="percentage" style="text-align:right; display:block; margin-top:5px;">0</span>
-->		
				<!-- BEGIN PROGRESS BAR EVENTSOURCE -->	
					
				
			</div>

			<!-- echo out the system feedback (error and success messages) -->
			<div id="ajax_message"></div>
			<?php $this->renderFeedbackMessages(); ?>

			<form class="horizontal-form" id="newsletter_form" method="POST" action="">	
					
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-user"></i>Hírlevelek</div>
						
							<div class="actions">
								<a href="admin/newsletter/insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új hírlevél</a>
								<button class="btn red btn-sm" id="delete_group" type="button"><i class="fa fa-trash"></i> Csoportos törlés</button>
								<div class="btn-group">
									<a data-toggle="dropdown" href="#" class="btn btn-sm default">
										<i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
									</a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="#" id="print_newsletter"><i class="fa fa-print"></i> Nyomtat </a>
											</li>
											
										</ul>
								</div>
							</div>

					</div>
					<div class="portlet-body">
						
			<!-- *************************** newsletter TÁBLA *********************************** -->						
						
						<table class="table table-striped table-bordered table-hover table-checkable dataTable" id="newsletter_table">
							<thead>
								<tr>
									<th class="table-checkbox">
										<input type="checkbox" class="group-checkable" data-set="#newsletter_table .checkboxes"/>
									</th>
									<th>Név</th>
									<th>Tárgy</th>
									<th title="Létrehozás dátuma">Létrehozva</th>
									<th title="Utolsó küldés dátuma">Utolsó küldés</th>
									<th>Státusz</th>
									<th style="width:0px;"></th>
								</tr>
							</thead>
							<tbody>

						<?php foreach($this->newsletters as $value) { ?>

							<tr class="odd gradeX">
								<td>
									<?php if (Session::get('user_role_id') > 0 && empty($value['newsletter_lastsent_date'])) { ?>
									<input type="checkbox" class="checkboxes" name="newsletter_id_<?php echo $value['newsletter_id'];?>" value="<?php echo $value['newsletter_id'];?>"/>
									<?php } ?>	
								</td>
								<td><?php echo $value['newsletter_name'];?></td>
								<td><?php echo $value['newsletter_subject'];?></td>
								<td><?php echo $value['newsletter_create_date'];?></td>
								<td><?php echo (empty($value['newsletter_lastsent_date'])) ? '<span class="label label-info">Nincs elküldve</span>' : $value['newsletter_lastsent_date'];?></td>
                                <?php if ($value['newsletter_status'] == 1) { ?>
                                    <td><span class="label label-sm label-success">Aktív</span></td>
                                <?php } ?>
                                <?php if ($value['newsletter_status'] == 0) { ?>
                                    <td><span class="label label-sm label-danger">Inaktív</span></td>
                                <?php } ?>
								<td>
									<div class="actions">
										<div class="btn-group">
											<a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown"><i class="fa fa-cogs"></i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li>
													<a class="submit_newsletter" data-id="<?php echo $value['newsletter_id'];?>"><i class="fa fa-trash"></i> Hírlevél elküldése</a>
												</li>
												<li>
													<a href="admin/newsletter/update/<?php echo $value['newsletter_id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a>
												</li>
												<?php if (empty($value['newsletter_lastsent_date'])) { ?>
												<li>
													<a class="delete_item" data-id="<?php echo $value['newsletter_id'];?>"><i class="fa fa-trash"></i> Töröl</a>
												</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</td>
							</tr>

						<?php } ?>	
			
							</tbody>
						</table>	
					</div>
				</div>
			</form>	

		</div>
	</div>
	
</div><!-- END PAGE CONTENT-->
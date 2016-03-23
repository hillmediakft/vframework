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
				<li><a href="admin/newsletter">Elküldött hírlevelek</a></li>
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
					<progress id="progress_bar" value="" max="100" style="display:none;"></progress>			
					<div id="progress_pc"></div>
					<div id="message"></div> 	
					<div id="message_done"></div>	
					<!-- END PROGRESS BAR -->	
				</div>
					<!-- echo out the system feedback (error and success messages) -->
					<?php $this->renderFeedbackMessages(); ?>
								

				<form class="horizontal-form" id="del_newsletter_form" method="POST" action="admin/newsletter/delete_newsletter">	
						
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-user"></i>Hírlevél küldés statisztikák</div>
							
								<div class="actions">

									<div class="btn-group">
										<a data-toggle="dropdown" href="#" class="btn btn-sm default">
											<i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
										</a>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="#" id="print_newsletter"><i class="fa fa-print"></i> Nyomtat </a>
												</li>
												<li>
													<a href="#" id="export_newsletter"><i class="fa fa-file-excel-o"></i> Export CSV </a>
												</li>
											</ul>
									</div>
								</div>

						</div>
						<div class="portlet-body">
							
				<!-- *************************** newsletter TÁBLA *********************************** -->						
							
							<table class="table table-striped table-bordered table-hover dataTable" id="newsletter_table">
								<thead>
									<tr>
										<th>Név</th>
										<th>Tárgy</th>
										<th>Küldés</th>
										<th>Címzettek</th>
										<th>Elküldve</th>
										<th>Hiba</th>
										<th>Megnyitás</th>
										<th>Leiratkozás</th>
										<th>Státusz</th>
										<th style="width:0px;"></th>
									</tr>
								</thead>
								<tbody>

							<?php foreach($this->newsletters as $value) { ?>

								<tr class="odd gradeX">

									<td><?php echo $value['newsletter_name'];?></td>
									<td><?php echo $value['newsletter_subject'];?></td>
									<td><?php echo $value['sent_date'];?></td>
									<td><?php echo $value['recepients'];?></td>
									<td><?php echo $value['send_success'];?></td>
									<td><?php echo $value['send_fail'];?></td>
									<td><?php echo $value['email_opens'];?></td>
									<td><?php echo $value['unsubscribe_count'];?></td>
									<td><?php echo (!$value['error']) ? '<span class="label label-success">Elküldve</span>' : '<span class="label label-danger">Hiba történt</span>';?></td>
									
									<td>
										<div class="actions">
											<div class="btn-group">
												<a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown" <?php echo (Session::get('user_role_id') <= 0) ? 'disabled' : '';?>>
													<i class="fa fa-cogs"></i>
												</a>
												<ul class="dropdown-menu pull-right">
													<li>
														<a id="submit_newsletter_<?php echo $value['newsletter_id'];?>" rel="<?php echo $value['newsletter_id'];?>"><i class="fa fa-trash"></i> Hírlevél elküldése</a>
													</li>
													<li>
														<a href="admin/newsletter/edit_newsletter/<?php echo $value['newsletter_id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a>
													</li>
													<li>
														<a class="delete_item" data-id="<?php echo $value['newsletter_id'];?>"><i class="fa fa-trash"></i> Töröl</a>
													</li>
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
	</div><!-- END PAGE CONTAINER-->
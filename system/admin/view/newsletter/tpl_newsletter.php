<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">

	<div class="page-content">
	
		<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
				Hírlevél <small>kezelése</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="admin/home">Kezdőoldal</a> 
						<i class="fa fa-angle-right"></i>
					</li>
					<li><a href="admin/newsletter">Hírlevél</a></li>
				</ul>
			</div>
			<!-- END PAGE TITLE & BREADCRUMB-->
			<!-- END PAGE HEADER-->
		
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
					<?php $this->renderFeedbackMessages(); ?>
								

				<form class="horizontal-form" id="del_newsletter_form" method="POST" action="admin/newsletter/delete_newsletter">	
						
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-user"></i>Hírlevelek</div>
							
								<div class="actions">
									<a href="admin/newsletter/new_newsletter" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új hírlevél</a>
									<button class="btn red btn-sm" name="delete_newsletter" type="submit"><i class="fa fa-trash"></i> Csoportos törlés</button>
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
							
							<table class="table table-striped table-bordered table-hover" id="newsletter_table">
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
										<th></th>
									</tr>
								</thead>
								<tbody>

							<?php foreach($newsletters as $value) { ?>

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
									<td><?php echo $value['newsletter_status'];?></td>
									
									<td>
										<div class="actions">
											<div class="btn-group">
												<a class="btn btn-sm green" href="#" data-toggle="dropdown" <?php echo (Session::get('user_role_id') <= 0) ? 'disabled' : '';?>>
													<i class="fa fa-cogs"></i> Műveletek
													<i class="fa fa-angle-down"></i>
												</a>
												<ul class="dropdown-menu pull-right">
													<li>
														<a id="submit_newsletter_<?php echo $value['newsletter_id'];?>" rel="<?php echo $value['newsletter_id'];?>"><i class="fa fa-trash"></i> Hírlevél elküldése</a>
													</li>
													<li>
														<a href="admin/newsletter/edit_newsletter/<?php echo $value['newsletter_id'];?>"><i class="fa fa-pencil"></i> Szerkeszt</a>
													</li>

													<!--
													<li><a href="<?php //echo $this->registry->site_url . 'newsletter/delete_newsletter/' . $value['newsletter_id'];?>" id="delete_newsletter_<?php //echo $value['newsletter_id'];?>" rel="<?php //echo $value['newsletter_id'];?>"> <i class="fa fa-trash"></i> Töröl</a></li>
													-->
													
													<?php if (empty($value['newsletter_lastsent_date'])) { ?>
													<li>
														<a id="delete_newsletter_<?php echo $value['newsletter_id'];?>" rel="<?php echo $value['newsletter_id'];?>"><i class="fa fa-trash"></i> Töröl</a>
													</li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</td>
									
									<!--
									<td>
										<button type="button" name="submit_newsletter" id="submit_newsletter_<?php //echo $value['newsletter_id'];?>" value="<?php //echo $value['newsletter_id'];?>" class="btn btn-sm default">Hírlevél elküldése</button>
									</td>
									-->
									
								</tr>

							<?php } ?>	
				
								</tbody>
							</table>	
						</div>
					</div>
				</form>	

			</div>
		</div>
	</div>
		<!-- END PAGE CONTAINER-->    
		
		

		
</div>                                                            
<!-- END PAGE CONTENT WRAPPER -->

</div><!-- END CONTAINER -->
<div id="loadingDiv"></div>	
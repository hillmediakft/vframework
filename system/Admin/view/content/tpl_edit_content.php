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
					<a href="admin/content">Tartalmi elemek</a> 
					<i class="fa fa-angle-right"></i>
				</li>
				<li><span>Tartalmi elem szerkesztése</span></li>
			</ul>
		</div>
		<!-- END PAGE TITLE & BREADCRUMB-->
	<!-- END PAGE HEADER-->

	<div class="margin-bottom-20"></div>	

	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">

			<form action="admin/content/update/<?php echo $content['id']; ?>" method="POST" id="update_content_form">

				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
                    
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-files-o"></i> 
                            <?php echo $content['title'];?> szerkesztése
                        </div>
                        <div class="actions">
                            <button class="btn green btn-sm" type="submit"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/content"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

<div class="margin-bottom-20"></div>

					<div class="portlet-body">
													
							<!-- <input type="hidden" name="content_id" id="content_id" value="<?php //echo $content['id'] ?>"> -->
							
							<div class="form-group">
								<label for="name">A tartalmi elem neve</label>	
								<input type="text" name="name" class="form-control input-large" disabled value="<?php echo $content['name'];?>"/>
							</div>
							
							<div class="form-group">
								<label for="title">A tartalmi elem megnevezése</label>	
								<input type='text' name='title' class='form-control input-large' required value="<?php echo $content['title'];?>"/>
							</div>

<div class="margin-bottom-30"></div>
							
							<div class="portlet">
							    <!--<div class="portlet-title"></div>-->
							    <div class="portlet-body">
							        <ul class="nav nav-tabs">
							        <?php foreach ($langs as $key => $lang) { ?>
							            <li class="<?php echo ($key == 0) ? 'active' : ''; ?>">
							                <a href="#tab_1_<?php echo $key+1; ?>" data-toggle="tab"> <?php echo $lang; ?> </a>
							            </li>
							        <?php } ?>
							        </ul>
							        <div class="tab-content">
							            <?php foreach ($langs as $key => $lang) { ?>
							            <div class="tab-pane fade <?php echo ($key == 0) ? 'active in' : ''; ?>" id="tab_1_<?php echo $key+1; ?>">

											<div class="form-group">
												<label for="body_<?php echo $lang; ?>">Tartalom (<?php echo $lang; ?>)</label>
												<textarea type="text" name="body_<?php echo $lang; ?>" class="form-control"><?php echo $content['body_' . $lang] ?></textarea>
											</div>

							            </div>
							            <?php } ?>
							        </div>
							    </div>
							</div>

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->

			</form>									

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
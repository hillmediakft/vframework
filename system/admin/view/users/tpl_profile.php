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
			<li><span>Felhasználói profil</span></li>
		</ul>
	</div>
	<!-- END PAGE TITLE & BREADCRUMB-->
<!-- END PAGE HEADER-->

<div class="margin-bottom-20"></div>

<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			
			<div class="row user-profile">
				<div class="col-md-3">
					<img class="img-responsive" src="<?php echo Config::get('user.upload_path') . $this->data_arr[0]['user_photo']; ?>" alt="">
				</div>
				
				<div class="col-md-4">
				<h3 style="margin-top: 0px;"><?php echo $this->data_arr[0]['user_first_name'] . " " . $this->data_arr[0]['user_last_name'];?></h3>
			
				
				<table class="table table-striped">  
					<tbody>
						<tr><td>Felhasználó név:</td><td><?php echo $this->data_arr[0]['user_name'];?></td></tr>
						<tr><td>Jogosultság:</td><td><?php echo $this->data_arr[0]['role_name'];?></td></tr>
						<tr><td>E-mail:</td><td><?php echo $this->data_arr[0]['user_email'];?></td></tr>
						<tr><td>Telefon:</td><td><?php echo $this->data_arr[0]['user_phone'];?></td></tr>
					</tbody>
				</table>		
				</div>
			</div>
			
			<div class="margin-bottom-20"></div>

			<!-- ÜZENETEK -->
			<div id="ajax_message"></div>
			<?php $this->renderFeedbackMessages(); ?>
																		
			<!-- BEGIN FORM-->			
			<form action="admin/users/profile/<?php echo $this->request->get_params('id'); ?>" method="POST" id="edit_user">

				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-user"></i>
							A felhasználói adatok módosítása
						</div>
						<div class="actions">
							<button class="btn green btn-sm" type="submit" name="submit_edit_user"><i class="fa fa-check"></i> Mentés</button>
							<a class="btn default btn-sm" href="admin/users"><i class="fa fa-close"></i> Mégsem</a>
						</div>
					</div>
				</div> <!-- portlet end -->
			
			<!-- TABS -->
				<div class="tab-pane" id="tab_1_3">
					<div class="row profile-account">
						<div class="col-md-3">
							<ul class="ver-inline-menu tabbable margin-bottom-10">
								<li class="active"><a data-toggle="tab" href="#tab_1_1"><i class="fa fa-cog"></i>Személyes adatok</a><span class="after"></span></li>
								<li ><a data-toggle="tab" href="#tab_2_2"><i class="fa fa-picture-o"></i> Profil kép</a></li>
								<li ><a data-toggle="tab" href="#tab_3_3"><i class="fa fa-lock"></i> Jelszó</a></li>
								<?php if(Acl::check('update_user_perm')) { ?>
								<li ><a data-toggle="tab" href="#tab_4_4"><i class="fa fa-wrench"></i> Jogosultságok</a></li>
								<?php } ?>
							</ul>
						</div>
						
						<div class="col-md-9">
							<div class="tab-content">

<!-- ****************************** SZEMÉLYES ADATOK ***************************** -->									
								<div id="tab_1_1" class="tab-pane active">
									
									<h3>Személyes adatok</h3>

									<div class="form-group">
										<label for="name" class="control-label">Felhasználó név<span class="required">*</span></label>
										<input type="text" name="name" id="name" value="<?php echo $this->data_arr[0]['user_name'];?>" placeholder="minimum hat karakter, ékezetek nélkül" class="form-control input-xlarge" />
									</div>
									<div class="form-group">
										<label for="last_name" class="control-label">Vezetéknév<span class="required">*</span></label>
										<input type="text" name="first_name" id="last_name" value="<?php echo $this->data_arr[0]['user_first_name'];?>" placeholder="" class="form-control input-xlarge" />
									</div>
									<div class="form-group">
										<label for="first_name" class="control-label">Keresztnév<span class="required">*</span></label>
										<input type="text" name="last_name" id="first_name" value="<?php echo $this->data_arr[0]['user_last_name'];?>" placeholder="" class="form-control input-xlarge" />
									</div>
									<div class="form-group">
										<label class="control-label">Telefonszám</label>
										<input type="text" name="phone" value="<?php echo $this->data_arr[0]['user_phone'];?>" placeholder="országkód-körzetszám-xxx-xxx formátumban" class="form-control input-xlarge" />
									</div>
									<div class="form-group">
										<label for="email" class="control-label">E-mail cím<span class="required">*</span></label>
										<input type="text" value="<?php echo $this->data_arr[0]['user_email'];?>" placeholder="" name="email" id="email" class="form-control input-xlarge" />
									</div>

								</div>

<!-- ****************************** PROFIL KÉP FELTÖLTÉSE ***************************** -->									
								<div id="tab_2_2" class="tab-pane">
									
									<h3>Profilkép feltöltése</h3>
										
									<div id="user_image"></div>	
									<input type="hidden" name="img_url" id="OutputId" >			
									<input type="hidden" id="old_img"  value="<?php echo Config::get('user.upload_path') . $this->data_arr[0]['user_photo'];?>" name="old_img">
									
									<div class="clearfix"></div>

									<div class="margin-bottom-10"></div>

									<div class="note note-info">
										Kép feltöltéséhez kattintson a képmező jobb felső sarkában lévő ikonra! A kiválasztott kép méreteit a + - ikonnal változtathatja meg. Ha másik képet szeretne kiválasztani, kattintson a piros x ikonra! A kép elmentéséhez klikkeljen a zöld vágó ikonra.
									</div>
							
								</div>
								
<!-- ****************************** JELSZÓ MEGADÁSA ***************************** -->									
								<div id="tab_3_3" class="tab-pane">
									
									<h3>Jelszó megadása</h3>
										
									<div class="form-group">
										<label for="password" class="control-label">Jelszó<span class="required">*</span></label>
										<input type="password" id="password" name="password" class="form-control input-xlarge"/>
									</div>	
									<div class="form-group">
										<label for="password_again" class="control-label">Jelszó ismétlése<span class="required">*</span></label>
										<input type="password" name="password_again" class="form-control input-xlarge" />
									</div>
							
								</div>
								
<!-- ****************************** JOGOSULTSÁGOK ***************************** -->										
								
                            <?php if(Acl::check('update_user_perm')) { ?>
								<div id="tab_4_4" class="tab-pane">
								
									<h3>Felhasználói jogosultság</h3>

									<div class="note note-info">
										A felhasználói jogosultság (felhasználói csoport) megadásával beállítható, hogy a felhasználó mihez férhet hozzá, milyen műveleteket hajthat végre.
									</div>
																		
									<div class="form-group">
										<label><strong>Felhasználói csoportok</strong></label>
										<div class="radio-list">
											<label>
											<input type="radio" name="user_group" value="1" <?php echo ($this->data_arr[0]['user_role_id'] == 1) ? 'checked' : '';?> />
											Szuper adminisztrátor - teljes jogkör
											</label>
											<label>
											<input type="radio" name="user_group" value="2" <?php echo ($this->data_arr[0]['user_role_id'] == 2) ? 'checked' : '';?> />
											Adminisztrátor - széles körű jogkör
											</label>  
								<!--			<label>
											<input type="radio" name="user_group" value="3" <?php //echo ($this->data_arr[0]['user_role_id'] == 3) ? 'checked' : '';?> />
											Regisztrált felhasználó - korlátozott jogkör
											</label>    -->
										</div>
									</div>
								</div>
						    <?php } ?>		
								
								
							</div> <!--END TAB-CONTENT-->
						</div> <!--END COL-MD-9--> 
					</div> <!--END ROW PROFILE-ACCOUNT-->
				</div> <!--END TAB-PANE-->
			</form>

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
	
</div> <!-- END PAGE CONTAINER-->
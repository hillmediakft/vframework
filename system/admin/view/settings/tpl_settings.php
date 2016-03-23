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
			<li><span>Beállítások</span></li>
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

            <form action='' name='settings_form' id='settings_form' method='POST'>
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-cogs"></i>Beállítások szerkesztése</div>
	                    <div class="actions">
                            <button class='btn green btn-sm' type='submit' name='submit_settings'><i class="fa fa-check"></i> Mentés</button>
                        </div>							
					</div>
					<div class="portlet-body">

                        <div class="form-group">
                            <label for="settings_ceg">Cég</label>	
                            <input type='text' name='setting_ceg' class='form-control input-large' value="<?php echo (empty($this->settings['ceg'])) ? "" : $this->settings['ceg']; ?>"/>
                        </div>

                        <div class="form-group">
                            <label for="settings_cim">Cím</label>	
                            <input type='text' name='setting_cim' class='form-control input-large' value="<?php echo (empty($this->settings['cim'])) ? "" : $this->settings['cim']; ?>"/>
                        </div>

                        <div class="form-group">
                            <label for="settings_tel">Telefonszám</label>	
                            <input type='text' name='setting_tel' class='form-control input-large' value="<?php echo (empty($this->settings['tel'])) ? "" : $this->settings['tel']; ?>"/>
                        </div>

                        <div class="form-group">
                            <label for="settings_email">E-mail (lábléc e-mail űrlap)</label>	
                            <input type='text' name='setting_email' class='form-control input-large' value="<?php echo (empty($this->settings['email'])) ? "" : $this->settings['email']; ?>"/>
                        </div>
                        
                    <!--
                        <div class="form-group">
                            <label for="setting_email_ceges">E-mail céges (céges kapcsolat)</label>	
                            <input type='text' name='setting_email_ceges' class='form-control input-large' value="<?php //echo (empty($this->settings['email_ceges'])) ? "" : $this->settings['email_ceges']; ?>"/>
                        </div>
                           
                        <div class="form-group">
                            <label for="setting_email_diak">E-mail diák</label>	
                            <input type='text' name='setting_email_diak' class='form-control input-large' value="<?php //echo (empty($this->settings['email_diak'])) ? "" : $this->settings['email_diak']; ?>"/>
                        </div>
                                                    
                        <div class="form-group">
                            <label for="setting_email_kilepes">E-mail kilépés</label>	
                            <input type='text' name='setting_email_kilepes' class='form-control input-large' value="<?php //echo (empty($this->settings['email_kilepes'])) ? "" : $this->settings['email_kilepes']; ?>"/>
                        </div>                                                            

                        <div class="form-group">
                            <label for="setting_email_diak">Facebook link</label>	
                            <input type='text' name='setting_facebook_link' class='form-control input-large' value="<?php //echo (empty($this->settings['facebook_link'])) ? "" : $this->settings['facebook_link']; ?>"/>
                        </div>
                    -->
						
					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
            </form>
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
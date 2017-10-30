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

            <form action='' name='settings_form' id='form' method='POST' class="form-horizontal">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-cogs"></i>Beállítások szerkesztése</div>
                        <div class="actions">
                            <button class='btn green btn-sm' type='submit' name='submit_settings'><i class="fa fa-check"></i> Mentés</button>
                        </div>							
                    </div>
                    <div class="portlet-body">



                        <div class="portlet light bg-inverse">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-green-haze bold uppercase">Elérhetőségek</span>
                                </div>
                            </div>
                            <div class="portlet-body form">

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cég/üzlet/iroda elnevezése</label>
                                    <div class="col-md-4">
                                        <div class="input-group">	
                                            <input type='text' name='ceg' class='form-control' value="<?php echo (empty($settings['ceg'])) ? "" : $settings['ceg']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-home fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cím</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='cim' class='form-control' value="<?php echo (empty($settings['cim'])) ? "" : $settings['cim']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-map-marker fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-3 control-label">Telefonszám 1</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='mobil' class='form-control' value="<?php echo (empty($settings['mobil'])) ? "" : $settings['mobil']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-mobile fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>                        


                                <div class="form-group">
                                    <label class="col-md-3 control-label">Telefonszám 2</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='tel' class='form-control' value="<?php echo (empty($settings['tel'])) ? "" : $settings['tel']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-phone fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>  


                                <div class="form-group">
                                    <label class="col-md-3 control-label">E-mail (lábléc e-mail űrlap)</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='email' class='form-control' value="<?php echo (empty($settings['email'])) ? "" : $settings['email']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="portlet light bg-inverse">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-green-haze bold uppercase">Közösségi média</span>
                                </div>
                            </div>
                            <div class="portlet-body form">                       

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Facebook fiók url-je</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='facebook' class='form-control' value="<?php echo (empty($settings['facebook'])) ? "" : $settings['facebook']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-facebook fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Google plus fiók url-je</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='googleplus' class='form-control' value="<?php echo (empty($settings['googleplus'])) ? "" : $settings['googleplus']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-google-plus fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>   

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Twitter fiók url-je</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='twitter' class='form-control' value="<?php echo (empty($settings['twitter'])) ? "" : $settings['twitter']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-twitter fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Linkedin fiók url-je</label>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type='text' name='linkedin' class='form-control' value="<?php echo (empty($settings['linkedin'])) ? "" : $settings['linkedin']; ?>"/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-linkedin fa-fw"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>                                 

                            </div>
                        </div>


                        <div class="portlet light bg-inverse">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject font-green-haze bold uppercase">Megjelenített utak száma oldalanként</span>
                                </div>
                            </div>
                            <div class="portlet-body form">                       

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Elemek száma</label>
                                    <div class="col-md-4">
                                        <select name="pagination" class="form-control">
                                            <?php
                                            for ($i = 6; $i < 22; $i += 3) {
                                                echo '<option value="' . $i . '"';
                                                echo ($settings['pagination'] == $i) ? 'selected' : '';
                                                echo '>' . $i . '</option>' . "\r\n";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>                                 

                            </div>
                        </div>                        



                    </div> <!-- END USER GROUPS PORTLET BODY-->
                </div> <!-- END USER GROUPS PORTLET-->
            </form>
        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
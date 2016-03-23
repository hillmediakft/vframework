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
                <a href="admin/slider">Slider lista</a> 
                <i class="fa fa-angle-right"></i>
            </li>
            <li><span>Slide szerkesztése</span></li>
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

            <form action="" method="POST" id="slider_update_form" enctype="multipart/form-data">	

                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i> 
                            Slide szerkesztése
                        </div>
                        <div class="actions">
                            <button class="btn green btn-sm" type="submit" name="submit_update_slide"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/slider"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

                    <div class="margin-bottom-20"></div>

                    <div class="portlet-body">

                        <div class="row">	
                            <div class="col-md-12">						

                                <!-- bootstrap file upload -->
                                <div class="form-group">
                                    <label class="control-label">Slide kép</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 585px"><img src="<?php echo Config::get('slider.upload_path') . $this->slider['picture']; ?>" alt=""/></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 585px; max-height: 210px; line-height: 20px;"></div>
                                        <div>
                                            <span class="btn default btn-file"><span class="fileupload-new">Kiválasztás</span><span class="fileupload-exists">Módosít</span><input id="uploadprofile" class="img" type="file" name="update_slide_picture"></span>
                                            <a href="#" class="btn btn-warning fileupload-exists" data-dismiss="fileupload">Töröl</a>
                                        </div>
                                    </div>

                                </div>
                                <!-- bootstrap file upload END -->

                                <div class="clearfix"></div>
                                <div class="note note-info">
                                    Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a megjelenő módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!
                                </div>

                                <div class="form-group">
                                    <label for="slider_title" class="control-label">Slide cím</label>
                                    <input type="text" name="slider_title" id="slider_title" placeholder="A slide címe" class="form-control input-xlarge" value="<?php echo $this->slider['title']; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label for="slider_text" class="control-label">Slide szöveg</label>
                                    <input type="text" name="slider_text" id="slider_text" placeholder="A slide szövege" class="form-control input-xlarge" value="<?php echo $this->slider['text']; ?>"/>
                                </div>

                                <div class="form-group">
                                    <label for="slider_link" class="control-label">Slide link</label>
                                    <input type="text" name="slider_link" id="slider_link" placeholder="A slide linkje" class="form-control input-xlarge" value="<?php echo $this->slider['target_url']; ?>"/>
                                </div>											

                                <!--Státusz beállítása-->
                                <div class="form-group">
                                    <label for="slider_status">Slide státusz</label>
                                    <select name='slider_status' class="form-control input-xlarge">
                                        <option value="1" <?php echo ($this->slider['active'] == 1) ? 'selected' : ''; ?>>Aktív</option>
                                        <option value="0" <?php echo ($this->slider['active'] == 0) ? 'selected' : ''; ?>>Inaktív</option>
                                    </select>
                                </div>

                                <!-- régi kép elérési útja-->
                                <input type="hidden" name="old_img" id="old_img" value="<?php echo $this->slider['picture']; ?>">				

                            </div>
                        </div>	


                    </div> <!-- END USER GROUPS PORTLET BODY-->
                </div> <!-- END USER GROUPS PORTLET-->

            </form>

        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
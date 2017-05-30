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
                                <?php 
                                    $placeholder = $this->getConfig('slider.upload_path') . $slider['picture'];
                                    echo $this->html_admin_helper->photoUpload(array(
                                        //'label' => 'Kép',
                                        'width' => 585,
                                        'height' => 210,
                                        'placeholder' => $placeholder,
                                        'input_name' => 'upload_slider_picture',
                                        // 'info_content' => 'Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a megjelenő módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!'
                                        ));
                                ?>
                                <!-- bootstrap file upload END -->

                                <!--Státusz beállítása-->
                                <div class="form-group">
                                    <label for="status">Slide státusz</label>
                                    <select name="status" class="form-control input-xlarge">
                                        <option value="1" <?php echo ($slider['status'] == 1) ? 'selected' : ''; ?>>Aktív</option>
                                        <option value="0" <?php echo ($slider['status'] == 0) ? 'selected' : ''; ?>>Inaktív</option>
                                    </select>
                                </div>

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
                                                    <label for="title_<?php echo $lang; ?>" class="control-label">Cím / <?php echo $lang; ?></label>
                                                    <input type="text" name="title_<?php echo $lang; ?>" id="title_<?php echo $lang; ?>" placeholder="" class="form-control input-xlarge" value="<?php echo (isset($slider['title_' . $lang])) ? $slider['title_' . $lang] : ''; ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="target_url_<?php echo $lang; ?>" class="control-label">Link / <?php echo $lang; ?></label>
                                                    <input type="text" name="target_url_<?php echo $lang; ?>" id="target_url_<?php echo $lang; ?>" placeholder="" class="form-control input-xlarge" value="<?php echo (isset($slider['target_url_' . $lang])) ? $slider['target_url_' . $lang] : ''; ?>"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="text_<?php echo $lang; ?>" class="control-label">Szöveg / <?php echo $lang; ?></label>
                                                    <textarea name="text_<?php echo $lang; ?>" id="text_<?php echo $lang; ?>" placeholder="" class="form-control input-xlarge"><?php echo (isset($slider['text_' . $lang])) ? $slider['text_' . $lang] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- régi kép elérési útja-->
                                <input type="hidden" name="old_img" id="old_img" value="<?php echo $slider['picture']; ?>">				

                            </div>
                        </div>	


                    </div> <!-- END USER GROUPS PORTLET BODY-->
                </div> <!-- END USER GROUPS PORTLET-->

            </form>

        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
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
                <a href="admin/gyik">GYIK listája</a> 
                <i class="fa fa-angle-right"></i>
            </li>
            <li><span>GYIK hozzáadása</span></li>
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

                <form action="admin/gyik/store" method="POST" id="new_gyik" enctype="multipart/form-data">	

                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet">

                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i>
                                Gyakori kérdés hozzáadása
                            </div>
                            <div class="actions">
                                <button class="btn green btn-sm" type="submit"><i class="fa fa-check"></i> Mentés</button>
                                <a class="btn default btn-sm" href="admin/gyik"><i class="fa fa-close"></i> Mégsem</a>
                                <!-- <button class="btn default btn-sm" name="cancel" type="button"><i class="fa fa-close"></i> Mégsem</button>-->
                            </div>
                        </div>
                        <div class="portlet-body">

                            <div class="margin-bottom-20"></div>

                            <div class="row">	
                                <div class="col-md-12">

                                    <!-- ÜZENET DOBOZOK -->
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        <span><!-- ide jön az üzenet--></span>
                                    </div>
                                    <div class="alert alert-success display-hide">
                                        <button class="close" data-close="alert"></button>
                                        <span><!-- ide jön az üzenet--></span>
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

                                                    <!-- KÉRDÉS --> 
                                                    <div class="form-group">
                                                        <label for="title_<?php echo $lang; ?>" class="control-label">Kérdés (<?php echo $lang; ?>)<?php echo ($key == 0) ? '<span class="required">*</span>' : ''; ?></label>
                                                        <input type="text" name="title_<?php echo $lang; ?>" id="title" placeholder="" class="form-control" />
                                                    </div>

                                                    <!-- VÁLASZ --> 
                                                    <div class="form-group">
                                                        <label for="description_<?php echo $lang; ?>" class="control-label">Válasz (<?php echo $lang; ?>)</label>
                                                        <textarea name="description_<?php echo $lang; ?>" id="description" placeholder="" class="form-control input-xlarge" rows="10"></textarea>
                                                    </div>

                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- KATEGÓRIA -->	
                                    <div class="form-group">
                                        <label for="gyik_category" class="control-label">Kategória <span class="required">*</span></label>
                                        <select name="gyik_category" class="form-control input-xlarge">
                                            <option value="">Válasszon</option>
                                            <?php foreach ($category_list as $category) { ?>
                                                <option value="<?php echo $category['id']; ?>">
                                                    <?php echo $category['category_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                        
                                    <!-- CÍMKÉK -->	
                                    <!-- 
                                    <h3 class="form-section">Címkék</h3>                                            
                                    <div class="form-group">
                                        <select name="tags[]" id="tags" class="form-control input-xlarge select2-multiple" multiple>
                                            <?php //foreach ($terms as $term) { ?>
                                                <option value="<?php //echo $term['id']; ?>"><?php //echo $term['term']; ?></option>
                                            <?php //} ?>
                                        </select>
                                    </div>                                      
                                     -->

                                    <!-- STÁTUSZ -->	
                                    <div class="form-group">
                                        <label for="status" class="control-label">Státusz</label>
                                        <select name="status" class="form-control input-xlarge">
                                            <option value="0">Inaktív</option>
                                            <option value="1" selected>Aktív</option>
                                        </select>
                                    </div>	
                                </div>
                            </div>

                        </div> <!-- END PORTLET BODY-->
                    </div> <!-- END PORTLET-->
                </form>

        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
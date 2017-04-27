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
                <a href="admin/documents">Dokumentumok</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li><a href="admin/documents/insert">Dokumentum hozzáadása</a></li>
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
            <div id="ajax_message"></div> 

            <form action="" method="POST" id="upload_document_form" enctype="multipart/form-data">	

                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i> 
                            Dokumentum feltöltése
                        </div>
                        <div class="actions">
                            <!-- Adatok "első" elküldése INSERT -->
                            <button class="btn green" id="data_upload_ajax" type="button" name="save_data">Mentés és folytatás <i class="fa fa-check"></i></button>
                            <!-- Adatok elküldése UPDATE és kilépés -->
                            <button disabled style="display:none;" class="btn blue" id="data_update_ajax" type="button" name="update_data">Mentés és kilépés <i class="fa fa-check"></i></button>
                            <a class="btn default btn-sm" id="button_megsem" href="admin/documents"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

                    <div class="portlet-body">

                        <div class="row">	
                            <div class="col-md-12">						

                                <!-- bootstrap file upload -->
                                <div class="form-group">
                                    <label class="control-label">Válassza ki a feltöltendő fájlt</label>



                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="portlet">
                                                <div class="portlet-body">
                                                    <h4 class="block">Feltöltött dokumentumok:</h4>
                                                    <ul id="dokumentumok" class="list-group">

                                                    </ul>											
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <!-- DOKUMENTUMOK FELTÖLTÉSE -->
                                            <div class="portlet">
                                                <div class="portlet-body">
                                                    <h4 class="block">Dokumentumok hozzáadása:</h4>
                                                    <input type="file" name="new_doc[]" multiple="true" id="input-5" />
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- row END -->	                                 








                                    <!--            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div>
                                                        <span class="btn default btn-file"><span class="fileupload-new">Fájl kiválasztása</span><span class="fileupload-exists">Módosít</span><input id="uploadprofile" class="img" type="file" name="upload_document"></span>
                                                        <a href="#" class="btn btn-warning fileupload-exists" data-dismiss="fileupload">Töröl</a>
                                                    </div>
                                                </div>
                                            </div>  -->
                                    <!-- bootstrap file upload END -->

                                    <div class="clearfix"></div>

                                    <div class="note note-info">
                                        Kattintson a kiválasztás gombra! Ha másik képet szeretne kiválasztani, kattintson a módosít gombra! Ha mégsem kívánja a kiválasztott képet feltölteni, kattintson a töröl gombra!
                                    </div>

                                    <div class="form-group">
                                        <label for="title" class="control-label">Cím</label>
                                        <input type="text" name="title" id="title" placeholder="" class="form-control input-xlarge" />
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="control-label">Rövid leírás</label>
                                        <textarea name="description" id="description" placeholder="" class="form-control input-xlarge"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="category_id">Kategória</label>
                                        <select name="category_id" class="form-control input-xlarge">
                                            <option value="0">-- válasszon --</option>
                                            <?php foreach ($category_list as $value) { ?>
                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>
                            </div>	

                        </div> <!-- END USER GROUPS PORTLET BODY-->
                    </div> <!-- END USER GROUPS PORTLET-->

            </form>

        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
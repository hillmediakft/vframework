<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
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
                    <span>Fordítások</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE TITLE & BREADCRUMB-->
        <!-- END PAGE HEADER-->

        <div class="margin-bottom-20"></div>

        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-globe"></i>Fordítások szerkesztése</div>
                    </div>
                    <div class="portlet-body">

                        <div class="alert alert-info"><i class="fa fa-info-circle"></i> Szerkesztéshez kattintson a kék színű, szaggatott vonallal aláhúzott szövegekre! </div> 

                        <div class="row">
                            <div class="col-md-12">
                                <table id="user" class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="heading">
                                            <th>Kód</th>
                                            <th>Magyar</th>
                                            <th>Angol</th>
                                        </tr>   
                                    <tbody>
                                        <?php foreach ($translations as $key1 => $value1) { ?>
                                            <tr class="warning">
                                                <td colspan="3">
                                                    Nyelvi kód kategória:  <?php echo '<strong>' . $key1 . '</strong>'; ?>
                                                </td>
                                            <tr>
                                                <?php foreach ($value1 as $value) { ?>			
                                                <tr>
                                                    <td style="width:15%"><?php echo $value['code']; ?></td>

                                                    <td style="width:25%">
                                                        <?php if ($value['editor'] == '0') { ?>                                                    
                                                            <a href="admin/translations#" 
                                                               class="xedit" 
                                                               id="<?php echo $value['code']; ?>_hu" 
                                                               data-type="textarea" 
                                                               data-pk="<?php echo $value['id']; ?>" 
                                                               data-title="Írja be a szöveget"><?php echo $value['hu']; ?></a>
                                                           <?php } ?>                                               

                                                        <?php if ($value['editor'] == '1') { ?>                                                    
                                                            <a href="#" id="pencil_<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> [szerkeszt] </a>
                                                            <div 
                                                                class="xedit"
                                                                id="<?php echo $value['code']; ?>_hu" 
                                                                data-pk="<?php echo $value['id']; ?>" 
                                                                data-type="wysihtml5" 
                                                                data-toggle="manual" 
                                                                data-original-title="Írja be a szöveget"><?php echo $value['hu']; ?></div>
                                                            <?php } ?>                                           
                                                    </td>

                                                    <td style="width:25%">
                                                        <?php if ($value['editor'] == '0') { ?>                                                    
                                                            <a href="admin/translations#" 
                                                               class="xedit" 
                                                               id="<?php echo $value['code']; ?>_en" 
                                                               data-type="textarea" 
                                                               data-pk="<?php echo $value['id']; ?>" 
                                                               data-title="Írja be a szöveget"><?php echo $value['en']; ?></a>
                                                           <?php } ?>                                               

                                                        <?php if ($value['editor'] == '1') { ?>                                                    
                                                            <a href="#" id="pencil_<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> [szerkeszt] </a>
                                                            <div 
                                                                class="xedit"
                                                                id="<?php echo $value['code']; ?>_en" 
                                                                data-pk="<?php echo $value['id']; ?>" 
                                                                data-type="wysihtml5" 
                                                                data-toggle="manual" 
                                                                data-original-title="Írja be a szöveget"><?php echo $value['en']; ?></div>
                                                            <?php } ?>                                           
                                                    </td>                                                       
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- END USER GROUPS PORTLET BODY-->
                </div> <!-- END USER GROUPS PORTLET-->
            </div> <!-- END COL-MD-12 -->
        </div> <!-- END ROW -->	
    </div> <!-- END PAGE CONTENT-->    
</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
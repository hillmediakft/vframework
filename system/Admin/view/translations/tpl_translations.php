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
                                        <?php foreach ($langs as $lang) { ?>
                                            <th><?php echo $lang; ?></th>
                                        <?php } ?>
                                    </tr>   
                                <tbody>
                                    <?php foreach ($translations as $category => $item_arr) { ?>
                                        <tr class="warning">
                                            <td colspan="3">
                                                Nyelvi kód kategória:  <?php echo '<strong>' . $category . '</strong>'; ?>
                                            </td>
                                        </tr>
                                            <?php foreach ($item_arr as $value) { ?>            
                                            <tr>
                                                <td style="width:15%"><?php echo $value['code']; ?></td>

                                                <!-- A különbözö nyelvi verziójú elemek létrehozása -->
                                                <?php foreach ($langs as $lang) { ?>			
                                                <td style="width:25%">
                                                    <?php if ($value['editor'] == '0') { ?>                                                    
                                                        <a href="admin/translations#" 
                                                           class="xedit" 
                                                           id="<?php echo $value['code'] . '_' . $lang; ?>" 
                                                           data-type="textarea" 
                                                           data-pk="<?php echo $value['id']; ?>" 
                                                           data-title="Írja be a szöveget"><?php echo $value['text_' . $lang]; ?></a>
                                                    <?php } ?>                                               

                                                    <?php if ($value['editor'] == '1') { ?>                                                    
                                                        <a href="#" id="pencil_<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> [szerkeszt] </a>
                                                        <div 
                                                            class="xedit"
                                                            id="<?php echo $value['code'] . '_' . $lang; ?>" 
                                                            data-pk="<?php echo $value['id']; ?>" 
                                                            data-type="wysihtml5" 
                                                            data-toggle="manual" 
                                                            data-original-title="Írja be a szöveget"><?php echo $value['text_' . $lang]; ?></div>
                                                    <?php } ?>                                           
                                                </td>
                                                <?php } ?> 

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
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
            <li><span>GYIK</span></li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->
    
<div class="margin-bottom-20"></div>

        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">

                <!-- RÉSZLETEK MEGJELENÍTÉSE MODAL -->	
                <div class="modal" id="ajax_modal" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" id="modal_container"></div>
                    </div>
                </div>	
                <!-- RÉSZLETEK MEGJELENÍTÉSE MODAL END -->	

                <div id="ajax_message"></div> 						
                <!-- echo out the system feedback (error and success messages) -->
                <?php $this->renderFeedbackMessages(); ?>				

                <form class="horizontal-form" id="del_gyik_form" method="POST" action="admin/gyik/delete_gyik">	

                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption"><i class="fa fa-shopping-cart"></i>Gyakori kérdések listája</div>
                            <div class="actions">
                                <a href="admin/gyik/new_gyik" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új GYIK</a>
                                <button class="btn red btn-sm" name="delete_gyik_submit" value="submit" type="submit"><i class="fa fa-trash"></i> Csoportos törlés</button>

                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- *************************** JOBS TÁBLA *********************************** -->						
                            <table class="table table-striped table-bordered table-hover" id="gyik">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            <input type="checkbox" class="group-checkable" data-set="#gyik .checkboxes"/>
                                        </th>
                                        <th>Kérdés</th>
                                        <th>Válasz</th>
                                        <th>Kategória</th>
                                        <th>Létrehozva</th>
                                        <th style="width:1%;">Státusz</th>
                                        <th style="width:1%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->all_gyik as $value) { ?>
                                        <tr class="odd gradeX">
                                            <td>
                                                <?php if (Session::get('user_role_id') < 3) : ?>
                                                    <input type="checkbox" class="checkboxes" name="gyik_id_<?php echo $value['gyik_id']; ?>" value="<?php echo $value['gyik_id']; ?>"/>
                                                <?php endif; ?>	
                                            </td>
                                            <td><?php echo $value['gyik_title']; ?></td>
                                            <td><?php echo $value['gyik_description']; ?></td>

                                            <td><?php echo $value['gyik_category_name']; ?></td>
                                            <td><?php echo date('Y-m-d H:i', $value['gyik_create_timestamp']); ?></td>
                                            <?php if ($value['gyik_status'] == 1) { ?>
                                                <td><span class="label label-sm label-success">Aktív</span></td>
                                            <?php } ?>
                                            <?php if ($value['gyik_status'] == 0) { ?>
                                                <td><span class="label label-sm label-danger">Inaktív</span></td>
                                            <?php } ?>
                                            <?php if ($value['gyik_status'] == 2) { ?>
                                                <td><span class="label label-sm label-success">Kiemelt</span></td>
                                            <?php } ?>                                                
                                            <td>									
                                                <div class="actions">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown" <?php echo ((Session::get('user_role_id') >= 2)) ? 'disabled' : ''; ?>>
                                                            <i class="fa fa-cogs"></i> 

                                                        </a>
                                                        <ul class="dropdown-menu pull-right">
                                                            <?php if ((Session::get('user_role_id') < 3)) { ?>	
                                                                <li><a href="<?php echo 'admin/gyik/update_gyik/' . $value['gyik_id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                            <?php }; ?>

                                                            <?php if ((Session::get('user_role_id') < 3)) { ?>	
                                                                <li><a href="<?php echo 'admin/gyik/delete_gyik/' . $value['gyik_id']; ?>" id="delete_gyik_<?php echo $value['gyik_id']; ?>"> <i class="fa fa-trash"></i> Töröl</a></li>
                                                            <?php }; ?>

                                                            <?php if ((Session::get('user_role_id') < 3)) { ?>		
                                                                <?php if ($value['gyik_status'] == 1) { ?>
                                                                    <li><a rel="<?php echo $value['gyik_id']; ?>" href="admin/gyik/change_status" id="make_inactive_<?php echo $value['gyik_id']; ?>" data-action="make_inactive"><i class="fa fa-ban"></i> Blokkol</a></li>
                                                                <?php } ?>
                                                                <?php if ($value['gyik_status'] == 0) { ?>
                                                                    <li><a rel="<?php echo $value['gyik_id']; ?>" href="admin/gyik/change_status" id="make_active_<?php echo $value['gyik_id']; ?>" data-action="make_active"><i class="fa fa-check"></i> Aktivál</a></li>
                                                                <?php } ?>
                                                            <?php }; ?>	
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>	
                                </tbody>
                            </table>	
                        </div> <!-- END PORTLET BODY -->
                    </div> <!-- END PORTLET -->

                </form>					

            </div>
        </div>
    </div>
    <!-- END PAGE CONTAINER-->    
</div>                                                            
<!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END CONTAINER -->
<div id="loadingDiv" style="display:none;"></div>	
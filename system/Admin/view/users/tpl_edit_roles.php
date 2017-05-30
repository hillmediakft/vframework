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
            <li><a href="#">Felhasználói jogosultságok</a></li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->

    <div class="margin-bottom-20"></div>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            
            <!-- echo out the system feedback (error and success messages) -->
            <?php $this->renderFeedbackMessages(); ?>
 
            <form action="admin/user/edit_roles/<?Php echo $role['id']; ?>" method="POST" id="edit_roles">				
            
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-cog"></i>Jogosultságok szerkesztése</div>
                        <div class="actions">
                            <button class="btn green submit" type="submit" name="submit_edit_roles"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/user/user_roles"><i class="fa fa-close"></i> Mégsem</a>
                        </div>

                    </div>

                    <div class="margin-bottom-20"></div>
                    
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            Felhasználói csoport: <span class="label label-info"><?php echo $role['role']; ?></span>
                        </div>

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="info">
                                    <th style="width:1%;">Engedély</th>
                                    <th>Jogosultság leírás</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permissions as $category => $permission_item) { ?>
                                <tr class="warning">
                                    <td colspan="3">
                                        Kategória:  <?php echo '<strong>' . $category . '</strong>'; ?>
                                    </td>
                                </tr>
                                <?php foreach ($permission_item as $key => $value) {
                                    $selected = (in_array($value['key'], $allowed_permissions));
                                ?>
                                    <tr class="odd gradeX">
                                        <td>
                                            <div class="form-group">
                                                <!-- ha nem jelöljük be a checkboxot a hidden mező értékét küld el -->
                                                <input type="hidden" name="<?php echo $value['id']; ?>" value="0" />
                                                <input type="checkbox" name="<?php echo $value['id']; ?>" value="1" <?php echo ($selected) ? 'checked' : '';?> />
                                            </div>
                                        </td>
                                        <td><?php echo $value['desc']; ?></td>
                                    </tr>

                                <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div> <!-- END PORTLET BODY-->
                </div> <!-- END PORTLET-->
        
            </form>

        </div>
    </div>

</div> <!-- END PAGE CONTAINER-->
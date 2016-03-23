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
 
            <form action="admin/users/edit_roles/<?Php echo $this->role['role_id']; ?>" method="POST" id="edit_roles">				
            
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-cog"></i>Jogosultságok szerkesztése</div>
                        <div class="actions">
                            <button class="btn green submit" type="submit" name="submit_edit_roles"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/users/user_roles"><i class="fa fa-close"></i> Mégsem</a>
                        </div>

                    </div>

                    <div class="margin-bottom-20"></div>
                    
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            Felhasználói csoport: <span class="label label-info"><?php echo $this->role['role_name']; ?></span>
                        </div>

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="info">
                                    <th>Jogosultság</th>
                                    <th>Leírás</th>
                                    <th>Engedélyezés</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->role_permissions as $value) { ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $value['perm_key']; ?></td>
                                    <td><?php echo $value['perm_desc']; ?></td>
                                    <td>
                                        <div class="form-group">
                                            <select name="<?php echo $value['perm_key']; ?>" class="form-control small" <?php echo($value['perm_key'] == 'menu_home') ? 'disabled' : '';?>>
                                                <option value="0" <?php echo(!$value['bool']) ? 'selected' : '';?>>Tiltott</option>
                                                <option value="1" <?php echo($value['bool']) ? 'selected' : '';?>>Engedélyezett</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div> <!-- END PORTLET BODY-->
                </div> <!-- END PORTLET-->
        
            </form>

        </div>
    </div>

</div> <!-- END PAGE CONTAINER-->
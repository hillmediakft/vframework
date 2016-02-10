<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <!-- 
        <h3 class="page-title">
                Felhasználók <small>kezelése</small>
        </h3>
        -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="admin/home">Kezdőoldal</a> 
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <span>Felhasználók</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE TITLE & BREADCRUMB-->
        <!-- END PAGE HEADER-->

        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->

                <!-- ÜZENETEK -->
                <div id="ajax_message"></div> 						
                <?php $this->renderFeedbackMessages(); ?>				

                <form class="horizontal-form" id="del_user_form" method="POST" action="admin/users/delete_user">	

                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption"><i class="fa fa-user"></i>Felhasználók</div>

                            <div class="actions">
                                <?php
                                $loggedin_user_role = Session::get('user_role_id');
                                $loggedin_user_id = Session::get('user_id');

                                if ($loggedin_user_role == 1) {
                                    ?>
                                    <a href="admin/users/new_user" class="btn blue-steel btn-sm"><i class="fa fa-plus"></i> Új felhasználó</a>
<?php } ?>
                                <button class="btn red btn-sm" id="del_user_group" type="button"><i class="fa fa-trash"></i> Csoportos törlés</button>
                                <div class="btn-group">
                                    <a data-toggle="dropdown" class="btn btn-sm default">
                                        <i class="fa fa-wrench"></i> Eszközök <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a id="print_users"><i class="fa fa-print"></i> Nyomtat </a>
                                        </li>
                                        <li>
                                            <a id="export_users"><i class="fa fa-file-excel-o"></i> Export CSV </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="portlet-body">

                            <!-- *************************** USERS TÁBLA *********************************** -->						

                            <table class="table table-striped table-bordered table-hover" id="users">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            <input type="checkbox" class="group-checkable" data-set="#users .checkboxes"/>
                                        </th>
                                        <th style="width:0px;"></th>
                                        <th>Felh.</th>
                                        <th>Név</th>
                                        <th>E-mail</th>
                                        <th>Telefon</th>
                                        <th>Jogosultság</th>
                                        <th>Státusz</th>
                                        <th style="width:0px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
<?php foreach ($all_user as $value) { ?>
                                        <tr class="odd gradeX">
                                            <td>
                                                <?php if (($value['user_role_id'] != 1) && ($loggedin_user_role < $value['user_role_id']) && !isset($user_ref[$value['user_id']])) { ?>
                                                    <input type="checkbox" class="checkboxes" name="user_id_<?php echo $value['user_id']; ?>" value="<?php echo $value['user_id']; ?>"/>
    <?php } ?>	
                                            </td>
                                            <td><img src="<?php echo Config::get('user.upload_path') . $value['user_photo']; ?>" width="60" height="60"/></td>
                                            <td><?php echo $value['user_name']; ?></td>
                                            <td><?php echo $value['user_first_name'] . ' ' . $value['user_last_name']; ?></td>
                                            <td><a href="mailto:<?php echo $value['user_email']; ?>"><?php echo $value['user_email']; ?> </a></td>
                                            <td><?php echo $value['user_phone']; ?></td>
                                            <td><?php echo $value['role_name']; ?></td>
                                            <?php if ($value['user_active'] == 1) { ?>
                                                <td><span class="label label-sm label-success">Aktív</span></td>
                                            <?php } ?>
                                            <?php if ($value['user_active'] == 0) { ?>
                                                <td><span class="label label-sm label-danger">Inaktív</span></td>
    <?php } ?>
                                            <td>									
                                                <div class="actions">
                                                    <div class="btn-group">

                                                        <a class="btn btn-sm grey-steel" title="műveletek" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
                                                        <ul class="dropdown-menu pull-right">
                                                            <?php if (($loggedin_user_id == $value['user_id']) || (($loggedin_user_role == 1) && ($value['user_role_id'] == 2))) { ?>	
                                                                <li><a href="admin/users/profile/<?php echo $value['user_id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                            <?php } else { ?>
                                                                <li class="disabled-link"><a class="disable-target"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                            <?php } ?>

                                                            <?php if (isset($user_ref[$value['user_id']])) { ?>
                                                                <li class="disabled-link"><a class="disable-target" title="A felhsználó nem törölhető amíg van munka hozzárendelve"><i class="fa fa-trash"></i> Töröl</a></li>
                                                            <?php } elseif (($loggedin_user_role == 1) && ($loggedin_user_id != $value['user_id']) && ($value['user_role_id'] == 2)) { ?>
                                                                <li><a id="delete_user_<?php echo $value['user_id']; ?>" data-id="<?php echo $value['user_id']; ?>"> <i class="fa fa-trash"></i> Töröl</a></li>
                                                            <?php } else { ?>
                                                                <li class="disabled-link"><a class="disable-target" title="Nem törölhető"><i class="fa fa-trash"></i> Töröl</a></li>
                                                            <?php } ?>

                                                            <?php if (($loggedin_user_role == 1) && ($value['user_role_id'] == 2)) { ?>	
                                                                <?php if ($value['user_active'] == 1) { ?>
                                                                    <li><a rel="<?php echo $value['user_id']; ?>" id="make_inactive_<?php echo $value['user_id']; ?>" data-action="make_inactive"><i class="fa fa-ban"></i> Blokkol</a></li>
                                                                <?php } ?>
                                                                <?php if ($value['user_active'] == 0) { ?>
                                                                    <li><a rel="<?php echo $value['user_id']; ?>" id="make_active_<?php echo $value['user_id']; ?>" data-action="make_active"><i class="fa fa-check"></i> Aktivál</a></li>
                                                                <?php } ?>
    <?php } ?>	
                                                        </ul>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
<?php } ?>	
                                </tbody>
                            </table>	

                        </div>
                    </div>
                </form>					
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>

    </div><!-- END PAGE CONTAINER-->    
</div><!-- END PAGE CONTENT WRAPPER -->
</div><!-- END CONTAINER -->
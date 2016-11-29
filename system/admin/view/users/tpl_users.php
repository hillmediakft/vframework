<?php use \System\Libs\Auth; ?>
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
                <span>Felhasználók</span>
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

            <!-- ÜZENETEK -->
            <div id="ajax_message"></div> 						
            <?php $this->renderFeedbackMessages(); ?>				

            <form class="horizontal-form" id="users_form" method="POST" action="">

                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-user"></i>Felhasználók</div>

                        <div class="actions">
                            <?php
                            $loggedin_user_id = Auth::getUser('id');

                            if (Auth::hasAccess('user.insert')) { ?>
                                <a href="admin/user/insert" class="btn blue-steel btn-sm"><i class="fa fa-plus"></i> Új felhasználó</a>
                            <?php } ?>
                            <button class="btn red btn-sm" id="delete_group" type="button"><i class="fa fa-trash"></i> Csoportos törlés</button>
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

                        <table class="table table-striped table-bordered table-hover table-checkable" id="users">
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
                                <?php foreach ($users as $user) { ?>
                                <tr class="odd gradeX">
                                    <td>
                                    <?php if (Auth::hasAccess('user.delete') && $user['id'] != $loggedin_user_id) { ?>
                                        <input type="checkbox" class="checkboxes" name="user_id_<?php echo $user['id']; ?>" value="<?php echo $user['id']; ?>"/>
                                    <?php } ?>	
                                    </td>
                                    <td><img src="<?php echo $this->getConfig('user.upload_path') . $user['photo']; ?>" width="60" height="60"/></td>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                    <td><a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?> </a></td>
                                    <td><?php echo $user['phone']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <?php if ($user['active'] == 1) { ?>
                                        <td><span class="label label-sm label-success">Aktív</span></td>
                                    <?php } ?>
                                    <?php if ($user['active'] == 0) { ?>
                                        <td><span class="label label-sm label-danger">Inaktív</span></td>
                                    <?php } ?>
                                    <td>									
                                        <div class="actions">
                                            <div class="btn-group">

                                                <a class="btn btn-sm grey-steel" title="műveletek" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if (Auth::hasAccess('user.update') || $user['id'] == $loggedin_user_id) { ?>	
                                                        <li><a href="admin/user/profile/<?php echo $user['id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                    <?php } else { ?>
                                                        <!-- <li class="disabled-link"><a class="disable-target"><i class="fa fa-pencil"></i> Szerkeszt</a></li> -->
                                                    <?php } 
                                                        if (Auth::hasAccess('user.delete') && $user['id'] != $loggedin_user_id) { ?>
                                                        <li><a class="delete_item" data-id="<?php echo $user['id']; ?>"> <i class="fa fa-trash"></i> Töröl</a></li>
                                                    <?php } else { ?>
                                                        <!-- <li class="disabled-link"><a class="disable-target" title="Nem törölhető"><i class="fa fa-trash"></i> Töröl</a></li> -->
                                                    <?php } ?>

                                                    <?php if (Auth::hasAccess('user.change_status') && $user['id'] != $loggedin_user_id) { ?>	
                                                        <?php if ($user['active'] == 1) { ?>
                                                            <li><a class="change_status" data-id="<?php echo $user['id']; ?>" data-action="make_inactive"><i class="fa fa-ban"></i> Blokkol</a></li>
                                                        <?php } ?>
                                                        <?php if ($user['active'] == 0) { ?>
                                                            <li><a class="change_status" data-id="<?php echo $user['id']; ?>" data-action="make_active"><i class="fa fa-check"></i> Aktivál</a></li>
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
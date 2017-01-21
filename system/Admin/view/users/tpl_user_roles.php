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
            <li><a href="#">Felhasználói csoportok</a></li>
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

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-users"></i>Felhasználói csoportok</div>
                    <!--
                    <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                    </div>
                    -->
                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">


                    </div>
                    <table class="table table-striped table-bordered table-hover" id="users">
                        <thead>
                            <tr class="heading">
                                <th>Id</th>
                                <th>Felhasználói csoport</th>
                                <th>Leírás</th>
                                <th>Felhasználók száma</th>
                                <th style="width:115px"></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($roles as $role) { ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $role['id']; ?></td>
                                    <td><?php echo $role['role']; ?></td>
                                    <td><?php echo $role['desc']; ?></td>
                                    <?php
                                        // megszámoljuk, hogy az éppen aktuális kategóriának mennyi eleme van a jobs tábla job_category_id oszlopában
                                        $counter = 0;
                                        foreach ($roles_counter as $v) {
                                            if ($role['id'] == $v['role_id']) {
                                                $counter++;
                                            }
                                        }
                                        ?>
                                    <td><?php echo $counter; ?></td>			
                                    <td>
                                        <?php if($role['id'] != 1) { ?>
                                        <a class="btn btn-sm grey-steel" href="admin/user/edit_roles/<?php echo $role['id'];?>" ><i class="fa fa-pencil"></i> Szerkeszt</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div> <!-- END USER GROUPS PORTLET BODY-->
            </div> <!-- END USER GROUPS PORTLET-->
        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
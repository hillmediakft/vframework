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
            <li>GYIK kategóriák</li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->

    <div class="margin-bottom-20"></div>
        <!-- END PAGE TITLE & BREADCRUMB-->

        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">

                <!-- echo out the system feedback (error and success messages) -->
                <?php $this->renderFeedbackMessages(); ?>

                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-cogs"></i>GYIK kategóriák</div>

                        <div class="actions">
                            <a href="admin/gyik/category_insert" class="btn blue-steel btn-sm"><i class="fa fa-plus"></i> Új kategória</a>

                        </div>

                    </div>
                    <div class="portlet-body">




                        <table class="table table-striped table-bordered table-hover" id="gyik_category">
                            <thead>
                                <tr class="heading">
                                    <th>Kategória neve</th>
                                    <th>Kérdések ebben a kategóriában</th>
                                    <th style="width:0px;"></th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php foreach ($this->all_gyik_category as $value) { ?>

                                    <tr class="odd gradeX">
                                        <td><?php echo $value['gyik_category_name']; ?></td>
                                        <?php
                                        // megszámoljuk, hogy az éppen aktuális kategóriának mennyi eleme van a gyik tábla gyik_category_id oszlopában
                                        $counter = 0;
                                        foreach ($this->category_counter as $v) {
                                            if ($value['gyik_category_id'] == $v['gyik_category_id']) {
                                                $counter++;
                                            }
                                        }
                                        ?>
                                        <td><?php echo $counter; ?></td>


                                        <td>									
                                            <div class="actions">
                                                <div class="btn-group">
                                                    <a class="btn btn-sm grey-steel" href="#" title="műveletek" data-toggle="dropdown" <?php echo (Session::get('user_role_id') <= 2) ? '' : 'disabled'; ?>>
                                                        <i class="fa fa-cogs"></i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <?php if (Session::get('user_role_id') <= 2) { ?>	
                                                            <li><a href="admin/gyik/category_update/<?php echo $value['gyik_category_id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                            <li><a href="admin/gyik/category_delete/<?php echo $value['gyik_category_id']; ?>" id="delete_category_<?php echo $value['gyik_category_id']; ?>"><i class="fa fa-trash"></i> Töröl</a></li>
                                                            <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
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
</div> <!-- END PAGE CONTENT WRAPPER -->
</div> <!-- END CONTAINER -->
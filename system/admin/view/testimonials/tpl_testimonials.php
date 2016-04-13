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
            <li><span>Rólunk mondták szerkesztése</span></li>
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
                    <div class="caption"><i class="fa fa-file"></i>Rólunk mondták</div>
                    <div class="actions">
                        <a href="admin/testimonials/insert" class="btn blue-steel btn-sm"><i class="fa fa-plus"></i> Új vélemény hozzáadása</a>
                    </div>
                </div>

                <div class="margin-top-20"></div>

                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="content">
                        <thead>
                            <tr class="heading">
                                <th>#id</th>
                                <th>Vélemény</th>
                                <th style="width:150px">Név</th>
                                <th>Beosztás</th>
                                <th style="width:0px"></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($this->all_testimonials as $value) { ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo $value['text']; ?></td>
                                    <td><?php echo $value['name']; ?></td>
                                    <td><?php echo $value['title']; ?></td>

                                    <td>
                                        <div class="actions">
                                            <div class="btn-group">
                                                <a class="btn btn-sm grey-steel" title="Műveletek" href="#" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li><a href="admin/testimonials/update/<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                    <li><a href="admin/testimonials/delete/<?php echo $value['id']; ?>" id="delete_<?php echo $value['id']; ?>"><i class="fa fa-trash"></i> Töröl</a></li>
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
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <!-- <h3 class="page-title">Munkák <small>listája</small></h3> -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="admin/home">Kezdőoldal</a> 
                    <i class="fa fa-angle-right"></i>
                </li>
                <li><a href="admin/clients">Partnerek listája</a></li>
            </ul>
        </div>
        <!-- END PAGE TITLE & BREADCRUMB-->
        <!-- END PAGE HEADER-->


        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
	
                <div id="ajax_message"></div> 						
                
                <!-- echo out the system feedback (error and success messages) -->
                <?php $this->renderFeedbackMessages(); ?>				

                <form class="horizontal-form" id="del_client_form" method="POST" action="">	

                    <div class="portlet">
                        <div class="portlet-title">
                            <div class="caption"><i class="fa fa-cogs"></i>Partnerek listája</div>
                            <div class="actions">
                                <a href="admin/clients/new_client" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új partner hozzáadása</a>
                                 
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- *************************** JOBS TÁBLA *********************************** -->						
                            <table class="table table-striped table-bordered table-hover" id="clients">
                                <thead>
                                    <tr class="heading">
                                        <th>Logó</th>
                                        <th>Név</th>
                                        <th>Link</th>
                                        <th style="width:1%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_client as $value) { ?>
                                        <tr class="odd gradeX">

                                            <td style="width:155px;"><img src="<?php echo (!empty($value['client_photo'])) ? Config::get('clientphoto.upload_path') . $value['client_photo'] : Config::get('clientphoto.default_photo'); ?>"/></td>
                                            <td><?php echo $value['client_name'];?></td>
                                            <td><?php echo $value['client_link']; ?></td>
                                            <td>									
                                                <div class="actions">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown" <?php echo ((Session::get('user_role_id') > 2)) ? 'disabled' : ''; ?>>
                                                            <i class="fa fa-cogs"></i> 
                                                            
                                                        </a>
                                                        <ul class="dropdown-menu pull-right">

                                                            <?php if ((Session::get('user_role_id') < 3)) { ?>	
                                                                <li><a href="<?php echo 'admin/clients/update_client/' . $value['client_id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                            <?php }; ?>

                                                            <?php if ((Session::get('user_role_id') < 3)) { ?>	
                                                                <li><a id="delete_client_<?php echo $value['client_id']; ?>" data-id="<?php echo $value['client_id']; ?>"> <i class="fa fa-trash"></i> Töröl</a></li>
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
	
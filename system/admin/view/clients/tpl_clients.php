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
            <li><a href="admin/clients">Partnerek listája</a></li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->

    <div class="margin-bottom-20"></div>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            <!-- echo out the system feedback (error and success messages) -->
            <div id="ajax_message"></div> 						
            <?php $this->renderFeedbackMessages(); ?>				

            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-cogs"></i>Partnerek listája</div>
                    <div class="actions">
                        <a href="admin/clients/insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új partner hozzáadása</a>
                    </div>
                </div>
                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover dataTable" id="clients">
                        <thead>
                            <tr class="heading">
                                <th style="width:0px;"></th>
                                <th style="width:0px;">#</th>
                                <th style="width:0px;"></th>
                                <th style="width:0px;">Logó</th>
                                <th>Név</th>
                                <th>Link</th>
                                <th style="width:0px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_client as $client) { ?>
                                <tr class="odd gradeX">

                                    <td><?php echo $client['client_order']; ?></td>
                                    <td><?php echo $client['id']; ?></td>
                                    <td class="sortable_mover"><i class="fa fa-arrows"></i></td>
                                    <td><img src="<?php echo (!empty($client['photo'])) ? $this->getConfig('clientphoto.upload_path') . $client['photo'] : $this->getConfig('clientphoto.default_photo'); ?>"/></td>
                                    <td><?php echo $client['name'];?></td>
                                    <td><?php echo $client['link']; ?></td>
                                    <td>

                                        <div class="actions">
                                            <div class="btn-group">
                                                <a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown">
                                                    <i class="fa fa-cogs"></i> 
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if (1) { ?>   
                                                        <li><a href="<?php echo 'admin/clients/update/' . $client['id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                    <?php }; ?>

                                                    <?php if (1) { ?>   
                                                        <li><a class="delete_item" data-id="<?php echo $client['id']; ?>"> <i class="fa fa-trash"></i> Töröl</a></li>
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

        </div>
    </div>
</div> <!-- END PAGE CONTAINER-->
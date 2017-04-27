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
            <li><a href="admin/documents">Dokumentumok</a></li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->

    <div class="margin-bottom-20"></div>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <!-- ÜZENETEK -->
            <div id="ajax_message"></div>
            <?php $this->renderFeedbackMessages(); ?>

            <form class="horizontal-form" id="document_form" method="POST" action="">							

                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-cogs"></i>Feltöltött dokumentumok kezelése</div>

                        <div class="actions">
                            <a href="admin/documents/insert" class="btn blue btn-sm"><i class="fa fa-plus"></i> Új feltöltés</a>
                            <button class="btn red btn-sm" id="delete_group" type="button"><i class="fa fa-trash"></i> Csoportos törlés</button>
                          </div>

                    </div>
                    <div class="portlet-body">

                        <table class="table table-striped table-bordered table-hover table-checkable dataTable" id="documents">
                            <thead>
                                <tr>
                                    <th class="table-checkbox">
                                        <input type="checkbox" class="group-checkable" data-set="#documents .checkboxes" />
                                    </th>
                                    <th>Cím</th>
                                    <th>Leírás</th>
                                    <th>Fájl</th>
                                    <th>Dátum</th>
                                    <th>Kategória</th>
                                    <th style="width:0px;"></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($all_document as $value) { ?>
                                    <tr class="odd gradeX">
                                        <td>
                                            <?php if (1) { ?>
                                                <input type="checkbox" class="checkboxes" name="id_<?php echo $value['id']; ?>" value="<?php echo $value['id']; ?>"/>
                                            <?php } ?>	
                                        </td>
                                        <!-- <td><img src="<?php //echo Util::thumb_path($value['document_picture']); ?>" width="60" /></td>-->
                                        <td><?php echo $value['title']; ?></td>
                                        <td><?php echo $value['description']; ?></td>
                                        <td>
                                            <?php
                                            if (!is_null($value['file']) && $value['file'] !== '') {
                                                foreach (json_decode($value['file']) as $file) { 
                                                    echo '<a href="admin/documents/download/' . $file . '">' . $file . '</a><br>';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('Y-m-d', $value['created']); ?></td>
                                        <td><?php echo $value['name']; ?></td>
                                        <td>									
                                            <div class="actions">
                                                <div class="btn-group">
                                                    <a class="btn btn-sm grey-steel" href="#" data-toggle="dropdown"><i class="fa fa-cogs"></i></a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <?php if (1) { ?>	
                                                            <li><a href="admin/documents/update/<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                        <?php } ?>
                                                        <?php if (1) { ?>
                                                            <li><a class="delete_item" data-id="<?php echo $value['id']; ?>"><i class="fa fa-trash"></i> Töröl</a></li>
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

            </form>			

        </div> <!-- END COL-MD-12 -->
    </div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->    
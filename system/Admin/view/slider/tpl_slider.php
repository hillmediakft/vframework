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
            <li><span>Slider lista</span></li>
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

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-film"></i> 
                        Kezdőoldali slider kezelése
                    </div>
                    <div class="actions">
                        <a class="btn blue-steel btn-sm" href="admin/slider/insert"><i class="fa fa-plus"></i> Slide hozzáadása</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="note note-info">
                        A sorrend módosításához az egeret a sor elején lévő nyíl ikonnal jelzett cella fölé kell vinni. A négy irányú nyíl kurzor jelzi, hogy az elem mozgatható. Az új helyre helyezés után a sorrend frissítése azonnal megtörténik.
                    </div>
                    
                    <table class="table table-hover table-bordered slider_list" id="slider_table">
                        <thead>
                            <tr class="heading">
                                <th style="width:0px;"></th>
                                <th style="width:0px;">#</th>
                                <th style="width:0px;"></th>
                                <th style="width: 0px">Kép</th>
                                <th>Slide címe</th>
                                <th>Slide szövege</th>
                                <th>Státusz</th>
                                <th style="width: 0px"></th>
                            </tr>
                        </thead>
                        <tbody id="slider_list">						

                            <?php foreach ($sliders as $slider) { ?>
                                <tr id="slider_<?php echo $slider['id']; ?>" class="odd gradeX">

                                    <td><?php echo $slider['slider_order']; ?></td>
                                    <td><?php echo $slider['id']; ?></td>
                                    <td class="sortable_mover"><i class="fa fa-arrows"></i></td>

                                    <td><img src="<?php echo $this->getConfig('slider.upload_path') . $this->url_helper->thumbPath($slider['picture']); ?>"></td>
                                    <td><?php echo $slider['title']; ?></td>
                                    <td><?php echo $slider['text']; ?></td>

                                    <?php if ($slider['active'] == 1) { ?>
                                        <td><span class="label label-sm label-success"><?php echo 'Aktív'; ?></span></td>
                                    <?php } ?>
                                    <?php if ($slider['active'] == 0) { ?>
                                        <td><span class="label label-sm label-danger"><?php echo 'Inaktív'; ?></span></td>
                                    <?php } ?>
                                    <td>									
                                        <div class="actions">
                                            <div class="btn-group">
                                                <a class="btn btn-sm grey-steel" title="Műveletek" href="#" data-toggle="dropdown">
                                                    <i class="fa fa-cogs"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if (1) { ?>
                                                    <li><a href="admin/slider/update/<?php echo $slider['id']; ?>"><i class="fa fa-pencil"></i> Szerkeszt</a></li>
                                                    <?php } ?>
                                                    <?php if (1) { ?>
                                                        <li><a class="delete_item" data-id="<?php echo $slider['id']; ?>"><i class="fa fa-trash"></i> Töröl</a></li>
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
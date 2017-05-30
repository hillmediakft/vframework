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
            <li><span>Oldal hozzáadása</span></li>
        </ul>
    </div>
    <!-- END PAGE TITLE & BREADCRUMB-->
    <!-- END PAGE HEADER-->

    <div class="margin-bottom-20"></div>
	
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
            <form action="" name="insert_page_form" method='POST'>

				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i> 
                                Oldal hozzáadása
                            </div>
                            <div class="actions">
                                <button class="btn green btn-sm" type="submit" name="submit_insert_page"><i class="fa fa-check"></i> Mentés</button>
                                <a class="btn default btn-sm" href="admin/pages"><i class="fa fa-close"></i> Mégsem</a>
                            </div>							
                        </div>                        
                               
<div class="margin-bottom-20"></div>

                        <div class="portlet-body">

                            <!-- OLDAL NEVE -->
                            <div class="form-group">
                                <label for="title">Az oldal neve*</label>   
                                <input type="text" name="title" class="form-control input-xlarge" required />
                            </div>
                            <!-- EDITOR -->
                            <div class="form-group">
                                <label for="body_edit" class="control-label">Body edit</label>
                                <input type="checkbox" value="1" name="body_edit">         
                            </div>

                            <div class="form-group">
                                <label for="friendlyurl">Friendly url (Ékezet nélküli kis- és nagybetű, "_" és "-" karakter)</label>   
                                <input type="text" name="friendlyurl" class="form-control input-xlarge" pattern="[A-Za-z\_\-]" required/>
                            </div>

<div class="margin-bottom-30"></div>

                            <div class="portlet">
                                <!--<div class="portlet-title"></div>-->
                                <div class="portlet-body">
                                    <ul class="nav nav-tabs">
                                    <?php foreach ($langs as $key => $lang) { ?>
                                        <li class="<?php echo ($key == 0) ? 'active' : ''; ?>">
                                            <a href="#tab_1_<?php echo $key+1; ?>" data-toggle="tab"> <?php echo $lang; ?> </a>
                                        </li>
                                    <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php foreach ($langs as $key => $lang) { ?>
                                        <div class="tab-pane fade <?php echo ($key == 0) ? 'active in' : ''; ?>" id="tab_1_<?php echo $key+1; ?>">

                                            <div class="form-group">
                                                <label for="metatitle_<?php echo $lang; ?>">Metatitle / <?php echo $lang; ?></label>   
                                                <input type='text' name='metatitle_<?php echo $lang; ?>' class='form-control input-xlarge' />
                                            </div>

                                            <div class="form-group">
                                                <label for="metadescription_<?php echo $lang; ?>">Metadescription / <?php echo $lang; ?></label>  
                                                <input type='text' name='metadescription_<?php echo $lang; ?>' class='form-control input-xlarge' />
                                            </div>

                                            <div class="form-group">
                                                <label for="metakeywords_<?php echo $lang; ?>">Metakeywords / <?php echo $lang; ?></label>
                                                <input type='text' name='metakeywords_<?php echo $lang; ?>' class='form-control input-xlarge' />
                                            </div>

                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

					    </div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->
            </form>									
		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
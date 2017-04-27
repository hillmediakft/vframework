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
				<a href="admin/documents">Dokumentumok</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li><span>Dokumentum módosítása</span></li>
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
			<div id="ajax_message"></div>			
<!-- enctype="multipart/form-data" -->
			<form action="" method="POST" id="update_document_form">	
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet">

                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-film"></i> 
                            Dokumentum adatok módosítása
                        </div>
                        <div class="actions">
                            <button class="btn green btn-sm" type="submit" name="submit_update_document"><i class="fa fa-check"></i> Mentés</button>
                            <a class="btn default btn-sm" href="admin/documents"><i class="fa fa-close"></i> Mégsem</a>
                        </div>
                    </div>

					<div class="portlet-body">

						<div class="row">	
							<div class="col-md-12">						
							





  <div class="row">
        <div class="col-md-3">
            <div class="portlet">
                <div class="portlet-body">
                    <h4 class="block">Feltöltött dokumentumok:</h4>
                    <ul id="dokumentumok" class="list-group">
                        <?php
	                        $result_docs = json_decode($document[0]['file']);
	                        if (!empty($result_docs)) {
	                            $counter = 0;
	                            $file_location = $this->getConfig('documents.upload_path');
	                            foreach ($result_docs as $key => $value) {
	                                $counter = $key + 1;
	                                echo '<li id="doc_' . $counter . '" class="list-group-item"><i class="glyphicon glyphicon-file"> </i>&nbsp;' . $value . '<button type="button" class="btn btn-xs btn-default" style="position: absolute; top:8px; right:8px;"><i class="glyphicon glyphicon-trash"></i></button></li>' . "\n\r";
	                            }
	                        }
                        ?>
                    </ul>											
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <!-- DOKUMENTUMOK FELTÖLTÉSE -->
            <div class="portlet">
                <div class="portlet-body">
                    <h4 class="block">Dokumentumok hozzáadása:</h4>
                    <input type="file" name="new_doc[]" multiple="true" id="input-5" />
                </div>
            </div>
        </div>
    </div> <!-- row END -->	             


																
							<div class="clearfix"></div>

							<div class="note note-info">
								Kattintson a böngész gombra! Ha dokumentumot szeretne feltölteni!
							</div>

							<?php foreach($document as $value) { ?>
								<div class="form-group">
									<label for="title" class="control-label">Cím</label>
									<input type="text" name="title" id="title" value="<?php echo $value['title'];?>" class="form-control input-xlarge" />
								</div>
								<div class="form-group">
									<label for="description" class="control-label">Szöveg</label>
									<textarea name="description" id="description" class="form-control input-xlarge"><?php echo $value['description'];?></textarea>
								</div>
								<div class="form-group">
									<label for="category_id">Kategória</label>
									<select name="category_id" class="form-control input-xlarge">
								<?php foreach($category_list as $category) { ?>
										<option value="<?php echo $category['id']?>" <?php echo ($value['category_id'] == $category['id']) ? "selected" : "";?>><?php echo $category['name']?></option>
								<?php } ?>
									</select>
								</div>
							<?php } ?>
								<!-- rekord id-je -->
								<input type="hidden" name="item_id" id="item_id" value="<?php echo $document[0]['id']; ?>">
							</div>
							
						</div>	

					</div> <!-- END USER GROUPS PORTLET BODY-->
				</div> <!-- END USER GROUPS PORTLET-->

			</form>

		</div> <!-- END COL-MD-12 -->
	</div> <!-- END ROW -->	
</div> <!-- END PAGE CONTENT-->
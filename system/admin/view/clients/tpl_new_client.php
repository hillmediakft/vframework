<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <!-- 
        <h3 class="page-title">
                Munka <small>hozzáadása</small>
        </h3>
        -->

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="admin/home">Kezdőoldal</a> 
                    <i class="fa fa-angle-right"></i>
                </li>
                <li><span>Partner hozzáadása</span></li>
            </ul>
        </div>

        <!-- END PAGE TITLE & BREADCRUMB-->
        <!-- END PAGE HEADER-->

        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">

                <!-- ÜZENETEK -->
                <div id="message"></div> 
                <?php $this->renderFeedbackMessages(); ?>			

                <form action="" method="POST" id="new_client">	

                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span><!-- ide jön az üzenet--></span>
                    </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button>
                        <span><!-- ide jön az üzenet--></span>
                    </div>	

                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet">

                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i>
                                Partner hozzáadása
                            </div>
                            <div class="actions">
                                <button class="btn green btn-sm" type="submit"><i class="fa fa-check"></i> Mentés</button>
                                <a class="btn default btn-sm" href="admin/clients"><i class="fa fa-close"></i> Mégsem</a>
                                <!-- <button class="btn default btn-sm" name="cancel" type="button"><i class="fa fa-close"></i> Mégsem</button>-->
                            </div>
                        </div>
                        <div class="portlet-body">

                            <div class="space10"></div>							
                            <div class="row">	
                                <div class="col-md-12">
                                    
                                    
                                    
                                    <label>Partner logó</label>

                                        <div id="client_image"></div>	

                                        <input type="hidden" name="img_url" id="OutputId" >

                                        <div class="space10"></div>
                                        <div class="clearfix"></div>
                                        <div class="alert alert-info">
                                            <span>Kép kiválasztásához kattintson a felső sarokban található zöld színű, nyilat ábrázoló ikonra. A képet mozgathatja, nagyíthatja, kicsinyítheti és forgathatja. Amikor a képet tetszés szerint beállította, kattintson a zöld körbevágás ikonra. <br>Amennyiben másik képet szeretne kiválasztani, kattintson a piros színű keresztre, majd ismét a zöld nyíl ikonra.</div>
                                        <div class="space10"></div>                                 
                                    
                                    
                                    
                                <div class="row">    
                                    
                                <div class="col-md-6">    
                                    <!-- PARTNER NÉV -->	
                                    <div class="form-group">
                                        <label for="client_name" class="control-label">Partner neve <span class="required">*</span></label>
                                        <input type="text" name="client_name" id="client_name" placeholder="" class="form-control input-xlarge" />
                                    </div>
                                    <!-- PARTNER WEBOLDAL LINKJE -->	
                                    <div class="form-group">
                                        <label for="client_link" class="control-label">Partner weboldala <span class="required">*</span></label>
                                        <input type="text" name="client_link" id="client_link" placeholder="" class="form-control input-xlarge" />
                                    </div>
                                                                       
                                </div> 
                                    
                                
                                </div>
                                    


                                </div>
                            </div>	

                        </div> <!-- END CREW MEMBERS GROUPS PORTLET BODY-->
                    </div> <!-- END CREW MEMBERS PORTLET-->
                </form>


            </div> <!-- END COL-MD-12 -->
        </div> <!-- END ROW -->	
    </div> <!-- END PAGE CONTENT-->    
</div> <!-- END PAGE CONTENT WRAPPER -->
</div><!-- END CONTAINER -->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h1 id="fme_Ulpoad_files_pluginheader"><?php echo esc_html__('Upload Files Settings', 'Fme_Upload_Files'); ?></h1>
		</div>
	</div>
</div>

<section class="Fme_Upload_Files">
  <div class="container-fluid">
	<div class="clearfix"></div>
	<div class="Fme-margin-top-4">
	  <div class="Fme-tabbable-line">
		<ul class="nav nav-tabs Fme_upload_files_tabtop">
		  <li class="active"> <a href="#Fme_tab_default_1" data-toggle="tab"><?php echo esc_html__('Add Rule', 'Fme_Upload_Files'); ?></a> </li>
		  <li> <a href="#Fme_tab_default_3" data-toggle="tab"><?php echo esc_html__('Manage Rule', 'Fme_Upload_Files'); ?></a> </li>
		</ul>
		<div class="tab-content Fmemargin-tops">
		  <div class="tab-pane active fade in" id="Fme_tab_default_1">
			<div class="col-md-11" id="fme_upload_files_content">
				<?php require_once( FMEUF_PLUGIN_DIR . 'admin/view/template/Fme-General-settings.php' ); ?>
			</div>
		  </div>
		  <div class="tab-pane fade" id="Fme_tab_default_3">
			<div class="col-md-11" id="fme_upload_files_content">
				<?php require_once( FMEUF_PLUGIN_DIR . 'admin/view/template/Fme-manage-rules.php' ); ?> 
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</section>

<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a class="ajax-save btn btn-primary" data-toggle="tooltip" title="<?php echo $text_apply; ?>" class="btn btn-primary"><i class="fa fa-rocket"></i></a>
        <button type="submit" form="form-remarketing" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <h1><?php echo $heading_title; ?></h1>
        <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($max_input_vars_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $max_input_vars_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($jetcache_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $jetcache_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($seopro_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $seopro_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (!empty($settings_warning)) { ?> 
	<?php foreach ($settings_warning as $warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-remarketing" class="form-horizontal">
		  <div class="col-md-2"> 
		  <ul class="nav nav-pills nav-stacked">
		    <li class="active"><a href="#tab-diagnostics" data-toggle="tab" class="diag"><img src="view/image/remarketing/rem_main.png"> <?php echo $text_diagnostics; ?> <i class="fa fa-flash"></i></a></li>
			<li><a href="#tab-ga4" data-toggle="tab" <?php if ($remarketing_ga4_dl_status || $remarketing_ga4_status || $remarketing_ga4_mp_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_ga4.png"> <?php echo $text_ga4; ?></a></li>
            <li><a href="#tab-ads" data-toggle="tab" <?php if ($remarketing_google_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_gads.png"> <?php echo $text_google_remarketing; ?></a></li>
			<li><a href="#tab-reviews" data-toggle="tab" <?php if ($remarketing_reviews_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_greviews.png"> <?php echo $text_google_reviews; ?></a></li>
            <li><a href="#tab-facebook" data-toggle="tab" <?php if ($remarketing_facebook_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_fb.png"> <?php echo $text_facebook_remarketing; ?></a></li>
			<li><a href="#tab-feed" data-toggle="tab" <?php if ($remarketing_feed_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_feed.png"> <?php echo $text_feed; ?></a></li>
			<li><a href="#tab-esputnik" data-toggle="tab" <?php if ($remarketing_esputnik_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_esputnik.png"> <?php echo $text_esputnik; ?></a></li>
			<li><a href="#tab-tiktok" data-toggle="tab" <?php if ($remarketing_tiktok_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_tiktok.png"> <?php echo $text_tiktok; ?></a></li>
			<li><a href="#tab-snapchat" data-toggle="tab" <?php if ($remarketing_snapchat_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_snapchat.png"> <?php echo $text_snapchat; ?> Beta</a></li>
			<li><a href="#tab-uet" data-toggle="tab" <?php if ($remarketing_uet_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_microsoft.png"> <?php echo $text_uet; ?> Beta</a></li>
			<li><a href="#tab-telegram" data-toggle="tab" <?php if ($remarketing_telegram_status) { ?>class="enabled"<?php } ?>><img src="view/image/remarketing/rem_tg.png"> <?php echo $text_telegram; ?></a></li>
            <li><a href="#tab-events" data-toggle="tab"><img src="view/image/remarketing/rem_js.png"> <?php echo $text_events; ?></a></li>
            <li><a href="#tab-counters" data-toggle="tab"><img src="view/image/remarketing/rem_counters.png"> <?php echo $text_counters; ?></a></li>
            <li><a href="<?php echo $remarketing_report_link; ?>" target="_blank"><img src="view/image/remarketing/rem_reports.png"> <?php echo $text_reports; ?></a></li>
            <li><a href="#tab-debug" data-toggle="tab"><img src="view/image/remarketing/rem_debug.png"> <?php echo $text_debug; ?></a></li>
          </ul>
		  </div>
		  <div class="col-md-10">
		  <div class="tab-content">
		  <div class="tab-pane active" id="tab-diagnostics">
		  <img src="view/image/remarketing/rem_logo.png">
		  <legend><?php echo $text_version; ?>: <span class="version"><?php echo $version; ?></span><span class="version-update"></span></legend>
		  <legend class="update-block" style="display:none;"><?php echo $text_how_update; ?></legend>
		  <?php if (!$remarketing_google_status && !$remarketing_ga4_status && !$remarketing_ga4_dl_status && !$remarketing_facebook_status) { ?>
		  <div class="quick-form">
		  <legend><?php echo $text_quickstart; ?></legend> 
		  <div class="legend-text"><?php echo $text_quickstart_description; ?></div>
		  <div class="form-group">
		 <label class="col-sm-2 control-label"><?php echo $text_quickstart_currency; ?></label>
		  <div class="col-sm-10">
		  <select name="quick_currency" id="quick_currency" class="form-control">
		  <?php foreach ($currencies as $currency) { ?>
		  <option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
		  <?php } ?>
		  </select>
		  </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_quickstart_ga4_id; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="ga4_id" value="" id="ga4_id" placeholder="G-XXXXXXXXX" class="form-control"/>
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_quickstart_gtm_id; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="gtm_id" value="" id="gtm_id" placeholder="GTM-XXXXXXX" class="form-control"/>
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_quickstart_ads_id; ?></label> 
                <div class="col-sm-10">
                   <input type="text" name="ads_id" value="" id="ads_id" placeholder="AW-XXXXXXXX" class="form-control"/>
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_quickstart_fb_id; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="facebook_id" value="" id="facebook_id" placeholder="XXXXXXXXXXX" class="form-control"/><br>
				   <a class="magic-button"><?php echo $text_quickstart_button; ?></a>
				   <div class="magic-error" style="display:none;"><?php echo $text_quickstart_error; ?></div>
                </div>
		  </div>
		  <br><br>
		  </div>
		  <?php } ?>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
			<label class="switch">
				<input type="checkbox" name="remarketing_status" <?php if ($remarketing_status) { ?>checked<?php } ?>>
				<span class="slider round"></span> 
			</label>
            </div>
            </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_bot_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_bot_status" <?php if ($remarketing_bot_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_admin_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_admin_status" <?php if ($remarketing_admin_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_not_customer_groups; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 100px; overflow: auto; width: 200px;">
                   <?php foreach ($customer_groups as $customer_group) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($customer_group['customer_group_id'], $remarketing_not_customer_groups)) { ?>
                       <input type="checkbox" name="remarketing_not_customer_groups[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                       <?php echo $customer_group['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_not_customer_groups[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                       <?php echo $customer_group['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
          </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_not_show_in_order; ?></label>
            <div class="col-sm-10">
            <label class="switch">
				<input type="checkbox" name="remarketing_not_show_in_order" <?php if ($remarketing_not_show_in_order) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_max_order_value; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_max_order_value" value="<?php echo $remarketing_max_order_value; ?>" class="form-control" />
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_custom_checkout_route; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_custom_checkout_route" value="<?php echo $remarketing_custom_checkout_route; ?>" class="form-control" />
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_custom_begin_checkout_route; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_custom_begin_checkout_route" value="<?php echo $remarketing_custom_begin_checkout_route; ?>" class="form-control" />
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_no_shipping; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_no_shipping" <?php if ($remarketing_no_shipping) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_product_cost; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_product_cost" value="<?php echo $remarketing_product_cost; ?>" class="form-control" />
                </div>
		  </div>
		  </div>  
          <div class="tab-pane" id="tab-ads"> 
			<legend><img src="view/image/remarketing/rem_gads.png"> <?php echo $text_google_remarketing; ?></legend>
			<div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
             <label class="switch">
				<input type="checkbox" name="remarketing_google_status" <?php if ($remarketing_google_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_google_id" class="form-control">
                <option value="product_id" <?php if ($remarketing_google_id == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
                <option value="model" <?php if ($remarketing_google_id == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select>
            </div>
          </div> 		  
		  <div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_google_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_google_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_identifier; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_google_identifier" placeholder="AW-XXXXXXXX" value="<?php echo $remarketing_google_identifier; ?>" class="form-control" />
                </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_ads_identifier; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_google_ads_identifier" placeholder="AW-CONVERSION_ID/AW-CONVERSION_LABEL" value="<?php echo $remarketing_google_ads_identifier; ?>" class="form-control" />
                  </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_ads_quick_order_identifier; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_google_ads_quick_order_identifier" placeholder="AW-CONVERSION_ID/AW-CONVERSION_LABEL" value="<?php echo $remarketing_google_ads_quick_order_identifier; ?>" class="form-control" />
                  </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_ads_identifier_cart; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_google_ads_identifier_cart" placeholder="AW-CONVERSION_ID/AW-CONVERSION_LABEL" value="<?php echo $remarketing_google_ads_identifier_cart; ?>" class="form-control" />
            </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_ads_identifier_cart_page; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_google_ads_identifier_cart_page" placeholder="AW-CONVERSION_ID/AW-CONVERSION_LABEL" value="<?php echo $remarketing_google_ads_identifier_cart_page; ?>" class="form-control" />
            </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ratio; ?></label>
            <div class="col-sm-10">
               <input type="text" name="remarketing_google_ads_ratio" value="<?php echo $remarketing_google_ads_ratio; ?>" class="form-control" />
            </div>
		  </div>
   		  </div>
          <div class="tab-pane" id="tab-facebook">
			<legend><img src="view/image/remarketing/rem_fb.png"> <?php echo $text_facebook_remarketing; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_facebook_status" <?php if ($remarketing_facebook_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_facebook_id" class="form-control">
			    <option value="product_id" <?php if ($remarketing_facebook_id == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
                <option value="model" <?php if ($remarketing_facebook_id == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select>
            </div>
          </div> 
		  <div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_facebook_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_facebook_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_identifier; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_facebook_identifier" value="<?php echo $remarketing_facebook_identifier; ?>" class="form-control" />
              </div>
			</div>
		<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ratio; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_facebook_ratio" value="<?php echo $remarketing_facebook_ratio; ?>" class="form-control" />
              </div>
		</div>
		  <legend><?php echo $text_facebook_pixel; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_script_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_facebook_script_status" <?php if ($remarketing_facebook_script_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_pixel_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_facebook_pixel_status" <?php if ($remarketing_facebook_pixel_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div>
          </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_lead; ?></label>
            <div class="col-sm-10">
             <label class="switch">
				<input type="checkbox" name="remarketing_facebook_lead" <?php if ($remarketing_facebook_lead) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>		  
		   <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_depth; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_facebook_depth" <?php if ($remarketing_facebook_depth) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_depth_params; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_facebook_depth_params" value="<?php echo $remarketing_facebook_depth_params; ?>" class="form-control" />
              </div>
		  </div>
		  <legend><?php echo $text_facebook_api; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_server_side; ?></label>
            <div class="col-sm-10"> 
              <label class="switch">
				<input type="checkbox" name="remarketing_facebook_server_side" <?php if ($remarketing_facebook_server_side) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_token; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_facebook_token" value="<?php echo $remarketing_facebook_token; ?>" class="form-control" />
              </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_api_ver; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_facebook_api_ver" value="<?php echo $remarketing_facebook_api_ver; ?>" class="form-control" />
              </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_test_code; ?></label>
             <div class="col-sm-10">
                <input type="text" name="remarketing_facebook_test_code" value="<?php echo $remarketing_facebook_test_code; ?>" class="form-control" />
				<br><a class="btn btn-primary test-facebook"><?php echo $button_test_facebook; ?></a>
				<div class="facebook_result"></div>
             </div>
		  </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_send_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_facebook_send_status)) { ?>
                       <input type="checkbox" name="remarketing_facebook_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_facebook_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_facebook_lead_send_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_facebook_lead_send_status)) { ?>
                       <input type="checkbox" name="remarketing_facebook_lead_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_facebook_lead_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		  <div class="form-group">
             <label class="col-sm-2 control-label"><?php echo $entry_resend_status; ?></label>
             <div class="col-sm-10">
             <select name="remarketing_facebook_resend_status" class="form-control">
			  <option value="0"><?php echo $text_not_selected; ?></option>
                 <?php foreach ($order_statuses as $order_status) { ?>
                 <?php if ($order_status['order_status_id'] == $remarketing_facebook_resend_status) { ?>
                 <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                 <?php } else { ?>
                 <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                 <?php } ?>
                 <?php } ?>
               </select>
             </div>
          </div>
		  </div>
          <div class="tab-pane" id="tab-tiktok">
          <legend><img src="view/image/remarketing/rem_tiktok.png"> <?php echo $text_tiktok; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_tiktok_status" <?php if ($remarketing_tiktok_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_tiktok_id" class="form-control">
			    <option value="product_id" <?php if ($remarketing_tiktok_id == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
                <option value="model" <?php if ($remarketing_tiktok_id == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select>
            </div>
          </div> 
		  <div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_tiktok_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_tiktok_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_identifier; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_tiktok_identifier" value="<?php echo $remarketing_tiktok_identifier; ?>" class="form-control" />
              </div>
			</div>
		<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ratio; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_tiktok_ratio" value="<?php echo $remarketing_tiktok_ratio; ?>" class="form-control" />
              </div>
		</div>
		  <legend><?php echo $text_tiktok_pixel; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_script_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_tiktok_script_status" <?php if ($remarketing_tiktok_script_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_pixel_status; ?></label>
			<div class="col-sm-10">
				<label class="switch">
				<input type="checkbox" name="remarketing_tiktok_pixel_status" <?php if ($remarketing_tiktok_pixel_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
				</label>
			</div>  
          </div>
		  <legend><?php echo $text_tiktok_api; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_server_side; ?></label>
            <div class="col-sm-10"> 
			<label class="switch">
				<input type="checkbox" name="remarketing_tiktok_server_side" <?php if ($remarketing_tiktok_server_side) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_token; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_tiktok_token" value="<?php echo $remarketing_tiktok_token; ?>" class="form-control" />
              </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_api_ver; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_tiktok_api_ver" value="<?php echo $remarketing_tiktok_api_ver; ?>" class="form-control" />
              </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_test_code; ?></label>
             <div class="col-sm-10">
                <input type="text" name="remarketing_tiktok_test_code" value="<?php echo $remarketing_tiktok_test_code; ?>" class="form-control" />
             </div>
		  </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tiktok_send_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_tiktok_send_status)) { ?>
                       <input type="checkbox" name="remarketing_tiktok_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_tiktok_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		  <div class="form-group">
             <label class="col-sm-2 control-label"><?php echo $entry_resend_status; ?></label>
             <div class="col-sm-10">
             <select name="remarketing_tiktok_resend_status" class="form-control">
			  <option value="0"><?php echo $text_not_selected; ?></option>
                 <?php foreach ($order_statuses as $order_status) { ?>
                 <?php if ($order_status['order_status_id'] == $remarketing_tiktok_resend_status) { ?>
                 <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                 <?php } else { ?>
                 <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                 <?php } ?>
                 <?php } ?>
               </select>
             </div>
          </div>		  
		  </div>
          <div class="tab-pane" id="tab-snapchat">
          <legend><img src="view/image/remarketing/rem_snapchat.png"> <?php echo $text_snapchat; ?></legend>
	   	  <div class="help-link doc-link"><a href="https://businesshelp.snapchat.com/s/topic/0TO0y000000YVdJGAW/snap-pixel?language=en_US" target="_blank"><?php echo $text_help_link; ?> Snapchat</a></div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_snapchat_status" <?php if ($remarketing_snapchat_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_snapchat_id" class="form-control">
				<option value="product_id" <?php if ($remarketing_snapchat_id == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
				<option value="model" <?php if ($remarketing_snapchat_id == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select>
            </div>
          </div> 
		 <div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_snapchat_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_snapchat_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_snapchat_identifier; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_snapchat_identifier" value="<?php echo $remarketing_snapchat_identifier; ?>" id="input-remarketing_snapchat_identifier" class="form-control" />
              </div>
			</div>
		<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ratio; ?></label>
              <div class="col-sm-10">
                 <input type="text" name="remarketing_snapchat_ratio" value="<?php echo $remarketing_snapchat_ratio; ?>" class="form-control" />
              </div>
		</div>
		  <legend><?php echo $text_snapchat_pixel; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_snapchat_script_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_snapchat_script_status" <?php if ($remarketing_snapchat_script_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_snapchat_pixel_status; ?></label>
			<div class="col-sm-10">
				<label class="switch">
				<input type="checkbox" name="remarketing_snapchat_pixel_status" <?php if ($remarketing_snapchat_pixel_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
				</label>
			</div>  
          </div>
		  </div>
		  <div class="tab-pane" id="tab-uet">
          <legend><img src="view/image/remarketing/rem_microsoft.png"> <?php echo $text_uet; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_uet_status" <?php if ($remarketing_uet_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  </div>
          <div class="tab-pane" id="tab-telegram">
          <legend><img src="view/image/remarketing/rem_tg.png"> <?php echo $text_telegram; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_telegram_status" <?php if ($remarketing_telegram_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
		  	</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_telegram_bot_id; ?></label>
               <div class="col-sm-10">
                    <input type="text" name="remarketing_telegram_bot_id" value="<?php echo $remarketing_telegram_bot_id; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_telegram_send_to_id; ?></label>
               <div class="col-sm-10">
                    <input type="text" name="remarketing_telegram_send_to_id" value="<?php echo $remarketing_telegram_send_to_id; ?>" class="form-control" />
					<br><a class="btn btn-primary test-tg"><?php echo $button_test_facebook; ?></a>
				<div class="tg_result"></div>
               </div>
		  </div>
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_telegram_send_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_telegram_send_status)) { ?>
                       <input type="checkbox" name="remarketing_telegram_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_telegram_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
			<div class="form-group">
			   <label class="col-sm-2 control-label"><?php echo $entry_telegram_message; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_telegram_message" rows="20" class="form-control"><?php echo $remarketing_telegram_message; ?></textarea>
               </div>
		    </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_telegram_contact_form; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_telegram_contact_form" <?php if ($remarketing_telegram_contact_form) { ?>checked<?php } ?>>
				<span class="slider round"></span>
		  	</label>
            </div>
            </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_telegram_review; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_telegram_review" <?php if ($remarketing_telegram_review) { ?>checked<?php } ?>>
				<span class="slider round"></span>
		  	</label>
            </div>
            </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_telegram_callback_oct; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_telegram_callback_oct" <?php if ($remarketing_telegram_callback_oct) { ?>checked<?php } ?>>
				<span class="slider round"></span>
		  	</label>
            </div>
            </div>
		  </div>
		  <div class="tab-pane" id="tab-reviews">
          <legend><img src="view/image/remarketing/rem_greviews.png"> <?php echo $text_google_reviews; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
             <label class="switch">
				<input type="checkbox" name="remarketing_reviews_status" <?php if ($remarketing_reviews_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
		 	</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_google_merchant_identifier; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_google_merchant_identifier" value="<?php echo $remarketing_google_merchant_identifier; ?>" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_reviews_country; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_reviews_country" value="<?php echo $remarketing_reviews_country; ?>" class="form-control" />
                  </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_reviews_date; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="remarketing_reviews_date" value="<?php echo $remarketing_reviews_date; ?>" class="form-control" />
                  </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_gtin; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_reviews_feed_gtin" value="<?php echo $remarketing_reviews_feed_gtin; ?>" class="form-control" />
                </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_mpn; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_reviews_feed_mpn" value="<?php echo $remarketing_reviews_feed_mpn; ?>" class="form-control" />
                </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_sku; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_reviews_feed_sku" value="<?php echo $remarketing_reviews_feed_sku; ?>" class="form-control" />
                </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_asin; ?></label>
                <div class="col-sm-10">
                   <input type="text" name="remarketing_reviews_feed_asin" value="<?php echo $remarketing_reviews_feed_asin; ?>" class="form-control" />
                </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_reviews_quick_order_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_reviews_quick_order_status" <?php if ($remarketing_reviews_quick_order_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
			  <br>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label" style="padding:0 15px"><?php echo $entry_feed_link; ?></label>
               <div class="col-sm-10">
			   <a href="<?php echo $catalog_link . 'index.php?route=extension/feed/remarketing_feed/googleReviews'; ?><?php echo $remarketing_reviews_feed_key ? '&key=' . $remarketing_reviews_feed_key : ''; ?>" target="_blank"><?php echo $catalog_link . 'index.php?route=extension/feed/remarketing_feed/googleReviews'; ?><?php echo $remarketing_reviews_feed_key ? '&key=' . $remarketing_reviews_feed_key : ''; ?></a>
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_key; ?></label> 
               <div class="col-sm-10">
                  <input type="text" name="remarketing_reviews_feed_key" value="<?php echo $remarketing_reviews_feed_key; ?>" class="form-control" />
				  <?php if (empty($remarketing_reviews_feed_key)) { ?>
				  <br><span class="feed-rkey-text"><?php echo $text_rfeed_generate; ?></span>  
				  <script>function generateRKey(){$('input[name="remarketing_reviews_feed_key"]').removeAttr('onclick').val(Array.from({length: 20},()=>'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'.charAt(Math.floor(Math.random()*62))).join(''));$('.gen-button').html('<i class="fa fa-check"></i> OK').css('background-color', 'green');setTimeout(function(){$('.feed-rkey-text').remove()},2000);}</script>
				  <?php } ?>
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_anonymous; ?></label>
            <div class="col-sm-10">
             <label class="switch">
				<input type="checkbox" name="remarketing_reviews_feed_anonymous" <?php if ($remarketing_reviews_feed_anonymous) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  </div>
		  <div class="tab-pane" id="tab-esputnik">
          <legend><img src="view/image/remarketing/rem_esputnik.png"> <?php echo $text_esputnik; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
            <label class="switch">
				<input type="checkbox" name="remarketing_esputnik_status" <?php if ($remarketing_esputnik_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_esputnik_id" class="form-control">
			    <option value="product_id" <?php if ($remarketing_esputnik_id == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
                <option value="model" <?php if ($remarketing_esputnik_id == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select>
            </div>
          </div> 
		  <div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_esputnik_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_esputnik_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_external_id; ?></label>
            <div class="col-sm-10"> 
              <select name="remarketing_esputnik_external_id" class="form-control">
                <option value="email" <?php if ($remarketing_esputnik_external_id == 'email') { ?>selected="selected"<?php } ?>>Email</option>
                <option value="telephone" <?php if ($remarketing_esputnik_external_id == 'telephone') { ?>selected="selected"<?php } ?>>Telephone</option>
                <option value="customer_id" <?php if ($remarketing_esputnik_external_id == 'customer_id') { ?>selected="selected"<?php } ?>>Customer id</option>
              </select>
            </div>
          </div> 
		  <legend>WEB Tracking</legend>	
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_webtracking_status; ?></label>
            <div class="col-sm-10"> 
            <label class="switch">
				<input type="checkbox" name="remarketing_esputnik_webtracking_status" <?php if ($remarketing_esputnik_webtracking_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_webtracking_identifier; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_esputnik_webtracking_identifier" value="<?php echo $remarketing_esputnik_webtracking_identifier; ?>" class="form-control" />
               </div>
		  </div> 
		  <legend>API Tracking</legend>	
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_api_status; ?></label>
            <div class="col-sm-10">  
            <label class="switch">
				<input type="checkbox" name="remarketing_esputnik_api_status" <?php if ($remarketing_esputnik_api_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_login; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_esputnik_login" value="<?php echo $remarketing_esputnik_login; ?>" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_esputnik_password; ?></label>
             <div class="col-sm-10">
                <input type="text" name="remarketing_esputnik_password" value="<?php echo $remarketing_esputnik_password; ?>" class="form-control" />
             </div>
		  </div>
		  <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_esputnik_address_format; ?></label>
             <div class="col-sm-10">
                <input type="text" name="remarketing_esputnik_address_format" value="<?php echo $remarketing_esputnik_address_format; ?>" class="form-control" />
             </div>
		  </div>
		  <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_esputnik_ttn_field; ?></label>
             <div class="col-sm-10">
                <input type="text" name="remarketing_esputnik_ttn_field" value="<?php echo $remarketing_esputnik_ttn_field; ?>" class="form-control" />
             </div>
		  </div>
			<div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_initialized_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_esputnik_initialized_status)) { ?>
                       <input type="checkbox" name="remarketing_esputnik_initialized_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_esputnik_initialized_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_inprogress_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_esputnik_inprogress_status)) { ?>
                       <input type="checkbox" name="remarketing_esputnik_inprogress_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_esputnik_inprogress_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_delivered_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_esputnik_delivered_status)) { ?>
                       <input type="checkbox" name="remarketing_esputnik_delivered_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_esputnik_delivered_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_esputnik_cancelled_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_esputnik_cancelled_status)) { ?>
                       <input type="checkbox" name="remarketing_esputnik_cancelled_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_esputnik_cancelled_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		  </div>
		  <div class="tab-pane" id="tab-events">
          <legend><img src="view/image/remarketing/rem_js.png"> <?php echo $text_events; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_events_purchase; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_events_purchase" rows="5" class="form-control"><?php echo $remarketing_events_purchase; ?></textarea>
               </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_events_quick_purchase; ?></label> 
               <div class="col-sm-10">
				    <textarea name="remarketing_events_quick_purchase" rows="5" class="form-control"><?php echo $remarketing_events_quick_purchase; ?></textarea>
               </div>
		  </div>
			<div class="form-group hidden to-delete">
			<label class="col-sm-2 control-label"><?php echo $entry_events_cart; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_events_cart" rows="5" class="form-control"><?php echo $remarketing_events_cart; ?></textarea>
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_events_cart_add; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_events_cart_add" rows="5" class="form-control"><?php echo $remarketing_events_cart_add; ?></textarea>
               </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_events_wishlist; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_events_wishlist" rows="5" class="form-control"><?php echo $remarketing_events_wishlist; ?></textarea>
               </div>
		  </div>
		  </div>
		  <div class="tab-pane" id="tab-ga4">
		   <legend><img src="view/image/remarketing/rem_ga4.png"> <?php echo $text_ga4; ?></legend>
			<div class="help-link doc-link"><a href="https://support.google.com/analytics/answer/9267735?hl=ru" target="_blank"><?php echo $text_help_link; ?> Google</a></div>
		  	<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_ga4_id" class="form-control">
			    <option value="product_id" <?php if ($remarketing_ga4_id == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
                <option value="model" <?php if ($remarketing_ga4_id == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select>
            </div>
          </div>
		  <div class="form-group">
		  <label class="col-sm-2 control-label"><?php echo $entry_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_ga4_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_ga4_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		  <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_ga4_language_id; ?></label>
                <div class="col-sm-10">
                    <select name="remarketing_ga4_language_id" class="form-control">
                      <?php foreach ($languages as $language_id => $language_name) { ?>
                      <?php if ($language_id == $remarketing_ga4_language_id) { ?>
                      <option value="<?php echo $language_id; ?>" selected="selected"><?php echo $language_name; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $language_id; ?>"><?php echo $language_name; ?></option>
                      <?php } ?>
                      <?php } ?>
                 </select>
              </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_quick_order_event_name; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_ga4_quick_order_event_name" value="<?php echo $remarketing_ga4_quick_order_event_name; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label"><?php echo $entry_ratio; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_ga4_ratio" value="<?php echo $remarketing_ga4_ratio; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_seopro_categories; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_seopro_categories" <?php if ($remarketing_ga4_seopro_categories) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
            </div>
			<legend><?php echo $text_ga4_send_way; ?> Google Tag</legend>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_status" <?php if ($remarketing_ga4_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_identifier; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_ga4_identifier" value="<?php echo $remarketing_ga4_identifier; ?>" placeholder="G-XXXXXXXXX" class="form-control" />
               </div>
			</div>
          <legend><?php echo $text_ga4_send_way; ?> dataLayer</legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_dl_status" <?php if ($remarketing_ga4_dl_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			 </label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_dl_remove_prefix; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_dl_remove_prefix" <?php if ($remarketing_ga4_dl_remove_prefix) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			 </label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_dl_netpeak; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_dl_netpeak" <?php if ($remarketing_ga4_dl_netpeak) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			 </label>
            </div>
          </div> 
           <legend><?php echo $text_ga4_send_way; ?> Measurement Procotol</legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_only_purchase; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_only_purchase" <?php if ($remarketing_ga4_only_purchase) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_ga4_mp_status" <?php if ($remarketing_ga4_mp_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div> 
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_api_secret; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_ga4_mp_api_secret" value="<?php echo $remarketing_ga4_mp_api_secret; ?>" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_ga4_analytics_id; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_ga4_analytics_id" value="<?php echo $remarketing_ga4_analytics_id; ?>" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_remarketing_ga4_send_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_ga4_send_status)) { ?>
                       <input type="checkbox" name="remarketing_ga4_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_ga4_send_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_remarketing_ga4_refund_status; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($order_statuses as $order_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($order_status['order_status_id'], $remarketing_ga4_refund_status)) { ?>
                       <input type="checkbox" name="remarketing_ga4_refund_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                       <?php echo $order_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_ga4_refund_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                       <?php echo $order_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
			 <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_resend_status; ?></label>
                <div class="col-sm-10">
                    <select name="remarketing_ga4_resend_status" class="form-control">
					  <option value="0"><?php echo $text_not_selected; ?></option>
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $remarketing_ga4_resend_status) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
            </div>
		  </div>
		  <div class="tab-pane" id="tab-counters">
		   <legend><img src="view/image/remarketing/rem_counters.png"> <?php echo $text_counters; ?></legend>
		   <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_counter1; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_counter1" rows="15" class="form-control"><?php echo $remarketing_counter1; ?></textarea>
               </div>
		   </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_counter2; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_counter2" rows="5" class="form-control"><?php echo $remarketing_counter2; ?></textarea>
               </div>
		   </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_counter3; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_counter3" rows="10" class="form-control"><?php echo $remarketing_counter3; ?></textarea>
               </div>
		   </div>
		   	<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_counter_bot; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_counter_bot" rows="10" class="form-control"><?php echo $remarketing_counter_bot; ?></textarea>
               </div>
		   </div>
		  </div>
		  <div class="tab-pane" id="tab-feed">
		   <legend><img src="view/image/remarketing/rem_feed.png"> <?php echo $text_feed; ?></legend>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
            <div class="col-sm-10"> 
             <label class="switch">
				<input type="checkbox" name="remarketing_feed_status" <?php if ($remarketing_feed_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			 </label>
            </div> 
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_identifier; ?></label>
            <div class="col-sm-10">
              <select name="remarketing_feed_identifier" class="form-control">
				<option value="product_id" <?php if ($remarketing_feed_identifier == 'product_id') { ?>selected="selected"<?php } ?>><?php echo $text_id; ?></option>
                <option value="model" <?php if ($remarketing_feed_identifier == 'model') { ?>selected="selected"<?php } ?>><?php echo $text_model; ?></option>
              </select> 
            </div>
          </div>
		  <div class="form-group">
		  <label class="control-label col-sm-2"><?php echo $entry_feed_currency; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_feed_currency" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_feed_currency) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		  <div class="form-group">
		  <label class="control-label col-sm-2"><?php echo $entry_feed_currency_base; ?></label>
		  <div class="col-sm-10">
			<select name="remarketing_feed_currency_base" class="form-control">
				<?php foreach ($currencies as $currency) { ?>
				<?php if ($currency['code'] == $remarketing_feed_currency_base) { ?>
				<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title'] . ' - ' . number_format((float)$currency['value'], 2, '.', ''); ?></option>
				<?php } else { ?>
				<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title'] . ' - ' . number_format((float)$currency['value'], 2, '.', ''); ?></option>
				<?php } ?>
				<?php } ?>
			</select>
		 </div>
		 </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_key; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_key" value="<?php echo $remarketing_feed_key; ?>" class="form-control" />
				  <?php if (empty($remarketing_feed_key)) { ?>
				  <br><span class="feed-key-text"><?php echo $text_feed_generate; ?></span> 
				  <script>function generateKey(){$('input[name="remarketing_feed_key"]').removeAttr('onclick').val(Array.from({length: 20},()=>'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'.charAt(Math.floor(Math.random()*62))).join(''));$('.gen-button').html('<i class="fa fa-check"></i> OK').css('background-color', 'green');setTimeout(function(){$('.feed-key-text').remove()},2000);}</script>
				  <?php } ?>
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_links; ?></label>
               <div class="col-sm-10 feed-links">
               		<b><img src="view/image/remarketing/rem_feed.png"><?php echo $text_feed_merchant; ?></b> <a href="<?php echo $link_merchant; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?>" target="_blank"><b><?php echo $link_merchant; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?></b></a><br>
					<b><img src="view/image/remarketing/rem_fb.png"><?php echo $text_feed_facebook; ?></b> <a href="<?php echo $link_facebook; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?>" target="_blank"><b><?php echo $link_facebook; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?></b></a><br>
					<b><img src="view/image/remarketing/rem_tiktok.png"><?php echo $text_feed_tiktok; ?></b> <a href="<?php echo $link_tiktok; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?>" target="_blank"><b><?php echo $link_tiktok; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?></b></a><br>
					<b><img src="view/image/remarketing/rem_esputnik.png"><?php echo $text_feed_esputnik; ?></b> <a href="<?php echo $link_esputnik; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?>" target="_blank"><b><?php echo $link_esputnik; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?></b></a><br>
					<b><img src="view/image/remarketing/rem_gads.png"><?php echo $text_feed_ads; ?></b> <a href="<?php echo $link_ads; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?>" target="_blank"><b><?php echo $link_ads; ?><?php echo $remarketing_feed_key ? '&key=' . $remarketing_feed_key : ''; ?></b></a><br><br>
               </div>
			</div>
			<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-remarketing-prices" data-toggle="tab"><?php echo $entry_feed_tab_price; ?></a></li>
			<li><a href="#tab-remarketing-available" data-toggle="tab"><?php echo $entry_feed_tab_stock; ?></a></li>
			<li><a href="#tab-remarketing-data" data-toggle="tab"><?php echo $entry_feed_tab_data; ?></a></li>
			<li><a href="#tab-remarketing-options" data-toggle="tab"><?php echo $entry_feed_tab_options; ?></a></li>
			<li><a href="#tab-remarketing-categories" data-toggle="tab"><?php echo $entry_feed_tab_categories; ?></a></li>
			<li><a href="#tab-remarketing-other" data-toggle="tab"><?php echo $entry_feed_tab_other; ?></a></li>
			</ul>
			<div class="tab-content">
			<div class="tab-pane active" id="tab-remarketing-prices">
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_multiplier; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_multiplier" value="<?php echo $remarketing_feed_multiplier; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
				<div class="col-sm-10">
				<select name="remarketing_feed_customer_group" class="form-control">
				<?php foreach ($customer_groups as $customer_group) { ?>
					<?php if ($customer_group['customer_group_id'] == $remarketing_feed_customer_group) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
				<?php } ?>
				<?php } ?>
				</select>
			</div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_special; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_special" <?php if ($remarketing_feed_special) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
            </div> 
			<div class="form-group hidden">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_auto_min_price; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_auto_min_price" <?php if ($remarketing_feed_auto_min_price) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_min_price; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_min_price" value="<?php echo $remarketing_feed_min_price; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_max_price; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_max_price" value="<?php echo $remarketing_feed_max_price; ?>" class="form-control" />
               </div>
			</div>
			</div>
		  <div class="tab-pane" id="tab-remarketing-available">
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_zero_quantity; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_zero_quantity" <?php if ($remarketing_feed_zero_quantity) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_always_avail; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_always_avail" <?php if ($remarketing_feed_always_avail) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_export_in_stock; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($stock_statuses as $stock_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($stock_status['stock_status_id'], $remarketing_feed_export_in_stock)) { ?>
                       <input type="checkbox" name="remarketing_feed_export_in_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" checked="checked" />
                       <?php echo $stock_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_feed_export_in_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" />
                       <?php echo $stock_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_export_out_of_stock; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($stock_statuses as $stock_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($stock_status['stock_status_id'], $remarketing_feed_export_out_of_stock)) { ?>
                       <input type="checkbox" name="remarketing_feed_export_out_of_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" checked="checked" />
                       <?php echo $stock_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_feed_export_out_of_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" />
                       <?php echo $stock_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_in_stock; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($stock_statuses as $stock_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($stock_status['stock_status_id'], $remarketing_feed_in_stock)) { ?>
                       <input type="checkbox" name="remarketing_feed_in_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" checked="checked" />
                       <?php echo $stock_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_feed_in_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" />
                       <?php echo $stock_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_out_of_stock; ?></label>
               <div class="col-sm-10">
                 <div class="well well-sm" style="height: 150px; overflow: auto;">
                   <?php foreach ($stock_statuses as $stock_status) { ?>
                   <div class="checkbox">
                     <label>
                       <?php if (in_array($stock_status['stock_status_id'], $remarketing_feed_out_of_stock)) { ?>
                       <input type="checkbox" name="remarketing_feed_out_of_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" checked="checked" />
                       <?php echo $stock_status['name']; ?>
                       <?php } else { ?>
                       <input type="checkbox" name="remarketing_feed_out_of_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" />
                       <?php echo $stock_status['name']; ?>
                       <?php } ?>
                     </label>
                   </div>
                   <?php } ?>
                 </div>
               </div>
             </div>
			</div>
		<div class="tab-pane" id="tab-remarketing-data">
		<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_condition; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_condition" value="<?php echo $remarketing_feed_condition; ?>" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_gtin; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_gtin" value="<?php echo $remarketing_feed_gtin; ?>" class="form-control" />
               </div>
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_mpn; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_mpn" value="<?php echo $remarketing_feed_mpn; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_highlight; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_highlight" value="<?php echo $remarketing_feed_highlight; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_replace_name; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_replace_name" value="<?php echo $remarketing_feed_replace_name; ?>" class="form-control" />
               </div>
		  </div> 
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_replace_description; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_replace_description" value="<?php echo $remarketing_feed_replace_description; ?>" class="form-control" />
               </div>
		  </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_original_description; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_original_description" <?php if ($remarketing_feed_original_description) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			<div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_rich_text; ?></label>
            <div class="col-sm-10">
              <label class="switch"> 
				<input type="checkbox" name="remarketing_feed_rich_text" <?php if ($remarketing_feed_rich_text) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
 			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_empty_brand; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_empty_brand" value="<?php echo $remarketing_feed_empty_brand; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_description; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_feed_description" rows="5" class="form-control"><?php echo $remarketing_feed_description; ?></textarea>
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_short_desc; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_short_desc" value="<?php echo $remarketing_feed_short_desc; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_replace_from; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_feed_replace_from" rows="5" class="form-control"><?php echo $remarketing_feed_replace_from; ?></textarea>
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_replace_to; ?></label>
               <div class="col-sm-10">
				    <textarea name="remarketing_feed_replace_to" rows="5" class="form-control"><?php echo $remarketing_feed_replace_to; ?></textarea>
               </div>
			</div>
			</div>
			<div class="tab-pane" id="tab-remarketing-options">
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_color; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_color" value="<?php echo $remarketing_feed_color; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_size; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_size" value="<?php echo $remarketing_feed_size; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_material; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_material" value="<?php echo $remarketing_feed_material; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_gender; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_gender" value="<?php echo $remarketing_feed_gender; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_age_group; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_age_group" value="<?php echo $remarketing_feed_age_group; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_export_options; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_export_options" <?php if ($remarketing_feed_export_options) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_option_color; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_option_color" value="<?php echo $remarketing_feed_option_color; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_option_size; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_option_size" value="<?php echo $remarketing_feed_option_size; ?>" class="form-control" />
               </div>
		  </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_all_attributes; ?></label>
            <div class="col-sm-10">
              <label class="switch"> 
				<input type="checkbox" name="remarketing_feed_all_attributes" <?php if ($remarketing_feed_all_attributes) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_adult; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_adult" <?php if ($remarketing_feed_adult) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			</div>
			<div class="tab-pane" id="tab-remarketing-categories">
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_ocstore_main; ?></label>
            <div class="col-sm-10">
			<label class="switch">
				<input type="checkbox" name="remarketing_feed_ocstore_main" <?php if ($remarketing_feed_ocstore_main) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>    
			</div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_last_category; ?></label>
            <div class="col-sm-10">
             <label class="switch">
				<input type="checkbox" name="remarketing_feed_last_category" <?php if ($remarketing_feed_last_category) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_type_category; ?></label>
            <div class="col-sm-10">
             <label class="switch">
				<input type="checkbox" name="remarketing_feed_type_category" <?php if ($remarketing_feed_type_category) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_tuning; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_tuning" <?php if ($remarketing_feed_tuning) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
		  	<div class="form-group <?php if (!$remarketing_feed_tuning) echo 'hidden'; ?>">
             <label class="col-sm-2 control-label"><?php echo $entry_category; ?>
			 <br>
			 <a href="https://www.google.com/basepages/producttype/taxonomy-with-ids.ru-RU.xls" target="_blank"><?php echo $text_category_google; ?></a><br><br>
			 <a href="https://support.google.com/merchants/answer/6324436?hl=ru" target="_blank">google_product_category HELP</a><br>
			 <a href="https://support.google.com/merchants/answer/6324406?hl=ru" target="_blank">product_type HELP</a><br>
			 <a href="https://support.google.com/merchants/answer/6324469?hl=ru" target="_blank">condition HELP</a><br>
			 <a href="https://support.google.com/google-ads/answer/6275295?hl=ru" target="_blank"> custom_label HELP</a>
			 </label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="max-height: 900px; overflow: auto;">
                    <table class="table table-striped">
                    <?php foreach ($categories as $category) { ?>
                    <tr class="feed-category">
                      <td class="checkbox">
                        <label>
                          <?php if (in_array($category['category_id'], $remarketing_feed_category)) { ?>
                          <input type="checkbox" name="remarketing_feed_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                          <b><?php echo $category['name']; ?></b>
                          <?php } else { ?>
                          <input type="checkbox" name="remarketing_feed_category[]" value="<?php echo $category['category_id']; ?>" />
                          <b><?php echo $category['name']; ?></b>
                          <?php } ?>
                        </label>
						<table class="table table-striped table-bordered">
							<tr>
								<td class="text-left gpc">google_product_category <input type="text" name="remarketing_feed_category_google_category[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_google_category[$category['category_id']]) ? $remarketing_feed_category_google_category[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left gpt">product_type <input type="text" name="remarketing_feed_category_product_type[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_product_type[$category['category_id']]) ? $remarketing_feed_category_product_type[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">condition <input type="text" name="remarketing_feed_category_condition[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_condition[$category['category_id']]) ? $remarketing_feed_category_condition[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_0 <input type="text" name="remarketing_feed_category_custom_label_0[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_custom_label_0[$category['category_id']]) ? $remarketing_feed_category_custom_label_0[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_1 <input type="text" name="remarketing_feed_category_custom_label_1[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_custom_label_1[$category['category_id']]) ? $remarketing_feed_category_custom_label_1[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_2 <input type="text" name="remarketing_feed_category_custom_label_2[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_custom_label_2[$category['category_id']]) ? $remarketing_feed_category_custom_label_2[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_3 <input type="text" name="remarketing_feed_category_custom_label_3[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_custom_label_3[$category['category_id']]) ? $remarketing_feed_category_custom_label_3[$category['category_id']] : ''); ?>" class="form-control"/></td>
								<td class="text-left">custom_label_4 <input type="text" name="remarketing_feed_category_custom_label_4[<?php echo $category['category_id']; ?>]" value="<?php echo (!empty($remarketing_feed_category_custom_label_4[$category['category_id']]) ? $remarketing_feed_category_custom_label_4[$category['category_id']] : ''); ?>" class="form-control"/></td>
							</tr>
						</table>
                      </td>
                    </tr>
                    <?php } ?>
                    </table>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a><br><br>
				  <a class="btn btn-primary" onclick="copyToGpc();"><?php echo $text_copy_to_category; ?></a><br><br>
				  <a class="btn btn-primary" onclick="copyToGpt();"><?php echo $text_copy_to_product_type; ?></a><br><br>
				  <?php if ($taxonomy_warning) { ?>
				  <div class="alert alert-danger taxonomy-alert"><i class="fa fa-exclamation-circle"></i> <?php echo $taxonomy_warning; ?>
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  </div>
				  <?php } ?>
				  <a class="btn btn-primary" onclick="tryFill('id');"><?php echo $text_try_fill_gc; ?></a><br><br>
				  <a class="btn btn-primary" onclick="tryFill('full_path');"><?php echo $text_try_fill_gc_name; ?></a><br>
				  </div>
          </div>
		  <div class="form-group <?php if (!$remarketing_feed_tuning) echo 'hidden'; ?>">
             <label class="col-sm-2 control-label"><?php echo $entry_manufacturer; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="max-height: 400px; overflow: auto;">
                    <table class="table table-striped">
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                    <tr>
                      <td class="checkbox">
                        <label>
                          <?php if (in_array($manufacturer['manufacturer_id'], $remarketing_feed_manufacturer)) { ?>
                          <input type="checkbox" name="remarketing_feed_manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                          <?php echo $manufacturer['name']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="remarketing_feed_manufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                          <?php echo $manufacturer['name']; ?>
                          <?php } ?>
                        </label>
                      </td>
                    </tr>
                    <?php } ?>
                    </table>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
			</div>
			<div class="tab-pane" id="tab-remarketing-other">
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_original_image_status; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_original_image_status" <?php if ($remarketing_feed_original_image_status) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div> 
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_additional_images; ?></label>
            <div class="col-sm-10">
              <label class="switch">
				<input type="checkbox" name="remarketing_feed_additional_images" <?php if ($remarketing_feed_additional_images) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			  </label>
            </div> 
          </div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_utm; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_utm" value="<?php echo $remarketing_feed_utm; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_utm_facebook; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_utm_facebook" value="<?php echo $remarketing_feed_utm_facebook; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_utm_tiktok; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_utm_tiktok" value="<?php echo $remarketing_feed_utm_tiktok; ?>" class="form-control" />
               </div> 
			</div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_feed_custom_sql; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_custom_sql" value="<?php echo $remarketing_feed_custom_sql; ?>" class="form-control" />
               </div>
			</div>
			<div class="form-group"> 
            <label class="col-sm-2 control-label"><?php echo $entry_feed_store_code; ?></label>
               <div class="col-sm-10">
                  <input type="text" name="remarketing_feed_store_code" value="<?php echo $remarketing_feed_store_code; ?>" class="form-control" />
               </div>
			</div>
			</div>
			</div>
		  </div> 
		  <div class="tab-pane" id="tab-debug">
		  <legend><img src="view/image/remarketing/rem_debug.png"> <?php echo $text_debug; ?></legend>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_debug_mode; ?></label>
            <div class="col-sm-10">
            <label class="switch">
				<input type="checkbox" name="remarketing_debug_mode" <?php if ($remarketing_debug_mode) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_debug_front_mode; ?></label>
            <div class="col-sm-10">
            <label class="switch">
				<input type="checkbox" name="remarketing_debug_front_mode" <?php if ($remarketing_debug_front_mode) { ?>checked<?php } ?>>
				<span class="slider round"></span>
			</label>
            </div>
          </div>
		  <legend><?php echo $text_db_info; ?></legend>
		  <?php if (!empty($db_info)) { ?>
		  <?php echo $text_db_size; ?> <?php echo $db_info['db_size']; ?> MB<br>
		  <?php echo $text_db_rows; ?> <?php echo $db_info['count_rows']; ?><br><br>
		  <?php } ?>
		  <a class="btn btn-danger db-clear"><i class="fa fa-eraser"></i> <?php echo $text_db_clear; ?></a>
		  <br>
		  <br>
		  <legend><?php echo $text_view_logs; ?></legend>
		  <div class="log-files">
		  <?php foreach ($logs as $log) { ?>
		  <a href="<?php echo $log['view']; ?>" target="_blank"><?php echo $log['name']; ?></a><br>
		  <?php } ?>
		  </div>
		  <br>
		  <a class="btn btn-danger logs-clear"><i class="fa fa-eraser"></i> <?php echo $text_file_logs_clear; ?></a>
		  <br>
		  <br>
		  <legend><?php echo $text_search_in_files; ?></legend>
		  <a class="btn btn-primary search-in-files"><i class="fa fa-search"></i> <?php echo $text_start_search; ?></a> 
		  <br><br><div class="file-search-results"></div>
		  <br>
		  <br>
		  <legend><?php echo $text_hard_reset; ?></legend>
		  <a class="btn btn-danger ajax-save hard-reset"><i class="fa fa-power-off"></i> HARD RESET (USE ONLY IN EXTREME CASES)</a>
		</div>
		  </div>
		  </div>
        </form>
      </div>
	  <div class="panel-heading sticky-heading">
      <div class="panel-title"><a class="btn btn-primary ajax-save"><i class="fa fa-rocket"></i> <?php echo $text_apply; ?></a> <a class="btn btn-primary ajax-save refresh"><i class="fa fa-rocket"></i> <?php echo $text_apply_and_refresh; ?></a></div>
      </div>
    </div>
  </div>
</div>
<div id="loading-screen" class="hidden"> 
    <div class="spinner"> </div><div class="loading-text"><?php echo $text_processing; ?></div>
</div>
<script>
$(document).on('click', '.magic-button', function() {
	currency = $('#quick_currency').val();
	ga4_id = $('#ga4_id').val();
	ads_id = $('#ads_id').val();
	gtm_id = $('#gtm_id').val();
	fb_id = $('#facebook_id').val();
	if (ga4_id == '' && ads_id == '' && gtm_id == '' && fb_id == '') {
		$('.magic-error').show();
		return;
	}
	$('[name="remarketing_status"]').prop('checked', true);
	gtag_text = '';
    if (fb_id != '') {
		$('[name="remarketing_facebook_status"], [name="remarketing_facebook_script_status"], [name="remarketing_facebook_pixel_status"], [name="remarketing_facebook_lead"]').prop('checked', true);
		$('[name="remarketing_facebook_currency"]').val(currency).trigger('change');
		$('[name="remarketing_facebook_identifier"]').val(fb_id);
	}
    if (ga4_id != '') {
		$('[name="remarketing_ga4_status"]').prop('checked', true);
		$('[name="remarketing_ga4_currency"]').val(currency).trigger('change');
		$('[name="remarketing_ga4_identifier"]').val(ga4_id);
	}
    if (ads_id != '') {
		$('[name="remarketing_google_status"]').prop('checked', true);
		$('[name="remarketing_google_currency"]').val(currency).trigger('change');
		$('[name="remarketing_google_identifier"]').val(ads_id);
	}
    if (gtm_id != '') {
		var gtm_head_tag = "\n&lt;!-- Google Tag Manager --&gt;&lt;script&gt;(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" + gtm_id + "');&lt;/script&gt;&lt;!-- End Google Tag Manager --&gt;";
		var gtm_body_tag = "\n&lt;!-- Google Tag Manager (noscript) --&gt;&lt;noscript&gt;&lt;iframe src='https://www.googletagmanager.com/ns.html?id=" + gtm_id + "' height='0' width='0' style='display:none;visibility:hidden'&gt;&lt;/iframe&gt;&lt;/noscript&gt;&lt;!-- End Google Tag Manager (noscript) --&gt;\n";
		$('[name="remarketing_counter1"]').append(gtm_head_tag); 
		$('[name="remarketing_counter2"]').append(gtm_body_tag); 
	}
	if (ga4_id != '' || ads_id != '') {
		if (ga4_id != '') {
			gtag_id = ga4_id;
		}
		if (ads_id != '' && ga4_id == '') {
			gtag_id = ads_id;
		}
		gtag_text += "\n&lt;!-- Google tag (gtag.js) --&gt;&lt;script async src='https://www.googletagmanager.com/gtag/js?id=" + gtag_id + "'&gt;&lt;/script&gt;&lt;script&gt;window.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);} gtag('js', new Date());\ngtag('config', '" + gtag_id + "');\n";
		if (ga4_id != '' && ads_id != '') {  
			gtag_text += "gtag('config', '" + ads_id + "', {'allow_enhanced_conversions':true});\n";
		}
		gtag_text += "&lt;/script&gt;"; 
		$('[name="remarketing_counter1"]').append(gtag_text);
	}			  
	
	$(this).text('Wait for magic :)');
	$('#loading-screen').removeClass('hidden');			  
	setTimeout(function() {
		$('.ajax-save.refresh').trigger('click');
	}, 2000);
});

$(document).on('click', '.copy-data', function() {
	let btn = $(this);
	let originalText = btn.html();
	let text = $(".license-info.order-data p").map(function(){return $(this).html().replace(/<br\s*\/?>/gi, "\n").replace(/<\/?[^>]+(>|$)/g, "").trim();}).get().join("\n");
	if (navigator.clipboard && window.isSecureContext) { 
		navigator.clipboard.writeText(text).then(() => {
			btn.html('<?php echo $text_copy_success; ?>');
			setTimeout(() => btn.html(originalText), 3000);
		});
	} else {
		let temp = $('textarea>').css({position: 'absolute', left: '-9999px'});
		$('body').append(temp);
		temp.val(text).select(); 
		if (document.execCommand('copy')) {
			btn.html('<?php echo $text_copy_success; ?>');
			setTimeout(() => btn.html(originalText), 3000);
		}
		temp.remove();
	}
});

$(document).on('click','.test-facebook',function(){$.ajax({type:'post',url:'<?php echo $test_facebook; ?>',data:'event_name=Contact',dataType:'json',success:function(json){$('.facebook_result').html(json.success?'<br><div style="background:green;width:auto;display:inline-block;color:#fff;padding:10px;font-weight:bold;">TEST OK!</div>':'<br><div style="background:red;width:auto;display:inline-block;color:#fff;padding:10px;font-weight:bold;">TEST FAILED! '+(json.error?json.error:'')+'</div>')}});});
$(document).on('click','.test-tg',function(){$.ajax({type:'post',url:'<?php echo $test_tg; ?>',dataType:'json',success:function(json){$('.tg_result').html(json.success?'<br><div style="background:green;width:auto;display:inline-block;color:#fff;padding:10px;font-weight:bold;">TEST OK!</div>':'<br><div style="background:red;width:auto;display:inline-block;color:#fff;padding:10px;font-weight:bold;">TEST FAILED! '+(json.error?json.error:'')+'</div>')}});});
 
$(document).on('click', '.load-taxonomy', function() {
	$.ajax({ 
		type: 'post',
		url:  '<?php echo $load_taxonomy; ?>',
		data: {}, 
		dataType: 'text',
		beforeSend: function() {
			$('#loading-screen').removeClass('hidden');
		},
		complete: function() { 
		}, 
		success: function(response) { 
			if (response != '') {
				$('.taxonomy-alert').hide();
				setTimeout(function() {$('#loading-screen').addClass('hidden')}, 2000);
			}
		}
	});
}); 

$('input[name*=\'remarketing_feed_category_google_category\']').autocomplete({
	'minLength': 3,  
	'source': function(request, response) {
		if (request.length < 3 || request.indexOf('>') !== -1) { 
			return; 
		}
		$.ajax({
			url: 'index.php?route=extension/module/remarketing/gcautocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['full_path'],
						value: item['full_path']
					};
				}));
			}
		});
	},
	'focus': function(event, ui) {
		event.preventDefault();
	},
	'select': function(item) { 
		if (item.label) { 
			$(this).val(item.label);  
		} else {
			
		}
	}
});

function tryFill(response_type) {
	$.ajax({ 
		type: 'post',
		url:  '<?php echo $try_fill; ?>',
		data: 'response_type=' + response_type,
		dataType: 'json',
		beforeSend: function() {
			$('#loading-screen').removeClass('hidden');
		},
		complete: function() {
			$('#loading-screen').addClass('hidden');
		}, 
		success: function(json) { 
			if (json['success']) {
				$.each(json['categories'], function(index, category) {
					$('input[name="remarketing_feed_category_google_category[' + index + ']"]').val((response_type == 'product_id') ? category.id : category.full_path);
				});
			} 
		}
	});
}

function copyToGpc() {
	$('.feed-category').each(function() {
		$(this).find('.gpc input').val($(this).find('.checkbox b').text());
	})
}

function copyToGpt() {
	$('.feed-category').each(function() {
		$(this).find('.gpt input').val($(this).find('.checkbox b').text());
	})
}

$(document).on('click', '.db-clear', function() {
	if (confirm('<?php echo $text_clear_database_warning; ?>')) {
		$.ajax({ 
			type: 'post',
			url:  '<?php echo $db_clear; ?>', 
			dataType: 'json',
			beforeSend: function() {
				$('#loading-screen').removeClass('hidden');
			},
			complete: function() {
				setTimeout(function() {$('#loading-screen').addClass('hidden')}, 3000);
			}, 
			success: function(json) { 
				if (json['success']) {
					$('.db-clear').html('<?php echo $text_success_db_clear; ?>');
				} 
			}
		});
	} 
});

$(document).on('click', '.logs-clear', function() {
	$.ajax({ 
		type: 'post',
		url:  '<?php echo $logs_clear; ?>', 
		dataType: 'json',
		beforeSend: function() {
			$('#loading-screen').removeClass('hidden');
		},
		complete: function() {
			setTimeout(function() {$('#loading-screen').addClass('hidden')}, 3000);
		}, 
		success: function(json) { 
			if (json['success']) {
				$('.logs-clear').html('<?php echo $text_success_logs_clear; ?>');
				$('.log-files').remove();
			} 
		}
	});
	
});

$(document).on('click', '.search-in-files', function() {
	$.ajax({ 
		type: 'post',
		url:  '<?php echo $search_in_files; ?>', 
		dataType: 'json',
		beforeSend: function() {
			$('#loading-screen').removeClass('hidden');
		},
		complete: function() {
			setTimeout(function() {$('#loading-screen').addClass('hidden')}, 3000);
		}, 
		success: function(json) { 
			if (json['success']) {
				$('.file-search-results').html(json['html']);
			} 
		}
	});
});

$(document).ready(function () { 
    if (window.location.hash) {
		$('.nav.nav-pills a[href="' + window.location.hash + '"]').tab('show');
    } 
    $(document).on('click', '.nav.nav-pills a[data-toggle="tab"]', function () {
		var hash = this.hash;
		var new_url = window.location.href.split('#')[0] + hash;
		if (history.pushState) {
			history.pushState(null, null, new_url);
		} else {
			window.location.hash = hash;
		}
    });
});

$(document).on('click', '.ajax-save', function() {
	refresh = $(this).hasClass('refresh');
	form_data = $('#form-remarketing').serialize();
	if ($(this).hasClass('hard-reset')) {
		if (confirm('ARE YOU SURE?')) {
			form_data = []; refresh = true;
		} else {
			alert('RESET CANCELED'); return;
		}
	}
	$.ajax({ 
		type: 'post',
		url:  '<?php echo $save_ajax; ?>',
		data: form_data,
		dataType: 'json',
		beforeSend: function() {
            $('#loading-screen').removeClass('hidden');$('.ajax-save').attr('disabled', true).find('i.fa').removeClass('fa-rocket').addClass('fa-spinner fa-pulse');
        },
        complete: function() { 
			setTimeout(function() {$('.ajax-save').attr('disabled', false).find('i.fa').removeClass('fa-spinner fa-pulse').addClass('fa-rocket');$('#loading-screen').addClass('hidden');if(refresh){history.pushState('', document.title, window.location.pathname + window.location.search);history.scrollRestoration='manual';window.location.reload()}}, 3000); 
        }, 
		success: function(json) { 
			if (json['success']) {
				console.log(json['success']);
			} 
		}
	});
});
</script>
<style>
#content legend {
	color: #167d4b
}
#content .btn-primary {
	color: #fff;
	background-color: #167d4b;
	border-color: #167d4b;
}

#content .btn-primary:hover {
	background-color: #2a9b64; 
	border-color: #2a9b64;
}
 
#content .nav-pills > li.active > a, #content .nav-pills > li.active > a:focus {
	background: #b1f2b1 !important;
	font-weight: bold;
	color: #167d4b !important;
} 

#content .nav-pills > li.active > a:hover {
	background: #b1f2b1 !important;
	font-weight: bold;
	color: #167d4b !important;
} 

#content .nav-pills > li.active > a.diag, #content .nav-pills > li > a.diag, a.diag:hover {
    background: #167d4b !important;
    color: #fff !important;
}

.nav-stacked img, .quick-form img {
	display: inline;
	width:30px !important; 
}

legend img {
	display:inline;
	width:50px !important; 
}

.quick-form .control-label {
	padding-right: 0px;
}

.quick-form img {
	margin-top: -5px;
}

.config-summary span {
	font-size: 20px;
	color: #0043ff;
	font-weight: bold;
}

.version {
    color: green;
	font-weight: bold;
}

.summary-heading {
	font-size: 20px;
	color: green;
	margin-bottom: 15px;
}

.enabled, .enabled:hover {
	background: #c7ffc7 !important;
    font-weight: bold;
}

.version-update {
	background: #c7ffc7 !important;
    font-weight: bold;
	margin-left:15px;
}

legend {
	font-size: 27px;
	margin-top: 15px;
	font-weight: bold;
}

.help-link {
	font-size: 22px;
}

.help-link.doc-link {
	display: none;
}

.help-link a {
	color: #167d4b;
	font-weight: bold;
}

form .nav li a {
	font-weight: bold;
	padding: 10px;
	font-size: 14px;
}

.feed-links img {
	max-width: 36px;
	margin: 3px auto;
}

.switch {
	position: relative;
	display: inline-block;
	width: 60px;
	height: 34px;
}

.switch input {
	display:none;
}

.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ccc;
	-webkit-transition: .4s;
	transition: .4s;
}

.slider:before {
	position: absolute;
	content: "";
	height: 26px;
	width: 26px;
	left: 4px;
	bottom: 4px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
}

input:checked + .slider {
	background-color: #167d4b;
}

input:focus + .slider {
	box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
	-webkit-transform: translateX(26px);
	-ms-transform: translateX(26px);
	transform: translateX(26px);
}

.slider.round {
	border-radius: 34px;
}

.slider.round:before {
	border-radius: 50%;
}

.form-group select.form-control {
	text-align: left;
	max-width: 300px;
}

.form-group input[type="text"] {
	text-align: left;
	max-width: 300px;
}

.selectric-wrapper{
	text-align: left;
	max-width: 300px;
}

.sticky-heading {
    position: -webkit-sticky !important;
    position: sticky !important;
    bottom: 0;
    background-color: #f5f5f5;
    color: #fff !important;
    z-index: 10;
    padding: 10px;
	text-align:center;
    border-bottom: 1px solid #ddd;
}

.nav-pills > li.active::after {
	content: '\f105';
	font-family: 'FontAwesome';
	font-weight: 900;
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	left: -15px;
	color: #167d4b;
	font-size: 25px;
}

.not_activated {
	background: red;
	margin: 15px 0;
	padding: 10px;
	color: #fff;
}

.license-info, .legend-text {
	margin-top: 20px;
	padding: 15px; 
	background: #f9f9f9;
	border-left: 4px solid #167d4b;
	font-size: 20px;
}

.license-info a {
	font-weight: bold;
}

.nav-stacked a {
	color: #167d4b;
}

#form-remarketing a {
	color: #167d4b;
}

#form-remarketing a.btn-danger {
	color: #fff;
}

#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.spinner {
    width: 200px;
    height: 200px;
    border: 10px solid rgba(255, 0, 0, 0.3);
    border-top: 10px solid red;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loading-text {
	text-align: center;
    margin-top: 20px;
    font-size: 24px; 
    font-weight: bold;
    color: #ffffff;
    font-family: Arial, sans-serif;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.magic-button{background-color:#167d4b;color:white!important;padding:15px 30px;border:none;border-radius:12px;font-size:18px;font-weight:bold;cursor:pointer;position:relative;overflow:hidden;display:inline-block;animation:pulse 1.5s infinite ease-in-out;transition:transform 0.3s ease;min-width:150px;}.magic-button:hover{color:#fff;transform:scale(1.03);animation:none;}@keyframes pulse{0%{transform:scale(1);}50%{transform:scale(1.03);}100%{transform:scale(1);}}

</style>
<script> 
/* TEST AREA */
</script>
<?php echo $footer; ?>
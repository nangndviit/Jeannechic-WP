/* Javascript Document */
var product_countdown_html = '';
var product_orderbump_html = '';
var minicart_orderbump_html = '';
var product_badge_html = '';
function add_new_product_countdown(){
	global_radio_count = global_radio_count + 1;
	var html = product_countdown_html;
	html = html.replace('<!--DELETE-->','<a href="javascript:;" class="delete-countdown" onclick="delete_product_countdown(this);">'+global_delete_countdown+'</a>');
	html = html.replace(/name="radio-0"/g,'name="radio-'+global_radio_count+'"');
	jQuery('#product-countdown').append('<div class="postbox change">'+html+'</div>');
	jQuery('#product-countdown .change span.select2-container').remove();
	jQuery('#product-countdown .change select.select2').removeClass('select2-hidden-accessible').html('');
	jQuery('#product-countdown .change .product-ids select.select2').each(function(){
		add_select_select2(jQuery(this))
	});
	jQuery('#product-countdown .change .cgkit-date').removeClass('hasDatepicker').removeAttr('id');
	jQuery('#product-countdown .change .cgkit-date').datepicker({dateFormat:'yy-mm-dd'});
	jQuery('#product-countdown .change').removeClass('change').addClass('no-change');
	jQuery('#product-countdown').sortable('refresh');
	if( jQuery('#product-countdown .postbox').length > 1 ){
		jQuery('#ctd-order-notice').css('display', 'block');
	} else {
		jQuery('#ctd-order-notice').css('display', 'none');
	}
	validate_countdown_timers();
}
function delete_product_countdown(obj){
	if( confirm(global_delete_countdown_confirm) ){
		jQuery(obj).closest('div.postbox').remove();
		if( jQuery('#product-countdown .postbox').length > 1 ){
			jQuery('#ctd-order-notice').css('display', 'block');
		} else {
			jQuery('#ctd-order-notice').css('display', 'none');
		}
		validate_countdown_timers();
	}
}
function add_select_select2(obj){
	var type = obj.data('type');
	var placeholder = obj.data('placeholder');
	var tab = obj.data('tab');
	var mode = obj.data('mode');
	obj.select2({
		placeholder: placeholder,
		ajax: {
			url: ajaxurl,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term,
					type: type,
					tab: tab,
					mode: mode,
					action: 'commercekit_get_pcids'
				};
			},
			processResults: function( data ) {
				var options = [];
				if ( data ) {
					jQuery.each( data, function( index, text ) {
						options.push( { id: text[0], text: text[1] } );
					});
				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3
	});
}
function add_new_order_bump(){
	var html = product_orderbump_html;
	html = html.replace('<!--DELETE-->','<a href="javascript:;" class="delete-orderbump" onclick="delete_product_orderbump(this);">'+global_delete_orderbump+'</a>');
	jQuery('#product-orderbump').append('<div class="postbox change">'+html+'</div>');
	jQuery('#product-orderbump .change span.select2-container').remove();
	jQuery('#product-orderbump .change select.select2').removeClass('select2-hidden-accessible').html('');
	jQuery('#product-orderbump .change select.select2').each(function(){
		add_select_select2(jQuery(this))
	});
	jQuery('#product-orderbump .change').removeClass('change').addClass('no-change');
	jQuery('#product-orderbump').sortable('refresh');
	validate_orderbump_products();
}
function add_new_order_bump_mini(){
	var html = minicart_orderbump_html;
	html = html.replace('<!--DELETE-->','<a href="javascript:;" class="delete-orderbump" onclick="delete_product_orderbump(this);">'+global_delete_orderbump+'</a>');
	jQuery('#minicart-orderbump').append('<div class="postbox change">'+html+'</div>');
	jQuery('#minicart-orderbump .change span.select2-container').remove();
	jQuery('#minicart-orderbump .change select.select2').removeClass('select2-hidden-accessible').html('');
	jQuery('#minicart-orderbump .change select.select2').each(function(){
		add_select_select2(jQuery(this))
	});
	jQuery('#minicart-orderbump .change').removeClass('change').addClass('no-change');
	jQuery('#minicart-orderbump').sortable('refresh');
	validate_orderbump_products();
}
function validate_countdown_timers(){
	if( jQuery('#product-countdown').length > 0 ){
		var can_submit = true;
		jQuery('#product-countdown input.required').each(function(){
			var $this = jQuery(this);
			if( $this.hasClass('error') ){
				$this.removeClass('error');
				$this.parent().parent().find('.input-error').remove();
			}
			if( $this.val() == '' ){
				$this.addClass('error');
				$this.parent().parent().append('<div class="input-error">'+global_required_text+'</div>');
				can_submit = false;
				var box = $this.closest('.postbox');
				if( box.hasClass('closed') ) 
					box.removeClass('closed');
				$this.focus();
				return false;
			}
		});
		if( !can_submit ){
			jQuery('#btn-submit').attr('disabled', 'disabled');
		} else {
			jQuery('#btn-submit').removeAttr('disabled');
		}
	}
}
function delete_product_orderbump(obj){
	if( confirm(global_delete_orderbump_confirm) ){
		jQuery(obj).closest('div.postbox').remove();
		validate_orderbump_products();
	}
}
function validate_orderbump_products(){
	if( jQuery('select.order-bump-product').length > 0 ){
		var can_submit = true;
		jQuery('select.order-bump-product').each(function(){
			var $this = jQuery(this);
			if( $this.find('option').length == 0 ){
				if( !$this.hasClass('error') ){
					$this.addClass('error');
					var $parent = $this.parent().parent();
					$parent.find('.select2-selection').addClass('error');
					$parent.append('<div class="input-error">'+global_required_text+'</div>');
				}
				var box = $this.closest('.postbox');
				if( box.hasClass('closed') ) 
					box.removeClass('closed');
				can_submit = false;
				return false;
			} else {
				$this.removeClass('error');
				var $parent = $this.parent().parent();
				$parent.find('.select2-selection').removeClass('error');
				$parent.find('.input-error').remove();
			}
		});
		if( can_submit ) {
			jQuery('table.admin-order-bump input.required').each(function(){
				var $this = jQuery(this);
				if( $this.hasClass('error') ){
					$this.removeClass('error');
					$this.parent().find('.input-error').remove();
				}
				if( $this.val() == '' ){
					$this.addClass('error');
					$this.after('<div class="input-error">'+global_required_text+'</div>');
					can_submit = false;
					var box = $this.closest('.postbox');
					if( box.hasClass('closed') ) 
						box.removeClass('closed');
					$this.focus();
					return false;
				}
			});
		}
		if( !can_submit ){
			jQuery('#btn-submit').attr('disabled', 'disabled');
		} else {
			jQuery('#btn-submit').removeAttr('disabled');
		}
	}
}
jQuery(document).ready(function(){
	jQuery('body').on('change', 'input.pdt-title', function(){
		var h2 = jQuery(this).closest('.postbox').find('h2 > span');
		if( jQuery(this).val() != '' )
			h2.html(jQuery(this).val());
		else
			h2.html('Title');
	});
	jQuery('body').on('change', 'select.order-bump-product', function(){
		var h2 = jQuery(this).closest('.postbox').find('h2 > span');
		var val = jQuery(this).val();
		var title = jQuery(this).find('option:selected').text();
		title = title.replace( '#'+val+' - ', '' );
		if( title != '' )
			h2.html(title);
		else
			h2.html('Title');
	});
	jQuery('body').on('change', 'input.max-total', function(){
		var min_val = parseFloat(jQuery(this).closest('.cart-total').find('input.min-total').val());
		var max_val = parseFloat(jQuery(this).val());
		if( !isNaN(min_val) && !isNaN(max_val) && max_val < min_val ){
			jQuery(this).val(min_val);
		}
	});
	jQuery('body').on('click', 'button.handlediv, .postbox > h2.gray', function(){
		jQuery(this).parent().toggleClass('closed');
	});
	jQuery('body').on('change', 'select.conditions', function(){
		var pids = jQuery(this).closest('.postbox').find('.product-ids');
		var option3 = pids.find('.options');
		var select3 = pids.find('select.select2');
		var select4 = pids.find('input.select3');
		var cval = jQuery(this).val();
		if( cval == 'all' ) {
			pids.hide();
		} else if( cval == 'products' || cval == 'non-products' ) {
			pids.show(); 
			option3.html('Specific products:');
			select3.data('type', 'products');
		}  else if( cval == 'tags' || cval == 'non-tags' ) {
			pids.show(); 
			option3.html('Specific tags:');
			select3.data('type', 'tags');
		} else {
			pids.show(); 
			option3.html('Specific categories:');
			select3.data('type', 'categories');
		}
		select3.select2('destroy'); 
		select3.html('');
		select4.val('');
		add_select_select2(select3);
	});
	jQuery('body').on('change', 'select.select2', function(){
		var pids = jQuery(this).closest('.postbox').find('.product-ids');
		var select3 = pids.find('select.select2');
		var select4 = pids.find('input.select3');
		var selvals = select3.val();
		if( selvals instanceof Array )
			select4.val(selvals.join(','));
	});
	jQuery('select.select2').each(function(){
		add_select_select2(jQuery(this))
	});
	jQuery('body').on('change', 'input.pdt-type', function(){
		var td = jQuery(this).closest('td');
		td.find('input.pdt-type-val').val(jQuery(this).val());
		var dates = jQuery(this).closest('.postbox').find('.product-dates');
		var ends = jQuery(this).closest('.postbox').find('.end-inputs');
		if( jQuery(this).val() == 2 ){
			dates.css('display', '');
			dates.find('.cgkit-date').addClass('required');
			ends.addClass('disable-events');
		} else {
			dates.css('display', 'none');
			dates.find('.cgkit-date').removeClass('required');
			ends.removeClass('disable-events');
		}
		validate_countdown_timers();
	});
	jQuery('#product-countdown .no-change .cgkit-date').datepicker({dateFormat:'yy-mm-dd'});
	jQuery('body').on('change', 'input.pdt-active', function(){
		var td = jQuery(this).closest('td');
		if( jQuery(this).prop('checked') )
			td.find('input.pdt-active-val').val(1);
		else
			td.find('input.pdt-active-val').val(0);
	});
	jQuery('body').on('change', 'input.pdt-hide-timer', function(){
		var tval = jQuery(this).closest('td').find('input.pdt-hide-timer-val');
		var cmsg = jQuery(this).closest('.postbox').find('.timer-custom-message');
		if( jQuery(this).prop('checked') ){
			tval.val(1);
			cmsg.show();
		} else {
			tval.val(0);
			cmsg.hide();
		}
	});
	jQuery('body').on('change', '#commercekit_inventory_display, #commercekit_ajax_search, #commercekit_ajs_hidevar, #commercekit_waitlist, #commercekit_wishlist, #commercekit_countdown_timer, #commercekit_order_bump, #commercekit_order_bump_mini, #commercekit_pdp_triggers, #commercekit_pdp_gallery, #commercekit_pdp_lightbox, #commercekit_pdp_attributes_gallery, #commercekit_pdp_video_autoplay, #commercekit_pdp_mobile_optimized, #commercekit_pdp_thumb_arrows, #commercekit_attribute_swatches, #commercekit_attribute_swatches_pdp, #commercekit_attribute_swatches_plp, #commercekit_as_activate_atc, #commercekit_as_logger, #commercekit_as_enable_tooltips, #commercekit_as_disable_facade, #commercekit_sticky_atc_desktop, #commercekit_sticky_atc_mobile, #commercekit_sticky_atc_tabs, #commercekit_fsn_cart_page, #commercekit_fsn_mini_cart, #commercekit_fsn_before_ship, #commercekit_size_guide, #commercekit_size_guide_search, #commercekit_store_badge, #commercekit_export_import_logger, #commercekit_ajs_index_logger', function(){
		if( jQuery(this).prop('checked') )
			jQuery(this).closest('section, tr').addClass('active');
		else
			jQuery(this).closest('section, tr').removeClass('active');
		jQuery('#ajax-loading-mask').show();
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: jQuery('#commercekit-form').serialize(),
			success: function( json ) {
				jQuery('#ajax-loading-mask').hide();
			}
		});
	});
	if( jQuery('#product-countdown #first-row').length > 0 ){
		product_countdown_html = jQuery('#product-countdown #first-row').html();
		jQuery('#product-countdown #first-row').remove();
	}
	jQuery('#product-countdown').sortable({handle:'h2.gray'});
	if( jQuery('#product-orderbump #first-row').length > 0 ){
		product_orderbump_html = jQuery('#product-orderbump #first-row').html();
		jQuery('#product-orderbump #first-row').remove();
	}
	if( jQuery('#minicart-orderbump #first-row-mini').length > 0 ){
		minicart_orderbump_html = jQuery('#minicart-orderbump #first-row-mini').html();
		jQuery('#minicart-orderbump #first-row-mini').remove();
	}
	jQuery('#product-orderbump').sortable({handle:'h2.gray'});
	jQuery('#minicart-orderbump').sortable({handle:'h2.gray'});
	jQuery('#screen_width').val(screen.width);
	jQuery('#screen_height').val(screen.height);
	if( jQuery('#product-countdown .postbox').length > 1 ){
		jQuery('#ctd-order-notice').css('display', 'block');
	} else {
		jQuery('#ctd-order-notice').css('display', 'none');
	}
	jQuery('body').on('change', 'select.order-bump-product, input.required', function(){
		validate_countdown_timers();
		validate_orderbump_products();
	});
	validate_countdown_timers();
	validate_orderbump_products();
	jQuery('#commercekit_ajs_excludes').bind('keyup blur', function(){
		var value = jQuery(this);
		value.val(value.val().replace(/[^0-9\,]/g,'') ); 
	});
	jQuery('table.admin-support #first_name').bind('keyup blur', function(){
		var value = jQuery(this);
		value.val(value.val().replace(/[^a-zA-Z\ ]/g,'') ); 
	});
	jQuery('#commercekit_pdp_thumbnails').bind('change', function(){
		var value = parseInt(jQuery(this).val());
		if( isNaN(value) ) value = 4;
		jQuery(this).val(value);
		if( value < 3 || value > 8 ){
			jQuery('#commercekit_pdp_thumbnails').addClass('error');
			jQuery('#pdp_thumbnails_error').show()
			jQuery('#btn-submit').attr('disabled', 'disabled');
		} else {
			jQuery('#commercekit_pdp_thumbnails').removeClass('error');
			jQuery('#pdp_thumbnails_error').hide()
			jQuery('#btn-submit').removeAttr('disabled');
		}
	});
	jQuery('#commercekit_pdp_gallery_layout').on('change', function(){
		jQuery('#gallery-layout-preview .layout-preview').hide();
		jQuery('#'+jQuery(this).val()+'-preview').show();
	});
	jQuery('#commercekit_pdp_gallery_layout').change();
	jQuery('#commercekit_attribute_swatches, #commercekit_attribute_swatches_plp, #commercekit_as_activate_atc').on('change', function(){
		update_attribute_swatches_options();
	});
	update_attribute_swatches_options();
	jQuery('#commercekit_sticky_atc_tabs').on('change', function(){
		if( jQuery(this).prop('checked') )
			jQuery('#sticky-atc-label').show();
		else
			jQuery('#sticky-atc-label').hide();
	});
	jQuery('input.cgkit-color-input').wpColorPicker();
	jQuery('select.select21').each(function(){
		jQuery(this).select2();
	});
	jQuery('body').on('change', 'input.badge-label', function(){
		var h2 = jQuery(this).closest('.postbox').find('h2 > span');
		if( jQuery(this).val() != '' )
			h2.html(jQuery(this).val());
		else
			h2.html('Label');
		validate_store_badges();
	});
	if( jQuery('#product-badge #first-row').length > 0 ){
		product_badge_html = jQuery('#product-badge #first-row').html();
		jQuery('#product-badge #first-row').remove();
	}
	jQuery('#product-badge').sortable({handle:'h2.gray'});
	jQuery('body').on('change', 'input.badge-check', function(){
		var td = jQuery(this).closest('td');
		if( jQuery(this).prop('checked') )
			td.find('input.badge-check-val').val(1);
		else
			td.find('input.badge-check-val').val(0);
	});
});
function update_attribute_swatches_options(){
	if( jQuery('#commercekit_attribute_swatches').length > 0 ){
		if( jQuery('#commercekit_attribute_swatches').prop('checked') ){
			jQuery('#cgkit-as-plp-options').removeClass('disable-as-plp');
			jQuery('#as-enable-tooltips').show();
			jQuery('#as-button-style').show();
		} else {
			jQuery('#cgkit-as-plp-options').addClass('disable-as-plp');
			jQuery('#as-enable-tooltips').hide();
			jQuery('#as-button-style').hide();
		}
		if( jQuery('#commercekit_attribute_swatches_plp').prop('checked') ){
			jQuery('#as-quick-atc').show();
		} else {
			jQuery('#as-quick-atc').hide();
		}
		if( jQuery('#commercekit_attribute_swatches_plp').prop('checked') && jQuery('#commercekit_as_activate_atc').prop('checked') ){
			jQuery('#as-quick-atc-txt').show();
			jQuery('#as-more-opt-txt, #as-swatch-link').hide();
		} else {
			jQuery('#as-quick-atc-txt').hide();
			jQuery('#as-more-opt-txt, #as-swatch-link').show();
		}
	}
}
function add_new_product_badge(){
	var html = product_badge_html;
	html = html.replace('<!--DELETE-->','<a href="javascript:;" class="delete-badge" onclick="delete_product_badge(this);">'+global_delete_badge+'</a>');
	jQuery('#product-badge').append('<div class="postbox change">'+html+'</div>');
	jQuery('#product-badge .change span.select2-container').remove();
	jQuery('#product-badge .change select.select2').removeClass('select2-hidden-accessible').html('');
	jQuery('#product-badge .change .product-ids select.select2').each(function(){
		add_select_select2(jQuery(this))
	});
	jQuery('#product-badge .change input.cgkit-color-input-tmp').wpColorPicker();
	jQuery('#product-badge .change').removeClass('change').addClass('no-change');
	jQuery('#product-badge').sortable('refresh');
	validate_store_badges();
}
function delete_product_badge(obj){
	if( confirm(global_delete_badge_confirm) ){
		jQuery(obj).closest('div.postbox').remove();
		validate_store_badges();
	}
}
function validate_store_badges(){
	if( jQuery('#product-badge').length > 0 ){
		var can_submit = true;
		jQuery('#product-badge input.badge-label.required').each(function(){
			var $this = jQuery(this);
			if( $this.hasClass('error') ){
				$this.removeClass('error');
				$this.parent().parent().find('.input-error').remove();
			}
			if( $this.val() == '' ){
				$this.addClass('error');
				$this.parent().parent().append('<div class="input-error">'+global_required_text+'</div>');
				can_submit = false;
				var box = $this.closest('.postbox');
				if( box.hasClass('closed') ) 
					box.removeClass('closed');
				$this.focus();
				return false;
			}
		});
		if( !can_submit ){
			jQuery('#btn-submit').attr('disabled', 'disabled');
		} else {
			jQuery('#btn-submit').removeAttr('disabled');
		}
	}
}
function reset_order_bump_statistics(){
	jQuery('#ajax-loading-mask').show();
	jQuery.ajax({
		url: ajaxurl+'?action=commercekit_reset_obp_statistics',
		type: 'POST',
		dataType: 'json',
		success: function( json ) {
			jQuery('#ajax-loading-mask').hide();
			jQuery('#obp-impressions').html('0');
			jQuery('#obp-revenue').html('0.00');
			jQuery('#obp-sales').html('0');
			jQuery('#obp-click-rate').html('0%');
			jQuery('#obp-covert-rate').html('0%');
			jQuery('#obp-click-rate-percent, #obp-covert-rate-percent').css('width', '0%');
		}
	});
}

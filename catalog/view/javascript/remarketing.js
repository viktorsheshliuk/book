function remarketingLog(message) {
    console.log('%c%s', 'color: #167d4b; font-weight: bold;', message);
}

function remarketingAddToCart(json) {
	const heading = $('title').text() || 'other';
	if (!json?.remarketing || Array.isArray(json.remarketing) && json.remarketing.length === 0) { remarketingLog('No remarketing data'); return; } 
	const { remarketing } = json; 
	if (remarketing?.ads_event && typeof gtag !== 'undefined') { gtag('event', 'add_to_cart', remarketing.ads_event); if (remarketing?.ads_conversion) { gtag('event', 'conversion', remarketing.ads_conversion); } }
	if (remarketing?.fb_pixel_event && typeof fbq !== 'undefined') { fbq('track', 'AddToCart', remarketing.fb_pixel_event, {eventID: remarketing.event_id}); }
	if (remarketing?.tiktok_event && typeof ttq !== 'undefined') { ttq.track('AddToCart', remarketing.tiktok_event, {eventID: remarketing.event_id}); }
	if (remarketing?.snapchat_event && typeof snaptr !== 'undefined') {	snaptr('track', 'ADD_CART', remarketing.snapchat_event); }
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.ga4_datalayer); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { remarketing.ga4_event.items[0].item_list_name = heading; gtag('event', 'add_to_cart', remarketing.ga4_event); }
	if (remarketing?.esputnik_event && typeof eS !== 'undefined') {	eS('sendEvent', 'StatusCart', remarketing.esputnik_event); }
	if (remarketing?.uet_event) { window.uetq = window.uetq || []; window.uetq.push('event', 'add_to_cart', remarketing.uet_event);	}
	if (typeof events_cart_add !== 'undefined') { events_cart_add(); }
	remarketingLog('add_to_cart');
}	  

function remarketingRemoveFromCart(json) {
	const heading = $('title').text() || 'other'; 
	if (!json?.remarketing || (Array.isArray(json.remarketing) && json.remarketing.length === 0)) {	remarketingLog('No remarketing data'); return; }
	const { remarketing } = json;
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.ga4_datalayer); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { remarketing.ga4_event.items[0].item_list_name = heading; gtag('event', 'remove_from_cart', remarketing.ga4_event); }
	if (remarketing?.esputnik_event && typeof eS !== 'undefined') {	eS('sendEvent', 'StatusCart', remarketing.esputnik_event); }
	remarketingLog('remove_from_cart');
}

function remarketingRemoveFromSimpleCart(cart_product_id, quantity) {
	if (cart_product_id && quantity) { $.ajax({type:'post',url:'index.php?route=common/remarketing/removeProduct',data:{'product_id' : cart_product_id, 'quantity': quantity},dataType:'json',success: function(json) { remarketingRemoveFromCart(json); }}); }
}

function sendWishList(json) {
	const heading = $('title').text() || 'other';
	if (!json?.remarketing || Array.isArray(json.remarketing) && json.remarketing.length === 0) { remarketingLog('No remarketing data'); return; } 
	const { remarketing } = json; 
	if (remarketing?.fb_pixel_event && typeof fbq !== 'undefined') { fbq('track', 'AddToWishlist', remarketing.fb_pixel_event, {eventID: remarketing.event_id}); }
	if (remarketing?.tiktok_event && typeof ttq !== 'undefined') { ttq.track('AddToWishlist', remarketing.tiktok_event, {eventID: remarketing.event_id}); }
	if (remarketing?.snapchat_event && typeof snaptr !== 'undefined') {	snaptr('track', 'ADD_TO_WISHLIST', remarketing.snapchat_event); }
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.ga4_datalayer); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { remarketing.ga4_event.items[0].item_list_name = heading; gtag('event', 'add_to_wishlist', remarketing.ga4_event); }
	if (remarketing?.esputnik_event && typeof eS !== 'undefined') {	eS('sendEvent', 'AddToWishlist', remarketing.esputnik_event); }
	if (typeof events_wishlist === 'function') { events_wishlist(); }
	remarketingLog('wishlist');
}

function sendCompare(json) {
	const heading = $('title').text() || 'other';
	if (!json?.remarketing || Array.isArray(json.remarketing) && json.remarketing.length === 0) { remarketingLog('No remarketing data'); return; } 
	const { remarketing } = json; 
	if (remarketing?.fb_pixel_event && typeof fbq !== 'undefined') { fbq('trackCustom', 'Compare', remarketing.fb_pixel_event, {eventID: remarketing.event_id}); }
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.ga4_datalayer); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { remarketing.ga4_event.items[0].item_list_name = heading; gtag('event', 'add_to_compare', remarketing.ga4_event); }
	remarketingLog('compare');
}

function remarketingCallback(json) {
	const heading = $('title').text() || 'other';
	if (!json?.remarketing || Array.isArray(json.remarketing) && json.remarketing.length === 0) { remarketingLog('No remarketing data'); return; } 
	const { remarketing } = json; 
	if (remarketing?.fb_status && typeof fbq !== 'undefined') { fbq('track', 'Contact'); }
	if (remarketing?.tiktok_status && typeof ttq !== 'undefined') { ttq.track('Contact'); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { gtag('event', 'callback', remarketing.ga4_event); }
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.ga4_datalayer); }
	remarketingLog('callback');
} 

function remarketingFoundCheaper(json) {
	const heading = $('title').text() || 'other';
	if (!json?.remarketing || Array.isArray(json.remarketing) && json.remarketing.length === 0) { remarketingLog('No remarketing data'); return; } 
	const { remarketing } = json; 
	if (remarketing?.fb_status && typeof fbq !== 'undefined') { fbq('track', 'Contact'); }
	if (remarketing?.tiktok_status && typeof ttq !== 'undefined') { ttq.track('Contact'); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { gtag('event', 'found_cheaper', remarketing.ga4_event); }
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.ga4_datalayer); }
	remarketingLog('found_cheaper');
} 
  

function remarketingNewsletter(json) {
	if (json['success'] || (json['output'] && typeof(json['error']) === 'undefined')) {
		remarketingLog('newsletter');
		window.dataLayer = window.dataLayer || [];
		dataLayer.push({'event': 'ga4_newsletter'})
		if (typeof gtag != 'undefined') {
			gtag('event', 'newsletter');
		}
		if (typeof snaptr != 'undefined') {
			snaptr('track', 'SIGN_UP');
		}
	}
} 

function remarketingTelephoneClick() {
	remarketingLog('telephone_click');
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({'event': 'ga4_telephone_click'})
	if (typeof gtag != 'undefined') {
		gtag('event', 'telephone_click'); 
	}
	if (typeof fbq != 'undefined') {
		fbq('track', 'Contact'); 
	}
	if (typeof ttq != 'undefined') {
		ttq.track('Contact'); 
	}
} 

function remarketingMailClick() {
	remarketingLog('mail_click');
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({'event': 'ga4_mail_click'})
	if (typeof gtag != 'undefined') {
		gtag('event', 'mail_click'); 
	}
	if (typeof fbq != 'undefined') {
		fbq('track', 'Contact'); 
	}
	if (typeof ttq != 'undefined') {
		ttq.track('Contact'); 
	}
} 

function remarketingTgClick() {
	remarketingLog('tg_click');
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({'event': 'ga4_tg_click'})
	if (typeof gtag != 'undefined') {
		gtag('event', 'tg_click');  
	}
	if (typeof fbq != 'undefined') {
		fbq('track', 'Contact'); 
	}
	if (typeof ttq != 'undefined') {
		ttq.track('Contact'); 
	}
} 

function remarketingQuickOrder(json) { 
	if (!json?.remarketing) { remarketingLog('No remarketing data'); return; }
	const { remarketing } = json; 
	if (remarketing?.ec_data) { window.enhanced_conversion_data = remarketing.ec_data; typeof gtag !== 'undefined' && gtag('set', 'user_data', remarketing.ec_data); }
	if (remarketing?.ads_event && typeof gtag !== 'undefined') { gtag('event', 'purchase', remarketing.ads_event)};
	if (remarketing?.ads_conversion && typeof gtag !== 'undefined') { gtag('event', 'conversion', remarketing.ads_conversion)}; 
	if (remarketing?.reviews_event) { $.getScript('https://apis.google.com/js/platform.js?onload=renderOptIn'); window.renderOptIn = () => window.gapi.load('surveyoptin', () => window.gapi.surveyoptin.render(remarketing.reviews_event)); }
	if (remarketing?.ga4_datalayer) { window.dataLayer = window.dataLayer || []; dataLayer.push({ ecommerce: null }); dataLayer.push(remarketing.client_data); dataLayer.push(remarketing.ga4_datalayer); }
	if (remarketing?.ga4_event && typeof gtag !== 'undefined') { gtag('event', remarketing.ga4_event_name, remarketing.ga4_event); }
	if (remarketing?.fb_event && typeof fbq !== 'undefined') { fbq('track', 'Purchase', remarketing.fb_event, {eventID: remarketing.fb_event_id}); if (remarketing?.fb_lead_event) fbq('track', 'Lead', remarketing.fb_lead_event, {eventID: remarketing.fb_lead_event_id}); }
	if (remarketing?.tiktok_event && typeof ttq !== 'undefined') { ttq.identify({phone_number : remarketing.telephone }); ttq.track('Purchase', remarketing.tiktok_event, {eventID: remarketing.tiktok_event_id}); }
	if (remarketing?.snapchat_event && typeof snaptr !== 'undefined') { snaptr('track', 'PURCHASE', remarketing.snapchat_event); }
	if (remarketing?.esputnik_event && typeof eS !== 'undefined') { eS('sendEvent', 'PurchasedItems', remarketing.esputnik_event); }
	if (remarketing?.uet_event) { window.uetq = window.uetq || []; window.uetq.push('event', 'purchase', remarketing.uet_event); }
	if (typeof quickPurchase === 'function') {
		quickPurchase(remarketing.order_id, remarketing.default_total, remarketing.email, remarketing.telephone);
	}
	remarketingLog('quick_order'); 
}
	
function decodePostParams(str) {
    return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
}

$(document).ready(function() {
	$.each($("[onclick*='cart.add'], [onclick*='get_revpopup_cart'], [onclick*='addToCart'], [onclick*='get_oct_popup_add_to_cart']"), function() {
		$(this).addClass('remarketing_cart_button').attr('data-product_id', $(this).attr('onclick').match(/[0-9]+/));
	});
	
	$(document).on('click', 'a[href^="tel:"]', function() {
		if (typeof remarketingTelephoneClick == 'function') {
			remarketingTelephoneClick();
		}	
	});
	
	$(document).on('click', 'a[href^="mailto"]', function() {
		if (typeof remarketingMailClick == 'function') {
			remarketingMailClick();
		}	
	});  
	
	$(document).on('click', 'a[href*="t.me"]', function() {
		if (typeof remarketingTgClick == 'function') {
			remarketingTgClick();
		}	
	});
	
	$(document).ajaxSuccess(function(event, xhr, settings) {
		
		cartRoutes = [
			'checkout/cart/add',
			'extension/module/technics/technicscart/fastadd2cart',
			'checkout/cart/add&oct_dirrect_add=1',
			'extension/module/frametheme/ft_cart/add',
			'extension/basel/basel_features/add_to_cart',
			'extension/soconfig/cart/add'
		];  
		
		if (cartRoutes.some(url => settings.url.includes(url))) {
			if (settings.type === 'POST' && xhr.responseJSON?.remarketing !== undefined) {
				if (typeof remarketingAddToCart === 'function') {
					remarketingAddToCart(xhr.responseJSON);
				}
			}
		}

		if (settings.url.indexOf('checkout/cart/remove') != -1 || settings.url.indexOf('status_cart') != -1 || settings.url.indexOf('statusCart') != -1 || settings.url.indexOf('extension/soconfig/cart/remove') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON['remarketing'] !== 'undefined') {
				if (typeof remarketingRemoveFromCart == 'function') {
					remarketingRemoveFromCart(xhr.responseJSON);
				}
			}
		}
		
		if (settings.url.indexOf('account/wishlist/add') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON['remarketing'] !== 'undefined') {
				if (typeof sendWishList == 'function') {
					sendWishList(xhr.responseJSON);
				}
			} 
		}
		
		if (settings.url.indexOf('product/compare/add') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON['remarketing'] !== 'undefined') {
				if (typeof sendCompare == 'function') {
					sendCompare(xhr.responseJSON);
				}
			} 
		}
		
		if (settings.url.indexOf('oct_popup_call_phone/send') != -1 || settings.url.indexOf('_callback') != -1 || settings.url.indexOf('callback/write') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON['remarketing'] !== 'undefined') {
				if (typeof remarketingCallback == 'function') {
					remarketingCallback(xhr.responseJSON);
				}
			} 
		} 
		
		if (settings.url.indexOf('footer/addToNewsletter') != -1 || settings.url.indexOf('oct_subscribe/makeSubscribe') != -1) {
			if (typeof xhr.responseJSON !== 'undefined') {
				if (typeof remarketingNewsletter == 'function') {
					remarketingNewsletter(xhr.responseJSON);
				}
			} 
		} 
		
		if (settings.url.indexOf('found_cheaper_product_confirm') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON['remarketing'] !== 'undefined') {
				if (typeof remarketingFoundCheaper == 'function') {
					remarketingFoundCheaper(xhr.responseJSON);
				}
			} 
		} 
		
		if (settings.url.indexOf('module/oct_popup_found_cheaper/send') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && typeof xhr.responseJSON['remarketing'] !== 'undefined') {
				if (typeof remarketingFoundCheaper == 'function') {
					remarketingFoundCheaper(xhr.responseJSON);
				}
			} 
		} 		
		
		if (settings.url.indexOf('oct_product_faq/write') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON['success']) {
				window.dataLayer = window.dataLayer || [];
				dataLayer.push({'event': 'ga4_product_faq'})
				if (typeof gtag != 'undefined') {
					gtag('event', 'product_faq');
				}
			} 
		} 
		
		if (settings.url.indexOf('product/product/write') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON['success']) {
				window.dataLayer = window.dataLayer || [];
				dataLayer.push({'event': 'ga4_product_review'})
				if (typeof gtag != 'undefined') {
					gtag('event', 'product_review');
				}
			} 
		} 
		
		if (settings.url.indexOf('oct_sreview_reviews/write') != -1) {
			if (typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON['success']) {
				window.dataLayer = window.dataLayer || [];
				dataLayer.push({'event': 'ga4_store_review'})
				if (typeof gtag != 'undefined') {
					gtag('event', 'store_review');
				}
			} 
		} 
		
		quickOrderRoutes = [
			'extension/module/luxshop_newfastordercart',
			'extension/module/luxshop_newfastorder',
			'extension/module/cyber_newfastordercart',
			'extension/module/cyber_newfastorder',
			'extension/module/chameleon_newfastorder/addFastOrder',
			'extension/module/newfastorder',
			'extension/module/newfastordercart',
			'extension/module/upstore_newfastorder/addFastOrder',
			'extension/module/uni_quick_order/add' 
		];  
		
		if (quickOrderRoutes.some(url => settings.url.includes(url))) {
			if (settings.type === 'POST' && xhr.responseJSON?.success !== undefined && xhr.responseJSON?.remarketing !== undefined) {
				if (typeof remarketingQuickOrder === 'function') {
					remarketingQuickOrder(xhr.responseJSON);
				}
			}
		}		
		
		if (settings.url.indexOf('checkout/simplecheckout&group=0') != -1) {
			simple_data = decodePostParams(decodeURI(settings.data));
			if (simple_data.remove !== 'undefined' && simple_data.remove !== '') {
				quantity_key = 'quantity[' + simple_data.remove + ']';
				quantity = simple_data[quantity_key]; 
				if (typeof cart_products[simple_data.remove] !== 'undefined') {
					cart_product_id = cart_products[simple_data.remove]['product_id'];
					if (typeof remarketingRemoveFromSimpleCart == 'function') {
						remarketingRemoveFromSimpleCart(cart_product_id, quantity);
					}
				}
			}
		}
		
		if (settings.url.indexOf('checkout/simplecheckout/prevent_delete') != -1) {
			if (typeof fbq != 'undefined' && typeof facebook_payment_data != 'undefined') {
				fbq('track', 'AddPaymentInfo', facebook_payment_data);
			}
			if (typeof ttq != 'undefined' && typeof tiktok_payment_data != 'undefined') {
				ttq('track', 'AddPaymentInfo', tiktok_payment_data);
			}
			if (typeof gtag != 'undefined' && typeof ga4_payment_data != 'undefined') {
				gtag('event', 'add_payment_info', ga4_payment_data);
			}
		}
	}); 
	$(document).on('click touchstart', '.product-thumb, .fm-module-item, .rm-module-item, .sc-module-item, .us-module-item, .ds-module-item', (e) => { item_id = $(this).find('.remarketing_cart_button').attr('data-product_id');	const index = $(e.target).index('*'); const header = $($('h1, h2, h3, .sc-module-header, .title-module > span, .rm-column-title, .fm-column-title, .us-module-column-box .panel-heading').get().reverse()).filter((i, el) => ($(el).index('*') < index)).first(); localStorage.setItem('remarketing_product_id', item_id); localStorage.setItem('remarketing_heading', header.text().trim());});
});
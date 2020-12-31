jQuery(document).ready(function ( $ ) {
	
	$('#shipping_state_city').selectWoo();
	
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	let pws_IDs = [ urlParams.get('post') ];
	let pws_button_submit = $("#pws-tapin-submit");
	let pws_button_ship = $("#pws-tapin-ship");
	
	pws_button_submit.click(function () {
		pws_change_status('pws-packaged');
	});
	
	pws_button_ship.click(function () {
		pws_change_status('pws-ready-to-ship');
	});
	
	$('#tapin_weight').keydown(function () {
		pws_button_submit.hide();
		$('#pws-tapin-submit-tip').show();
	});
	
	$('#shipping_state_city').change(function () {
		pws_button_submit.hide();
		$('#pws-tapin-submit-tip').show();
	});
	
	function pws_change_status( status ) {
		
		// Start
		pws_button_submit.attr('disabled', 'disabled');
		pws_button_ship.attr('disabled', 'disabled');
		$('.pws-tips').html('');
		
		pws_change_status_ajax(status);
	}
	
	function pws_change_status_ajax( status ) {
		
		let id = pws_IDs.shift();
		
		if( id == undefined ) {
			// End
			pws_button_submit.removeAttr('disabled');
			pws_button_ship.removeAttr('disabled');
			return true;
		}
		
		let data = {
			'action': 'pws_change_order_status',
			'status': status,
			'id': id
		};
		
		$(".pws-tips").html(`
                        <mark class="order-status">
                            <span>...</span>
                        </mark>
                    `);
		
		$.post(ajaxurl, data).then(function ( response ) {
			
			response = JSON.parse(response);
			
			if( response.success ) {
				
				$(".pws-tips").html(`
                                <mark class="order-status status-processing">
                                    <span>${response.message}</span>
                                </mark>
                            `);
				
				setTimeout(function () {
					location.reload();
				}, 3000);
				
			} else {
				
				$(".pws-tips").html(`
                                <mark class="order-status status-pws-returned">
                                    <span>خطا در پردازش</span>
                                </mark>
                            `);
				
				$(".pws-tips").append(`
                                <mark class="order-status status-pws-returned pws-tips"
                                        style="margin-top: 10px; font-size: 11px;">
                                    <span>
                                        ${response.message}
                                    </span>
                                </mark>
                            `);
				
			}
			
			pws_change_status_ajax(status);
		});
		
	}
});
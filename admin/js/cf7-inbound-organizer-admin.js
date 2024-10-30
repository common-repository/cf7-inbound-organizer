( function( $ ) {
	'use strict';

	/**
	 * Attaches JQuery UI Draggable to all divs with the specified class.
	 */
	function makeDraggable() {
		$( '.cf7-inbound-organizer-card' ).draggable( {
			containment: '.cf7-inbound-organizer-container',
			stack: '.cf7-inbound-organizer-container'
			}
		);
	}
	
	/**
	 * Refresh the contents of a column from the server.
	 * 
	 * @param { HTMLElement } column The element that contains the column elements to be refreshed
	 */
	function refreshColumn ( column ) {
		$.post(
			cf7io_ajax_obj.ajax_url, {      			
			_ajax_nonce: cf7io_ajax_obj.nonce,			
			action: 'cf7io_refresh_messages',       
			postData: { 							
						status: column.data( 'status' ),
						count: column.find('.cf7-inbound-organizer-card').length
					  }
			}, function ( result ) {
				$( column ).find('h2').remove();
				$( column ).find('.cf7-inbound-organizer-card').remove();
				$( column ).find('.button-primary').remove();
				$( column ).append ( result );
				makeDraggable();
			} 
		);
	}

	/**
	 * Helper function to process a drag & drop event of a card, 
	 * adding the card to the column in which it was dropped.
	 * 
	 * @param { Event } 	  event 
	 * @param { HTMLElement } card 
	 */
	function handleDropEvent( event, card) {
		var columnTitle = $ ( this ).find( 'h2' ).html(),
			cardTitle = card.draggable.find('h3').html(),
			oldColumn = card.draggable.parent(),
			newColumn = $( this ); 
		$.post(
			cf7io_ajax_obj.ajax_url, {      			
			_ajax_nonce: cf7io_ajax_obj.nonce,			
			action: 'cf7io_update_message_tracking_status',       
			postData: { 							
							status: $(this).data( 'status' ),
							id: card.draggable.data( 'id' )
					  }
			}, function ( notification ) {
					displayNotification( notification );
					card.draggable.remove();
					refreshColumn( oldColumn);
					refreshColumn( newColumn );
			} 
		);
	}

	/**
	 * Display a notification for 7 seconds.
	 * 
	 * @param { String } notification 
	 */
	function displayNotification ( notification ) {
		$( '#cf7-inbound-organizer-message' ).html( '<p>' + notification + '</p>' + 
		'<button type="button" class="notice-dismiss">' + 
		'<span class="screen-reader-text">Dismiss this notice.</span></button>' );
		$( '#cf7-inbound-organizer-message' ).slideDown(250);
		setTimeout( function () { 
			$( '#cf7-inbound-organizer-message' ).slideUp(1000);
			}
			, 7000 
		);
	}
	
	/**
	 * Main JQuery function event handlers
	 * 
	 */
	$( document ).ready( function() {
		//Make all messages draggable
		makeDraggable();

		//Event handler for when a message is dropped
		$( '.cf7-inbound-organizer-column' ).droppable( {
			drop: handleDropEvent
		} );

		//Definition of the message popup
		var cardDetail = $( '#cf7-inbound-organizer-card-detail' ).dialog( {
			autoOpen: false,
			width: 500,
			height: 500,
			show: { effect: 'fadeIn', duration: 500},
			hide: { effect: 'fadeOut', duration: 500}
		  } );
		  cardDetail.data( 'original-styles', $('.ui-dialog').attr('class') );

		//Event handler for clicking on trash icon in overview
		$ ( document ).on( 'click', '.cf7-inbound-organizer-card-trash', function ( ) {
			var card = $( this ).parent();
			$.post(
				cf7io_ajax_obj.ajax_url, {      			
				_ajax_nonce: cf7io_ajax_obj.nonce,			
				action: 'cf7io_trash_message',			
				postData: { 							
							id: card.data( 'id' )
						  }
				}, 
				function ( notification ) { 
								var column = card.parent();
								card.remove();
								refreshColumn ( column );
								displayNotification( notification );
				} 
			);
			return false;
		} );

		//Event handler for clicking on trash icon in message popup
		$ ( document ).on( 'click', '#cf7-inbound-organizer-card-detail-trash', function ( ) {
			$.post(
				cf7io_ajax_obj.ajax_url, { 
				_ajax_nonce: cf7io_ajax_obj.nonce,
				action: 'cf7io_trash_message',
				postData: {
							id: cardDetail.data( 'post-id' )
						  }
				}, 
				function ( notification ) { 
								var column = $('body').find('[data-id="'+cardDetail.data( 'post-id' )+'"]').parent();
								cardDetail.dialog( 'close' );
								$( '#cf7-modal-background' ).hide();	 
								$('body').find('[data-id="'+cardDetail.data( 'post-id' )+'"]').remove();
								refreshColumn( column );
								displayNotification( notification );
				 } 
			);
			return false;
		} );

		//Event handler for clicking on a message in the overview --> show popup
		$( document ).on( 'click', '.cf7-inbound-organizer-card', function() {
			$( '#cf7-modal-background' ).show();
			$.post(
				cf7io_ajax_obj.ajax_url, {      			
				_ajax_nonce: cf7io_ajax_obj.nonce,
				action: 'cf7io_render_message_details',
				postData: { 							
							id: $( this ).data( 'id' )
							}
				}, 
				function ( result ) {
					cardDetail.html( result );
					$('.ui-dialog').attr('class', cardDetail.data('original-styles') );
					$('.ui-dialog').addClass( $( '#cf7-inbound-organizer-card-detail-color-palette').data( 'selected' ) );
					$('.ui-dialog').data( 'original-color', $( '#cf7-inbound-organizer-card-detail-color-palette').data( 'selected' ) );
				}
			);
			cardDetail.data( 'post-id', $( this ).data( 'id' ) );
			cardDetail.data( 'status-id', $( this ).parent().data( 'status' ) );
			cardDetail.dialog( 'open' );
		} );

		//Event handler for closing the message popup
		$ ( document ).on( 'click', '#cf7-inbound-organizer-card-detail-close, #cf7-modal-background', 
			function () {
				var column = $( 'body' ).find('[data-id="' + cardDetail.data( 'post-id' ) + '"]').parent(); 
				refreshColumn ( column );
				cardDetail.dialog( 'close' );
				$( '#cf7-modal-background' ).hide();
		} );

		//Event handler to save the notes field of the message in the popup
		$( document ).on( 'submit', '#cf7-inbound-organizer-card-detail-save', function () {
			$.post(
				cf7io_ajax_obj.ajax_url, {
				_ajax_nonce: cf7io_ajax_obj.nonce,
				action: 'cf7io_save_message_notes',
				postData: {
							id: $( this ).parent().data( 'post-id' ),
							notes: $( this ).find( 'textarea' ).val()
					  	  }
				}
			);
			$(this).find( 'textarea' ).prop( 'disabled', true );
			$(this).find( 'button').hide();
			return false;
		} );

		//Event handler to activate notes field in the message popup
		$( document ).on( 'click', '#cf7-inbound-organizer-card-detail-save', function() {
			$(this).find( 'textarea' ).prop( 'disabled', false );
			$(this).find( 'textarea' ).focus();
			$(this).find( 'button').show();
		} );

		//Event handler to load more messages
		$( document ).on( 'click', '.cf7-inbound-organizer-column .button-primary', function() {
			var this2 = $( this );
			$.post(
					cf7io_ajax_obj.ajax_url, {
					_ajax_nonce: cf7io_ajax_obj.nonce,
					action: 'cf7io_load_more_messages',
					postData: {
								status: $( this ).data( 'status' ),
								page: $( this ).data( 'page' ),
							  } 
					}, 
					function ( result ) {
						this2.parent().append( result );
						this2.remove();
						makeDraggable();
					}
				 );
		} );

		//Event handler to add messages prior to plugin install (settings pages)
		$( document ).on( 'click', '#add-messages', function ( event ) {
			event.preventDefault();
			var input_days = parseInt ( $('#add-since' ).val() );
			if ( isNaN( input_days ) ) {
				$('#add-since' ).val('');
				return;
			}
			$.post(
				cf7io_ajax_obj.ajax_url, {
				_ajax_nonce: cf7io_ajax_obj.nonce,
				action: 'cf7io_add_messages',
				postData: {
							days: input_days,
							column: $( '#add-to-column' ).val(),
						  } 
				}, 
				function ( notification ) {
						displayNotification ( notification );
				}
			 );
		} );

		//Event handlers for hovering over color palette in card detail view
		$( document ).on( 'mouseenter', '#cf7-inbound-organizer-card-detail-color-palette td', 
			function() {
				$( '.ui-dialog' ).removeClass(  $( '.ui-dialog' ).data('original-color') );
				$( '.ui-dialog' ).addClass( $( this ).attr('class'));
			} );
		$( document ).on( 'mouseleave', '#cf7-inbound-organizer-card-detail-color-palette td', 
			function() {
				$( '.ui-dialog' ).removeClass( $( this ).attr('class'));
				$( '.ui-dialog' ).addClass(  $( '.ui-dialog' ).data('original-color') );
			} );

		//Event handler for clicking on a color in color palette in card detail view
		$( document ).on( 'click', '#cf7-inbound-organizer-card-detail-color-palette td', 
			function() {
				$( '.ui-dialog' ).addClass ( $( this ).attr( 'class' ) );
				$( '.ui-dialog' ).data( 'original-color', $( this ).attr( 'class' ) );
				$.post(
					cf7io_ajax_obj.ajax_url, { 
					_ajax_nonce: cf7io_ajax_obj.nonce,
					action: 'cf7io_update_message_color',
					postData: {
							id: cardDetail.data( 'post-id' ),
							color: $( this ).data( 'index' )
					} 
				} );
			} );
	} );
} )( jQuery );

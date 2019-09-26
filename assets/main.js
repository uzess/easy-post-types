(function( $ ){

	function EasyPostTypeRepeater(){

		var $addBtn = $( '.easy-post-type-add-repeater' ),
			$delBtn = $( '.easy-post-type-del-repeater.button' ),
			setting = '',
			wrapperStart = '<div class="easy-post-type-repeater-group"><div class="easy-post-type-icons-outer clearfix"><span class="handle dashicons dashicons-move"></span><span class="selector dashicons dashicons-yes"></span></div><div class="easy-post-type-repeater-fields">',
			wrapperEnd   = '</div></div>';

		var getFields = function( fields, media ){

			var html = '';

			$.each( fields, function( key, field ){
				if( field.type == 'select' ){
					console.log( field );
				}
				var name = setting + '[' + key + '][]';
				
				var placeholder = '';
				if( typeof field.placeholder != 'undefined' ){
					placeholder = field.placeholder;
				}

				var label = '';
				if( typeof field.label != 'undefined' ){
					label = field.label;
				}

				switch( field.type ){
					case 'checkbox':
						html += '<p><label><input type="hidden" name="'+name+'" value="0"><input type="checkbox" onclick="this.previousSibling.value=1-this.previousSibling.value">'+field.label+'</label></p>';
					break;

					case 'image':
						//Do nothing
					break;

					case 'select':
						if( label.length != 0 ){
							html += '<p><label>' + label + '</label></p>';
						}
						html += '<p><select name="'+name+'" >';

						$.each( field.choices, function( v, l ){
							html += '<option value="' + v + '">'+l+'</option>';
						});

						html += '</select></p>';
					break;

					case 'textarea':
						if( label.length != 0 ){
							html += '<p><label>' + label + '</label></p>';
						}
						html += '<p><textarea class="widefat" name="'+name+'" placeholder="'+placeholder+'"></textarea></p>';
						
					break;
					default:
						if( label.length != 0 ){
							html += '<p><label>' + label + '</label></p>';
						}
						html += '<p><input class="widefat" type="'+field.type+'" name="'+name+'" placeholder="'+placeholder+'"></p>';
					break;
				}
			});

			return html;
		};

		var onMedia = function(){

			var attachment = multipleMediaUploader.state().get( 'selection' ).toJSON();
				
			if( attachment  && attachment.length > 0 ){
				var html = '';
				for( var i = 0; i < attachment.length; i++ ){
					var image = attachment[ i ],url = image.url;
					if( typeof image.sizes !== 'undefined' ){
						url = image.sizes.thumbnail ? image.sizes.thumbnail.url : image.sizes.full.url;
					}

					html += wrapperStart + '<div class="media">\
								<input type="hidden" name="'+setting+'[media][]" value="'+image.id+'">\
								<img src="' + url +'" alt="">\
							</div>' + getFields( fields ) + wrapperEnd;
				}

				$wrapper.append( html );
			}
		};

		var multipleMediaUploader, fields, $wrapper;
		$( document ).on( 'click', $addBtn.selector, function(e){
			e.preventDefault();

			var media    = $( this ).data( 'media' );

				setting  = $( this ).data( 'setting' );
				$wrapper = $( '#easy-post-type-repeater-' + setting + ' .easy-post-type-repeater' );
				fields   = $( this ).data( 'fields' );
				if( media ){

					if( multipleMediaUploader ){
						multipleMediaUploader.open();
						return;
					}

					multipleMediaUploader = wp.media.frames.file_name = wp.media({
						title: EPT.media_title,
						button: {
							text: EPT.media_btn_text
						},
						multiple: true
					});

					multipleMediaUploader.on( 'select', onMedia );
					multipleMediaUploader.open();
				}else{

					var html = wrapperStart + getFields( fields ) + wrapperEnd;
					$wrapper.append( html );
				}

		});

		$( document ).on( 'click', $delBtn.selector, function(e){
			e.preventDefault();
			if( confirm( EPT.confirm_delete ) ){
				$( '.easy-post-type-repeater-group .ui-selectee.ui-selected').parent().parent().remove();
			}
		});

		jQuery( ".easy-post-type-repeater" )
			.sortable( { handle: ".handle" } )
			.selectable( { filter: ".easy-post-type-repeater-group .dashicons-yes", cancel: ".handle, .easy-post-type-repeater-fields" } );
	};

	function EasyPostTypeTab( args ){

		this.tab = args.tab;
		this.section = [];

		this.init = function(){

			var _this = this;

			jQuery( this.tab ).each( function( i ){

				var id = jQuery( this ).attr( 'href' );

				if( 0 == i ){
					jQuery( this ).addClass( 'active' );
				}else{
					jQuery( id ).addClass( 'hidden' );
				}

				_this.section.push( id );
			});

			jQuery( document ).on( 'click', this.tab, function( e ){

				e.preventDefault();

				if( jQuery( this ).hasClass( 'active' ) ){
					return;
				}

				var id = jQuery( this ).attr( 'href' );
				jQuery( _this.tab ).removeClass( 'active' );
				jQuery( this ).addClass( 'active' );

				jQuery.each( _this.section, function( index, item ){
					jQuery( id ).removeClass( 'hidden' );
					jQuery( item ).not( id ).addClass( 'hidden' );
				});

			});

		};

		this.init();
	};

	function imageBrowser(){

		var mediaUploader,
			$this;

		$( document ).on( 'click', '.easy-post-type-image-browse', function(e){
			
			$this = $( this );
			e.preventDefault();
			if( mediaUploader ){
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_name = wp.media({
				title: EPT.media_title,
				button: {
					text: EPT.media_btn_text
				},
				multiple: false
			});

			mediaUploader.on( 'select', function(){
				
				var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();

				if( attachment ){

					var url = attachment.url;
					if( typeof attachment.sizes !== 'undefined' ){
						url = attachment.sizes.thumbnail.url;
					}

					var data = $this.data( 'required' ),
					    img = $( '<img />',{  src: url } );
					$( '#' + data.setting ).val( attachment.id );
					$( '#' + data.holder ).html( img );
					$( '#' + data.delete ).removeClass( 'hidden' );

					$this.find( '.easy-post-type-image-btn-text' ).text( EPT.media_btn_change_text );
				}
			});

			mediaUploader.open();
		});

		$( document ).on( 'click', '.easy-post-type-image-delete', function( e ){ 
			e.preventDefault();

			var data = $( this ).data( 'required' );

			$( '#' + data.setting ).val( '' );
			$( '#' + data.holder ).html( '' );
			$( this ).addClass( 'hidden' );

			$( this ).parent( '.easy-post-type-image-btns' ).find( '.easy-post-type-image-btn-text' ).text( EPT.image_upload_text );
		});
	};

	jQuery( document ).ready( function(){
		new EasyPostTypeTab({
			tab : '.easy-post-type-rel-tab'
		});

		EasyPostTypeRepeater();

		imageBrowser();
	});

})( jQuery );
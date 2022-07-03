jQuery(function( $ ) {
	'use strict';

	function tmce_setContent(content, editor_id, textarea_id) {
		if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
		if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
		
		if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
		  return tinyMCE.get(editor_id).setContent(content);
		}else{
		  return jQuery('#'+textarea_id).val(content);
		}
	}

	
	var loader = `<div class="steamLoader">
		<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
			<path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
			s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
			c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"></path>
			<path fill="#1e73be" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
			C22.32,8.481,24.301,9.057,26.013,10.047z">
			<animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.9s" repeatCount="indefinite"></animateTransform>
			</path>
		</svg>
	</div>`;

	$("#scrap_steam").on("click", function(e){
		e.preventDefault();
		let steamUrl = $("input[name='steam_scraper_url']").val();
		$.ajax({
			type: "get",
			url: steamscraper.adminajax,
			data: {
				action: "get_steam_source",
				url: steamUrl,
				nonce: steamscraper.nonce
			},
			beforeSend: function(){
				$('body').append(loader);
			},
			dataType: "json",
			success: function (response) {
				$(document).find(".steamLoader").remove();
				if(response.success){
					let html = response.success;
					let description = $(html).find(".guideTopDescription").html();
					description = `<div>${description}</div>`;
					let contents = $(html).find(".guide.subSections");
					
					let finalContents = '';
					$(contents).find('.subSection.detailBox').each(function(){
						let title = $(this).find(".subSectionTitle").html();
						title = `<h2>${title}</h2>`;
				
						let ptags = '';
						$(this).find(".subSectionDesc").each(function () {
							$(this)[0].childNodes.forEach(function (el, ind) {
								if(el.nodeName === '#text'){
									if (el.textContent.trim().length > 0) {
										ptags += '<p>'+el.textContent+'</p>';
									}
								}else if(el.nodeName === "A"){
									ptags += `<p><a href="${$(el).find('img').attr("src")}"><img src="${$(el).find('img').attr("src")}"></a></p>`;
								} else {
									if(el.nodeName !== "BR") ptags += el.outerHTML
								}
							})
						});
				
						finalContents += `<div>${title} ${ptags}</div>`;
					});

					let finalOutputs = description+finalContents;
					tmce_setContent(finalOutputs, "content", "content")
				}
			}
		});
	});
});

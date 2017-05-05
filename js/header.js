/*
	- Venue tag functionality
	- Create Datepicker
	- Create Timepicker
*/

function getActiveTags() {
	var activeTags = new Array();
	$('.venue-active-tags').children('.venue-tag').each(function () {
		activeTags.push($(this).attr('data-tag'));
	});
	return activeTags;
};

$( function() {
	$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
});

$(document).ready(function(){
	$('.col-accordion-wrapper .col-title').click(function() {
		$(this).siblings('.accordion-content').toggle();
	});
	
	$('.tag-search-field').on("keyup input", function(){
		var inputVal = $(this).val();
		var itemID = $(this).attr('data-item-id');
		var resultDropdown = $(this).siblings(".tag-result");
		var currentPostTags = getActiveTags();
		if(inputVal.length){
			$.get(template_url + '/ajax-search-tag.php', {term: inputVal, post_tags: currentPostTags, item_id: itemID }).done(function(data){
				resultDropdown.html(data);
			});
		} else{
			resultDropdown.empty();
		}
	});

	$(document).on("click", ".tag-result p", function(){
		$(this).parents(".search-box").find('input[type="text"]').val($(this).text());
		$(this).parent(".tag-result").empty();
	});
});
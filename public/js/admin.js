var vdata;

function htmlbodyHeightUpdate(){
	var height3 = $( window ).height();
	var height1 = $('.nav').height()+50;
		height2 = $('.main').height();
	if(height2 > height3){
		$('html').height(Math.max(height1,height3,height2)+10);
		$('body').height(Math.max(height1,height3,height2)+10);
	}
	else
	{
		$('html').height(Math.max(height1,height3,height2));
		$('body').height(Math.max(height1,height3,height2));
	}

}
$(document).ready(function () {
	htmlbodyHeightUpdate();
	$( window ).resize(function() {
		htmlbodyHeightUpdate();
	});
	$( window ).scroll(function() {
		height2 = $('.main').height();
		htmlbodyHeightUpdate()
	});
	$('[data-toggle="tooltip"]').tooltip({
    trigger : 'hover'
	});
});

$('#user-profile').popover({
	title: "&nbsp;",
	placement: 'right',
	content: function () {
		return $("#user-profile-popover").html();
	},
}).data('bs.popover').tip().addClass('user-profile-popover');

$(document).on('click', function (e) {
	$('[data-toggle="popover"],[data-original-title]').each(function () {
		if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
			(($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false
		}
	});
});

$(document).on('click', '.checkbox-spotlight', function(e){
	if($(this).parent().children().first().is(':checked')){
		$(this).parent().children().first().removeAttr('checked');
		$(this).removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
	} else {
		$(this).parent().children().first().attr("checked", "checked");
		$(this).removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
	}
});



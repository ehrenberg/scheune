/*
 * NOTIFICATIONS
 * 
 */
$(function() {
	$(".view_notifications").click(function() {
		var ID = $(this).attr("id");
		$.ajax({
			type: "POST",
			url: "viewajax.php",
			data: "NID="+ ID, 
			cache: false,
			success: function(html){
				$("#view_notifications"+ID).prepend(html);
				$("#view"+ID).remove();
				$("#two_notifications"+ID).remove();
			}
		});
		return false;
	});
	
	$(".view_notifications_back").click(function() {
		var ID = $(this).attr("id");
		$.ajax({
			type: "POST",
			url: "viewajax.php",
			data: "BACK="+ ID, 
			cache: false,
			success: function(html){
				alert("YES");
			}
		});
		return false;
	});
});
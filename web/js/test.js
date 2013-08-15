$(function () {
	function validate(){
		if ($('#url').val() == "") {
			return false;
		}
		return true;
	}

	$('#addbtn').click(function(){
		if (!validate()) {
			alert("please enter right URL!");
		}
		var data = {_token:$('#token').val(),url:$('#url').val()};
		$.ajax({
			type:"POST",
			url:"/rss/add",
			data:data,
			success:function(rs)
			{
				location.href="/rss";
			},
			});
	});

	$(document).on('click','.delete',function(){
		if (confirm('Would U really want to delete it?')){
			var site_id = $(this).parent().data('id');
			alert(site_id);
			$.post('/rss/delete',{site_id:site_id},function(rs){
			$('#siteTitleId_'+site_id).fadeOut(100);
				location.href="/rss";
			});
		}
	});
});
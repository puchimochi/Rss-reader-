$(function () {
	/*function validate(){
		if ($('#url').val() == "") {
			return false;
		}
		return true;
	}

	$('#addbtn').click(function(){
		if (!validate()) {
			$('.error_list').append('<ul class="error_list"><li>Please enter RSS URL!</li></ul>');
		}
	});
*/
	//RSSフィードを削除
	$(document).on('click','.delete',function(){
		if (confirm('Would U really want to delete it?')){
			var site_id = $(this).parent().data('id');
			//alert(site_id);
			$.post('/rss/delete',{site_id:site_id},function(rs){
				$('#siteTitleId_'+site_id).fadeOut(100);
				location.href="/rss";
			});
		}
	});
	//個別に記事を表示
	$(document).on('click','.active',function(){
		var site_id = $(this).data('id');
		//alert(site_id);

		$.ajax({
			type:"POST",
			url:"/rss/showlist",
			data:{site_id:site_id},
			//dataType:'json',
			success:function(data)
			{
				$('#content').html('<div class="accordion" id="accordion2"><div class="accordion-group" id="contentfeed"></div></div>');
				var count = 1;
				$.each(data, function(i,value){
					$('#content').prepend('<div class="accordion" id="accordion2"><div class="accordion-group" id="contentfeed"><div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse'+count+'">title:'+data[i].title+'</a></div><div id="collapse'+count+'" class="accordion-body collapse in"><div class="accordion-inner">投稿日時：'+ data[i].created_at +'<br>'+data[i].content+'</div></div>');
					count ++;
				});

				console.log(data);
			},
			error: function(xhr, textStatus, errorThrown){
				console.log(arguments);
				alert('Error! ' + textStatus + ' ' + errorThrown);

			}
		});
	});
	//JqueryUIで並び替え、データーベースに順番を保存
	$('#lists').sortable({
		update:function(){
			$.post('/rss/updatelist',{list:$(this).sortable('serialize')}
				);
		}
	});
});
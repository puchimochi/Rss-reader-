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
			var site_id = $(this).parent().parent().parent().data('id');
			alert(site_id);
			$.post('/rss/delete',{site_id:site_id},function(rs){
				console.log(rs);
				$('#siteId_'+site_id).fadeOut(100);
				location.href="/rss";
			});
		}
	});
	//個別に記事を表示
	$(document).on('click','.lists',function(){
		var site_id = $(this).data('id');
		//alert(site_id);
		$.ajax({
			type:"POST",
			url:"/rss/showlist",
			data:{site_id:site_id},
			//dataType:'json',
			success:function(data)
			{
				if (data == 'false') {
					$('#content').html('<h3>未読記事がありません。</h3>');
				} else{

				$('#content').html('<div></div>');
				var count = 1;
				$.each(data, function(i,value){
					$('#content').append('<table class="table table-bordered"><tr><th>title:<a href="'+data[i].link+'" target="_blank">'+data[i].title+'</a></th></tr><tr><th>投稿日時：'+data[i].created_at +'<form action= "http://localhost:1212/rss/change" method = "post"><input type="hidden" name="entry_id" value="'+data[i].id +'" id="readflag"><input type="submit" id="addbtn" value="既読"></form><br>'+data[i].content+'<br>'+'<a href="'+data[i].link+'" target="_blank">続きは...</a></th></tr></table>');
					count ++;
				});

				console.log(data);
			}
			},
			error: function(xhr, textStatus, errorThrown){
				console.log(arguments);
				alert('Error! ' + textStatus + ' ' + errorThrown);

			}
		});
	});
//カテゴリ別に記事を表示
	$(document).on('click','#category',function(){
		var category_name = $(this).data('id');

		$.ajax({
			type:"POST",
			url:"/rss/show",
			data:{category_name:category_name},
			//dataType:'json',
			success:function(data)
			{
				if (data == 'false') {
					$('#content').html('<h3>未読記事がありません。</h3>');
				} else if(data == 'empty' ){
					$('#content').html('このカテゴリはemptyです。');
				}else{

				$('#content').html('<div><div>');
				var count = 1;
				$.each(data, function(i,value){
					$('#content').append('<table class="table table-bordered"><tr><th>title:<a href="'+data[i].link+'" target="_blank">'+data[i].title+'</a></th></tr><tr><th>投稿日時：'+data[i].created_at +'<form action= "http://localhost:1212/rss/change" method = "post"><input type="hidden" name="entry_id" value="'+data[i].id +'" id="readflag"><input type="submit" id="addbtn" value="既読"></form><br>'+data[i].content+'<br>'+'<a href="'+data[i].link+'" target="_blank">続きは...</a></th></tr></table>');
					count ++;
				});

				console.log(data);
			}
			},
			error: function(xhr, textStatus, errorThrown){
				console.log(arguments);
				alert('Error! ' + textStatus + ' ' + errorThrown);
			}
		});
	});

//カテゴリを変更
	$('.categories').click(function () {
		var $category_name = $(this).parent('li').data('id');
		var $site_id = $(this).parents('.lists').data('id');
		alert(category_name);
		alert(site_id);
		$.ajax(
			url :"/rss/categorize",
			type : 'POST',
			data :{category_name :$category_name,site_id:$site_id},
			settings,
			settings,)

	})


/*
	//JqueryUIで並び替え、データーベースに順番を保存
	$('#lists').sortable({
		update:function(){
			$.post('/rss/updatelist',{list:$(this).sortable('serialize')}
				);
		}
	});

/*
	// 記事に既読フラグを立つ
	$('#readflag').click(function(){
		var entryId = $(this).data('id');
		$.ajax({
			type:"POST",
			url:"/rss/change",
			data:{entry_id:entryId}
		});
		// $.post('/rss/change',{entry_id:entryId});
	});*/
});
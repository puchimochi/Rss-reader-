$(function () {
	function validate(str) {
		if (str == "" ){
				return false;
			}
			return true;
	}

function isUrl(url) {
	var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
	'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
	'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
	'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
	'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
	'(\\#[-a-z\\d_]*)?$','i'); // fragment locator

	if(!pattern.test(url)) {
	return false;
	} else {
	return true;
	}
	}

//RSS追加
	$('#addbtn').click(function  () {
			var url = $('#url').val();
			if ( !validate(url)){
				$('#addrss').prepend('<li>Please enter URL</li>');
				// alert("Please enter URL");

			}else if(!isUrl(url)){
				$('#addrss').prepend('<li>Please enter right URL</li>');
			}else{
				var data = {_token:$('#token').val(),url:$('#url').val()};
				$.ajax({
					type :"POST",
					url : '/rss/add',
					data:data,
					success : function(msg){
						if (msg ==="forbidden") {
							// location.href="/rss";
							// alert(msg);
							console.log(msg);
							location.href="/rss";
						}else if(msg ==="error"){
							console.log(msg);
							location.href="/rss";
						}else{
							// alert("success");
							location.href="/rss";
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown){
						//通常はここでtextStatusやerrorThrownの値を見て処理を切り分けるか、単純に通信に失敗した際の処理を記述します。
						//this;
						//thisは他のコールバック関数同様にAJAX通信時のオプションを示します。
						//エラーメッセージの表示
						alert('Error : ' + errorThrown);
					}
				});
			}
		});


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

//カテゴリ名を変更
	$(document).on('dblclick', '#category',function(){
		if (!$(this).hasClass('on')) {
			$(this).addClass('on');
			var text = $(this).text();
			alert(text);
			$(this).html('<input type="text" name="category_name" placeholder="'+text+'">');
			$('#category > input').focus().blur(function () {
				var inputVal = $(this).val();
				if (inputVal === '') {
					$(this).parent().html('<li id="category" data-id="'+text+'"><a><strong><i class="icon-list"></i>'+text+'</strong></a></li>');
				}else{
					$(this).parent().removeClass('on');
					$.ajax({
						url :"/rss/changecategoryname",
						type :'POST',
						data :{new_category_name :inputVal,category_name:text},
						success: function(msg){
							alert(msg);
							if (msg == 'error') {
								alert('false!');
							} else if(msg== 'fobidden'){
								alert('post');
							}else if(msg === 'bubu'){
								alert('database');
							}else{
								location.href="/rss";
							}
						},
						error: function(xhr, textStatus, errorThrown){
							console.log(arguments);
							//alert('Error! ' + textStatus + ' ' + errorThrown);
							//location.href="/rss";
						}
					});
				}
				});
		}
	});


//カテゴリを変更
	$('.categories').click(function () {
		var category_name = $(this).parent('li').data('id');
		var site_id = $(this).parents('.lists').data('id');
		alert(category_name);
		alert(site_id);
		$.ajax({
			url :"/rss/categorize",
			type :'POST',
			data :{category_name :category_name,site_id:site_id},
			success: function(msg){
				alert(msg);
					if (msg == 'error') {
						alert('false!');
					} else if(msg== 'fobidden'){
						alert('post');
					}else{
						location.href="/rss";
					}
				},
			error: function(xhr, textStatus, errorThrown){
				console.log(arguments);
				//alert('Error! ' + textStatus + ' ' + errorThrown);
				//location.href="/rss";
			}
		});
	});


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
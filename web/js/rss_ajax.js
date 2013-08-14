$(function() {
    	function validate() {
    		if ($('#url').val() == "" ){
				return false;
			}
			return true;
		}

		$('#addbtn').click(function  () {
			if ( !validate()){
				alert("Please enter URL");
				return false;
			}
			var data = {_token:$('#token').val(),url:$('#url').val()};
			//var data = {url:$('#url').val()};

			$.ajax({
				type: "POST",
				url: "/rss/add",
				data: data,
				success: function(rs)
                {
                    //successのブロック内は、Ajax通信が成功した場合に呼び出される
                    //PHPから返ってきたデータの表示
                    location.href="/rss";
                },
                /**
                 * Ajax通信が失敗した場合に呼び出されるメソッド
                 */
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    //通常はここでtextStatusやerrorThrownの値を見て処理を切り分けるか、単純に通信に失敗した際の処理を記述します。
                    //this;
                    //thisは他のコールバック関数同様にAJAX通信時のオプションを示します。
                    //エラーメッセージの表示
                    alert('Error : ' + errorThrown);
                	}
			 });
		});

		$(document).on('click','.delete',function(){
		if (confirm('Would U really want to delete it?')) {
			var site_id = $(this).parent().data('id');
			alert(site_id);
			$.post('/rss/delete',{site_id:site_id},function(rs){
				$('#siteTitleId_'+site_id).fadeOut(100);
				location.href="/rss";
			});
		}
	});
});
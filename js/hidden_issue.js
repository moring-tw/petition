$('.hidden_btn').each(function(){
	$(this).on("click", function(e){
		console.log("STOP");
		e.preventDefault();
		e.stopPropagation();
		var result = confirm("確定刪除嗎? Are you sure to delete this issue?");
		if(result){
			$(this).parent().submit();
		} else {
			//do nothing
		}
	});
});

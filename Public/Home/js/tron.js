$('.tron').mouseover(function(){
	//修改tr里td个背景色
		$(this).find('td').css('backgroundColor' , '#FFCCAA')
});
$('.tron').mouseout(function(){
	//修改tr里td个背景色
	$(this).find('td').css('backgroundColor' , '')
});
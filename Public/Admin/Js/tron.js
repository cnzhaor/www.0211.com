$('.tron').mouseover(function(){
	//修改tr里td个背景色
	$(this).find('td').css('backgroundColor' , '#DDEEF2')
});
$('.tron').mouseout(function(){
	//修改tr里td个背景色
	$(this).find('td').css('backgroundColor' , '')
});
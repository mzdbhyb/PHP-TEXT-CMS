window.onscroll = function(){
	var sTop = 0;
	if(typeof window.pageYOffset != 'undefined'){
		sTop = window.pageYOffset;
	}else if(typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat'){
		sTop = document.documentElement.scrollTop;
	}else if(typeof document.body != 'undefined'){
		sTop = document.body.scrollTop;
	}
	document.getElementById('sDiv').style.display = sTop > 0 ? 'block' : 'none';
}

window.onresize = window.onload = function(){
	var maxW = document.getElementById('nav').scrollWidth;
	var imgElements = document.getElementById('maincont').getElementsByTagName('img');
	for (var k = 0; k < imgElements.length; k++) {
		if (imgElements[k].width > maxW) {
			imgElements[k].style.width = (maxW - 20) + 'px';
		}
	}
}

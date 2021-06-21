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
	var divL = document.getElementById('mainside');
	var divR = document.getElementById('maincont');
	divR.style.minHeight = "560px";
	var hL = divL.scrollHeight;
	var hR = divR.scrollHeight;
	hL > hR ? divR.style.height = hL + 'px' : divL.style.height = hR + 'px';
	var divMl = document.getElementById('mmleft');
	var divMr = document.getElementById('mmright');
	if(divMl && divMr){//首页用: mmleft和mmrigh对换位置时调整宽度
		if(divMl.offsetLeft > divMr.offsetLeft) { divMl.style.width = (divR.offsetWidth - divMr.offsetWidth - 30) + 'px'; }
	}
}

/* 移动设备检测: 自动跳转
function browserRedirect() {
	var sUserAgent = navigator.userAgent.toLowerCase();
	var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
	var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
	var bIsMidp = sUserAgent.match(/midp/i) == "midp";
	var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
	var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
	var bIsAndroid = sUserAgent.match(/android/i) == "android";
	var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
	var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
	if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
		window.location.href = '/wap/';
	}
}

browserRedirect();

*/

//图像特效
var sIMG = document.getElementsByClassName('rotate');//声明img对象（数组）
for(i=0;i<sIMG.length;i++){//遍历数组，为其制定翻转css属性
	sIMG[i].onmouseover = function() {//鼠标经过图片时
		this.style.transform = this.style.webkitTransform = 'rotate(45deg)';
	}
	sIMG[i].onmouseout = function() {//鼠标离开图片时
		this.style.transform = this.style.webkitTransform = 'rotate(0)';
	}
}
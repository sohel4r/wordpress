<<<<<<< HEAD
//grobal data
var viewerdata = {
	imagenum:0,
	imgtextarray:[],
	nownum:0,
	allwidth:0,
	imagewidth:0,
	iamgeheight:0,
	speed:70,
	pclick:false,
	nclick:false,
	lock:true,
	overregexp:/^(.+)(\.[A-Za-z]+)$/,
	outregexp:/^(.+)_over(\.[A-Za-z]+)$/
};

$(document).ready(function(){	
	//erase prevbutton
	$("#prevbutton").fadeTo(0 , 0);
	
	//get size
	viewerdata.imagenum = $("#imagelist a").size();
	viewerdata.imageheight = $("#imagearea").height();
	viewerdata.imagewidth = $("#imagearea").width();
	$("#imagelist li").each(function(){
		viewerdata.allwidth += parseInt($(this).css("width")) + parseInt($(this).css("margin-left")) + parseInt($(this).css("margin-right"));
	});

	//pre loading
	var i;
	for(i = 0 ; i < viewerdata.imagenum ; i++){
		var str = "#imagelist a:eq(" + i.toString() + ")";
		jQuery("<img>").attr("src", $(str).attr("href"));
	}

	//set size
	$("#imagelist ul").width(viewerdata.allwidth);
		
	//attach first img		
	$("#imagearea").append("<img src='' alt='" + $("#imagelist img:first").attr("alt") + "' class='displaynone' />");
		$("#imagearea img:first").bind("load", function() {
		$(this).css("margin-left" , (viewerdata.imagewidth - $(this).width()) / 2).css("margin-top" , (viewerdata.imageheight - $(this).height()) / 2);
		$("#imagearea img:first").fadeIn("fast");
		viewerdata.lock = false;
	});	
	$("#imagearea img:first").attr("src",$("#imagelist a:first").attr("href"));
	$("#imagelist img:first").fadeTo("fast" , 0.5);

	//thumnail mouse
	$("#imagelist img").mouseover(function(){
		$(this).fadeTo("fast" , 0.5);
	}).mouseout(function(){
		if(viewerdata.nownum === $("#imagelist img").index(this))return;
		$(this).fadeTo("fast" , 1);
	});
	
	//thumnail click
	$("#imagelist a").click(function(){
		if(viewerdata.lock !== false)return false;
		if(viewerdata.nownum === $("#imagelist a").index(this))return false;
		viewerdata.lock = true;		

		var str = "#imagelist img:eq(" + viewerdata.nownum.toString()  + ")";
		$(str).fadeTo("fast" , 1);

		viewerdata.nownum = $("#imagelist a").index(this);
		
		$("#imagearea img:first").before("<img src='' alt='" + $("img" , this).attr("alt") + "' class='displaynone' />");
		
		$("#imagearea img:first").bind("load", function() {
			$(this).css("margin-left" , (viewerdata.imagewidth - $(this).width()) / 2).css("margin-top" , (viewerdata.imageheight - $(this).height()) / 2);
			$("#imagearea img:first").fadeIn("fast");	
			$("#imagearea img:last").fadeOut("fast",function(){
				$(this).remove();
				viewerdata.lock = false;
			});
		});
		$("#imagearea img:first").attr("src",$(this).attr("href"));

		viewerdata.nownum = $("#imagelist a").index(this);

		if(viewerdata.imgtextarray[viewerdata.nownum] !== undefined){
			$("#aboutarea").html(viewerdata.imgtextarray[viewerdata.nownum]);
		}
		else{
			$("#aboutarea").html("");
		}
		
		return false;
	});

	//button mouse
	$("#nextbutton , #prevbutton").mouseover(function(){
		$(this).attr("src" , $(this).attr("src").replace(viewerdata.overregexp,"$1_over$2"));
	}).mouseout(function(){
		$(this).attr("src" , $(this).attr("src").replace(viewerdata.outregexp,"$1$2"));
	});
	
	$("#prevbutton").mousedown(function(){
		viewerdata.pclick = true;
		scrollPrev();
	}).mouseup(function(){
		viewerdata.pclick = false;
	});
	
	$("#nextbutton").mousedown(function(){
		viewerdata.nclick = true;
		scrollNext();
	}).mouseup(function(){
		viewerdata.nclick = false;
	});
	
	//scroll right
	function scrollPrev(){
		$("#nextbutton").fadeTo("fast" , 1.0);
		if(parseInt($("#imagelist ul").css("margin-left")) > -1){
			$("#imagelist ul").animate({marginLeft : "0px"} , "fast" , "linear");
			$("#prevbutton").fadeTo("fast" , 0);
			return;
		}
		if(viewerdata.pclick !== true)return;
		if(parseInt($("#imagelist ul").css("margin-left")) < 0){
			$("#imagelist ul").animate({marginLeft : (parseInt($("#imagelist ul").css("margin-left")) + viewerdata.speed) + "px"} , "fast" , "linear" , scrollPrev);
		}
	}

	//scroll left
	function scrollNext(){
		$("#prevbutton").fadeTo("fast" , 1.0);
		if(parseInt($("#imagelist ul").css("margin-left")) < -1 * (viewerdata.allwidth - $("#imagelist").width()) + 1){
			$("#imagelist ul").animate({marginLeft : -1 * (viewerdata.allwidth - $("#imagelist").width())+ "px"} , "fast" , "linear");
			$("#nextbutton").fadeTo("fast" , 0);
			return;
		}
		if(viewerdata.nclick !== true)return;
		if(parseInt($("#imagelist ul").css("margin-left")) > -1 * (viewerdata.allwidth - $("#imagelist").width())){
			$("#imagelist ul").animate({marginLeft : (parseInt($("#imagelist ul").css("margin-left")) - viewerdata.speed) + "px"} , "fast" , "linear" , scrollNext);
		}
	}
	
	
	//xml load
	$.ajax({
		url: 'xml/js011.xml',
		dataType: 'xml',
		success : function(data){
			$("imgtext",data).each(function(){
				viewerdata.imgtextarray.push($(this).text());
			});
			$("#aboutarea").html(viewerdata.imgtextarray[viewerdata.nownum]);
		}
	});
});
=======
//grobal data
var viewerdata = {
	imagenum:0,
	imgtextarray:[],
	nownum:0,
	allwidth:0,
	imagewidth:0,
	iamgeheight:0,
	speed:70,
	pclick:false,
	nclick:false,
	lock:true,
	overregexp:/^(.+)(\.[A-Za-z]+)$/,
	outregexp:/^(.+)_over(\.[A-Za-z]+)$/
};

$(document).ready(function(){	
	//erase prevbutton
	$("#prevbutton").fadeTo(0 , 0);
	
	//get size
	viewerdata.imagenum = $("#imagelist a").size();
	viewerdata.imageheight = $("#imagearea").height();
	viewerdata.imagewidth = $("#imagearea").width();
	$("#imagelist li").each(function(){
		viewerdata.allwidth += parseInt($(this).css("width")) + parseInt($(this).css("margin-left")) + parseInt($(this).css("margin-right"));
	});

	//pre loading
	var i;
	for(i = 0 ; i < viewerdata.imagenum ; i++){
		var str = "#imagelist a:eq(" + i.toString() + ")";
		jQuery("<img>").attr("src", $(str).attr("href"));
	}

	//set size
	$("#imagelist ul").width(viewerdata.allwidth);
		
	//attach first img		
	$("#imagearea").append("<img src='' alt='" + $("#imagelist img:first").attr("alt") + "' class='displaynone' />");
		$("#imagearea img:first").bind("load", function() {
		$(this).css("margin-left" , (viewerdata.imagewidth - $(this).width()) / 2).css("margin-top" , (viewerdata.imageheight - $(this).height()) / 2);
		$("#imagearea img:first").fadeIn("fast");
		viewerdata.lock = false;
	});	
	$("#imagearea img:first").attr("src",$("#imagelist a:first").attr("href"));
	$("#imagelist img:first").fadeTo("fast" , 0.5);

	//thumnail mouse
	$("#imagelist img").mouseover(function(){
		$(this).fadeTo("fast" , 0.5);
	}).mouseout(function(){
		if(viewerdata.nownum === $("#imagelist img").index(this))return;
		$(this).fadeTo("fast" , 1);
	});
	
	//thumnail click
	$("#imagelist a").click(function(){
		if(viewerdata.lock !== false)return false;
		if(viewerdata.nownum === $("#imagelist a").index(this))return false;
		viewerdata.lock = true;		

		var str = "#imagelist img:eq(" + viewerdata.nownum.toString()  + ")";
		$(str).fadeTo("fast" , 1);

		viewerdata.nownum = $("#imagelist a").index(this);
		
		$("#imagearea img:first").before("<img src='' alt='" + $("img" , this).attr("alt") + "' class='displaynone' />");
		
		$("#imagearea img:first").bind("load", function() {
			$(this).css("margin-left" , (viewerdata.imagewidth - $(this).width()) / 2).css("margin-top" , (viewerdata.imageheight - $(this).height()) / 2);
			$("#imagearea img:first").fadeIn("fast");	
			$("#imagearea img:last").fadeOut("fast",function(){
				$(this).remove();
				viewerdata.lock = false;
			});
		});
		$("#imagearea img:first").attr("src",$(this).attr("href"));

		viewerdata.nownum = $("#imagelist a").index(this);

		if(viewerdata.imgtextarray[viewerdata.nownum] !== undefined){
			$("#aboutarea").html(viewerdata.imgtextarray[viewerdata.nownum]);
		}
		else{
			$("#aboutarea").html("");
		}
		
		return false;
	});

	//button mouse
	$("#nextbutton , #prevbutton").mouseover(function(){
		$(this).attr("src" , $(this).attr("src").replace(viewerdata.overregexp,"$1_over$2"));
	}).mouseout(function(){
		$(this).attr("src" , $(this).attr("src").replace(viewerdata.outregexp,"$1$2"));
	});
	
	$("#prevbutton").mousedown(function(){
		viewerdata.pclick = true;
		scrollPrev();
	}).mouseup(function(){
		viewerdata.pclick = false;
	});
	
	$("#nextbutton").mousedown(function(){
		viewerdata.nclick = true;
		scrollNext();
	}).mouseup(function(){
		viewerdata.nclick = false;
	});
	
	//scroll right
	function scrollPrev(){
		$("#nextbutton").fadeTo("fast" , 1.0);
		if(parseInt($("#imagelist ul").css("margin-left")) > -1){
			$("#imagelist ul").animate({marginLeft : "0px"} , "fast" , "linear");
			$("#prevbutton").fadeTo("fast" , 0);
			return;
		}
		if(viewerdata.pclick !== true)return;
		if(parseInt($("#imagelist ul").css("margin-left")) < 0){
			$("#imagelist ul").animate({marginLeft : (parseInt($("#imagelist ul").css("margin-left")) + viewerdata.speed) + "px"} , "fast" , "linear" , scrollPrev);
		}
	}

	//scroll left
	function scrollNext(){
		$("#prevbutton").fadeTo("fast" , 1.0);
		if(parseInt($("#imagelist ul").css("margin-left")) < -1 * (viewerdata.allwidth - $("#imagelist").width()) + 1){
			$("#imagelist ul").animate({marginLeft : -1 * (viewerdata.allwidth - $("#imagelist").width())+ "px"} , "fast" , "linear");
			$("#nextbutton").fadeTo("fast" , 0);
			return;
		}
		if(viewerdata.nclick !== true)return;
		if(parseInt($("#imagelist ul").css("margin-left")) > -1 * (viewerdata.allwidth - $("#imagelist").width())){
			$("#imagelist ul").animate({marginLeft : (parseInt($("#imagelist ul").css("margin-left")) - viewerdata.speed) + "px"} , "fast" , "linear" , scrollNext);
		}
	}
	
	
	//xml load
	$.ajax({
		url: 'xml/js011.xml',
		dataType: 'xml',
		success : function(data){
			$("imgtext",data).each(function(){
				viewerdata.imgtextarray.push($(this).text());
			});
			$("#aboutarea").html(viewerdata.imgtextarray[viewerdata.nownum]);
		}
	});
});
>>>>>>> origin

$.fn.numeric = function() {
this.keydown(function(event){
	if((event.keyCode==9)||(event.keyCode==8)||(event.keyCode==110)){
	}else{
 		if((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode>95)&&(event.keyCode<106)){
		}else{
			
		event.preventDefault();
		}
	}
});	
};

$.fn.emptyField = function(){
this.css("border","1px solid red").attr("placeholder","Empty Field!");
}

/**
 * Copyright (C) 2019 Paladin Business Solutions
 *
 */ 

function reveal_it() {
	var x = document.getElementById("myClientSecret");
    var y = document.getElementById("myRCPassword");    
	if (x.type === "password") {
	  x.type = "text";
	} 
	if (y.type === "password") {
		y.type = "text";
	} 
}

function hide_it() {
	var x = document.getElementById("myClientSecret");
    var y = document.getElementById("myRCPassword");    
	if (x.type === "text") {	  
	  x.type = "password";
	}
	if (y.type === "text") {
	    y.type = "password";
	}
}
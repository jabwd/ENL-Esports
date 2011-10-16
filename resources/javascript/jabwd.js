function getLadderInfo(teamID)
{
	var value = document.getElementById("ladderSelect").value;
	
	if( value && value != '0' )
	{
		$.get('lib/ladderTeamStatus.php?ladderID='+value+'&teamID='+teamID,function(data)
		{
			if( data == 'Error' )
			{
				// don't do anything
			}
			else 
			{
				document.getElementById('toggleLadderActivity').value = data;
				$("#toggleLadderActivityTR").fadeIn(500);
			}
		});	
	}
	else if( value == '0' )
	{
		$("#toggleLadderActivityTR").fadeOut(500);
	}
}

function slideToggle(elementID)
{
	$("#"+elementID).slideToggle(100,"swing",function(){});
}

function iforgot()
{
	window.location = 'http://www.enl-esports.com/iforgot.php?username='+document.getElementById("usernameField").value;
}

// this function restores the website by going to the specified url..
function checkHash()
{
	var hash = window.location.hash;
	
	if( hash.length > 0 )
	{
		hash = hash.substr(1,hash.length);
		var variables = hash.split("&");
		if( hash != "index" )
		{
			//var URL = hash + ".php?ajax=true";
			var URL = "";
			if( variables.length > 0 )
				URL = variables[0] + ".php?ajax=true";
			else
				URL = hash + ".php?ajax=true";
			for(var i=1;i<variables.length;i++)
			{
				URL = URL + "&"+variables[i];
			}
		 	loadPage(URL,hash);
		}
	}
}

function loadPage(URL,friendlyURL)
{
	URL = "http://www.enl-esports.com/"+URL;
	$("#main").fadeOut(50,function(){
		$("#main").html("<div id=\"loader\"><img src=\"resources/loader.gif\"/></div>");
		$("#main").fadeIn(100);
	});
	$("#preContentLoader").load(URL,function(){
		$("#main").fadeOut(200,function(){
			$("#main").html($("#preContentLoader").html());
			$(".current").removeClass('current');
			$("#menu_"+friendlyURL).addClass('current');
			$("#main").fadeIn(200,function(){
			});
			if( friendlyURL.length > 0 )
				window.location.hash = friendlyURL;
			else
				window.location.hash = URL;
			document.title = "ENL - "+friendlyURL;
		});
	});
	
	// make sure that the actual link isn't called
	return false;
}



function loadTZACID(tzacID,itemID)
{
	$("#preContentLoader").load("http://www.enl-esports.com/lib/tzacStatus.php?tzacID="+tzacID,function(){
		$("#"+itemID).fadeOut(100,function(){
			$("#"+itemID).html($("#preContentLoader").html());
			$("#"+itemID).fadeIn(100);
		});
		//alert($("#preContentLoader").html());
	});
}

function toggleAvatarUpload()
{
	$(".box").slideToggle(100,function(){});
}

function hideBox()
{
	$(".box").hide();
}

function showEditRules()
{
	//var test = " ehtoahnror ror";
	//test = test.replace(/ror/g,"lol");
	//alert(test);
	$("#rulesBox").fadeOut(100,function(){});
	var rules = $("#rulesBox").html();
	//alert(rules);
	rules = rules.replace(/<br>/g,"");
	rules = rules.replace(/</g,"[");
	rules = rules.replace(/>/g,"]");
	$("#rulesArea").html(rules);
	//alert(rules);
	$("#rulesForm").fadeIn(100,function(){});
}

function clickPage(event)
{
	var pos_x = event.offsetX
	var pos_y = event.offsetY
	alert("("+pos_x+","+pos_y+")");
}
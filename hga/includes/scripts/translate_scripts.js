// Tournament Formats
const LANG_KDF     = 0;
const LANG_ENGLISH = 1;
const LANG_FIORE   = 2;
const LANG_MODERN  = 3;
const LANG_COUNT   = 4;

const translateLanguages 	= ["KdF","English","Fiore","HEMA Tournament"];
const translateArray = [
	["Vom Tag",		"Shoulder Guard",			"Posta di Donna", 	"Point-Forward-Hilt-At-Groin"],
	["Pflug",		"Plow",						"Side-Pointy-Thing","Point-Forward-Hilt-At-Groin"],
	["Alber",		"Low Guard",				"Boar's Tooth", 	"Point-Forward-Hilt-At-Groin"],
	["Ochs",		"Ox",						"Finestra", 		"Point-Forward-Hilt-At-Groin"],
	["Hut",			"Guard",						"Posta", 			"Point-Forward-Hilt-At-Groin"],
	["Oberhau",		"Descending Cut",			"Fendente",			"Flat-To-Head"],
	["Unterhau",	"Ascending Cut",			"Sottano",			"Flat-To-Ribs"],
	["Zwerhau",		"(Who Doesn't Say Zwerhau?)",	"Fake-Mezzano",	"Helicopter"],
	["Mittelhau-That-Doesn't Suck",	"Fiore-Mittelhau","Mezzano",	"Helicopter-That-Sucks"],
	["Ansetzen",	"Thrust-That-Doesn't-Suck",	"Punta",			"Absetzen"],
	["Absetzen",	"Parry-That-Doesn't-Suck",	"Scambiar",			"Ansetzen"],
	["Ringen",		"Wrestling",				"Abrazare",			"Hold-Call"],
	["Versetzen",	"Parry",					"Coverta",			"-file-not-found-"],
];


/************************************************************************************/

$('#translate-list').append("<tr>");
for (var lang = 0; lang < LANG_COUNT; lang++) {

	$('#translate-list').append("<th>"+translateLanguages[lang]+"</th>");
	$('#translate-from').append("<option value='"+lang+"'>"+translateLanguages[lang]+"</option>");
	$('#translate-to').append("<option value='"+lang+"'>"+translateLanguages[lang]+"</option>");
}
$('#translate-list').append("</tr>");

for (var term = 1; term < translateArray.length; term++) {
	$('#translate-list').append("<tr>");
	for (var lang = 0; lang < LANG_COUNT; lang++) {

		$('#translate-list').append("<td>"+translateArray[term][lang]+"</td>");
	}
	$('#translate-list').append("</tr>");
}

/************************************************************************************/

/************************************************************************************/

String.prototype.replaceAll = function(strReplace, strWith) {
    // See http://stackoverflow.com/a/3561711/556609
    var esc = strReplace.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    var reg = new RegExp(esc, 'ig');
    return this.replace(reg, strWith);
};

/************************************************************************************/

function translateGame(){
	var infoItems = document.getElementsByClassName("game-info");

	langFrom = $('#translate-from').val();
	langTo = $('#translate-to').val();

	if(langFrom == -1 || langTo == -1){
		return;
	}

	for (var i = 0; i < infoItems.length; i++) {
		var text = infoItems.item(i).innerHTML;

		// Deliberately start at 1 because 0 is the titles
		for (var term = 0; term < translateArray.length; term++) {
			text = text.replaceAll(translateArray[term][langFrom],translateArray[term][langTo]);
		}
		



		infoItems.item(i).innerHTML = text;
	}
}

/************************************************************************************/
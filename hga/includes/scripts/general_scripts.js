

// CONSTANT DECLARATIONS ///////////////////////////////////////
const AJAX_LOCATION = "/hga/includes/functions/AJAX.php";


/************************************************************************************/

function toggle(divName, divName2 = null) {
	
    var x = document.getElementById(divName);
    if (x.offsetHeight == 0) {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
    
    if(divName2 == null){return;}
    
    var x = document.getElementById(divName2);
    if (x.offsetHeight == 0) {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
    
}

/************************************************************************************/

function toggleClass(className){
    $('.'+className).toggle();
}

/************************************************************************************/

function show(text){
// Alias of console.log() used to maintain symetry with data dump function from php
    console.log(text);
}

/**********************************************************************/

function openModal(modalName){
	$("#"+modalName).foundation("open");
}

/**********************************************************************/

function safeReload(){
	location.reload();
}

/**********************************************************************/

function submitForm(formID, formName, directMode = false){
   
    if(directMode == false){
        form = document.getElementById(formID);
    } else {
        form = formID;
    }
    

    var formNameInput = document.createElement('input');
    formNameInput.type = 'hidden';
    formNameInput.name = 'formName';
    formNameInput.value = formName;
    form.appendChild(formNameInput);
    
    form.submit();
}

/**********************************************************************/

function toggleWithButton(className, onStatus){

    if(onStatus == true){
        $("."+className).show();
        $("."+className+'-on-button').hide();
    } else {
        $("."+className).hide();
        $("."+className+'-on-button').show();
    }

}


/**********************************************************************/

function var_dump(obj) {
    var out = '';

    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    return (out);

}

/**********************************************************************/
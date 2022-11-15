/************************************************************************************/

$("#tag-input").on('change keydown paste input', function(){
      tagTest();
});

const tag_input = document.querySelector('#tag-input');
const suggestions = document.querySelector('.tag-suggestions ul');
suggestions.addEventListener('mouseup', useSuggestion);

function tagTest(){

    var text = $("#tag-input").val();
    var allTags = text.split(' ');
    var lastWord = allTags.pop();

    if(lastWord.length == 0){
        suggestions.innerHTML = '';
    } else {
        var tags_startsWith = tagList.filter(option => option.startsWith(lastWord));
        var tags_includes = tagList.filter(option => option.includes(lastWord));
        tags_includes = tags_includes.filter( function(item){
                return !tags_startsWith.includes(item); 
            });

        var tagsFromList = tags_startsWith.concat(tags_includes);
        tagsFromList = tagsFromList.slice(0, 4);

        htmlList = '<li>'+tagsFromList.join('</li><li>')+'</li>';
        suggestions.innerHTML = htmlList;
    }

    //var a = document.getElementById('game-table-tag-list-7');

    var listOfCells = document.getElementsByClassName("game-list-of-tags");
    var allTagIDs = [];

    for (let i = 0; i < allTags.length; ++i) {
        var tagName = allTags[i];

        if(tagName.length > 1){
            allTagIDs.push(tagIdsFromName[tagName]);
        }
    }



    Object.keys(gameListWithTags).forEach(function(gameID) {

        var tagsMissing = false;

        for (let j=0; j < allTagIDs.length; ++j) {

            var tagFound = false;

            for (let k=0; k < gameListWithTags[gameID]['tags'].length; ++k) {

                if(gameListWithTags[gameID]['tags'][k].tagID == allTagIDs[j]){
                    tagFound = true;
                }
            }

            if(tagFound == false){
                tagsMissing = true;
            }
        }

        if(tagsMissing == true){
            $("#game-table-row-"+gameID).hide();
        } else {
            $("#game-table-row-"+gameID).show();
        }

    });


}

/************************************************************************************/

function useSuggestion(e) {
    
    var text = $("#tag-input").val();
    var lastIndex = text.lastIndexOf(" ");
    text = text.substring(0, lastIndex);
    text = text+" "+e.target.innerText+" ";
    tag_input.value = text;

    tag_input.focus();
    suggestions.innerHTML = '';

    tagTest();

}

/************************************************************************************/

function appendToTagEntry(tagName){

    var text = $("#tag-input").val();
    text = text + " " + tagName + " ";
    $("#tag-input").val(text);

    tagTest();

}

/************************************************************************************/

/************************************************************************************/

$("#tag-input").on('change keydown paste input', function(){
      tagTest();
});

$("#name-filter").on('change paste input', function(){
      nameFilter();
});

const tag_input = document.querySelector('#tag-input');
const suggestions = document.querySelector('.tag-suggestions ul');
suggestions.addEventListener('mouseup', useSuggestion);

function tagTest(){

    document.getElementById('name-filter').value = '';

    var text = $("#tag-input").val();
    text = text.toLowerCase();
    $("#tag-input").val(text);
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

    var listOfCells = document.getElementsByClassName("game-list-of-tags");
    var allTagIDs = [];

    if(document.getElementById("tag-url")) {

        var permalink = "https://www.gd4h.org/hga/gameList.php";
        var linkIndex = 0;

        for (let i = 0; i < allTags.length; ++i) {
            var tagName = allTags[i];

            if(tagName.length > 1){
                allTagIDs.push(tagIdsFromName[tagName]);

                if(linkIndex == 0){
                    permalink = permalink + '?';
                } else {
                    permalink = permalink + '&';
                }
                permalink = permalink + 't' + linkIndex + '=' + tagName;
                linkIndex++;
            }

        }

        if(linkIndex != 0){
            var urlText = "Link To Current Tags: <a href='" + permalink + "'>";
            urlText = urlText + permalink + "</a>";
            document.getElementById('tag-url').innerHTML = urlText;
        } else {
            document.getElementById('tag-url').innerHTML = '';
        }
    }




    if(typeof gameListWithTags !== 'undefined'){
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

function nameFilter(){

    document.getElementById('tag-input').value = '';
    if(document.getElementById("tag-url")) {
      document.getElementById('tag-url').innerHTML = ''
    }

    var filterText = $("#name-filter").val().toLowerCase();


    var listOfCells = document.getElementsByClassName("game-list-of-tags");

    Object.keys(gameListWithTags).forEach(function(gameID) {
        var gameName = gameListWithTags[gameID]['gameName'].toLowerCase();

        if(gameName.includes(filterText) == true){
            $("#game-table-row-"+gameID).show();
        } else {
            $("#game-table-row-"+gameID).hide();
        }
    });

}


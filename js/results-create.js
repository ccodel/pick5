//-------INPUT FORM LOGICS--------//
//Get all the divs and elements we need
var form = document.getElementById("session-set-results-form");

var dropDown = document.getElementById("session-dropdown");
var hidden = document.getElementById("session-num");

function hideAll() {
    form.style.display = "none";
}

function showAll() {
    form.style.display = "inline";
    form.style.display = "center";
}

if (dropDown.value == "blank")
    hideAll();
else
    showAll();

dropDown.addEventListener("change", function () {return hideAll();});

function clearChoices(indexNum) {
    return function () {
        var leftCover = document.getElementById("team-left-victor-" + indexNum);
        var rightCover = document.getElementById("team-right-victor-" + indexNum);

        leftCover.checked = false;
        rightCover.checked = false;
    };
}

var buttons = document.getElementsByClassName("clear-button");

for (var i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener("click", clearChoices(i));
}
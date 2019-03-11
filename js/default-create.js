//-------INPUT FORM LOGICS--------//
//Get all the divs and elements we need
var form = document.getElementById("session-set-defaults-form");

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
        var leftLoss = document.getElementById("team-left-loss-" + indexNum);
        var leftUnderdog = document.getElementById("team-left-underdog-" + indexNum);
        var rightLoss = document.getElementById("team-right-loss-" + indexNum);
        var rightUnderdog = document.getElementById("team-right-underdog-" + indexNum);

        leftLoss.checked = false;
        leftUnderdog.checked = false;
        rightLoss.checked = false;
        rightUnderdog.checked = false;
    };
}

var buttons = document.getElementsByClassName("clear-button");

for (var i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener("click", clearChoices(i));
}
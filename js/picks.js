var toggle = document.getElementById("toggle");
var leftHeader = document.getElementById("left-header");
var rightHeader = document.getElementById("right-header");
var numOfGames = document.getElementById("games-to-play").value;

var hidden = document.getElementById("session");

function toggleView () {
    if (toggle.innerHTML.includes("favored")) {
        toggle.innerHTML = "View by away/home";

        var leftHeader = document.getElementById("left-header");
        var rightHeader = document.getElementById("right-header");
        leftHeader.innerHTML = "Favored";
        rightHeader.innerHTML = "Underdog";

        for (var i = 0; i < numOfGames; i++) {
            //Organize the game by favored, underdog
            var left = document.getElementById("left-team-" + i);
            var right = document.getElementById("right-team-" + i);
            var spread = document.getElementById("left-spread-" + i);
            if (spread.innerHTML.includes("-") && left.parentNode.className === "right-team-box") {
                var leftParent = left.parentNode;
                var rightParent = right.parentNode;
                leftParent.removeChild(leftParent.childNodes[0]);
                rightParent.removeChild(rightParent.childNodes[0]);
                leftParent.appendChild(right);
                rightParent.appendChild(left);
            } else if (spread.innerHTML.includes("+") && left.parentNode.className === "left-team-box") {
                var leftParent = left.parentNode;
                var rightParent = right.parentNode;
                leftParent.removeChild(leftParent.childNodes[0]);
                rightParent.removeChild(rightParent.childNodes[0]);
                leftParent.appendChild(right);
                rightParent.appendChild(left);
            }
        }
    } else {
        toggle.innerHTML = "View by favored/underdog";

        var leftHeader = document.getElementById("left-header");
        var rightHeader = document.getElementById("right-header");
        leftHeader.innerHTML = "Away";
        rightHeader.innerHTML = "Home";

        for (var i = 0; i < numOfGames; i++) {
            //Organize the game by favored, unfavored
            var left = document.getElementById("left-team-" + i);
            var right = document.getElementById("right-team-" + i);
            if (left.parentNode.classList == "right-team-box") {
                var leftParent = left.parentNode;
                var rightParent = right.parentNode;
                leftParent.removeChild(leftParent.childNodes[0]);
                rightParent.removeChild(rightParent.childNodes[0]);
                leftParent.appendChild(right);
                rightParent.appendChild(left);
            }
        }
    }
}

toggle.addEventListener("click", toggleView);

function clearChoices(indexNum) {
    return function () {
        var leftPick = document.getElementById("team-left-victor-" + indexNum);
        var rightPick = document.getElementById("team-right-victor-" + indexNum);

        if (!leftPick.disabled && !rightPick.disabled) {
            leftPick.checked = false;
            rightPick.checked = false;
        }
    };
}

//Add clear pick button event listener
if (hidden.value != null) {
    for (var i = 0; i < numOfGames; i++) {
        var button = document.getElementById("clear-" + i);
        if (button !== null)
            button.addEventListener("click", clearChoices(i));
    }
}
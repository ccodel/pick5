var alphaButton = document.getElementById("alphabetize-button");
var ytdPLButton = document.getElementById("ytd-pl-button");

function sort(func, sessionBool) {
    return function () {
        var userNum = document.getElementById("user-num").innerHTML;

        for (var i = 0; i < userNum - 1; i++) {
            for (var j = 0; j < userNum - 1 - i; j++) {
                func(j, j + 1, sessionBool);
            }
        }
    }
}

function nameCompare(firstNum, secondNum, sessionBool) {
    var firstYTDName = document.getElementById("ytd-name-" + firstNum);
    var secondYTDName = document.getElementById("ytd-name-" + secondNum);
    var firstYTDPL = document.getElementById("ytd-pl-" + firstNum);
    var secondYTDPL = document.getElementById("ytd-pl-" + secondNum);

    var ytdParent = firstYTDName.parentElement.parentElement;

    if (firstYTDName.innerHTML.localeCompare(secondYTDName.innerHTML) === 1) {
        ytdParent.insertBefore(secondYTDName.parentElement, firstYTDName.parentElement);
        firstYTDName.parentElement.setAttribute("id", "ytd-row-" + secondNum);
        secondYTDName.parentElement.setAttribute("id", "ytd-row-" + firstNum);
        firstYTDName.setAttribute("id", "ytd-name-" + secondNum);
        secondYTDName.setAttribute("id", "ytd-name-" + firstNum);
        firstYTDPL.setAttribute("id", "ytd-pl-" + secondNum);
        secondYTDPL.setAttribute("id", "ytd-pl-" + firstNum);
    }
}

function plCompare(firstNum, secondNum, sessionBool) {
    var firstYTDName = document.getElementById("ytd-name-" + firstNum);
    var secondYTDName = document.getElementById("ytd-name-" + secondNum);
    var firstYTDPL = document.getElementById("ytd-pl-" + firstNum);
    var secondYTDPL = document.getElementById("ytd-pl-" + secondNum);

    var ytdParent = firstYTDName.parentElement.parentElement;

    if ((sessionBool && parseInt(firstYTDPL.innerHTML) < parseInt(secondYTDPL.innerHTML)) 
        || (!sessionBool && parseInt(firstYTDPL.innerHTML) < parseInt(secondYTDPL.innerHTML))) {
        ytdParent.insertBefore(secondYTDName.parentElement, firstYTDName.parentElement);
        firstYTDName.parentElement.setAttribute("id", "ytd-row-" + secondNum);
        secondYTDName.parentElement.setAttribute("id", "ytd-row-" + firstNum);
        firstYTDName.setAttribute("id", "ytd-name-" + secondNum);
        secondYTDName.setAttribute("id", "ytd-name-" + firstNum);
        firstYTDPL.setAttribute("id", "ytd-pl-" + secondNum);
        secondYTDPL.setAttribute("id", "ytd-pl-" + firstNum);
    }
}

alphaButton.addEventListener("click", sort(nameCompare, true));
ytdPLButton.addEventListener("click", sort(plCompare, false));

sort(plCompare, false) ();
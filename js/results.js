var alphaButton = document.getElementById("alphabetize-button");
var plButton = document.getElementById("pl-button");
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
    var firstName = document.getElementById("name-" + firstNum);
    var secondName = document.getElementById("name-" + secondNum);
    var firstPL = document.getElementById("pl-" + firstNum);
    var secondPL = document.getElementById("pl-" + secondNum);
    var firstYTDName = document.getElementById("ytd-name-" + firstNum);
    var secondYTDName = document.getElementById("ytd-name-" + secondNum);
    var firstYTDPL = document.getElementById("ytd-pl-" + firstNum);
    var secondYTDPL = document.getElementById("ytd-pl-" + secondNum);

    var parent = firstName.parentElement.parentElement;
    var ytdParent = firstYTDName.parentElement.parentElement;

    if (firstName.innerHTML.localeCompare(secondName.innerHTML) === 1) {
        parent.insertBefore(secondName.parentElement, firstName.parentElement);
        firstName.parentElement.setAttribute("id", "row-" + secondNum);
        secondName.parentElement.setAttribute("id", "row-" + firstNum);
        firstName.setAttribute("id", "name-" + secondNum);
        secondName.setAttribute("id", "name-" + firstNum);
        firstPL.setAttribute("id", "pl-" + secondNum);
        secondPL.setAttribute("id", "pl-" + firstNum);

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
    var firstName = document.getElementById("name-" + firstNum);
    var secondName = document.getElementById("name-" + secondNum);
    var firstPL = document.getElementById("pl-" + firstNum);
    var secondPL = document.getElementById("pl-" + secondNum);
    var firstYTDName = document.getElementById("ytd-name-" + firstNum);
    var secondYTDName = document.getElementById("ytd-name-" + secondNum);
    var firstYTDPL = document.getElementById("ytd-pl-" + firstNum);
    var secondYTDPL = document.getElementById("ytd-pl-" + secondNum);

    var parent = firstName.parentElement.parentElement;
    var ytdParent = firstYTDName.parentElement.parentElement;

    if ((sessionBool && parseInt(firstPL.innerHTML) < parseInt(secondPL.innerHTML)) 
        || (!sessionBool && parseInt(firstYTDPL.innerHTML) < parseInt(secondYTDPL.innerHTML))) {
        parent.insertBefore(secondName.parentElement, firstName.parentElement);
        firstName.parentElement.setAttribute("id", "row-" + secondNum);
        secondName.parentElement.setAttribute("id", "row-" + firstNum);
        firstName.setAttribute("id", "name-" + secondNum);
        secondName.setAttribute("id", "name-" + firstNum);
        firstPL.setAttribute("id", "pl-" + secondNum);
        secondPL.setAttribute("id", "pl-" + firstNum);

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
plButton.addEventListener("click", sort(plCompare, true));
ytdPLButton.addEventListener("click", sort(plCompare, false));
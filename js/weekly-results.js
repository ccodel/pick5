var alphaButton = document.getElementById("alphabetize-button");
var plButton = document.getElementById("pl-button");

function sort(func, sessionBool) {
    return function () {
        var userNum = document.getElementById("user-num").innerHTML;

        for (var i = 0; i < userNum - 1; i++)
            for (var j = 0; j < userNum - 1 - i; j++)
                func(j, j + 1, sessionBool);
    }
}

function nameCompare(firstNum, secondNum, sessionBool) {
    var firstName = document.getElementById("name-" + firstNum);
    var secondName = document.getElementById("name-" + secondNum);
    var firstPL = document.getElementById("pl-" + firstNum);
    var secondPL = document.getElementById("pl-" + secondNum);

    var parent = firstName.parentElement.parentElement;

    if (firstName.innerHTML.localeCompare(secondName.innerHTML) === 1) {
        parent.insertBefore(secondName.parentElement, firstName.parentElement);
        firstName.parentElement.setAttribute("id", "row-" + secondNum);
        secondName.parentElement.setAttribute("id", "row-" + firstNum);
        firstName.setAttribute("id", "name-" + secondNum);
        secondName.setAttribute("id", "name-" + firstNum);
        firstPL.setAttribute("id", "pl-" + secondNum);
        secondPL.setAttribute("id", "pl-" + firstNum);
    }
}

function plCompare(firstNum, secondNum, sessionBool) {
    var firstName = document.getElementById("name-" + firstNum);
    var secondName = document.getElementById("name-" + secondNum);
    var firstPL = document.getElementById("pl-" + firstNum);
    var secondPL = document.getElementById("pl-" + secondNum);

    var parent = firstName.parentElement.parentElement;

    if ((sessionBool && parseInt(firstPL.innerHTML) < parseInt(secondPL.innerHTML)) 
        || (!sessionBool && parseInt(firstYTDPL.innerHTML) < parseInt(secondYTDPL.innerHTML))) {
        parent.insertBefore(secondName.parentElement, firstName.parentElement);
        firstName.parentElement.setAttribute("id", "row-" + secondNum);
        secondName.parentElement.setAttribute("id", "row-" + firstNum);
        firstName.setAttribute("id", "name-" + secondNum);
        secondName.setAttribute("id", "name-" + firstNum);
        firstPL.setAttribute("id", "pl-" + secondNum);
        secondPL.setAttribute("id", "pl-" + firstNum);
    }
}

alphaButton.addEventListener("click", sort(nameCompare, true));
plButton.addEventListener("click", sort(plCompare, true));

sort(plCompare, true) ();
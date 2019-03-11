var textField = document.getElementById("email-text");

function onTextChange() {
    var actionHidden = document.getElementById("action-hidden-text");
    var resultsHidden = document.getElementById("results-hidden-text");
    actionHidden.setAttribute("value", this.value);
    resultsHidden.setAttribute("value", this.value);
}

textField.addEventListener("change", onTextChange);
var destHidden = document.getElementById("destination");
var picHidden = document.getElementById("picture");

var destination = destHidden.innerHTML;
var picture = picHidden.innerHTML;

console.log(picture);
console.log(destination);

document.body.parentElement.style.backgroundImage = "url('" + picture + "')";
document.body.style.opacity = 0.0;


function changeWindow() {
    window.location.replace(destination);
}

window.setTimeout(changeWindow, 5000);
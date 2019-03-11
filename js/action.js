var currentView = document.getElementById("current-view");

function toggle() {
    var teamView = document.getElementById("team-table");
    var playerView = document.getElementById("player-table");

    var cloned1 = teamView.cloneNode(true);
    var cloned2 = playerView.cloneNode(true);

    playerView.parentNode.replaceChild(cloned1, playerView);
    teamView.parentNode.replaceChild(cloned2, teamView);

    tables.insertBefore(tables.childNodes[0], tables.childNodes[1]);

    if (this.innerHTML == "By player") {
        currentView.innerHTML = "Currently viewing picks by player";
        this.innerHTML = "By team";
    } else {
        currentView.innerHTML = "Currently viewing picks by team";
        this.innerHTML = "By player";
    }
}

var button = document.getElementById("toggle");
button.addEventListener("click", toggle);
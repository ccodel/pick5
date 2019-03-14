//----TEAM LIST----//
var teamList = ["blank", "49ers", "bears", "bengals", "bills", "broncos", 
                "browns", "buccaneers", "cardinals", "chargers", "chiefs", "colts",
                "cowboys", "dolphins", "eagles", "falcons", "giants",
                "jaguars", "jets", "lions", "packers", "panthers", "patriots",
                "raiders", "rams", "ravens", "redskins", "saints", "seahawks",
                "steelers", "texans", "titans", "vikings"];
var teamNameList = ["------", "49ers", "Bears", 
                    "Bengals", "Bills", "Broncos",
                    "Browns", "Buccaneers", "Cardinals", 
                    "Chargers", "Chiefs", "Colts",
                    "Cowboys", "Dolphins", "Eagles", "Falcons",
                    "Giants", "Jaguars", "Jets", "Lions",
                    "Packers", "Panthers", "Patriots", "Raiders",
                    "Rams", "Ravens", "Redskins", "Saints",
                    "Seahawks", "Steelers", "Texans", "Titans",
                    "Vikings"];

//----HTTP CLASS----//
var HttpClient = function() {
    this.get = function(url, response) {
        var httpRequest = new XMLHttpRequest();

        httpRequest.open( "GET", url, true);            
        httpRequest.send();

        httpRequest.onreadystatechange = function() { 
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                response(httpRequest.responseText);
            }       
        }
    }
}

//-------INPUT FORM LOGICS--------//
//Get all the divs and elements we need
var form = document.getElementById("session-create-form");

var dropDown = document.getElementById("session-dropdown");

var hidden = document.getElementById("session");

var masterKickoffMonth = document.getElementById("master-kickoff-month");
var masterKickoffDay = document.getElementById("master-kickoff-day");
var masterKickoffYear = document.getElementById("master-kickoff-year");
var masterKickoffHour = document.getElementById("master-kickoff-hour");
var masterKickoffMinute = document.getElementById("master-kickoff-minute");

//var findGamesButton = document.getElementById("find-games");
var inputGamesButton = document.getElementById("enter-games");

var gameNumInput = document.getElementById("games-to-play");

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

function updateTeamImage(name, img) {
    if (name == "blank") {
        img.src = "../../img/team-logos/nfl.png";
    } else {
        img.src = "../../img/team-logos/" + name + ".png";
    }
}

function removeChoice() {
    var activeTeamList = teamList.slice(0);
    var activeTeamNameList = teamNameList.slice(0);

    //Remove teams from the list
    for (var i = 0; i < gameNumInput.value; i++) {
        var leftDropdown = document.getElementById("team-select-left-" + i);
        var rightDropdown = document.getElementById("team-select-right-" + i);

        var leftVal = leftDropdown.value;
        var rightVal = rightDropdown.value;
        
        for (var j = activeTeamList.length; j >= 1; j--) {
            if (activeTeamList[j] == leftVal) {
                activeTeamList.splice(j, 1);
                activeTeamNameList.splice(j, 1);
            }
        }
        
        for (var j = activeTeamList.length; j >= 1; j--) {
            if (activeTeamList[j] == rightVal) {
                activeTeamList.splice(j, 1);
                activeTeamNameList.splice(j, 1);
            }
        }
    }

    //Once updated lists, make that the list for unselected options
    for (var i = 0; i < gameNumInput.value; i++) {
        var leftDropdown = document.getElementById("team-select-left-" + i);
        var rightDropdown = document.getElementById("team-select-right-" + i);

        if (leftDropdown.value == "blank") {
            //Clear options first
            for (var j = leftDropdown.options.length - 1; j >= 1; j--) {
                leftDropdown.remove(j);    
            }
            
            //Add in new list
            for (var j = 1; j < activeTeamList.length; j++) {
                var optionElem = document.createElement("option");
                optionElem.setAttribute("value", activeTeamList[j]);
                optionElem.text = activeTeamNameList[j];
                leftDropdown.appendChild(optionElem);
            }
        }
        
        if (rightDropdown.value == "blank") {
            //Clear options first
            for (var j = rightDropdown.options.length - 1; j >= 1; j--) {
                rightDropdown.remove(j);    
            }
            
            //Add in new list
            for (var j = 1; j < activeTeamList.length; j++) {
                var optionElem = document.createElement("option");
                optionElem.setAttribute("value", activeTeamList[j]);
                optionElem.text = activeTeamNameList[j];
                rightDropdown.appendChild(optionElem);
            }
        }
    }
}

//Handling of team selection on dropdown
function newTeamSelected() {
    var parentDiv = this.parentElement;
    var teamName = this.value;
    var image = parentDiv.children[0];

    updateTeamImage(teamName, image);
}

function createDropdown(idValue) {
    var selectElem = document.createElement("select");
    selectElem.setAttribute("id", idValue);
    selectElem.setAttribute("name", idValue);
    for (var i = 0; i < teamList.length; i++) {
        var optionElem = document.createElement("option");
        optionElem.setAttribute("value", teamList[i]);
        optionElem.text = teamNameList[i];
        selectElem.appendChild(optionElem);
    }

    selectElem.addEventListener("change", removeChoice);
    selectElem.addEventListener("change", newTeamSelected);

    return selectElem;
}

//---------WEB SCRAPING LOGIC----------//
function scrape(games) {
    //Get session num
    var sessionNum = dropDown.value.substring(7);
    alert("URL: http://www.nfl.com/schedules/2018/REG" + sessionNum);

    var client = new HttpClient();
    client.get("http://www.nfl.com/schedules/2018/REG" + sessionNum, function (response) {
        //HTML code of the page loaded in response variable
        //Get the schedule <ul> object
        //var schedule = response.match(/<ul class="schedules-table"(.|\n|\w|\d)*?<\/ul>/);
        var scheduleChildren = schedule.children;

        //The lists are arranged so that the date is first
        //Then the game info
        var currentDate;
        for (var i = 0; i < scheduleChildren.length; i++) {
            if (scheduleChildren[i].className == "schedules-list-date") {
                currentDate = scheduleChildren[i].children[0].children[0].text();
            } else {
                var tempInfo = scheduleChildren[i].children[1];
                var homeTeamName = tempInfo.getAttribute("data-home-mascot").toLowerCase();
                var awayTeamName = tempInfo.getAttribute("data-away-mascot").toLowerCase();
                var gameTime = tempInfo.getAttribute("data-localtime");
                games.addGame(homeTeamName, awayTeamName, currentDate, gameTime, 0.0);
            }
        }
    });
}

function generateTable(scrapeBool) {
    return function() {
        var table = document.getElementById("game-information-table");
        var firstChild = table.children[0].cloneNode(true);
        while (table.firstChild)
            table.removeChild(table.firstChild);
        table.appendChild(firstChild);

        if (scrapeBool) {
            //scrape(games);
            alert("scraped here");
        }

        for (var i = 0; i < gameNumInput.value; i++) {
            var row = document.createElement("tr");
            var left = document.createElement("td");
            left.setAttribute("class", "left-team-box");
            var center = document.createElement("td");
            var right = document.createElement("td");
            right.setAttribute("class", "right-team-box");

            //Create divs
            var leftDiv = document.createElement("div");
            leftDiv.setAttribute("id", "left-team-" + i);
            leftDiv.setAttribute("name", "left-team-" + i);
            var centerDiv = document.createElement("div");
            centerDiv.setAttribute("id", "center-team-" + i);
            centerDiv.setAttribute("name", "center-team-" + i);
            var rightDiv = document.createElement("div");
            rightDiv.setAttribute("id", "right-team-" + i);
            rightDiv.setAttribute("name", "right-team-" + i);

            //Images
            var leftImg = document.createElement("img");
            leftImg.setAttribute("src", "../../img/team-logos/nfl.png");
            leftImg.setAttribute("id", "team-logo-left-" + i);
            leftImg.setAttribute("name", "team-logo-left-" + i);
            leftImg.setAttribute("class", "team-logo-left");
            var rightImg = document.createElement("img");
            rightImg.setAttribute("src", "../../img/team-logos/nfl.png");
            rightImg.setAttribute("id", "team-logo-right-" + i);
            rightImg.setAttribute("name", "team-logo-right-" + i);
            rightImg.setAttribute("class", "team-logo-right");

            //Two drop down
            var leftSelect = createDropdown("team-select-left-" + i);
            var rightSelect = createDropdown("team-select-right-" + i);

            //Append to left and right
            leftDiv.appendChild(leftImg);
            leftDiv.appendChild(leftSelect);
            rightDiv.appendChild(rightImg);
            rightDiv.appendChild(rightSelect);
            
            //Construct center
            var notice = document.createElement("p");
            var noticeStrong = document.createElement("strong");
            noticeStrong.innerHTML = "Kickoff (CST)";
            notice.appendChild(noticeStrong);
            
            var monthLabel = document.createElement("label");
            monthLabel.setAttribute("for", "game-month-" + i);
            monthLabel.innerHTML = "Month ";
            
            var monthInput = document.createElement("input");
            monthInput.setAttribute("type", "number");
            monthInput.setAttribute("id", "game-month-" + i);
            monthInput.setAttribute("name", "game-month-" + i);
            monthInput.setAttribute("min", "1");
            monthInput.setAttribute("max", "12");
            monthInput.setAttribute("value", masterKickoffMonth.value);
            
            var dayLabel = document.createElement("label");
            dayLabel.setAttribute("for", "game-day-" + i);
            dayLabel.innerHTML = "Day ";
            
            var dayInput = document.createElement("input");
            dayInput.setAttribute("type", "number");
            dayInput.setAttribute("id", "game-day-" + i);
            dayInput.setAttribute("name", "game-day-" + i);
            dayInput.setAttribute("min", "1");
            dayInput.setAttribute("max", "31");
            dayInput.setAttribute("value", masterKickoffDay.value);
            
            var yearLabel = document.createElement("label");
            yearLabel.setAttribute("for", "game-year-" + i);
            yearLabel.innerHTML = "Year ";
            
            var yearInput = document.createElement("input");
            yearInput.setAttribute("type", "number");
            yearInput.setAttribute("id", "game-year-" + i);
            yearInput.setAttribute("name", "game-year-" + i);
            yearInput.setAttribute("min", "2018");
            yearInput.setAttribute("max", "9999");
            yearInput.setAttribute("value", masterKickoffYear.value);
            
            var hourLabel = document.createElement("label");
            hourLabel.setAttribute("for", "game-hour-" + i);
            hourLabel.innerHTML = "Hour (24hr) ";
            
            var hourInput = document.createElement("input");
            hourInput.setAttribute("type", "number");
            hourInput.setAttribute("id", "game-hour-" + i);
            hourInput.setAttribute("name", "game-hour-" + i);
            hourInput.setAttribute("min", "0");
            hourInput.setAttribute("max", "23");
            hourInput.setAttribute("value", masterKickoffHour.value);
            
            var minuteLabel = document.createElement("label");
            minuteLabel.setAttribute("for", "game-minute-" + i);
            minuteLabel.innerHTML = "Minute ";
            
            var minuteInput = document.createElement("input");
            minuteInput.setAttribute("type", "number");
            minuteInput.setAttribute("id", "game-minute-" + i);
            minuteInput.setAttribute("name", "game-minute-" + i);
            minuteInput.setAttribute("min", "0");
            minuteInput.setAttribute("max", "59");
            minuteInput.setAttribute("value", masterKickoffMinute.value);

            var spreadLabel = document.createElement("label");
            spreadLabel.setAttribute("for", "game-spread-" + i);
            spreadLabel.innerHTML = "Spread ";
            
            var spreadInput = document.createElement("input");
            spreadInput.setAttribute("type", "number");
            spreadInput.setAttribute("id", "game-spread-" + i);
            spreadInput.setAttribute("name", "game-spread-" + i);
            spreadInput.setAttribute("value", "0.5");
            spreadInput.setAttribute("step", "1");
            spreadInput.setAttribute("min", "-28.5");
            spreadInput.setAttribute("max", "28.5");
            
            var breakElem = document.createElement("br");
            
            centerDiv.appendChild(notice);
            centerDiv.appendChild(monthLabel);
            centerDiv.appendChild(monthInput);
            centerDiv.appendChild(breakElem.cloneNode(false));
            centerDiv.appendChild(dayLabel);
            centerDiv.appendChild(dayInput);
            centerDiv.appendChild(breakElem.cloneNode(false));
            centerDiv.appendChild(yearLabel);
            centerDiv.appendChild(yearInput);
            centerDiv.appendChild(breakElem.cloneNode(false));
            centerDiv.appendChild(hourLabel);
            centerDiv.appendChild(hourInput);
            centerDiv.appendChild(breakElem.cloneNode(false));
            centerDiv.appendChild(minuteLabel);
            centerDiv.appendChild(minuteInput);
            centerDiv.appendChild(breakElem.cloneNode(false));
            centerDiv.appendChild(breakElem.cloneNode(false));
            centerDiv.appendChild(spreadLabel);
            centerDiv.appendChild(spreadInput);

            //Append divs to their td's
            left.appendChild(leftDiv);
            center.appendChild(centerDiv);
            right.appendChild(rightDiv);

            //Append all to row
            row.appendChild(left);
            row.appendChild(center);
            row.appendChild(right);

            //Add to the table
            table.appendChild(row);
        }
    }
}

//findGamesButton.addEventListener("click", generateTable(false));
inputGamesButton.addEventListener("click", generateTable(false));

if (hidden.value != null && gameNumInput.value > 0) {
    for (var i = 0; i < gameNumInput.value; i++) {
        var leftDropdown = document.getElementById("team-select-left-" + i);
        var rightDropdown = document.getElementById("team-select-right-" + i);
        leftDropdown.addEventListener("change", removeChoice);
        leftDropdown.addEventListener("change", newTeamSelected);
        rightDropdown.addEventListener("change", removeChoice);
        rightDropdown.addEventListener("change", newTeamSelected);
    }
}

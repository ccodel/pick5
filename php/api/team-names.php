<?php

function getTeam($label) {
    $teams = array(32);
    $teams["49ers"] = "San Fransisco 49ers";
    $teams["bears"] = "Chicago Bears";
    $teams["bengals"] = "Cincinnati Bengals";
    $teams["bills"] = "Buffalo Bills";
    $teams["broncos"] = "Denver Broncos";
    $teams["browns"] = "Cleveland Browns";
    $teams["buccaneers"] = "Tampa Bay Buccaneers";
    $teams["cardinals"] = "Arizona Cardinals";
    $teams["chargers"] = "San Diego Chargers";
    $teams["chiefs"] = "Kansas City Chiefs";
    $teams["colts"] = "Indianapolis Colts";
    $teams["cowboys"] = "Dallas Cowboys";
    $teams["dolphins"] = "Miami Dolphins";
    $teams["eagles"] = "Philadelphia Eagles";
    $teams["falcons"] = "Atlanta Falcons";
    $teams["giants"] = "New York Giants";
    $teams["jaguars"] = "Jacksonville Jaguars";
    $teams["jets"] = "New York Jets";
    $teams["lions"] = "Detriot Lions";
    $teams["packers"] = "Green Bay Packers";
    $teams["panthers"] = "Carolina Panthers";
    $teams["patriots"] = "New England Patriots";
    $teams["raiders"] = "Oakland Raiders";
    $teams["rams"] = "Saint Louis Rams";
    $teams["ravens"] = "Baltimore Ravens";
    $teams["redskins"] = "Washington Redskins";
    $teams["saints"] = "New Orleans Saints";
    $teams["seahawks"] = "Seattle Seahawks";
    $teams["steelers"] = "Pittsburgh Steelers";
    $teams["texans"] = "Houston Texans";
    $teams["titans"] = "Tennessee Titans";
    $teams["vikings"] = "Minnesota Vikings";

    if ($teams[$label] != null)
        return $teams[$label];

    return false;
}

function getAbbr($label) {
    $teams = array(32);
    $teams["49ers"] = "49ers";
    $teams["bears"] = "Bears";
    $teams["bengals"] = "Bengals";
    $teams["bills"] = "Bills";
    $teams["broncos"] = "Broncos";
    $teams["browns"] = "Browns";
    $teams["buccaneers"] = "Buccaneers";
    $teams["cardinals"] = "Cardinals";
    $teams["chargers"] = "Chargers";
    $teams["chiefs"] = "Chiefs";
    $teams["colts"] = "Colts";
    $teams["cowboys"] = "Cowboys";
    $teams["dolphins"] = "Dolphins";
    $teams["eagles"] = "Eagles";
    $teams["falcons"] =  "Falcons";
    $teams["giants"] = "Giants";
    $teams["jaguars"] = "Jaguars";
    $teams["jets"] = "Jets";
    $teams["lions"] = "Lions";
    $teams["packers"] = "Packers";
    $teams["panthers"] = "Panthers";
    $teams["patriots"] = "Patriots";
    $teams["raiders"] = "Raiders";
    $teams["rams"] = "Rams";
    $teams["ravens"] = "Ravens";
    $teams["redskins"] = "Redskins";
    $teams["saints"] = "Saints";
    $teams["seahawks"] = "Seahawks";
    $teams["steelers"] = "Steelers";
    $teams["texans"] = "Texans";
    $teams["titans"] = "Titans";
    $teams["vikings"] = "Vikings";

    if ($teams[$label] != null)
        return $teams[$label];

    return false;
}

function getLabel($team) {
    $labels = array(32);
    $labels["San Fransisco 49ers"] = "49ers";
    $labels["Chicago Bears"] = "bears";
    $labels["Cincinnati Bengals"] = "bengals";
    $labels["Buffalo Bills"] = "bills";
    $labels["Denver Broncos"] = "broncos";
    $labels["Cleveland Browns"] = "browns";
    $labels["Tampa Bay Buccaneers"] = "buccaneers";
    $labels["Arizona Cardinals"] = "cardinals";
    $labels["San Diego Chargers"] = "chargers";
    $labels["Kansas City Chiefs"] = "chiefs";
    $labels["Indianapolis Colts"] = "colts";
    $labels["Dallas Cowboys"] = "cowboys";
    $labels["Miami Dolphins"] = "dolphins";
    $labels["Philadelphia Eagles"] = "eagles";
    $labels["Atlanta Falcons"] = "falcons";
    $labels["New York Giants"] = "giants";
    $labels["Jacksonville Jaguars"] = "jaguars";
    $labels["New York Jets"] = "jets";
    $labels["Detroit Lions"] = "lions";
    $labels["Green Bay Packers"] = "packers";
    $labels["Carolina Panthers"] = "panthers";
    $labels["New England Patriots"] = "patriots";
    $labels["Oakland Raiders"] = "raiders";
    $labels["Saint Louis Rams"] = "rams";
    $labels["Baltimore Ravens"] = "ravens";
    $labels["Washington Redskins"] = "redskins";
    $labels["New Orleans Saints"] = "saints";
    $labels["Seattle Seahawks"] = "seahawks";
    $labels["Pittsburgh Steelers"] = "steelers";
    $labels["Houston Texans"] = "texans";
    $labels["Tennessee Titans"] = "titans";
    $labels["Minnesota Vikings"] = "vikings";
    
    if ($labels[$team] != null)
        return $labels[$team];
    
    return false;
}

?>
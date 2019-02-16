function httpGetAsync(url, search) {
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            populateData(request.response);
        }
    };

    if (search.length === 0){
        document.getElementById("main-table").innerHTML = "";
        document.getElementById("additional-container").innerHTML = "";
        document.getElementById("additional-table").innerHTML = "";
        request.open("GET", url, true);
    }
    else{
        document.getElementById("main-table").innerHTML = "";
        document.getElementById("additional-container").innerHTML = "";
        document.getElementById("additional-table").innerHTML = "";
        request.open("GET", url + "?number=" + search, true);
    }

    request.responseType = 'json';
    request.send();
}

function populateData(json) {
    var list = json['list'];

    var mainTable = "<thead class='thead-dark'><tr><th scope='col'>Phone Number</th><th scope='col'>Body</th><th scope='col'>Status</th></tr></thead><tbody>";
    for (var i = 0; i < list.length; i++){
        mainTable += "<tr><td>" + list[i].number + "</td><td>" + list[i].body + "</td><td>" + list[i].status + "</td></tr>";
    }
    mainTable += "</tbody>";
    document.getElementById("main-table").innerHTML = mainTable;

    var quantityOfSms = "<p>SMS counts: " + json['count'] + "</p>";
    var firstAPIUsage = "<p>First API usage: " + json['firstUsage'] + "</p>";
    var secondAPIUsage = "<p>Second API usage: " + json['secondUsage'] + "</p>";
    var firstAPIError = "<p>First API error: " + json['firstError'] + "</p>";
    var secondAPIError = "<p>Second API error: " + json['secondError'] + "</p>";
    document.getElementById("additional-container").innerHTML = quantityOfSms + firstAPIUsage + secondAPIUsage + firstAPIError + secondAPIError;

    var topTen = json['topTen'];
    var topTenTable = "<thead class='thead-dark'><tr><th scope='col'>Phone Number</th><th scope='col'>Count</th></tr></thead><tbody>";
    for (var i = 0; i < topTen.length; i++){
        topTenTable += "<tr><td>" + topTen[i].number + "</td><td>" + topTen[i].smsCount + "</td></tr>";
    }
    topTenTable += "</tbody>";
    document.getElementById("additional-table").innerHTML = topTenTable;
}
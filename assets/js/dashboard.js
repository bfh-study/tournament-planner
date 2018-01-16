require('bootstrap');
var $ = require('jquery');

const TIMEOUT = 3000;

function DataStore() {
    this.data = [];
    this.lastchangedResult = null;
    this.addTournament = function(tournament) {
        this.data[tournament.id] = tournament;
    };
    this.resultChanged = function(tournament) {
        var retVal = false;
        if (this.data[tournament.id] != undefined) {
            var oldTournament = this.data[tournament.id];
            var actualObj = this;
            oldTournament.results.forEach(function(oldResult) {
                tournament.results.forEach(function(newResult) {
                    if (oldResult.name == newResult.name) {
                        if(actualObj.hasChanged(oldResult, newResult)) {
                            retVal = true;
                            actualObj.lastchangedResult = newResult;
                            actualObj.data[tournament.id] = tournament;
                        }
                    }
                });
            });
            return retVal;
        }
        return false;
    };
    this.hasChanged = function(oldResult, newResult) {
        if (oldResult.goalsHome == newResult.goalsHome && oldResult.goalsAway == newResult.goalsAway) {
            return false;
        }
        return true;
    };
    this.getLastChangedResult = function () {
        return this.lastchangedResult;
    }
}

$(document).ready(function() {
    var url = $('div[data-ajax-url]').attr('data-ajax-url');
    var dataStore = new DataStore();
    var interval = null;

    var timeoutCallback = function() {
        $.ajax(url, {
        }).done(function( tournaments ) {
            tournaments.forEach(function(tournament) {
                if (dataStore.resultChanged(tournament)) {
                    $('#tournamentTeamResult').text(tournament.name);
                    var result = dataStore.getLastChangedResult();
                    var names = result.name.trimLeft().trimRight().split(':');
                    $('#homeTeam').text(names[0]);
                    $('#homeTeamResult').text(result.goalsHome);
                    $('#awayTeam').text(names[1]);
                    $('#awayTeamResult').text(result.goalsAway);
                    $('#resultChangedModal').modal('show');
                } else {
                    window.console.log("not changed");
                    dataStore.addTournament(tournament);
                }
            });
        });
    };

    var showModalCallback = function () {
        if (interval != null) {
            window.clearInterval(interval);
        }
    };

    var hideModalCallback = function () {
        interval = window.setInterval(timeoutCallback, TIMEOUT);
    };
    var modal = $('#resultChangedModal');
    modal.on('show.bs.modal', showModalCallback);
    modal.on('hide.bs.modal', hideModalCallback);
    interval = window.setInterval(timeoutCallback, TIMEOUT);
});

document.open_modal_copy_link = function (link) {
    $('#clipboardInputField').val(link);
    $('#copyLinkModal').modal('show');
};

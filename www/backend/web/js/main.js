var protocol = location.protocol;
var slashes = protocol.concat("//");
var port = location.port;
var path = $('body').data('url');
var host = (port) ? slashes.concat(window.location.hostname) + ':' + port : slashes.concat(window.location.hostname);

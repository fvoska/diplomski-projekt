var express = require('express');
var app = express();

app.use(express.static('client'));

var server = app.listen(80, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Serving client application at http://%s:%s', host, port);
});
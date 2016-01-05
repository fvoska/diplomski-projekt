var express = require('express');
var app = express();

app.use(express.static('client'));
app.use(function(req, res) {
  // Use res.sendfile, as it streams instead of reading the file into memory.
  res.sendfile(__dirname + '/client/index.html');
});

var server = app.listen(80, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Serving client application at http://%s:%s', host, port);
});

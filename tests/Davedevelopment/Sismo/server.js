var http = require('http');
var lastRequest = new Date().getTime();
port = process.argv.length >= 3 ? process.argv[2] : 5555;
http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hello World\n');
  console.log(req.method + " " + req.url);
  lastRequest = new Date().getTime();

  for(i in req.headers) {
    console.log(i + ": " + req.headers[i]);
  } 

  if (req.method == 'POST') {
    var body = '';
    req.on('data', function (data) {
        body += data;
    });
    req.on('end', function () {
        console.log("\n" + body);
        process.exit(code=0);
    });
  }

}).listen(port, "127.0.0.1");
console.log('Server running at http://127.0.0.1:' + port + '/');

setInterval(function() {
    /**
     * Kill the process if no activity for > 30 seconds
     */
    var now = new Date().getTime();
    if ((now - lastRequest) > 30000) {
        console.log('No activity for 30+ seconds, exiting...');
        process.exit();
    }

}, 1000);

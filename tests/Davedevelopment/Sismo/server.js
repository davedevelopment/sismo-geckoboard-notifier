var http = require('http');
port = process.argv.length >= 3 ? process.argv[2] : 5555;
http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hello World\n');
  console.log(req.method + " " + req.url);

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

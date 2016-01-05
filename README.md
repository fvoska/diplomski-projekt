# Diplomski projekt
* Marko Gudan
* Erik Perčić
* Petar Perković
* Filip Voska

### Running the client
Client files can be served via any HTTP server. If using Apache, make sure to enable mod-rewrite and use /client/.htaccess.

Alternatively, included in this project is a small script which starts up Node.js HTTP server using Express. Client is served as static content. Default port is 80, change it in /index.js if needed.

Installation instructions for Debian and Ubuntu based Linux distributions:
```
# Install Node.js and npm.
curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
sudo apt-get install -y nodejs
```
For other OSes: [link](https://nodejs.org/en/download/package-manager/)

---

Setup and start the server:
```
# Install dependencies.
npm install

# Start the server.
npm start
```

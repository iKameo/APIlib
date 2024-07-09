<p align="center">
    <img src="https://pictures.byteoverflow.de/gallery-images/firefox_rpcfR9grAy.png"
        height="100">
</p>
<p align="center">

 <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" alt="License">
        <img src="https://img.shields.io/badge/license-GPLv3-green"/></a>
    <a href="https://en.wikipedia.org/wiki/Software_release_life_cycle" alt="Beta">
        <img src="https://img.shields.io/badge/build-beta-red"/></a>
    <a href="https://en.wikipedia.org/wiki/Self-hosting_(web_services)">
        <img src="https://img.shields.io/badge/feature-selfhosting-yellowgreen" /></a>
</p>


## Table of Contents
- [Features](#features)
- [Selfhosting](#selfhosting)

## Features
- __basic:__ written in plain `html`,`css` and vanilla `javascript`.
- __selhostable:__ is this even a real word?
- __janky:__ code made by me and is held together with ducttape and hope.


## Selfhosting
You´ll need a webserver which is capable of rendering `html` and `css` - so basically any webserver.
Additionally you´ll need a selfhosted FoundryVTT instance with GM access.

Drop the __pulldata_style.php__ into your `www` directory and your good to go.

Modify the apilib.js to point to your own website.
```
function sendPlayerData(data) {
  const url = 'https://beta.byteoverflow.de/foundry/pulldata_style.php'; // Replace with your PHP script URL
  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
```
Add the __apilib.js__ and the __module.json__ to your modules folder inside your foundry installation.

<p align="left">
    <img src="https://pictures.byteoverflow.de/gallery-images/WinSCP_c7qnZOP9r5.png">
</p>

## Support
If you want to help or have a feature request just drop a pull request and we´ll see

## License

All assets and code are under the <a href="https://www.gnu.org/licenses/gpl-3.0.en.html">GPLv3 license</a> unless specified otherwise.

All assets are trademarks of their respective companies and are under their terms and license.

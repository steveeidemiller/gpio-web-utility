# gpio-web-utility
PHP-based web front-end for WiringPi's `gpio` utility at drogon.net

Based on the original work and concept by
[Travis Brown](https://github.com/WarriorRocker/wiringpi-web-utility).

## Screenshot
![Screenshot](/screenshots/1.png?raw=true)

## Description
This is a single-page interface to the `gpio` command line utility used on Raspberry Pi.
It displays the pin names, modes, values, and physical locations as shown with `gpio readall`.

You can use this utility to change pin modes and values simply by clicking their links.
This is quite useful for prototyping, testing, and quickly controlling the Pi from another
computer on the network.

## Changes from the original
This version handles the new double column layout of `gpio readall`. In addition, it no longer uses
[WiringPi-PHP](https://github.com/WiringPi/WiringPi-PHP). My version simply uses PHP's `exec()`
function to call `gpio` directly.

## Requirements
This is a completely self-contained single page solution, but does require PHP running from a web server,
and of course [gpio](https://projects.drogon.net/raspberry-pi/wiringpi/the-gpio-utility/).
I designed it to run on a Raspberry Pi 3 Model B using apache2 and PHP 5.6, but I see no reason why
it couldn't run in other similar environments. The `exec()` function must NOT be disabled in the php.ini
file, and the PHP web account will obviously need the ability to run `gpio`. For standard installations,
I don't expect that this will be an issue.

## Installation
Ensure that `gpio` is installed first, which is a utility in the WiringPi package:
```
cd ~
git clone git://git.drogon.net/wiringPi
cd wiringPi
./build
```
Ensure that you have a web server:
```
sudo apt-get install apache2 -y
sudo apt-get install php5
```
Clone this repository to your web server:
```
cd /var/www/html
git clone https://github.com/steveeidemiller/gpio-web-utility.git
```
You can then test with Chromium directly on the Raspberry Pi. Open a new tab in Chromium and type in the following URL:
```
http://localhost/gpio-web-utility/index.php
```

## Security
This utility is NOT intended to be exposed directly to the Internet or any other insecure network.
Because it can control your Pi and has no security features, you should NOT use it in a production
environment. It is intended for testing and experimentation only. You have been warned!

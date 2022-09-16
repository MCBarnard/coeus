# Coeus
This is a service that will visit different websites through a chromedriver to match and
compare prices. It will then send these prices to the main site where we will distribute
the prices to users who opt in to certain items.

## How to use it
After following the startup procedure in the below section simply visit local host page and click on the big power button.
This kicks off the scraping and will search through the stores for their product prices.

# Project Name
Coeus means questioning, while the respective Roman deity was Polus, the celestial axis around which the heavens revolve. 
Based on his Greek name, it has been suggested that Coeus may have also been the Titan of inquisitive minds and intellect.
Source: https://www.greekmythology.com/Titans/Coeus/coeus.html

## pronunciation
["Koi" + "use"](https://youtu.be/BF8t3fgC3rM)


# Start Up Commands
To start the system locally run the below commands:

* Start the chrome webdriver

    `php artisan chrome-driver:start`


* Ensure the Tor service is running

    `systemctl start tor` 



* Start the website service

    `php artisan serve` 

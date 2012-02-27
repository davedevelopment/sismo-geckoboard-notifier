Sismo Geckoboard Notifier
=========================

What is it?
-----------

I had a couple of hours to spare, so I thought I'd have a crack at creating a
[Sismo](https://github.com/fabpot/Sismo) notifier for the first [Ibuildings
Challenge](http://ibuildings.com/challenge) of 2012. It's a simple notifier that
pushes a message up to a widget on your [Geckoboard](http://geckoboard.com)

![Widget showing failed status](http://i.imgur.com/9LYKM.png)

It actually ended up being more than a couple of hours, probably about 4 in
total, after a few refactorings and extra features I thought up. It currently
passes upto the last 5 build statues to the widget, though this can be
configured.

![Widget showing success status](http://i.imgur.com/KXtPh.png)

I've used a hacky way to send the HTTP POST in order to keep this dependency
free, one class, one file, etc. As such, I've used a hacky way to test it fully,
so you'll need [node.js](http://nodejs.org) installed to run on of the tests


Usage
-----

First up, you need a Geckoboard account, one with the Push API enabled (I had to
ask, I assume at some point it will be the default). Add a custom text widget to
your board, select Push as the method and take note of the provided widget URL.
You can find your API key on the API tab of the Account page.

In your Sismo config

``` php
<?php
$notifier = new Davedevelopment\Sismo\GeckoboardNotifier(
    "your_api_key", 
    "your_widget_url"
); 

$myProject->addNotifier($notifier);

```

Try running sismo

You can customise the display by passing a third parameter to the constructor,
either a string or a callback that would take a `Sismo\Commit` instance

Todo
----

* <del>Could optionally take a widget url rather than key, in case they change things</del>
* <del>Take Buzz out</del>
* <del>Send output for the last X commits, so they can be paged through</del>

Copyright
---------

Copyright (c) 2012 Dave Marshall. See LICENCE for further details

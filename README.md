Sismo Geckoboard Notifier
=========================

What is it?
-----------

I had a couple of hours to spare, so I thought I'd have a crack at creating a
[Sismo](https://github.com/fabpot/Sismo) notifier for the first [Ibuildings
Challenge](http://ibuildings.com/challenge) of 2012. It's a simple notifier that
pushes a message up to a widget on your [Geckoboard](http://geckoboard.com)

I've used a hacky way to send the HTTP POST in order to keep this dependency
free, one class, one file, etc. As such, I've used a hacky to test it.

Usage
-----

First up, you need a Geckoboard account, one with the Push API enabled (I had to
ask, I assume at some point it will be the default). Add a custom text widget to
your board, select Push as the method and give it an API key. The system should
provide you a URL to use for the widget.

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

# Lean, a micro-framework for Hack

## Introduction
This repository holds the beginnings of a clone of [Slim](http://slimframework.com/)
for Hack, Facebook's new statically-typed PHP-based language, in strict-mode as
possible. Slim relies on various dynamic features of PHP, so the API has to
differ in some places, but the aim is to get as close as possible, so porting a
Slim code base is as easy as possible.

## Current Status
The `Lean\Router` class is currently responding to GET, POST, PUT and DELETE,
it passes paramters from routes through to the callback defined by the user
through a `Map<string, mixed>` in the function.

## Installation
Currently this needs a bit of work, but clone this repository down and run
`composer install`. The you'll want to point Nginx at it (I need to rework the
route matcher to handle `/index.php/<route>` request URIs so you can use it
without needing a rewrite), and ensure you havea `try_files /index.php$args;`
line in your location block.

Then, take a look at the `index.php` file in `/public` for how it currently works.

## Usage
Defining a route is easy, if a bit messy currently.

````
<?hh //partial

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Lean\Router();

$app->get('/hello/:name', function(Map<string, mixed> $items) {
    print "Hello, " . $name . "!";
});

$app->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
````

The current methods on `$app` are:

* get()
* post()
* put()
* delete()
* run()
* setStatus()

I'll enumerate when this is locked down a bit more, check out `index.php` for
a proper example

## Contributing
Pull requests are more than welcome! I'd love some help to get this going,
it's a bit rough at the moment but theres a solid base there. Feel free to
send me a stack of issues too, let me know what you need and what needs work.

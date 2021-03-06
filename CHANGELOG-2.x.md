# Changelog for 2.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/streaming`.

## 2.2.2

Released: 2019-03-15

### Changes

* Small trivial changes to code and readme.

## 2.2.1

Released: 2019-02-17

### Changes

* Improve performance by prefixing all global functions calls with `\` to skip the look up and resolve process and go straight to the global function.

## 2.2.0

Released: 2018-11-09

### Changes

* Update `react/event-loop` to 1.0+.

## 2.1.1

Released: 2018-09-13

### Changes

* Replaced abandoned `predis/predis-async` with `laravie/predis-async`.

## 2.1.0

Released: 2018-07-10

### Changes

* Remove return scalar typehint.

## 2.0.0

Released: 2018-02-10

### Changes

* Update minimum PHP to 7.1+.
* Add scalar typehint for parameter and return type.

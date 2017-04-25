# Criteria

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Sometimes you need to decide whether to perform a particular operation based on some criteria.

For example, you may want to send out a promotional email if it's a Monday or Thursday. Perhaps you only want to send out an alert if it's a weekday.

Your criteria may be slightly more complex; perhaps you only want to send out your Monday and Thursday promotional email if there are a certan number of offers to advertise, or you want to send out an alert if there are a cetain number of errors, regardless of whether it's a weekday.

These scenarios are all pretty straightforward to code, but sometimes it can be useful to be able to express them as strings; that way you can, for example, add them to a configuration file. That way, you can modify the criteria without changing any code, or you can evaluate it differently according to the current application environment by setting that string in an environment variable.

This package allows you to write the above examples as follows:
 
```
days:monday,thursday
weekday||gte:errors,1000
```

## Install

Via Composer

``` bash
$ composer require lukaswhite/criteria
```

## Usage

First you'll need to create a new instance; then call the `evaluate( )` method. You don't need to provide any arguments when creating an instance:

```php
$criteria = new Lukaswhite\Criteria\Criteria( );

if ( $criteria->evaluate( 'days:monday,thursday' ) {
  // send your promotional email if it's a Monday or a Thursday
}
```

However, some of the available criteria are based on data that you provide, so you can optionally pass an associative array of data as an argument in the constrctor: 


```php
$criteria = new Lukaswhite\Criteria\Criteria( [ 'errors' => 1023 ] );

if ( $criteria->evaluate( 'gte:errors,1000' ) {
  // send an alert if the value of errors is greater than 
  // or equal to 1000
}
```

You can provide multiple criteria; simply use `&&` or `||` for AND / OR respectively:

```php
$criteria = new Lukaswhite\Criteria\Criteria( [ 'errors' => 1023 ] );

if ( $criteria->evaluate( 'weekday||gte:errors,1000' ) {
  // send an alert if the value of errors is greater than 
  // or equal to 1000 OR if it's a weekday
}
```

### Custom Criteria

The class comes provided with a range of simple criteria, but there's every chance you'll want to define your own that are specific to your application. Doing so is easy; simply extend the class, and add a method that returns a boolean.

#### Example

```php
class MyCritera extends \Lukaswhite\Criteria\Criteria {
  public function foo( ) {
    // your logic here
    return true;
  }
  public function bar( $x, $y ) {
      // your logic here
      return true;
    }
}

$criteria = new MyCriteria( );

if ( $criteria->evaluate( 'foo' ) ) {
  // Do something
}

if ( $criteria->evaluate( 'bar:1,2' ) ) {
  // Do something
}
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@lukaswhite.com instead of using the issue tracker.

## Credits

- [Lukas White][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lukaswhite/criteria.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lukaswhite/criteria/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/lukaswhite/criteria.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/lukaswhite/criteria.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lukaswhite/criteria.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lukaswhite/criteria
[link-travis]: https://travis-ci.org/lukaswhite/criteria
[link-scrutinizer]: https://scrutinizer-ci.com/g/lukaswhite/criteria/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/lukaswhite/criteria
[link-downloads]: https://packagist.org/packages/lukaswhite/criteria
[link-author]: https://github.com/lukaswhite

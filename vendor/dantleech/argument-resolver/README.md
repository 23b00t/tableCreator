Named Argument Resolver
=======================

Resolve and validate method arguments from an associative array.

- Resolves arguments including default values from an associative array.
- Throws exception if required arguments are missing.
- Throws exception if extra arguments are provided.

## Example

```php
class Foobar
{
    public function execute(string $foobar, $barfoo = 'foobar')
    {
    }
}

$argumentResolver = new ArgumentResolver();
$arguments = $argumentResolver->resolveArguments(Foobar::class, 'execute', [
    'foobar' => 'hello'
]);

var_dump($arguments);
// array(2) {                       
//   [0] =>                         
//   string(5) "hello"              
//   [1] =>                         
//   string(6) "foobar"             
// }

$result = call_user_func([$myClass, 'myMethod'], $arguments);
```

## Motivation

It is often desired that service have some runtime invocation configuration
which is provided by the user (similar to Symfony Forms and the Symfony
[OptionResolver](https://symfony.com/doc/current/components/options_resolver.html)
component, which does something like:

```php
$resolver = new OptionsResolver();
$resolver->setDefault([
    'barfoo' => 'hello',
]);
$resolver->setRequired('foobar')l
$options = $resolver->resolve(['foobar' => 'hello']);

$myService->doSomething($options);
```

I have used this pattern in PHPBench and found it very useful for configuring
services invocations at runtime. However it has drawbacks:

- Your service is passed an untyped array.
- The service has an implicit dependency on the options resolver.

This package does something similar to the options resolver, but uses the
reflection information from the method to resolve the arguments.

## Drawbacks

This method does mean that you cannot implement a interface for your service
(e.g. `execute($blah, array $config)` as each service will have it's own
signature `execute($blah, $timeout = 1234, $iterations = 33)`.

## Similar Libraries

[https://github.com/sroze/ArgumentResolver](sroze/argument-resolver) does
almost exactly the same thing

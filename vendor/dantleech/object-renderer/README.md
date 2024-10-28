Object Renderer
===============

[![Build Status](https://travis-ci.org/dantleech/object-renderer.svg?branch=master)](https://travis-ci.org/dantleech/indexer-extension)

Render / pretty print objects using Twig templates.

- Templates are selected based on the FQN.
- Templates are fallback based on class hierarchy.
- Templates can render objects.

This library, or ones like it, could be suitable for:

- Pretty print `ReflectionClass` and friends, (e.g. printing formatted
  documentation in a language server).
- Building a CMS based on _objects_.
- Other things.

Rendering an Object
-------------------

Create a renderer and render an object:

```php
$renderer = ObjectRendererBuilder::create()
    ->addTemplatePath('example/path')
    ->build();

$renderer->render(new \stdClass());
```

Will throw an exception:

```
Could not render object "stdClass" using templates "stdClass.twig"',
```

You can guess what you need to do, create `stdClass.twig` in the path given in
the builder:

```
# stdClass.twig
Hello I am a stdClass
```

Object Properties and Recursive Rendering
-----------------------------------------

The object is available as `object` in the template.

If the object contains other objects, you can recurisvely render them
by calling `render(object.anotherObject)`.

Ancestor Class Template Resolution
----------------------------------

If a template for a given object's class is not found. The renderer will
try and locate a template for each of the parent classes.

DOMDocument Example
-------------------

```
{# DOMDocument.twig #}
DOMDocument:
{% for node in object.childNodes %}
    - {{ render(node) }}
{%- endfor -%}
```

```
{# DOMElement.twig #}
Element: "{{ object.nodeName }}"
{% for attribute in object.attributes %}
      {{ render(attribute) }}
{%- endfor -%}
```

```
{# DOMAttr.twig #}
{{ object.name }}: {{ object.value }}
```

Render them like:

```php
$dom = new DOMDocument();
$child1 = $dom->createElement('child-1');
$child1->setAttribute('foo', 'bar');
$dom->appendChild($child1);
$child2 = $dom->createElement('child-2');
$child2->setAttribute('bar', 'foo');
$dom->appendChild($child2);

$renderer = ObjectRendererBuilder::create()
    ->addTemplatePath('example/path')
    ->build();

$renderer->render($dom);
```

Should return something like:

```
DOMDocument:
    - Element: "child-1"
      foo: bar
    - Element: "child-2"
      bar: foo
```

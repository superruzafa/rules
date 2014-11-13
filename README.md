Rules
=====

A rule engine following the condition -> action paradigm

What a rule is?
---------------
A rule is build up by three elements:

#### One condition
Every rule has a boolean condition. If the condition is satisfied (i.e, its value is not false) then the rule is processed.

#### Some actions
Every rule has some associated actions.
The actions could be performed at different rule's stages:
* Before the rule's condition being evaluated (regardless being satisfied or not)
* After its condition has been satisfied but before its subrules have been executed
* After both its condition has been satisfied and its subrules have been executed.
* Before _leave_ the rule's, regardless the condition has been satisfied or not.

#### Zero or more subrules
Each rule could contain subrules. Each subrule is executed only if the parent rule's condition has been satisfied. 

When executes, the flow a rule follows could be described as: 
```
Perform pre-rule actions
If the rule's condition is satisfied then
    Perform pre-subrules actions
    Execute subrules
    Perform post-subrules actions
Perform post-rule actions
```

Quick example
=============

```php
$ctx = new Context();
$ctx['name'] = 'Philip J.';
$ctx['surname'] = 'Fry';

// If the context's name is 'Philip J.' AND the context's surname is 'Fry'...
$condition = new AndOp(
    new EqualTo('{{ name }}', 'Philip J.'),
    new EqualTo('{{ surname }}', 'Fry')
);

// Then print the message
$action = new RunCallback(function (Context $c) {
    echo 'Hello, my name is ' . $c['name'] . $c['surname'];
});

$rule = new Rule();
$rule
    ->setCondition($condition)
    ->setAction($action)
    ->execute($ctx);
    
// Hello, my name is Philip J. Fry
```

Conditions
==========

Conditions use boolean expressions to check whether the rule must be processed.
By default, a rule has a ```true``` as a condition.

### Primitives

There are four predefined primitives:

- String
- Integer
- Float
- Boolean

### Operators

Operators take one, two or more values (primitives or other operator's output) and return some other value

### Logical operators

#### ```AndOp($expr [, $expr]*)```

```AndOp()``` operator takes one or more arguments and returns ```true``` if *all* of its arguments are evaluated as ```true```:

``` php
$and = new AndOp(true, false, false, true);
echo $and->evaluate(); // <-- false
```

#### ```OrOp($expr [, $expr]*)```

```OrOp()``` operator takes one or more arguments and returns ```true``` if *any* of its arguments are evaluated as ```true```:

``` php
$or = new OrOp(true, false, false, true);
echo $or->evaluate(); // <-- true
```

#### ```NotOp($expr [, $expr]*)```

```NotOp()``` operator takes one or more arguments and returns ```true``` if *all* of its arguments are evaluated as ```false```.
This is indeed a _Nand_ operation.

``` php
$not = new Not(true, false, false, true);
echo $not->evaluate(); // <-- false
```

### Comparison conditions

#### ```EqualTo($expr, $expr [, $expr]*)```

```EqualTo()``` operator takes two or more arguments and returns ```true``` if *all* of its arguments are the same:

``` php
$equalTo = new EqualTo("foo", "bar", "baz");
echo $equalTo->evaluate(); // <-- false
```

#### ```NotEqualTo($expr, $expr [, $expr]*)```

```EqualTo()``` operator takes two or more arguments and returns ```true``` if *any* of its arguments is different from aonther one:

``` php
$notEqualTo = new EqualTo("foo", "bar", "baz");
echo $notEqualTo->evaluate(); // <-- true
```

Actions
=======

### Override context
This action updates/extends the current context:

```php
$ctx = new Context();
$ctx['name'] = 'Philip J.';

$overrideCtx['surname'] = 'Fry';
$action = new OverrideContext($overrideCtx);
$action->perform($ctx);

var_dump($ctx);
// array(
//     'name' => 'Philip J.',
//     'surname' => 'Fry'
// );
```

### Interpolate context
This action allows to render the current context as a template by feeding itself as the variable replacements:

```php
$ctx = new Context();
$ctx['name'] = 'Philip J.';
$ctx['surname'] = 'Fry';
$ctx['fullname'] = '{{ name }} {{ surname }}';

$action = new InterpolateContext();
$action->perform($ctx);

var_dump($ctx);
// array(
//     'name' => 'Philip J.',
//     'surname' => 'Fry'
//     'fullname' => 'Philip J. Fry'
// );
```

### Filter context
This action filter (either by preserving or discarding) entries in a context:

```php
$ctx = new Context();
$ctx['name'] = 'Philip J.';
$ctx['surname'] = 'Fry';
$ctx['fullname'] = 'Philip J. Fry';

$action = new FilterContext();
$action
    ->setKeys('fullname')
    ->setMode(FilterContext::ALLOW_KEYS)
    ->perform($ctx);

var_dump($ctx);
// array(
//     'fullname' => 'Philip J. Fry'
// );
```

### Run callback
This actions calls a custom user function passing the current context as argument:

```php
$ctx = new Context();
$ctx['name'] = 'Philip J.';
$ctx['surname'] = 'Fry';

$callback = function(Context $ctx) {
    $ctx['fullname'] = $ctx['name'] . ' ' . $ctx['surname'];
    unset($ctx['surname'];
}

$action = new RunCallback($callback);
$action->perform($ctx);

var_dump($ctx);
// array(
//     'name' => 'Philip J.',
//     'fullname' => 'Philip J. Fry'
// );
```


### No action
This actions does nothing :)

```php
$ctx = new Context();
$ctx['name'] = 'Philip J.';
$ctx['surname'] = 'Fry';

$action = new NoAction($callback);
$action->perform($ctx);

var_dump($ctx);
// array(
//     'name' => 'Philip J.',
//     'surname' => 'Fry'
// );
```

### Sequence
This actions allows to define a list of actions to be performed sequentially:

```php
$ctx = new Context();
$ctx['fullname'] = '{{ name }} {{ surname }}';

$override = new OverrideContext(new Context(array('name' => 'Philip J.')));     // Adds name
$callback = new RunCallback(function (Context $c) { $c['surname'] = 'Fry'; } ); // Adds surname
$interpolate = new InterpolateContext();                                        // Interpolates fullname
$filter = new FilterContext(FilterContext::ALLOW_KEYS, 'fullname');             // Filters all except fullname

$sequence = new Sequence($override, $callback);
$sequence->appendAction($interpolate)->appendAction($filter);
$sequence->perform($ctx);

var_dump($ctx);
// array(
//     'fullname' => 'Philip J. Fry'
// );
```

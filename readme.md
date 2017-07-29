Pattern
=========================================
[![Latest Stable Version](https://poser.pugx.org/slexx/pattern/v/stable)](https://packagist.org/packages/slexx/pattern) [![Total Downloads](https://poser.pugx.org/slexx/pattern/downloads)](https://packagist.org/packages/slexx/pattern) [![Latest Unstable Version](https://poser.pugx.org/slexx/pattern/v/unstable)](https://packagist.org/packages/slexx/pattern) [![License](https://poser.pugx.org/slexx/pattern/license)](https://packagist.org/packages/slexx/pattern)

## Установка

```
$ composer require slexx/pattern
```

## Базовое использование

Это новый язык шаблонов схожий с регулярными выражениями, но значительно проще. Основной класс имеет всего несколько методов, а сам язык прост как валенок. Компилируется в регулярное выражение. 

Пример шаблона:
```
/users[/<id:int>[/<action:edit|delete>]]
```

Он компилируется в следующее регулярное выражение:
```
/^\/users(?:\/(?P<id>[1-9][0-9]*|0)(?:\/(?P<action>edit|delete))?)?(?:\/)?$/
```

Первый вариант намного легче читается и сним проще работать.

Пример использования:
```php
use Slexx\Pattern\Pattrn;

$pattern = new Pattern('/users/<id:int>');
var_dump($pattern->match('/users/5')); // -> ['id' => 5]
```

## Документация

### Текст

В любом тексте который не является синтаксической единицой языка, будут экранированы все символы регулярных выражений.

| Правило       | Регулярное выражение      |
| ------------- | ------------------------- |
| `users/<id>/` | `/^users\/(?P<id>.+)\/$/` |

### Не обязательное

Всё не обязательное, то чо может осуцтвовать в тексте по тем или иным присинам можно просто обнести квадратными скобками.

<table>
    <thead>
        <tr>
            <th>Правило</th>
            <th>Регулярное выражение</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>foo[|bar]</code></td>
            <td><code>/^foo(?:\|bar)?$/</code></td>
        </tr>
    </tbody>
</table>

### Параметры

Параметры обносятся знаком меньше слева и знаком больше справа, их можно использовать 
для того что бы найти какуюто часть текста.

| Правило               | Регулярное выражение                            |
| --------------------- | ----------------------------------------------- |
| `users/<id>/<action>` | `/^users\/(?P<id>.+)\/(?P<action>.+)(?:\/)?$/`  |

#### Правила

В параметре через двоеточие можно указать имя правила для валидации параметра или 
регулярное выражение.

<table>
    <thead>
        <tr>
            <th>Правило</th>
            <th>Регулярное выражение</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>/users/&lt;id:int>/</code></td>
            <td><code>/^\/users\/(?P&lt;id>[1-9][0-9]*|0)\/$/</code></td>
        </tr>
        <tr>
            <td><code>&lt;year:\d{4}>-&lt;month:\d{2}></code></td>
            <td><code>/^(?P&lt;year>\d{4})-(?P&lt;month>\d{2})$/</code></td>
        </tr>
    </tbody>
</table>

##### Установка правил

Для установки правила можно воспользоватся методом `rule` в котором первый аргумент
имя параметра, а второй регулярное выражение.

```php
$pattern = new Pattern('users show <id:slug>');
$pattern->rule('slug', '[\w\d\-]+');

$pattern->match('users show alex1234'); 
// -> ['slug' => 'alex1234'];

$pattern->match('users show {}+'); 
// -> null
```

##### Стандартные правила

Для удобства уже реализовано несколько правил, вот их список:

<table>
    <thead>
        <tr>
            <th>Правило</th>
            <th>Псевдоним</th>
            <th>Регулярное выражение </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>integer</td>
            <td>int</td>
            <td><code>[1-9][0-9]*|0</code></td>
        </tr>
        <tr>
            <td>float</td>
            <td>double</td>
            <td><code>(?:[1-9][0-9]*|0)\.[0-9]*</code></td>
        </tr>
        <tr>
            <td>number</td>
            <td></td>
            <td><code>(?:[1-9][0-9]*|0)(?:\.[0-9]*)?</code></td>
        </tr>
        <tr>
            <td>string</td>
            <td></td>
            <td><code>(?:.|[^.])+</code></td>
        </tr>
        <tr>
            <td>boolean</td>
            <td>bool</td>
            <td><code>true|false|0|1|on|off</code></td>
        </tr>
        <tr>
            <td>word</td>
            <td></td>
            <td><code>\w+</code></td>
        </tr>
        <tr>
            <td>slug</td>
            <td></td>
            <td><code>[\w\d_\-]+</code></td>
        </tr>
    </tbody>
</table>

##### Правило по умолчанию

Если не указывать правило то будет использовано следующее регулярное выражение: `.+`.

#### Приведение типов

Для некоторых стандартных правил работает приведение типов:

* int, integer
* float, double
* bool, boolean

Для все остальных случаев возвращается строка.

```php
$pattern = new Pattern('users list[ --verbose[ <verbose:bool>]][ --offset <offset:int>][ --limit <limit:int>]');

$pattern->match('users list --verbose on --offset 5'); 
// -> ['verbose' => true, 'offset' => 5, 'limit' => null];
```

#### Значения по умолчанию

Для типа `boolean` по умолчанию возвращается `false`, для всех остальных `null`. Значение по умолчанию можно указать с помощью метода `default` где первым аргументом следует имя параметра, а вторым значение по умолчанию.

```php
$pattern = new Pattern('users list[ --verbose[ <verbose:bool>]][ --offset <offset:int>][ --limit <limit:int>]');

$pattern->default('limit', 50);
$pattern->default('verbose', true);

$pattern->match('users list --offset 5'); 
// -> ['verbose' => true, 'offset' => 5, 'limit' => 50];
```

### Проверка

Для проверки текста на соотвецтвие шаблону есть иетод `is`, он принимает строку 
первым параметром и возвращает `boolean`.

```php
$pattern = new Pattern('users/<id:int>[/]');
$pattern->is('users'); // -> false
$pattern->is('users/5'); // -> true
$pattern->is('/users/5/'); // -> true
```


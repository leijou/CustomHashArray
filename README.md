CustomHashArray
===============

Create you own associative arrays in PHP with custom key matching

For a convenient, single-class version of a case insensitive implimentation see [CaseInsensitiveArray][]

[CaseInsensitiveArray]: https://github.com/leijou/CaseInsensitiveArray


Usage
-----

Creating a custom whitespace-insensitive array class. Only one method is required: `hash($key)`
```php
class WhitespaceInsensitiveArray extends CustomHashArray {
    protected function hash($key) {
        return preg_replace('/\s/', '', $key);
    }
}
```

Using the newly created class
```php
$arr = new WhitespaceInsensitiveArray;
$arr['Hello'."\n".'World'] = 'hi';

echo $arr['Hello    World'];
// Outputs: hi

$arr['other'] = 'Other thing';
$arr['HelloWorld'] = 'Same hash';

foreach ($arr as $key => $value) {
    echo $key.' => '.$value."\n";
}
// Outputs:
//   other => Other thing
//   HelloWorld => Same hash
```

Behaviour
---------
Key access is controlled by a hash. When accessing the array's keys they will be returned as the original key that defined them.

If an existing hash is overwritten with a different key the new key will be used when returning key names.

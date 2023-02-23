# PHP Form Validation Package

[![form-validator]][https://packagist.org/packages/mdali-devops/form-validator]

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

This is a form validation package for PHP. Right now it only supports only procedural approach.It is still in continuous development process and the first release is just released.

## Installation

You can install the package via composer:

``` bash
composer require weblintreal/form-validator
```

## Usage

You can use the package in your PHP projects like this:

#### Procedural or functional approach

```php
#include composer autoload file
require_once __DIR__ . '/../vendor/autoload.php';

#now you need to use the namespace in your file where you want to use these functions
use Weblintreal\FormValidator\Functions as fv;

#array for holding errors
$errors = [];

# check if post request of received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    #define rules for each input
    $rules = [
        'name' => ['required'],
        'email' => ['required','email'],
        'message' => ['required'],
    ];
    #define custom error messages for each input
    $messages = [
        'required' => ':attribute is required.',
        'email' => 'Invalid :attribute format.',
        'min' => ':attribute must have at least :min characters.',
    ];

    global $errors;
    #now validate the data
    $errors = fv\validateForm($_POST, $rules, $messages);
    if (empty($errors)) {
        #do database operation or email or anything you want
    } else {
      #show errors or whatever you want
    }
}
```

#### Object Oriented approach

```php
#Coming Soon
```

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information what has changed recently.

## Running Tests

To run tests, run the following command

```bash
  composer test
```

## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.

## Security

If you discover any security related issues, please email <email> instead of using the issue tracker.

## Credits

- [Author](https://www.github.com/weblintreal)
- [CONTRIBUTORS.md](CONTRIBUTORS.md)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) File for more information.
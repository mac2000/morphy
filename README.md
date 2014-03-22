MORPHY
======

Simple wrapper around PHPMorphy

http://phpmorphy.sourceforge.net/dokuwiki/

Installation
------------

Add to your `composer.json`:

    {
        "require": {
            "mac/morphy": "1.0.0"
        },
        "repositories": [
            {
                "type": "package",
                "package": {
                    "name": "mac/morphy",
                    "version": "1.0.0",
                    "dist": {
                        "url": "https://github.com/mac2000/morphy/archive/1.0.0.zip",
                        "type": "zip"
                    },
                    "include-path": [""]
                }
            }
        ],
    }

Usage example
-------------

Here is few examples of library usage (in russian):

    <?php
    use Morphy\Morphy;

    require_once 'vendor/autoload.php';

    $morphy = new Morphy();

    print_r($morphy->base('Киеве'));
    print_r($morphy->all('Киев'));
    print_r($morphy->where('Киев'));

And its output:

    Киев

    Array
    (
        [0] => Киев
        [1] => Киева
        [2] => Киеву
        [3] => Киевом
        [4] => Киеве
        [5] => Киевы
        [6] => Киевов
        [7] => Киевам
        [8] => Киевами
        [9] => Киевах
    )

    Array
    (
        [0] => Киеве
    )


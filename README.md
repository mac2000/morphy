MORPHY
======

Simple wrapper around PHPMorphy

http://phpmorphy.sourceforge.net/dokuwiki/

Installation
------------

Add to your `composer.json`:

    {
        "require": {
            "phpmorphy/phpmorphy": "x",
            "phpmorphy/ru": "x",
            "phpmorphy/en": "x",
            "mac/morphy": "x"
        },
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/mac2000/morphy"
            },
            {
                "type": "package",
                "package": {
                    "name": "phpmorphy/phpmorphy",
                    "version": "0.3.7",
                    "dist": {
                        "url": "http://sourceforge.net/projects/phpmorphy/files/phpmorphy/0.3.7/phpmorphy-0.3.7.zip/download",
                        "type": "zip"
                    },
                    "include-path": [""]
                }
            },
            {
                "type": "package",
                "package": {
                    "name": "phpmorphy/ru",
                    "version": "0.3.7",
                    "dist": {
                        "url": "http://sourceforge.net/projects/phpmorphy/files/phpmorphy-dictionaries/0.3.x/ru_RU/morphy-0.3.x-ru_RU-withjo-utf-8.zip/download",
                        "type": "zip"
                    },
                    "include-path": [""]
                }
            },
            {
                "type": "package",
                "package": {
                    "name": "phpmorphy/en",
                    "version": "0.3.7",
                    "dist": {
                        "url": "http://sourceforge.net/projects/phpmorphy/files/phpmorphy-dictionaries/0.3.x/en_EN/morphy-0.3.x-en_EN-windows-1250.zip/download",
                        "type": "zip"
                    },
                    "include-path": [""]
                }
            }
        ],
        "autoload": {
            "files": [
                "phpmorphy/phpmorphy/src/common.php"
            ]
        }
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


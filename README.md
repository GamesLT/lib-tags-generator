[![license](https://img.shields.io/github/license/GamesLT/lib-tags-generator.svg)](license.txt) 
[![PHP from Packagist](https://img.shields.io/packagist/php-v/GamesLT/lib-tags-generator.svg)](https://php.net)
[![Packagist](https://img.shields.io/packagist/v/GamesLT/lib-tags-generator.svg)](https://packagist.org/packages/gameslt/lib-tags-generator)

# What is this?

PHP Library for generating automatically tags for text content

# How to include?

You can use it by adding to your project with composer by running `composer require gameslt/lib-tags-generator` command on command line or by copying contents of the `src/` folder to your project and including contents.

# How to use it?

Simple! You can get tags list by using this line of code in your file:
```php5
echo \GamesLT\TagsGenerator\TagsGenerator::getInstance()->findTags($title, $short_description, $content);
```

Where `$title` is the title of the content, `$short_description` is short description, and `$content` is real content.

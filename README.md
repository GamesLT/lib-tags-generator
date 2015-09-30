# What is this?

PHP Library for generating automatically tags for text content

# How to include?

You can use it by adding to your project with composer by running `composer require gameslt/lib-tags-generator` command on command line or by copying contents of the `src/` folder to your project and including contents.

# How to use it?

Simple! You can get tags list by using this line of code in your file:
`echo \GamesLT\TagsGenerator\TagsGenerator::getInstance()->findTags($title, $short_description, $content);`

Where `$title` is the title of the content, `$short_description` is short description, and `$content` is real content.

# License

This library use MIT license. See [license.txt](https://raw.githubusercontent.com/MekDrop/tags-generator/master/license.txt) for more info.

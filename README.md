# PHP Classmap Generator
Simple & Lightweight PHP Classmap Generator

[![Latest Stable Version][latest-stable-version-img]][latest-stable-version-link]
[![Latest Unstable Version][latest-Unstable-version-img]][latest-Unstable-version-link]
[![Total Downloads][total-downloads-img]][total-downloads-link]
[![License][license-img]][license-link]
[![composer.lock available][composerlock-img]][composerlock-link]

## Installation
The preferred way to install this extension is through [Composer][composer].

To install **PHP_Classmap_Generator library**, simply:

    $ composer require varunsridharan/php-classmap-generator

The previous command will only install the necessary files, if you prefer to **download the entire source code** you can use:

    $ composer require varunsridharan/php-classmap-generator --prefer-source

You can also **clone the complete repository** with Git:

    $ git clone https://github.com/varunsridharan/php-classmap-generator.git

Or **install it manually**:

[Download PHP_Classmap_Generator.zip][downloadzip]:

    $ wget https://github.com/varunsridharan/php-classmap-generator/archive/master.zip

## Usage
### Config Arguments
| Key | Description |
| --- | ----------- |
| namespace | Which name space to search for. leave empty to get all classes |
| source | string / array of places to search for php classes |
| output | Location & File type to save classmap data |
| excluded | Namespace / File Paths to exclude |
| fullpath | set true to provide full path |

### Sample Config File
***class-mapper.json***
```json
{
  "namespace" : "",
  "source"    : [ "./folder1","./folder2" ],
  "output"    : {
	"location" : "mylocation/class-map-data.php",
	"type" : "php"
  },
  "excluded"  : {
	"namespace" : [ "Test/ABC/"],
	"paths"     : [ "./folder1/unwanted","./folder1/unwanted2"]
  },
  "fullpath"  : false
}
```

## Sample Outputs

### PHP
```php
<?php
/**
 * Last Updated: Wed 02-Oct-2019 / 10:23:12:am
 * Total Class:  3
 * Namespace: 
 */

return array (
  'Namespace\\Class1' => 'your-path/class1.php',
  'Namespace\\Deep1\\Class2' => 'your-path/deep1/class2.php',
  'Simple_Class' => 'simple_class.php',
);
```

### JSON
```json
{
  "Namespace\\Class1"      : "your-path/class1.php",
  "Namespace\\Deep1\\Class2" : "your-path/deep1/class2.php",
  "Simple_Class" : "simple_class.php"
}
```

---

## Contribute
If you would like to help, please take a look at the list of
[issues][issues] or the [To Do](#-todo) checklist.

## License
This project is licensed under **General Public License v3.0 license**. See the [LICENSE](LICENSE) file for more info.

## Copyright
2017 - 2019 Varun Sridharan, [varunsridharan.in][website]

If you find it useful, let me know :wink:

You can contact me on [Twitter][twitter] or through my [email][email].

## Backed By
| [![DigitalOcean][do-image]][do-ref] | [![JetBrains][jb-image]][jb-ref] |  [![Tidio Chat][tidio-image]][tidio-ref] |
| --- | --- | --- |

[twitter]: https://twitter.com/varunsridharan2
[email]: mailto:varunsridharan23@gmail.com
[website]: https://varunsridharan.in
[issues]: issues/
[composer]: http://getcomposer.org/download/
[downloadzip]:https://github.com/varunsridharan/php-classmap-generator/archive/master.zip

[do-image]: https://vsp.ams3.cdn.digitaloceanspaces.com/cdn/DO_Logo_Horizontal_Blue-small.png
[jb-image]: https://vsp.ams3.cdn.digitaloceanspaces.com/cdn/phpstorm-small.png?v3
[tidio-image]: https://vsp.ams3.cdn.digitaloceanspaces.com/cdn/tidiochat-small.png
[do-ref]: https://s.svarun.in/Ef
[jb-ref]: https://www.jetbrains.com
[tidio-ref]: https://tidiochat.com

[latest-stable-version-img]: https://poser.pugx.org/varunsridharan/php-classmap-generator/version
[latest-Unstable-version-img]: https://poser.pugx.org/varunsridharan/php-classmap-generator/v/unstable
[total-downloads-img]: https://poser.pugx.org/varunsridharan/php-classmap-generator/downloads
[Latest-Unstable-version-img]: https://poser.pugx.org/varunsridharan/php-classmap-generator/v/unstable
[license-img]: https://poser.pugx.org/varunsridharan/php-classmap-generator/license
[composerlock-img]: https://poser.pugx.org/varunsridharan/php-classmap-generator/composerlock

[latest-stable-version-link]: https://packagist.org/packages/varunsridharan/php-classmap-generator
[latest-Unstable-version-link]: https://packagist.org/packages/varunsridharan/php-classmap-generator
[total-downloads-link]: https://packagist.org/packages/varunsridharan/php-classmap-generator
[Latest-Unstable-Version-link]: https://packagist.org/packages/varunsridharan/php-classmap-generator
[license-link]: https://packagist.org/packages/varunsridharan/php-classmap-generator
[composerlock-link]: https://packagist.org/packages/varunsridharan/php-classmap-generator

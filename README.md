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

### CMD Line
```
/**
 * Arguments Order.
 * 1. PHP Class Source
 * 2. PHP Classmap File Output
 * 3. Namespace (Leave Empty To Get All)
 * 4. exclude_namespace
 * 5. exclude_path
 * 6. fullpath
 */
composer classmap-generator "your-path-to-php-class" "class-map-output/files.php"
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

<!-- START common-footer.mustache  -->
## 📝 Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

[Checkout CHANGELOG.md](https://github.com/varunsridharan/php-classmap-generator/blob/main/CHANGELOG.md)


## 🤝 Contributing
If you would like to help, please take a look at the list of [issues](https://github.com/varunsridharan/php-classmap-generator/issues/).


## 📜  License & Conduct
- [**GNU General Public License v3.0**](https://github.com/varunsridharan/php-classmap-generator/blob/main/LICENSE) © [Varun Sridharan](website)
- [Code of Conduct](https://github.com/varunsridharan/.github/blob/main/CODE_OF_CONDUCT.md)


## 📣 Feedback
- ⭐ This repository if this project helped you! :wink:
- Create An [🔧 Issue](https://github.com/varunsridharan/php-classmap-generator/issues/) if you need help / found a bug


## 💰 Sponsor
[I][twitter] fell in love with open-source in 2013 and there has been no looking back since! You can read more about me [here][website].
If you, or your company, use any of my projects or like what I’m doing, kindly consider backing me. I'm in this for the long run.

- ☕ How about we get to know each other over coffee? Buy me a cup for just [**$9.99**][buymeacoffee]
- ☕️☕️ How about buying me just 2 cups of coffee each month? You can do that for as little as [**$9.99**][buymeacoffee]
- 🔰         We love bettering open-source projects. Support 1-hour of open-source maintenance for [**$24.99 one-time?**][paypal]
- 🚀         Love open-source tools? Me too! How about supporting one hour of open-source development for just [**$49.99 one-time ?**][paypal]

<!-- Personl Links -->
[paypal]: https://sva.onl/paypal
[buymeacoffee]: https://sva.onl/buymeacoffee
[twitter]: https://sva.onl/twitter/
[website]: https://sva.onl/website/


## Connect & Say 👋
- **Follow** me on [👨‍💻 Github][github] and stay updated on free and open-source software
- **Follow** me on [🐦 Twitter][twitter] to get updates on my latest open source projects
- **Message** me on [📠 Telegram][telegram]
- **Follow** my pet on [Instagram][sofythelabrador] for some _dog-tastic_ updates!

<!-- Personl Links -->
[sofythelabrador]: https://www.instagram.com/sofythelabrador/
[github]: https://sva.onl/github/
[twitter]: https://sva.onl/twitter/
[telegram]: https://sva.onl/telegram/


---

<p align="center">
<i>Built With ♥ By <a href="https://sva.onl/twitter"  target="_blank" rel="noopener noreferrer">Varun Sridharan</a> <a href="https://en.wikipedia.org/wiki/India">
   <img src="https://cdn.svarun.dev/flag-india.jpg" width="20px"/></a> </i> <br/><br/>
   <img src="https://cdn.svarun.dev/codeispoetry.png"/>
</p>

---


<!-- END common-footer.mustache  -->




[composer]: http://getcomposer.org/download/
[downloadzip]:https://github.com/varunsridharan/php-classmap-generator/archive/master.zip

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

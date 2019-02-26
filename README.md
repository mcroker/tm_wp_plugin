# TM_WP_Plugin

Class library for wordpress development - works as a superclass to post and taxonomy classes, providing data access (as properties) plus some additional functions such as automatic creation of metadata boxes.

## Getting Started

Install tm_wp_plugin as wordpress plugin.

Within your plugin create class objects extendign TMBasePost or TMBaseTax superclass.

```
if (! class_exists('TMSessionPlan')):
  class TMSessionPlan extends TMBasePost {
    protected static $post_type = 'tm_sessionplan';   // Required

    protected static $labels = Array(
      'singular_name'       => 'Session Plan'         // Required
   );

    function __construct($sessionid = 0) {
      parent::__construct($sessionid);
    }

  }
  TMSessionPlan::init();
endif;
```

Load your plugin into wordpress as normal

## Usage

* [BASEPOST.md](https://github.com/mcroker/tm_wp_plugin/blob/master/docs/BASEPOST.md)
* [BASETAX.md](https://github.com/mcroker/tm_wp_plugin/blob/master/docs/BASETAX.md)

## Built With

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags).

## Authors

* **Martin Croker** - *Initial work* - [martincroker](https://github.com/martincroker)

See also the list of [contributors](https://github.com/your/project/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc

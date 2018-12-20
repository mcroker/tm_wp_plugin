# Usage guide - BasePost

## Fields

```
protected static $post_type;
protected static $labels;
protected static $args = [];
protected static $meta_keys = [];
protected static $setting_keys = [];
protected static $tmargs = [];
```

## post_type (Required)

Provides the post_type name.

```
protected static $post_type = 'tm_sessionplan';
```

## labels

Array of labels which are merged with default values based on singular_name.

* singular_name (Required)
* Slug (Optional) - enables the slug to be set

```
protected static $labels = Array(
  'singular_name'       => 'Session Plan',
  'slug'                => 'sessionplans'
  ...[others as specified in wordpress docs](https://codex.wordpress.org/Function_Reference/register_post_type)...
);
```

## args

Array of arguments for post_type as specified in [wordpress docs](https://codex.wordpress.org/Function_Reference/register_post_type)
Where nothing specified default values are taken

```
protected static $args = Array (
  'supports'            => array( 'title', 'editor', 'author', 'revisions', 'thumbnail', 'revisions'),
);
```

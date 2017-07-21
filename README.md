# WordPress Output Buffering

A simple [mu-plugin](https://codex.wordpress.org/Must_Use_Plugins) that buffers the entire WP process, capturing the final output for DOM manipulation.

[Original code](http://stackoverflow.com/a/22818089/3799374) by [kfriend](https://stackoverflow.com/users/419673/kfriend) on Stack Overflow. Licensed as GPL because it is a WordPress derivative.

### Usage

##### Simple Example

This code will replace any instance of "foo" on the web page with "bar":

```
add_filter( 'final_output', function($output) {
    return str_replace('foo', 'bar', $output);
});
```

### Configuration

By default, output buffering is only **enabled** on the *frontend* of the site and is **disabled** for *WP Admin* and during *AJAX* requests.

You can modify this behavior and specify on which request/screen types that you want to enable it by adding the following constants to `wp-config.php`:

##### PHP (any version)

```
define( 'OB_ENABLE_ADMIN', true ); // Enables output buffering in WP Admin
define( 'OB_ENABLE_AJAX', true ); // Enables output buffering during AJAX calls
```

##### PHP 7.0 and Higher

```
define( 'OB_REQUEST_TYPES', array( 'site', 'admin', 'ajax' ) ); // Case-sensitive
```

In the example above, output buffering is enable on the frontend ("site"), in WP Admin ("admin") and during AJAX requests ("ajax"). Add or remove from the array as desired. For example, to **only** load output buffering in WP Admin and **not** on the frontend or during AJAX calls:

```
define( 'OB_ENABLE_SCREENS', array( 'admin' ) ); // Case-sensitive
```

##### Caution

Take care when changing this behavior! You may experience issues when enabling in WP Admin or during AJAX calls if your manipulation logic interferes with normal WordPress function.

Always test first before using in a production setting!

## Changelog

**1.0.3 (master)**
* Added wp-config.php constants to control where output buffering is enabled.

**1.0.0**
* Initial commit

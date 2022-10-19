# Hagakure - Yet Another Error Reporter

Contributors: kuno1, Takahashi_Fumiki  
Tags: php, error, recovery  
Requires at least: 5.8  
Requires PHP: 7.0  
Tested up to: 6.0  
Stable Tag: nightly  
License: GPLv3 or later  
License URI: https://www.gnu.org/licenses/gpl-3.0.html


A WordPress plugin to clarify meaningless errors like "Allowed memory size of xxxxxxxx bytes exhausted".

<!-- only:github/ -->
![example workflow](https://github.com/kuno1/hagakure/actions/workflows/wordpress.yml/badge.svg)
<!-- /only:github -->

## Description

Have you ever seen an error log like below?

> PHP Fatal error: Allowed memory size of xxxxxx bytes exhausted (tried to allocate xxx bytes) in /var/www/wordpress/wp-includes/wp-db.php on line 2007

This means that [PHP memory limit](https://www.php.net/manual/en/ini.core.php#ini.memory-limit) is exhausted while retrieving data from database. In many case, this happens when your site has big data and making insane loops inside.

But we want to know that **which plugin tried to retrieve data?**

Hagakure adds extra information to error.log file when `wp-db.php` causes memory limit error:

```
[08-May-2019 10:28:37 UTC] wpdb Error Backtrace:
#1      Kunoichi\Hagakure\DbLogger->filter_query()      /app/public/wp-includes/class-wp-hook.php       Line 286
#2      WP_Hook->apply_filters()        /app/public/wp-includes/plugin.php      Line 208
#3      apply_filters   /app/public/wp-includes/wp-db.php       Line 1871
#4      wpdb->query()   /app/public/wp-includes/wp-db.php       Line 2579
#5      wpdb->get_results()     /app/public/wp-includes/class-wp-query.php      Line 2979
#6      WP_Query->get_posts()   /app/public/wp-includes/class-wp-query.php      Line 3387
#7      WP_Query->query()       /app/public/wp-includes/post.php        Line 1961
#8      get_posts       /app/public/wp-content/plugins/hagakure/hagakure.php    Line 34
#9      {closure}       /app/public/wp-includes/class-wp-hook.php       Line 286
#10     WP_Hook->apply_filters()        /app/public/wp-includes/class-wp-hook.php       Line 310
#11     WP_Hook->do_action()    /app/public/wp-includes/plugin.php      Line 465
#12     do_action       /app/public/wp-includes/template-loader.php     Line 13
#13     require_once    /app/public/wp-blog-header.php  Line 19
#14     require /app/public/index.php   Line 17
```

This log will always follow memoly limit Fatal Error by `wp-db.php`. Now you can find `#8` calls `get_posts` repeatedly.

We recommend to watch logs with notification services like [CloudWatch Logs](https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/WhatIsCloudWatchLogs.html).
This error occurs on the productional environment and you may not have chanses to see it occurs.
We use Hagakure with our [hosting service](https://hosting.kunoichiwp.com/), please looking forward to see [our blog published](https://kunoichiwp.com/blog) and describing about the integration!

This plugin also adds debug backtrace to error logs. To modify error level to detailed backtrace, define constant:

```
define( 'HAGAKURE_ERROR_LEVEL', E_NOTICE | E_USER_WARNING | E_WARNING | E_USER_ERROR; );
```

## Installation

1. Upload `hagakure` folder to the `/wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. That's it. This plugin will work as background.

## Frequently Asked Questions

### How can I contribute?

We host this plugin on GitHub [kuno1/hagakure](https://github.com/kuno1/hagakure). Please feel free to send [PRs](https://github.com/kuno1/hagakure/pulls) or to make [issues](https://github.com/kuno1/hagakure/issues).

## Screenshots

W.I.P

## Changelog

### 1.1.0

* Add Request URI to backtrace.

### 1.0.0

* Add warning and notice detail handler.

### 0.8.0

* first Release.

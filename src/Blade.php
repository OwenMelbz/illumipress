<?php

namespace OwenMelbz\IllumiPress;

use \Illuminate\Filesystem\Filesystem;

/**
 * Uses the Blade templating engine
 * Originally written by tormjens/wp-blade
 * Adapted for IllumiPress
 */
class Blade
{

    /**
     * @var bool
     */
    protected static $enabled = true;

    /**
     * The single instance of the class.
     *
     * @var
     */
    protected static $_instance = null;

    /**
     * View factory
     *
     * @var OwenMelbz\IllumiPress\BladeFactory
     */
    protected $factory;

    /**
     * View folder
     *
     * @var string
     */
    protected $views;

    /**
     * Cache folder
     *
     * @var string
     */
    protected $cache;

    /**
     * Set up hooks and initialize Blade
     */
    public function __construct($actions = true)
    {
        do_action('wp_blade_booting');

        // Directory where views are loaded from
        $viewDirectory = trailingslashit(
            apply_filters(
                'wp_blade_views_directory',
                defined('BLADE_VIEWS') ?
                BLADE_VIEWS : trailingslashit(get_template_directory())
            )
        );

        // Directory where compiled templates are cached
        $cacheDirectory = trailingslashit(
            apply_filters(
                'wp_blade_cache_directory',
                defined('BLADE_CACHE') ?
                BLADE_CACHE : trailingslashit(wp_upload_dir()['basedir']) . '.cache'
            )
        );

        // Set class properties
        $this->views = [
            $viewDirectory,
            $cacheDirectory
        ];

        $this->cache = $cacheDirectory;

        // Create cache directories if needed
        $this->maybeCreateCacheDirectory();

        // Create the blade instance
        $this->factory = new BladeFactory($this->views, $this->cache);

        // extend the compiler
        $this->extend();
        
        if ($actions && static::isEnabled()) {
            add_action('template_include', array($this, 'blade_include'));
        }

        do_action('wp_blade_booted', $this);
    }

    /**
     * Returns if blade rendering is enabled or not
     *
     * @return bool
     */
    public static function isEnabled()
    {
        return static::$enabled;
    }

    /**
     * Turns on the rendering via blade
     */
    public static function turnOn()
    {
        static::$enabled = true;
    }


    /**
     * Turns off the rendering via blade
     */
    public static function turnOff()
    {
        static::$enabled = false;
    }

    /**
     * Clears the cache folder
     */
    public static function clearCache()
    {
        (new \Illuminate\Filesystem\Filesystem)
        ->deleteDirectory(static::create()->cache, true);
    }

    /**
     * Main Blade Instance.
     *
     * Ensures only one instance of Blade is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @return OwenMelbz\IllumiPress\Blade
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Set up hooks and initialize Blade
     */
    public static function create()
    {
        return self::instance();
    }

    /**
     * Return the compiler instance
     * @return mixed
     */
    public function compiler()
    {
        return $this->factory->compiler();
    }

    /**
     * Return the factory instance
     * @return mixed
     */
    public function factory()
    {
        return $this->factory;
    }

    /**
     * Returns the transpiled template into vanilla php
     *
     * @param string $template path to the template
     * @param array $with Additional args to pass to the template
     * @return string Compiled template
     */
    public function view($template, $with = [])
    {
        $template = apply_filters('wp_blade_include_template', $template, $with);
        $with = apply_filters('wp_blade_include_arguments', $with, $template);
        $html = apply_filters('wp_blade_include_html', $this->factory->render($template, $with), $template, $with);
        
        return $html;
    }

    /**
     * Checks whether the cache directory exists, and if not creates it.
     *
     * @return boolean
     */
    public function maybeCreateCacheDirectory()
    {
        if (!is_dir($this->cache)) {
            if (wp_mkdir_p($this->cache)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Include the template
     *
     * @return string
     */
    public function blade_include($template)
    {
        if (!$template || !static::isEnabled() || !file_exists($template)) {
            return $template;
        }
        
        $compiledTemplate = $this->cache . md5($template) . '_' . str_replace('.php', '', basename($template)) . '.blade.php';
        $bladeFileName = str_replace('.blade.php', '', basename($compiledTemplate));

        if (!str_contains($template, '.blade.php')) {
            if ($this->viewHasExpired($template, $compiledTemplate)) {
                copy($template, $compiledTemplate);
            }
        } else {
            $bladeFileName = str_replace('.blade.php', '', basename($template));
        }

        echo $this->view($bladeFileName);

        return '';
    }

    /**
     * Checks if the view was changed after we stored it for caching
     *
     * @param string  $path Path to the file
     * @return boolean
     */
    protected function viewHasExpired($source, $cached)
    {
        if (!file_exists($cached)) {
            return true;
        }

        $sourceLastMod = filemtime($source);
        $cacheLastMod = filemtime($cached);

        return $sourceLastMod >= $cacheLastMod;
    }

    /**
     * Extend blade with some custom directives
     *
     * @return void
     */
    protected function extend()
    {
        $this->compiler()->directive('post', function () {
            return '<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>';
        });

        $this->compiler()->directive('wpquery', function ($expression) {
            $php  = '<?php $bladequery = new WP_Query'.$expression.'; ';
            $php .= 'if ( $bladequery->have_posts() ) : ';
            $php .= 'while ( $bladequery->have_posts() ) : ';
            $php .= '$bladequery->the_post(); ?> ';
            return $php;
        });

        $this->compiler()->directive('wpempty', function () {
            return '<?php endwhile; ?><?php else: ?>';
        });

        $this->compiler()->directive('wpend', function () {
            return '<?php endif; wp_reset_postdata(); ?>';
        });

        $this->compiler()->directive('acfempty', function () {
            return '<?php endwhile; ?><?php else: ?>';
        });

        $this->compiler()->directive('acfend', function () {
            return '<?php endif; ?>';
        });

        $this->compiler()->directive('acf', function ($expression) {
            $php = '<?php if ( have_rows'.$expression.' ) : ';
            $php .= 'while ( have_rows'.$expression.' ) : the_row(); ?>';

            return $php;
        });

        $this->compiler()->directive('acffield', function ($expression) {
            $php = '<?php if ( get_field'.$expression.' ) : ';
            $php .= 'the_field'.$expression.'; endif; ?>';

            return $php;
        });

        $this->compiler()->directive('acfhas', function ($expression) {
            $php = '<?php if ( $field = get_field'.$expression.' ) : ';
            
            return $php;
        });

        $this->compiler()->directive('acfsub', function ($expression) {
            $php = '<?php if ( get_sub_field'.$expression.' ) : ';
            $php .= 'the_sub_field'.$expression.'; endif; ?>';
            
            return $php;
        });

        do_action('wp_blade_add_directive', $this->factory->compiler());
    }
}

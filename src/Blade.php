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
     * @var BladeFactory
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
     * @param bool $actions
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
                defined('ILLUMINATE_CACHE') ?
                ILLUMINATE_CACHE : trailingslashit(wp_upload_dir()['basedir']) . '.cache'
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
     *
     * @return Blade
     */
    public static function turnOn()
    {
        static::$enabled = true;

        return static::create();
    }


    /**
     * Turns off the rendering via blade
     */
    public static function turnOff()
    {
        return static::$enabled = false;
    }

    /**
     * Clears the cache folder
     */
    public static function clearCache()
    {
        return (new Filesystem)->deleteDirectory(static::create()->cache, true);
    }

    /**
     * Main Blade Instance.
     *
     * Ensures only one instance of Blade is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @return Blade
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
    protected function maybeCreateCacheDirectory()
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
     * @param $template
     * @param array $with
     * @param bool $return
     * @return string
     */
    public function blade_include($template, $with = [], $return = false)
    {
        if (!$template || !static::isEnabled()) {
            return $template;
        }

        $compiledTemplate = $this->cache . md5($template) . '_' . str_replace('.php', '', basename($template)) . '.blade.php';
        $bladeFileName = str_replace('.blade.php', '', basename($compiledTemplate));

        if (!str_contains($template, '.blade.php')) {
            if ($this->viewHasExpired($template, $compiledTemplate)) {
                $templateContent = file_get_contents($template);
                $templateContent = str_replace('get_header()', "echo view('header')", $templateContent);
                $templateContent = str_replace('get_footer()', "echo view('footer')", $templateContent);
                file_put_contents($compiledTemplate, $templateContent);
            }
        } else {
            $bladeFileName = str_replace('.blade.php', '', basename($template));
        }

        if ($return) {
            return $this->view($bladeFileName, $with);
        }

        echo $this->view($bladeFileName, $with);

        return '';
    }

    /**
     * Checks if the view was changed after we stored it for caching
     *
     * @param $source
     * @param $cached
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
     * Returns the fully qualified file path of
     * a template name passed in.
     *
     * @param string $name template name
     * @return string $path Path to the file
     */
    public function resolveTemplatePath($name)
    {
        $basename = basename($name);
        $basename = str_replace(['.blade.php', '.php'], '', $basename);

        if ($this->factory->exists($basename)) {
            $filename = $this->factory->api()->getFinder()->find($basename);
            $filename = str_replace('//', '/', $filename);
            $filename = str_replace('\\\\', '\\', $filename);

            return $filename;
        }

        return null;
    }

    /**
     * Extend blade with some custom directives
     *
     * @return void
     */
    protected function extend()
    {
        $endWhile = '<?php endwhile; ?>';
        $endIf = '<?php endif; ?>';
        $endWhileEndIf = '<?php endwhile; endif; ?>';
        $endIfEndWhile = '<?php endif; endwhile; ?>';

        $this->compiler()->directive('post', function ($expression) {
            return '<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>';
        });

        $this->compiler()->directive('endpost', function ($expression) use ($endWhileEndIf) {
            return $endWhileEndIf;
        });

        $this->compiler()->directive('field', function ($expression) {
            $code = <<<EOT
            <?php if (get_field($expression)) :
            the_field($expression);
            endif; ?>
EOT;
            return $code;
        });

        $this->compiler()->directive('subfield', function ($expression) {
            $code = <<<EOT
            <?php if (get_sub_field($expression)) :
            the_sub_field($expression);
            endif; ?>
EOT;
            return $code;
        });

        $this->compiler()->directive('repeater', function ($expression) {
            $code = <<<EOT
            <?php if( have_rows($expression) ):
                while ( have_rows($expression) ) : the_row(); ?>
EOT;
            return $code;
        });

        $this->compiler()->directive('endrepeater', function ($expression) use ($endWhileEndIf) {
            return $endWhileEndIf;
        });

        $this->compiler()->directive('has', function ($expression) {
            return "<?php if (get_field($expression)) : ?>";
        });

        $this->compiler()->directive('has', function () use ($endIf) {
            return $endIf;
        });

        $this->compiler()->directive('resetwp', function () {
            return '<?php wp_reset_postdata(); ?>';
        });

        $this->compiler()->directive('wpquery', function ($expression) {
            $code = <<<EOT
            <?php \$query = new WP_Query($expression);
            if (\$query->have_posts()) :
                while(\$query->have_posts()) : \$query->the_post(); ?>
EOT;
            return $code;
        });

        $this->compiler()->directive('endwpquery', function () {
            $code = <<<EOT
            <?php endwhile; endif; ?>
            <?php wp_reset_postdata(); ?>
EOT;

            return $code;
        });

        do_action('wp_blade_add_directive', $this->factory->compiler());
    }
}

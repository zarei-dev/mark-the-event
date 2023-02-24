<?php

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

use League\Container\Container;
use League\Container\ReflectionContainer;
use TypistTech\WPContainedHook\Hooks\Action;
use TypistTech\WPContainedHook\Hooks\Filter;
use TypistTech\WPContainedHook\Loader;

class Plugin
{
    const PREFIX = 'zareidev_mark_the_event';

    /**
     * The container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * The loader instance
     *
     * @var Loader
     */
    protected $loader;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->loader = new Loader($this->container);
    }

    /**
     * Begins execution of the plugin.
     *
     * - set up the container
     * - add actions and filters
     *
     * @return void
     */
    public function run()
    {
        $this->setUpContainer();
        $this->setUpLoader();

        add_action('plugins_loaded', function () {
            // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable -- Because of phpcs bug.
            do_action(static::PREFIX . '_register', $this->container);
        }, PHP_INT_MAX - 1000);

        add_action('plugins_loaded', function () {
            // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable -- Because of phpcs bug.
            do_action(static::PREFIX . '_boot', $this->container);
        }, PHP_INT_MIN + 1000);
    }

    /**
     * Set up container.
     *
     * @return void
     */
    protected function setUpContainer()
    {
        // Register the reflection container as a delegate to enable auto wiring.
        $this->container->delegate(
            new ReflectionContainer()
        );
    }

    /**
     * Set up loader.
     *
     * @return void
     */
    protected function setUpLoader()
    {
        $this->loader->add(
            new Action('init', eventCPT::class, 'RegisterEventPostType', 10, 2),
            new Action('init', eventCPT::class, 'RegisterEventTaxonomies', 10, 2),
            new Filter('acf/settings/save_json', ACFJSON::class, 'Save', 10, 2),
            new Filter('acf/settings/load_json', ACFJSON::class, 'Load', 10, 2),
            new Filter('acf/json_directory', ACFJSON::class, 'Directory', 10, 2),
        );
        $this->loader->run();
    }
}
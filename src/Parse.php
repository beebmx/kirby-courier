<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier;

use Kirby\Cms\App;
use Kirby\Exception\NotFoundException;
use Kirby\Filesystem\F;
use Kirby\Template\Snippet;
use Kirby\Template\Template;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class Parse
{
    protected array $data = [];

    public function __construct(protected string $template, protected string $theme = 'default') {}

    public function data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function render(): Content
    {
        $template = $this->getTemplate($this->template);
        $theme = $this->getTheme($this->theme);

        return new Content(
            (new CssToInlineStyles)->convert(
                $this->renderTemplate($template, 'html'),
                $theme
            )
        );
    }

    public function renderText(): Content
    {
        return new Content(
            $this->renderTemplate($this->getTemplate($this->template), 'text')
        );
    }

    /**
     * Renders the template with the html or text elements
     *
     * @throws NotFoundException
     */
    protected function renderTemplate(Template $template, string $type): string
    {
        if ($template->exists() === false) {
            throw new NotFoundException(
                message: 'The courier template "'.$template->name().'" does not exist'
            );
        }

        $this->loadElements($template, $type);

        return $template->render($this->data);
    }

    /**
     * Loads the Blade components for Blade templates,
     * otherwise the Kirby snippets
     */
    protected function loadElements(Template $template, string $type): void
    {
        Blade::isAvailable() && Blade::isTemplate($template->file())
            ? Blade::components($type)
            : $this->loadSnippets($type);
    }

    protected function loadSnippets(string $type): void
    {
        $snippets = [];

        foreach (['button', 'code', 'footer', 'header', 'layout', 'message', 'panel', 'subcopy', 'table'] as $snippet) {
            $snippets['courier/'.$snippet] = dirname(__DIR__)."/snippets/{$type}/{$snippet}.php";

            // Kirby caches the resolved snippet files, which would
            // keep the html snippets when rendering the text version
            unset(Snippet::$cache['courier/'.$snippet]);
        }

        App::instance()->extend(['snippets' => $snippets]);
    }

    protected function directory(): string
    {
        return App::instance()->option('beebmx.courier.path', 'courier');
    }

    protected function path(): string
    {
        return App::instance()->roots()->templates().'/'.$this->directory();
    }

    protected function getTemplate(string $name): Template
    {
        return App::instance()->template($this->directory().'/'.$name);
    }

    protected function getTheme(string $theme): string
    {
        return match (true) {
            F::exists($file = $this->path().'/themes/'.$theme.'.css') => F::read($file),
            default => F::read(dirname(__DIR__).'/themes/default.css'),
        };
    }
}

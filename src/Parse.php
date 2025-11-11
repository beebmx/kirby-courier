<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier;

use Kirby\Cms\App;
use Kirby\Filesystem\F;
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
        $html = $this->getTemplate($this->template);
        $theme = $this->getTheme($this->theme);

        if ($html->exists()) {
            $this->loadElements(type: 'html');
            $html = $html->render($this->data);
        }

        return new Content(
            (new CssToInlineStyles)->convert($html, $theme)
        );
    }

    public function renderText(): Content
    {
        $text = $this->getTemplate($this->template);

        if ($text->exists()) {
            $this->loadElements(type: 'text');
            $text = $text->render($this->data);
        }

        return new Content(
            $text
        );
    }

    protected function loadElements(string $type): void
    {
        class_exists('Beebmx\\KirbyBlade\\Facades\\Blade')
            ? $this->loadComponents($type)
            : $this->loadSnippets($type);
    }

    protected function loadComponents(string $type): void
    {
        \Illuminate\Container\Container::getInstance()
            ->get('view')->flushFinderCache();

        \Beebmx\KirbyBlade\Facades\Blade::replaceAnonymousComponentPath(
            dirname(__DIR__)."/components/{$type}", 'courier'
        );
    }

    protected function loadSnippets(string $type): void
    {
        App::instance()->extend(['snippets' => [
            'courier/button' => dirname(__DIR__)."/snippets/{$type}/button.php",
            'courier/code' => dirname(__DIR__)."/snippets/{$type}/code.php",
            'courier/footer' => dirname(__DIR__)."/snippets/{$type}/footer.php",
            'courier/header' => dirname(__DIR__)."/snippets/{$type}/header.php",
            'courier/layout' => dirname(__DIR__)."/snippets/{$type}/layout.php",
            'courier/message' => dirname(__DIR__)."/snippets/{$type}/message.php",
            'courier/panel' => dirname(__DIR__)."/snippets/{$type}/panel.php",
            'courier/subcopy' => dirname(__DIR__)."/snippets/{$type}/subcopy.php",
            'courier/table' => dirname(__DIR__)."/snippets/{$type}/table.php",
        ]]);
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

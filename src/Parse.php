<?php

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
        $html = $this->getTemplate($this->template, 'html');
        $theme = $this->getTheme($this->theme);

        if ($html->exists()) {
            $html = $html->render($this->data);
        }

        return new Content(
            (new CssToInlineStyles)->convert($html, $theme)
        );
    }

    protected function directory(): string
    {
        return App::instance()->option('beebmx.kirby-courier.path', 'courier');
    }

    protected function path(): string
    {
        return App::instance()->roots()->templates().'/'.$this->directory();
    }

    protected function getTemplate(string $name, ?string $type = null): Template
    {
        return App::instance()->template($this->directory().'/'.$name, $type, $type);
    }

    protected function getTheme(string $theme): string
    {
        return match (true) {
            F::exists($file = $this->path().'/themes/'.$theme.'.css') => F::read($file),
            default => F::read(dirname(__DIR__).'/themes/default.css'),
        };
    }
}

<?php

declare(strict_types=1);

namespace Duyler\TwigWrapper;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigWrapper
{
    private FilesystemLoader $loader;
    private Environment $twig;
    private array $variables = [];

    public function __construct(TwigConfigDto $config)
    {
        $this->loader = new FilesystemLoader(
            $config->pathToViews
        );

        $this->twig = new Environment($this->loader);

        foreach ($config->extensions as $extension) {
            $this->twig->addExtension(new $extension($config));
        }
    }

    public function content(array $variables): self
    {
        $this->variables = $this->variables + $variables;

        return $this;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $template): string
    {
        $content = $this->twig->render($template . '.twig', $this->variables);
        $this->variables = [];
        return $content;
    }

    public function exists(string $template): bool
    {
        return $this->loader->exists($template . '.twig');
    }
}

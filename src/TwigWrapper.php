<?php

declare(strict_types=1);

namespace Duyler\TwigWrapper;

use Duyler\Config\Config;
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

    public function __construct(Config $config)
    {
        $this->loader = new FilesystemLoader(
            $config->env(Config::PROJECT_ROOT) .
            $config->get('twig', 'path_to_view')
        );

        $this->twig = new Environment($this->loader);

        $extensions = $config->get('twig', 'extensions');

        foreach ($extensions as $extension) {
            $this->twig->addExtension(new $extension);
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
        return $this->twig->render($template . '.twig', $this->variables);
    }

    public function exists(string $template): bool
    {
        return $this->loader->exists($template . '.twig');
    }
}

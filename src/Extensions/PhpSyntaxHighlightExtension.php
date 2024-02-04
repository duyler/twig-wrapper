<?php

declare(strict_types=1);

namespace Duyler\TwigWrapper\Extensions;

use Duyler\TwigWrapper\TwigConfigDto;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PhpSyntaxHighlightExtension extends AbstractExtension
{
    public function __construct(private readonly TwigConfigDto $config) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('php_highlight', [$this, 'phpToHtml']),
        ];
    }

    public function phpToHtml(string $phpCode): string
    {
        $file = $this->config->projectRoot . $phpCode . '.php';
        if (is_file($file)) {
            return $this->wrap(highlight_file($file, true));
        }

        return $this->wrap(highlight_string($phpCode, true));
    }

    private function wrap(string $code): string
    {
        return <<<HTML
                <div class="highlight bg-body-tertiary">
                     <p class="font-monospace p-4">
                     {$code}
                    </p>
                </div>
            HTML;
    }
}

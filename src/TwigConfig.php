<?php

declare(strict_types=1);

namespace Duyler\TwigWrapper;

class TwigConfig
{
    public function __construct(
        public string $pathToViews,
        public array $extensions = [],
    ) {}
}

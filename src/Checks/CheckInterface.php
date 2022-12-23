<?php

namespace Vormkracht10\Seo\Checks;

use Closure;

/**
 * @property string $title
 * @property string $priority
 * @property int $timeToFix
 * @property int $scoreWeight
 * @property bool $checkSuccessful
 *
 * @method handle()
 */
interface CheckInterface
{
    public function handle(array $request, Closure $next): array;
}

<?php

declare(strict_types=1);

namespace Scheel\Tracer;

use Illuminate\Support\Collection;

use function collect;

/** @extends Collection<int, Frame> */
class StackTrace extends Collection
{
    public static function getTrace(): self
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $frames = collect($backtrace)
            ->filter(fn (array $frame): bool => isset(
                $frame['file'],
                $frame['line'],
            ))
            ->values()
            ->mapInto(Frame::class);

        return self::make($frames);
    }

    public function ignoreVendor(): self
    {
        return $this->filter(fn (Frame $frame): bool => ! $frame->isVendor());
    }

    public function ignoreFile(string $file, ?int $line = null): self
    {
        return $this->filter(
            fn (Frame $frame): bool => $file !== $frame->file || ($line !== null && $frame->line !== $line)
        );
    }

    public function ignoreClass(string $class, ?string $function = null): self
    {
        return $this->filter(
            fn (Frame $frame): bool => $class !== $frame->class || ($function !== null && $frame->function !== $function)
        );
    }
}

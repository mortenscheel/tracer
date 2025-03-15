<?php

declare(strict_types=1);

namespace Scheel\Tracer;

use Illuminate\Contracts\Support\Arrayable;

use function Scheel\EditorLinks\editorLink;
use function str_contains;

/** @implements Arrayable<string, mixed> */
class Frame implements Arrayable
{
    public string $file;

    public int $line;

    public string $function;

    public ?string $class;

    public ?string $type = null;

    /**
     * @param  array{file: string, line: int, function: string, class?: string, type?: string}  $frame
     */
    public function __construct(
        array $frame,
    ) {
        $this->file = $frame['file'];
        $this->line = $frame['line'];
        $this->function = $frame['function'];
        $this->class = $frame['class'] ?? null;
        $this->type = $frame['type'] ?? null;
    }

    public function isVendor(): bool
    {
        return str_contains($this->file, DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR);
    }

    public function location(bool $baseName = false): string
    {
        $file = $baseName ? basename($this->file) : $this->file;

        return "$file:$this->line";
    }

    public function editorLink(): string
    {
        return editorLink($this->file, $this->line);
    }

    public function toArray(): array
    {
        return [
            'file' => $this->file,
            'line' => $this->line,
            'function' => $this->function,
            'class' => $this->class,
            'type' => $this->type,
        ];
    }
}

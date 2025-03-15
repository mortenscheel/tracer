<?php

declare(strict_types=1);

use Scheel\Tracer\Frame;
use Scheel\Tracer\StackTrace;

arch()->preset()->php()->ignoring('debug_backtrace');
arch()->preset()->security();
it('includes vendor frames by default', function (): void {
    expect(
        StackTrace::getTrace()->filter(fn (Frame $frame): bool => $frame->isVendor())
    )->not->toBeEmpty();
});
it('can ignore vendor frames', function (): void {
    expect(
        StackTrace::getTrace()->ignoreVendor()->filter(fn (Frame $frame): bool => $frame->isVendor())
    )->toBeEmpty();
});
it('includes StackTrace frames by default', function (): void {
    expect(
        StackTrace::getTrace()->first(fn (Frame $frame): bool => $frame->class === StackTrace::class)
    )->not->toBeNull();
});
it('can ignore a specific class', function (): void {
    $a = StackTrace::getTrace()->ignoreClass(StackTrace::class)->toArray();
    expect(
        StackTrace::getTrace()->ignoreClass(StackTrace::class)->filter(
            fn (Frame $frame): bool => $frame->class === StackTrace::class
        )
    )->toBeEmpty();
});
it('can ignore a specific class method', function (): void {
    expect(StackTrace::getTrace()
        ->ignoreClass(StackTrace::class, 'ignoreVendor')->first(
            fn (Frame $frame): bool => $frame->class === StackTrace::class && $frame->function === 'getTrace'
        ))->not->toBeNull()
        ->and(StackTrace::getTrace()
            ->ignoreClass(StackTrace::class, 'getTrace')->first(
                fn (Frame $frame): bool => $frame->class === StackTrace::class && $frame->function === 'getTrace'
            ))->toBeNull();

});
it('can ignore a specific file', function (): void {
    $stackTraceFilename = (new ReflectionClass(StackTrace::class))->getFileName();
    expect(
        StackTrace::getTrace()->ignoreFile($stackTraceFilename)->filter(
            fn (Frame $frame): bool => $frame->file === $stackTraceFilename
        )
    )->toBeEmpty();
});
it('can ignore a specific line in a file', function (): void {
    $a = StackTrace::getTrace()->ignoreFile(__FILE__, 1)->count();
    $b = StackTrace::getTrace()->ignoreFile(__FILE__, __LINE__)->count();
    expect($a)->toBeGreaterThan($b);
});
it('can format the frame location', function (): void {
    expect(StackTrace::getTrace()->first()->location())->toBe(__FILE__.':'.__LINE__);
});
it('can format the frame location using basename', function (): void {
    expect(StackTrace::getTrace()->first()->location(true))->toBe(basename(__FILE__).':'.__LINE__);
});
it('can seralize the frame to array', function (): void {
    $line = __LINE__ + 1;
    expect(StackTrace::getTrace()->first()->toArray())->toBe([
        'file' => __FILE__,
        'line' => $line,
        'function' => 'getTrace',
        'class' => StackTrace::class,
        'type' => '::',
    ]);
});
it('can provide an editor link to the frame', function (): void {
    $file = __FILE__;
    $line = __LINE__ + 1;
    expect(StackTrace::getTrace()->first()->editorLink())
        ->toBe("phpstorm://open?file=$file&line=$line");
});

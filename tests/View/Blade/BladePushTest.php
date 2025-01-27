<?php

namespace Illuminate\Tests\View\Blade;

use Illuminate\Support\Str;

class BladePushTest extends AbstractBladeTestCase
{
    public function testPushIsCompiled()
    {
        $string = '@push(\'foo\')
test
@endpush';
        $expected = '<?php $__env->startPush(\'foo\'); ?>
test
<?php $__env->stopPush(); ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPushIsCompiledWithParenthesis()
    {
        $string = '@push(\'foo):))\')
test
@endpush';
        $expected = '<?php $__env->startPush(\'foo):))\'); ?>
test
<?php $__env->stopPush(); ?>';
        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPushOnceIsCompiled()
    {
        $string = '@pushOnce(\'foo\', \'bar\')
test
@endPushOnce';

        $expected = '<?php if (! $__env->hasRenderedOnce(\'foo:bar\')): $__env->markAsRenderedOnce(\'foo:bar\');
$__env->startPush(\'foo\'); ?>
test
<?php $__env->stopPush(); endif; ?>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPushOnceIsCompiledWhenIdIsMissing()
    {
        Str::createUuidsUsing(fn () => 'e60e8f77-9ac3-4f71-9f8e-a044ef481d7f');

        $string = '@pushOnce(\'foo\')
test
@endPushOnce';

        $expected = '<?php if (! $__env->hasRenderedOnce(\'e60e8f77-9ac3-4f71-9f8e-a044ef481d7f\')): $__env->markAsRenderedOnce(\'e60e8f77-9ac3-4f71-9f8e-a044ef481d7f\');
$__env->startPush(\'foo\'); ?>
test
<?php $__env->stopPush(); endif; ?>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }

    public function testPushIfIsCompiled()
    {
        $string = '@pushIf(true, \'foo\')
test
@endPushIf';
        $expected = '<?php if(true): $__env->startPush( \'foo\'); ?>
test
<?php $__env->stopPush(); endif; ?>';

        $this->assertEquals($expected, $this->compiler->compileString($string));
    }
}

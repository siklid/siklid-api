<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Util;

use App\Foundation\Util\Hashtag;
use App\Tests\TestCase;

class HashtagTest extends TestCase
{
    /**
     * @test
     */
    public function extract_with_english_text(): void
    {
        $sut = new Hashtag();

        $actual = $sut->extract('This is a #test and another #test2 and #foo_bar and #FooBar and #foo@bar');

        $this->assertEquals(
            ['#test', '#test2', '#foo_bar', '#FooBar', '#foo'],
            $actual
        );
    }

    /**
     * @test
     */
    public function extract_with_arabic_text(): void
    {
        $sut = new Hashtag();

        $actual = $sut->extract('هذا هو #تجربة و #تجربة2 و #foo_bar و #FooBar و #foo و #كلمة_و_أخرى');

        $this->assertEquals(
            ['#تجربة', '#تجربة2', '#foo_bar', '#FooBar', '#foo', '#كلمة_و_أخرى'],
            $actual
        );
    }

    /**
     * @test
     */
    public function extract_with_french_text(): void
    {
        $sut = new Hashtag();

        $actual = $sut->extract('Ceci est un #test et un autre #test2 et #foo_bar et #FooBar et #foo et #superposés');

        $this->assertEquals(
            ['#test', '#test2', '#foo_bar', '#FooBar', '#foo', '#superposés'],
            $actual
        );
    }

    /**
     * @test
     */
    public function extract_with_german_text(): void
    {
        $sut = new Hashtag();

        $actual = $sut->extract(
            'Dies ist ein #test und ein anderer #test2 und #foo_bar und #FooBar und #foo und #überlappend'
        );

        $this->assertEquals(
            ['#test', '#test2', '#foo_bar', '#FooBar', '#foo', '#überlappend'],
            $actual
        );
    }

    /**
     * @test
     */
    public function extract_with_turkish_text(): void
    {
        $sut = new Hashtag();

        $actual = $sut->extract('Bu bir #test ve başka bir #test2 ve #foo_bar ve #FooBar ve #foo ve #üstüste');

        $this->assertEquals(
            ['#test', '#test2', '#foo_bar', '#FooBar', '#foo', '#üstüste'],
            $actual
        );
    }

    /**
     * @test
     */
    public function extract_with_spanish_text(): void
    {
        $sut = new Hashtag();

        $actual = $sut->extract('Esto es un #test y otro #test2 y #foo_bar y #FooBar y #foo y #superpuestos');

        $this->assertEquals(
            ['#test', '#test2', '#foo_bar', '#FooBar', '#foo', '#superpuestos'],
            $actual
        );
    }
}

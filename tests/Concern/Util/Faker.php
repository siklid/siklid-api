<?php

declare(strict_types=1);

namespace App\Tests\Concern\Util;

use Faker\Factory;
use Faker\Generator;

/**
 * A wrapper for the Faker library.
 *
 * @mixin Generator
 */
final class Faker
{
    /**
     * @var Generator the Faker generator
     */
    private Generator $faker;

    /**
     * @param Generator $faker the Faker generator
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Static constructor.
     *
     * @param string $locale The locale to use
     */
    public static function create(string $locale = Factory::DEFAULT_LOCALE): self
    {
        return new self(Factory::create($locale));
    }

    /**
     * Send calls to the Faker generator.
     *
     * @param string $name      The name of the method to call
     * @param array  $arguments The arguments to pass to the method
     *
     * @return string
     *
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function __call(string $name, array $arguments)
    {
        return $this->faker->$name(...$arguments);
    }

    /**
     * Send access to the Faker generator.
     *
     * @param string $name The property to access
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->faker->$name;
    }

    /**
     * Returns a random markdown string.
     */
    public function markdown(): string
    {
        $text = $this->text();
        $img = $this->imageUrl();
        $link = $this->url();

        $words = explode(' ', $text);
        $randomIndex = array_rand($words);
        $words[$randomIndex] = "[$words[$randomIndex]]($link)";
        $text = implode(' ', $words);

        return "![]($img)\n\n$text";
    }
}

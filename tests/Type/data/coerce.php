<?php declare(strict_types=1);

namespace PslShapeTest;

use Psl\Type;

use function PHPStan\Testing\assertType;

class GeneralTest
{
    /**
     * @param array<mixed> $input
     */
    public function coerceShape(array $input): void
    {
        $specification = Type\shape([
            'name' => Type\string(),
            'age' => Type\int(),
            'location' => Type\optional(Type\shape([
                'city' => Type\string(),
                'state' => Type\string(),
                'country' => Type\string(),
            ])),
        ]);

        $output = $specification->coerce($input);

        assertType('array{name: string, age: int, location?: array{city: string, state: string, country: string}}', $output);
        assertType('array', $input);
    }

	/**
	 * @param array<mixed> $input
	 */
	public function coerceComplex(array $input): void
	{
		$spec = Type\dict(Type\string(), Type\shape([
			'a' => Type\non_empty_string(),
			'b' => Type\non_empty_string(),
		]));
		$output = $spec->coerce($input);
		assertType('array<string, array{a: non-empty-string, b: non-empty-string}>', $output);
		assertType('array', $input);
	}

	public function coerceInt($i): void
	{
		$spec = Type\int();
		$coerced = $spec->coerce($i);
		assertType('int', $coerced);
		assertType('mixed', $i);
	}

	public function coerceWrongShape(): void
	{
		Type\shape();
	}
}

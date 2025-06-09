<?php

namespace App\Http\Response;

abstract readonly class AbstractResponseEvent implements \JsonSerializable
{
    public const MESSAGE = 'unknown error';
    public const CODE = 500;

    final public function __construct(
        public array $context = [],
    ){}

    public function jsonSerialize(): array
    {
        $json = [
            'code' => static::CODE,
            'message' => static::MESSAGE,
        ];

        if (empty($this->context) === false) {
            $json['context'] = $this->context;
        }

        return $json;
    }

    /**
     * @param array<string|int, string|int|float|mixed|null> $context
     */
    public static function create(array $context = []): static
    {
        return new static($context);
    }
}

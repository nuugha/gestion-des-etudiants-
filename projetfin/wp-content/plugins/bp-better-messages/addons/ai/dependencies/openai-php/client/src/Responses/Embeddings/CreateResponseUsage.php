<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 08-April-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BetterMessages\OpenAI\Responses\Embeddings;

final class CreateResponseUsage
{
    private function __construct(
        public readonly int $promptTokens,
        public readonly int $totalTokens,
    ) {
    }

    /**
     * @param  array{prompt_tokens: int, total_tokens: int}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['prompt_tokens'],
            $attributes['total_tokens'],
        );
    }

    /**
     * @return array{prompt_tokens: int, total_tokens: int}
     */
    public function toArray(): array
    {
        return [
            'prompt_tokens' => $this->promptTokens,
            'total_tokens' => $this->totalTokens,
        ];
    }
}

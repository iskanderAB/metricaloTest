<?php
declare(strict_types=1);

namespace App\Http\Request;

use App\Http\Response\AbstractResponseEvent;

final class RequestDecodeException extends \RuntimeException
{
    /**
     * @var list<AbstractResponseEvent>
     */
    private array $events = [];

    /**
     * @param list<AbstractResponseEvent> $events
     */
    public static function fromEvents(array $events, \Throwable $previous = null): self
    {
        $exception = new self(
            message: 'Request decoding error',
            previous: $previous,
        );

        $exception->events = $events;

        return $exception;
    }

    /**
     * @return list<AbstractResponseEvent>
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}

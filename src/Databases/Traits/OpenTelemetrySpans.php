<?php

namespace Stickee\Instrumentation\Databases\Traits;

use OpenTelemetry\API\Trace\SpanKind;
use PlunkettScott\LaravelOpenTelemetry\Otel;
use Stickee\Instrumentation\Spans\OpenTelemetrySpan;
use Stickee\Instrumentation\Spans\SpanInterface;

/**
 * Create OpenTelemetry spans
 */
trait OpenTelemetrySpans
{
    /**
     * Creates a new span wrapping the given callable.
     * If an exception is thrown, the span is ended and the exception is recorded and rethrown.
     *
     * @param string $name The name of the span
     * @param callable $callable A callable that will be executed within the span context. The activated Span will be passed as the first argument.
     * @param int $kind The kind of span to create. Defaults to SpanKind::KIND_INTERNAL
     * @param iterable $attributes Attributes to add to the span. Defaults to an empty array, but can be any iterable.
     *
     * @return mixed The result of the callable
     */
    public function span(string $name, callable $callable, int $kind = SpanKind::KIND_INTERNAL, iterable $attributes = []): mixed
    {
        return Otel::span($name, $callable, $kind, $attributes);
    }

    /**
     * Start a span and scope
     *
     * @param string $name The name of the span
     * @param int $kind The kind of span to create. Defaults to SpanKind::KIND_INTERNAL
     * @param iterable $attributes Attributes to add to the span. Defaults to an empty array, but can be any iterable.
     *
     * @return \Stickee\Instrumentation\Utils\Span
     */
    public function startSpan(string $name, int $kind = SpanKind::KIND_INTERNAL, iterable $attributes = []): SpanInterface
    {
        $span = Otel::tracer()->spanBuilder($name)
            ->setSpanKind($kind)
            ->setAttributes($attributes)
            ->startSpan();

        return new OpenTelemetrySpan($span);
    }
}

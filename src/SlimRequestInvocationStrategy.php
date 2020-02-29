<?php

declare(strict_types=1);

namespace Sihae;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RequestHandlerInvocationStrategyInterface;

final class SlimRequestInvocationStrategy implements RequestHandlerInvocationStrategyInterface
{
    /**
     * @param callable $callable The callable to invoke using the strategy.
     * @param ServerRequestInterface $request The request object.
     * @param ResponseInterface $response The response object.
     * @param array<mixed> $routeArguments The route's placeholder arguments
     *
     * @return ResponseInterface The response from the callable.
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
        // TODO this can be the entire method when everything is a RequestHandlerInterface
        if (is_array($callable)
            && $callable[0] instanceof RequestHandlerInterface
        ) {
            foreach ($routeArguments as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            return $callable($request);
        }

        // marshal route arguments that look like ints into actual ints
        $marshalledArguments = array_reduce(
            $routeArguments,
            static function (array $arguments, string $value): array {
                $arguments[] = ctype_digit($value) ? (int) $value : $value;

                return $arguments;
            },
            []
        );

        return $callable($request, $response, ...$marshalledArguments);
    }
}

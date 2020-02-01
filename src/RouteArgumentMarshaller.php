<?php declare(strict_types=1);

namespace Sihae;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RequestHandlerInvocationStrategyInterface;

final class RouteArgumentMarshaller implements RequestHandlerInvocationStrategyInterface
{
    /**
     * @param callable $callable The callable to invoke using the strategy.
     * @param ServerRequestInterface $request The request object.
     * @param ResponseInterface $response The response object.
     * @param array<mixed> $routeArguments The route's placeholder arguments
     * @return ResponseInterface The response from the callable.
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
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

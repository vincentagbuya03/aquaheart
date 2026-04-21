<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAquaheartActions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->user()) {
            return $response;
        }

        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }

        $routeName = (string) optional($request->route())->getName();
        if ($routeName === '' || !str_starts_with($routeName, 'aquaheart.')) {
            return $response;
        }

        if (str_starts_with($routeName, 'aquaheart.logs.')) {
            return $response;
        }

        if ($response->getStatusCode() >= 400) {
            return $response;
        }

        try {
            [$entityType, $entityId] = $this->resolveEntity($request);
            $action = $this->resolveAction($request->method(), $routeName);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $action,
                'route_name' => $routeName,
                'method' => $request->method(),
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'description' => $this->buildDescription($request, $action, $entityType, $entityId),
                'meta' => [
                    'request' => $request->except([
                        '_token',
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    private function resolveEntity(Request $request): array
    {
        $parameters = optional($request->route())->parameters() ?? [];

        foreach ($parameters as $value) {
            if (is_object($value) && method_exists($value, 'getKey')) {
                return [class_basename($value), (string) $value->getKey()];
            }
        }

        $entityType = $parameters !== [] ? ucfirst((string) array_key_first($parameters)) : null;
        $entityId = $entityType ? (string) reset($parameters) : null;

        return [$entityType, $entityId];
    }

    private function resolveAction(string $method, string $routeName): string
    {
        if (str_contains($routeName, 'payment-status')) {
            return 'status_changed';
        }

        return match ($method) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'updated',
        };
    }

    private function buildDescription(Request $request, string $action, ?string $entityType, ?string $entityId): string
    {
        $actor = (string) ($request->user()->name ?? 'System');
        $target = trim((string) ($entityType ?: 'Record') . ($entityId ? " #{$entityId}" : ''));

        return sprintf('%s %s %s', $actor, str_replace('_', ' ', $action), $target);
    }
}

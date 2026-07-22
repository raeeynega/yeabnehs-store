<?php

namespace App\Http\Middleware;

use App\Services\ImmutableAuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImmutableAuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Log state-changing requests to the immutable chain
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $action = $this->resolveAction($request);

            ImmutableAuditLogger::log(
                action: $action,
                entityType: $this->resolveEntityType($request),
                entityId: $this->resolveEntityId($request),
                oldValues: null,
                newValues: $request->except([
                    'password', 'password_confirmation', '_token',
                    '_method', 'current_password', 'new_password',
                ]),
                riskLevel: $this->resolveRiskLevel($request, $response),
                request: $request,
            );
        }

        return $response;
    }

    private function resolveAction(Request $request): string
    {
        $route = $request->route()?->getName() ?? $request->path();

        return match (true) {
            str_contains($route, 'login')    => 'auth.login',
            str_contains($route, 'register') => 'auth.register',
            str_contains($route, 'logout')   => 'auth.logout',
            str_contains($route, 'cart')     => 'cart.' . ($request->is('*add*') ? 'add' : ($request->is('*remove*') ? 'remove' : 'update')),
            str_contains($route, 'order')    => 'order.create',
            str_contains($route, 'payment')  => 'payment.' . ($request->is('*submit*') ? 'submit' : 'select'),
            str_contains($route, 'booking')  => 'booking.create',
            str_contains($route, 'account')  => 'account.update',
            str_contains($route, 'admin')    => 'admin.' . $this->extractAdminAction($request),
            default                          => $request->method() . '.' . $request->path(),
        };
    }

    private function extractAdminAction(Request $request): string
    {
        $route = $request->route()?->getName() ?? '';
        if (str_contains($route, 'toggle')) return 'toggle_user';
        if (str_contains($route, 'resolve')) return 'resolve_event';
        if (str_contains($route, 'block-ip')) return 'block_ip';
        if (str_contains($route, 'unblock')) return 'unblock_ip';
        return 'action';
    }

    private function resolveEntityType(Request $request): ?string
    {
        $route = $request->route()?->getName() ?? '';

        if (str_contains($route, 'cart'))     return 'Cart';
        if (str_contains($route, 'order'))    return 'Order';
        if (str_contains($route, 'payment'))  return 'Payment';
        if (str_contains($route, 'booking'))  return 'Booking';
        if (str_contains($route, 'account'))  return 'User';
        if (str_contains($route, 'user'))     return 'User';
        if (str_contains($route, 'event'))    return 'SecurityEvent';
        if (str_contains($route, 'blocked'))  return 'BlockedIp';

        return null;
    }

    private function resolveEntityId(Request $request): ?int
    {
        return $request->route('event')?->id
            ?? $request->route('user')?->id
            ?? $request->route('ip')?->id
            ?? null;
    }

    private function resolveRiskLevel(Request $request, Response $response): string
    {
        if ($response->getStatusCode() >= 500) return 'critical';
        if ($response->getStatusCode() >= 400) return 'medium';

        $route = $request->route()?->getName() ?? '';
        if (str_contains($route, 'admin'))   return 'high';
        if (str_contains($route, 'payment')) return 'high';
        if (str_contains($route, 'order'))   return 'medium';
        if (str_contains($route, 'login'))   return 'medium';

        return 'low';
    }
}

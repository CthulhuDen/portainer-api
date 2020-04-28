<?php

namespace CthulhuDen\Portainer;

use CthulhuDen\Portainer\Filter\ContainersFilter;
use CthulhuDen\Portainer\Filter\ServicesFilter;
use CthulhuDen\Portainer\Filter\TasksFilter;
use CthulhuDen\Portainer\Model\Container;
use CthulhuDen\Portainer\Model\Service;
use CthulhuDen\Portainer\Model\Task;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class EndpointScopedClient
{
    use BuildsAndSendsRequests;

    /**
     * @internal
     */
    public function __construct(
        ClientInterface $http,
        RequestFactoryInterface $requestFactory,
        string $apiEndpoint,
        int $endpoint
    ) {
        $this->http = $http;
        $this->requestFactory = $requestFactory;
        $this->endpoint = rtrim($apiEndpoint, '/') . "/api/endpoints/{$endpoint}";
    }

    /**
     * @return Service[]
     * @psalm-return list<Service>
     */
    public function findServices(ServicesFilter $filters): array
    {
        $request = $this->buildRequest('GET', 'docker/services?' . http_build_query([
            'filters' => json_encode($filters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]));

        /** @psalm-var list<array> $data */
        $data = json_decode((string) $this->sendAndExpect2xx($request)->getBody(), true);

        return array_map([Service::class, 'create'], $data);
    }

    /**
     * @return Task[]
     * @psalm-return list<Task>
     */
    public function findTasks(TasksFilter $filters): array
    {
        $request = $this->buildRequest('GET', 'docker/tasks?' . http_build_query([
            'filters' => json_encode($filters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]));

        /** @psalm-var list<array> $data */
        $data = json_decode((string) $this->sendAndExpect2xx($request)->getBody(), true);

        return array_map([Task::class, 'create'], $data);
    }

    /**
     * @return Container[]
     * @psalm-return list<Container>
     */
    public function findContainers(ContainersFilter $filter): array
    {
        $request = $this->buildRequest('GET', 'docker/containers/json?' . http_build_query([
            'filters' => json_encode($filter, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]));

        /** @psalm-var list<array> $data */
        $data = json_decode((string) $this->sendAndExpect2xx($request)->getBody(), true);

        return array_map([Container::class, 'create'], $data);
    }
}

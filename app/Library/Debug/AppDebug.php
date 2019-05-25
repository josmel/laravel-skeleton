<?php

namespace App\Library\Debug;

use Illuminate\Database\Connection;
use Illuminate\Events\Dispatcher as Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Config;

class AppDebug {

    /**
     * @var Collection
     */
    private $queries;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var Collection
     */
    private $debug;

    /**
     * @var bool
     */
    private $collectQueries = false;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Event $event
     * @param Connection $connection
     */
    public function __construct(Event $event, Connection $connection)
    {
        $this->queries = new Collection();
        $this->debug   = new Collection();
        $this->event   = $event;
        $this->connection = $connection;

        $this->event->listen('kernel.handled', function($request, $response)
        {
            $this->updateResponse($request, $response);
        });
    }

    /**
     * Listen database queries events.
     */
    public function collectDatabaseQueries()
    {
        $this->collectQueries = true;
        $this->connection->enableQueryLog();

        $this->connection->listen(function ($event)
        {
            $this->logQuery($event->sql, $event->bindings, $event->time);
        });
    }

    /**
     * Log DB query.
     *
     * @param string $query
     * @param array $attributes
     * @param float $time
     */
    private function logQuery($query, $attributes, $time)
    {
        $query = vsprintf(str_replace(['%', '?'], ['%%', "'%s'"], $query), $attributes) . ';';

        $this->queries->push([
            'query' => $query,
            'time' 	=> $time,
        ]);
    }

    /**
     * Add vars to debug output.
     */
    public function dump()
    {
        if(Config::get('app.library.debug.enable')) {
            foreach (func_get_args() as $var) {
                $this->debug->push($var);
            }
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    private function updateResponse(Request $request, Response $response)
    {
        if ($response instanceof JsonResponse && $this->needToUpdateResponse())
        {
            $data = $response->getData(true);

            if ($this->collectQueries)
            {
                $data['debug']['sql'] = [
                    'total_queries' => $this->queries->count(),
                    'queries' => $this->queries,
                ];
            }

            if ( ! $this->debug->isEmpty())
            {
                $data['debug']['dump'] = $this->debug;
            }

            $response->setData($data);
        }
    }

    /**
     * Check if debugger has to update the response.
     *
     * @return bool
     */
    private function needToUpdateResponse()
    {
        return $this->collectQueries || !$this->debug->isEmpty();
    }
}

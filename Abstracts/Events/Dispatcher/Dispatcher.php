<?php

namespace Apiato\Core\Abstracts\Events\Dispatcher;

use Apiato\Core\Abstracts\Events\Interfaces\ShouldHandle;
use Apiato\Core\Abstracts\Events\Interfaces\ShouldHandleNow;
use Apiato\Core\Abstracts\Events\Jobs\EventJob;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Illuminate\Foundation\Bus\PendingDispatch as JobDispatcher;

/**
 * Created by PhpStorm.
 * User: arthur Devious
 */
class Dispatcher extends EventDispatcher
{
  public function dispatch($event, $payload = [], $halt = false)
  {
    if ($event instanceof ShouldHandle) {

      /* Initialize delay & queue variables */
      $delay = $event->jobDelay;
      $queue = $event->jobQueue;

      /* Create a job & initialize the dispatcher */
      $job = new EventJob($event);
      $dispatcher = (new JobDispatcher($job));

      if (isset($delay) && (is_numeric($delay) ||
          $delay instanceof \DateTimeInterface ||
          $delay instanceof \DateInterval
        )) {
        $dispatcher->delay($delay);
      }

      if (isset($queue) && is_string($queue)) {
        $dispatcher->onQueue($queue);
      }

    } else if ($event instanceof ShouldHandleNow) {
      $event->handle();
    }

    return parent::dispatch($event, $payload, $halt);
  }
}

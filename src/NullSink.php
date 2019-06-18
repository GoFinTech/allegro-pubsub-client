<?php

/*
 * This file is part of the Allegro framework.
 *
 * (c) 2019 Go Financial Technologies, JSC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GoFinTech\Allegro\PubSub;


/**
 * MessageSink that destroys all messages.
 * @package GoFinTech\Allegro\PubSub
 */
class NullSink implements MessageSinkInterface
{

    public function sendMessage($message): void
    {
        // This implementation is intentionally silent (no logs or other side effects)
    }
}

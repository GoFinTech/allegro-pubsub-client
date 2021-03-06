<?php

/*
 * This file is part of the Allegro framework.
 *
 * (c) 2019-2021 Go Financial Technologies, JSC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GoFinTech\Allegro\PubSub;

use Google\Cloud\PubSub\PubSubClient;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Helps building TopicSink's with similar configuration
 * @package GoFinTech\Allegro\PubSub
 */
class TopicSinkFactory
{
    /**
     * @var PubSubClient
     */
    private $client;
    /**
     * @var SerializerInterface|null
     */
    private $serializer;

    public function __construct(?PubSubClient $client = null, ?SerializerInterface $serializer = null)
    {
        $this->client = $client ?? new PubSubClient();
        $this->serializer = $serializer;
    }

    public function createSink(string $topicName): TopicSink
    {
        if (isset($this))
            return new TopicSink($topicName, $this->client, $this->serializer);
        $oneShot = new TopicSinkFactory();
        return $oneShot->createSink($topicName);
    }

    public function __invoke(string $topicName)
    {
        return $this->createSink($topicName);
    }
}

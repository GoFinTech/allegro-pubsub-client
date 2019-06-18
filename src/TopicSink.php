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


use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use InvalidArgumentException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * This sink publishes messages on Google Pub/Sub topic
 * @package GoFinTech\Allegro\PubSub
 */
class TopicSink implements MessageSinkInterface
{
    /**
     * @var Topic
     */
    private $topic;
    /**
     * @var SerializerInterface|null
     */
    private $serializer;

    public function __construct(string $topicName, PubSubClient $client, ?SerializerInterface $serializer = null)
    {
        $this->topic = $client->topic($topicName);
        $this->serializer = $serializer;
    }

    public function sendMessage($message): void
    {
        if ($message instanceof MessageInterface) {
            // Structured mode
            if (is_null($this->serializer))
                throw new InvalidArgumentException(
                    "TopicSink: serializer required but not specified for topic {$this->topic->name()}");
            $data = $this->serializer->serialize($message, 'json');
            $this->topic->publish([
                'data' => $data,
                'attributes' => [
                    'message-type' => $message->getMessageType()
                ]
            ]);
        } else {
            // Ad-hoc mode
            $this->topic->publish(['data' => json_encode($message)]);
        }
    }
}

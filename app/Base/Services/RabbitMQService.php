<?php namespace App\Base\Services;

use App\Base\Models\Shipment;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(config('rabbitmq.host'), config('rabbitmq.port'), config('rabbitmq.user'), config('rabbitmq.pass'));
        $this->channel = $this->connection->channel();
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    protected function setupShipmentRegistrationQueue(string $shippingCompanyCode): array
    {
        $queueName = $exchangeName = 'register_' . $shippingCompanyCode;
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->channel->exchange_declare($exchangeName, AMQPExchangeType::DIRECT, false, true, false, false);
        $this->channel->queue_bind($queueName, $exchangeName);

        return [$queueName, $exchangeName];
    }

    public function publishShipmentRegistrationRequest(Shipment $shipping): bool
    {
        [, $exchangeName] = $this->setupShipmentRegistrationQueue($shipping->company->code);
        $message = new AMQPMessage($shipping->id);
        try {
            $this->channel->basic_publish($message, $exchangeName);
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    public function registerShipmentRegistrationWorker(string $shippingCompanyCode, callable $callback)
    {
        [$queueName] = $this->setupShipmentRegistrationQueue($shippingCompanyCode);
        $this->channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function setupShipmentTrackingQueue(string $shippingCompanyCode)
    {
        $queueName = $exchangeName = 'track_' . $shippingCompanyCode;
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->channel->exchange_declare(
            $exchangeName, 'x-delayed-message', false, true, false, false, false,
            new AMQPTable([
                "x-delayed-type" => AMQPExchangeType::DIRECT,
            ])
        );
        $this->channel->queue_bind($queueName, $exchangeName);
        return [$queueName, $exchangeName];
    }

    public function publishShipmentTrackingRequest(Shipment $shipment, int $delay = null): bool
    {
        $shippingCompanyCode = $shipment->company->code;
        $environment = config('shippi.active_env');

        [, $exchangeName] = $this->setupShipmentTrackingQueue($shippingCompanyCode);

        $delay = $delay ?? config("shippi.$shippingCompanyCode.env.$environment.tracking_delay");
        $message = new AMQPMessage($shipment->id, [
            'delivery_mode' => 2,
            'application_headers' => new AMQPTable([
                'x-delay' => $delay,
            ]),
        ]);

        try {
            $this->channel->basic_publish($message, $exchangeName);
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    public function registerShipmentTrackingWorker(string $shippingCompanyCode, callable $callback)
    {
        [$queueName] = $this->setupShipmentTrackingQueue($shippingCompanyCode);
        $this->channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

}

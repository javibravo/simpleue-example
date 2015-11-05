<?php
/**
 * Created by PhpStorm.
 * User: jbravo
 * Date: 25/05/15
 * Time: 12:18
 */

namespace ConsoleMessage;

use Aws\Sqs\SqsClient;
use SimplePhpQueue\Queue\AwsSqsQueue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SimplePhpQueue\Worker\QueueWorker;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ConsoleMessageWorkerSqsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('console-message:sqs')
            ->setDescription('Show in the terminal messages sent to a redis queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting...</info>');

        $sqsClient = new SqsClient([
            'profile' => 'sqs',
            'region' => 'eu-west-1',
            'version' => 'latest'
        ]);

        $sqsQueue = new AwsSqsQueue($sqsClient, 'test_sqs_javi');

        $logger = new Logger('ConsoleMessage');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/console_message.log', Logger::DEBUG));
        $jsonToCsvWorker = new QueueWorker($sqsQueue, new ConsoleMessageTask($output));
        $jsonToCsvWorker->setLogger($logger);
        $jsonToCsvWorker->start();

        $output->writeln('<info>End.</info>');
    }
}
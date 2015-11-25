<?php
/**
 * Created by PhpStorm.
 * User: jbravo
 * Date: 25/05/15
 * Time: 12:18
 */

namespace ConsoleMessage;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Aws\Sqs\SqsClient;
use Simpleue\Queue\AwsSqsQueue;
use Simpleue\Worker\QueueWorker;

class ConsoleMessageWorkerSqsCommand extends Command
{
    protected function configure()
    {
        $this->setName('console-message:sqs')
            ->setDescription('Show in the terminal messages received from SQS queue')
            ->addOption(
                'queue',
                null,
                InputOption::VALUE_REQUIRED,
                'Queue name',
                'queue-console-message'
            )
            ->addOption(
                'aws-profile',
                null,
                InputOption::VALUE_REQUIRED,
                'AWS profile for credentials.'
            )
            ->addOption(
                'aws-region',
                null,
                InputOption::VALUE_REQUIRED,
                'AWS Region',
                'eu-west-1'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting...</info>');

        $sqsClient = new SqsClient([
            'profile' => $input->getOption('aws-profile'),
            'region' => $input->getOption('aws-region'),
            'version' => 'latest'
        ]);

        $sqsQueue = new AwsSqsQueue($sqsClient, $input->getOption('queue'));

        $logger = new Logger('ConsoleMessage');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/sqs_console_message.log', Logger::DEBUG));
        $consoleMessagesWorker = new QueueWorker($sqsQueue, new ConsoleMessageJob($output));
        $consoleMessagesWorker->setLogger($logger);
        $consoleMessagesWorker->start();

        $output->writeln('<info>End.</info>');
    }
}
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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Predis\Client;
use Simpleue\Queue\RedisQueue;
use Simpleue\Worker\QueueWorker;

class ConsoleMessageWorkerRedisCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('console-message:redis')
            ->setDescription('Show in the terminal messages received redis queue')
            ->addOption(
                'queue',
                null,
                InputOption::VALUE_REQUIRED,
                'Queue name',
                'queue-console-message'
            )
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                'Redis host',
                'localhost'
            )
            ->addOption(
                'port',
                null,
                InputOption::VALUE_REQUIRED,
                'Redis port',
                6379
            )
            ->addOption(
                'database',
                null,
                InputOption::VALUE_REQUIRED,
                'Redis database',
                0
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting...</info>');

        $redisClient = new Client(array(
            'host' => $input->getOption('host'),
            'port' => $input->getOption('port'),
            'database' => $input->getOption('database'),
            'schema' => 'tcp'
        ));
        $redisQueue = new RedisQueue($redisClient, $input->getOption('queue'));

        $logger = new Logger('ConsoleMessage');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/redis_console_message.log', Logger::INFO));
        $consoleMessagesWorker = new QueueWorker($redisQueue, new ConsoleMessageJob($output));
        $consoleMessagesWorker->setLogger($logger);
        $consoleMessagesWorker->start();

        $output->writeln('<info>End.</info>');
    }
}
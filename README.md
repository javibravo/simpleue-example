SimplePHPQueue Example - Colored console messages
=================================================

Example of use [SimplePhpQueue](https://github.com/javibravo/simple-php-queue-example).

Commands which receive a JSON $task from a queue. The task contains a message
and a color, to be printed in the Terminal. A JSON example:

```json
{
    "text" : "message text",
    "color" : "yellow"
}
```

It contains one command for each queue system:

   - **Redis** : console-message:redis
   - **AWS SQS** : console-message:sqs


Install
-------

Install dependencies,

```
composer install
```


Usage
-----

Show command help

```
>$ ./console console-message:redis --help

>$ ./console console-message:sqs --help
```

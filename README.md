SimplePHPQueue Example - Colored console messages
=================================================

Example of use SimplePhpQueue.

Command which receive a JSON $task in a redis queue. The task contains a message
and a color, to be printed in the console. A JSON example:

```json
{
    "text" : "message text",
    "color" : "yellow"
}
```

Usage
-----

The command is called "". Running in the following way you will see the help:

```
./console console-message:redis --help
```

The availabe options are:

- queue: name of the queue to use in redis.
- host: Redis host.
- port: Redis port.
- database: Redis database.
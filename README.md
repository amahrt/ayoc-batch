# AYOC Batch

This batch library provides classes to process data in multiple steps.
It is heavily inspired by Spring Batch.

Features:
  - Job System
  - Multiple different step types.
  - Data Import/Export and Processing

### Installation
This library is designed to be used with composer. You can require it by issuing the following command.

> `php composer.phar require aboutyou/ayoc-batch:~1.0`

### Usage

```php
    $config = [
        'name' => 'Example Job',
        'steps' => [
            [
                '_class' => 'Ayoc\Batch\Step\Step', // Default Step class
                'name' => 'Example Step',
                'batchSize' => 100, // Specify the number of entries to commit at once
                'reader' => [
                    '_class' => 'Foo\Bar\ExampleReader', // Your own Reader implementation
                    'exampleSetting' => 'exampleValue',
                ],
                'processors' => [
                    [
                        '_class' => 'Foo\Bar\ExampleProcessor', // Your own Processor implementation
                        'exampleSetting' => 'exampleValue',
                    ]
                ],
                'writer' => [
                    '_class' => 'Foo\Bar\ExampleWriter', // Your own Writer implementation
                    'exampleSetting' => 'exampleValue',
                ],
            ]
        ],
    ];
    $jobConfig = new Ayoc\Batch\Job\Config($config);
    $launcher = new Ayoc\Batch\Job\Launcher\ResourcelessLauncher(); // If you want you can inject a LoggerInterface from psr/log here.
    $job = $launcher->create($jobConfig);
    $launcher->launch($job);
```

Please see the examples folder on how to implement your own Reader/Writer/Processor.

### TODOs

 - Implement more step classes.
    - Multi Threaded Steps
    - Skipping Steps
    - Retrying Steps
 - Implement more job launchers
    - Job progress aware launchers
    - Database connected launchers

<?php
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/CSVReader.php');
require_once(__DIR__ . '/CSVWriter.php');
require_once(__DIR__ . '/TransformationProcessor.php');

$config = [
    'name' => 'CSV Manipulation',
    'steps' => [
        [
            '_class' => 'Ayoc\Batch\Step\Step', // Default Step class
            'name' => 'Transform CSV file',
            'batchSize' => 100, // Specify the number of entries to commit at once
            'reader' => [
                '_class' => 'CSVReader',
                'filename' => 'unordered_ponies.csv',
                'delimiter' => ','
            ],
            'processors' => [
                [
                    '_class' => 'TransformationProcessor',
                    'dropUnknown' => true,
                    'mapping' => [
                        'content_1' => 'name',
                        'value_1' => 'kind',
                        'value_2' => 'group',
                        'image_link_3' => 'image',
                    ],
                    'static' => [
                        'type' => 'foal'
                    ],
                ]
            ],
            'writer' => [
                '_class' => 'CSVWriter',
                'filename' => 'processed_ponies.csv',
                'delimiter' => ';'
            ],
        ]
    ],
];

$jobConfig = new Ayoc\Batch\Job\Config($config);
$launcher = new Ayoc\Batch\Job\Launcher\ResourcelessLauncher();
$job = $launcher->create($jobConfig);
$launcher->launch($job);

if ($job->getState() == \Ayoc\Batch\Contract\JobInterface::STATE_FAILED) {
    if ($job->getException() != null) {
        echo "Job threw Exception: " . $job->getException()->getMessage() . PHP_EOL;
    }
}
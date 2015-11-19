<?php
/**
 * Ayoc\Batch\Step\AbstractStep
 *
 * * Copyright 2015 ABOUT YOU Open Commerce GmbH
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */

namespace Ayoc\Batch\Step;

use Ayoc\Batch\Contract\ItemProcessorInterface;
use Ayoc\Batch\Contract\ItemReaderInterface;
use Ayoc\Batch\Contract\ItemWriterInterface;
use Ayoc\Batch\Contract\StepInterface;
use Ayoc\Batch\Util\ArrayUtils;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractStep
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Batch\Step
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
abstract class AbstractStep implements StepInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ItemReaderInterface
     */
    protected $reader;

    /**
     * @var ItemWriterInterface
     */
    protected $writer;

    /**
     * @var ItemProcessorInterface[]
     */
    protected $processors;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @throws \Exception
     * @return void
     */
    abstract public function run();

    /**
     * Configures the component with a config array.
     * The component itself should do the necessary steps to read the config.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->name = ArrayUtils::extractAttribute($config, 'name', 'Default Step');
        $readerConfig = ArrayUtils::extractAttribute($config, 'reader');
        $readerClass = ArrayUtils::extractAttribute($readerConfig, '_class');
        /**
         * @var $reader ItemReaderInterface
         */
        $reader = new $readerClass();
        $reader->configure($readerConfig);

        $writerConfig = ArrayUtils::extractAttribute($config, 'writer');
        $writerClass = ArrayUtils::extractAttribute($writerConfig, '_class');
        /**
         * @var $writer ItemWriterInterface
         */
        $writer = new $writerClass();
        $writer->configure($writerConfig);

        $processors = array();
        $processorsConfig = ArrayUtils::extractAttribute($config, 'processors');
        if (is_array($processorsConfig) && $processorsConfig) {
            foreach ($processorsConfig as $pcfg) {
                $pcls = ArrayUtils::extractAttribute($pcfg, '_class');
                /**
                 * @var $p ItemProcessorInterface
                 */
                $p = new $pcls();
                $p->configure($pcfg);
                $processors[] = $p;
            }
        }
        $this->reader = $reader;
        $this->writer = $writer;
        $this->processors = $processors;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}

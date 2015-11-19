<?php
/**
 * Ayoc\Batch\Step\Step
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

use Ayoc\Batch\Step\AbstractStep;
use Ayoc\Batch\Util\ArrayUtils;
use Psr\Log\LoggerAwareInterface;

/**
 * Class Step
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Batch\Step
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class Step extends AbstractStep
{

    /**
     * @var int
     */
    protected $batchSize = 100;

    /**
     * @throws \Exception
     * @return void
     */
    public function run()
    {
        if ($this->logger != null) {
            foreach ($this->processors as $processor) {
                if ($processor instanceof LoggerAwareInterface) {
                    $processor->setLogger($this->logger);
                }
            }
        }
        try {
            $items = array();
            while (($item = $this->reader->read()) != null) {
                foreach ($this->processors as $processor) {
                    $item = $processor->process($item);
                }
                $items[] = $item;
                if (count($items) >= $this->batchSize) {
                    $this->commit($items);
                }
            }
            $this->commit($items);
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @param int $batchSize
     *
     * @return Step
     */
    public function setBatchSize($batchSize)
    {
        $this->batchSize = $batchSize;
        return $this;
    }

    private function commit(&$items)
    {
        if (empty($items))
            return;
        if ($this->logger) $this->logger->info("Writing ".count($items)." items.");
        $this->writer->write($items);
        $items = array();
    }

    /**
     * Configures the component with a config array.
     * The component itself should do the necessary steps to read the config.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        parent::configure($config);
        $this->batchSize = ArrayUtils::extractAttribute($config, 'batchSize', $this->batchSize);
    }
}
 
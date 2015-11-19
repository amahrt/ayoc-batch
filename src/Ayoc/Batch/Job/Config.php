<?php
/**
 * Ayoc\Batch\Job\Config
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

namespace Ayoc\Batch\Job;

use Ayoc\Batch\Contract\ConfigInterface;
use Ayoc\Batch\Contract\ItemProcessorInterface;
use Ayoc\Batch\Contract\ItemReaderInterface;
use Ayoc\Batch\Contract\ItemWriterInterface;
use Ayoc\Batch\Contract\StepInterface;
use Ayoc\Batch\Util\ArrayUtils;

/**
 * Class Config
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Batch\Job
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class Config implements ConfigInterface
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $configHash;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var StepInterface[]
     */
    protected $steps = array();

    public function __construct(array $config) {
        $this->config = $config;
        $this->configHash = sha1(serialize($config));
        $this->init();
    }

    /**
     * Initializes the config.
     * This method will create the steps and their dependencies.
     */
    protected function init()
    {
        $this->name = $this->config['name'];
        foreach ($this->config['steps'] as $stepConfig) {
            $stepClass = ArrayUtils::extractAttribute($stepConfig, '_class');
            /**
             * @var $step StepInterface
             */
            $step = new $stepClass();
            $step->configure($stepConfig);
            $this->steps[] = $step;
        }
    }

    /**
     * @return StepInterface[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getConfigHash()
    {
        return $this->configHash;
    }
}

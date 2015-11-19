<?php
/**
 * Ayoc\Batch\Job\Job
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
use Ayoc\Batch\Contract\JobInterface;
use Ayoc\Batch\Contract\StepInterface;

/**
 * Class Job
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Batch\Job
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class Job implements JobInterface
{

    /**
     * @var int
     */
    protected $state = self::STATE_IDLE;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * Returns the current state of this job.
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the current state of the job.
     * @param int $state
     * @return Job
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get the config.
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the config.
     * @param ConfigInterface $config
     * @return Job
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Exception $e
     * @return JobInterface
     */
    public function setException(\Exception $e)
    {
        $this->exception = $e;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return StepInterface[]
     */
    public function getSteps()
    {
        return $this->config->getSteps();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->config->getName();
    }
}

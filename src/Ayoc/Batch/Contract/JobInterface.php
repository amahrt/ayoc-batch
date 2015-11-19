<?php
/**
 * Ayoc\Emarsys\Import\Contract\JobInterface
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

namespace Ayoc\Batch\Contract;

/**
 * Interface JobInterface
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Emarsys\Import\Contract
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
interface JobInterface
{
    const STATE_IDLE = 0;
    const STATE_STARTED = 1;
    const STATE_RUNNING = 2;
    const STATE_COMPLETED = 4;
    const STATE_FAILED = 8;

    /**
     * Returns the current state of this job.
     * @return int
     */
    public function getState();

    /**
     * Set the current state of the job.
     * @param int $state
     * @return JobInterface
     */
    public function setState($state);

    /**
     * Get the config.
     * @return ConfigInterface
     */
    public function getConfig();

    /**
     * Set the config.
     * @param ConfigInterface $config
     * @return JobInterface
     */
    public function setConfig(ConfigInterface $config);

    /**
     * @param \Exception $e
     * @return JobInterface
     */
    public function setException(\Exception $e);

    /**
     * @return \Exception
     */
    public function getException();

    /**
     * @return StepInterface[]
     */
    public function getSteps();

    /**
     * @return string
     */
    public function getName();
}
 
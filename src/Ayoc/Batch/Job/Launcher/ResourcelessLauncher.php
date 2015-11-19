<?php
/**
 * Ayoc\Batch\Job\Launcher\ResourcelessLauncher
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

namespace Ayoc\Batch\Job\Launcher;

use Ayoc\Batch\Contract\ConfigInterface;
use Ayoc\Batch\Contract\JobInterface;
use Ayoc\Batch\Contract\LauncherInterface;
use Ayoc\Batch\Job\Job;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ResourcelessLauncher
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Batch\Job\Launcher
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class ResourcelessLauncher implements LauncherInterface
{

    /**
     * @var LoggerInterface
     */
    protected $log;

    public function __construct(LoggerInterface $log = null)
    {
        $this->log = $log;
    }

    /**
     * Creates a Job by it's config.
     * @param ConfigInterface $config
     * @return JobInterface
     */
    public function create(ConfigInterface $config)
    {
        $job = new Job();
        $job->setConfig($config);
        return $job;
    }

    /**
     * Launches a Job.
     * @param JobInterface $job
     * @return int
     */
    public function launch(JobInterface $job)
    {
        try {
            if ($this->log) $this->log->info("Executing Job '" . $job->getName() . "'.");
            $job->setState(JobInterface::STATE_STARTED);
            $steps = $job->getSteps();
            $job->setState(JobInterface::STATE_RUNNING);
            foreach ($steps as $step) {
                if ($this->log != null && $step instanceof LoggerAwareInterface) {
                    $step->setLogger($this->log);
                }
                if ($this->log) $this->log->info("Executing Step '" . $step->getName() . "'.");
                $step->run();
            }
            $job->setState(JobInterface::STATE_COMPLETED);
        } catch(\Exception $e) {
            $job->setState(JobInterface::STATE_FAILED);
            $job->setException($e);
        }
    }
}
 
<?php
/**
 * Ayoc\Batch\Reader\AbstractReader
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
 * @author    Christoph Melzer <christoph.melzer@aboutyou.de>
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <christoph.melzer@aboutyou.de>
 */

namespace Ayoc\Batch\Reader;

use Ayoc\Batch\Contract\ItemReaderInterface;

/**
 * Class AbstractReader
 *
 * @author    Christoph Melzer <christoph.melzer@aboutyou.de>
 * @package   Ayoc\Batch\Reader
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <christoph.melzer@aboutyou.de>
 */
abstract class AbstractReader implements ItemReaderInterface
{
    private $data = null;

    /**
     * Reads a piece of input data and advance to the next one.
     * Implementations must return null at the end of the input data set.
     *
     * @return mixed
     */
    public function read()
    {
        if ($this->data === null) {
            $this->initData();
        }

        return array_shift($this->data);
    }

    /**
     * Initializes the data to use in the read function.
     * @return void
     */
    protected function initData()
    {
        if (!is_array($this->data) || $this->data === null) {
            $this->data = $this->readData();
        }
    }

    /**
     * Returns an array to use for the read function.
     * @return array
     */
    abstract protected function readData();
}

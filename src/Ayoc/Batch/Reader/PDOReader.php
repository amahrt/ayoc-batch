<?php
/**
 * Ayoc\Batch\Reader\PDOReader
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

namespace Ayoc\Batch\Reader;

use Ayoc\Batch\Contract\ItemReaderInterface;
use Ayoc\Batch\Util\ArrayUtils;

/**
 * Class PDOReader
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @package   Ayoc\Batch\Reader
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class PDOReader implements ItemReaderInterface
{

    /**
     * @var \PDO
     */
    protected $pdo;
    /**
     * @var \PDOStatement
     */
    protected $stmnt;

    /**
     * @var bool
     */
    protected $ready = false;

    /**
     * Reads a piece of input data and advance to the next one.
     * Implementations must return null at the end of the input data set.
     * @return mixed
     */
    public function read()
    {
        if (!$this->ready) {
            $this->init();
        }
        $data = $this->stmnt->fetch(\PDO::FETCH_ASSOC);
        return $data ? $data : null;
    }

    /**
     * Configures the component with a config array.
     * The component itself should do the necessary steps to read the config.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        $dsn = ArrayUtils::extractAttribute($config, 'dsn');
        $username = ArrayUtils::extractAttribute($config, 'username');
        $password = ArrayUtils::extractAttribute($config, 'password');
        $query = ArrayUtils::extractAttribute($config, 'query');
        $this->pdo = new \PDO($dsn, $username, $password);
        $this->stmnt = $this->pdo->prepare($query);
    }

    protected function init()
    {
        $this->stmnt->execute();
        $this->ready = true;
    }
}
 
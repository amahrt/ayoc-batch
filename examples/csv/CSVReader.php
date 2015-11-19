<?php
/**
 * CSVReader
 *
 * Copyright 2015 ABOUT YOU Open Commerce GmbH
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

/**
 * Class CSVReader
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class CSVReader extends \Ayoc\Batch\Reader\AbstractReader
{

    /**
     * The filename to read from
     * @var string
     */
    protected $filename;

    /**
     * The CSV delimiter
     * @var string
     */
    protected $delimiter;

    /**
     * Returns an array to use for the read function.
     * @return array
     */
    protected function readData()
    {
        $data = [];
        if (file_exists($this->filename) && is_readable($this->filename)) {
            $in = fopen($this->filename, 'r');
            $headers = fgetcsv($in, 0, $this->delimiter);
            while (($line = fgetcsv($in, 0, $this->delimiter)) != null) {
                $row = $this->safeCombine($headers, $line);
                $data[] = $row;
            }
            fclose($in);
        }
        return $data;
    }

    protected function safeCombine($keys, $values) {
        $count = min(count($keys), count($values));
        return array_combine(array_slice($keys, 0, $count), array_slice($values, 0, $count));
    }

    /**
     * Configures the component with a config array.
     * The component itself should do the necessary steps to read the config.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->filename = \Ayoc\Batch\Util\ArrayUtils::extractAttribute($config, 'filename');
        $this->delimiter = \Ayoc\Batch\Util\ArrayUtils::extractAttribute($config, 'delimiter', ';');
    }
}
 
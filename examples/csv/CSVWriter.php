<?php
/**
 * CSVWriter
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
 * Class CSVWriter
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class CSVWriter implements \Ayoc\Batch\Contract\ItemWriterInterface
{

    /**
     * Whether the writer was initialized.
     * @var bool
     */
    protected $initialized = false;

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
     * The output stream
     * @var mixed
     */
    protected $out;

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

    /**
     * Writes the items that are given to a place that it itemWriter specific.
     * @param array $items
     * @return void
     */
    public function write(array $items)
    {
        if (!$this->initialized) {
            $this->init($items);
        }
        foreach ($items as $item) {
            fputcsv($this->out, array_values($item), $this->delimiter);
        }
    }

    /**
     * Initializes the CSVWriter.
     *
     * Provides lazy initialization since it only opens the stream once
     * @param array $items Some sample items to do initialization
     * @throws Exception
     * @return void
     */
    private function init(array $items)
    {
        if (is_writable(dirname($this->filename))) {
            $this->out = fopen($this->filename, 'w');
            $this->writeHeaders($items);
            $this->initialized = true;
        } else {
            throw new Exception($this->filename . ' is not writable.');
        }
    }

    /**
     * Writes CSV headers by using some sample data.
     * @param array $items Some sample items to do initialization
     * @return void
     */
    protected function writeHeaders(array $items)
    {
        if (!count($items)) {
            return;
        }
        $sample = $items[0];
        $headers = array_keys($sample);
        fputcsv($this->out, $headers, $this->delimiter);
    }

    /**
     * Destructs the CSVWriter
     */
    public function __destruct()
    {
        if (!is_null($this->out)) {
            fclose($this->out);
        }
    }

}
 
<?php
/**
 * TransformationProcessor
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
 * Class TransformationProcessor
 *
 * @author    Jochen Niebuhr <jochen.niebuhr@aboutyou.de>
 * @copyright 2015 ABOUT YOU Open Commerce GmbH <jochen.niebuhr@aboutyou.de>
 */
class TransformationProcessor implements \Ayoc\Batch\Contract\ItemProcessorInterface
{

    /**
     * Describes a mapping to use on data.
     * @var array
     */
    protected $mapping;

    /**
     * Describes some static fields to apply to every document.
     * @var array
     */
    protected $static;

    /**
     * If this is {@code true} keys not described in mapping will be dropped.
     * Otherwise those keys would not be changed.
     * @var bool
     */
    protected $dropUnknown;

    /**
     * Configures the component with a config array.
     * The component itself should do the necessary steps to read the config.
     *
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->mapping = \Ayoc\Batch\Util\ArrayUtils::extractAttribute($config, 'mapping', []);
        $this->static = \Ayoc\Batch\Util\ArrayUtils::extractAttribute($config, 'static', []);
        $this->dropUnknown = \Ayoc\Batch\Util\ArrayUtils::extractAttribute($config, 'dropUnknown', false);
    }

    /**
     * Processes the item.
     * @param mixed $item
     * @return mixed
     */
    public function process($item)
    {
        $out = $this->static;
        foreach ($item as $k => $v) {
            if (isset($this->mapping[$k])) {
                $newKey = $this->mapping[$k];
                if ($newKey !== false) {
                    $out[$newKey] = $v;
                }
            } else if (!$this->dropUnknown) {
                $out[$k] = $v;
            }
        }
        return $out;
    }
}
 
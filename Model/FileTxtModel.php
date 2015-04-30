<?php

/**
 * @package DataTreeBundle
 *
 * @author ML
 *
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

namespace Fascinosum\DataTreeBundle\Model;

/**
 * Model for load and parse multilevel list from *.txt file
 */
class FileTxtModel implements MultiListProviderInterface
{
    /**
     * Contain array of parse values
     * @var array
     * @see getData()
     */
    private $contents = null;
    /**
     * Path to file
     * @var string
     */
    private $path = null;
    /**
     * Column separator for file
     * @var string
     */
    private $separator = '|';
    
    /**
     * Constructor
     *
     * @param string $path
     * @param string $separator
     */
    function __construct($path, $separator = '|')
    {
        $this->setPath($path);
        $this->setSeparator($separator);
    }

    /**
     * Create formatted array data from result of parse file (@see parse())
     * 
     * Count of rows in array equals number of items on "0" level
     * Each row indexed node_id and contains info about node
     * ['name'] - contains name of node and
     * ['id'] - contains node_id and
     * ['children'] - contains indexed node_id child nodes if exist
     *
     * @return array $tree
     */
    private function generateTree()
    {
        $tree = [];
        $contents = $this->contents;

        foreach ($contents as $id => $item) {
            if ($item['parent_id'] === 0) {
                $tree[$id]['name'] = $item['name'];
                $tree[$id]['id'] = $id;
                $tree[$id]['children'] = $this->searchChildren($id);
            }
        };

        return $tree;
    }

    /**
     * Return formatted array from data in file
     * 
     * @throws Exception If access error to file
     *
     * @return array
     */
    public function getData()
    {
        if (!is_readable($this->path)) {
            throw new \Exception("File not exist or locked");            
        };

        $this->contents = $this->parse($this->path, $this->separator);

        $tree = $this->generateTree();

        return $this->sortNodes($tree);
    }

    /**
     * Return path to file
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return column separator for file
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Parse contents of file in array
     *
     * @return array $result
     */
    private function parse($path, $separator)
    {
        $contents = file($path);

        $result = [];

        foreach ($contents as $str) {
            $values = explode($separator, $str);
            if (is_array($values)) {
                $result[(int)$values[0]] = [
                    'parent_id' => (int)$values[1],
                    'name' => trim($values[2]),
                ];
            }
        }

        return $result;
    }

    /**
     * Recursive function which searches child nodes
     *
     * Search all child nodes relatively parent node on input and
     * indexed node_id add ['name'] - contains name of child node
     * ['id'] - contains node_id and
     * ['children'] - recursive call self
     *
     * @param int $idParent Primary key of parent node
     *
     * @return array $children
     */
    private function searchChildren($idParent)
    {

        $children = [];
        foreach ($this->contents as $id => $item) {
            if ($idParent == $item['parent_id']) {
                $children[$id]['name'] = $item['name'];
                $children[$id]['id'] = $id;
                $children[$id]['children'] = $this->searchChildren($id);
            }
        };

        return $children;
    }

    /**
     * Check and save path to file
     * 
     * @param string $path
     *
     * @throws Exception If $path not correct
     */
    public function setPath($path)
    {
        if (is_file($path)) {
            $this->path = $path;
        } else {
            throw new \Exception("Bad path to source");            
        }
    }

    /**
     * Check and save column separator for file
     *
     * @param string $separator
     *
     * @throws Exception If $separator not string
     */
    public function setSeparator($separator)
    {
        if (is_string($separator)) {
            $this->separator = $separator;
        } else {
            throw new \Exception("Bad separator");            
        }
    }

    /**
     * Recursive function that sorts nodes tree
     *
     * @param array $tree
     *
     * @return array $tree
     */
    private function sortNodes(array $tree)
    {
        usort($tree, function($a, $b) {
            return strcmp($a['name'],$b['name']);
        });

        foreach ($tree as $key => $branch) {
            if (!is_null($branch['children'])) {
                $tree[$key]['children'] = $this->sortNodes($branch['children']);
            }
        };

        return $tree;
    }
}

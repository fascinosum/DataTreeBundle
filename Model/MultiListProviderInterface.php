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
 * Interface of models of multilevel lists
 */
interface MultiListProviderInterface
{
    /**
     * Return result parse of multilevel list
     */
    public function getData();
}

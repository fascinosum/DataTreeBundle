<?php

/**
 * @package DataTreeBundle
 *
 * @author ML
 *
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

namespace Fascinosum\DataTreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Fascinosum\DataTreeBundle\Model\FileTxtModel;

/**
 * Main controller of bundle
 */
class MainController extends Controller
{
    /**
     * Return JSON Response from data in file
     *
     * @param string $file path to file
     *
     * @throws NotFoundException If is Exception in FileTxtModel
     *
     * @return mixed
     */
    public function getJsonAction($file)
    {
        try {
            $fileModel = new FileTxtModel($file, '|');
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Error');
        };
        
        return new JsonResponse($fileModel->getData());
    }
}

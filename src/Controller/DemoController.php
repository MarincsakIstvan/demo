<?php

namespace App\Controller;

use App\Service\Demo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DemoController
 * @package App\Controller
 */
class DemoController extends Controller
{
    /**
     * @param string $handle1
     * @param string $handle2
     * @param string $mod
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index($handle1, $handle2, $mod = 'mod')
    {
        /** @var Demo $demoService */
        $demoService = $this->container->get('demo');

        return $this->render('demo.html.twig', [
            'result' => $demoService->getResult($handle1, $handle2, $mod),
            'handle1' => $handle1,
            'handle2' => $handle2,
            'mod'     => $mod
        ]);
    }
}
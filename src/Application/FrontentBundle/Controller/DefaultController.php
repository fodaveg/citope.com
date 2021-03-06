<?php

namespace Application\FrontentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction()
    {
        $images = $this->getMediaProcessor()->ServeImages(false, 'boobs');

        return $this->render(':default:home.html.twig', array('images' => $images, 'category' => 'home'));
    }

    /**
     * @Route("/rabo-spinner")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function raboSpinnerAction()
    {
        return $this->render(':default:rabo_spinner.html.twig');
    }

    /**
     * @param $category
     * @Route("/{category}")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($category)
    {
        $images = $this->getMediaProcessor()->ServeImages(false, $category);

        return $this->render(':default:home.html.twig', array('images' => $images, 'category' => $category));
    }

    /**
     * @param $category
     * @Route("/{category}/{width}/{height}")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($category, $width, $height)
    {
        $image = $this->getMediaProcessor()->ProcessImage($category, $width, $height);

        return $this->render(
            ':default:show.html.twig', array('image' => $image, 'width' => $width, 'height' => $height)
        );
    }

    private function getMediaProcessor()
    {
        return $this->container->get('fodaveg_citope.media_processor');
    }

    public function ddpAction()
    {
        $now = time(); // or your date as well
        $thePurgaDay = strtotime($this->getParameter('last_purga_day'));
        $datediff = $now - $thePurgaDay;

        $daysAfterPurga = floor($datediff / (60 * 60 * 24));

        return $this->render(':default:days_after_purga.html.twig', array(
            'days_after_purga' => $daysAfterPurga,
        ));
    }
}

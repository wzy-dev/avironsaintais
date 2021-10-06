<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use App\Entity\Image;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends FOSRestController
{
    /**
     * @Route("/api/images/{page}", requirements={
     *     "page": "\d+"
     * }, name="api_images", defaults={"page" = "1"})
     */
    public function images(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $images=$em->getRepository(Image::class)->findBy(array(), array('id' => 'desc'), 20, ($page-1)*20);
        // $em = $this->getDoctrine()->getManager();
        // $images=$em->getRepository(Image::class)->findBy(array(), array('id' => 'desc'));
        
        if (!$images) {
            throw $this->createNotFoundException('Pas d\'images enregistrées');
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getImage();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($images, 'json');
        return $this->json($jsonContent);
    }

    /**
     * @Route("/api/images/index", name="api_images_index")
     */
    public function imagesIndex()
    {
        $em = $this->getDoctrine()->getManager();
        $images=$em->getRepository(Image::class)->findImagesForIndex();
        
        if (!$images) {
            throw $this->createNotFoundException('Pas d\'images enregistrées');
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getImage();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($images, 'json');
        return $this->json($jsonContent);
    }
}

<?php
namespace App\CacheImage;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\RequestStack;

class CacheImage
{
	protected $controller;
	protected $requestStack;
	protected $cacheManager;
	
	public function __construct($controller, $cacheManager, RequestStack $requestStack)
	{
        $this->controller = $controller;
		$this->cacheManager = $cacheManager;
		$this->requestStack = $requestStack;
    }
	
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if ($entity instanceof Image || $entity instanceof Partenaire) {
			$this->controller->filterAction($this->requestStack->getCurrentRequest(),'uploads/articles/'.$entity->getImage(), 'compression');
			$this->controller->filterAction($this->requestStack->getCurrentRequest(),'uploads/articles/'.$entity->getImage(), 'miniature');
		}
	}
	
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if ($entity instanceof Image || $entity instanceof Partenaire) {
			$this->controller->filterAction($this->requestStack->getCurrentRequest(),'uploads/articles/'.$entity->getImage(), 'compression');
			$this->controller->filterAction($this->requestStack->getCurrentRequest(),'uploads/articles/'.$entity->getImage(), 'miniature');
		}
	}
	
	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if ($entity instanceof Image || $entity instanceof Partenaire) {
			$this->cacheManager->remove('uploads/articles/'.$entity->getImage());
		}
	}
	
	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if ($entity instanceof Image || $entity instanceof Partenaire) {
			$this->cacheManager->remove('uploads/articles/'.$entity->getImage());
		}
	}
}
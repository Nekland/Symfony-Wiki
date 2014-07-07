<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Simple enhanced controller
 */
class Controller extends BaseController
{
    /**
     * Set a flash and automatically translate the message
     *
     * @param $name
     * @param $message
     */
    protected function setFlash($name, $message)
    {
        $this->getSession()
            ->getFlashBag()
            ->add(
                $name,
                $this->getTranslator()->trans($message)
            )
        ;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return $this->get('session');
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * @param  string $route
     * @param  array $parameters
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToRoute($route, array $parameters = [])
    {
        return $this->redirect($this->generateUrl($route, $parameters));
    }

    /**
     * @param $entity
     */
    protected function persistAndFlush($entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush($entity);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}

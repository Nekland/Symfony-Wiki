<?php

namespace Nekland\NekoWiki\Provider;

use Nekland\NekoWiki\Entity\Page;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageProvider
{
    private $em;

    /**
     * @param EntityManager $em
     * @param RequestStack  $requestStack
     */
    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em              = $em;
        $this->defaultLanguage = $requestStack->getCurrentRequest()->getLocale();
    }

    /**
     * @return Nekland\NekoWiki\Entity\Page
     * @throws NotFoundHttpException
     */
    public function getHomepage()
    {
        $page = $this->findPageBySlug('home');

        if ($page === null) {
            throw new NotFoundHttpException('The homepage is not findable. Please read the documentation of NekoWiki about installation.');
        }

        return $page;
    }

    /**
     * @param string $slug
     * @return null|Nekland\NekoWiki\Entity\Page
     */
    public function findPageBySlug($slug)
    {
        $translation = $this->getTranslationRepository()->findOneBy(['titleSlug' => $slug]);
        if ($translation === null) {
            return null;
        }

        return $translation->getTranslatable();
    }

    /**
     * @param string $title
     * @param string $locale
     *
     * @return null|Nekland\NekoWiki\Entity\Page
     */
    public function findPageByTitle($title, $locale = null)
    {
        $criteria = ['title' => $title];

        if (is_string($locale)) {
            $criteria['locale'] = $locale;
        }

        $translation = $this->getTranslationRepository()->findOneBy($criteria);

        if ($translation === null) {
            return null;
        }

        return $translation->getTranslatable();
    }

    /**
     * @param string $title
     * @param string $locale
     *
     * @return null|Nekland\NekoWiki\Entity\Page
     */
    public function searchPage($title, $locale)
    {
        return $this->findPageByTitle($title, $locale);
    }

    public function createPage($title = null)
    {
        $page = new Page();
        $page->setCurrentLocale($this->defaultLanguage);
        $page->setTitle($title);
        $page->mergeNewTranslations();

        return $page;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getTranslationRepository()
    {
        return $this->em->getRepository('NekoWiki:PageTranslation');
    }
}

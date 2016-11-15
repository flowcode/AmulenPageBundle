<?php

namespace Flowcode\PageBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Amulen\PageBundle\Entity\Page;
use Amulen\PageBundle\Repository\BlockRepository;
use Flowcode\DashboardBundle\Entity\Setting;

/**
 * Description of PageService
 *
 * @author Francisco Memoli <fmemoli@flowcode.com.ar>
 */
class PageService
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(ContainerInterface $container, BlockRepository $blockRepository)
    {
        $this->container = $container;
        $this->blockRepository = $blockRepository;
    }

    public function loadSeoForPage(Page $page, $locale)
    {
        $seoPage = $this->container->get('sonata.seo.page');

        /* title */
        $title = $this->blockRepository->findOneBy(array('page' => $page->getId(), 'name' => "title", "lang" => $locale));
        if ($title) {
            $pageTitle = $title->getContent() . " - " . $seoPage->getTitle();
        } else {
            $pageTitle = $page->getName() . " - " . $seoPage->getTitle();
        }
        $seoPage
            ->setTitle($pageTitle);

        /* description */
        $description = $this->blockRepository->findOneBy(array('page' => $page->getId(), 'name' => "description", "lang" => $locale));
        if ($description) {
            $pageDescription = $description->getContent();
        } else {
            $pageDescription = $page->getDescription();
        }
        /* locale */
        $localeOg = $this->blockRepository->findOneBy(array('page' => $page->getId(), 'name' => "locale", "lang" => $locale));
        if ($localeOg) {
            $pagelocale = $localeOg->getContent();
            $seoPage->addMeta('locale', 'og:locale', $pagelocale);
        } else {
            $seoPage->addMeta('locale', 'og:locale', $locale);
        }
        /* type */
        $type = $this->blockRepository->findOneBy(array('page' => $page->getId(), 'name' => "type", "lang" => $locale));
        if ($type) {
            $pagelocale = $type->getContent();
            $seoPage->addMeta('type', 'og:type', $pagelocale);
        }
        /* type */
        $site_name = $this->blockRepository->findOneBy(array('page' => $page->getId(), 'name' => "site_name", "lang" => $locale));
        if ($site_name) {
            $pagelocale = $site_name->getContent();
            $seoPage->addMeta('site_name', 'og:site_name', $pagelocale);
        }

        if (!is_null($page->getImage()) && $page->getImage() != "") {
            $siteUrl = $this->container->get("amulen.dashboard.service.setting")->getValue(Setting::SITE_URL);
            $seoPage->addMeta('image', 'og:image', $siteUrl."/".$page->getImage());
        }
        $seoPage
            ->addMeta('name', 'description', $pageDescription)
            ->addMeta('property', 'og:title', $pageTitle)
            ->addMeta('property', 'og:description', $pageDescription)
        ;
    }
}

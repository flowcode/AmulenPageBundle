<?php

namespace Flowcode\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Amulen\PageBundle\Entity\Page;
use Flowcode\DashboardBundle\Entity\Setting;

/**
 * Page controller.
 *
 * @Route("/{_locale}")
 */
class PageController extends Controller
{
    /**
     * Lists all Page entities.
     *
     * @Route("/{slug}", name="page")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AmulenPageBundle:Page')->findOneBy(array("slug" => $slug));
        if (!$page) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $response = $this->forward('FlowcodePageBundle:Page:show', array(
            'id' => $page->getId(),
            'parameterBag' => $request->query->all(),
        ));
        return $response;
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}", name="page_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, $id, $parameterBag)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AmulenPageBundle:Page')->find($id);
        $entity->setViewCount($entity->getViewCount() + 1);
        $em->flush();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $locale = $request->getLocale();
        $seoPage = $this->container->get('sonata.seo.page');

        /* title */
        $title = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "title", "lang" => $locale));
        if ($title) {
            $pageTitle = $title->getContent() . " - " . $seoPage->getTitle();
        } else {
            $pageTitle = $entity->getName() . " - " . $seoPage->getTitle();
        }
        $seoPage
            ->setTitle($pageTitle);

        /* description */
        $description = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "description", "lang" => $locale));
        if ($description) {
            $pageDescription = $description->getContent();
        } else {
            $pageDescription = $entity->getDescription();
        }
        /* locale */
        $localeOg = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "locale", "lang" => $locale));
        if ($localeOg) {
            $pagelocale = $localeOg->getContent();
            $seoPage->addMeta('locale', 'og:locale', $pagelocale);
        } else {
            $seoPage->addMeta('locale', 'og:locale', $locale);
        }
        /* type */
        $type = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "type", "lang" => $locale));
        if ($type) {
            $pagelocale = $type->getContent();
            $seoPage->addMeta('type', 'og:type', $pagelocale);
        }
        /* type */
        $site_name = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "site_name", "lang" => $locale));
        if ($site_name) {
            $pagelocale = $site_name->getContent();
            $seoPage->addMeta('site_name', 'og:site_name', $pagelocale);
        }

        if (!is_null($entity->getImage()) && $entity->getImage() != "") {
            $siteUrl = $this->get("amulen.dashboard.service.setting")->getValue(Setting::SITE_URL);
            $seoPage->addMeta('property', 'og:image', str_replace(" ", "%20", $siteUrl . "/" . $entity->getImage()));
            $seoPage->addMeta('property', 'twitter:image', str_replace(" ", "%20", $siteUrl . "/" . $entity->getImage()));
        }
        $seoPage->addMeta('property', 'twitter:title', $pageTitle);
        $seoPage->addMeta('property', 'twitter:description', $pageDescription);

        $seoPage
            ->addMeta('name', 'description', $pageDescription)
            ->addMeta('property', 'og:title', $pageTitle)
            ->addMeta('property', 'og:description', $pageDescription);
        return $this->render($entity->getTemplate(), array('page' => $entity, 'parameterBag' => $parameterBag));
    }

    /**
     * Find by .
     *
     * @Route("/bycategory/{category_name}", name="page_page_list")
     * @Method("GET")
     * @Template()
     */
    public function findByCategoryAction(Request $request, $category_name, $pageId = null)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AmulenClassificationBundle:Category')->findOneBy(array("name" => $category_name));

        $pages = $em->getRepository('AmulenPageBundle:Page')->findByCategory($category->getId(), $pageId);

        return array(
            "pages" => $pages
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/block/{pageId}/{blockName}", name="page_block_show")
     * @Method("GET")
     * @Template()
     */
    public function blockAction(Request $request, $pageId, $blockName)
    {
        $em = $this->getDoctrine()->getManager();

        /* get current locale */
        $locale = $request->getLocale();
        
        $entity = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $pageId, 'name' => $blockName, "lang" => $locale));
        $content = "";
        if ($entity) {
            $content = $entity->getContent();
        }
        return array(
            'content' => $content
        );
    }
}

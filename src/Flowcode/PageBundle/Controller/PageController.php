<?php

namespace Flowcode\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Amulen\PageBundle\Entity\Page;

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
            return $this->redirect($this->generateUrl('home'));
        }

        $response = $this->forward('FlowcodePageBundle:Page:show', array(
            'id' => $page->getId(),
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
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AmulenPageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $locale = $request->getLocale();
        $seoPage = $this->container->get('sonata.seo.page');

        /* title */
        $title = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "title", "lang" => $locale));
        if($title){
            $pageTitle = $title->getContent() . " - " . $seoPage->getTitle();
        }else{
            $pageTitle = $entity->getName() . " - " . $seoPage->getTitle();
        }

        /* description */
        $description = $em->getRepository('AmulenPageBundle:Block')->findOneBy(array('page' => $id, 'name' => "description", "lang" => $locale));
        if($description){
            $pageDescription = $description->getContent();
        }else{
            $pageDescription = $entity->getDescription();
        }

        $seoPage
            ->setTitle($pageTitle)
            ->addMeta('name', 'description', $pageDescription)
            ->addMeta('property', 'og:title', $pageTitle)
            ->addMeta('property', 'og:description', $pageDescription)
        ;

        return $this->render($entity->getTemplate(), array('page' => $entity));
    }

    /**
    * Find by .
    *
    * @Route("/bycategory/{category_name}", name="page_page_list")
    * @Method("GET")
    * @Template()
    */
    public function findByCategoryAction(Request $request, $category_name)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AmulenClassificationBundle:Category')->findOneBy(array("name" => $category_name));
        $pages = $em->getRepository('AmulenPageBundle:Page')->findBy(array("category" => $category), array("position" => "ASC"));

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
        if($entity){
            $content = $entity->getContent();
        }
        return array(
            'content' => $content
        );
    }
}

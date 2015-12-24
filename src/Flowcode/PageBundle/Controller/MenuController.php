<?php

namespace Flowcode\PageBundle\Controller;

use Amulen\PageBundle\Entity\MenuItem;
use Amulen\PageBundle\Entity\Menu;
use Flowcode\PageBundle\Form\MenuItemType;
use Flowcode\PageBundle\Form\MenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 */
class MenuController extends Controller
{
    /**
     * Show menu
     *
     * @Route("/{id}/items", name="menu_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Request $request, Menu $menu)
    {
        $em = $this->getDoctrine()->getManager();

        $itemRoot = $em->getRepository('AmulenPageBundle:MenuItem')->findOneBy(array('menu' => $menu, 'isRoot'=> true));
        $menuItems = $em->getRepository('AmulenPageBundle:MenuItem')->childrenHierarchy($itemRoot);
        $locale = $request->getLocale();
        $this->updateLinks($menuItems, $locale);

        return array(
            'extra_class' => 'nav navbar-nav navbar-right',
            'menuitems' => $menuItems,
        );
    }

    /**
     * Show menu
     *
     * @Route("/{name}/getitems", name="menu_by_permalink")
     * @Method("GET")
     * @Template("FlowcodePageBundle:Menu:show.html.twig")
     */
    public function byPermalinkAction(Request $request, $name)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $em->getRepository("AmulenPageBundle:Menu")->findOneBy(array("name" => $name));

        $itemRoot = $em->getRepository('AmulenPageBundle:MenuItem')->findOneBy(array('menu' => $menu, 'isRoot'=> true));
        $menuItems = $em->getRepository('AmulenPageBundle:MenuItem')->childrenHierarchy($itemRoot);
        $locale = $request->getLocale();
        $this->updateLinks($menuItems, $locale);

        return array(
            'extra_class' => 'nav navbar-nav navbar-right',
            'menuitems' => $menuItems,
        );
    }

    private function updateLinks(&$items, $locale){
        $em = $this->getDoctrine()->getManager();
        foreach ($items as &$itemArr) {
            $menuItem = $em->getRepository('AmulenPageBundle:MenuItem')->find($itemArr['id']);
            $menuItemLabel = $em->getRepository('AmulenPageBundle:MenuItemLabel')->findOneBy(array("menuItem" => $itemArr['id'], "lang" => $locale));
            if($menuItem->getPage()){
                $page = $menuItem->getPage();
                $itemArr['pageSlug'] = $page->getSlug();
                if($menuItemLabel){
                    $itemArr['label'] = $menuItemLabel->getContent();
                }else {
                    $itemArr['label'] = $menuItem->getName();
                }
            }
            $this->updateLinks($itemArr['__children'], $locale);
        }
    }
}

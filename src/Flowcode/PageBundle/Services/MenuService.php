<?php

namespace Flowcode\PageBundle\Services;


use Amulen\PageBundle\Entity\Menu;

/**
 * Class MenuService
 * @package Flowcode\PageBundle\Services
 */
class MenuService
{
    private $menuRepository;
    private $menuItemRepository;

    public function __construct($menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }



    /**
     * Reorder Menu tree.
     *
     * @param Menu $menu
     * @param string $field
     * @return bool
     */
    public function reorder(Menu $menu, $field = 'position')
    {
        $roots = $this->menuItemRepository->getRootNodes();
        $rootItem = null;

        foreach ($roots as $root) {
            if ($root->getMenu() == $menu) {
                $rootItem = $root;
            }
        }

        if ($rootItem) {
            $this->menuItemRepository->reorder($rootItem, $field);
            return true;
        }

        return false;
    }

}
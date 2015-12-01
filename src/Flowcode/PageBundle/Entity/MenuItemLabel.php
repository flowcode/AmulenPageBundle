<?php

namespace Flowcode\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * MenuItemLabel
 */
class MenuItemLabel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="MenuItem", inversedBy="labels")
     * @JoinColumn(name="menu_item_id", referencedColumnName="id")
     * */
    protected $menuItem;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    protected $content;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=10)
     */
    protected $lang;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return MenuItemLabel
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return MenuItemLabel
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set menuItem
     *
     * @param \Amulen\PageBundle\Entity\MenuItem $menuItem
     * @return Block
     */
    public function setMenuItem(\Amulen\PageBundle\Entity\MenuItem $menuItem = null) {
        $this->menuItem = $menuItem;

        return $this;
    }

    /**
     * Get menuItem
     *
     * @return \Amulen\PageBundle\Entity\MenuItem
     */
    public function getMenuItem() {
        return $this->menuItem;
    }

}

<?php

namespace CoreShop\Component\Resource\Pimcore\Model;

use CoreShop\Component\Resource\Model\ResourceInterface;
use Pimcore\Model\Element\ElementInterface;

interface PimcoreModelInterface extends ResourceInterface, ElementInterface
{
    /**
     * @param $key
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param bool $published
     */
    public function setPublished($published);

    /**
     * @return bool
     */
    public function getPublished();

    /**
     * @param ElementInterface $parent
     */
    public function setParent($parent);

    /**
     * @return ElementInterface
     */
    public function getParent();

    /**
     * @return mixed
     */
    public function save();

    /**
     * @return mixed
     */
    public function delete();
}

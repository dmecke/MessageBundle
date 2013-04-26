<?php

namespace Cunningsoft\MessageBundle\Entity;

interface UserInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getUsername();
}

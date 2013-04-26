<?php

namespace Cunningsoft\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserInterface")
     */
    private $sender;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserInterface")
     */
    private $receiver;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isRead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $insertDate;

    /**
     * @return UserInterface
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return \DateTime
     */
    public function getInsertDate()
    {
        return $this->insertDate;
    }

    /**
     * @return boolean
     */
    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return UserInterface
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param boolean $isRead
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * @param UserInterface $sender
     */
    public function setSender(UserInterface $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param UserInterface $receiver
     */
    public function setReceiver(UserInterface $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param \DateTime $insertDate
     */
    public function setInsertDate($insertDate)
    {
        $this->insertDate = $insertDate;
    }
}

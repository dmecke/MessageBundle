<?php

namespace Cunningsoft\MessageBundle\Controller;

use Cunningsoft\MessageBundle\CunningsoftMessageEvents;
use Cunningsoft\MessageBundle\Entity\Message;
use Cunningsoft\MessageBundle\Entity\UserInterface;
use Cunningsoft\MessageBundle\Event\MessageEvent;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/message")
 */
class MessageController extends Controller
{
    /**
     * @param int $id
     *
     * @return UserInterface
     */
    public function findUser($id)
    {
        throw new \Exception('This method must be overriden in your controller!');
    }

    /**
     * @return UserInterface
     */
    public function findAllUsers()
    {
        throw new \Exception('This method must be overriden in your controller!');
    }

    /**
     * @return array
     *
     * @Route("", name="cunningsoft_message_list")
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     * @Template
     */
    public function listAction()
    {
        return array(
            'messages' => $this->get('doctrine.orm.entity_manager')->getRepository('CunningsoftMessageBundle:Message')->findBy(array('receiver' => $this->getUser()), array('insertDate' => 'DESC')),
        );
    }

    /**
     * @return array
     *
     * @Route("/send", name="cunningsoft_message_send")
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     * @Template
     */
    public function sendAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $message = new Message();
            $message->setSender($this->getUser());
            $message->setReceiver($this->findUser($request->get('receiver')));
            $message->setSubject($request->get('subject'));
            $message->setContent($request->get('content'));
            $message->setInsertDate(new \DateTime());
            $message->setIsRead(false);
            $this->get('doctrine.orm.entity_manager')->persist($message);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->get('event_dispatcher')->dispatch(CunningsoftMessageEvents::MESSAGE_SENT, new MessageEvent($message));
            $this->container->get('session')->setFlash('cunningsoft_message_success', 'message.flash.sent');

            return $this->redirect($this->generateURL('cunningsoft_message_list'));
        }

        return array(
            'users' => $this->findAllUsers(),
        );
    }

    /**
     * @param Message $message
     *
     * @return array
     *
     * @throws AccessDeniedException
     *
     * @Route("/{id}", name="cunningsoft_message_show")
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     * @Template
     */
    public function showAction(Message $message)
    {
        if (!$this->getUser()->getId() == $message->getReceiver()->getId()) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $message->setIsRead(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return array(
            'message' => $message,
        );
    }

    /**
     * @param Message $message
     *
     * @return array
     *
     * @throws AccessDeniedException
     *
     * @Route("/{id}/markRead", name="cunningsoft_message_markRead")
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function markReadAction(Message $message)
    {
        if (!$this->getUser()->getId() == $message->getReceiver()->getId()) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $message->setIsRead(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->redirect($this->generateURL('cunningsoft_message_list'));
    }

    /**
     * @param Message $message
     *
     * @return array
     *
     * @throws AccessDeniedException
     *
     * @Route("/{id}/markUnread", name="cunningsoft_message_markUnread")
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function markUnreadAction(Message $message)
    {
        if (!$this->getUser()->getId() == $message->getReceiver()->getId()) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $message->setIsRead(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->redirect($this->generateURL('cunningsoft_message_list'));
    }
}

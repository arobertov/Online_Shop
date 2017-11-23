<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 21.11.2017 г.
 * Time: 9:12
 */

namespace BlogBundle\Services;


use BlogBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;


class SendEmailService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $adminEmail;

    /**
     * @var EngineInterface
     */
    private $template;

    /**
     * SendEmailService constructor.
     * @param \Swift_Mailer $mailer
     * @param $adminEmail
     */
    public function __construct(\Swift_Mailer $mailer,$adminEmail,EngineInterface $template)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->template = $template;
    }

    public function verifyRegistrationEmail(User $user){
        $message = (new \Swift_Message('New user registration'))
            ->setFrom($this->adminEmail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->template->render('@basic/Emails/registration_email.html.twig',[
                  'user'=>$user
                ]),
                'text/html'
            );
        $this->mailer->send($message);
        return true;
    }


}
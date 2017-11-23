<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 23.11.2017 г.
 * Time: 15:58
 */

namespace BlogBundle\Services;


use BlogBundle\Entity\ForgotPassword;
use BlogBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var SendEmailService
     */
    private $sendEmailService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserService constructor.
     * @param SendEmailService $sendEmailService
     * @param EntityManagerInterface $em
     */
    public function __construct(SendEmailService $sendEmailService, EntityManagerInterface $em)
    {
        $this->sendEmailService = $sendEmailService;
        $this->em = $em;
    }


    public function forgotPassword(ForgotPassword $validate)
    {
        if (isset($validate)) {
            $username = $validate->getUsername();
            $email = $validate->getEmail();
        }
        $result = $this->em->getRepository(User::class)->findOneBy(array('username' => $username));

        if ($result === null) {
            die('username ( ' . $username . ' ) not found');
        }

        if ($email !== $result->getEmail()) {
            die('email ' . $email . ' not found');
        }

        $randomPassword = substr($result->getPassword(), 8, 8);

    }
}
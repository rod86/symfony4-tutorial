<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Mailer\Mailer;

class MailerTest extends TestCase
{
    public function testConfirmationEmail()
    {
        $user = new User;
        $user->setEmail('john_doe@example.org');

        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $swiftMailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($subject) {
                $messageStr = (string)$subject;
                return strpos($messageStr, "From: me@example.org") !== false
                    && strpos($messageStr, "Content-Type: text/html; charset=utf-8") !== false
                    && strpos($messageStr, "Subject: Welcome to micropost app") !== false
                    && strpos($messageStr, "To: john_doe@example.org") !== false
                    && strpos($messageStr, "This is a message body") !== false;
            }));

        $twigMock = $this->getMockBuilder(\Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twigMock->expects($this->once())
            ->method('render')
            ->with('email/registration.html.twig', ['user' => $user])
            ->willReturn('This is a message body');

        $mailer = new Mailer($swiftMailer, $twigMock, 'me@example.org');
        $mailer->sendConfirmationEmail($user);
    }
}
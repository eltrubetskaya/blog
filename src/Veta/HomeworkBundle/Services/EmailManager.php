<?php

namespace Veta\HomeworkBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailManager
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * EmailManager constructor.
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templating
     * @param ObjectManager $manager
     * @param ContainerInterface $container
     */
    public function __construct($mailer, EngineInterface $templating, ObjectManager $manager, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * @param null $username
     */
    public function sendDistributionEmailMessage($username = null)
    {
        $posts = $this->manager->getRepository('VetaHomeworkBundle:Post')->findMostLikes(5);

        if (!$posts) {
            throw new Exception('No Posts to send!!!');
        }

        if ($username === null) {
            $users = $this->manager->getRepository('VetaHomeworkBundle:User')->findAll();
            foreach ($users as $user) {
                $rendered = $this->templating->render('VetaHomeworkBundle:Email:distribution.html.twig', [
                    'user' => $user,
                    'posts' => $posts,
                ]);
                $this->sendEmailMessage($rendered, 'noreply@example.com', $user->getEmail());
            }
            printf("Send email of the distribution %s users!\n", count($users));
        } else {
            $user = $this->manager->getRepository('VetaHomeworkBundle:User')->findOneBy(['username' => $username]);

            if (!$user) {
                throw new Exception('Failed to find User to send!!!');
            }

            $rendered = $this->templating->render('VetaHomeworkBundle:Email:distribution.html.twig', [
                'user' => $user,
                'posts' => $posts,
            ]);
            $this->sendEmailMessage($rendered, $this->container->getParameter('mailer_user'), $user->getEmail());
            printf("Send email of the distribution 1 user!\n");
        }
    }

    /**
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = array_shift($renderedLines);
        $body = implode("\n", $renderedLines);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body)
            ->setContentType('text/html')
        ;

        $this->mailer->send($message);
    }
}

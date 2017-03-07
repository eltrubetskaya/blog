<?php
namespace Veta\HomeworkBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailDistributionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('veta_homework:send-email-distribution')
            ->setDescription('Send Email Distribution')
            ->setHelp('Send Email Distribution 5 popular posts for today.')
            ->addArgument('username', InputArgument::OPTIONAL, 'Whom send email of the Distribution.', "all users")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
        '<comment>Send Email Distribution</comment>',
        '<comment>=======================</comment>',
        '',
        ]);

        $userManager = $this->getContainer()->get('veta_homework.services.email_manager');
        $userManager->sendDistributionEmailMessage($input->getArgument('username'));

        $output->writeln([
        '',
        '<info>Distribution successfully sending!</info>',
        ]);
    }
}

<?php
namespace App\Command;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateClientCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:create-client')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new client.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a client...')

            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the client.')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the client.')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password of the client.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Client Creator');

        $input->setArgument('name', $io->ask('Name'));
        $input->setArgument('username', $io->ask('Username'));
        $input->setArgument('password', $io->ask('Password'));

        if ($io->confirm('Confirm creation?', true)) {
            $client = new Client();

            $client->setName($input->getArgument('name'));
            $client->setUsername($input->getArgument('username'));
            $client->setPassword($input->getArgument('password'));

            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($client);
            $em->flush();

            $io->success('Client successfully generated!');
        }
    }
}

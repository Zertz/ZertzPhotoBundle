<?php
namespace Zertz\PhotoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PhotoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zertz:photo:generate')
            ->setDescription('Generate photos in a defined format')
            ->addArgument('format', InputArgument::OPTIONAL, 'Which format do you want to generate?')
            ->addOption('all', null, InputOption::VALUE_NONE, 'If set, all missing photo formats will be generated')
            ->addOption('force', null, InputOption::VALUE_NONE, 'If set, photos will be generated even if they already exist')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = false;
        if ($input->getOption('force')) {
            $force = true;
        }
        
        $format = $input->getArgument('format');
        
        if ($input->getOption('all')) {
            $output->writeln('Generating photos of <info>all</info> formats...');
        } else if ($format) {
            $output->writeln('Generating <info>'. $format . '</info> photos...');
        } else {
            $output->writeln('<error>A format must be specified or --all</error>');
        }
    }
}

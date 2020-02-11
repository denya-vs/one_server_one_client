<?php

namespace App\Command;

use App\Entity\Disease;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetSadPatientsCommand extends Command
{
    protected static $defaultName = 'app:get-sad-patients';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Calculate % of patients who did not receive a prescription on time    ')
            ->addArgument('execution_time', InputArgument::OPTIONAL, 'Custom execution time in seconds (default 300)')
            ->addOption('timeout', 't',InputOption::VALUE_OPTIONAL, 'Custom timeout in seconds (default 1)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $countGoodResponse = $countBadResponse = 0;
        $executionTime = $input->getArgument('execution_time') ?? 300;
        $timeout = $input->getOption('timeout') ?? 1;
        $repo = $this->em->getRepository(Disease::class);

        $io = new SymfonyStyle($input, $output);
        $client = HttpClient::create();
        $time = time();
        $io->note(sprintf('Start time: %s', date('m/d/Y H:i:s', $time)));

        while (true) {
            $disease = $repo->createQueryBuilder('di')
                ->addSelect('RAND() as HIDDEN rand')
                ->orderBy('rand')
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();
            try {
                $client->request('GET', 'http://nginx/drug/' . $disease->getId(), ['timeout' => $timeout]);
                $countGoodResponse++;
            } catch (TransportExceptionInterface $e) {
                $countBadResponse++;
            }
            if ((time() - $time) >= $executionTime) {
                $io->note(sprintf('End time: %s', date('m/d/Y H:i:s', time())));
                break;
            }
        }

        $result = $countBadResponse / ($countBadResponse + $countGoodResponse) * 100;
        $io->success($result . '% пациентов, своевременно не получили рецепт');

        return 0;
    }
}

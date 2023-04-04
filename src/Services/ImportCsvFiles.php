<?php

namespace App\Services;



use AllowDynamicProperties;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AllowDynamicProperties] class ImportCsvFiles extends User
{
    private $repUser;
    CONST BATCH_SIZE = 500;
    public function __construct(EntityManager $em)

    {
        parent::__construct();
        $this->em = $em;
        $this->repUser = $this->em->getRepository('App:User');


    }
    public function importCSV(array $data, ProgressBar $progress, OutputInterface $output)
    {
        $i = 1;

        $stopwatch = new Stopwatch();
        $stopwatch->start('import');

        foreach ($data as $row) {

            if (($i % self::BATCH_SIZE) === 0) {

                $event = $stopwatch->lap('import');
                $progress->advance(self::BATCH_SIZE);
                $output->writeln(' of users imported ... | - Memory : '
                    .number_format($event->getMemory() / 1048576, 2)
                    . ' MB - Time : '
                    . number_format($event->getDuration() / 1000, 2) .' seconds');
            }
            $stopwatch->stop('import');
        }
    }
    public function addOptions($options = ['logQueries' => false])
    {
        // Turning off doctrine default logs queries for saving memory
        if (isset($options['logQueries']) && $options['logQueries'] === false) {
            $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        }
    }
    private function generateFileName($inputFileName,$partNumber)
    {
        return pathinfo($inputFileName, PATHINFO_FILENAME).'_part_'.$partNumber.'.csv';
    }

    private function generateOutputFilePath($outputFolder,$inputFileName,$partNumber)
    {
        $outputFileName = $this->generateFileName($inputFileName,$partNumber);
        return $outputFolder.$outputFileName;
    }

}
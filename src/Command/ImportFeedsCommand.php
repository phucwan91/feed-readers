<?php

namespace App\Command;

use App\Services\FeedsImporter;
use App\Services\FeedXmlParser;
use App\Services\LoggerFactory;
use Exception;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportFeedsCommand extends Command
{
    private const DEFAULT_CATEGORY = 'Uncategorized';

    protected static $defaultName = 'app:import-feeds';

    /**
     * @var FeedXmlParser
     */
    private $feedXmlParser;

    /**
     * @var FeedsImporter
     */
    private $feedsImporter;

    /**
     * @var LoggerFactory
     */
    private $loggerFactory;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * ImportFeedsCommand constructor.
     *
     * @param FeedXmlParser $feedXmlParser
     * @param FeedsImporter $feedsImporter
     * @param LoggerFactory $loggerFactory
     */
    public function __construct(
        FeedXmlParser $feedXmlParser,
        FeedsImporter $feedsImporter,
        LoggerFactory $loggerFactory
    ) {
        parent::__construct();

        $this->feedXmlParser = $feedXmlParser;
        $this->feedsImporter = $feedsImporter;
        $this->loggerFactory = $loggerFactory;
    }


    protected function configure()
    {
        $this
            ->setDescription('Import feeds from urls to xml files separated by commas')
            ->setHelp('This command allows you to import feeds from urls separated by commas')
            ->addArgument('urls', InputArgument::REQUIRED, 'URLs of feeds, separated by commas')
            ->addArgument('log-filepath', InputArgument::REQUIRED, 'Path to the log file, make sure it is writable')
            ->addArgument('default-category-name', InputArgument::OPTIONAL, 'Default category name in case category is not found in xml feed list', self::DEFAULT_CATEGORY);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $urls                = $input->getArgument('urls');
        $logFilepath         = $input->getArgument('log-filepath');
        $defaultCategoryName = $input->getArgument('default-category-name');

        $output->writeln([
            'Import Feeds',
            'urls: '.$urls,
            'log filepath: '.$logFilepath,
        ]);

        try {
            $logger = $this->loggerFactory->create('import-feeds', $logFilepath);
            $logger->info('Start importing feeds from urls command');
        } catch (Exception $e) {
            $output->writeln([
                'Could not create log file with filepath: '.$logFilepath,
                'Please make sure the path is writable.',
            ]);

            return;
        }

        $this->setOutput($output);
        $this->setLogger($logger);

        foreach (explode(',', $urls) as $url) {
            $output->writeln('------------------------------');

            $this->outputAndLog('Start getting xml from url: '.$url);

            $xml = $this->feedXmlParser->getXmlFromUrl($url);

            if (!$xml) {
                $this->outputAndLog('Could not get xml from url: '.$url);

                continue;
            }

            $this->outputAndLog('Start parsing xml');

            $feedCategoryName = $this->feedXmlParser->getFeedCategoryNameFromXml($xml);

            $this->outputAndLog(sprintf('Got category "%s"', $feedCategoryName));

            if (!$feedCategoryName) {
                $feedCategoryName = $defaultCategoryName;
            }

            $feedsData = $this->feedXmlParser->getFeedsDataFromXml($xml);
            $totalFeeds = count($feedsData);

            $this->outputAndLog(sprintf('Got "%d" feed(s)', $totalFeeds));
            $this->outputAndLog('Start importing feeds to database');

            try {
                $this->feedsImporter->import($feedCategoryName, $feedsData);
            } catch (Exception $exception) {
                $this->outputAndLog('Could not insert feeds into database.'.$exception->getMessage(), Logger::ERROR);

                continue;
            }

            $this->outputAndLog(sprintf('Completed importing %d feed(s) into database.', $totalFeeds));
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param $message
     * @param int $logLevel
     */
    private function outputAndLog($message, int $logLevel = Logger::INFO)
    {
        $this->output->writeln($message);
        $this->logger->log($logLevel, $message);
    }
}

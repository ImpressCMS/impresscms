<?php

declare(strict_types=1);

namespace App\Command;

use App\Routing\RouteOwnership;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

#[AsCommand(name: 'icms:routes:ownership', description: 'Show whether Symfony or legacy ImpressCMS handles a URL')]
final class RoutesOwnershipCommand extends Command
{
    public function __construct(private readonly RouteOwnership $ownership)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'Path with optional query string, for example "/modules/system/admin.php?fct=users"')
            ->addOption('method', 'm', InputOption::VALUE_REQUIRED, 'HTTP method', 'GET');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = (string) $input->getArgument('url');
        $method = strtoupper((string) $input->getOption('method'));
        $owner = $this->ownership->describe(Request::create($url, $method));

        $output->writeln(sprintf('%s %s', $method, $url));
        $output->writeln(sprintf('  owner:  %s', $owner['owner']));
        $output->writeln(sprintf('  route:  %s', $owner['route'] ?? '-'));
        $output->writeln(sprintf('  detail: %s', $owner['detail']));

        return Command::SUCCESS;
    }
}

<?php

namespace App\Commands;

use App\Libraries\MaterialStockEditReportScheduler;
use App\Models\RawMaterialStockLogModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestMaterialStockReport extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'stocklog:test-report';
    protected $description = 'Force-send the Material Stock Edit report now, optionally seeding a fake log row first.';
    protected $usage       = 'stocklog:test-report [--seed] [--material=<id>]';
    protected $options     = [
        '--seed'     => 'Insert a fake manual stock edit log row before sending.',
        '--material' => 'Material ID to use with --seed (default: 1).',
    ];

    public function run(array $params)
    {
        if (CLI::getOption('seed')) {
            $materialId = (int) (CLI::getOption('material') ?? 1);

            (new RawMaterialStockLogModel())->logChange([
                'material_id'     => $materialId,
                'action'          => 'added',
                'amount'          => 10,
                'before_qty'      => 5,
                'after_qty'       => 15,
                'unit'            => 'kg',
                'changed_by'      => null,
                'changed_by_name' => 'CLI Test',
                'source'          => 'cli_test_seed',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            CLI::write('Seeded a fake log row for material_id=' . $materialId, 'yellow');
        }

        CLI::write('Forcing Material Stock Edit report now...', 'green');
        MaterialStockEditReportScheduler::runNow(true);
        CLI::write('Done — check writable/logs/ for the log_message() lines and your inbox.', 'green');
    }
}
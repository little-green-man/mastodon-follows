<?php

namespace App\Commands;

use App\Library\Mastodon;
use LaravelZero\Framework\Commands\Command;

class ProcessFollowersCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'process
        {url : URL to the page of followers}
        {--personalInstance= : url of your personal instance}
        {--maxPages=0 : Integer maximum number of pages to process}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Process follows so that you, too can follow them.';

    /**
     * Execute the console command.
     */
    public function handle(Mastodon $mastodon): void
    {
        $url = $this->argument('url');
        $mastodon->url = $url;

        $mastodon->maxPages = $this->option('maxPages');
        $mastodon->personalInstance = $this->option('personalInstance');

        $this->task('Compiling list of Follows', function () use ($mastodon) {
            $mastodon->testAndCountExpectedItems();
            $mastodon->processPages();

            return true;
        });

        $this->info('Gathering data from individual Follows');

        $bar = $this->output->createProgressBar($mastodon->items->count());

        $bar->start();

        $mastodon->items->each(function ($follow) use ($bar) {
            $follow->gatherData();
            $bar->advance();
        });

        $bar->finish();

        $this->newLine();

        $this->info('Gathered data for all Follows');

        $mastodon->export();

        // dd($mastodon->items);
    }
}

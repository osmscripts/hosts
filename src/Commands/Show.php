<?php

namespace OsmScripts\Hosts\Commands;

/** @noinspection PhpUnused */

/**
 * `show` shell command class.
 *
 * @property
 */
class Show extends Command
{
    protected function configure() {
        $this->setDescription("Outputs local DNS entries from the 'hosts' file");
    }

    protected function handle() {
        $this->output->writeln($this->contents);
    }
}
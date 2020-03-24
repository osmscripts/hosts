<?php

namespace OsmScripts\Hosts\Commands;

use OsmScripts\Core\Files;
use OsmScripts\Core\Script;
use OsmScripts\Hosts\Hints\EntryHint;
use Symfony\Component\Console\Input\InputArgument;

/** @noinspection PhpUnused */

/**
 * `remove` shell command class.
 *
 * Dependencies:
 *
 * @property Files $files Helper for generating files.
 *
 * Command line arguments:
 *
 * @property string $host
 */
class Remove extends Command
{
    #region Properties
    public function default($property) {
        /* @var Script $script */
        global $script;

        switch ($property) {
            case 'files': return $script->singleton(Files::class);

            case 'host': return $this->input->getArgument('host');
        }

        return parent::default($property);
    }
    #endregion

    protected function configure() {
        $this
            ->setDescription("Removes a local DNS entry from 'hosts' file")
            ->addArgument('host', InputArgument::REQUIRED,
                "Host name");
    }

    protected function handle() {
        if (!($entries = $this->find($this->host))) {
            throw new \Exception("'{$this->host}' host not found in 'hosts' file");
        }

        $lines = $this->lines;

        foreach (array_reverse($entries) as $entry) {
            /* @var EntryHint $entry */
            array_splice($lines, $entry->line, 1);
        }

        $this->files->save($this->filename, implode("", $lines));
    }
}
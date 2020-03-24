<?php

namespace OsmScripts\Hosts\Commands;

use OsmScripts\Core\Editor;
use OsmScripts\Core\Files;
use OsmScripts\Core\Script;
use Symfony\Component\Console\Input\InputArgument;

/** @noinspection PhpUnused */

/**
 * `add` shell command class.
 *
 * Dependencies:
 *
 * @property Files $files Helper for generating files.
 * @property Editor $editor
 *
 * Command line arguments:
 *
 * @property string $ip
 * @property string $host
 */
class Add extends Command
{
    #region Properties
    public function default($property) {
        /* @var Script $script */
        global $script;

        switch ($property) {
            case 'files': return $script->singleton(Files::class);
            case 'editor': return $script->singleton(Editor::class);

            case 'ip': return $this->input->getArgument('ip');
            case 'host': return $this->input->getArgument('host');
        }

        return parent::default($property);
    }
    #endregion

    protected function configure() {
        $this
            ->setDescription("Adds a local DNS entry to 'hosts' file")
            ->addArgument('ip', InputArgument::REQUIRED,
                "IP address")
            ->addArgument('host', InputArgument::REQUIRED,
                "Host name");
    }

    protected function handle() {
        if ($this->find($this->host)) {
            throw new \Exception("'hosts' file already has an entry for '{$this->host}' host");
        }

        $this->files->save($this->filename, $this->editor->edit($this->contents, function() {
            if (($pos = $this->editor->last($this->end_marker)) !== false) {
                $this->editor->insertBefore($pos, "{$this->ip} {$this->host}\n");
            }
            else {
                $this->editor->add(<<<EOT

{$this->start_marker}
{$this->ip} {$this->host}
{$this->end_marker}

EOT
                );
            }
        }));
    }
}
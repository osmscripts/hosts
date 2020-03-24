<?php

namespace OsmScripts\Hosts\Commands;

use OsmScripts\Core\Script;

/** @noinspection PhpUnused */

/**
 * `show` shell command class.
 *
 * @property
 */
class Show extends Command
{
    #region Properties
    public function default($property) {
        /* @var Script $script */
        global $script;

        switch ($property) {
        }

        return parent::default($property);
    }
    #endregion

    protected function configure() {
        // TODO: describe the command usage, arguments and options
    }

    protected function handle() {
        // TODO: execute command logic
    }
}
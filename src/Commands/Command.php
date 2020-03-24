<?php

namespace OsmScripts\Hosts\Commands;

use OsmScripts\Core\Command as BaseCommand;
use OsmScripts\Hosts\Hints\EntryHint;

/**
 * @property string $filename
 * @property string $contents
 * @property string[] $lines
 * @property object[]|EntryHint[] $entries
 */
class Command extends BaseCommand
{
    const PATTERN = '/(?<ip>\d{1,3}(?:\.\d{1,3}))\s(?<host>\w+(?:\.\w+)*)/';

    public $start_marker = "# Entries managed with `hosts` shell command:";
    public $end_marker = "# End of entries managed with `hosts` shell command";

    #region Properties
    public function default($property) {
        switch ($property) {
            case 'filename': return $this->getFilename();
            case 'contents': return file_get_contents($this->filename);
            case 'lines': return file($this->filename);
            case 'entries': return $this->getEntries();
        }

        return parent::default($property);
    }

    protected function getFilename() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? 'c:\Windows\System32\drivers\etc\hosts'
            : 'etc/hosts';
    }

    protected function getEntries() {
        $result = [];

        foreach ($this->lines as $index => $line) {
            if (!preg_match(static::PATTERN, $line, $match)) {
                continue;
            }

            $result[] = (object)[
                'ip' => $match['ip'],
                'host' => $match['host'],
                'line' => $index,
            ];
        }

        return $result;
    }
    #endregion

    protected function find($host) {
        $result = [];

        foreach ($this->entries as $entry) {
            if ($entry->host == $host) {
                $result[] = $entry;
            }
        }

        return count($result) ? $result : null;
    }
}
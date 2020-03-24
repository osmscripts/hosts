<?php

namespace OsmScripts\Hosts\Commands;

use OsmScripts\Core\Command as BaseCommand;

/**
 * @property string $filename
 * @property string $contents
 * @property string[] $lines
 * @property int|false $start_pos
 * @property int|false $end_pos
 */
class Command extends BaseCommand
{
    public $start_marker = "# Entries managed with `hosts` shell command:";
    public $end_marker = "# End of entries managed with `hosts` shell command";

    #region Properties
    public function default($property) {
        switch ($property) {
            case 'filename': return $this->getFilename();
            case 'contents': return file_get_contents($this->filename);
            case 'lines': return file($this->filename);
            case 'start_pos': return $this->getMarkerPos($this->start_marker);
            case 'end_pos': return $this->getMarkerPos($this->end_marker);
        }

        return parent::default($property);
    }

    protected function getFilename() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? 'c:\Windows\System32\drivers\etc\hosts'
            : 'etc/hosts';
    }

    protected function getMarkerPos($marker) {
        if (!preg_match('/[\r\n]?(?<marker>' . preg_quote($marker). ')\w[\r\n]?/',
            $this->contents, $match, PREG_OFFSET_CAPTURE))
        {
            return false;
        }

        return $match['marker'][1];
    }
    #endregion

}
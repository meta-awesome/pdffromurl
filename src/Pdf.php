<?php
namespace Metawesome\PdfFromUrl;

use Metawesome\PdfFromUrl\Commands\Command;

class Pdf
{
    /**
     * @var string the name of the `wkhtmltopdf` binary. Default is
     * `wkhtmltopdf`. You can also configure a full path here.
     */
    public $binary = 'wkhtmltopdf';

    /**
     * @var Command the command instance that executes wkhtmltopdf
     */
    protected $_command;

    /**
     * @var filename the file name can be set or auto generated
     */
    protected $filename;

    /**
     * @var url the url to generate a PDF file
     */
    public $url;

    /**
     * @var string the detailed error message. Empty string if none.
     */
    protected $_error = '';

    /**
     * @var bool whether the PDF was created
     */
    protected $_isCreated = false;

    public function fromUrl(string $url)
    {
        if(empty($url)) {
            return false;
        }

        $this->$url = $url;

        return $this;
    }

    /**
     * @return Command the command instance that executes wkhtmltopdf
     */
    public function getCommand()
    {
        if ($this->_command === null) {
            if (!isset($options['command'])) {
                $options['command'] = $this->binary;
            }
            $this->_command = new Command($options);
        }
        return $this->_command;
    }

    /**
     * Save the PDF to given filename (triggers PDF creation)
     *
     * @param string $filename to save PDF as
     * @return bool whether PDF was created successfully
     */
    public function saveAs(string $filename)
    {
        if(empty($filename)) {
            return false;
        }

        if ($this->_isCreated) {
            return false;
        }

        $command = $this->getCommand();

        $command->addArg($this->url, $filename, null, true);    // Always escape filename
        if (!$command->execute()) {
            $this->_error = $command->getError();
            if (!(file_exists($filename) && filesize($filename) == 0)) {
                return false;
            }

            if ($this->_error) {
                return $this->_error;
            }
        }

        $this->_isCreated = true;

        return true;
    }

    /**
     * @return string the detailed error message. Empty string if none.
     */
    public function getError()
    {
        return $this->_error;
    }
}
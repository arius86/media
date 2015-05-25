<?php
/**
 * This file is part of Vegas package
 *
 * @author Adrian Malik <adrian.malik.89@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Forms\Element;

use Phalcon\Forms\Element;
use Vegas\Forms\Decorator\DecoratedTrait;
use Vegas\Forms\Decorator;
use Vegas\Upload\Attributes;

class Upload extends File
{
    use Attributes;
    use DecoratedTrait {
        renderDecorated as private baseRenderDecorated;
    }

    const BROWSER_BUTTON = 'button';
    const BROWSER_DROPZONE = 'dropzone';

    private $model = null;
    private $path = null;
    private $uploadUrl = null;
    private $browserLabel = null;
    private $browserType = null;

    public function __construct($name, $attributes = null)
    {
        $this->path = 'files/';
        $this->maxFiles = 1;
        $this->uploadUrl = '/upload';
        $this->minFileSize = '1B';
        $this->maxFileSize = '10MB';
        $this->browserLabel = 'Select file';
        $this->browserType = self::BROWSER_BUTTON;
        $this->allowedExtensions = [];
        $this->forbiddenExtensions = [];
        $this->allowedMimeTypes = [];
        $this->forbiddenMimeTypes = [];

        $templatePath = implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Upload', 'views', '']);
        $this->setDecorator(new Decorator($templatePath));
        $this->getDecorator()->setTemplateName('jquery');

        parent::__construct($name, $attributes);
    }

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path = 'files/')
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $uploadUrl Url where uploaded file will be sent
     * @return $this
     */
    public function setUploadUrl($uploadUrl = '/upload')
    {
        $this->uploadUrl = $uploadUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadUrl()
    {
        return $this->uploadUrl;
    }

    /**
     * @param string $browserLabel Label on button or drop zone which tells you to select file
     * @return $this
     */
    public function setBrowserLabel($browserLabel = 'Select file')
    {
        $this->browserLabel = $browserLabel;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrowserLabel()
    {
        return $this->browserLabel;
    }

    /**
     * @param string $browserType It can be button or drop zone (drag & drop)
     * @return $this
     */
    public function setBrowserType($browserType = self::BROWSER_BUTTON)
    {
        $this->browserType = $browserType;
        return $this;
    }

    public function getBrowserType()
    {
        return $this->browserType;
    }

    public function renderDecorated($attributes = null)
    {
        $attributes = $this->getAttributes();

        foreach ($attributes as $name => $value) {
            $this->setAttribute('data-' . $name, $value);
        }

        $this->setAttribute('max-files', $this->maxFiles);
        $this->setAttribute('upload-url', $this->uploadUrl);
        $this->setAttribute('min-file-size', $this->minFileSize);
        $this->setAttribute('max-file-size', $this->maxFileSize);
        $this->setAttribute('browser-type', $this->browserType);
        $this->setAttribute('browser-label', $this->browserLabel);
        $this->setAttribute('allowed-extensions', implode(',', $this->allowedExtensions));
        $this->setAttribute('forbidden-extensions', implode(',', $this->forbiddenExtensions));
        $this->setAttribute('allowed-mime-types', implode(',', $this->allowedMimeTypes));
        $this->setAttribute('forbidden-mime-types', implode(',', $this->forbiddenMimeTypes));

        return $this->baseRenderDecorated($attributes);
    }
}
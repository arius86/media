<?php
/**
 * This file is part of Vegas package
 *
 * @author Tomasz Borodziuk <tomasz.borodziuk@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Test\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Vegas\Forms\Element\Upload;
use Test\Models\FakeFile as FakeFileModel;

class Fake extends \Phalcon\Forms\Form
{
    public function initialize()
    {
        $image = new Upload('fake_file');
        $image->setModel(new FakeFileModel());
        $image->setPreviewSize(array('width' => 100, 'height' => 100));
        $image->getDecorator()->setTemplateName('jquery');
        $image->setUploadUrl($this->url->get([
            'for' => 'testcrud',
            'action' => 'upload'
        ]));

        $image->setMode(Upload::MODE_MULTI);
        $image->setMaxFiles(3);
        $image->setExtensions(['jpg', 'png']);
        $image->setMimeTypes(['image/jpeg', 'image/png']);
        $image->setRenderPreview(true);
        $image->setLabel('Image');
        $image->getDecorator()->setDI($this->di);
        $image->setAutoUpload(false);
        $image->setButtonLabels(array('add'=>'Add image'));
        $image->setTriggerType('button');
        $image->setMaxFileSize('10MB');
        $this->add($image);


        $field = new Text('fake_field');
        $field->addValidator(new PresenceOf());
        $this->add($field);

    }
} 
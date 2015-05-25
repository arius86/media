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

namespace Vegas\Mvc\Controller;

use Vegas\Upload\Exception\ForbiddenFileExtensionException;
use Vegas\Upload\Exception\ForbiddenFileMimeTypeException;

trait UploadTrait
{
    /**
     * Upload action for every controller which inherit from CRUDController
     *
     * @return mixed
     * @throws \Exception
     */
    public function uploadAction()
    {
        if ($this->request->hasFiles() == true) {
            $this->initializeScaffolding();
            $form = $this->scaffolding->getForm();
            $name = key($_FILES);
            $uploadElement = $form->get($name);
            $model = $uploadElement->getModel();

            $path = $uploadElement->getPath();

            $maxFileSize = $uploadElement->getMaxFileSize();
            $minFileSize = $uploadElement->getMinFileSize();

            $allowedExtensions = $uploadElement->getAllowedExtensions();
            $forbiddenExtensions = $uploadElement->getForbiddenExtensions();

            $allowedMimeTypes = $uploadElement->getAllowedMimeTypes();
            $forbiddenMimeTypes = $uploadElement->getForbiddenMimeTypes();

            foreach ($this->request->getUploadedFiles() as $file) {
                $fileName = $file->getName();
                $fileSize = $file->getSize();
                $fileType = $file->getRealType();
                $fileExtensions = pathinfo($fileName, PATHINFO_EXTENSION);


                if (!empty($allowedExtensions)) {
                    if (!in_array($fileExtensions, $allowedExtensions)) {
                        throw new ForbiddenFileExtensionException();
                    }
                }

                if (!empty($forbiddenExtensions)) {
                    if (in_array($fileExtensions, $forbiddenExtensions)) {
                        throw new ForbiddenFileExtensionException();
                    }
                }

                if (!empty($allowedMimeTypes)) {
                    if (!in_array($fileType, $allowedMimeTypes)) {
                        throw new ForbiddenFileMimeTypeException();
                    }
                }

                if (!empty($forbiddenMimeTypes)) {
                    if (in_array($fileType, $forbiddenMimeTypes)) {
                        throw new ForbiddenFileMimeTypeException();
                    }
                }

                if (!empty($maxFileSize)) {
                    if ($fileSize > $this->convertFileSizeToBytes($maxFileSize)) {
                        throw new \Exception('s');
                    }
                }

                if (!empty($minFileSize)) {
                    if ($fileSize < $this->convertFileSizeToBytes($minFileSize)) {
                        throw new \Exception('s');
                    }
                }

                if (empty($path)) {
                    $path = 'files/';
                }

                $model->name = $fileName;
                $model->mime_type = $fileType;
                $model->path = $path;
                $model->save();

                $file->moveTo($path . $model->_id);
                return $this->response->setJsonContent((string) $model->_id);
            }
        }

        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    private function convertFileSizeToBytes($size)
    {
        $size = (string) $size;
        $letters = '';
        $bytes = '';

        for ($i = 0; $i < strlen($size); $i++) {
            if (!is_numeric($size[$i])) {
                $letters .= $size[$i];
            } else {
                $bytes .= (string) $size[$i];
            }
        }

        $bytes = (int) $bytes;

        if ($letters) {
            switch (strtoupper($letters)) {
                case 'B': break;
                case 'KB': $bytes = $bytes * 1024; break;
                case 'MB': $bytes = $bytes * 1024 * 1024; break;
                case 'GB': $bytes = $bytes * 1024 * 1024 * 1024; break;
                case 'TB': $bytes = $bytes * 1024 * 1024 * 1024 * 1024; break;
                case 'PB': $bytes = $bytes * 1024 * 1024 * 1024 * 1024 * 1024; break;
            }
        }

        return $bytes;
    }
}
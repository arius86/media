<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Mvc\Controller\Crud\Exception;

use Vegas\Mvc\Controller\Exception as Exception;

class UploaderNotSetException extends Exception
{
    protected $message = "There is no uploader service in dependency injection.";
}
 
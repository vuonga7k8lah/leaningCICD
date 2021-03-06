<?php

/*
 * This file is part of twig-cache-extension.
 *
 * (c) Alexander <iam.asm89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\CacheExtension\Exception;

class NonExistingStrategyKeyException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = 'No strategy key found in value.', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
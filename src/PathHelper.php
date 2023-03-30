<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor;

class PathHelper
{
    public const VAR_PERSISTENT_DIR = __DIR__.'/../var/persistent';
    public const PUBLIC_DIR = __DIR__.'/../public';
    public const CLIENT_DIR = __DIR__.'/../client';

    public const SUPPORTED_IMAGE_EXTENSIONS = ['.png', '.jpg', '.jpeg'];
}

<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-20 14:31:30
 */

namespace Yeskn\Support\File;

class UploadedFile
{
    private $absoluteFileName;

    public function __construct($absoluteName)
    {
        $this->absoluteFileName = $absoluteName;
    }

    /**
     * @return mixed
     */
    public function getAbsoluteFileName()
    {
        return $this->absoluteFileName;
    }

    /**
     * @param mixed $absoluteFileName
     */
    public function setAbsoluteFileName($absoluteFileName)
    {
        $this->absoluteFileName = $absoluteFileName;
    }
}

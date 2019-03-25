<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-25 23:01:45
 */

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\Support\AbstractController;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class UploadController
 * @package Yeskn\AdminBunle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class UploadController extends AbstractController
{
    /**
     * @Route("/upload/images")
     *
     * @param $request
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $name = $request->get('name', 'file');
        $width = $request->get('width', 100);
        $height = $request->get('height', 100);

        $file = $request->files->get($name);

        $extension = $file->guessExtension();
        $fileName = 'upload/images/' . time() . mt_rand(1000, 9999) . '.' . $extension;

        $targetPath = $this->getParameter('kernel.project_dir') .  '/web/' . $fileName;

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $targetPath);

        Image::configure(array('driver' => 'gd'));

        $image = Image::make($targetPath);
        $image->resize($width, $height)->save();

        return new JsonResponse([
            'errno' => '0',
            'data' => [
                $this->getParameter('assets_base_url') . '/' . $fileName
            ]
        ]);
    }
}

<?php

namespace App\Helpers;

use Exception;

class UploadFileHelper {
    public static function upload($file, $userId, $fileType) {
        $directory = __DIR__ . '/../../public/uploads/' . $userId;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $originalFilename = $file->getClientFilename();
        $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);

        if (!$fileExtension) {
            $fileExtension = 'unknown';
        }

        $filename = $fileType . '-' . $userId . '-' . time() . '.' . $fileExtension;
        $filePath = $directory . '/' . $filename;

        try {
            $file->moveTo($filePath);

            return array(
                'success' => true,
                'message' => 'Successfully uploaded file.',
                'data' => array(
                    'filename' => $filename,
                    'filepath' => '/uploads/' . $userId . '/' . $filename
                )
            );
        } catch (Exception $e) {
            return array(
                'success' => false,
                'message' => 'Failed while upload file.',
                'data' => null
            );
        }
    }
}
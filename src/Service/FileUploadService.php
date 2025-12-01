<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
  # WhiteList des types MIME autorisés pour les images
  private const ALLOWED_MIME_TYPES = [
    'image/jpeg',
    'image/png',
    'image/webp',
  ];
  private const MAX_FILE_SIZE = 5242880; // 5MB en bytes

  public function validateAndUpload(UploadedFile $file, string $uploadDirectory): string
  {
    // Vérifier le MIME type
    $mimeType = $file->getMimeType();
    if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
      throw new \InvalidArgumentException(
        "Type de fichier non autorisé. Seuls sont acceptés les fichiers JPEG, PNG et WebP sont acceptés."
      );
    }
    // Vérifier la taille
    if ($file->getSize() > self::MAX_FILE_SIZE) {
      throw new \InvalidArgumentException(
        "Fichier trop volumineux (max 5MB)."
      );
    }
    // Générer un nom sécurisé - éviter les extensions propres de l'utilisateur
    // génère 32 caractères aléatoires 
    $safeFilename = bin2hex(random_bytes(16));

    // L'extension doit être basée sur le MIME type et pas sur le fichier
    $extension = match ($mimeType) {
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/webp' => 'webp',
      default => throw new \RuntimeException('MIME type non géré')
    };

    $filename = $safeFilename . '.' . $extension;

    // Déplacer le fichier 
    $file->move($uploadDirectory, $filename);

    // Retraiter l'image pour supprimer les métadonnées EXIF 
    $this->stripExifData($uploadDirectory . '/' . $filename);

    return $filename;
  }

  /**
   * Supprime les métadonnées EXIF en retraitant l'image
   *
   * @param string $filepath Chemin complet vers l'image
   */
  private function stripExifData(string $filepath): void
  {
    // Détecter le type d'image
    $imageInfo = getimagesize($filepath);

    if ($imageInfo === false) {
      throw new \RuntimeException('Impossible de lire les informations de l\'image');
    }

    // Retraiter selon le type pour supprimer EXIF
    // La mémoire est automatiquement libérée
    switch ($imageInfo[2]) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($filepath);
        if ($image === false) {
          throw new \RuntimeException('Impossible de charger l\'image JPEG');
        }
        imagejpeg($image, $filepath, 90);
        // Plus besoin de imagedestroy($image) en PHP 8.0+
        break;

      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($filepath);
        if ($image === false) {
          throw new \RuntimeException('Impossible de charger l\'image PNG');
        }
        imagepng($image, $filepath, 9);
        // Plus besoin de imagedestroy($image) en PHP 8.0+
        break;

      case IMAGETYPE_WEBP:
        $image = imagecreatefromwebp($filepath);
        if ($image === false) {
          throw new \RuntimeException('Impossible de charger l\'image WebP');
        }
        imagewebp($image, $filepath, 90);
        // Plus besoin de imagedestroy($image) en PHP 8.0+
        break;

      default:
        throw new \RuntimeException('Type d\'image non supporté');
    }
  }
}

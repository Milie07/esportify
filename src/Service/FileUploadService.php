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

  // Configuration pour les images de tournois
  private const TOURNAMENT_IMAGE_MAX_WIDTH = 1920;
  private const TOURNAMENT_IMAGE_MAX_HEIGHT = 1080;
  private const TOURNAMENT_IMAGE_QUALITY = 85; 

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

  /**
   * Upload une image de tournoi avec compression et redimensionnement
   * Les images sont stockées dans un dossier "pending" tant que le tournoi n'est pas validé
   *
   * @param UploadedFile $file Le fichier uploadé
   * @param string $uploadDirectory Le répertoire de base (ex: /path/to/uploads/tournaments/)
   * @param bool $isPending Si true, stocke dans pending/, sinon dans le dossier principal
   * @return string Le chemin relatif de l'image (ex: "uploads/tournaments/pending/xxx.jpg")
   */
  public function uploadTournamentImage(UploadedFile $file, string $uploadDirectory, bool $isPending = true): string
  {
    // Validation
    $mimeType = $file->getMimeType();
    if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
      throw new \InvalidArgumentException(
        "Type de fichier non autorisé. Seuls JPEG, PNG et WebP sont acceptés."
      );
    }

    if ($file->getSize() > self::MAX_FILE_SIZE) {
      throw new \InvalidArgumentException(
        "Fichier trop volumineux (max 5MB)."
      );
    }

    // Déterminer le dossier de destination
    $targetDirectory = $isPending
      ? $uploadDirectory . '/pending'
      : $uploadDirectory;

    // Créer le dossier si nécessaire
    if (!is_dir($targetDirectory)) {
      mkdir($targetDirectory, 0775, true);
    }

    // Générer un nom sécurisé unique
    $safeFilename = 'tournament_' . uniqid('', true);
    $extension = match ($mimeType) {
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/webp' => 'webp',
      default => throw new \RuntimeException('MIME type non géré')
    };

    $filename = $safeFilename . '.' . $extension;
    $filepath = $targetDirectory . '/' . $filename;

    // Déplacer le fichier temporaire
    $file->move($targetDirectory, $filename);

    // Compresser et redimensionner l'image
    $this->compressAndResizeImage($filepath, $mimeType);

    // Retourner le chemin relatif
    $relativePath = $isPending
      ? 'uploads/tournaments/pending/' . $filename
      : 'uploads/tournaments/' . $filename;

    return $relativePath;
  }

  /**
   * Déplace une image du dossier pending vers le dossier permanent
   *
   * @param string $pendingPath Chemin relatif de l'image en pending (ex: "uploads/tournaments/pending/xxx.jpg")
   * @param string $baseDirectory Répertoire de base absolu (ex: "/var/www/html/public")
   * @return string Le nouveau chemin relatif (ex: "uploads/tournaments/xxx.jpg")
   */
  public function moveToPermanent(string $pendingPath, string $baseDirectory): string
  {
    // Construire les chemins absolus
    $absolutePendingPath = $baseDirectory . '/' . $pendingPath;

    if (!file_exists($absolutePendingPath)) {
      throw new \RuntimeException("Le fichier en attente n'existe pas: {$pendingPath}");
    }

    // Extraire le nom de fichier
    $filename = basename($pendingPath);

    // Nouveau chemin
    $newRelativePath = 'uploads/tournaments/' . $filename;
    $absoluteNewPath = $baseDirectory . '/' . $newRelativePath;

    // Déplacer le fichier
    if (!rename($absolutePendingPath, $absoluteNewPath)) {
      throw new \RuntimeException("Impossible de déplacer l'image vers le dossier permanent");
    }

    return $newRelativePath;
  }

  /**
   * Supprime une image de tournoi
   *
   * @param string $imagePath Chemin relatif de l'image (ex: "uploads/tournaments/pending/xxx.jpg")
   * @param string $baseDirectory Répertoire de base absolu (ex: "/var/www/html/public")
   * @return bool True si supprimé, false si le fichier n'existait pas
   */
  public function deleteTournamentImage(string $imagePath, string $baseDirectory): bool
  {
    $absolutePath = $baseDirectory . '/' . $imagePath;

    if (!file_exists($absolutePath)) {
      return false; // Fichier déjà supprimé ou inexistant
    }

    return unlink($absolutePath);
  }

  /**
   * Compresse et redimensionne une image pour optimiser sa taille
   *
   * @param string $filepath Chemin absolu vers l'image
   * @param string $mimeType Type MIME de l'image
   */
  private function compressAndResizeImage(string $filepath, string $mimeType): void
  {
    // Charger l'image selon son type
    $image = match ($mimeType) {
      'image/jpeg' => imagecreatefromjpeg($filepath),
      'image/png' => imagecreatefrompng($filepath),
      'image/webp' => imagecreatefromwebp($filepath),
      default => throw new \RuntimeException('Type MIME non supporté')
    };

    if ($image === false) {
      throw new \RuntimeException('Impossible de charger l\'image');
    }

    // Obtenir les dimensions actuelles
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Calculer les nouvelles dimensions en préservant le ratio
    $newWidth = $originalWidth;
    $newHeight = $originalHeight;

    if ($originalWidth > self::TOURNAMENT_IMAGE_MAX_WIDTH || $originalHeight > self::TOURNAMENT_IMAGE_MAX_HEIGHT) {
      $widthRatio = self::TOURNAMENT_IMAGE_MAX_WIDTH / $originalWidth;
      $heightRatio = self::TOURNAMENT_IMAGE_MAX_HEIGHT / $originalHeight;
      $ratio = min($widthRatio, $heightRatio);

      $newWidth = (int) round($originalWidth * $ratio);
      $newHeight = (int) round($originalHeight * $ratio);
    }

    // Créer une nouvelle image redimensionnée si nécessaire
    if ($newWidth !== $originalWidth || $newHeight !== $originalHeight) {
      $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

      if ($resizedImage === false) {
        throw new \RuntimeException('Impossible de créer l\'image redimensionnée');
      }

      // Préserver la transparence pour PNG et WebP
      if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
      }

      // Redimensionner
      imagecopyresampled(
        $resizedImage,
        $image,
        0, 0, 0, 0,
        $newWidth,
        $newHeight,
        $originalWidth,
        $originalHeight
      );

      $image = $resizedImage;
    }

    // Sauvegarder l'image compressée
    match ($mimeType) {
      'image/jpeg' => imagejpeg($image, $filepath, self::TOURNAMENT_IMAGE_QUALITY),
      'image/png' => imagepng($image, $filepath, (int) round((100 - self::TOURNAMENT_IMAGE_QUALITY) / 11)), // PNG compression 0-9
      'image/webp' => imagewebp($image, $filepath, self::TOURNAMENT_IMAGE_QUALITY),
      default => throw new \RuntimeException('Type MIME non supporté')
    };
  }
}

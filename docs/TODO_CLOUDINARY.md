# TODO : Intégration Cloudinary pour le stockage des images

## Contexte
Actuellement, les images de tournois sont stockées localement dans `/public/uploads/`.
En production sur Fly.io, ces images sont **perdues à chaque redéploiement** car le système de fichiers est éphémère.

## Solution : Cloudinary
Utiliser Cloudinary pour le stockage permanent et gratuit des images (plan gratuit : 25GB).

---

## Étapes d'intégration

### 1. Créer un compte Cloudinary (gratuit)
- Aller sur https://cloudinary.com/users/register_free
- Créer un compte gratuit
- Récupérer les identifiants dans le Dashboard :
  - `Cloud name` (ex: dxxxx)
  - `API Key` (ex: 123456789)
  - `API Secret` (ex: abc123xyz)

### 2. Configurer les secrets dans Fly.io
```bash
fly secrets set CLOUDINARY_URL="cloudinary://API_KEY:API_SECRET@CLOUD_NAME"
```

**⚠️ IMPORTANT : Ne JAMAIS mettre ces secrets dans fly.toml ou dans Git !**

### 3. Ajouter les secrets en dev local
Ajouter dans `.env.local` (non versionné) :
```
CLOUDINARY_URL="cloudinary://API_KEY:API_SECRET@CLOUD_NAME"
```

### 4. Installer les dépendances Composer
```bash
composer require cloudinary/cloudinary_php
composer require cloudinary/transformation-builder-sdk
```

### 5. Créer un service Cloudinary dans Symfony

**Fichier : `src/Service/CloudinaryUploader.php`**
```php
<?php

namespace App\Service;

use Cloudinary\Cloudinary;
use Cloudinary\Uploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CloudinaryUploader
{
    private Cloudinary $cloudinary;

    public function __construct(string $cloudinaryUrl)
    {
        $this->cloudinary = new Cloudinary($cloudinaryUrl);
    }

    public function upload(UploadedFile $file, string $folder = 'tournaments'): string
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getPathname(),
            [
                'folder' => $folder,
                'resource_type' => 'image',
                'transformation' => [
                    'width' => 1200,
                    'height' => 900,
                    'crop' => 'limit',
                    'quality' => 'auto'
                ]
            ]
        );

        return $result['secure_url'];
    }

    public function delete(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
```

**Configurer le service dans `config/services.yaml`** :
```yaml
services:
    App\Service\CloudinaryUploader:
        arguments:
            $cloudinaryUrl: '%env(CLOUDINARY_URL)%'
```

### 6. Modifier l'entité Tournament

**Fichier : `src/Entity/Tournament.php`**

Modifier la propriété `imagePath` pour stocker l'URL Cloudinary au lieu du chemin local.

### 7. Modifier le contrôleur

**Fichier : Contrôleur qui gère la création de tournois**

Remplacer l'upload local par :
```php
use App\Service\CloudinaryUploader;

public function create(Request $request, CloudinaryUploader $uploader): Response
{
    // ...

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('tournamentImage')->getData();

        if ($imageFile) {
            // Upload vers Cloudinary au lieu du système local
            $imageUrl = $uploader->upload($imageFile, 'tournaments');
            $tournament->setImagePath($imageUrl);
        }

        // ...
    }
}
```

### 8. Nettoyer les anciennes images locales (optionnel)

Supprimer ou archiver les images dans `/public/uploads/` qui ne seront plus utilisées.

### 9. Retirer le message d'avertissement

Une fois Cloudinary intégré, retirer le message d'avertissement dans :
**`templates/spaces/organizer.html.twig` lignes 145-151**

---

## Avantages de cette solution

✅ **Gratuit** : Plan gratuit Cloudinary (25GB)
✅ **Persistant** : Les images ne sont jamais perdues
✅ **CDN** : Images servies rapidement partout dans le monde
✅ **Optimisation** : Redimensionnement et compression automatiques
✅ **Dev = Prod** : Même système en local et en production

---

## Coût estimé

- **Cloudinary** : $0/mois (plan gratuit)
- **Fly.io** : Pas de volume nécessaire → $0/mois

**Total : GRATUIT**

---

## Ressources

- [Documentation Cloudinary PHP](https://cloudinary.com/documentation/php_integration)
- [Transformation d'images Cloudinary](https://cloudinary.com/documentation/image_transformations)
- [Gestion des secrets Fly.io](https://fly.io/docs/reference/secrets/)

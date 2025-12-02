<?php

namespace App\Service;

final class InputSanitizer
{
  public function text(string $v, int $max = 255): string
  {
    $v = trim(preg_replace('/\s+/u', ' ', $v));
    $v = strip_tags($v);
    return mb_substr($v, 0, $max);
  }

  public function textarea(string $v, int $max = 5000, bool $allowBasic = false): string
  {
    $v = trim($v);
    if ($allowBasic) {
      // Supprimer les attributs dangereux
      $v = strip_tags($v, '<p><br><ul><ol><li><strong><em>');
      // Supprimer tous les attributs (onclick, onerror, etc.)
      $v = preg_replace('/<(\w+)[^>]*>/', '<$1>', $v);
    } else {
      $v = strip_tags($v);
    }
    return mb_substr($v, 0, $max);
  }

  public function email(string $v): string
  {
    $v = trim($v);
    return filter_var($v, FILTER_VALIDATE_EMAIL) ? $v : '';
  }

  public function int(string|int $v, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): int
  {
    $i = filter_var($v, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]]);
    return $i === false ? $min : $i;
  }

  public function sanitize(?string $value): ?string
  {
    if ($value === null) {
        return null;
    }

    // enlève les balises HTML
    $clean = strip_tags($value);

    // nettoie les espaces
    $clean = trim(preg_replace('/\s+/u', ' ', $clean));

    // retourne la valeur BRUTE 
    return $clean;
  } 
}

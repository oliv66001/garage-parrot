<?php

namespace App\Entity\MyTrait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait SlugTrait
{
    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateSlug(): void
    {
        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug($this->name)->lower();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $this->slug = $this->slugify($this->name);
        }
    }

    public function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

}

<?php

namespace Lyssal\DoctrineExtraBundle\Model\Breadcrumb;

/**
 * A breadcrumb.
 */
class Breadcrumb
{
    protected string $label;

    protected ?string $link = null;

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }
}

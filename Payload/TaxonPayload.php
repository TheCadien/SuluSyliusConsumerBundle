<?php

declare(strict_types=1);

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\SyliusConsumerBundle\Payload;

use Sulu\Bundle\SyliusConsumerBundle\Common\Payload;
use Sulu\Exception\FeatureNotImplementedException;

class TaxonPayload
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Payload
     */
    private $payload;

    public function __construct(int $id, array $payload)
    {
        $this->id = $id;
        $this->payload = new Payload($payload);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->payload->getStringValue('code');
    }

    public function getLevel(): int
    {
        return $this->payload->getIntValue('level');
    }

    public function getPosition(): int
    {
        return $this->payload->getIntValue('position');
    }

    public function getChildren(): array
    {
        $children = [];
        foreach ($this->payload->getArrayValue('children') as $childrenPayload) {
            $id = $childrenPayload['id'];
            $children[] = new TaxonPayload($id, $childrenPayload);
        }

        return $children;
    }

    public function getParent(): ?array
    {
        return $this->payload->getNullableArrayValue('parent', true);
    }

    /**
     * @return TaxonTranslationPayload[]
     */
    public function getTranslations(): array
    {
        $translations = [];
        foreach ($this->payload->getArrayValue('translations') as $locale => $translationPayload) {
            $id = $translationPayload['id'];
            $translations[$locale] = new TaxonTranslationPayload($id, $translationPayload);
        }

        return $translations;
    }

    /**
     * @return mixed[]
     */
    public function getImages(): array
    {
        throw new FeatureNotImplementedException('Images are not implemented');
    }

    public function getCustomData(): Payload
    {
        return new Payload($this->payload->getNullableArrayValue('customData', true) ?? []);
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }
}

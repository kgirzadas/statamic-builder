<?php

namespace Tdwesten\StatamicBuilder\Repositories;

use Illuminate\Support\Collection;
use Statamic\Fields\Fieldset as StatamicFieldset;
use Statamic\Fields\FieldsetRepository as FieldsFieldsetRepository;
use Tdwesten\StatamicBuilder\Fieldset;

class FieldsetRepository extends FieldsFieldsetRepository
{
    private ?array $handleToClassMap = null;

    public function find(string $handle): ?StatamicFieldset
    {
        $builderFieldset = $this->findFieldset($handle);

        if (! $builderFieldset) {
            return parent::find($handle);
        }

        return $this->createFieldset($builderFieldset);
    }

    public function findFieldset(string $handle): ?Fieldset
    {
        $map = $this->getHandleToClassMap();

        if (! isset($map[$handle])) {
            return null;
        }

        $fieldsetClassName = $map[$handle];

        return new $fieldsetClassName;
    }

    private function getHandleToClassMap(): array
    {
        if ($this->handleToClassMap !== null) {
            return $this->handleToClassMap;
        }

        $this->handleToClassMap = collect(config('statamic.builder.fieldsets', []))
            ->mapWithKeys(function ($fieldsetClassName) {
                if (empty($fieldsetClassName) || ! class_exists($fieldsetClassName)) {
                    return [];
                }

                $instance = new $fieldsetClassName;

                return [$instance->getSlug() => $fieldsetClassName];
            })
            ->filter()
            ->all();

        return $this->handleToClassMap;
    }

    public function all(): Collection
    {
        return collect([
            ...$this->getStandardFieldsets(),
            ...$this->getNamespacedFieldsets(),
            ...collect($this->getHandleToClassMap())
                ->map(function (string $fieldsetClassName) {
                    $fieldset = new $fieldsetClassName;

                    return $this->createFieldset($fieldset);
                })
                ->values()
                ->all(),
        ]);
    }

    public function save(StatamicFieldset $fieldset)
    {
        if ($this->findFieldset($fieldset->handle())) {
            // Fieldsets from statamic-builder should not be saved
            return;
        }

        parent::save($fieldset);
    }

    private function createFieldset(Fieldset $fieldset): StatamicFieldset
    {
        return $this
            ->make($fieldset->getSlug())
            ->initialPath(resource_path('fieldsets'))
            ->setContents($fieldset->fieldsetToArray());
    }
}

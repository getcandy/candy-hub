<?php

namespace GetCandy\Search\Elastic\Indexers;

use Illuminate\Database\Eloquent\Model;
use GetCandy\Search\Indexable;

abstract class BaseIndexer
{

    /**
     * Gets a collection of indexables, based on a model
     *
     * @param [type] $product
     * @return void
     */
    protected function getIndexables(Model $model)
    {
        $attributes = $this->attributeMapping($model);
        
        $indexables = collect();
        
        foreach ($attributes as $attribute) {
            foreach ($attribute as $lang => $item) {
                // Base Stuff
                $indexable = $this->getIndexable($model);
                $indexable->setIndex(config('search.index_prefix') . '_' .  $lang);
                
                $indexable->set('image', $this->getThumbnail($model));

                $indexable->set('departments', $this->getCategories($model));

                if (!empty($item['data'])) {
                    foreach ($item['data'] as $field => $value) {
                        $indexable->set($field, $value);
                    }
                }
                
                if ($model->variants) {
                    foreach ($model->variants as $variant) {
                        if (!$indexable->min_price || $indexable->min_price > $variant->price) {
                            $indexable->set('min_price', $variant->price);
                        }
                        if (!$indexable->max_price || $indexable->max_price < $variant->price) {
                            $indexable->set('max_price', $variant->price);
                        }
                    }
                }
                
                $indexables->push($indexable);
            }
        }

        return $indexables;
    }

    /**
     * Gets the attribute mapping for a model to be indexed
     *
     * @param Model $model
     * @return Array
     */
    public function attributeMapping(Model $model)
    {
        $mapping = [];
        $searchable = $this->getIndexableAttributes($model);
        
        foreach ($model->attribute_data as $field => $channel) {
            if (!$searchable->contains($field)) {
                continue;
            }
            foreach ($channel as $channelName => $locales) {
                foreach ($locales as $locale => $value) {
                    $mapping[$model->id][$locale]['data'][$field] = strip_tags($model->attribute($field, $channelName, $locale));
                }
            }
        }
        return $mapping;
    }

    /**
     * Gets any attributes which are marked as searchable
     *
     * @param Model $model
     * @return void
     */
    protected function getIndexableAttributes(Model $model)
    {
        return $model->attributes()->whereSearchable(true)->get()->map(function ($attribute) {
            return $attribute->handle;
        });
    }

    /**
     * Gets an indexable object
     *
     * @param array $attributes
     * @return Indexable
     */
    protected function getIndexable(Model $model)
    {
        $indexable = new Indexable(
            $model->encodedId()
        );
        return $indexable;
    }

    /**
     * Gets the thumbnail for a model
     *
     * @param Model $model
     * @return String
     */
    protected function getThumbnail(Model $model)
    {
        $url = null;
        if (isset($model->primaryAsset()->thumbnail)) {
            $transform = $model->primaryAsset()->thumbnail->first();
            $path = $transform->location . '/' . $transform->filename;
            $url = \Storage::disk($model->primaryAsset()->disk)->url($path);
        }
        return $url;
    }

    /**
     * Gets the category mapping for an indexable
     *
     * @param Model $model
     * @return Array
     */
    protected function getCategories(Model $model, $lang = 'en')
    {
        return $model->categories()->get()->map(function ($item) use ($lang) {
            return [
                'id' => $item->encodedId(),
                'name' => $item->attribute('name', null, $lang)
            ];
        })->toArray();
    }
}

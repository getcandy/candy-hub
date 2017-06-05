<?php

namespace GetCandy\Api\Pages\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Pages\Models\Page;
use Illuminate\Database\Eloquent\Model;

class PageService extends BaseService
{
    public function __construct()
    {
        $this->model = new Page();
    }

    /**
     * Creates a page
     * @param  array      $data
     * @param  Model|null $relation
     * @return Page
     */
    public function create(array $data, Model $relation = null)
    {
        $page = $this->model;

        $data['slug'] = $this->getUniqueSlug($data['slug']);
        $page->fill($data);

        if ($relation) {
            $relation->page()->save($page);
        } else {
            $page->save();
        }

        return $page;
    }

    /**
     * Finds a page based on it's channel, language and slug
     * @param  string $channel
     * @param  string $lang
     * @param  string $slug
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     * @return Mixed
     */
    public function findPage($channel, $lang, $slug = null)
    {

        if ($slug) {
            $result = $this->model->where(function ($q) use ($channel, $lang, $slug) {
                $q->whereHas('channel', function ($q2) use ($channel) {
                    $q2->where('handle', '=', $channel);
                });
                $q->whereHas('language', function ($q3) use ($lang) {
                    $q3->where('code', '=', $lang);
                });
                $q->where('slug', '=', $slug);
            });
        } else {
            $slug = $lang;
            $result = $this->model->where(function ($q) use ($channel, $lang, $slug) {
                $q->whereHas('channel', function ($q2) use ($channel) {
                    $q2->where('handle', '=', $channel);
                });
                $q->where('slug', '=', $slug);
            });
        }

        $model = $result->firstOrFail();

        if ($model->language->code != app()->getLocale()) {
            app()->setLocale($model->language->code);
        };

        return $result->firstOrFail();
    }

    /**
     * Gets a unique slug for a page
     * @param  string $slug
     * @return string
     */
    protected function getUniqueSlug($slug)
    {
        $suffixe = '1';
        while ($this->model->where('slug', '=', $slug)->exists()) {
            $slug = $slug . '-' . $suffixe;
            ++$suffixe;
        }
        return $slug;
    }
}

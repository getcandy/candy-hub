<?php

namespace GetCandy\Importers\Aqua;

class Decorator
{
    public function getData($model, $descriptionField = 'full_description')
    {
        $basename = str_singular(strtolower(class_basename($model)));
        $name = [];
        $newName = null;
        $desc = [];
        $newDesc = null;
        $urls = [];
        foreach ($model->descriptions as $description) {
            // Name
            if (str_slug($newName) == str_slug($description->{$basename})) {
                $name[$description->lang_code] = null;
            } else {
                $name[$description->lang_code] = $description->{$basename};
                $urls[$description->lang_code] = str_slug($description->{$basename});
            }
            $newName = $description->{$basename};

            // Description
            if ($newDesc == $description->{$descriptionField}) {
                $desc[$description->lang_code] = null;
            } else {
                $desc[$description->lang_code] = $description->{$descriptionField};
            }
            $newDesc = $description->{$descriptionField};
        }

        return [
            'historical_id' => $model->getAttribute($model->idref),
            'name' => $name,
            'description' => $desc,
            'url' => $urls
        ];
    }
}
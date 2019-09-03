<?php

namespace backend\modules\blog\repository;

use backend\modules\blog\entities\Meta;

class MetaRepository
{
    public function get($id,$alias): Meta
    {
        if (!$seo = Meta::findOne(['page_id' => $id,'alias' => $alias])) {
            throw new \DomainException('MetaSeo is not found.');
        }
        return $seo;
    }

    public function save(Meta $meta): void
    {
        if (!$meta->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function delete($id,$alias) : void
    {
        if($meta = Meta::findOne(['page_id' => $id,'alias' => $alias])){
            $meta->delete();
        }
    }
}

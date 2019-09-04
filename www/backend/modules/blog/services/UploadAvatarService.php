<?php
namespace backend\modules\blog\services;

use backend\modules\blog\entities\Tag;
use backend\modules\blog\forms\TagForm;
use backend\modules\blog\repository\TagRepository;

class UploadAvatarService
{
    public function setAvatar($data)
    {

        $upload = \Yii::getAlias('@backend') . '/web/uploads/avatar';
        if(!is_dir($upload)){
            mkdir( $upload, 0777 );
        }
        chmod($upload, 0777);

        $file = $data['file'];

        $file_name = $file['name'];

        if(is_uploaded_file($file['tmp_name']) && move_uploaded_file($file['tmp_name'],"$upload/$file_name")){
            return [
                'type' => 'success',
                'path' => realpath("$upload/$file_name"),
                'name' => "$file_name"
            ];
        }
        return [
            'type' => 'error',
            'message' => 'Ошибка загрузки файла'
        ];
    }
}

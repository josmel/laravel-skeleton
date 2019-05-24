<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseModel extends Model {

    use SoftDeletes;

    protected $perPage = 10;
    protected $dates = ['deleted_at'];
    protected $enableflagactive = true;
    protected $flagactive = 'flagactive';
    protected $connection= 'mysql';
    const STATE_FLAGACTIVE = 1;
    const STATE_FLAGINACTIVE = 0;

    public function delete() {

        if ($this->enableflagactive) {
            parent::update([
                $this->flagactive => self::STATE_FLAGINACTIVE,
            ]);
        }

        return parent::delete();
    }

//    public function update(array $attributes = [], array $options = []) {
//        if (!$this->exists) {
//            return false;
//        }
//        Edit::create(['edit_type' => static::class,
//            'edit_id' => $this->getKey(),
//            'user_id' => $options[0]]);
//        return $this->fill($attributes)->save($options);
//    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{

    protected $table = 'files';
    public $timestamps = true;

    protected $fillable = [
        'id', 'title', 'file_path', 'doc_type', 'user_id'
    ];

    public function docTypes()
    {
        return $this->belongsTo(DocType::class, 'doc_type', 'id')->select('id', 'title');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'first_name','email');
    }

    
}

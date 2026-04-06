<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

protected $fillable = [

'parent_id',
'label_id',
'label_en',
'type',
'page_id',
'news_id',
'url',
'sort_order',
'is_active'

];


public function parent()
{
return $this->belongsTo(Menu::class,'parent_id');
}


public function children()
{
return $this->hasMany(Menu::class,'parent_id');
}


public function page()
{
return $this->belongsTo(Page::class,'page_id');
}


public function news()
{
return $this->belongsTo(News::class,'news_id');
}

}
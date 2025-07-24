<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Str;
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    protected $fillable = ['title','description','user_id'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    protected static function boot(){
        parent::boot();
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
            $count=1;
            $originalSlug= $post->slug;

        /* the code below ensures uniqueness of the slug. if there exists a slug of the same value,
         then the others will be appended with numbers unicrementally*/
        while(Post::where('slug',$post->slug)->exists()){
            $post->slug = $originalSlug.'-'.$count++;
        }
        /* the code below ensures the slug changes if the title changes */
        static::updating(function ($post){
            $post->slug = Str::slug($post->title);
        });
        });
    }
}
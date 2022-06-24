<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Define /admin routes
Route::middleware('auth')->prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::resource('posts', 'PostController')->parameters([
        'posts' => 'post:slug'
    ]);
    Route::resource('categories', 'CategoryController')->parameters([
        'categories' => 'category:slug'
    ])->except(['show', 'create', 'edit']);
    Route::resource('tags', 'TagController')->parameters([
        'tags' => 'tag:slug'
    ])->except(['show', 'create', 'edit']);
});

//Route::get('/home', 'HomeController@index')->name('home');


Route::get('{any?}', function () {
    return view('guest.home');
})->where('any', '.*');


/* 
MANY TO MANY

- Creare modello 
php artisan make:model Models/Tag

- Entrare nel modello:
public function posts() : BelongsToMany { ->importare BelongsTo many
    return $this->belongsTo Many(Post::class);
}

- Entrare nel modello post

public function tags(): BelongsToMany ->importare 
{
    return $this->belongsToMany(Tag::class);
}

- php artisan make:migration create_tags_table (creare migrazione tabella tags)

- Entrare nella migrazione:

$table->string('name', 100)->unique();
$table->string('slug', 150)

- php artisan migrate

- php srtisan make:seeder TagSeeder

- Entrare nel seeder (importare Str, importare modello Tag)

 $tags = ['coding', 'laravel', 'css', 'Js', 'vue', 'sequel'];

        foreach ($tags as $tag) {
            $new_tag = new tag();
            $new_tag->name = $tag;
            $new_tag->slug = Str::slug($new_tag->name);
            $new_tag->save();
        }

- php artisan db:seed --class=TagSeeder

- php artisan tin
Tag::all
exit

- Creare la tabella pivot:
php artisan make:migration create_post_tag_table

Entrare nella migrazione(metodo up):
$table->unsignedBigInteger('post_id')->nullable();
$table->foreign('post_id)->references('id)->on('posts')->cascadeOnDelete();
$table->unsignedBigInteger('tag_id')->nullable();
$table->foreign('tag_id)->references('id)->on('tags')->cascadeOnDelete();

-php artisan migrate

- php artisan ti
$post = Post::find(18)

$tag = Tag::find(5)

$tag->posts()->attach($post->id) (oppure inserire numero del post nelle parentesi di attach)

$tag->posts

$tag->posts()->sync([1,2,3])
$tag->posts()->detach()

- Entrare nel PostController

passare la lista dei tag -> importare modello Tag
$tags = Tag::all()
passarli tramite compact

- Entrare nella view create:
fare un tag select

bs5 multi-select

for tags name tags[] id tags ara label tags
<option value "" disabled>Select Tags </option>
forelse($tags as $tag)
<option value="{{$tag->id}}>{{$tag->name}}</option>
@empty
<option value="">No Tags</option>
@endforelse

- Entrare nel post controller

metodo store

$new_post = Post::create($val_data);
$new_post->tags()->attach($request->tags);

- Mostrare tags nello show di post

aggiungere div tags
@if(count($post->tags >0 ))
    strong Tags:
    @foreach($post->tags as $tag)
    span #{{$tag->name}}
    @endforeach
@else
span N/A
@endif

- Validazione tags nel PostRequest
'tags' => [exists:tags,id],   RISOLVERE BUG

- Aggiungere select all'edit

bs5 multi-select

for tags name tags[] id tags ara label tags
<option value "" disabled>Select Tags </option>
forelse($tags as $tag)
<option value="{{$tag->id}}>{{$tag->name}}</option>
@empty
<option value="">No Tags</option>
@endforelse

- Passare tags nel metodo edit con il compact

- Aggiunger old nell'edit


@if($errors->any())
<option value"{{$tag->id}}" {{in_array($tag->id, old('tags)) ? 'selected' : ''}}> {{$tag->name}} >/option>
@else
<option value"{{$tag->id}}" {{$post->tags->contains($tag->id)) ? 'selected' : ''}}> {{$tag->name}} >/option>
@endif

- Validare tags nel metodo update

'tags' => 'exists:tags,id'

- Sync tags

$post->tags->sync($request->tags);

- IMPLEMENTARE CRUD PER I TAGS

- php artisan make:controller Admin/TagController -rm App/Models/Tag

- Metodo index

$tags = Tag::all();
return view('admin.tags.index, compact('tags))

- Creare le rotte

Route::resource('tag', 'tagController')->parameters([
        'tag' => 'tag:slug'
    ])->except(['show', 'create', 'edit']);

- crare view in admin

- Copiare html da index categories

-Nel tag controller copiare validazione e metodo store da conroller categories

- Copiare update e destroy da categories

- METTERE IN RELAZIONE UTENTI E POST

- User Model :

public function posts(): HasMany -> importare
{
    return $this->hasMany(Post::class)
}

- User Post

public function user() : BelongsTo
{
    return $this->belongsTo(User::class)

}

- php artisan make:migration add_user_id_to_posts_table

- entrare nella migrazione metodo up
$table->unsigendBigInteger('user_id')->nullable()->after('id);
$table->foreign('user_id')->reference('id')->on('user')->onDelete('set null');

-metodo down
$table->dropForeign('posts_users_id_foreign');
$table->dropColumn('user_id');

- php artisan migrate

- spostare User all'interno di Models e cambiare namespace
modifcare namespace nella registrazione App\Models\User

- associare post a noi stessi
php artisan ti

User::all()

Post::all()

$user_one_posts = Post::where('id', '<', 20)->get()
$user_two_posts = Post::where('id', '>', 20)->get()

$user_one = User::find(1)
$user_tow = User::find(2)

$user_one->posts 



test




*/


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


    FILE STORAGE

- entrare in config>filesystem 
- modificare in public
- tutte le immagine verranno caricate in storage>app>public 
- Fare link simbolico tra public e storage : php artisan storage:link
- Entrare nel file create dei post: cambiare l'input type in file ed eliminare l'old
- nel form aggiungere attributo enctype="multipart/form-data"
- nel metodo store verificare se la richiesta contiene il file, validare il file, salvarlo nel filesystem, recuperare la path,passare la path all'array di dati validati per il salvataggio:
    
    //ddd($request->hasFile('cover_image')); OPZIONE 1
    
    OPZIONE 2
    if(array_key_exists('cover_image', $request->all())){
        //valido il file
        $request->validate([
            'cover_image' => 'nullable|image|max:50'
        ]);
        //Lo salvo nel file system
        ddd($request->all());
        $path = Storage::put('post_images', $request->cover_image);
        //recupero il percorso
        //ddd($path);
        //passare la path all'array di dati validati per il salvataggio:
    
        $val_data['cover_image'] = $path;
    }

    //ddd($val_data);

    - Entrare nella view index per trasformare link allimmagine:
        {{asset('storage/' . $post->cover_image)}}

    - Entrare nella view show e ripetere operazione

    IMPLEMENTARE METODO EDIT

    - Entrare nella view edit

    - Modificare input in file ed elimnare value

    - nell'img {{asset('storage/' . $post->cover_image)}}

    - nel form aggiungere attributo enctype="multipart/form-data"

    VALIDAZIONE NEL POSTCONTROLLER
    
    if(array_key_exists('cover_image', $request->all())){
        //valido il file
        $request->validate([
            'cover_image' => 'nullable|image|max:50'
        ]);
        //Lo cancello dal file system
        Storage::delete($post->cover_image);

        //ddd($request->all());
        $path = Storage::put('post_images', $request->cover_image);
        //recupero il percorso
        //ddd($path);

        //passare la path all'array di dati validati per il salvataggio:
        $val_data['cover_image'] = $path;
    }

    //ddd($val_data);


    IMPLEMENTARE METODO DELETE

     Storage::delete($post->cover_image);
     $post->delete();

     return ..........


     INVIO DELLE MAIL

     - Andare nel file .env e incollare i dati da mailtrap.io

     - Stoppare server e riavviarlo

     - Creare oggetto mailable: php artisan make:mail NewPostCreated

     - Cartella App>Mail passare:

     $public $post;

     nel costruttore
     $this->post = $post

     nella build

     return $this
     ->from('example.com')
     ->subject('A new post created')
     ->view('mail.posts-created')

     - Nelle views creare cartalla mail
     - Cartella posts
     - File created.blade.php
     - Mettere html con h1
        p con strong title: {{$post->title}}          
     
     - Entrare nel post controller

     - Nel metodo store

     return new NewPostcreated($new_post)->render(); ANTEPRIMA EMAIL

     - Entrare nel post controller e inviare mail

     Mail::to($request->user())->send(NewPostcreated($new_post));

     OPPURE

     Mail::to('test@example.com')->send(NewPostcreated($new_post));

     FARE NUOVA MAILABLE APPROVAZIONE AMMINISTRATORE

     - php artisan make mailable PostUpdatedAdminMessage --markdown=mail.markdown.admin-postupdated

     - Entrare in views>mail>markdown e inserire corpo della mail

     - Entrare nel PostUpdated
     protected $post;

     nel costruttore 
     $this->post = $post;

     nel build 
     return $this 
     ->markdown('mail.markdown.admin-postupdated')
     ->with([
        'postSlug'  => $this->post->slug,
        'postUrl' => env('APP_URL') . '/posts/' . $this->post->slug
     ])

     - Nel metodo updated

     //return (new PostUpdatedAdminMessage($post))->render();

     Mail::to('admin@boolpress.it')->send(new PostUpdatedAdminMessage($post));

     - Creare rotta
     Route::get('mailable', function(){
        $post = Post::findOrFail(1);

        return new PostUpdatedAdminMessage($post);
     })


     ADMIN RULES

     nel metodo index 
     $posts = Auth::user()->posts;







*/


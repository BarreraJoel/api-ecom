<?php

namespace App\Models;

use App\Http\Resources\UserResource;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'image_url',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function toResource() {
        return new UserResource($this);
    }

    public function updateImage(UploadedFile $file)
    {
        $fileService = new FileService();
        $filename = $fileService->generateFileName($this->id);
        $path = $fileService->upload($file, '/users/images', $filename);
        $this->image_url = $path;
        return $this->save();
    }

    public function roles()
    {
        return $this->hasOne(Role::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'carts')
            ->withPivot(['quantity', 'unit_price']);
    }
}

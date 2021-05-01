<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Image;

trait HasAvatar
{
    /**
     * Get the model's avatar.
     *
     * @return string
     */
    public function getAvatarAttribute()
    {
        if ($this->attributes['avatar']) {
            return $this->attributes['avatar'];
        }

        return 'https://via.placeholder.com/150';
    }

    /**
     * Save an image to the avatars folder.
     *
     * @return void
     */
    public function saveAvatar(UploadedFile $file)
    {
        $date = now()->format('Y-m-d');
        $filename = time().'.'.$file->getClientOriginalExtension();
        $image = Image::make($file->getRealPath());
        $image->fit(150, 150);
        $filepath = public_path("uploads/{$filename}");
        $image->save($filepath);

        $this->avatar = "/uploads/{$filename}";
        $this->save();
    }
}

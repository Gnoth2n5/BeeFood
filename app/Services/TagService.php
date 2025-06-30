<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    /**
     * Create a new tag.
     */
    public function create(array $data): Tag
    {
        $tag = new Tag($data);
        $tag->slug = Str::slug($data['name']);
        $tag->save();

        return $tag;
    }

    /**
     * Update an existing tag.
     */
    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);
        $tag->slug = Str::slug($data['name']);
        $tag->save();

        return $tag;
    }

    /**
     * Delete a tag.
     */

} 
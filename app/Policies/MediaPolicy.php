<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Media;

class MediaPolicy
{
    /**
     * Determine if the user can view any media.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('media.view');
    }

    /**
     * Determine if the user can view the media.
     */
    public function view(User $user, Media $media): bool
    {
        // Public media visible to anyone with media.view permission
        if ($media->visibility === 'public') {
            return $user->can('media.view');
        }

        // Private media only visible to owner or those with media.manage permission
        return $user->id === $media->owner_id || $user->can('media.manage');
    }

    /**
     * Determine if the user can upload media.
     */
    public function create(User $user): bool
    {
        return $user->can('media.upload');
    }

    /**
     * Determine if the user can update the media.
     */
    public function update(User $user, Media $media): bool
    {
        // Owner can update their own media
        if ($user->id === $media->owner_id) {
            return true;
        }

        // Others need media.manage permission
        return $user->can('media.manage');
    }

    /**
     * Determine if the user can delete the media.
     */
    public function delete(User $user, Media $media): bool
    {
        // Owner can delete their own media
        if ($user->id === $media->owner_id) {
            return true;
        }

        // Others need media.delete permission
        return $user->can('media.delete');
    }
}

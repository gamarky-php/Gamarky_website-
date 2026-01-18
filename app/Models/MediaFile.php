<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'media_library';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'disk',
        'path',
        'filename',
        'original_name',
        'mime_type',
        'extension',
        'size_bytes',
        'width',
        'height',
        'alt',
        'caption',
        'tags',
        'is_public',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tags' => 'array',
        'meta' => 'array',
        'is_public' => 'boolean',
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * Get the user who uploaded the file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL of the media file.
     */
    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Get the file type (image, video, document).
     */
    public function getTypeAttribute()
    {
        if (str_starts_with($this->mime_type, 'image/')) {
            return 'image';
        }
        if (str_starts_with($this->mime_type, 'video/')) {
            return 'video';
        }
        return 'document';
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSizeAttribute()
    {
        $bytes = $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < 4; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope a query to only include public files.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to only include images.
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * Scope a query to only include videos.
     */
    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'like', 'video/%');
    }

    /**
     * Scope a query to only include documents.
     */
    public function scopeDocuments($query)
    {
        return $query->where('mime_type', 'not like', 'image/%')
                     ->where('mime_type', 'not like', 'video/%');
    }
}

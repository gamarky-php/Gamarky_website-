<?php

namespace App\Livewire\Shared;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaLibrary extends Component
{
    use WithPagination, WithFileUploads;

    public $files = [];
    public $filterType = ''; // image | document | video
    public $search = '';

    protected $rules = [
        'files.*' => 'required|max:10240', // 10MB max
    ];

    public function uploadFiles()
    {
        $this->validate();

        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();
            $path = $file->store('media-library', 'public');
            $filename = basename($path);
            $extension = $file->getClientOriginalExtension();
            $sizeBytes = $file->getSize();
            $mimeType = $file->getMimeType();

            // Get image dimensions if it's an image
            $width = null;
            $height = null;
            if (str_starts_with($mimeType, 'image/')) {
                try {
                    $dimensions = getimagesize($file->getRealPath());
                    if ($dimensions) {
                        $width = $dimensions[0];
                        $height = $dimensions[1];
                    }
                } catch (\Exception $e) {
                    // Ignore if can't get dimensions
                }
            }

            DB::table('media_library')->insert([
                'user_id' => auth()->id(),
                'disk' => 'public',
                'path' => $path,
                'filename' => $filename,
                'original_name' => $originalName,
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size_bytes' => $sizeBytes,
                'width' => $width,
                'height' => $height,
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->files = [];
        session()->flash('success', 'تم رفع الملفات بنجاح');
    }

    public function deleteFile($fileId)
    {
        $file = DB::table('media_library')->where('id', $fileId)->first();
        
        if ($file) {
            Storage::disk('public')->delete($file->path);
            DB::table('media_library')->where('id', $fileId)->delete();
            session()->flash('success', 'تم حذف الملف بنجاح');
        }
    }

    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        return 'document';
    }

    public function getMediaFilesProperty()
    {
        $query = DB::table('media_library')
            ->orderBy('created_at', 'desc');

        if ($this->filterType) {
            // Filter by mime_type prefix
            if ($this->filterType === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($this->filterType === 'video') {
                $query->where('mime_type', 'like', 'video/%');
            } elseif ($this->filterType === 'document') {
                $query->where('mime_type', 'not like', 'image/%')
                      ->where('mime_type', 'not like', 'video/%');
            }
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('filename', 'like', '%' . $this->search . '%')
                  ->orWhere('original_name', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate(24);
    }

    public function render()
    {
        return view('livewire.shared.media-library', [
            'mediaFiles' => $this->mediaFiles,
        ]);
    }
}

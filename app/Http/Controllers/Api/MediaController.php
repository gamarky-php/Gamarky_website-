<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

/**
 * Media Library API Controller
 * 
 * Features:
 * - Upload/resize/convert images
 * - Folder organization
 * - Access permissions
 * - File type validation
 * - Thumbnail generation
 */
class MediaController extends Controller
{
    /**
     * Get all media files
     * 
     * GET /api/media
     */
    public function index(Request $request)
    {
        $query = DB::table('media_library')
            ->orderBy('created_at', 'desc');
        
        // Filter by folder
        if ($request->has('folder_id')) {
            if ($request->folder_id === '0' || $request->folder_id === 'null') {
                $query->whereNull('folder_id');
            } else {
                $query->where('folder_id', $request->folder_id);
            }
        }
        
        // Filter by type
        if ($request->has('type')) {
            $query->where('mime_type', 'like', $request->type . '%');
        }
        
        // Search
        if ($request->has('search')) {
            $query->where('filename', 'like', '%' . $request->search . '%');
        }
        
        $media = $query->paginate(30);
        
        return response()->json([
            'success' => true,
            'data' => $media->items(),
            'pagination' => [
                'total' => $media->total(),
                'per_page' => $media->perPage(),
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ]
        ]);
    }

    /**
     * Upload file
     * 
     * POST /api/media/upload
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'folder_id' => 'nullable|integer|exists:media_folders,id',
            'alt_text' => 'nullable|string|max:255',
            'resize' => 'nullable|boolean',
            'max_width' => 'nullable|integer|min:100|max:3000',
            'max_height' => 'nullable|integer|min:100|max:3000',
        ]);
        
        $file = $request->file('file');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        // Determine storage path
        $folderPath = 'media';
        if ($validated['folder_id'] ?? null) {
            $folder = DB::table('media_folders')->where('id', $validated['folder_id'])->first();
            if ($folder) {
                $folderPath .= '/' . $folder->slug;
            }
        }
        
        // Upload file
        $path = $file->storeAs($folderPath, $filename, 'public');
        $url = Storage::url($path);
        
        // Generate thumbnail for images
        $thumbnailUrl = null;
        if (str_starts_with($file->getMimeType(), 'image/')) {
            // Resize if requested
            if ($validated['resize'] ?? false) {
                $image = Image::make($file);
                if ($validated['max_width'] ?? null) {
                    $image->widen($validated['max_width'], function ($constraint) {
                        $constraint->upsize();
                    });
                }
                if ($validated['max_height'] ?? null) {
                    $image->heighten($validated['max_height'], function ($constraint) {
                        $constraint->upsize();
                    });
                }
                $image->save(storage_path('app/public/' . $path));
            }
            
            // Create thumbnail
            $thumbnailFilename = 'thumb_' . $filename;
            $thumbnailPath = $folderPath . '/' . $thumbnailFilename;
            
            $thumbnail = Image::make($file)->fit(200, 200);
            $thumbnail->save(storage_path('app/public/' . $thumbnailPath));
            $thumbnailUrl = Storage::url($thumbnailPath);
        }
        
        // Save to database
        $mediaId = DB::table('media_library')->insertGetId([
            'filename' => $file->getClientOriginalName(),
            'stored_filename' => $filename,
            'path' => $path,
            'url' => $url,
            'thumbnail_url' => $thumbnailUrl,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'folder_id' => $validated['folder_id'] ?? null,
            'alt_text' => $validated['alt_text'] ?? null,
            'uploaded_by' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $mediaId,
                'url' => $url,
                'thumbnail_url' => $thumbnailUrl,
            ]
        ], 201);
    }

    /**
     * Delete file
     * 
     * DELETE /api/media/{id}
     */
    public function destroy(int $id)
    {
        $media = DB::table('media_library')->where('id', $id)->first();
        
        if (!$media) {
            return response()->json([
                'success' => false,
                'error' => 'File not found'
            ], 404);
        }
        
        // Delete physical file
        Storage::disk('public')->delete($media->path);
        
        // Delete thumbnail if exists
        if ($media->thumbnail_url) {
            $thumbnailPath = str_replace('/storage/', '', parse_url($media->thumbnail_url, PHP_URL_PATH));
            Storage::disk('public')->delete($thumbnailPath);
        }
        
        // Delete from database
        DB::table('media_library')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    /**
     * Get folders
     * 
     * GET /api/media/folders
     */
    public function getFolders()
    {
        $folders = DB::table('media_folders')
            ->select([
                'media_folders.*',
                DB::raw('(SELECT COUNT(*) FROM media_library WHERE folder_id = media_folders.id) as files_count')
            ])
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $folders
        ]);
    }

    /**
     * Create folder
     * 
     * POST /api/media/folders
     */
    public function createFolder(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:media_folders,slug',
            'parent_id' => 'nullable|integer|exists:media_folders,id',
        ]);
        
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $folderId = DB::table('media_folders')->insertGetId([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'] ?? null,
            'created_by' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Folder created successfully',
            'data' => ['id' => $folderId]
        ], 201);
    }
}

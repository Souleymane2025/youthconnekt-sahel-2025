<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Obtenir les statistiques du dashboard
     */
    public function getStats()
    {
        try {
            $stats = [
                'participants' => [
                    'total' => Participant::count(),
                    'pending' => Participant::where('status', 'pending')->count(),
                    'confirmed' => Participant::where('status', 'confirmed')->count(),
                    'rejected' => Participant::where('status', 'rejected')->count(),
                ],
                'blogs' => [
                    'total' => Blog::count(),
                    'published' => Blog::where('status', 'published')->count(),
                    'draft' => Blog::where('status', 'draft')->count(),
                ],
                'partners' => [
                    'total' => 0, // À implémenter si nécessaire
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
                'sponsors' => [
                    'total' => 0, // À implémenter si nécessaire
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
                'stands' => [
                    'total' => 0, // À implémenter si nécessaire
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
                'messages' => [
                    'total' => 0, // À implémenter si nécessaire
                    'unread' => 0,
                    'read' => 0,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les participants avec pagination
     */
    public function getParticipants(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 50);
            $participants = Participant::latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $participants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des participants: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour le statut d'un participant
     */
    public function updateParticipantStatus(Request $request, $id)
    {
        try {
            $participant = Participant::findOrFail($id);
            $status = $request->validate(['status' => 'required|in:pending,confirmed,rejected'])['status'];
            
            $participant->update(['status' => $status]);

            return response()->json([
                'success' => true,
                'message' => 'Statut du participant mis à jour',
                'data' => $participant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les blogs
     */
    public function getBlogs(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 25);
            $blogs = Blog::latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $blogs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des blogs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau blog
     */
    public function createBlog(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'nullable|string|max:120',
                'content' => 'required|string',
                'status' => 'nullable|string|in:draft,published'
            ]);

            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
            $data['excerpt'] = \Illuminate\Support\Str::limit(strip_tags($data['content']), 180);
            $data['status'] = $data['status'] ?? 'draft';
            $data['author_name'] = 'Admin';

            $blog = Blog::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Blog créé avec succès',
                'data' => $blog
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du blog: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un blog
     */
    public function updateBlog(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $data = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'category' => 'nullable|string|max:120',
                'content' => 'sometimes|required|string',
                'status' => 'nullable|string|in:draft,published'
            ]);

            if (isset($data['title'])) {
                $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
            }
            if (isset($data['content'])) {
                $data['excerpt'] = \Illuminate\Support\Str::limit(strip_tags($data['content']), 180);
            }

            $blog->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Blog mis à jour avec succès',
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un blog
     */
    public function deleteBlog($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Blog supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publier un blog
     */
    public function publishBlog($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->update(['status' => 'published', 'published_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Blog publié avec succès',
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication: ' . $e->getMessage()
            ], 500);
        }
    }
}



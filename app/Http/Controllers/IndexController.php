<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Index;
use App\Models\Partenaire;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class IndexController extends Controller
{
    public function index()
    {
        // récupérer le post pour la page d'accueil
        $indexPost = Index::latest()->first();


        // $pageId = env('FACEBOOK_PAGE_ID');
        // $accessToken = env('FACEBOOK_ACCESS_TOKEN');
        // $post = null;

        // // Récupération du dernier post
        // $response = Http::get("https://graph.facebook.com/v22.0/{$pageId}/posts", [
        //     'fields' => 'message,created_time,permalink_url,attachments{subattachments{media},media},likes.summary(true),comments.summary(true),reactions.summary(true)',
        //     'limit' => 1,
        //     'access_token' => $accessToken,
        // ]);

        // $data = $response->json()['data'][0] ?? null;

        // if ($data) {
        //     $postId = $data['id'];

        //     // Récupération détaillée des réactions
        //     $reactionsResponse = Http::get("https://graph.facebook.com/v22.0/{$postId}/reactions", [
        //         'fields' => 'type',
        //         'limit' => 1000,
        //         'access_token' => $accessToken,
        //     ]);

        //     $reactionsData = $reactionsResponse->json()['data'] ?? [];

        //     // Compter chaque type de réaction
        //     $reactionCounts = [
        //         'LIKE' => 0, 'LOVE' => 0, 'CARE' => 0, 'HAHA' => 0,
        //         'WOW' => 0, 'SAD' => 0, 'ANGRY' => 0
        //     ];

        //     foreach ($reactionsData as $reaction) {
        //         $type = $reaction['type'];
        //         if (isset($reactionCounts[$type])) {
        //             $reactionCounts[$type]++;
        //         }
        //     }

        //     $post = [
        //         'message' => $data['message'] ?? '(Pas de texte dans ce post)',
        //         'permalink_url' => $data['permalink_url'] ?? '#',
        //         'likes' => $data['likes']['summary']['total_count'] ?? 0,
        //         'comments' => $data['comments']['summary']['total_count'] ?? 0,
        //         'reactions' => [
        //             '👍' => $reactionCounts['LIKE'],
        //             '❤️' => $reactionCounts['LOVE'],
        //             '🤗' => $reactionCounts['CARE'],
        //             '😂' => $reactionCounts['HAHA'],
        //             '😲' => $reactionCounts['WOW'],
        //             '😢' => $reactionCounts['SAD'],
        //             '😡' => $reactionCounts['ANGRY'],
        //         ],
        //         'images' => [],
        //     ];

        //     // Vérifier les images
        //     if (!empty($data['attachments']['data'])) {
        //         foreach ($data['attachments']['data'] as $attachment) {
        //             if (!empty($attachment['media']['image']['src'])) {
        //                 $post['images'][] = $attachment['media']['image']['src'];
        //             }
        //             if (!empty($attachment['subattachments']['data'])) {
        //                 foreach ($attachment['subattachments']['data'] as $subattachment) {
        //                     if (!empty($subattachment['media']['image']['src'])) {
        //                         $post['images'][] = $subattachment['media']['image']['src'];
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //     $post['images'] = array_unique($post['images']);
        // }

        // // Récupérer le post en favoris (s'il y en a un)
        // $favoriPost = Post::where('favoris', true)->first();

        

        $partenaires = Partenaire::get();

        $banniere = SiteSetting::first();

        return view('index', [
            'indexPost' => $indexPost,
            // 'post' => $post,
            // 'favoriPost' => $favoriPost,
            'partenaires' => $partenaires,
            'banniere' => $banniere
        ]);
    }

    private function extractImages($attachments)
    {
        $images = [];
        foreach ($attachments as $attachment) {
            if (!empty($attachment['media']['image']['src'])) {
                $images[] = $attachment['media']['image']['src'];
            }

            if (!empty($attachment['subattachments']['data'])) {
                foreach ($attachment['subattachments']['data'] as $subattachment) {
                    if (!empty($subattachment['media']['image']['src'])) {
                        $images[] = $subattachment['media']['image']['src'];
                    }
                }
            }
        }

        return array_unique($images);
    }

    public function index_post()
    {
        

        return view('index', compact('indexPost'));
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Services\AIServiceInterface;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AIServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show the chatbot interface
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Process a chatbot query
     */
    public function query(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $query = $request->input('message');
        $result = $this->aiService->searchProducts($query);

        // Process the AI response to extract and replace product IDs with actual links
        $processedResponse = $this->processAiResponse($result['response'], $result['products']);

        return response()->json([
            'message' => $processedResponse,
            'products' => $result['products']->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'url' => route('product.view', $product->slug),
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                ];
            }),
        ]);
    }

    /**
     * Process the AI response to extract and replace product IDs with actual links
     */
    protected function processAiResponse(string $response, $products): string
    {
        // Replace product IDs with links using a regular expression
        $processedResponse = preg_replace_callback(
            '/\[ID:(\d+)\]/',
            function ($matches) use ($products) {
                $productId = $matches[1];
                $product = $products->firstWhere('id', $productId);
                
                if ($product) {
                    $url = route('product.view', $product->slug);
                    return "<a href=\"{$url}\" class=\"text-blue-500 hover:underline\" target=\"_blank\">{$product->title}</a>";
                }
                
                return $matches[0]; // Return the original if product not found
            },
            $response
        );

        // Convert Markdown to HTML for better display
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        
        // But preserve the HTML links we just created
        // First, temporarily replace our HTML links with placeholders
        $linkPlaceholders = [];
        $processedResponse = preg_replace_callback(
            '/<a href="([^"]+)" class="([^"]+)" target="([^"]+)">([^<]+)<\/a>/',
            function ($matches) use (&$linkPlaceholders) {
                $placeholder = '{{LINK_' . count($linkPlaceholders) . '}}';
                $linkPlaceholders[] = $matches[0];
                return $placeholder;
            },
            $processedResponse
        );
        
        // Convert markdown to HTML
        $processedResponse = $converter->convert($processedResponse)->getContent();
        
        // Put the HTML links back
        foreach ($linkPlaceholders as $i => $link) {
            $processedResponse = str_replace('{{LINK_' . $i . '}}', $link, $processedResponse);
        }

        return $processedResponse;
    }
} 
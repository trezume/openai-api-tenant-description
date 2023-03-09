<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    public function chat(Request $request)
    {
        $text = $request->input('message');

        $client = new Client();

        $response = $client->request('POST', 'https://api.openai.com/v1/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' .env('OPENAI_API_KEY'),
            ],
            'json' => [
                'model' => 'text-davinci-001',
                'prompt' => 'Write me a good tenant bio caption in my tenant resume to attract landlord, starts with i am ',
                'temperature' => 0.5,
                'max_tokens' => 100,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ],
        ]);

        // Get the response from the OpenAI API
        $result = json_decode($response->getBody(), true);
        $text = $result['choices'][0]['text'];

        // Return the chatbot's response
        return response()->json(['message' => $text]);
    }
}
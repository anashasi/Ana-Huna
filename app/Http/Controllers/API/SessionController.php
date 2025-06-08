<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Models\TranslationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class SessionController extends Controller
{

    public function signtotext(Request $request) {
        $llm = filter_var($request->input('LLM'), FILTER_VALIDATE_BOOLEAN);
        return Inertia::render('CameraPage',[
            'LLM'=>$llm
        ]); 
    }


    public function generateSpeech(Request $request)
    {
        $apiKey = '5d6Xb9ddk8GoQrmpYnsaEMyh4iIWMseT2kDEQJprml6XH7iy1hKrJQQJ99BDACFcvJRXJ3w3AAAYACOGMuAm';
        $region = 'qatarcentral';

        $text = $request->input('text');

        // First, define the SSML string
                    $ssml = <<<XML
            <speak version='1.0' xml:lang='ar-SA'>
            <voice xml:lang='ar-JO' xml:gender='Male' name='ar-JO-TaimNeural'>
                $text
            </voice>
            </speak>
        XML;

        // Now, use it
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $apiKey,
            'Content-Type' => 'application/ssml+xml',
            'X-Microsoft-OutputFormat' => 'audio-16khz-32kbitrate-mono-mp3',
        ])
        ->withBody($ssml, 'application/ssml+xml')
        ->post("https://$region.tts.speech.microsoft.com/cognitiveservices/v1");

        if ($response->successful()) {
            return response($response->body(), 200)->header('Content-Type', 'audio/mpeg');
        } else {
            return response()->json(['error' => 'Failed to generate speech'], 500);
        }
    }

    
}

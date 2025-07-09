<?php

return [

    'required' => ':attribute फ़ील्ड आवश्यक है।',
    'string' => ':attribute एक मान्य स्ट्रिंग होनी चाहिए।',
    'numeric' => ':attribute एक मान्य संख्या होनी चाहिए।',
    'integer' => ':attribute एक पूर्णांक होना चाहिए।',
    'max' => [
        'string' => ':attribute :max अक्षरों से अधिक नहीं हो सकती।',
    ],
    'min' => [
        'string' => ':attribute कम से कम :min अक्षर लंबी होनी चाहिए।',
        'integer' => ':attribute कम से कम :min होना चाहिए।',
    ],

    'attributes' => [
        'name' => 'योजना का नाम',
        'duration' => 'अवधि',
        'price' => 'मूल्य',
        'objective' => 'उद्देश्य',
        'summary' => 'सारांश',
        'paper_limit' => 'प्रस्तुत पत्रों की सीमा',
        'download_limit' => 'डाउनलोड सीमा',
    ],

];

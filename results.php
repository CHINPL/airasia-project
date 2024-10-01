<?php
// Define the API URL
$api_url = "https://flights.airasia.com/web/fp/search/flights/v5/aggregated-results";

// Define the parameters you want to send
$params = [
    'type' => 'paired',
    'isPromoMessagesByCode' => 'true',
    'include_list' => 'searchResults,content,currency,vouchers,upsellSnap,upsellPremiumFlatBed',
    'airlineProfile' => 'all',
    'isOriginCity' => 'false',
    'isDestinationCity' => 'false',
    'uce' => 'true',
    'page' => '1'
];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $depart_date = $_POST['depart_date'];
    $return_date = $_POST['return_date'];
    $formatted_date = DateTime::createFromFormat('Y-m-d', $depart_date)->format('d/m/Y');
    echo "<h4>You selected from  " . $departure . " to " . $arrival . " at " . $formatted_date . "</h4>";

    // Create the request payload (this contains your flight search details)
    $request_payload = [
        "consumerId" => "ListingAPP",
        "flightJourney" => [
            "journeyType" => "O",
            "journeyDetails" => [
                [
                    "departDate" => $formatted_date,
                    "origin" => $departure,
                    "destination" => $arrival
                ]
            ],
            "passengers" => [
                "adult" => 1,
                "child" => 0,
                "infant" => 0
            ]
        ],
        "searchContext" => [
            "promocode" => "",
            "sort" => "best",
            "filters" => [
                "cabin" => [
                    "applyMixedClasses" => false,
                    "cabinClass" => "ECONOMY"
                ],
                "carriers" => [
                    "allowAllCarriers" => false,
                    "excludedCarriers" => [],
                    "onlyAllowedCarriers" => ["AK", "Z2", "D7", "FD", "QZ", "XJ", "KT"]
                ],
                "duration" => [
                    "maxStopoverTimeInHrs" => 0,
                    "maxTravelTimeInHrs" => 0,
                    "minStopoverTimeInHrs" => 0
                ],
                "stops" => [
                    "allowOvernight" => false,
                    "stopType" => "ANY"
                ]
            ]
        ],
        "userContext" => [
            "currency" => "MYR",
            "geoId" => "MY",
            "locale" => "en-gb",
            "platform" => "web"
        ]
    ];

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_payload)); // Set the request payload
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: 
    Bearer eyJraWQiOiJ6b2RpYWNmbGlnaHRzd2ViIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJvdGFAZmxpZ2h0cy5haXJhc2lhLmNvbSIsInN1YiI6Ik9UQUZsaWdodHMiLCJpYXQiOjE3Mjc3NjU2MzUsImV4cCI6MTcyNzc2NjUzNX0.AfA1k2H7bm9OZBZldRlAA1UI4Y20LFwkYf3sJ-k1WkjIMpoYf6B_6kLNFP7ZegY8EaxO_IsPQOKdIvSTLfSEYhrSmd3ldWw1VYfVqiFOSi8aNX1VGaviCc3Hy_pGR-cwBBXPGqM2-0tLpDk3LdBsICmYC6cQQJY9PtT_fO2LfIvmrk0W0IBQvtpDd1_nS9jCJ2hEPmfDU3FlLbArZB_14s48BC7aaJhnD_gKvVRxy-ZJI2NE-zazHplsm5REkwn82zAA4Cb3XstsUHDFQShSALcEKDghXFrS2JxGJMU3hmvX_5fQLxYDuYkbzEt9yiKxaAZ1dtU5u-iq8aLTLpEcjuGmMFvy_FCADgPorkdX8T-9oLzOku4f7i0qj1yksQ8XpokYeY1KHnOqvZsj7HkCWG561Q-NN7D6Mzypf4rKAoWXpa9hRPgydk9WMeuVF2lhy2Ii_5O5DaWkKwGeB_NnbRJNWAZqDzMvCTCwDDf7UR1kyRyX6p2tAYxuolGDmyyHs5B29znsaudNL-uT6S3RstGsS_viSC9pWPQN6LEvAbDmIa_gfurjgUOA2HiLTgFh8_MF7FDGk_AxbNqv3TMRS1Csc8clTCSYa5h-KmCfYOKgWDDsNyj9cznto1wJLhDCJ1Fgvr3y1C9JzoBHrhwSEWESlMSnj1DB7i6AlVHeG8Oa',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
        'Content-Type: application/json',
        'Channel_hash: c5e9028b4295dcf4d7c239af8231823b520c3cc15b99ab04cde71d0ab18d65bc',
        'Content-Length: ' . strlen(json_encode($request_payload))
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Decode and display the response
        $data = json_decode($response, true);
        echo '<pre>';
        // print_r($data); // Display the data
        echo "<h5>Sponsored Departure:</h5>";
        if (isset($data)) {
            $recommendtrips = $data['searchResults']['recommendedFlights'];
            foreach ($recommendtrips as $rtrip) {
                foreach ($rtrip['flightsList'] as $rflight) {
                    if (isset($rflight['priceBreakdown']['convertedBaseFare'])) {
                        // Convert the price and round up
                        $price = ceil($rflight['priceBreakdown']['convertedBaseFare']);
                    } else {
                        // Default to a specific value or handle null case
                        $price = ceil($rflight['convertedPrice']);
                    }
                    $departureTime = $rflight['flightDetails']['designator']['departureTime'];
                    $arrivalTime = $rflight['flightDetails']['designator']['arrivalTime'];

                    $dTime = new DateTime($departureTime);
                    $aTime = new DateTime($arrivalTime);

                    $formatted_departure = $dTime->format('d/m/Y H:i');
                    $formatted_arrival = $aTime->format('d/m/Y H:i');

                    $duration = $rflight['flightDetails']['duration'];
                    $hours = floor($duration / 3600);
                    $minutes = floor(($duration % 3600) / 60);

                    // Output the price and times
                    echo "Flight Price: " . $price . " MYR\n";
                    echo "Departure Time: " . $formatted_departure . "\n";
                    echo "Arrival Time: " . $formatted_arrival . "\n";
                    echo "Flight Duration: " . $hours . " h " . $minutes . " m\n";
                    echo "------------------------------------\n";
                }
            }
        } else {
            echo 'Time out';
        }
        echo "<h5>Other Departure:</h5>";
        if (isset($data)) {
            $trips = $data['searchResults']['trips'];
            foreach ($trips as $trip) {
                foreach ($trip['flightsList'] as $flight) {
                    // Extract the price and departure/arrival times
                    if (isset($flight['priceBreakdown']['convertedBaseFare'])) {
                        // Convert the price and round up
                        $price = ceil($flight['priceBreakdown']['convertedBaseFare']);
                    } else {
                        // Default to a specific value or handle null case
                        $price = ceil($flight['convertedPrice']);
                    }
                    $departureTime = $flight['flightDetails']['designator']['departureTime'];
                    $arrivalTime = $flight['flightDetails']['designator']['arrivalTime'];

                    $dTime = new DateTime($departureTime);
                    $aTime = new DateTime($arrivalTime);

                    $formatted_departure = $dTime->format('d/m/Y H:i');
                    $formatted_arrival = $aTime->format('d/m/Y H:i');

                    $duration = $flight['flightDetails']['duration'];
                    $hours = floor($duration / 3600);
                    $minutes = floor(($duration % 3600) / 60);

                    // Output the price and times
                    echo "Flight Price: " . $price . " MYR\n";
                    echo "Departure Time: " . $formatted_departure . "\n";
                    echo "Arrival Time: " . $formatted_arrival . "\n";
                    echo "Flight Duration: " . $hours . " h " . $minutes . " m\n";
                    echo "------------------------------------\n";
                }
            }
        } else {
            echo 'Time out';
        }
        echo '</pre>';
    }

    // Close cURL session
    curl_close($ch);
}
?>

</div>
</div>
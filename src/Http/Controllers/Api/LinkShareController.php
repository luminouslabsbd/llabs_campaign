<?php

namespace Luminouslabs\Installer\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class LinkShareController extends Controller
{
    public function userCampaignQrData(Request $request)
    {
        // Validate request inputs
        $validator = Validator::make($request->all(), [
            'OrderID' => 'required',
            'TenantID' => 'required',
            'CampaignID' => 'required',
            'ProductID' => 'required',
            'PurchaseValue' => 'required',
            'email' => 'required|email',
            'is_login' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            // Validation failed
            $errorResponse = [
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ];
            return response()->json($errorResponse, 422);
        }

        $member = DB::table('members')->where('email', $request['email'])->first();

        if ($member) {
            $jsonString = json_encode(json_decode($request->getContent()));
            $encryptedData = Crypt::encrypt($jsonString);

            $qrCode = $this->QrGenerator([
                'email' => $request['email'],
                'hash' => $encryptedData,
            ]);

            return $response = $qrCode->getData();
        } else {
            return response()->json(['message' => "User email don't found", "status" => 404], 404);
        }
    }

    public function getHashByTenantID(Request $request)
    {
        try {
            // Get the raw content from the request
            $rawData = $request->getContent();
            // dd($rawData);
            // Decode the raw JSON data
            $requestData = json_decode($rawData, true);
            // Convert the associative array to a JSON string
            $jsonString = json_encode($requestData);
            // Create a hash using Crift encrypt

            // Validate request inputs
            $validator = Validator::make($requestData, [
                'OrderID' => 'required',
                'TenantID' => 'required',
                'CampaignID' => 'required',
                'ProductID' => 'required',
                'PurchaseValue' => 'required',
                'email' => 'required|email',
                'is_login' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                // Validation failed
                $errorResponse = [
                    'status' => 'error',
                    'message' => 'Validation error.',
                    'errors' => $validator->errors(),
                ];
                return response()->json($errorResponse, 422); // Use a 422 status code for unprocessable entity
            }

            $member = DB::table('members')->where('email', $requestData['email'])->first();
            if ($member) {
                $user = Auth::loginUsingId($member->id);
                $encryptedData = Crypt::encrypt($jsonString);

                if ($encryptedData) {
                    $response = [
                        'status' => 200,
                        'hash' => $encryptedData,
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'status' => 202,
                        'error' => 'Data process error',
                    ];
                    return response()->json($response, 202);
                }
            } else {
                return response()->json(['message' => "User email don't found", "status" => 404], 404);
            }
        } catch (MethodNotAllowedHttpException $e) {
            // Handle the exception, e.g., by returning a custom response
            return response()->json(['error' => 'Method not allowed'], 405);
        }
    }

    //This will be call when QR is scaned
    public function QrCodeScaned($locale, $id)
    {
        // Check if the data already exists
        $existingQRCode = DB::table('hash_qr_code')->find($id);

        if ($existingQRCode) {
            $decodedData = json_decode(Crypt::decrypt($existingQRCode->hash));
            if (is_object($decodedData)) {
                // Get data as an array
                $codeContents = json_decode(json_encode($decodedData), true);
            } else {
                // $decodedData is already an array
                $codeContents = $decodedData;
            }

            $spinnerData = DB::table('spiner_data')->where('cam_id', $codeContents['CampaignID'])->get();

            $campaign = DB::table('campaigns')->find($codeContents['CampaignID']);
            $labels = [];
            $colors = [];
            $is_wining_label = [];
            foreach ($spinnerData as $key => $value) {
                if ($value->is_wining_label === 1) {
                    $winingLabels[] = $value->label_title; // Number of winning label
                } else {
                    $loseingLabels[] = $value->label_title;
                }
                $labels[] = $value->label_title;
                $colors[] = $value->label_color;
                $is_wining_label[] = $value->is_wining_label;
            }
            // dd($winingLabels);
            $arrayContainLabels = array_count_values($is_wining_label);
            // dd($arrayContainLabels);
            $numberOfZeros = $arrayContainLabels[0] ?? 0; //Number of false values
            $numberOfOnes = $arrayContainLabels[1] ?? 0; //Number of true values

            $winPercentage = round(100 / count($is_wining_label) * $numberOfOnes);
            $winningPercentage = $winPercentage;
            $losingPercentage = 100 - $winningPercentage;
            // Calculate the number of winning and losing labels based on percentages
            $numberOfWinningLabels = count($winingLabels);
            $numberOfLosingLabels = count($loseingLabels);
            // dd($numberOfWinningLabels);
            $member = DB::table('members')->where('email', $decodedData->email)->first();

            if ($member) {
                $user = Auth::loginUsingId($member->id);
                $username = $user->name;
                $available_spin = DB::table('member_spinner_count')
                    ->where('campaign_id', $codeContents['CampaignID'])
                    ->where('member_id', $user->id)
                    ->first();

                // Create an array with equal number of winning and losing labels
                $rewardArray = [];
                $labelCount = [];
                $timeToWin = round(($available_spin->total_spin * $winPercentage) / 100);
                // dd($timeToWin, $winPercentage);
                // Check if spins are available and prizes are not set
                if ($available_spin->remaining_spin > 0 && $available_spin->is_prizes_set === 0) {
                    for ($i = 1; $i <= $available_spin->total_spin; $i++) {
                        $label = null;
                        // Check if there are available winning labels and it's time to use them
                        if ($timeToWin > 0) {
                            // Get available winning labels with their remaining prize quantities
                            $availableWinningLabels = DB::table('spiner_data')
                                ->whereIn('label_title', $winingLabels)
                                ->where('available_prize', '>', 0)
                                ->pluck('label_title')
                                ->toArray();
                            // If there are available winning labels, choose one randomly
                            if (!empty($availableWinningLabels)) {
                                $label = $availableWinningLabels[array_rand($availableWinningLabels)];
                                $numberOfWinningLabels--;
                                $timeToWin--;
                            }
                        } else {
                            $availablelooseingLabels = DB::table('spiner_data')
                                ->whereIn('label_title', $loseingLabels)
                                ->where('available_prize', '>', 0)
                                ->pluck('label_title')
                                ->toArray();

                            if (!empty($availablelooseingLabels)) {
                                $label = $loseingLabels[array_rand($availablelooseingLabels)];
                            }
                        }

                        $rewardArray[] = [
                            'campaign_id' => $codeContents['CampaignID'],
                            'member_id' => $user->id,
                            'spinner_round' => $i,
                            'rewards' => $label ?? 'No Rewards found!'
                        ];

                        // Count label occurrences
                        if (!isset($labelCount[$label])) {
                            $labelCount[$label] = 1;
                        } else {
                            $labelCount[$label]++;
                        }

                        // Update the available prize quantity for the chosen label
                        DB::table('spiner_data')
                            ->where('label_title', $label)
                            ->where('available_prize', '>', 0)
                            ->decrement('available_prize', 1);
                    }

                    $dataInsertToDb = DB::table('campaign_member')->insert($rewardArray);
                    if ($dataInsertToDb === true) {
                        if ($available_spin) {
                            DB::table('member_spinner_count')
                                ->where('id', $available_spin->id)
                                ->update([
                                    'remaining_spin' => $available_spin->remaining_spin - $available_spin->total_spin
                                ]);
                        }

                        DB::table('member_spinner_count')
                            ->where('campaign_id', $codeContents['CampaignID'])
                            ->where('member_id', $user->id)
                            ->update([
                                'is_prizes_set' => true
                            ]);
                    };
                } else {
                    $previousData = DB::table('campaign_member')
                        ->where('campaign_id', $codeContents['CampaignID'])
                        ->where('member_id', $member->id)
                        ->get();
                }

                // $newObj = [
                //     'username' => $username,
                //     'spin_options' => [
                //         'labels' => $labels,
                //         'colors' => $colors
                //     ],
                //     'spinner_rewards' => !empty($rewardArray) ? $rewardArray : $previousData,
                //     'campaign_name' => $campaign->name,
                // ];

                // $codeContents['spinner_details'] = $newObj;

                $totalSpin = DB::table('campaign_member')
                    ->where('campaign_id',$codeContents['CampaignID'])
                    ->where('member_id',$member->id)
                    ->where('is_claimed',false)
                    ->count();

                $newObj = [
                    'available_spin' => $totalSpin,
                    'username' => $username,
                    'spin_options' => [
                        'labels' => $labels,
                        'colors' => $colors
                    ],
                    'spinner_rewards' => !empty($rewardArray) ? $rewardArray : $previousData,
                    'campaign_name' => $campaign->name,
                ];

                $codeContents['spinner_details'] = $newObj;

                MemberWallet::updateOrCreate(['member_id'=>$member->id,'hash_id' => $id],[
                    'member_id'=> $member->id,
                    'member_email' => $member->email,
                    'hash_id' => $id
                ]);

                $memberWallets = DB::table('member_wallets')
                    ->where('member_email',$member->email)
                    ->get();

                $allWallets = [];

                foreach ($memberWallets as $wallet){
                    $decodedata = $this->decodeHash($wallet->hash_id);

                    $totalAvailableCount = DB::table('campaign_member')
                        ->where('member_id',$wallet->member_id)
                        ->where('campaign_id',$decodedata->id)
                        ->where('is_claimed',false)
                        ->count();

                    $data = [
                        'campaingId' => $decodedata->id,
                        'memberId' => $member->id,
                        'campaingName'=>$decodedata->name,
                        'memberEmail' => $member->email,
                        'hashId' => $wallet->hash_id,
                        'availableSpin' => $totalAvailableCount,
                        'createdAt'=>$wallet->created_at
                    ];

                    $allWallets[]=$data;
                }

                $currentSpin = DB::table('campaign_member')
                    ->where('campaign_id',$codeContents['CampaignID'])
                    ->where('member_id',$member->id)
                    ->where('is_claimed',false)
                    ->first();
                $codeContents['currentSpin'] = $currentSpin;
                $codeContents['allWalletsData'] = $allWallets;

                return response()->json($codeContents, 200);
            } else {
                return response()->json(['message' => 'Users not found', 'status' => 404], 404);
            }
        } else {
            return response()->json(['message' => 'something went wrong', 'status' => 404], 404);
        }
    }

    public function decodeHash($id)
    {
        $hash = DB::table('hash_qr_code')->select('hash')->find($id)->hash;
        $decodeData = json_decode(Crypt::decrypt($hash));
        return DB::table('campaigns')->select('id','name')->find($decodeData->CampaignID);
    }

    public function countAvailableSpin($memberId,$campaginId)
    {
        $data = DB::table('campaign_member')
            ->where('member_id',$memberId)
            ->where('campaign_id',$campaginId)
            ->where('is_claimed',false)
            ->count();

        return $data;
    }

    public function makeQRCode($jsonString, $hash)
    {
        $decodedData = json_decode(Crypt::decrypt($hash));

        if (is_object($decodedData)) {
            $codeContents = json_decode(json_encode($decodedData), true);
        } else {
            $codeContents = $decodedData;
        }
        $e = DB::table('hash_qr_code')->insertGetId([
            'order_id' => $decodedData->OrderID,
            'hash' => $hash,
            'qr_code_path' => 'a'
        ]);
        $tempDir = storage_path('app/tmp/');

        // Ensure the target directory exists
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $qrCodeFileName = uniqid('qr_code_') . '.png';
        // Generate QR code
        QrCode::format('png')->size(300)->generate(route('qr-scaned', ['hash_id' => $e]), $tempDir . $qrCodeFileName);

        $storagePath = 'public/qrcodes/';
        $qrCodePath = $storagePath . $qrCodeFileName;

        if (!Storage::exists($storagePath)) {
            Storage::makeDirectory($storagePath);
        }
        Storage::move("tmp/{$qrCodeFileName}", $qrCodePath);

        $fullQRCodeUrl = asset(Storage::url('' . $qrCodePath));

        $isInserted = $this->hashDataStore($e, $fullQRCodeUrl);
        return $fullQRCodeUrl;
    }



    public function hashDataStore($e, $path)
    {
        if ($path) {
            $data = [
                'qr_code_path' => $path,
            ];
            DB::table('hash_qr_code')->where('id', $e)->update($data);
            return true;
        } else {
            return false;
        }
    }

    public function getHashUrl($requestData, $hash)
    {
        $getData = DB::table('hash_qr_code')->where('hash', $hash)->select('qr_code_path')->first();

        if ($getData != null) {
            $dataFromQR = json_decode(Crypt::decrypt($hash));
            $labels = DB::table('spiner_data')->where('cam_id', $dataFromQR->CampaignID)->get()->pluck('label_title')->toArray();
            // Get the currently authenticated user
            $user = Auth::user();
            // Get the username
            $username = $user->name;
            // Get the access token for the user (if using Passport)
            // $accessToken = $user->createToken('token-for-spin');
            $response = [
                'spin_options' => $labels,
                // 'auth_token' => $accessToken->plainTextToken,
                'username' => $username,
                'status' => 200,
                'hash' => $hash,
                'path' => $getData->qr_code_path ?? '',
                'number' => $requestData['phone'],
            ];
            return $response;
        }
    }

    public function QrGenerator($request)
    {
        $requestData = $request;

        $validator = validator($requestData, [
            'hash' => 'required',
        ]);

        if ($validator->fails()) {
            $errorResponse = [
                'status' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ];
            return response()->json($errorResponse, 422);
        }

        $encryptedData = $requestData['hash'];

        // $getData = DB::table('hash_qr_code')->where('hash', $requestData['hash'])->select('qr_code_path')->first();

        // if ($getData != null) {
        // $member= DB::table('members')->where('email', $requestData['email'])->first();
        // $user = Auth::loginUsingId($member->id);
        // if (isset($user)) {
        //     $dataFromQR = json_decode(Crypt::decrypt($encryptedData));

        //     $labels = DB::table('spiner_data')->where('cam_id', $dataFromQR->CampaignID)->get()->pluck('label_title')->toArray();
        //     $point = DB::table('campaigns')->where('id', $dataFromQR->CampaignID)->get()->pluck('unit_price_for_point');
        //     $available_spin = round(intval($dataFromQR->PurchaseValue) / intval($point[0]));
        //     //Insert spinner count, how much time a user could spin and remaining spin
        //     DB::table('member_spinner_count')->updateOrInsert([
        //         'campaign_id' => $dataFromQR->CampaignID,
        //         'member_id' => $user->id,
        //         'total_spin' => $available_spin,
        //         'remaining_spin' => $available_spin,
        //     ]);

        //     $remaining_spin = DB::table('member_spinner_count')->where('campaign_id', $dataFromQR->CampaignID)->where('member_id', $user->id)->first();
        //     $username = $user->name;
        //     $accessToken = $user->createToken('token-for-spin');
        // $response = [
        //     'available_spin' => $remaining_spin->remaining_spin,
        //     'spin_options' => $labels,
        //     'auth_token' => $accessToken->plainTextToken,
        //     'username' => $username,
        //     'status' => 200,
        //     'hash' => $encryptedData,
        //     'path' => $getData->qr_code_path ?? '',
        //     // 'number' => $requestData['phone'],
        // ];

        // return response()->json($response, 200);
        // }
        // }

        $makeQrPath = $this->makeQRCode($requestData, $encryptedData);


        // $isInserted = $this->hashDataStore($dataFromQR, $encryptedData, $makeQrPath);

        if ($requestData['hash']) {

            $getData = DB::table('hash_qr_code')->where('hash', $requestData['hash'])->first();


            if (!$getData) {
                $response = [
                    'status' => 404,
                    'message' => 'Data not found!',
                ];
                return response()->json($response, 404);
            } else {
                $dataFromQR = json_decode(Crypt::decrypt($encryptedData));
                $member = DB::table('members')->where('email', $dataFromQR->email)->first();
                $user = Auth::loginUsingId($member->id);
                // dd($user);
                if (isset($user)) {
                    $dataFromQR = json_decode(Crypt::decrypt($encryptedData));
                    $labels = DB::table('spiner_data')->where('cam_id', $dataFromQR->CampaignID)->get()->pluck('label_title')->toArray();
                    $point = DB::table('campaigns')->where('id', $dataFromQR->CampaignID)->get()->pluck('unit_price_for_point');
                    $available_spin = round(intval($dataFromQR->PurchaseValue) / intval($point[0]));

                    //Insert spinner count, how much time a user could spin and remaining spin
                    $spinner_count = DB::table('member_spinner_count')
                        ->where('campaign_id', $dataFromQR->CampaignID)
                        ->where('member_id', $user->id)->first();
                    // dd($spinner_count, $user->id, $dataFromQR->CampaignID);
                    if (!$spinner_count) {
                        DB::table('member_spinner_count')->insert([
                            'campaign_id' => $dataFromQR->CampaignID,
                            'member_id' => $user->id,
                            'total_spin' => $available_spin,
                            'remaining_spin' => $available_spin,
                        ]);
                    }
                    $remaining_spin = DB::table('member_spinner_count')->where('campaign_id', $dataFromQR->CampaignID)->where('member_id', $user->id)->first();
                    $username = $user->name;
                    $accessToken = $user->createToken('token-for-spin');
                    $response = [
                        'available_spin' => $remaining_spin->remaining_spin,
                        'spin_options' => $labels,
                        'auth_token' => $accessToken->plainTextToken,
                        'username' => $username,
                        'status' => 200,
                        'hash' => $requestData['hash'],
                        'path' => $makeQrPath ?? '',
                    ];
                    return response()->json($response, 200);
                }
            }
        } else {
            $response = [
                'status' => 202,
                'error' => 'Wrong data format',
            ];
            return response()->json(
                $response,
                202
            );
        }
    }
}

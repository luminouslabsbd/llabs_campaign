<?php

namespace Luminouslabs\Installer\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class LinkShareController extends Controller
{

    //Will generate a hash
    public function getHashByTenantID(Request $request)
    {

        try {
            // Get the raw content from the request
            $rawData = $request->getContent();
            // Decode the raw JSON data
            $requestData = json_decode($rawData, true);

            //Make the user authenticate and get a auth token for further proceed.
            $user = DB::table('members')->where('email', $request['email'])->first();
            $authenticatedUser = Auth::loginUsingId($user->id);
            $accessToken = $authenticatedUser->createToken('token-for-spin');
            $requestData['access-token'] = $accessToken->plainTextToken;

            // Convert the associative array to a JSON string
            $jsonString = json_encode($requestData);
            // Create a hash using Crift encrypt

            // Validate request inputs
            $validator = Validator::make($requestData, [
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

            $encryptedData = Crypt::encrypt($jsonString);

            // $decryptedData = Crypt::decrypt($encryptedData);
            // $makeQrPath = $this->makeQRCode($requestData,$encryptedData);
            // $isInserted = $this->hashDataStore($requestData,$encryptedData,$makeQrPath);

            // $whatspp = $this->getHashUrl($requestData,$jsonString);

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
        } catch (MethodNotAllowedHttpException $e) {
            // Handle the exception, e.g., by returning a custom response
            return response()->json(['error' => 'Method not allowed'], 405);
        }
    }

    public function makeQRCode($jsonString, $hash)
    {
        // return response()->json($jsonString, 200);
        // Check if the data already exists
        $existingQRCode = DB::table('hash_qr_code')->where('hash', $hash)->first();
        if (isset($existingQRCode)) {
            return $fullQRCodeUrl = $existingQRCode->qr_code_path;
        } else {
            $tempDir = storage_path('app/tmp/');

            // Ensure the target directory exists
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // $whatsappLink = $this->getHashUrl($jsonString, $hash);
            // // Build raw data
            // $codeContents = $whatsappLink . "\n";
            // Assuming $hash contains the encrypted string
            $decodedData = json_decode(Crypt::decrypt($hash));

            if (is_object($decodedData)) {
                // Get data as an array
                $codeContents = json_decode(json_encode($decodedData), true);
            } else {
                // $decodedData is already an array
                $codeContents = $decodedData;
            }

            $spinnerData = DB::table('spiner_data')->where('cam_id', $codeContents['CampaignID'])->get();
            $campaign = DB::table('campaigns')->find($codeContents['CampaignID']);

            foreach ($spinnerData as $key => $value) {
                $labels[] = $value->label_title;
            }
            // $user = DB::table('members')->where('email', $res);
            // $username = $user->name;

            $newObj = [
                // 'username' => $username,
                'spin_options' => $labels,
                'campaign_name' => $campaign->name,
            ];

            $codeContents['campaign_details'] = $newObj;
            // Generate a unique identifier for the QR code filename
            $qrCodeFileName = uniqid('qr_code_') . '.png';

            // Generate QR code
            QrCode::format('png')->size(300)->generate(json_encode($codeContents), $tempDir . $qrCodeFileName);

            // Define the destination folder for QR codes
            $storagePath = 'public/qrcodes/';
            $qrCodePath = $storagePath . $qrCodeFileName;

            // Check if the destination folder exists, and create it if not
            if (!Storage::exists($storagePath)) {
                Storage::makeDirectory($storagePath);
            }
            // Move the generated QR code to the permanent storage location (qrcodes folder)
            Storage::move("tmp/{$qrCodeFileName}", $qrCodePath);

            $fullQRCodeUrl = asset(Storage::url('' . $qrCodePath));

            return $fullQRCodeUrl;
        }

    }

    public function hashDataStore($mainArray, $hash, $path)
    {

        if ($hash && $path) {
            $data = [
                // 'tenant_id' => $mainArray['TenantID'],
                // 'campaign_id' => $mainArray['CampaignID'],
                // 'product_id' => $mainArray['ProductID'],
                // 'purchase_value' => $mainArray['PurchaseValue'],
                'hash' => $hash,
                'qr_code_path' => $path,
            ];
            DB::table('hash_qr_code')->insert($data);
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

            $response = [
                'spin_options' => $labels,
                'status' => 200,
                'hash' => $hash,
                'path' => $getData->qr_code_path ?? '',
                'number' => $requestData['phone'],
            ];
            return $response;
        }

    }

    public function whatsappLinkGenerator(Request $request)
    {
        $rawData = $request->getContent();
        // Decode the raw JSON data
        $requestData = json_decode($rawData, true);
        // $dataFromQR = json_decode(Crypt::decrypt($requestData['hash']));

        // return response()->json($dataFromQR, 422);

        $validator = validator($requestData, [
            'hash' => 'required',
            'phone' => 'required',
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

        $encryptedData = $requestData['hash'];

        $getData = DB::table('hash_qr_code')->where('hash', $requestData['hash'])->select('qr_code_path')->first();

        if ($getData != null) {
            $dataFromQR = json_decode(Crypt::decrypt($encryptedData));

            $labels = DB::table('spiner_data')->where('cam_id', $dataFromQR->CampaignID)->get()->pluck('label_title')->toArray();
            $point = DB::table('campaigns')->where('id', $dataFromQR->CampaignID)->get()->pluck('unit_price_for_point');
            $available_spin = round(intval($dataFromQR->PurchaseValue) / intval($point[0]));

            //Insert spinner count, how much time a user could spin and remaining spin
            $user = DB::table('members')->where('email', $dataFromQR->email)->first();
            DB::table('member_spinner_count')->updateOrInsert([
                'campaign_id' => $dataFromQR->CampaignID,
                'member_id' => $user->id,
                'total_spin' => $available_spin,
                'remaining_spin' => $available_spin,
            ]);

            // Get the username
            $username = $user->name;
            // Get the access token for the user (if using Passport)
            $response = [
                'spin_options' => $labels,
                'username' => $username,
                'status' => 200,
                'hash' => $encryptedData,
                'path' => $getData->qr_code_path ?? '',
                'number' => $requestData['phone'],
            ];

            return response()->json($response, 200);
        }

        $makeQrPath = $this->makeQRCode($requestData, $encryptedData);

        $dataFromQR = json_decode(Crypt::decrypt($encryptedData));

        $isInserted = $this->hashDataStore($dataFromQR, $encryptedData, $makeQrPath);

        if ($requestData['phone'] && $requestData['hash']) {

            $getData = DB::table('hash_qr_code')->where('hash', $requestData['hash'])->first();

            if (!$getData) {
                $response = [
                    'status' => 404,
                    'message' => 'Data not found!',
                    'number' => $requestData['phone'],
                ];
                return response()->json($response, 404);
            }
        } else {
            $response = [
                'status' => 202,
                'error' => 'Wrong data format',
            ];
            return response()->json($response, 202);
        }
    }
}

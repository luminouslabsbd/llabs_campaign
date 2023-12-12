<?php

namespace Luminouslabs\Installer\Http\Controllers\Api;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkShareController extends Controller

{
    
    public function getHashByTenantID(Request $request){
        
        try{
            // Get the raw content from the request
            $rawData = $request->getContent();
            // Decode the raw JSON data
            $requestData = json_decode($rawData, true);
            // Convert the associative array to a JSON string
            $jsonString = json_encode($requestData);
            // Create a hash using Crift encrypt
            
            // Validate request inputs
            $validator = validator($requestData, [
                'TenantID' => 'required',
                'CampaignID' => 'required',
                'ProductID' => 'required',
                'PurchaseValue' => 'required',
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
            
            if($encryptedData){
                $response = [
                    'status' => 200 ,
                    'hash' => $encryptedData,
                ];
                return response()->json($response, 200);
            }else{
                $response = [
                    'status' => 202 ,
                    'error' => 'Data process error',
                ];
                return response()->json($response, 202);
            }
        }catch (MethodNotAllowedHttpException $e) {
        // Handle the exception, e.g., by returning a custom response
        return response()->json(['error' => 'Method not allowed'], 405);
    }
        
        
    }
    
    public function makeQRCode($jsonString,$hash)
    {
        
        // $TenantID = $jsonString['TenantID'];
        // $CampaignID = $jsonString['CampaignID'];
        // $ProductID = $jsonString['ProductID'];
        // $PurchaseValue = $jsonString['PurchaseValue'];
        
        $tempDir = storage_path('app/tmp/');
    
        // Ensure the target directory exists
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        $whatsappLink  = $this->getHashUrl($jsonString,$hash);
        // Build raw data
        $codeContents  =  $whatsappLink ."\n";
        // $codeContents  = 'TenantID:' . $whatsappLink . "\n";
        // $codeContents .= 'CampaignID:' . $CampaignID . "\n";
        // $codeContents .= 'ProductID:' . $ProductID . "\n";
        // $codeContents .= 'PurchaseValue:' . $PurchaseValue . "\n";
    
        // Generate a unique identifier for the QR code filename
        $qrCodeFileName = uniqid('qr_code_') . '.png';
    
        // Generate QR code
        QrCode::format('png')->size(300)->generate($codeContents, $tempDir . $qrCodeFileName);
    
        // Define the destination folder for QR codes
        $storagePath = 'public/qrcodes/';
        $qrCodePath = $storagePath . $qrCodeFileName;
        
        // Check if the destination folder exists, and create it if not
        if (!Storage::exists($storagePath)) {
            Storage::makeDirectory($storagePath);
        }
        // Move the generated QR code to the permanent storage location (qrcodes folder)
        Storage::move("tmp/{$qrCodeFileName}", $qrCodePath);
        
        $fullQRCodeUrl = asset(Storage::url(''.$qrCodePath));
        
        return $fullQRCodeUrl;
    }
    
    
    public function hashDataStore($mainArray,$hash,$path){
        
        if($hash && $path){
            $data = [
                // 'tenant_id' => $mainArray['TenantID'],
                // 'campaign_id' => $mainArray['CampaignID'],
                // 'product_id' => $mainArray['ProductID'],
                // 'purchase_value' => $mainArray['PurchaseValue'],
                'hash'   => $hash,
                'qr_code_path' => $path,
            ];
            DB::table('hash_qr_code')->insert($data);
            return true ;
        }else{
            return false ;
        }
        
    }
    
    public function getHashUrl($requestData,$hash){
        
        if($hash && $requestData['phone'] ){
    
            $link = 'https://wa.me/' . $requestData['phone'];
            $link .= '?text=' . urlencode($hash);
            return $link;
        }
        
    }
    
    public function whatsappLinkGenerator(Request $request){
        
        $rawData = $request->getContent();
        // Decode the raw JSON data
        $requestData = json_decode($rawData, true);
        
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
            return response()->json($errorResponse, 422); // Use a 422 status code for unprocessable entity
        }
        
        $encryptedData = $requestData['hash'];
       
        $getData =  DB::table('hash_qr_code')->where('hash',$encryptedData)->select('qr_code_path')->first();
        
        if($getData  != null){
            $response = [
                    'status' => 200 ,
                    'hash' => $encryptedData,
                    'path' => $getData->qr_code_path ?? '',
                    'number' => $requestData['phone'],
                ];
            return response()->json($response, 200);
        }
        
       

        $makeQrPath = $this->makeQRCode($requestData,$encryptedData);
        
        $isInserted = $this->hashDataStore($requestData,$encryptedData,$makeQrPath);
        
        
        if($requestData['phone'] && $requestData['hash']){
            
            $getData =  DB::table('hash_qr_code')->where('hash',$requestData['hash'])
                                ->select('qr_code_path')->first();
            
            if($getData != null){
                $path = $getData->qr_code_path;
            }
                
            $link = 'https://wa.me/' . $requestData['phone'];
            
            if ($requestData['hash']) {
                $link .= '?text=Hello! I want to go for the prize!! Here is my coupon: ' . urlencode($requestData['hash']);
            }
            
            if($getData && $link){
                $response = [
                    'status' => 200 ,
                    'hash' => $link ?? '',
                    'path' => $path,
                    'number' => $requestData['phone'],
                ];
                return response()->json($response, 200);
            }else{
                $response = [
                    'status' => 202,
                    'number' => $requestData['phone'],
                ];
                return response()->json($response, 200);
            }
            

        }else{
            $response = [
                'status' => 202 ,
                'error' => 'Wrong data format',
            ];
            return response()->json($response, 202);
        }
        
    }
    
    
     
   
}

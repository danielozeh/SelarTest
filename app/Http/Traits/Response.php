<?php
namespace App\Http\Traits;

/**
 * @author Daniel Ozeh hello@danielozeh.com.ng
 */

trait Response {
    public function response($status, $message, $status_code = null) {
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $status_code);
    }
}
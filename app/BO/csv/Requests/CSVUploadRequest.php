<?php

namespace App\BO\csv\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CSVUploadRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'csv_file' => 'required|file|mimes:csv',
        ];
    }

    public function messages()
    {
        return [
            'csv_file.required' => 'Please upload a CSV file.',
            'csv_file.file' => 'The uploaded file is not a valid file.',
            'csv_file.mimes' => 'The uploaded file must be a CSV file.',
        ];
    }


}

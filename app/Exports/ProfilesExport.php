<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Profile;

class ProfilesExport implements FromCollection , WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return  Profile::select('id', 'name', 'email', 'phone')->get();
        //
    }

    public function headings () : array
    {
        return ['ID', 'Name','Email' , 'Phone'];

    }

    public function map ($profile): array
    {
         return [
            $profile->id,
            $profile->name,
            $profile->email,
            $profile->phone,
        ];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportType extends Model
{

    protected $table = 'transport_types';

    protected $fillable = ['type'];

    public function getTransportTypesDropDown()
    {
        return array_combine($this->getTransportTypes(), $this->getTransportTypes());
    }

    /**
     * @return array
     */
    public function getTransportTypes(): array
    {
        return $this->transportTypes();
        // return $this->transportTypes;
    }

    /**
     * @param array $transportTypes
     */
    public function setTransportTypes(array $transportTypes): void
    {
        $this->transportTypes = $transportTypes;
    }

    public function transportTypes()
    {
        $TransportType = TransportType::all();
        return $TransportType->pluck('type')->toArray();
    }
}

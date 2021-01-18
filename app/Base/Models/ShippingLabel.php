<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Base\Models\Shipment $shipment
 * @property string $storage_path
 * @property string $file_path
 * @property string $url
 * 
 */
class ShippingLabel extends Model
{

    protected $fillable = ['uuid'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function getFilePathAttribute(): string
    {
        return storage_path('public/shipment_barcodes/' . $this->uuid . '.pdf');
    }

    public function getStoragePathAttribute(): string
    {
        return 'public/shipment_barcodes/' . $this->uuid . '.pdf';
    }

    public function getUrlAttribute(): string
    {
        return url('storage/shipment_barcodes/' . $this->uuid . '.pdf');
    }
}

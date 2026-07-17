<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SystemManagementController extends Controller
{
    public function index()
    {
        $priceTaxMode = SystemSetting::getValue('price_tax_mode', 'exclude_tax');

        return view('admin.system_management.index', compact('priceTaxMode'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'price_tax_mode' => 'required|in:exclude_tax,include_tax',
        ]);

        SystemSetting::setValue('price_tax_mode', $request->price_tax_mode);

        Cache::forget('system_setting_price_tax_mode');

        return redirect()
            ->route('admin.system-management.index')
            ->with('success', 'System settings updated successfully.');
    }
}

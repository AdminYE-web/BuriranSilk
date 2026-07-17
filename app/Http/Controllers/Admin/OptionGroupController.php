<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OptionGroupController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $language = session('admin_product_language', 'pt');
        $baseLanguage = 'pt';

        if ($language === $baseLanguage) {
            $groups = OptionGroup::with('parent')
                ->where('language', $baseLanguage)
                 ->when($type !== null && $type !== '', function ($query) use ($type) {
        $query->where('product_type', $type);
    })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('group_name', 'like', '%'.$search.'%')
                            ->orWhere('group_code', 'like', '%'.$search.'%');
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('option_group_id', 'desc')
                ->paginate(15)
                ->withQueryString();

            return view('admin.option_groups.index', compact('groups', 'search', 'language', 'type'));
        }

        $baseGroups = OptionGroup::with('parent')
            ->where('language', $baseLanguage)
             ->when($type !== null && $type !== '', function ($query) use ($type) {
        $query->where('product_type', $type);
    })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('group_name', 'like', '%'.$search.'%')
                        ->orWhere('group_code', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('sort_order')
            ->orderBy('option_group_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $translationKeys = $baseGroups
            ->getCollection()
            ->pluck('translation_key')
            ->filter()
            ->values();

        $translatedGroups = OptionGroup::with('parent')
            ->where('language', $language)
            ->whereIn('translation_key', $translationKeys)
            ->get()
            ->keyBy('translation_key');

        $baseGroups->getCollection()->transform(function ($baseGroup) use ($translatedGroups) {
            $translatedGroup = $translatedGroups->get($baseGroup->translation_key);

            if ($translatedGroup) {
                $translatedGroup->is_missing_translation = false;
                $translatedGroup->base_option_group_id = $baseGroup->option_group_id;

                return $translatedGroup;
            }

            $baseGroup->is_missing_translation = true;
            $baseGroup->base_option_group_id = $baseGroup->option_group_id;

            return $baseGroup;
        });

        $groups = $baseGroups;

        return view('admin.option_groups.index', compact('groups', 'search', 'language', 'type'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $parentGroups = OptionGroup::whereNull('parent_group_id')
            ->where('is_active', 1)
            ->where('language', $language)
            ->orderBy('sort_order')
            ->orderBy('group_name')
            ->get();

        $translationKey = 'og_'.strtolower(Str::random(12));

        return view('admin.option_groups.create', compact(
            'parentGroups',
            'language',
            'translationKey'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_code' => 'required|string|max:100|unique:option_groups,group_code',
            'group_name' => 'required|string|max:255',
            'display_type' => 'required|string|in:button,image_card,color,select_detail,image_card_variant,image_grid_compact,grouped_buttons,previous_order_design',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'help_text' => 'nullable|string',
            'parent_group_id' => 'nullable|exists:option_groups,option_group_id',
            'option_group_main' => 'nullable|boolean',
            'product_type' => 'required|in:1,2',
            'translation_key' => 'nullable|string|max:255',
        ]);

        OptionGroup::create([
            'parent_group_id' => $request->parent_group_id,
            'group_code' => $request->group_code,
            'group_name' => $request->group_name,
            'help_text' => $request->help_text,
            'is_required' => $request->has('is_required') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'display_type' => $request->display_type,
            'sort_order' => $request->sort_order ?? 0,
            'option_group_main' => $request->has('option_group_main') ? 1 : 0,
            'language' => session('admin_product_language', 'pt'),
            'product_type' => $request->product_type,
            'translation_key' => $request->translation_key ?: 'og_'.strtolower(Str::random(12)),
        ]);

        return redirect()
            ->route('admin.option-groups.index')
            ->with('success', 'เพิ่มกลุ่มตัวเลือกเรียบร้อยแล้ว');
    }

    public function edit(OptionGroup $optionGroup)
    {
        $parentGroups = OptionGroup::whereNull('parent_group_id')
            ->where('option_group_id', '!=', $optionGroup->option_group_id)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('group_name')
            ->get();
        $language = $optionGroup->language ?? session('admin_product_language', 'pt');

        return view('admin.option_groups.edit', compact('optionGroup', 'parentGroups', 'language'));
    }

    public function update(Request $request, OptionGroup $optionGroup)
    {
        $request->validate([
            'group_code' => 'required|string|max:100|unique:option_groups,group_code,'.$optionGroup->option_group_id.',option_group_id',
            'group_name' => 'required|string|max:255',
            'display_type' => 'required|string|in:button,image_card,color,select_detail,image_card_variant,image_grid_compact,grouped_buttons,previous_order_design',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'help_text' => 'nullable|string',
            'parent_group_id' => 'nullable|exists:option_groups,option_group_id',
            'option_group_main' => 'nullable|boolean',
            'product_type' => 'required|in:1,2',
            'translation_key' => 'nullable|string|max:255',
        ]);

        $optionGroup->update([
            'parent_group_id' => $request->parent_group_id,
            'group_code' => $request->group_code,
            'group_name' => $request->group_name,
            'display_type' => $request->display_type,
            'is_required' => $request->has('is_required') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'help_text' => $request->help_text,
            'option_group_main' => $request->has('option_group_main') ? 1 : 0,
            'product_type' => $request->product_type,
            'translation_key' => $request->translation_key ?: $optionGroup->translation_key ?: $optionGroup->group_code,
        ]);

        return redirect()
            ->route('admin.option-groups.index')
            ->with('success', 'updated');
    }

    public function destroy(OptionGroup $optionGroup)
    {
        $optionGroup->delete();

        return redirect()
            ->route('admin.option-groups.index')
            ->with('success', 'deleted');
    }

    public function duplicateTranslation(OptionGroup $optionGroup)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.option-groups.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($optionGroup->language !== 'pt') {
            return redirect()
                ->route('admin.option-groups.index')
                ->with('success', 'Only PT option group can be duplicated as translation.');
        }

        $translationKey = $optionGroup->translation_key ?: $optionGroup->group_code;

        $existing = OptionGroup::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.option-groups.edit', $existing->option_group_id)
                ->with('success', 'Translation already exists.');
        }

        $newGroup = $optionGroup->replicate();

        $newGroup->language = $targetLanguage;
        $newGroup->translation_key = $translationKey;
        $newGroup->group_code = $optionGroup->group_code.'-'.$targetLanguage;
        $newGroup->group_name = $optionGroup->group_name.' ('.strtoupper($targetLanguage).')';
        $newGroup->is_active = 0;
        $newGroup->created_at = now();
        $newGroup->updated_at = now();

        /*
    |--------------------------------------------------------------------------
    | Parent group translation
    |--------------------------------------------------------------------------
    | ถ้า parent ของ PT มีตัวแปลในภาษาปัจจุบันแล้ว ให้ผูก parent เป็นตัวแปลนั้น
    | ถ้ายังไม่มี ให้ปล่อยเป็น null ก่อน เพื่อกัน parent ผิดภาษา
    |--------------------------------------------------------------------------
    */
        if ($optionGroup->parent_group_id) {
            $parent = OptionGroup::find($optionGroup->parent_group_id);

            if ($parent) {
                $parentTranslationKey = $parent->translation_key ?: $parent->group_code;

                $translatedParent = OptionGroup::where('translation_key', $parentTranslationKey)
                    ->where('language', $targetLanguage)
                    ->first();

                $newGroup->parent_group_id = $translatedParent->option_group_id ?? null;
            }
        }

        $newGroup->save();

        return redirect()
            ->route('admin.option-groups.edit', $newGroup->option_group_id)
            ->with('success', 'Option group duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }
}

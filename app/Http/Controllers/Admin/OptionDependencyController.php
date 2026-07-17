<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OptionDependency;
use App\Models\OptionGroup;
use App\Models\ProductOption;
use Illuminate\Http\Request;

class OptionDependencyController extends Controller
{
    public function index()
    {
        $language = session('admin_product_language', 'pt');
        $baseLanguage = 'pt';

        if ($language === $baseLanguage) {
            $dependencies = OptionDependency::with([
                'parentOption.group',
                'targetGroup',
                'targetOption.group',
            ])
                ->whereHas('parentOption', function ($query) use ($baseLanguage) {
                    $query->where('language', $baseLanguage);
                })
                ->orderBy('dependency_id', 'desc')
                ->paginate(20);

            return view('admin.option_dependencies.index', compact('dependencies', 'language'));
        }

        $baseDependencies = OptionDependency::with([
            'parentOption.group',
            'targetGroup',
            'targetOption.group',
        ])
            ->whereHas('parentOption', function ($query) use ($baseLanguage) {
                $query->where('language', $baseLanguage);
            })
            ->orderBy('dependency_id', 'desc')
            ->paginate(20);

        $baseDependencies->getCollection()->transform(function ($dependency) use ($language) {
            $dependency->is_missing_translation = true;
            $dependency->base_dependency_id = $dependency->dependency_id;

            $parentOption = $dependency->parentOption;

            if (! $parentOption || ! $parentOption->translation_key) {
                return $dependency;
            }

            $translatedParentOption = ProductOption::where('translation_key', $parentOption->translation_key)
                ->where('language', $language)
                ->first();

            if (! $translatedParentOption) {
                return $dependency;
            }

            $translatedTargetGroup = null;
            $translatedTargetOption = null;

            if ($dependency->target_type === 'group') {
                $targetGroup = $dependency->targetGroup;

                if (! $targetGroup || ! $targetGroup->translation_key) {
                    return $dependency;
                }

                $translatedTargetGroup = OptionGroup::where('translation_key', $targetGroup->translation_key)
                    ->where('language', $language)
                    ->first();

                if (! $translatedTargetGroup) {
                    return $dependency;
                }
            }

            if ($dependency->target_type === 'option') {
                $targetOption = $dependency->targetOption;

                if (! $targetOption || ! $targetOption->translation_key) {
                    return $dependency;
                }

                $translatedTargetOption = ProductOption::where('translation_key', $targetOption->translation_key)
                    ->where('language', $language)
                    ->first();

                if (! $translatedTargetOption) {
                    return $dependency;
                }
            }

            $existingQuery = OptionDependency::with([
                'parentOption.group',
                'targetGroup',
                'targetOption.group',
            ])
                ->where('parent_option_id', $translatedParentOption->option_id)
                ->where('target_type', $dependency->target_type)
                ->where('action_type', $dependency->action_type);

            if ($dependency->target_type === 'group') {
                $existingQuery->where('target_group_id', $translatedTargetGroup->option_group_id);
            }

            if ($dependency->target_type === 'option') {
                $existingQuery->where('target_option_id', $translatedTargetOption->option_id);
            }

            $translatedDependency = $existingQuery->first();

            if ($translatedDependency) {
                $translatedDependency->is_missing_translation = false;
                $translatedDependency->base_dependency_id = $dependency->dependency_id;

                return $translatedDependency;
            }

            return $dependency;
        });

        $dependencies = $baseDependencies;

        return view('admin.option_dependencies.index', compact('dependencies', 'language'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $options = ProductOption::with('group')
            ->where('language', $language)
            ->where('is_active', 1)
            ->whereHas('group', function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->orderBy('option_group_id')
            ->orderBy('option_name')
            ->get();

        $groups = OptionGroup::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('group_name')
            ->get();

        return view('admin.option_dependencies.create', compact('options', 'groups', 'language'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_option_id' => 'required|exists:product_options,option_id',
            'target_type' => 'required|in:group,option',
            'target_group_id' => 'nullable|required_if:target_type,group|exists:option_groups,option_group_id',
            'target_option_id' => 'nullable|required_if:target_type,option|exists:product_options,option_id',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'action_type' => 'required|in:show,hide,disable',
        ]);

        OptionDependency::create([
            'parent_option_id' => $request->parent_option_id,

            // เก็บ child_option_id ไว้ด้วย เพื่อรองรับโครงเดิม
            'child_option_id' => $request->target_type === 'option'
                ? $request->target_option_id
                : null,

            'target_type' => $request->target_type,
            'target_group_id' => $request->target_type === 'group'
                ? $request->target_group_id
                : null,
            'target_option_id' => $request->target_type === 'option'
                ? $request->target_option_id
                : null,

            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'action_type' => $request->action_type,
        ]);

        return redirect()
            ->route('admin.option-dependencies.index')
            ->with('success', 'Option dependency created successfully.');
    }

    public function edit(OptionDependency $optionDependency)
    {
        $optionDependency->load([
            'parentOption',
            'targetOption',
            'targetGroup',
        ]);

        $language = $optionDependency->parentOption->language
            ?? session('admin_product_language', 'pt');

        $options = ProductOption::with('group')
            ->where('language', $language)
            ->where('is_active', 1)
            ->whereHas('group', function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->orderBy('option_group_id')
            ->orderBy('option_name')
            ->get();

        $groups = OptionGroup::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('group_name')
            ->get();

        return view('admin.option_dependencies.edit', [
            'dependency' => $optionDependency,
            'options' => $options,
            'groups' => $groups,
            'language' => $language,
        ]);
    }

    public function update(Request $request, OptionDependency $optionDependency)
    {
        $request->validate([
            'parent_option_id' => 'required|exists:product_options,option_id',
            'target_type' => 'required|in:group,option',
            'target_group_id' => 'nullable|required_if:target_type,group|exists:option_groups,option_group_id',
            'target_option_id' => 'nullable|required_if:target_type,option|exists:product_options,option_id',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'action_type' => 'required|in:show,hide,disable',
        ]);

        $optionDependency->update([
            'parent_option_id' => $request->parent_option_id,

            'child_option_id' => $request->target_type === 'option'
                ? $request->target_option_id
                : null,

            'target_type' => $request->target_type,
            'target_group_id' => $request->target_type === 'group'
                ? $request->target_group_id
                : null,
            'target_option_id' => $request->target_type === 'option'
                ? $request->target_option_id
                : null,

            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'action_type' => $request->action_type,
        ]);

        return redirect()
            ->route('admin.option-dependencies.index')
            ->with('success', 'Option dependency updated successfully.');
    }

    public function duplicateTranslation(OptionDependency $optionDependency)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.option-dependencies.index')
                ->with('success', 'You are already in PT language.');
        }

        $optionDependency->load([
            'parentOption',
            'targetOption',
            'targetGroup',
        ]);

        $parentOption = $optionDependency->parentOption;

        if (! $parentOption || $parentOption->language !== 'pt') {
            return redirect()
                ->route('admin.option-dependencies.index')
                ->with('success', 'Only PT dependency can be duplicated as translation.');
        }

        if (! $parentOption->translation_key) {
            return redirect()
                ->route('admin.option-dependencies.index')
                ->with('success', 'Parent option has no translation key.');
        }

        $translatedParentOption = ProductOption::where('translation_key', $parentOption->translation_key)
            ->where('language', $targetLanguage)
            ->first();

        if (! $translatedParentOption) {
            return redirect()
                ->route('admin.option-dependencies.index')
                ->with('success', 'Please duplicate the parent option first.');
        }

        $targetGroupId = null;
        $targetOptionId = null;

        if ($optionDependency->target_type === 'group') {
            $targetGroup = $optionDependency->targetGroup;

            if (! $targetGroup || ! $targetGroup->translation_key) {
                return redirect()
                    ->route('admin.option-dependencies.index')
                    ->with('success', 'Target group has no translation key.');
            }

            $translatedTargetGroup = OptionGroup::where('translation_key', $targetGroup->translation_key)
                ->where('language', $targetLanguage)
                ->first();

            if (! $translatedTargetGroup) {
                return redirect()
                    ->route('admin.option-dependencies.index')
                    ->with('success', 'Please duplicate the target group first.');
            }

            $targetGroupId = $translatedTargetGroup->option_group_id;
        }

        if ($optionDependency->target_type === 'option') {
            $targetOption = $optionDependency->targetOption;

            if (! $targetOption || ! $targetOption->translation_key) {
                return redirect()
                    ->route('admin.option-dependencies.index')
                    ->with('success', 'Target option has no translation key.');
            }

            $translatedTargetOption = ProductOption::where('translation_key', $targetOption->translation_key)
                ->where('language', $targetLanguage)
                ->first();

            if (! $translatedTargetOption) {
                return redirect()
                    ->route('admin.option-dependencies.index')
                    ->with('success', 'Please duplicate the target option first.');
            }

            $targetOptionId = $translatedTargetOption->option_id;
        }

        $existingQuery = OptionDependency::where('parent_option_id', $translatedParentOption->option_id)
            ->where('target_type', $optionDependency->target_type)
            ->where('action_type', $optionDependency->action_type);

        if ($optionDependency->target_type === 'group') {
            $existingQuery->where('target_group_id', $targetGroupId);
        }

        if ($optionDependency->target_type === 'option') {
            $existingQuery->where('target_option_id', $targetOptionId);
        }

        $existing = $existingQuery->first();

        if ($existing) {
            return redirect()
                ->route('admin.option-dependencies.edit', $existing->dependency_id)
                ->with('success', 'Translation dependency already exists.');
        }

        $newDependency = OptionDependency::create([
            'parent_option_id' => $translatedParentOption->option_id,

            'child_option_id' => $optionDependency->target_type === 'option'
                ? $targetOptionId
                : null,

            'target_type' => $optionDependency->target_type,
            'target_group_id' => $optionDependency->target_type === 'group'
                ? $targetGroupId
                : null,
            'target_option_id' => $optionDependency->target_type === 'option'
                ? $targetOptionId
                : null,

            'is_active' => 0,
            'sort_order' => $optionDependency->sort_order,
            'action_type' => $optionDependency->action_type,
        ]);

        return redirect()
            ->route('admin.option-dependencies.edit', $newDependency->dependency_id)
            ->with('success', 'Option dependency duplicated for '.strtoupper($targetLanguage).'. Please review and activate it.');
    }

    public function destroy(OptionDependency $optionDependency)
    {
        $optionDependency->delete();

        return redirect()
            ->route('admin.option-dependencies.index')
            ->with('success', 'Option dependency deleted successfully.');
    }
}
